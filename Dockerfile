FROM php:8.2-apache

# Instala extensões necessárias (ajuste aqui conforme seu uso)
RUN apt-get update \
  && apt-get install -y libssl-dev \
  && docker-php-ext-install pdo

# Habilita mod_rewrite (para .htaccess e URLs amigáveis)
RUN a2enmod rewrite

# Copia o ponto de entrada web (index.php e assets)
COPY public/ /var/www/html/

# Copia as dependências do Composer
COPY vendor/ /var/www/html/vendor/

# Copia os fontes PHP da aplicação
COPY src/ /var/www/html/src/

# Copia o arquivo de ambiente
COPY .env /var/www/html/.env

# Ajusta permissões (se necessário para ambiente Docker)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# (Opcional) Exibe arquivos copiados para debug
# RUN ls -la /var/www/html/
