FROM node:21-alpine3.19 as build-step

WORKDIR /home/node/
COPY ./src_depl/ .

RUN npm install\
 && npm run build

#CMD /bin/sh
FROM nginx:1.25.5 as prod-step

COPY ./nginx_conf/ /etc/nginx/conf.d/
COPY --from=build-step /home/node/dist /var/www

EXPOSE 80
