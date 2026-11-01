FROM php:8.2-fpm-bookworm

# Системные зависимости и расширения PHP
RUN apt-get update && apt-get install -y git && echo "git OK"
RUN apt-get install -y curl && echo "curl OK"
RUN apt-get install -y libpng-dev && echo "libpng-dev OK"
RUN apt-get install -y libonig-dev && echo "libonig-dev OK"
RUN apt-get install -y libxml2-dev && echo "libxml2-dev OK"
RUN apt-get install -y libzip-dev && echo "libzip-dev OK"
RUN apt-get install -y libsqlite3-dev && echo "libsqlite3-dev OK"
RUN apt-get install -y pkg-config && echo "pkg-config OK"
RUN apt-get install -y libjpeg-dev && echo "libjpeg-dev OK"
RUN apt-get install -y libfreetype6-dev && echo "libfreetype6-dev OK"
RUN apt-get install -y default-libmysqlclient-dev && echo "default-libmysqlclient-dev OK"
RUN apt-get install -y zip && echo "zip OK"
RUN apt-get install -y unzip && echo "unzip OK"
RUN apt-get install -y libexif-dev && echo "libexif-dev OK"
RUN docker-php-ext-install pdo_mysql && echo "pdo_mysql OK"
RUN docker-php-ext-install pdo_sqlite && echo "pdo_sqlite OK"
RUN docker-php-ext-install mbstring && echo "mbstring OK"
RUN docker-php-ext-install exif && echo "exif OK"
RUN docker-php-ext-install pcntl && echo "pcntl OK"
RUN docker-php-ext-install bcmath && echo "bcmath OK"
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && echo "gd configured"
RUN docker-php-ext-install gd && echo "gd OK"
RUN docker-php-ext-install zip && echo "zip OK"

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория внутри контейнера
WORKDIR /var/www

# Сначала composer-файлы — чтобы кэшировать слой
COPY composer.json composer.lock ./

# Весь код проекта
COPY . /var/www

# Права на запись для Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# PHP-FPM слушает 9000
EXPOSE 9000

# При старте запускаем PHP-FPM
CMD ["php-fpm"]
