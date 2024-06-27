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

# Definir o ServerName globalmente para suprimir o aviso
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar os arquivos da aplicação
COPY ./src /var/www/html

# Copiar o script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Definir permissões de execução para o script de entrada
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expor a porta 80 do Apache
EXPOSE 80

# Definir o script de entrada como ponto de entrada
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
