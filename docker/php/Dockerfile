FROM composer
FROM php:7.1-fpm

ENV WORKPATH "/var/www/todolist"

RUN apt-get update -y \
    && apt-get install -y \
               libfreetype6-dev \
               libjpeg62-turbo-dev \
               libmcrypt-dev \
               libpng12-dev \
               zip \
               unzip \
               wget \
               curl \
               libcurl4-openssl-dev \
               pkg-config \
               libssl-dev \
               libpq-dev

COPY conf/php.ini /usr/local/etc/php/

# COPY conf/production/php.ini /usr/local/etc/php/ -> Only for production usage.

# Core extensions
RUN pecl install apcu \
                 xdebug \
                 mongodb 1.3.1 \
    && apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_mysql opcache json pdo_pgsql pgsql \
    && docker-php-ext-enable apcu xdebug mongodb.so \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=0 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

# Blackfire (Docker approach)
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && wget -O - https://packagecloud.io/gpg.key | apt-key add - \
    && echo "deb http://packages.blackfire.io/debian any main" | tee /etc/apt/sources.list.d/blackfire.list \
    && apt-get update -y \
    && apt-get install blackfire-agent -y

# Blackfire Player
RUN curl -OLsS http://get.blackfire.io/blackfire-player.phar \
    && chmod +x blackfire-player.phar \
    && mv blackfire-player.phar /usr/local/bin/blackfire-player

# PHP-CS-FIXER
RUN wget http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer \
    && chmod a+x php-cs-fixer \
    && mv php-cs-fixer /usr/local/bin/php-cs-fixer

RUN mkdir -p ${WORKPATH}
RUN mkdir -p \
		${WORKDIR}/var/cache \
		${WORKDIR}/var/logs \
		${WORKDIR}/var/sessions \
	&& chown -R www-data ${WORKDIR}/var \
	&& chown -R www-data /tmp/

RUN chown www-data:www-data -R ${WORKPATH}

WORKDIR ${WORKPATH}

EXPOSE 9000

CMD ["php-fpm"]
