FROM ${DEPENDENCY_PROXY}composer:latest AS composer
FROM ${DEPENDENCY_PROXY}php:8.1-fpm-alpine

ARG PUID=1000
ARG PGID=1000

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
    && \
    apk add --no-cache \
        bash \
        libxml2-dev \
        libzip-dev \
        zip \
    && \
    docker-php-ext-install -j$(nproc) \
        soap \
        zip \
    && \
    apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Add a non-root user to prevent files being created with root permissions on host machine.
RUN addgroup -g ${PGID} user && \
    adduser -u ${PUID} -G user -D user

USER user