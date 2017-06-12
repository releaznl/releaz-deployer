<?php
/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 13:05
 */


namespace Deployer;

require __DIR__ . '/vendor/deployer/deployer/recipe/common.php'; // Require the common tasks.

use Recipe\deployment\Files;
use Recipe\deployment\Migrate;
use Recipe\deployment\Yii;
use Recipe\deployment\Sync;
use Symfony\Component\Yaml\Yaml;


$recipes = [
    new Yii(),
    new Sync(),
    new Files(),
    new Migrate()
];


// Configuration
$yaml = Yaml::parse(file_get_contents(__DIR__ . "/deploy-config.yml"));

$general = $yaml['general'];
set('repository', $general['ssh_repo_url']);
set('git_tty', true); // [Optional] Allocate tty for git on first deployment

// Hosts
foreach ($yaml['server'] as $host) {
    host($host['host'])
        ->user($host['ssh_user'])
        ->forwardAgent()
        ->stage($host['stage'])
        ->set('branch', $host['branch'])
        ->set('deploy_path', $host['deploy_path'])
        ->set('settings', $host['settings'])
        ->set('shared_files', $host['shared']);
}

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


