#!/bin/bash

cd Scripts/Bash/
./MigrateDatabase.sh up
./MigrateDatabase.sh seed

cd /var/www/html

/usr/sbin/apache2ctl -D FOREGROUND