###### BASE STAGE START #######
FROM php:8.3-fpm-bookworm as base

ARG USER_ID=1000
ARG GROUP_ID=1000

# Create app user and group with UID same as provided argument to avoid permission issues and conflicts
RUN echo "Creating app user and group with IDs $USER_ID:$GROUP_ID" \
    && groupadd -g $GROUP_ID app \
    && useradd -u $USER_ID -g app -m app

# Add runtime dependencies
ENV RUNTIME_DEPS libicu-dev libzip-dev postgresql-client
RUN apt-get update \
    && apt-get install -y --no-install-recommends $RUNTIME_DEPS \
    && rm -rf /var/lib/apt/lists/*

# Install PostgreSQL development packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install intl pdo pdo_mysql zip pdo_pgsql

###### BUILD STAGE START #######
FROM base as build

# Install composer
ENV COMPOSER_DEPS zip unzip wget
ADD docker/services/php/install-composer.sh .
RUN apt-get update \
    && apt-get install -y --no-install-recommends git $COMPOSER_DEPS \
    && chmod u+x install-composer.sh \
    && ./install-composer.sh \
    && composer --version \
    && apt-get remove --purge -y $COMPOSER_DEPS \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /opt/app && chown app /opt/app
USER app

RUN composer global require maglnet/composer-require-checker

COPY . /opt/app
WORKDIR /opt/app

ENV APP_ENV=prod

USER root
RUN composer install --no-dev -o

###### DEV STAGE START #######
FROM build as dev

USER root
RUN composer install -o
# Install XDebug
RUN apt-get update \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
    && pecl install -f xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get remove --purge -y $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/*
ADD docker/services/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

USER app

ENV COMPOSER_ALLOW_XDEBUG 0
ENV APP_ENV=dev
WORKDIR /opt/app
