FROM webdevops/php-nginx:8.2-alpine

# Installation dans votre Image du minimum pour que Docker fonctionne
RUN apk add oniguruma-dev libxml2-dev \
 && docker-php-ext-install \
        bcmath \
        ctype \
        fileinfo \
        mbstring \
        pdo_mysql \
        xml

# Installation dans votre image de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installation dans votre image de NodeJS
# RUN apk add nodejs npm
#
ENV WEB_DOCUMENT_ROOT /app/public
ENV APP_ENV production
WORKDIR /app
COPY ./src_depl .

# Installation et configuration de votre site pour la production
# https://laravel.com/docs/10.x/deployment#optimizing-configuration-loading
RUN cp .env.example .env \
 && COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --optimize-autoloader \
 && php artisan key:generate \
 && php artisan route:cache \
 && php artisan view:cache \
 && chown -R application:application .
#RUN php artisan config:cache

# Compilation des assets de Breeze (ou de votre site)
EXPOSE 80

# command script
RUN echo "#!/bin/sh" > startCMD.sh \
  && echo "php artisan migrate --force;" >> startCMD.sh \
  && echo "php artisan config:cache;" >> startCMD.sh \
  && echo "supervisord;" >> startCMD.sh \
  && chmod +x startCMD.sh

CMD [ "/app/startCMD.sh" ]

