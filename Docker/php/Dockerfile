# Используем официальный образ PHP с FPM
FROM php:8.2-fpm

# Устанавливаем только необходимые зависимости для MongoDB
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer (глобально)
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

# Рабочая директория
WORKDIR /var/www/html

# Открываем порт для PHP-FPM
EXPOSE 9000
