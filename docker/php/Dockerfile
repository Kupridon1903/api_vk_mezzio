FROM php:7.4-fpm-alpine as base

# add config
ADD docker/php/fpm/pool.d/fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf
ADD docker/php/php.ini /usr/local/etc/php/php.ini

ENV WORK_DIR /var/www/api-vk-mezzio

ENV PHPIZE_DEPS \
    linux-headers \
    autoconf \
    gcc \
    libc-dev \
    make \
    curl-dev \
    musl-dev \
    zlib-dev \
    cyrus-sasl-dev \
    libmemcached-dev \
    libzip-dev \
    g++ \
    re2c \
    perl \
    perl-error  \
    perl-git \
    m4 \
    icu-dev

ENV PERMANENT_DEPS \
    openssh \
    libgcc \
    libstdc++ \
    libbz2 \
    libmemcached-libs \
    file \
    binutils \
    gmp \
    isl \
    libgomp \
    libatomic \
    zlib \
    libzip \
    icu-libs \
    git

RUN set -xe \
    && apk update \
    && apk add --no-cache ${PERMANENT_DEPS} \
    && apk add --no-cache --virtual .build-deps ${PHPIZE_DEPS} \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pcntl \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-configure opcache --enable-opcache \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* \
    && rm -rf /tmp/*

# vendor image #
FROM base as vendor

ARG ssh_prv_key
ARG ssh_pub_key

COPY . ${WORK_DIR}
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Authorize SSH Host
RUN mkdir -p /root/.ssh \
    && chmod 0700 /root/.ssh \
    && ssh-keyscan github.com > /root/.ssh/known_hosts

# Add the keys and set permissions
RUN echo -e "$ssh_prv_key" > /root/.ssh/id_rsa \
    && echo -e "$ssh_pub_key" > /root/.ssh/id_rsa.pub \
    && chmod 600 /root/.ssh/id_rsa \
    && chmod 600 /root/.ssh/id_rsa.pub

RUN cd ${WORK_DIR} \
    && composer install --no-interaction --no-ansi --no-dev --no-suggest \
    && composer dump-autoload -o

# build app
FROM base

COPY . ${WORK_DIR}
COPY --from=vendor --chown=www-data:www-data ${WORK_DIR}/vendor ${WORK_DIR}/vendor

RUN chown -R www-data:www-data ${WORK_DIR} \
    && chown -R www-data:www-data /var/log

# todo entrypoint
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
