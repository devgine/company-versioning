ARG PHP_VERSION=8.2.0
ARG COMPOSER_VERSION=2.5.5
ARG PHP_EXTENSION_INSTALLER_VERSION=latest

FROM composer:${COMPOSER_VERSION} AS composer
FROM mlocati/php-extension-installer:${PHP_EXTENSION_INSTALLER_VERSION} AS php_extension_installer

FROM php:${PHP_VERSION}-fpm-alpine AS builder

### SYMFONY REQUIREMENT
RUN apk add --no-cache icu-dev \
  && docker-php-ext-install intl \
  && docker-php-ext-enable intl \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

COPY .docker/php/symfony.ini /usr/local/etc/php/conf.d/
### END SYMFONY REQUIREMENT

### MYSQL DRIVER
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

# @see https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
#ENV COMPOSER_ALLOW_SUPERUSER=1
#RUN set -eux; \
#    composer clear-cache
#ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Copy file required by opcache preloading
COPY api/config/preload.php /config/preload.php

### INSTALL DEPENDENCIES WITHOUT SCRIPTS
COPY api/composer.json ./
COPY api/composer.lock ./
COPY api/symfony.lock ./

RUN set -eux; \
    composer install --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader --no-dev;

# todo: entrypoint
# ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm", "-F"]

EXPOSE 9000

FROM builder as prodenv

ENV APP_ENV=prod

COPY .docker/php/prod/php.ini        $PHP_INI_DIR/php.ini
COPY .docker/php/prod/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

ARG BUILD_DATE
ARG VCS_REF
ARG BUILD_VERSION

## LABELS
LABEL maintainer="yosribahri@gmail.com"
LABEL org.opencontainers.image.source="https://github.com/devgine/company-verioning"
LABEL org.opencontainers.image.description="Symfony api to manage companies versioning"
LABEL org.label-schema.schema-version="1.0"
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.label-schema.name="devgine/company-versioning-api"
LABEL org.label-schema.description="Symfony api to manage companies versioning"
LABEL org.label-schema.url="http://www.devengine.fr/"
LABEL org.label-schema.vcs-url="https://github.com/devgine/company-verioning"
LABEL org.label-schema.vcs-ref=$VCS_REF
#LABEL org.label-schema.vendor="WSO2"
LABEL org.label-schema.version=$BUILD_VERSION
LABEL org.label-schema.docker.cmd="docker run --rm -d ghcr.io/devgine/company-versioning-api:latest sh"

# todo: remove .env
COPY api/.env ./
COPY api/bin/console ./bin/
COPY api/config config/
COPY api/migrations migrations/
COPY api/public public/
COPY api/src src/
COPY api/templates templates/

RUN set -eux; \
    composer dump-autoload --classmap-authoritative; \
    composer run-script post-install-cmd; \
    chmod +x bin/console; sync;

# todo: create health route
HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD curl --fail http://localhost/api/ || exit 1

## ClEAN
RUN rm -rf /tmp/* /var/cache/apk/* /var/tmp/*

FROM builder as local
COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/bin/

## symfony cli install
RUN apk add --no-cache bash git
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli
## END symfony cli install

HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD symfony check:req

## XDEBUG
RUN install-php-extensions xdebug-3.2.1;
COPY .docker/php/dev/xdebug.ini          $PHP_INI_DIR/docker-php-ext-xdebug.ini
#COPY .docker/php/dev/error_reporting.ini $PHP_INI_DIR/error_reporting.ini
## END XDEBUG

## INSTALL PHP DETECTORS (PHPCPD & PHPMD)
RUN wget -c https://phar.phpunit.de/phpcpd.phar -O /usr/local/bin/phpcpd \
    && wget -c https://phpmd.org/static/latest/phpmd.phar -O /usr/local/bin/phpmd \
    && chmod +x /usr/local/bin/phpcpd /usr/local/bin/phpmd

FROM builder as devenv

COPY .docker/php/dev/php.ini        $PHP_INI_DIR/php.ini
COPY .docker/php/dev/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

ENV APP_ENV=dev

### INSTALL DEPENDENCIES DEV REQUIREMENTS
RUN set -eux; \
    composer install --prefer-dist --no-progress --no-scripts --no-interaction --optimize-autoloader;

### COPY PROJECT FILES AND DIRECTORY
COPY api/bin bin/
COPY api/config config/
COPY api/migrations migrations/
COPY api/public public/
COPY api/src src/
COPY api/templates templates/
COPY api/.env ./

### RUN COMPOSER SCRIPTS AND CLEAR CAHE
RUN set -eux; \
    composer run-script post-install-cmd \
    composer clear-cache

## ClEAN
RUN rm -rf /tmp/* /var/cache/apk/* /var/tmp/*

FROM local as ci

ENV APP_ENV=test

COPY --from=devenv /var/www/app ./
### COPY ADDITIONAL PROJECT FILES AND DIRECTORY
COPY api/.env.test api/.php-cs-fixer.dist.php api/phpstan.neon api/phpunit.xml.dist api/phpmd.xml.dist ./
COPY api/tests tests/
