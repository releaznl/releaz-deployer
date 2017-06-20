Configurable Deployment Tool (Based on Deployer)
======================================

Docs: https://johankladder.github.io/releaz-deployer

Packagist: https://packagist.org/packages/releaz/deployer

--------------------------------------

This tool is created for easy Yii2 deployment with the help of Deployer. In this 
tool it's easy to configure deployment variables and share them between your colleagues. 

## Deployment:
Deploying is done in with the help of [Deployer](https://github.com/deployphp/deployer). 
To use this dependency, please require this in your composer.json file.
- `composer require releaz/deployer`

Then initialize Deployer by calling `vendor/bin/dep init` and choose the `Releaz' template. 
Then and example config file is created with the deploy.php file. Please look below
for more information about the config file. Please contruct the config file from duplicating the 
example. The deploy.php file is looking default for ``deploy-config.yml``

The `deploy.php` file can be edited in the same way as Deployer works. Please visit the docs 
of Deployer for more help.

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

  # An custom deployment server:
  custom:
    host: 'localhost'                                 # Deployment server hostname/ip
    stage: 'test'                                     # Stage name; can be used by 'dep deploy-yii [stage]
    branch: 'develop'                                 # The branch that should be used to deploy
    deploy_path: '/var/www/test.local'                # The deploy location
    ssh_user: 'johankladder'                          # The SSH username, that has access to the remote server
    settings:
      yii:
        init: 'Development'                           # Environment that can be used. See `php init` for possibilities
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
`branch:` | The branch that the stage contains. (This is the branch that will be pulled on the remote server) | Yes
`deploy_path:` | The path where the sources should be pulled on the remote server. (Should always be absolute) | Yes
`ssh_user:` | The user that is needed for logging in at the remote server. | Yes
`settings:` | Contains specific settings for the given stage. | No
`yii/init:` | The initialisation enviromnent for Yii2 apps. In an default situation this can be 'Development' or 'Production'. | No
`files:upload_files` | Paths to files that needs to be uploaded to the remote server to the same location (paths are seen from project folder).  | No
`files:show` | Shows the content of an file. Prefixed with the release_path. | No
`migrate:rbac` | Migrates the RBAC functionality of Yii2. | No
`migrate:db` | Migrates the 'normal' database migrations | No
`sync:*` | Special feature for syncing remote files with for example an shared folder. That way developers can maintain shared files and sync them to the remote server, without loss of user created files. The uploads key is required when using this functionality, but only used for visual purpose. (rsync) | No
`sync:source` | Path to folder (from project root) | When using sync option -> Yes, else no.
`sync:dest` | Destination path (from deploy path) | When using sync option -> Yes, else no.
`sync:create_if_not_exists` | Create the destination folder if not exists. | No
`shared` | Shared entities that need to be placed in the shared folder | No |

### Passwords
Mentioned that no passwords are asked to login with SSH? The module is using forward-agent. To ensure your user has access to the remote server with forward-agent and no passwords are asked:
  - Try `ssh-add -L` to see if your public key is added to the agent. If not run: `ssh-add`
  - Copy your public key to the remote server's known-hosts with `ssh-copy-id remoteusername@remotehost`
  - Try `ssh remoteuser@remotehost`. Now no password should be asked as it is inside your agent.
  
### Remote server access repository
To give the deployment server access to your private/public repository on Github, please 
provide the server's public SSH key as an deployment key in your repository settings.
  
