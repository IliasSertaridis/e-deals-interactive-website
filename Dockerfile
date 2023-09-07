FROM php:8-apache
#WORKDIR /var/www

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get autoremove -y && \
    apt-get install vim cron apache2-utils -y

#COPY my-apache-site.conf /etc/apache2/sites-available/my-apache-site.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli && \
    a2enmod rewrite && \
    a2enmod cache && \
    a2enmod cache_disk && \
    a2enmod expires && \
    a2enmod headers && \
#    a2dissite 000-default && \
#    a2ensite my-apache-site && \
    service apache2 restart
