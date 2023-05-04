ARG PHP_VERSION=8.2.0
ARG COMPOSER_VERSION=2.5.5

FROM composer:${COMPOSER_VERSION} as composer
FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

## symfony cli install
RUN apk add --no-cache bash git
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli
## END symfony cli install

### SYMFONY REQUIREMENT
RUN apk add --no-cache icu-dev \
  && docker-php-ext-install intl \
  && docker-php-ext-enable intl \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache

### MYSQL DRIVER
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY .docker/php/symfony.ini /usr/local/etc/php/conf.d/
### END SYMFONY REQUIREMENT

WORKDIR /var/www/app

HEALTHCHECK --interval=5s --timeout=3s --retries=3 CMD symfony check:req

CMD ["php-fpm", "-F"]

EXPOSE 9000

## ClEAN
RUN rm -rf /tmp/* /var/cache/apk/* /var/tmp/*

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

## XDEBUG
RUN install-php-extensions xdebug-3.2.1
COPY .docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
COPY .docker/php/error_reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
## END XDEBUG

ARG BUILD_DATE
ARG VCS_REF
ARG BUILD_VERSION

## LABELS
LABEL maintainer="yosribahri@gmail.com"
LABEL org.opencontainers.image.source="https://github.com/devgine/company-verioning"
LABEL org.opencontainers.image.description="Symfony api to manage comapanies versioning"
LABEL org.label-schema.schema-version="1.0"
LABEL org.label-schema.build-date=$BUILD_DATE
LABEL org.label-schema.name="devgine/company-verioning-api"
LABEL org.label-schema.description="Symfony api to manage comapanies versioning"
LABEL org.label-schema.url="http://www.devengine.fr/"
LABEL org.label-schema.vcs-url="https://github.com/devgine/company-verioning"
LABEL org.label-schema.vcs-ref=$VCS_REF
#LABEL org.label-schema.vendor="WSO2"
LABEL org.label-schema.version=$BUILD_VERSION
LABEL org.label-schema.docker.cmd="docker run -d ghcr.io/devgine/company-verioning-api:dev"
