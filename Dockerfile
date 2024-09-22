FROM php:8.3

RUN apt-get update && apt-get install -y unzip

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ./src /app/src
COPY ./composer.json /app/composer.json
COPY ./db-data /app/db-data

RUN docker-php-ext-install sockets
RUN composer install
RUN composer dump-autoload

# Указываем точку входа в консольное приложение
ENTRYPOINT ["php", "/app/src/cli.php"]