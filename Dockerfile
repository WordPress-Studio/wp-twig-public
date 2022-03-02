FROM ubuntu/apache2
LABEL maintainer="prosenjit@itobuz.com"
RUN apt-get update
RUN a2enmod rewrite && a2enmod headers && a2enmod expires
RUN apt install -y php libapache2-mod-php php-mysql
RUN apt install php-curl -y
RUN apt install php-dom -y
RUN apt-get install php-mbstring -y

RUN apt install zip unzip -y
RUN apt-get install php-zip -y
RUN apt-get install -y curl
RUN apt-get install -y ca-certificates
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN php wp-cli.phar --info
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp
RUN apt-get install -y mysql-client

# Default appache config
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./default-ssl.conf /etc/apache2/sites-available/default-ssl.conf


RUN apt install nano -y
RUN a2enmod ssl
RUN a2ensite default-ssl.conf

# Composer install
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet

RUN apt install imagemagick  php-imagick -y
RUN apt-get install -y webp
