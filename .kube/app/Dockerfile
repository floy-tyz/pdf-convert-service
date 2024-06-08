# Use arguments for PHP version and image name
ARG PHP
ARG NAME

# Use the specified PHP image
FROM $PHP

# Install required packages and dependencies
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

# Install PECL extensions separately to isolate issues
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install amqp && docker-php-ext-enable amqp

# Install Imagick with necessary build dependencies
RUN apk add --no-cache --virtual .build-deps \
    imagemagick-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && apk del .build-deps

# Clean up
RUN apk del autoconf g++ make \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/* \
    && fc-cache -f

# Install unoserver using pip
RUN pip install --break-system-packages unoserver

# Copy composer from the composer image
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Define the default command to run supervisord
CMD ["supervisord"]