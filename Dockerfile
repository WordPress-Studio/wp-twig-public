FROM ubuntu:20.04
LABEL maintainer="prosenjit@itobuz.com"
RUN apt-get update
RUN DEBIAN_FRONTEND=noninteractive apt install apache2 apache2-utils  -y
RUN a2enmod rewrite && a2enmod headers && a2enmod expires 
RUN apt-get install -y  unzip curl ca-certificates nano imagemagick webp wget

RUN apt-get install -y software-properties-common apt-transport-https -y
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update

# php 8.3
RUN apt install -y php8.3 php8.3-mysql php8.3-curl php8.3-dom php8.3-mbstring php8.3-zip php8.3-imagick libapache2-mod-php8.3 php8.3-intl

# Install php version 7.4
# RUN apt install -y php7.4 php7.4-mysql php7.4-curl php7.4-dom php7.4-mbstring php7.4-zip php7.4-imagick libapache2-mod-php7.4 php7.4-intl

# Install php version 5.6.40
# RUN apt install -y php5.6 php5.6-mysql php5.6-curl php5.6-dom php5.6-mbstring php5.6-zip php5.6-imagick libapache2-mod-php5.6 php5.6-intl

# Install WP CLI
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN php wp-cli.phar --info
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp
RUN apt-get install -y mysql-client

# Default appache config
COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

RUN a2enmod ssl
RUN a2ensite default-ssl.conf

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install php scanner
RUN wget https://raw.githubusercontent.com/marcocesarato/PHP-Antimalware-Scanner/master/dist/scanner --no-check-certificate -O /usr/bin/awscan.phar && \
printf "#!/bin/bash\nphp /usr/bin/awscan.phar \$@" > /usr/bin/awscan && \
chmod u+x,g+x /usr/bin/awscan.phar && \
chmod u+x,g+x /usr/bin/awscan && \
export PATH=$PATH":/usr/bin"

RUN apt-get install -y  git
RUN apt clean 
EXPOSE 80
ENTRYPOINT ["apache2ctl", "-D", "FOREGROUND"]