FROM php:8.2-fpm

# Instalar dependências necessárias
RUN apt-get update && apt-get install -y \
  zip \
  curl \
  libxml2-dev \
  libcurl4-openssl-dev \
  libpng-dev \
  libonig-dev \
  libpq-dev \
  pkg-config \
  git \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar extensões PHP
RUN docker-php-ext-install \
  soap \
  xml \
  curl \
  opcache \
  gd \
  mbstring \
  pdo_pgsql

# Definir o diretório de trabalho
WORKDIR /app
