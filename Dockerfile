FROM gtrias/dev-base
MAINTAINER Genar Trias <genar@acs.li>
ADD . /acspanel
WORKDIR /acspanel
RUN apt-get update && apt-get install -y php5 \
        php5-sqlite \
        php5-curl \
        curl \
        acl
RUN rm -rf app/cache/* && rm -rf app/logs/* && HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1` && setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs && setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN composer install && php app/console avanzu:admin:fetch-vendor
RUN php app/console fixtures:load
CMD ["php", "app/console", "server:run", "0.0.0.0:8000"]
