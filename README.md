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
FROM php:7.2-fpm-alpine

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

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
```
