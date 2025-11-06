FROM php:8.2-apache

# Instale extensões necessárias
RUN apt-get update \
  && apt-get install -y libssl-dev \
  && docker-php-ext-install pdo

# Habilite mod_rewrite do Apache
RUN a2enmod rewrite

# Copie tudo para a pasta do Apache
COPY . /var/www/html/
