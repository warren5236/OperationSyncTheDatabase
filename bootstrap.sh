#!/usr/bin/env bash

sudo apt-get update
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password password rootpass'
sudo debconf-set-selections <<< 'mysql-server-5.5 mysql-server/root_password_again password rootpass'
sudo apt-get -y install mysql-server-5.5 php-pear php5-mysql
sudo pear upgrade pear
sudo pear channel-discover pear.phpunit.de
sudo pear channel-discover components.ez.no
sudo pear channel-discover pear.symfony.com
sudo pear install --alldeps phpunit/PHPUnit


echo "CREATE USER 'ostdtestuser'@'localhost' IDENTIFIED BY 'ostdtestpassword'" | mysql -uroot -prootpass
echo "CREATE DATABASE ostdtestuser" | mysql -uroot -prootpass
echo "GRANT ALL ON ostdtestuser.* TO 'ostdtestuser'@'localhost'" | mysql -uroot -prootpass
echo "flush privileges" | mysql -uroot -prootpass
