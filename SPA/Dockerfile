FROM node:21-alpine3.19 as builded-front

WORKDIR /home/node/
COPY ./src2/ .

RUN npm install\
 && npm run build

#CMD /bin/sh
FROM nginx:1.25.5

COPY ./nginx_conf/ /etc/nginx/conf.d/
COPY --from=builded-front /home/node/dist /var/www

