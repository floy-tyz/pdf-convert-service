# Репозиторий PHP-FPM на ubuntu
FROM floytyz/php-fpm-ubuntu:latest

ENV DEBIAN_FRONTEND noninteractive

# Установка необходимых пакетов и зависимостей
RUN apt-get update && apt-get install -y --no-install-recommends \
    bash \
    curl \
    python3-pip \
    supervisor \
    make \
    autoconf \
    g++ \
    librabbitmq-dev \
    libmagickwand-dev \
    ghostscript \
    libgs-dev \
    unzip

# Установка необходимых пакетов шрифтов
RUN apt-get update && apt-get install -y --no-install-recommends \
    cabextract \
    fontforge \
    fontconfig \
    fonts-dejavu \
    fonts-dejavu-core \
    fonts-dejavu-extra  \
    fonts-freefont-ttf \
    fonts-liberation \
    fonts-noto \
    fonts-noto-cjk \
    fonts-noto-extra \
    fonts-terminus \
    fonts-font-awesome \
    fonts-crosextra-carlito

# Установка шрифтов Windows
RUN echo "ttf-mscorefonts-installer msttcorefonts/accepted-mscorefonts-eula select true" | debconf-set-selections \
    && apt-get install -y ttf-mscorefonts-installer

# Установка шрифтов Calibri
RUN wget https://raw.githubusercontent.com/floy-tyz/pdf-calibri-font/refs/heads/main/make.sh -q -O - | bash

# Кеширование шрифтов
RUN mkdir /.cache && chown -R www-data:www-data /.cache/ && fc-cache -fv

# Установка последней версии libreoffice
RUN apt-get install -y software-properties-common \
    && add-apt-repository ppa:libreoffice \
    && apt-get update \
    && apt install libreoffice -y

# Установка расширений PECL
RUN pecl install amqp && docker-php-ext-enable amqp

# Установка Imagick (альтернативное решение из-за бага)
RUN apt-get update && apt-get install -y --no-install-recommends \
    && curl -L https://github.com/remicollet/imagick/archive/9df92616f577e38625b96b7b903582a46c064739.zip -o /tmp/imagick-issue-php83.zip \
    && unzip /tmp/imagick-issue-php83.zip -d /tmp \
    && pecl install /tmp/imagick-9df92616f577e38625b96b7b903582a46c064739/package.xml \
    && docker-php-ext-enable imagick

# Очистка
RUN apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
    && rm -rf /var/lib/apt/lists/* /tmp/* \
    && pecl update-channels \
    && rm -rf /tmp/pear ~/.pearrc \
	  && docker-php-source delete \
    && apt autoremove -y \
    && apt autoclean -y

# Установка unoserver с помощью pip
RUN pip install unoserver --break-system-packages

USER www-data

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock ./

RUN composer install --no-scripts --no-dev --optimize-autoloader

COPY --chown=www-data:www-data . .

RUN composer run-script post-install-cmd

CMD ["supervisord"]