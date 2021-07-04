FROM php:7.3.0RC6-fpm-alpine3.8
# timezone
ARG TZ
WORKDIR /var/www/laravel
COPY ./docker/php/php.ini /usr/local/etc/php/
COPY ./laravel/ /var/www/laravel/

RUN apk upgrade --update && apk add --no-cache \
  coreutils freetype-dev jpeg-dev libjpeg-turbo-dev libpng-dev libmcrypt-dev \
  git vim unzip tzdata \
  libltdl && \
  docker-php-ext-install pdo_mysql mysqli mbstring && \
  docker-php-ext-install -j$(nproc) iconv && \
  docker-php-ext-configure gd \
  --with-freetype-dir=/usr/include/ \
  --with-jpeg-dir=/usr/include/ && \
  docker-php-ext-install -j$(nproc) gd && \
  cp /usr/share/zoneinfo/${TZ} /etc/localtime && \
  apk del tzdata && \
  rm -rf /var/cache/apk/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
  composer update && \
  chmod -R a+rw storage/ bootstrap/cache