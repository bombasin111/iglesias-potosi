# Usar una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar los archivos de tu aplicación al contenedor
COPY . /var/www/html/

# Exponer el puerto 80 (puerto por defecto de Apache)
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]