---
layout: default
---

# FAQ

---

- Deployment path is incorrect:
    - De key: 'deploy_path' moet een absolute path bevatten naar de locatie waar je de Deployer content 
    wil hebben. Een handige manier om dit te kunnen weten is om op de server (wanneer je je op de gewenste 
    locatie bevindt) `pwd` uit te voeren. Dit path kan gebruikt worden als prefix.

---

- Deployer blijf hangen:
    - Overnieuw proberen. Dit is een bug bij oudere versies van Deployer. Cancel executie van het 
    programma en probeer het opnieuw

---

- Ik moet nog steeds wachtwoorden invoeren tijdens het deployen.
    - Je hebt of je ssh-agent niet draaiend, of je key niet toegevoegd aan deze agent.
        - Voer `ssh-add -l` uit om te kijken of jouw key in de agent zit
        - Zo nee? Voer `ssh-add` uit. En daarna `ssh-add -l` om te checken of de key beschikbaar is.
    - Public keys, wat zijn dat? Raadpleeg [ssh](ssh)

---

