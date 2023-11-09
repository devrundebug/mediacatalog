FROM php:8.2-fpm
ARG TIMEZONE
ARG GIT_EMAIL
ARG GIT_NAME

COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libonig-dev \
    libxslt1-dev \
    acl \
    htop \
    telnet \
    sudo \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

RUN pecl install -f xdebug
RUN docker-php-ext-enable xdebug

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer > symfony.installer.sh && bash symfony.installer.sh --install-dir=/usr/local/bin && rm symfony.installer.sh

## Add user for application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www && usermod -G sudo,root,www-data www

RUN usermod -G sudo,root,www www-data

RUN echo -n 'www ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers


# Change current user to www
USER www
RUN git config --global user.email ${GIT_EMAIL} && git config --global user.name ${GIT_NAME}
RUN echo 'alias ll="ls -alFh"' >> ~/.bashrc
WORKDIR /var/www/mediacatalog
EXPOSE 9000

STOPSIGNAL SIGTERM

ENTRYPOINT ["/bin/bash", "-c", "php-fpm"]


