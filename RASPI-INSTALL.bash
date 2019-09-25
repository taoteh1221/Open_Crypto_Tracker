#!/bin/bash


######################################


/usr/bin/sudo apt-get update

/usr/bin/sudo apt-get upgrade -y

/usr/bin/sudo apt-get install apache2 php php-curl php-gd php-zip libapache2-mod-php -y

sleep 3

/usr/bin/sudo mv -v /var/www/html/index.html /var/www/html/index.php

/usr/bin/sudo chown www-data:www-data /var/www/*

/usr/bin/sudo usermod -a -G www-data pi

echo "User 'pi' added to group 'www-data' for website file access."

######################################


echo "If you want to use price alerts or charts, you'll need to setup a cron job for that."

echo "Select 1 or 2, to choose whether to setup a cron job for price alerts / charts."

OPTIONS="setup_cron skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_cron" ]; then
        
        echo "Enter the path to cron.php (leave blank / hit enter for default of /var/www/html/cron.php)"
        
        read PATH
        
        	if [ -z "$PATH" ]
			then
			PATH=${1:-/var/www/html/cron.php}
      	echo "Using default path to cron.php: $PATH"
			else
      	echo "Path set to cron.php: $PATH"
			fi
        
        echo "Enter time interval in minutes (must be 5, 10, 15, 20, or 30...leave blank / hit enter for default of 15)"
        
        read INTERVAL
        
        	if [ -z "$INTERVAL" ]
			then
			INTERVAL=${2:-15}
      	echo "Using default interval of $INTERVAL minutes."
			else
      	echo "Interval set to $INTERVAL minutes."
			fi
        
        CRONJOB="*/$INTERVAL * * * * /usr/bin/php -q $PATH"
		  (/usr/bin/crontab -u pi -l; echo "$CRONJOB" ) | /usr/bin/crontab -u pi -
        
        echo "Cron job setup as: */$INTERVAL * * * * /usr/bin/php -q $PATH"
        
        break
       elif [ "$opt" = "skip" ]; then
        echo "Skipping cron setup."
        break
       fi
done


######################################


echo "Enabling the built-in SSH server on your pi allows easy remote installation / updating of your website via SFTP (from another computer on your home network) with Filezilla or any other SFTP-enabled FTP software."

echo "If you choose to NOT enable SSH instead, you'll need to install / update your website directly on the pi (not recommended)."

echo "If you do use SSH, make sure the password for username 'pi' is strong, because anybody on your wifi / internal home network will have access if they know the username/password!"

echo "Select 1 or 2, to choose whether to setup SSH (under 'Interfacing Options' in main config)."

OPTIONS="setup_ssh skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_ssh" ]; then
        /usr/bin/sudo raspi-config
        break
       elif [ "$opt" = "skip" ]; then
        echo "Skipping SSH setup."
        break
       fi
done


######################################


echo "Raspberry pi webserver setup should now be complete, unless you saw any errors on screen during setup."

IP=`/bin/hostname -I`

echo "Raspi INTERNAL HOME NETWORK address (main web page) is: http://$IP"

echo "Website files must be placed inside this folder: /var/www/html/"


######################################

#PHP fastCGI and suexec...not yet a good automated script, so disabled for now

#echo "https://cwiki.apache.org/confluence/display/httpd/PHP-FPM"
#echo "https://geekanddummy.com/how-to-raspberry-pi-tutorial-part-3-web-file-hosting-with-webmin-virtualmin"

#/usr/bin/sudo apt-get install php-fpm apache2-suexec-custom -y

# Activates in Apache2 with following commands
#/usr/sbin/a2enmod proxy_fcgi setenvif
#/usr/sbin/a2enconf php7.3-fpm
#/usr/sbin/a2enmod suexec
#/usr/sbin/a2enmod actions

