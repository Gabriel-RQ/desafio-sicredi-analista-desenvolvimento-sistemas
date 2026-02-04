# ==============================================================================
# ESTÁGIO 1: Build
# ==============================================================================
FROM php:8.4 AS build

# 1. Instala dependências de build
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && apt-get clean

# 2. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 3. Copia os arquivos do projeto
COPY . .

# 4. Executa o script de setup
RUN composer setup-docker

# ==============================================================================
# ESTÁGIO 2: Execução
# ==============================================================================
FROM php:8.4

# 1. Instala apenas dependências de Runtime
RUN apt-get update \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql
    
WORKDIR /var/www

# 2. Copia a aplicação pronta do estágio 'build'
COPY --from=build /var/www .

# 3. Ajusta permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan config:clear

EXPOSE 8000

# 4. Comando de execução
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]