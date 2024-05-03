# deploy web application

This markdown is used as steps in my server can't be writen in this repo. So everything is indicated here.
Please note that I do not have made copy-paste my own config (ie. database name, username, etc.)
## virtual machine config
When buyong my machine, I choose Debian as operating system. This was the only available operating system with docker pre-installed for my price and also one that i know the most (as ubuntu and co is a fork).

### firewall setup

I choose to use ufw firewall (the one that i did use in the past).
It can be installed manually or pre-installed (even if in some distribution, uftables is the default firewall, not on debian).

Then I activated it:
```bash
sudo systemctl enable ufw
sudo systemctl start ufw
```

By default, I deny all external call.
```bash
sudo ufw default deny incoming
```

### ssh configuration

In sshd configuration file (int ```/etc/ssh/sshd_config```) of the server, I activate the connexion by ssh key
```
PubkeyAuthentication yes
```
In my terminal, I make the virtual mchine register my ssh key (if not already):
```bash
ssh-copy-id <user>@<id>
```

Then I update the configurations:
```
PasswordAuthentication no
ChallengeResponseAuthentication no
UsePAM no

#optionnaly, I can choose to connect with another port
Port 22
```

Then I allow my ssh port connexion on the server (port 22 if not explicitly modified):
```bash
sudo ufw allow 22/tcp comment 'ssh connexion port'
```

Then I activate my firewall (that wasn't before):
```bash
sudo ufw enable
```

### docker configuration with nginx

I try to the nginx image on my container inside the vm
```bash
docker run -dp 80:80 nginx:1.25
curl localhost
```

for outside testing,
```bash
sudo ufw allow 80/tcp #for http connexion
sudo ufw allow 443/tcp #for https connexion
sudo systemctl restart ufw
```

then we can manually test in our local machine
```bash
curl <ip-du-serv>:80
```

## Traefik setup

traefik has the role of the reverse proxy. Unlike nginx, it will automatically create a proxy with docker images.

### traefik configuration

To work, traefik only need access to docker container.
Additionnal configuration can be used.

Then, we can use it by calling its image on a docker compose:
```yaml
services:
  nginx:
    image: nginx:1.25
    restart: always
    labels:
      - "traefik.http.routers.nginx.rule=Host(`nginx.domain.name`)"
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

### TSL certificat generation

To make a ssl certificate within our traefik image
we need to configure traefik like this:
where nginx is the random container.

```yaml
services:
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
      # We want to use the dashboard
      - "--api.dashboard=true"
      # The entrypoints we want to expose
      - "--entrypoints.web.address=:80"
      - "--entrypoints.websecure.address=:443"
      # Enable ACME (Let's Encrypt): automatic TSL.
      - "--certificatesresolvers.letsencrypt.acme.email=your@email.com"
      - "--certificatesresolvers.letsencrypt.acme.storage=/etc/traefik/acme/acme.json"
      - "--certificatesresolvers.letsencrypt.acme.httpchallenge=true"
      - "--certificatesresolvers.letsencrypt.acme.httpchallenge.entrypoint=web"
      # Global redirection to https
      - "--entrypoints.web.http.redirections.entryPoint.to=websecure"
      - "--entrypoints.web.http.redirections.entryPoint.scheme=https"

    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - ./letsencrypt/acme.json:/etc/traefik/acme/acme.json

    # label is used to configure how traefik must give access to web component
    # As we enabled the api.dashboard option, traefik became one of its component through api@internal service
    labels:
      - "traefik.enable=true"
      # http
      # Since we are using the http challenge we and use the redirect we need 
      #  to enable the http entrypoint.
      - "traefik.http.routers.dashboard-http.entrypoints=web"
      # The domain we want to listen to
      - "traefik.http.routers.dashboard-http.rule=Host(`traefik.domain.name`)"
      # We need to attach the api@internal service to the dashboard-http router
      #  in order for the dashboard to be able to access the api
      - "traefik.http.routers.dashboard-http.service=api@internal"
      # https
      # Enable the https entrypoint
      - "traefik.http.routers.dashboard.entrypoints=websecure"
      # The domain we want to listen to
      - "traefik.http.routers.dashboard.rule=Host(`traefik.domain.name`)"
      # We want to obtain a certificate through Let's Encrypt
      - "traefik.http.routers.dashboard.tls=true"
      - "traefik.http.routers.dashboard.tls.certresolver=letsencrypt"
      # We need to attach the api@internal service to the dashboard router
      #  in order for the dashboard to be able to access the api
      - "traefik.http.routers.dashboard.service=api@internal"

  # another example of container used by traefik
  nginx:
    image: nginx:1.25
    restart: always
    expose:
      - 80
    labels:
      # to enable the endpoint
      - "traefik.enable=true"

      # to allow traefik to redirect http into https request
      - "traefik.http.routers.nginx-redirect-secure.entrypoints=web"
      - "traefik.http.routers.nginx-redirect-secure.rule=Host(`nginx.domain.name`)"

      # https configurations
      - "traefik.http.routers.to-nginx.entrypoints=websecure"
      - "traefik.http.routers.to-nginx.rule=Host(`nginx.domain.name`)"
      - "traefik.http.routers.to-nginx.tls=true"
      - "traefik.http.routers.to-nginx.tls.certresolver=letsencrypt"


```


## deploying the back and front app

To deploy our app using docker, we first need to push our images to dockerhub
```bash
docker tag image_name <user name>/image_name
docker push <user name>/image_name
```

Then on server use the next compose.yaml to use them:
```yaml
services:
  db:
    image: postgres
    restart: always
    volumes:
      - db_data:/var/lib/postgresql/data
    environment:
      POSTGRES_PASSWORD: greenhub
      POSTGRES_USER: greenhub
      POSTGRES_DB: greenhub
    # This session is used to describe how we know if database can accept connexion
    # I need to update it if I choose another database
    # Also, I manually defined that it will correctly start within 10 seconds (number of retries × interval time)
    healthcheck:
      test: [ "CMD-SHELL", "pg_isready -U greenhub -d greenhub"]
      interval: 1s
      timeout: 1s
      retries: 10
  back:
    image: <user>/image_name_back
    restart: always
    depends_on:
      # had to add it because in my run command, I make migrations.
      # So I need an available database
      data_postgres:
        condition: service_healthy
    environment:
      - DB_CONNECTION=pgsql
      - DB_HOST=db
      - DB_PORT=5432
      - DB_DATABASE=greenhub
      - DB_USERNAME=greenhub
      - DB_PASSWORD=greenhub
      - SESSION_DOMAIN=domain.name
      - APP_URL=https://api.domain.name
      - FRONTEND_URL=https://domain.name
      - APP_DEBUG=false
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.to-back.entrypoints=websecure"
      - "traefik.http.routers.to-back-unsecure.entrypoints=web"
      - "traefik.http.routers.to-back.rule=Host(`api.domain.name`)"
      - "traefik.http.routers.to-back-unsecure.rule=Host(`api.domain.name`)"
      - "traefik.http.routers.to-back.tls=true"
      - "traefik.http.routers.to-back.tls.certresolver=letsencrypt"

  front:
    image: <user>/image_name_front
    restart: always
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.to-front.entrypoints=websecure"
      - "traefik.http.routers.to-front-unsecure.entrypoints=web"
      - "traefik.http.routers.to-front.rule=Host(`domain.name`)"
      - "traefik.http.routers.to-front-unsecure.rule=Host(`domain.name`)"
      - "traefik.http.routers.to-front.tls=true"
      - "traefik.http.routers.to-front.tls.certresolver=letsencrypt"

  traefik:
     image: "traefik:v3.0"
     restart: always
     ports:
       - 80:80
       - 443:443
     command:
       - "--providers.docker"
       - "--providers.docker.exposedByDefault=false"
       - "--certificatesresolvers.letsencrypt.acme.email=your@email.com"
       - "--certificatesresolvers.letsencrypt.acme.storage=/etc/traefik/acme/acme.json"
       - "--certificatesresolvers.letsencrypt.acme.httpchallenge=true"
       - "--certificatesresolvers.letsencrypt.acme.httpchallenge.entrypoint=web"
       - "--entrypoints.web.http.redirections.entryPoint.to=websecure"
       - "--entrypoints.web.http.redirections.entryPoint.scheme=https"
     volumes:
       - /var/run/docker.sock:/var/run/docker.sock
       - ./letsencrypt/acme.json:/etc/traefik/acme/acme.json

volumes:
  db_data: {}
```
