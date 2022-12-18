FROM php:8-apache
#WORKDIR /var/www

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get autoremove -y && \
    apt-get install vim -y

#COPY my-apache-site.conf /etc/apache2/sites-available/my-apache-site.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite && \
#    a2dissite 000-default && \
#    a2ensite my-apache-site && \
    service apache2 restart
