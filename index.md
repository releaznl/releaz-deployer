---
layout: default
---

# Opzetten van de Deployer

## SSH initialisatie:
- Zorg ervoor dat je SSH toegang hebt naar de deployment server. Zie [hier](ssh) voor meer.

## Deployer installatie:
- Download / installeer [composer](https://getcomposer.org/download/) als dit nog niet beschikbaar is binnen het project.
    - Dit kan met de volgende commando's
       - `php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"`
       - `php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"`
       - `php composer-setup.php`
       - `php -r "unlink('composer-setup.php');"`
- Installeer de deployer als een development dependency d.m.v. : 
  - `php composer.phar require releaz/deployer ~1.2.4 --dev`
- Installeer restrerende packages met `php composer.phar install` als dat nog nodig is.
- De deployer is vanaf nu beschikbaar vanuit je vendor map. Doordat het gebruik maakt van [Deployer](https://deployer.org/) 
kun je de deployer gewoon starten d.m.v. :
   - `vendor/bin/dep`

### Let op!
Gebruik altijd een minimale versie van de Deployer. Raadpleeg de repository voor de 
laatste versie.

# Het initialiseren van een een deploybare applicaties

- Voer `vendor/bin/dep init` uit.
- Kies de 'Releaz' template voor Yii2 applicaties

> ![useful image](assets/image1.png)

- Controlleer de repository.
- Verstuur eventueel anonieme development data naar Deployer.
- Wanneer alles goed is gegaan, worden er twee bestanden aangemaakt: `deploy.php` en deploy-config.yml.example

# Gegenereerde bestanden
Tijdens het initialisatieproces zijn er twee bestanden aangemaakt. Deze bestanden 
hebben de volgende eigenschappen:

### deploy.php

```php
<?php

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


/**
 * Loading all relevant recipe tasks and functions:
 */
new Yii();
new Sync();
new Files();
new Migrate();


/**
 * Load the configuration file and extract the content:
 */
$yaml = YamlExtractor::parse(__DIR__ . "/deploy-config.yml");

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
        ->set('settings', YamlExtractor::extract($host, 'settings'))
        ->set('shared_files', YamlExtractor::extract($host, 'shared'));
}

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
```
Dit bestand is de kern van de deployer. Het bestand is geoptimaliseerd voor het laden van 
gegeven configuraties. Hierop staan geen zogenoemde 'secrets' en kan dus gerust meegepushed 
worden naar de repository. Hierdoor hoeft niet iedereen in het team Deployer te initialiseren.

Klik [hier]() voor meer informatie over dit bestand.

### deploy-config.yml.example

```yaml
# General information:
general:
  ssh_repo_url: 'git@github.com:johankladder/releaz-deployer.git'                          # The repository your project is stored

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

[...]
```

Dit bestand is een voorbeeldbestand van de deployment configuratie. Dit bestand kan worden 
overgenomen en naar eigen inbreng worden ingevuld. Een 'echte' versie van dit bestand wordt gebruikt in 
de deploy.php.

Klik [hier](configuration.md) voor meer informatie over dit bestand.

----------------------------------------------------------------------------------------------

[Ga naar configuratie](configuration)