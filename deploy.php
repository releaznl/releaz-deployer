<?php

namespace Deployer;

require 'recipe/yii.php';                       // The base recipe for Yii(2) applications
require 'recipe/releaz/deployment/yii.php';     // Custom Yii tasks implementation
require 'recipe/releaz/deployment/files.php';   // Custom Files tasks implementation
require 'recipe/releaz/deployment/migrate.php'; // Custom Migrate tasks implementation
require 'recipe/releaz/deployment/sync.php';    // Custom Sync tasks implementation

use Symfony\Component\Yaml\Yaml;                // Require YAML to read config file.

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
