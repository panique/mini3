#!/usr/bin/env bash

# Use single quotes instead of double quotes to make it work with special-character passwords.
# If you change it here, also change it in the config.php file!
PASSWORD='12345678'

# update / upgrade
sudo apt-get update
sudo apt-get -y upgrade

# php
sudo apt install -y php8.1-fpm
# php modules
sudo apt install -y php-xml
sudo apt install -y php-mbstring
sudo apt install -y php-zip

# nginx, copy nginx config into Vagrant box, syntax check, restart nginx
sudo apt install -y nginx
cp /var/www/html/_install/nginx/default  /etc/nginx/sites-available/default
sudo nginx -t
sudo systemctl restart nginx

# mysql (pw 12345678), user "root"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $PASSWORD"
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $PASSWORD"
sudo apt-get install -y mysql-server
sudo apt-get install -y php8.1-mysql

# run SQL statements
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/mysql/01-create-database.sql"
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/mysql/02-create-table-song.sql"
sudo mysql -h "localhost" -u "root" "-p${PASSWORD}" < "/var/www/html/_install/mysql/03-insert-demo-data-into-table-song.sql"

# phpmyadmin (and add symlink to it's reachable via /phpmyadmin)
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $PASSWORD"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect nginx"
sudo apt-get install -y phpmyadmin
sudo ln -s /usr/share/phpmyadmin /var/www/html/public/phpmyadmin

# install Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# install Git
sudo apt-get install -y git

# initial composer install, necessary to make the whole application work
cd /var/www/html && composer update

# delete demo file from nginx
cd /var/www/html && rm index.nginx-debian.html

# clickable link
echo "Hello, Hello! Click to start: http://192.168.56.77"