<?php


namespace Recipe\deployment;

use function Deployer\before;
use function Deployer\desc;
use function Deployer\get;
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
            $init = get('settings')['yii']['init'];
            if ($init) {
                $this->init_yii($init);
            }
        });

    }

    public function init_yii($environment)
    {
        run("cd {{release_path}} && php init --env={$environment} --overwrite=All");
    }
}


