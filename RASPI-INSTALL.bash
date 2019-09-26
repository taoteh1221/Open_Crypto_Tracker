#!/bin/bash


######################################


IP=`/bin/hostname -I`

echo "Making sure your system is updated before web server installation..."

/usr/bin/sudo apt-get update

/usr/bin/sudo apt-get upgrade -y

echo "Proceeding with web server installation..."

/usr/bin/sudo apt-get install apache2 php php-curl php-gd php-zip libapache2-mod-php -y

sleep 3

echo "Configuring web server for access by user 'pi'..."

/usr/bin/sudo mv -v /var/www/html/index.html /var/www/html/index.php

/usr/bin/sudo chown pi:pi /var/www/*

/usr/bin/sudo usermod -a -G www-data pi

######################################


echo "If you want to use price alerts or charts, you'll need to setup a cron job for that."

echo "IMPORTANT NOTE: If you have --already setup a cron job previously-- and need to reconfigure it, skip the automated setup (to avoid creating duplicate cron jobs), and edit the cron jobs manually with this command: crontab -e"

echo "Select 1 or 2, to choose whether to setup a cron job for price alerts / charts."

OPTIONS="setup_cron skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_cron" ]; then
        
        echo "Enter the FULL system path to cron.php (leave blank / hit enter for default of /var/www/html/cron.php)"
        
        read PATH
        
        	if [ -z "$PATH" ]
			then
			PATH=${1:-/var/www/html/cron.php}
      	echo "Using default system path to cron.php: $PATH"
			else
      	echo "System path set to cron.php: $PATH"
			fi
        
        echo "Enter the time interval in minutes to run this cron job (must be 5, 10, 15, 20, or 30...leave blank / hit enter for default of 15)"
        
        read INTERVAL
        
        	if [ -z "$INTERVAL" ]
			then
			INTERVAL=${2:-15}
      	echo "Using default time interval of $INTERVAL minutes."
			else
      	echo "Time interval set to $INTERVAL minutes."
			fi
        
        CRONJOB="*/$INTERVAL * * * * /usr/bin/php -q $PATH"
		  (/usr/bin/crontab -u pi -l; echo "$CRONJOB" ) | /usr/bin/crontab -u pi -
        
        echo "A cron job has been setup as cron command: */$INTERVAL * * * * /usr/bin/php -q $PATH"
        
        echo "IMPORTANT NOTE: If everything is setup properly and the cron job still does NOT run, your particular server may require the cron.php file permissions to be set as 'executable' ('755' chmod on unix / linux systems) to allow running it."
        
        break
       elif [ "$opt" = "skip" ]; then
        echo "Skipping cron job setup."
        break
       fi
done


######################################


echo "Enabling the built-in SSH server on your pi allows easy remote installation / updating of your website via SFTP (from another computer on your home network) with Filezilla or any other SFTP-enabled FTP software."

echo "If you choose to NOT enable SSH instead, you'll need to install / update your website directly on the pi (not recommended)."

echo "If you do use SSH, ---make sure the password for username 'pi' is strong---, because anybody on your wifi / internal home network will have access if they know the username/password!"

echo "Select 1 or 2, to choose whether to setup SSH (under 'Interfacing Options' in main config)."

OPTIONS="setup_ssh skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_ssh" ]; then
        /usr/bin/sudo raspi-config
        SSH_SETUP=1
        break
       elif [ "$opt" = "skip" ]; then
        echo "Skipping SSH setup."
        break
       fi
done

	if [ "$SSH_SETUP" = "1" ]; then
	echo "SFTP host: $IP (port 22)"
	
	echo "SFTP username: pi"
	
	echo "SFTP password: (password for system user pi)"
	
	echo "SFTP web server directory (where web site files should be placed): /var/www/html/"
   fi
       
######################################


echo "Raspberry pi web server setup should now be complete, unless you saw any errors on screen during setup."

echo "Raspi INTERNAL HOME NETWORK address (the main web page to view in your web browser) is: http://$IP"

echo "Web site files must be placed inside this folder: /var/www/html/"

echo "You may now install DFD Cryptocoin Values in /var/www/html/ on this machine via SFTP, or by copying over the app files locally."


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

