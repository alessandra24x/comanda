FROM php:7-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql
EXPOSE 8080
