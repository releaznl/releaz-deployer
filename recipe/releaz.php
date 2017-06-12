<?php
/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 13:05
 */


namespace Deployer;

/**
 * Require the common task from the original Deployer folder:
 */
require __DIR__ . '/vendor/deployer/deployer/recipe/common.php'; // Require the common tasks.

use Deployer\Helpers\YamlExtractor;
use Recipe\deployment\Files;
use Recipe\deployment\Migrate;
use Recipe\deployment\Sync;
use Recipe\deployment\Yii;
use Symfony\Component\Yaml\Yaml;


/**
 * Loading all relevant recipe tasks and functions:
 */
$recipes = [
    new Yii(),
    new Sync(),
    new Files(),
    new Migrate()
];

/**
 * Load the configuration file and extract the content:
 */
$yaml = Yaml::parse(file_get_contents(__DIR__ . "/deploy-config.yml"));

$general = YamlExtractor::extract($yaml, 'general', true);
$repo = YamlExtractor::extract($general, 'ssh_repo_url', true);
$server = YamlExtractor::extract($yaml, 'server', true);

set('repository', $repo);
set('git_tty', true); // [Optional] Allocate tty for git on first deployment

/**
 * Define all the hosts:
 */
foreach ($server as $host) {
    host(YamlExtractor::extract($host, 'host', true))
        ->user(YamlExtractor::extract($host, 'ssh_user', true))
        ->forwardAgent()
        ->stage(YamlExtractor::extract($host, 'stage', true))
        ->set('branch', YamlExtractor::extract($host, 'branch', true))
        ->set('deploy_path', YamlExtractor::extract($host, 'deploy_path', true))
        ->set('settings', YamlExtractor::extract($host, 'settings', true))
        ->set('shared_files', YamlExtractor::extract($host, 'shared'));
}

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


