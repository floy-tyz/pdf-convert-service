ARG PHP
ARG NAME

FROM $PHP

RUN apk add --no-cache \
    bash \
    curl \
    py3-pip \
    linux-headers \
    libreoffice \
    supervisor \
    vim \
    make \
    autoconf \
    g++ \
    rabbitmq-c-dev \
    imagemagick \
    ghostscript \
    ghostscript-fonts \
    groff \
    freetype-dev \
    font-noto \
    font-noto-cjk \
    font-noto-extra \
    terminus-font \
    ttf-font-awesome \
    ttf-dejavu \
    ttf-freefont \
    ttf-hack \
    ttf-inconsolata \
    ttf-liberation \
    ttf-mononoki \
    ttf-opensans \
    fontconfig

RUN pecl install amqp && docker-php-ext-enable amqp

RUN apk add --no-cache --virtual .build-deps \
    imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del .build-deps

RUN apk del autoconf g++ make \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && fc-cache -f

RUN pip install --break-system-packages unoserver

USER www-data

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock ./

RUN composer install --no-scripts --no-dev --optimize-autoloader

COPY --chown=www-data:www-data . .

RUN composer run-script post-install-cmd

CMD ["supervisord"]