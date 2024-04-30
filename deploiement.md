# Déployer l'application web

## configuration de la machine virtuel
Lors de l'achat, J'ai choisi le système d'exploitation debian (une des seules possibilités pour le prix de mon serveur ainsi que le SE que je connais le mieux avec docker installé par défaut).
### installation et configuration du firewall

J'ai commencé par installer un firewall ```sudo apt install ufw```,
que j'ai immédiatement activé sur ma machine (uftables n'est pas activé sur ce système d'exploitation par défaut)
```bash
sudo systemctl enable ufw
sudo systemctl start ufw
```

puis je refuse les demandes de connections sortantes:
```
sudo ufw default deny incoming
```

### configuration du ssh

Dans le fichier de configuration du serveur ssh (```/etc/ssh/sshd_config```), j'écris la ligne ```PubkeyAuthentication yes```
Dans ma propre console, ```ssh-copy-id <user>@<ip>``` (avec le mot de passe)
Dans la console du VPS, j'ajoute ou modifie les configurations suivantes:
```
PasswordAuthentication no
ChallengeResponseAuthentication no
UsePAM no
```

Then I allow port 22 in my firewall
```
sudo ufw allow 22/tcp comment 'ssh connexion port'
```

Then I start the service ufw:
```
sudo ufw enable
```

### configuration de docker avec nginx

I try to launch my nginx container
```bash
docker run -dp 80:80 nginx:1.25
curl localhost
```

for testing outside of VPS,
```bash
sudo ufw allow 80/tcp
sudo systemctl restart ufw
```
