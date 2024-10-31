FROM php:8.1-apache

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite

# Configura o Apache para permitir .htaccess
RUN echo "<Directory /var/www/html/> \n\
    AllowOverride All \n\
    </Directory>" > /etc/apache2/conf-available/allow-override.conf \
    && a2enconf allow-override

# Instala a extensão pdo_mysql e outras dependências necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do projeto para o contêiner
COPY . .

# Instala as dependências do Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Configura o ServerName para suprimir o aviso
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf