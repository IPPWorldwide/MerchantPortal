FROM php:8.0-apache

RUN apt-get update
RUN apt-get install -y wget unzip
RUN php -v
USER www-data
WORKDIR /var/www/html/
RUN wget https://github.com/IPPWorldwide/IntegrationTestMerchantPortal/archive/refs/heads/main.zip -O main.zip
RUN unzip main.zip
WORKDIR /var/www/html/IntegrationTestMerchantPortal-main
RUN mv * /var/www/html/
