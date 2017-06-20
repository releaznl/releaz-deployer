---
layout: default
---

Deployer maakt gebruik van SSH. Om te zorgen dat deployment goed verloopt via SSH, moeten er 
een aantal dingen ingesteld worden.

## Instellingen

- Zorg ervoor dat je een public key gemaakt hebt. [Help](https://help.github.com/articles/connecting-to-github-with-ssh/)
- Kopieer deze key naar de deployment server d.m.v. `ssh-copy-id remoteusername@remotelocation` en 
volg de stappen.
- Voeg je public key toe aan je [agent](https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/#adding-your-ssh-key-to-the-ssh-agent) of 
voer alleen `ssh-add` uit.
- Probeer via SSH in te loggen op de server. Gelukt? Mooi.

## Server's SSH

Om sources te kunnen pullen van bijvoorbeeld github, moet de server zijn public key worden toegoevoegd 
aan de deployments keys van de repository. Dit kun je bij de repository settings instellen.

Wanneer deze correct is, kan de server pullen van de repository. 

