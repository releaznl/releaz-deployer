<?php

namespace Deployer;

// TODO:10 Hardcode all the tasks that needs to be executed in the right order. id:1
desc('Deploys a Yii2 application, complete with given settings.');
task('deploy-yii', [
    'deploy',
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
        init_yii($init);
    }
});

function init_yii($environment)
{
    run("cd {{release_path}} && php init --env={$environment} --overwrite=All");
}
