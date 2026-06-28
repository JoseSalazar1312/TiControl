FROM php:8.4-fpm

# Dependencias del sistema necesarias para compilar extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Extensiones PHP requeridas por Laravel + Postgres + manejo de imágenes (firmas)
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Composer (copiado desde la imagen oficial de Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Usuario no-root para evitar problemas de permisos con archivos generados por Laravel
RUN groupadd -g 1000 laravel && \
    useradd -u 1000 -ms /bin/bash -g laravel laravel

USER laravel

EXPOSE 9000

CMD ["php-fpm"]