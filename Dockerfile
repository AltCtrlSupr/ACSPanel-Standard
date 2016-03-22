FROM ubuntu

MAINTAINER Genar Trias <genar@acs.li>

RUN apt-get install -y --no-install-recommends software-properties-common

RUN apt-get install -y --no-install-recommends language-pack-en-base && LC_ALL=en_US.UTF-8 DEBIAN_FRONTEND=noninteractive add-apt-repository ppa:ondrej/php

RUN apt-get update && apt-get install -y curl git nginx php7.0-fpm php7.0-cli php7.0-xml php7.0-mysql php7.0-zip php7.0-curl nodejs npm
RUN npm install -g bower && ln -s /usr/bin/nodejs /usr/bin/node
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www
RUN chown -R www-data:www-data /var/www

RUN echo "\ndaemon off;" >> /etc/nginx/nginx.conf

COPY config/vhost.conf /etc/nginx/sites-enabled/default
COPY entrypoint.sh /root/entrypoint.sh

VOLUME ["/var/log/nginx/"]

EXPOSE 80
EXPOSE 443

ENTRYPOINT ["/root/entrypoint.sh"]
