---
layout: default
---

Om deployment servers, stages, branches en dergelijken te configureren moet er een `deploy-config.yml`
bestand worden aangemaakt. In dit bestand worden alle 'secrets' gezet. Zorg ervoor, dat wanneer je een 
public repository gebruikt, dit bestand niet wordt meegepushed.

# Definieren
Om te beginnen moet er een `deploy-config.yml` bestand aangemaakt worden. Dit kan doormiddel van 
het dupliceren van het voorbeeld bestand. Wanneer dit niet mogelijk is, gebruik dan het volgende
voorbeeld of [download](assets/deploy-config.yml.example) het orginele bestand.

## Algemeen

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

```
In het bovenstaande (gedeeltelijke) example is te zien hoe een stage gedefinieerd wordt.
- Allereerst wordt een betreffende repository opgegeven. Deze repository mag zowel private als public zijn.
- Daarna kunnen de server settings worden gedefinieerd;
    - Allereerst moet de host worden gedefinieerd. De host is het adres waarop de remote server 
     te bereiken is. Dit kan zowel een ip-adres als dns naam zijn. Het is belangrijk dat een hostnaam maar een keer 
     gebruikt mag worden tussen elke stage. Er kunnen dus niet meerdere stages zijn die 'applicationname.com' als 
     hostname hebben. 
     - Daarna kan de stage worden opgegeven. De stage is de key die je gaat gebruiken bij het oproepen van 
     een actie. Bijvoorbeeld in dit voorbeeld zou je voor het deployen `vender/bin/dep deploy-yii production` moeten 
     gebruiken.
     - Als derde wordt er een branchnaam opgegeven. Deze branch wordt gebruikt om als basis te gebruiken 
     voor het deployen. 
     - Hierna wordt een zogenoemd deploy_path opgegeven. Dit is de locatie waar Deployer zijn bestanden
     en mappen mag plaatsen.
     - Als laatste wordt er een SSH gebruikersnaam opgegeven. Dit is de naam die je gebruikt om 
     via SSH toegang te krijgen naar de server.

Volgorde van bovenstaande keys zijn niet van belang.

## Instellingen

```yaml
    [...]
    username: '.....'
    settings:
      yii:
        init: 'Development'                           # Environment that can be used. See `php init` for possibilities
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

Om stages meer opties te geven, kan het volgende onder de SSH username worden geplaatst. In deze situatie 
zal er tijdens het deployen het volgende gebeuren:
- Een Yii applicatie zal in 'Development' environment worden ingesteld. De deployer voert `php init` op 
de remote server uit en zal dit doen met de 'Development' optie.
- RBAC migraties zullen worden uitgevoerd.
- Database migraties zullen worden uitgevoerd.
- De map `projectroot/shared/uploads` zal worden gesynchroniseerd met `deploypath/shared/uploads`. En deze 
zal worden aangemaakt wanneer deze niet aanwezig is.
- Het bestand 'common/config/config.yaml' zal worden gezien als shared resources.

# Meer opties:
Wanneer het bestand wordt ingesteld. Houdt dan rekening met de volgende restricties en mogelijkheden:

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
`files:upload-files` | Paths to files that needs to be uploaded to the remote server to the same location (paths are seen from project folder).  | No
`files:show` | Shows the content of a file. Prefixed with the release_path. | No
`migrate:rbac` | Migrates the RBAC functionality of Yii2. | No
`migrate:db` | Migrates the 'normal' database migrations | No
`sync:*` | Special feature for syncing remote files with for example a shared folder. That way developers can maintain shared files and sync them to the remote server, without loss of user created files. The uploads key is required when using this functionality, but only used for visual purpose. (rsync) | No
`sync:source` | Path to folder (from project root) | When using sync option -> Yes, else no.
`sync:dest` | Destination path (from deploy path) | When using sync option -> Yes, else no.
`sync:create_if_not_exists` | Create the destination folder if it doesn't exist. | No
`shared` | Shared entities that need to be placed in the shared folder | No |


[Ga naar deployen](deploy)