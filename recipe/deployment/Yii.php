<?php

namespace Recipe\deployment;

use function Deployer\before;
use function Deployer\desc;
use function Deployer\get;
use Deployer\Helpers\ArrayHelper;
use function Deployer\run;
use function Deployer\task;

class Yii
{

    public function __construct()
    {
        desc('Deploys a Yii2 application, complete with given settings.');
        task('deploy-yii', [
            'deploy:prepare',
            'deploy:lock',
            'deploy:release',
            'deploy:update_code',
            'deploy:vendors',
            'deploy:shared',
            'deploy:symlink',
            'deploy:unlock',
            'cleanup',
        ]);

        task('deploy-custom', [
            'files',
            'sync',
            'deploy:shared',
            'deploy-yii:init',
            'migrate'
        ]);
        before('deploy:symlink', 'deploy-custom');

        desc("Inits the remote application with the value of init section");
        task('deploy-yii:init', function () {
            $init = ArrayHelper::get(get('settings'), ['yii', 'init']);
            if (!empty($init)) {
                $this->initYii($init);
            }
        });

    }

    /**
     * Initializes Yii with the given environment string (remotely)
     *
     * @param string $environment
     */
    public function initYii(string $environment)
    {
        run("cd {{release_path}} && php init --env={$environment} --overwrite=All");
    }
}


