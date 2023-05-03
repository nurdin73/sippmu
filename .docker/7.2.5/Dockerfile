FROM nimmis/apache:16.04

MAINTAINER nimmis <kjell.havneskold@gmail.com>

# disable interactive functions
ENV DEBIAN_FRONTEND noninteractive

RUN add-apt-repository ppa:jczaplicki/xenial-php74-temp && apt-get update && \
apt-get install -y php7.4 libapache2-mod-php7.4  \
php7.4-fpm php7.4-cli php7.4-mysql php7.4-pgsql php7.4-sqlite php7.4-redis \
php7.4-apcu php7.4-intl php7.4-imagick php7.4-json php7.4-gd php7.4-curl && \
rm -rf /var/lib/apt/lists/* && a2enmod rewrite && \
cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
