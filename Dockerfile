FROM php:8.2-apache

# Ativar o mod_rewrite do Apache
RUN a2enmod rewrite

# Atualizar e Instalar ferramentas básicas
RUN apt-get update && apt-get install -y libzip-dev unzip git

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos da aplicação
COPY ./src /var/www/html

# Expor a porta 80 do Apache
EXPOSE 80
