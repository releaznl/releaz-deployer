<?php
/**
 * Created by PhpStorm.
 * User: johankladder
 * Date: 12-6-17
 * Time: 13:05
 */


namespace Deployer;

require __DIR__ . '/vendor/deployer/deployer/recipe/common.php'; // Require the common tasks.

// Add php files containing custom tasks
require 'recipe/deployment/yii.php';
require 'recipe/deployment/sync.php';
require 'recipe/deployment/migrate.php';
require 'recipe/deployment/files.php';

use Symfony\Component\Yaml\Yaml;

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


