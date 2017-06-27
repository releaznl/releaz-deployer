Configurable Deployment Tool (Based on Deployer)
======================================

Build status: [![Build Status](https://travis-ci.org/johankladder/releaz-deployer.svg?branch=master)](https://travis-ci.org/johankladder/releaz-deployer)

Build status [Deployer](https://github.com/deployphp/deployer): [![Build Status](https://travis-ci.org/deployphp/deployer.svg?branch=master)](https://travis-ci.org/deployphp/deployer)

Docs: https://releaznl.github.io/releaz-deployer

Packagist: https://packagist.org/packages/releaz/deployer

--------------------------------------

This tool is created for easy Yii2 deployment with the help of Deployer. In this 
tool it's easy to configure deployment variables and share them between your colleagues. 

## Deployment:
Deploying is done with the help of [Deployer](https://github.com/deployphp/deployer). 
To use this dependency, please require the following in your composer.json file.
- `composer require releaz/deployer`

Afterwards initialize Deployer by calling `vendor/bin/dep init` and choose the 'Releaz' template. 
Doing so will generate an example config and the required Deployer deploy.php file. Please look below
for more information on how to configure this file. Create the Yii2 configutation file by duplicating the generated .example config file in the common folder. By default the deploy.php file looks for ``deploy-config.yml``

The `deploy.php` file can be edited in the same manner as Deployer. Please visit the Deployer [documentation](https://deployer.org/docs) for more help.

### Explanation:
```yaml
# General information:
general:
  ssh_repo_url: 'git@github.com:johankladder/yii2-app-advanced.git'   # The repository your project is stored

# Staging servers:
server:
  # The production server
  production:
    host: 'applicationname.com'                       # Deployment server hostname/ip
    stage: 'production'                               # Stage name; can be used by 'dep deploy-yii [stage]
    branch: 'master'                                  # The branch that should be used to deploy
    deploy_path: '/var/www/applicationname.com'       # The deploy location
    ssh_user: 'username'                              # The SSH username, that has access to the remote server

  # The development server
  development:
    host: 'dev.applicationname.com'                   # Deployment server hostname/ip
    stage: 'development'                              # Stage name; can be used by 'dep deploy-yii [stage]
    branch: 'develop'                                 # The branch that should be used to deploy
    deploy_path: '/var/www/dev.applicationname.com'   # The deploy location
    ssh_user: 'username'                              # The SSH username, that has access to the remote server

  # A custom deployment server:
  custom:
    host: 'localhost'                                 # Deployment server hostname/ip
    stage: 'test'                                     # Stage name; can be used by 'dep deploy-yii [stage]
    branch: 'develop'                                 # The branch that should be used to deploy
    deploy_path: '/var/www/test.local'                # The deploy location
    ssh_user: 'johankladder'                          # The SSH username, that has access to the remote server
    settings:
      yii:
        init: 'Development'                           # Environment that can be used. See `php init` for possibilities
        overwrite: 'All'                              # Overwrite all the generated files during init | Can also be None
      files:
        upload-files:
          - 'common/config/afile.yml'                 # A file that needs the be send to the remote server
        show:
          - 'common/config/afile.yml'                 # A file that needs to be outputted when deploying
      migrate:
        rbac: true                                    # Execute RBAC migrations
        db: true                                      # Execute `yii migrate`
      sync:
        uploads:
          source: 'shared/uploads/'                   # Base of the sync folder
          dest: 'shared/uploads'                      # 'To' pathname from base deployment path
          create_if_not_exists: true                  # Create the 'To' path if not exist
    shared:
      - 'common/config/config.yaml'


```

In the server section you can add different amount of stages. The keys that are given, are not used by Deployer. Explanation:

Key | Explanation | Required
--- | --- | ---
`host:` | The server host address (Where should the stage be deployed to) | Yes
`stage:` | The name of the stage. (This stage name can be used when using `dep deploy [stagename]`) | Yes
`branch:` | The branch that the stage contains. (This is the branch that will be pulled on to the remote server) | Yes
`deploy_path:` | The path where the sources should be pulled on the remote server. (Should always be absolute) | Yes
`ssh_user:` | The user that is needed for logging in at the remote server. | Yes
`settings:` | Contains specific settings for the given stage. | No
`yii/init:` | The initialisation enviromnent for Yii2 apps. In a default situation this can be 'Development' or 'Production'. | No
`yii/overwrite:` | Overwrite all the files when perform a php init (can be 'All' | 'None') | No (All are overwritten)
`files:upload_files` | Paths to files that needs to be uploaded to the remote server to the same location (paths are seen from project folder).  | No
`files:show` | Shows the content of a file. Prefixed with the release_path. | No
`migrate:rbac` | Migrates the RBAC functionality of Yii2. | No
`migrate:db` | Migrates the 'normal' database migrations | No
`sync:*` | Special feature for syncing remote files with for example a shared folder. That way developers can maintain shared files and sync them to the remote server, without loss of user created files. The uploads key is required when using this functionality, but only used for visual purpose. (rsync) | No
`sync:source` | Path to folder (from project root) | When using sync option -> Yes, else no.
`sync:dest` | Destination path (from deploy path) | When using sync option -> Yes, else no.
`sync:create_if_not_exists` | Create the destination folder if it doesn't exist. | No
`shared` | Shared entities that need to be placed in the shared folder | No |

### Passwords
The module uses forward-agent to grant your user access to the remote server without the need of a password. Follow the following steps to ensure no password is needed:
  - Try `ssh-add -L` to see if your public key is added to the agent. If not run: `ssh-add`
  - Copy your public key to the remote server's known-hosts with `ssh-copy-id remoteusername@remotehost`
  - Try `ssh remoteuser@remotehost`. The server should no longer ask for a password as it is now provided by the agent.
  
### Remote server access repository
To give the deployment server access to your private/public repository on Github, please 
provide the server's public SSH key as a deployment key in your repository settings.
  
