FROM php:7.1-apache
RUN apt-get update
RUN apt-get install -y vim
RUN docker-php-ext-install pdo_mysql
