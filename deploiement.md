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

## Fonctionnement de traefik

traefik has the role of the reverse proxy. Unlike nginx, it will automaticly found docker images on disc and redistribute request like this.

### configuration de traefik

Pour fonctionner, traefik n'a besoin que de configurations (ici donné en CLI), et des composants docker.

Ensuite, comme il est plus simple d'utiliser traefik dans son conteneur docker, il sera préférable de
l'utiliser dans son docker compose:
```
services:
  nginx:
    image: nginx:1.25
    restart: always
    labels:
      - "traefik.http.routers.nginx.rule=Host(`nginx.aurelipool.space`)"
      - "traefik.http.services.nginx.loadbalancer.server.port=80"

  traefik:
    image: traefik:v2.11
    restart: always
    ports:
      - 80:80
    command: --providers.docker=true
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock

```

### configuration du certificat ssl/tls

To make a ssl certificate within our traefik image
we need to configure traefik like this:
where nginx is the random container.

```
services:
  nginx:
    image: nginx:1.25
    restart: always
    expose:
      - 80
    depends_on:
      - traefik
    labels:
      # to enable the endpoint
      - "traefik.enable=true"

      # to allow traefik to redirect http into https request
      - "traefik.http.routers.nginx-redirect-secure.entrypoints=web"
      - "traefik.http.routers.nginx-redirect-secure.rule=Host(`nginx.aurelipool.space`)"

      # https configurations
      - "traefik.http.routers.to-nginx.entrypoints=websecure"
      - "traefik.http.routers.to-nginx.rule=Host(`nginx.aurelipool.space`)"
      - "traefik.http.routers.to-nginx.tls=true"
      - "traefik.http.routers.to-nginx.tls.certresolver=letsencrypt"

  traefik:
    image: "traefik:v3.0"
    restart: always
    ports:
      - 80:80
      - 443:443
      #- 8075:8080
    command:
      # We are going to use the docker provider
      - "--providers.docker"
      # Only enabled containers should be exposed
      - "--providers.docker.exposedByDefault=false"
      # We want to use the dashbaord
      - "--api.dashboard=true"
      # The entrypoints we ant to expose
      - "--entrypoints.web.address=:80"
      - "--entrypoints.websecure.address=:443"
      # Enable ACME (Let's Encrypt): automatic SSL.
      - "--certificatesresolvers.letsencrypt.acme.email=f.aurelien@yahoo.fr"
      - "--certificatesresolvers.letsencrypt.acme.storage=/etc/traefik/acme/acme.json"
      - "--certificatesresolvers.letsencrypt.acme.httpchallenge=true"
      - "--certificatesresolvers.letsencrypt.acme.httpchallenge.entrypoint=web"
      # Global redirect to https
      - "--entrypoints.web.http.redirections.entryPoint.to=websecure"
      - "--entrypoints.web.http.redirections.entryPoint.scheme=https"

    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - ./letsencrypt/acme.json:/etc/traefik/acme/acme.json

    # because we need to configure traefik dashboard (as we have --api.dashboard=true in command-line config)
    labels:
      - "traefik.enable=true"
      # http
      # Since we are using the http challenge we and use the redirect we need 
      #  to enable the http entrypoint.
      - "traefik.http.routers.dashboard-http.entrypoints=web"
      # The domain we want to listen to
      - "traefik.http.routers.dashboard-http.rule=Host(`traefik.aurelipool.space`)"
      # We need to attach the api@internal service to the dashboard-http router
      #  in order for the dashboard to be able to access the api (I think 🙈)
      - "traefik.http.routers.dashboard-http.service=api@internal"
      # https
      # Enable the https entrypoint
      - "traefik.http.routers.dashboard.entrypoints=websecure"
      # The domain we want to listen to
      - "traefik.http.routers.dashboard.rule=Host(`traefik.aurelipool.space`)"
      # We want to obtain a certificate through Let's Encrypt
      - "traefik.http.routers.dashboard.tls=true"
      - "traefik.http.routers.dashboard.tls.certresolver=letsencrypt"
      # We need to attach the api@internal service to the dashboard router
      #  in order for the dashboard to be able to access the api (I think 🙈)
      - "traefik.http.routers.dashboard.service=api@internal"

```


## deploying the back and front app


