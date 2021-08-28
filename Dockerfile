FROM ubuntu/apache2
LABEL maintainer="prosenjit@itobuz.com"
RUN apt-get update
RUN a2enmod rewrite && a2enmod headers && a2enmod expires
RUN apt install -y php libapache2-mod-php php-mysql
RUN apt install php-curl -y
RUN apt install php-dom -y
RUN apt-get install php-mbstring -y
RUN apt install imagemagick  php-imagick -y
RUN apt install zip unzip -y
RUN apt-get install php-zip -y
RUN apt-get install -y curl
RUN apt-get install -y ca-certificates
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN php wp-cli.phar --info
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp
RUN apt-get install -y mysql-client
RUN apt-get install -y webp
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

# Enter in docker image
# docker exec -it wp-twig_wp-dev_1 /bin/bash 
# cd /var/www/html && wp db import db.sql --allow-root