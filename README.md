# Sun CI: Building Docker image
---

You can use the dind (Docker-in-Docker) image from [docker](https://hub.docker.com/_/docker/) to build your image by using services `docker:dind`.
```
jobs:
  docker-build:
    stage: build
    services:
    - docker:dind
    image: docker
```
## Dockerfile
```
# Set master image
FROM php:7.3-fpm-alpine

ENV RUN_DEPS \
    zlib \
    libzip \
    libpng \
    libjpeg-turbo \
    postgresql-libs

ENV BUILD_DEPS \
    zlib-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    postgresql-dev

ENV PHP_EXTENSIONS \
    opcache \
    zip \
    gd \
    bcmath \
    pgsql \
    pdo_pgsql

ENV PECL_EXTENSIONS \
    redis

# Install PHP Composer and ext
RUN apk add --no-cache --virtual .build-deps $BUILD_DEPS \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/include \
    && pecl install $PECL_EXTENSIONS \
    && docker-php-ext-install -j "$(nproc)" $PHP_EXTENSIONS \
    && docker-php-ext-enable $PECL_EXTENSIONS \
    && apk del .build-deps
RUN apk add --no-cache --virtual .run-deps $RUN_DEPS
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory
COPY . .
```
:warning: If you want use directory in container (docker in docker), you must add or copy files when building image. :warning:

## Docker-compose
```
version: '3.9'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: php-laravel
    restart: unless-stopped

  postgres:
    image: postgres
    restart: unless-stopped
    tty: true
    ports:
      - "5432:5432"
    environment:
      POSTGRES_DB: ${DB_DATABASE}
      POSTGRES_USER: ${DB_USERNAME}
      POSTGRES_PASSWORD: ${DB_PASSWORD}

  redis:
    image: redis
    ports:
      - "6379:6379"
```
