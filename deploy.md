---
layout: default
---

Als referentie nemen we een voorbeeld met de volgende stage in de configuratie:

```yaml
  production:
    host: 'applicationname.com'                   
    stage: 'production'                              
    branch: 'master'                                
    deploy_path: '/var/www/applicationname.com'      
    ssh_user: 'username'
    settings:
          yii:
            init: 'Development'                         
          migrate:
            rbac: true                                  
            db: true                                     
          sync:
            uploads:
              source: 'shared/uploads/'                 
              dest: 'shared/uploads'                    
              create_if_not_exists: true                  
        shared:
          - 'common/config/config.yaml'
```

# Uitvoeren
Om deze deployment uit te voeren, zullen we de volgende commando moeten uitvoeren:

- `vendor/bin/dep deploy-yii production`

Wanneer het bovenstaande commando is uitgevoerd, zal Deployer contact maken met de deployment 
server op locatie 'applicationname.com' met als identificatiegebruiker 'username'. Daarna zal de 
externe server de sources pullen van de master branch en deze plaatsen in 'var/www/applicationname'

# Omgeving indeling:
![useful image]({{ site.url }}/assets/deploy-env.png)

Hier is te zien hoe de de deployment omgeving er uit ziet. Deze omgeving bestaat uit drie 
verschillende componenten:

## releases
In de `releases` folder staan maximaal zes instanties van deployments. Dit houdt in dat in bijvoorbeeld 
`releases/10` alle sources van de 10e release staan en in `releases/11` alle sources van de 11e release. 
Hierdoor is het mogelijk om snel rollback te toen met Deployer. [Lees meer]()

## shared
In de shared folder staat alle 'shared' content. D.w.z. dat hier alleen bestanden in staan 
die worden gebruikt door alle deployments. Denk hierbij aan bijvoorbeeld secrets en dergelijken.
Om deze secrets in te vullen moet men 1 keer handmatig inloggen op de server en bijvoorbeeld 
 wachtwoorden toevoegen aan het `shared/common/config/config.yaml` bestand. Vervolgens zal 
 Deployer hier met de volgende deploy rekening mee houden. [Lees meer]()
 
## current
De current folder verwijst altijd naar de laatste deploy. Verwijs eventueel de public_html 
folder dus ook altijd door naar sources binnen deze map.
 
 > Om een public_html door te verwijzen naar de `current` folder, is het noodzakelijk dat de 
 >`public_html` folder eerst wordt verwijderd. Daarna kan er `ln -s current/path/to/docroot public_html` 
 > worden uitgevoerd. Hierdoor is `public_html` geen folder meer, maar een symlink en verwijst deze 
 > altijd naar de nieuwste versie van de deployments.