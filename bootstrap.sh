#!/usr/bin/env bash

apt-get update

apt-get install -y zip unzip

apt-get install -y apache2
sudo a2enmod rewrite
cp  /vagrant/000-default.conf /etc/apache2/sites-available/000-default.conf
sudo service apache2 restart

debconf-set-selections <<< 'mysql-server mysql-server/root_password password toor'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password toor'
apt-get -y install mysql-server

#apt-get install -y php libapache2-mod-php php-mcrypt php-mysql php7.0-zip php7.0-mbstring php-xml php-curl
apt-get install -y php5 libapache2-mod-php5 php5-mcrypt php5-mysql php5-curl
php /vagrant/mysql_init.php

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
mv composer.phar /usr/local/bin/composer
echo "export PATH=~/.config/composer/vendor/bin:\$PATH" >> /home/vagrant/.bashrc

wget https://phar.phpunit.de/phpunit.phar
chmod +x phpunit.phar
mv phpunit.phar /usr/local/bin/phpunit

composer global require "laravel/installer"

apt-get install -y nodejs
apt-get install -y npm
npm install --global gulp

echo "Hurray! All done"