###> ARGUMENTS ###
ARG PHP
ARG NAME
###< ARGUMENTS ###

###> PHP IMAGE FORM ARGUMENTS ###
FROM $PHP
###< PHP IMAGE FORM ARGUMENTS ###

# Install required packages and dependencies
RUN apk add --no-cache \
    bash curl \
    py3-pip \
    linux-headers \
    libreoffice \
    supervisor \
    vim \
    bash \
    make \
    autoconf \
    g++ \
    rabbitmq-c-dev  \
    ###> INSTALL PECL AMQP ###
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    ###< INSTALL PECL AMQP ###
    \
    && rm -rf /var/lib/apt/lists/*


RUN apk add --no-cache \
    font-noto font-noto-cjk font-noto-extra \
    terminus-font \
    ttf-font-awesome \
    ttf-dejavu \
    ttf-freefont \
    ttf-hack \
    ttf-inconsolata \
    ttf-liberation \
    ttf-mononoki  \
    ttf-opensans   \
    fontconfig && \
    fc-cache -f

RUN pip install --break-system-packages unoserver

COPY . /var/www/html

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN composer i

CMD ["supervisord"]