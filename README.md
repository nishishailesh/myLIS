# myLIS
Apache, MySQL, PHP based  clinical laboratory information system

Everyone is welcome to use/modify and contribute.
Installation:
=============
download and extract zip (or if you are femiliar with git, use "git pull")

#Creation of mysql database

create mysql database named "alllab"

use alllab.sql to populate the database

following commands may be required to complate the steps
in the terminal:

mysql -uroot -pXXXXX 

in the mysql shell:

create database alllab

quit
in the terminal:

create a folder in your /var/www/html. e.g /var/www/html/alllab

copy all downloaded files of the folder into/var/www/html eg copy in to /var/www/html/alllab 

if required change folder owner to www-data
e.g run following commands
chown -R www-data /var/www/html/alllab 
chgrp -R www-data /var/www/html/alllab 

point your browser to the folder, login using your mysql username and password
Enjoy, mutate and improve.
If you wish to contribute to my github, email me at biochemistrygmcs@gmail.com
