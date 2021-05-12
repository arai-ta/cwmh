FROM php:8-apache

RUN docker-php-ext-install pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN a2enmod rewrite

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "date.timezone = 'Asia/Tokyo'" >> "$PHP_INI_DIR/php.ini"

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
