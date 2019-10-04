#!/bin/bash

# Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


######################################

echo " "

if [ "$EUID" -ne 0 ]; then 
 echo "Please run as root (or sudo)."
 echo "Exiting..."
 exit
fi


######################################


# Get date
DATE=$(date '+%Y-%m-%d')


# Get the host ip address
IP=`/bin/hostname -I` 


# Get the operating system and version
if [ -f /etc/os-release ]; then
    # freedesktop.org and systemd
    . /etc/os-release
    OS=$NAME
    VER=$VERSION_ID
elif type lsb_release >/dev/null 2>&1; then
    # linuxbase.org
    OS=$(lsb_release -si)
    VER=$(lsb_release -sr)
elif [ -f /etc/lsb-release ]; then
    # For some versions of Debian/Ubuntu without lsb_release command
    . /etc/lsb-release
    OS=$DISTRIB_ID
    VER=$DISTRIB_RELEASE
elif [ -f /etc/debian_version ]; then
    # Older Debian/Ubuntu/etc.
    OS=Debian
    VER=$(cat /etc/debian_version)
elif [ -f /etc/SuSe-release ]; then
    # Older SuSE/etc.
    ...
elif [ -f /etc/redhat-release ]; then
    # Older Red Hat, CentOS, etc.
    ...
else
    # Fall back to uname, e.g. "Linux <version>", also works for BSD, etc.
    OS=$(uname -s)
    VER=$(uname -r)
fi


######################################

			
echo "Enter the FULL SYSTEM PATH to the document root of the web server:"
echo "(this does NOT automate setting apache's document root, you would need to do that manually)"
echo "(leave blank / hit enter to use the default value: /var/www/html)"
echo " "

read DOC_ROOT
        
if [ -z "$DOC_ROOT" ]; then
DOC_ROOT=${1:-/var/www/html}
echo "Using default website document root:"
echo "$DOC_ROOT"
else
echo "Using custom website document root:"
echo "$DOC_ROOT"
fi

echo " "

if [ ! -d "$DOC_ROOT" ] && [ "$DOC_ROOT" != "/var/www/html" ]; then
echo "The custom document root directory '$DOC_ROOT' does not exist yet."
echo "Please create this directory structure before running this script."
echo "Exiting..."
exit
fi


######################################


echo "TECHNICAL NOTE:"
echo "This script was designed to install / setup on the Raspian operating system,"
echo "and was developed / created on Raspbian Linux v10, for Raspberry Pi computers."
echo " "

echo "Your operating system has been detected as:"
echo "$OS v$VER"
echo " "

echo "This script may work on other Debian-based systems as well, but it has not been tested for that purpose."
echo "If you already have unrelated web site files located at $DOC_ROOT on your system, they may be affected."
echo "Please back up any important pre-existing files in that directory before proceeding."
echo " "

if [ -f "/etc/debian_version" ]; then
echo "Your system has been detected as Debian-based, which is compatible with this automated installation script."
echo "Continuing..."
echo " "
else
echo "Your system has been detected as NOT BEING Debian-based. Your system is NOT compatible with this automated installation script."
echo "Exiting..."
exit
fi
				
				
if [ -f $DOC_ROOT/config.php ]; then
echo "A configuration file from a previous install of DFD Cryptocoin Values has been detected on your system."
echo "During this upgrade / re-install, it will be backed up to:"
echo "$DOC_ROOT/config.php.BACKUP.$DATE.[random string]"
echo "This will save any custom settings within it."
echo "You will need to manually move any custom settings in this backup file to the new config.php file with a text editor."
echo " "
fi
  				
  				
echo "Select 1 or 2 to choose whether to continue, or quit."
echo " "

OPTIONS="continue quit"

select opt in $OPTIONS; do
        if [ "$opt" = "continue" ]; then
        echo " "
        echo "Continuing with setup..."
        break
       elif [ "$opt" = "quit" ]; then
        echo " "
        echo "Exiting setup..."
        exit
        break
       fi
done

echo " "


######################################


echo "Select 1 or 2 to choose whether to install the PHP web server, or skip."
echo " "

OPTIONS="install_webserver skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_webserver" ]; then
         
         echo " "
         
			echo "Making sure your system is updated before PHP web server installation..."
			
			echo " "
			
			/usr/bin/apt-get update
			
			/usr/bin/apt-get upgrade -y
			
			echo " "
			
			echo "Proceeding with PHP web server installation..."
			
			echo " "
			
			/usr/bin/apt-get install apache2 php php-curl php-gd php-zip libapache2-mod-php -y
			
			sleep 3
			
			echo " "
			
			mv -v $DOC_ROOT/index.html $DOC_ROOT/index.php
			
			echo " "
			
			echo "PHP web server installation is complete."

        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "Skipping PHP web server setup..."
        break
       fi
done

echo " "


######################################


echo "We need to find out what user group the web server belongs to."
echo " "

echo "Attempting to auto-detect the web server's user group..."
echo " "

WWW_GROUP=$(/bin/ps -ef | /bin/egrep '(httpd|httpd2|apache|apache2)' | /bin/grep -v `whoami` | /bin/grep -v root | /usr/bin/head -n1 | /usr/bin/awk '{print $1}')

echo "The web server's user group has been detected as:"

if [ -z "$WWW_GROUP" ]; then
WWW_GROUP="www-data"
echo "User group NOT detected, using default group 'www-data'"
else
echo "$WWW_GROUP"
fi

echo " "
echo "Enter the web server's user group:"
echo "(leave blank / hit enter to use default group '$WWW_GROUP')"
echo " "

read CUSTOM_GROUP
        
if [ -z "$CUSTOM_GROUP" ]; then
CUSTOM_GROUP=${1:-$WWW_GROUP}
echo "Using default user group: $WWW_GROUP"
else
echo "Using custom user group: $CUSTOM_GROUP"
fi

echo " "
echo "The web server's user group has been declared as:"
echo "$CUSTOM_GROUP"
echo " "


######################################


echo "We need to add the username you'll be logging in as,"
echo "to the '$CUSTOM_GROUP' web server user group to allow proper editing permissions..."
echo " "

echo "Enter the system username to allow web server editing access for:"
echo "(leave blank / hit enter for default of username 'pi')"
echo " "

read SYS_USER
        
if [ -z "$SYS_USER" ]; then
SYS_USER=${1:-pi}
echo "Using default username: $SYS_USER"
else
echo "Using username: $SYS_USER"
fi

chown -R $SYS_USER:$SYS_USER /var/www/*

/usr/sbin/usermod -a -G $CUSTOM_GROUP $SYS_USER

echo " "
echo "Web server editing access for user name '$SYS_USER', in web server user group '$CUSTOM_GROUP', is completed."
echo " "


######################################


echo "Do you want this script to automatically download the latest version of"
echo "DFD Cryptocoin Values from Github.com, and install / configure it?"
echo " "

echo "Select 1 or 2 to choose whether to auto-install DFD Cryptocoin Values, or skip it."
echo " "

OPTIONS="auto_install_coin_app skip"

select opt in $OPTIONS; do
        if [ "$opt" = "auto_install_coin_app" ]; then
        
        		if [ ! -d "$DOC_ROOT" ]; then
        		
        		echo " "
				
				echo "Directory $DOC_ROOT DOES NOT exist, cannot install DFD Cryptocoin Values."
				echo "Skipping auto-install of DFD Cryptocoin Values."
				else
				
				echo " "
				
				echo "Making sure your system is updated before installing required components..."
				
				echo " "
				
				/usr/bin/apt-get update
				
				/usr/bin/apt-get upgrade -y
				
				echo " "
				
				echo "Proceeding with required component installation..."
				
				echo " "
				
				/usr/bin/apt-get install curl jq bsdtar pwgen openssl -y
				
				echo " "
				
				echo "Required component installation completed."
				
				sleep 3
				
				echo " "
				
				echo "Downloading and installing the latest version of DFD Cryptocoin Values, from Github.com..."
				
				echo " "
				
				mkdir DFD-Cryptocoin-Values
				
				cd DFD-Cryptocoin-Values
				
				ZIP_DL=$(/usr/bin/curl -s 'https://api.github.com/repos/taoteh1221/DFD_Cryptocoin_Values/releases/latest' | /usr/bin/jq -r '.zipball_url')
				
				/usr/bin/wget -O DFD-Cryptocoin-Values.zip $ZIP_DL
				
				/usr/bin/bsdtar --strip-components=1 -xvf DFD-Cryptocoin-Values.zip
				
				rm DFD-Cryptocoin-Values.zip
				
				
					if [ -f $DOC_ROOT/config.php ]; then
					
					# Generate random string 16 characters long
					RAND_STRING=$(/usr/bin/pwgen -s 16 1)
					
					
						# If pwgen fails, use openssl
						if [ -z "$RAND_STRING" ]; then
  						RAND_STRING=$(/usr/bin/openssl rand -hex 12)
						fi
				
						# If openssl fails, create manually
						if [ -z "$RAND_STRING" ]; then
						echo " "
						echo "Automatic random hash creation has failed,"
						echo "please enter a random alphanumeric string of text (no spaces / symbols) at least 10 characters long."
						echo "If you skip this, no backup of the previous install's $DOC_ROOT/config.php file will be created (for security reasons),"
						echo "and YOU WILL LOSE ALL PREVIOUSLY-CONFIGURED SETTINGS."
						echo " "
  						read RAND_STRING
						fi
				
						# If $RAND_STRING has a value, backup config.php, otherwise don't create backup file (for security reasons)
						if [ ! -z "$RAND_STRING" ]; then
  							
						cp $DOC_ROOT/config.php $DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING
						
						chown $SYS_USER:$SYS_USER $DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING
						
						echo " "
						echo "Old configuration file $DOC_ROOT/config.php has been backed up to:"
						echo "$DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING"
						echo " "
						
  						else
  						echo "No backup of the previous install's $DOC_ROOT/config.php file was created (for security reasons)."
  						echo "The new install WILL NOW OVERWRITE ALL PREVIOUSLY-CONFIGURED SETTINGS in $DOC_ROOT/config.php..."
  						echo " "
						fi
						
					
  					fi
  				
  				
				# No trailing forward slash here
				\cp -r ./ $DOC_ROOT
				
				cd ../
				
				rm -rf DFD-Cryptocoin-Values
				
				rm -rf $DOC_ROOT/.github
				
				rm $DOC_ROOT/.gitattributes
				
				rm $DOC_ROOT/.gitignore
				
				chmod 777 $DOC_ROOT/cache
				
				chmod 755 $DOC_ROOT/cron.php
				
				# No trailing forward slash here
				chown -R $SYS_USER:$SYS_USER $DOC_ROOT
				
				echo " "
				
				echo "DFD Cryptocoin Values has been installed / configured."
				
	        	APP_SETUP=1
   	     	
  				fi

        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "Skipping auto-install of DFD Cryptocoin Values."
        break
       fi
done

echo " "


######################################


echo "If you want to use price alerts or charts, you'll need to setup a cron job for that."
echo " "

echo "IMPORTANT NOTE:"
echo "If you have --already setup a cron job PREVIOUSLY-- and need to reconfigure it,"
echo "skip the automated setup (to avoid creating duplicate cron jobs), and edit the"
echo "cron jobs manually with this command:"
echo "crontab -e"
echo " "

echo "Select 1 or 2 to choose whether to setup a cron job for price alerts / charts, or skip it."
echo " "

OPTIONS="auto_setup_cron skip"

select opt in $OPTIONS; do
        if [ "$opt" = "auto_setup_cron" ]; then
        
        echo " "
        echo "Enter the FULL system path to cron.php:"
        echo "(leave blank / hit enter for default of $DOC_ROOT/cron.php)"
        echo " "
        
        read PATH
        
        	if [ -z "$PATH" ]; then
			PATH=${1:-$DOC_ROOT/cron.php}
      	echo "Using default system path to cron.php:"
      	echo "$PATH"
			else
      	echo "System path set to cron.php:"
      	echo "$PATH"
			fi
        
        echo " "
        echo "Enter the time interval in minutes to run this cron job:"
        echo "(must be 5, 10, 15, 20, or 30...leave blank / hit enter for default of 15)"
        echo " "
        
        read INTERVAL
        
        	if [ -z "$INTERVAL" ]; then
			INTERVAL=${2:-15}
      	echo "Using default time interval of $INTERVAL minutes."
			else
      	echo "Time interval set to $INTERVAL minutes."
			fi
        
        CRONJOB="*/$INTERVAL * * * * /usr/bin/php -q $PATH"
		  (/usr/bin/crontab -u $SYS_USER -l; echo "$CRONJOB" ) | /usr/bin/crontab -u $SYS_USER -
        
        echo " "
        echo "A cron job has been setup for user '$SYS_USER', as cron command:"
        echo "*/$INTERVAL * * * * /usr/bin/php -q $PATH"
        echo " "
        
        echo "IMPORTANT NOTE:"
        echo "If everything is setup properly and the cron job still does NOT run,"
        echo "your particular server may require the cron.php file permissions to be set"
        echo "as 'executable' ('755' chmod on unix / linux systems) to allow running it."
        
        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "Skipping cron job setup."
        break
       fi
done

echo " "


######################################


echo "Enabling the built-in SSH server on your system allows easy remote"
echo "installation / updating of your web site files via SFTP (from another computer"
echo "on your home / internal network), with Filezilla or any other SFTP-enabled FTP software."
echo " "

echo "If you choose to NOT enable SSH on your system, you'll need to install / update your"
echo "web site files directly on the device itself (not recommended)."
echo " "

echo "If you do use SSH, ---make sure the password for username '$SYS_USER' is strong---,"
echo "because anybody on your home / internal network will have access if they know the username/password!"
echo " "

if [ -f "/usr/bin/raspi-config" ]; then
echo "Select 1 or 2 to choose whether to setup SSH (under 'Interfacing Options' in raspi-config), or skip it."
else
echo "Select 1 or 2 to choose whether to setup SSH, or skip it."
fi

echo " "

OPTIONS="setup_ssh skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_ssh" ]; then
        

				if [ -f "/usr/bin/raspi-config" ]; then
				echo " "
				echo "Initiating raspi-config..."
				# We need sudo here, or raspi-config fails in bash
				/usr/bin/sudo /usr/bin/raspi-config
				else
				echo " "
				
				echo "Installing openssh-server..."
				
				echo "Making sure your system is updated before installing openssh-server..."
				
				echo " "
				
				/usr/bin/apt-get update
				
				/usr/bin/apt-get upgrade -y
				
				echo " "
				
				echo "Proceeding with openssh-server installation..."
				
				echo " "
				
				/usr/bin/apt-get install openssh-server -y
				
				echo " "
				
				echo "openssh-server installation completed."
				fi
        
        
        SSH_SETUP=1
        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "Skipping SSH setup."
        break
       fi
done
       
echo " "

       
######################################

echo " "
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo "# SAVE THE INFORMATION BELOW FOR FUTURE ACCESS TO THIS APP #"
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo " "

if [ "$APP_SETUP" = "1" ]; then

echo "Web server setup and installation / configuration of DFD Cryptocoin Values"
echo "should now be complete (if you chose those options), unless you saw any"
echo "errors on screen during setup."
echo " "

echo "DFD Cryptocoin Values is located (and can be edited) inside this folder:"
echo "$DOC_ROOT"
echo " "

echo "You may now optionally edit the DFD Cryptocoin Values configuration file"
echo "(config.php) remotely via SFTP, or by editing app files locally."
echo " "

else

echo "Web server setup should now be complete (if you chose that option),"
echo "unless you saw any errors on screen during setup."
echo " "

echo "Web site app files must be placed inside this folder:"
echo "$DOC_ROOT"
echo " "

echo "If web server setup has completed successfully, DFD Cryptocoin Values"
echo "can now be installed (if you haven't already) in $DOC_ROOT remotely via SFTP,"
echo "or by copying over app files locally."
echo " "

fi


if [ "$SSH_SETUP" = "1" ]; then

echo "SFTP login details are..."

echo " "
echo "INTERNAL NETWORK SFTP host (port 22, on home / internal network):"
echo "$IP"
echo " "

echo "SFTP username: $SYS_USER"
echo "SFTP password: (password for system user $SYS_USER)"

echo " "
echo "SFTP remote working directory (where web site files should be placed on web server):"
echo "$DOC_ROOT"
echo " "

fi


echo "INTERNAL NETWORK HTTP web address (viewing web pages in web browser, on home / internal network) is:"
echo "http://$IP"
echo " "

echo "If you wish to allow internet access (when not on your home / internal network),"
echo "port forwarding on your router needs to be setup (preferably with strict router firewall rules,"
echo "to disallow the Raspberry Pi to request access to other machines on your home / internal network,"
echo "and only allow it to route outbound through the internet gateway)."
echo " "

echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"


######################################


#PHP fastCGI and suexec...not yet a good automated script, so disabled for now

#echo "https://cwiki.apache.org/confluence/display/httpd/PHP-FPM"
#echo "https://geekanddummy.com/how-to-raspberry-pi-tutorial-part-3-web-file-hosting-with-webmin-virtualmin"

#echo "Making sure your system is updated before installing required components..."
				
#/usr/bin/apt-get update
				
#/usr/bin/apt-get upgrade -y
				
#echo "Proceeding with required component installation..."

#/usr/bin/apt-get install php-fpm apache2-suexec-custom -y
				
#echo "Required component installation completed."

# Activates in Apache2 with following commands
#/usr/sbin/a2enmod proxy_fcgi setenvif
#/usr/sbin/a2enconf php7.3-fpm
#/usr/sbin/a2enmod suexec
#/usr/sbin/a2enmod actions


######################################


