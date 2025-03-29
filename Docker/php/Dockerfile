# Используем официальный образ PHP с FPM (для работы с Nginx)
FROM php:8.2-fpm

# Устанавливаем зависимости для PHP и расширений
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libssl-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache

# Устанавливаем Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем MongoDB extension
RUN apt-get install -y libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Копируем php.ini в контейнер
COPY ./Docker/php/php.ini /usr/local/etc/php/

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Открываем порт 9000 для PHP-FPM
EXPOSE 9000
