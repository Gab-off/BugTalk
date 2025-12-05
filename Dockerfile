FROM php:8.2-apache

# Instala extensões necessárias e dependências do Composer
RUN apt-get update \
  && apt-get install -y \
    libssl-dev \
    git \
    unzip \
    libzip-dev \
  && docker-php-ext-install pdo pdo_mysql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita mod_rewrite (IMPORTANTE!)
RUN a2enmod rewrite

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia TODA a estrutura do projeto PRIMEIRO
COPY . /var/www/html/

# Instala as dependências do Composer DEPOIS
RUN composer install --no-dev --optimize-autoloader

# Configuração SSL
COPY default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
RUN a2enmod ssl && a2ensite default-ssl

# Ajusta o DocumentRoot para apontar para /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/public/>|g' /etc/apache2/apache2.conf \
    && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf \
    && sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/default-ssl.conf

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80 443
