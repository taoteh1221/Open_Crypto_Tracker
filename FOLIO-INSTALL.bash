#!/bin/bash

# Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com


######################################

echo " "

if [ "$EUID" -ne 0 ]; then 
 echo "Please run as root (or sudo)."
 echo "Exiting..."
 exit
fi


######################################


# EXPLICITLY set paths 
#PATH=/bin:/usr/bin:/usr/local/bin:/sbin:/usr/sbin:/usr/local/sbin:$PATH


# Bash's FULL PATH
BASH_PATH=$(which bash)


# Get logged-in username (if sudo, this works best with logname)
TERMINAL_USERNAME=$(logname)


# Get date
DATE=$(date '+%Y-%m-%d')


# Get the host ip address
IP=`hostname -I` 


# Get a list of PHP packages THAT ARE ALREADY INSTALLED
PHP_INSTALLED=$(dpkg --get-selections | grep -i php)


# Get a list of PHP-FPM packages THAT ARE AVAILABLE TO INSTALL
PHP_FPM_LIST=$(apt-cache search php-fpm)


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



# Start in user home directory
# WE DON'T USE ~/ FOR PATHS IN THIS SCRIPT BECAUSE:
# 1) WE'RE #RUNNING AS SUDO# ANYWAYS (WE CAN INSTALL ANYWHERE WE WANT)
# 2) WE SET THE USER WE WANT TO INSTALL UNDER DYNAMICALLY
# 3) IN CASE THE USER INITIATES INSTALL AS ANOTHER ADMIN USER
cd /home/$TERMINAL_USERNAME


# For setting user agent header in curl, since some API servers !REQUIRE! a set user agent OR THEY BLOCK YOU
CUSTOM_CURL_USER_AGENT_HEADER="User-Agent: Curl (${OS}/$VER; compatible;)"

            
######################################
         

# WE NEED TO SET THIS OUTSIDE OF / BEFORE ANY OTHER SETUP LOGIC, AS WE'RE SETTING THE SYSTEM USER VAR
echo "We need to know the SYSTEM username you'll be logging in as on this machine to edit web files..."
echo " "
        
echo "Enter the SYSTEM username to allow web server editing access for:"
echo "(leave blank / hit enter for default username '${TERMINAL_USERNAME}')"
echo " "
        
read APP_USER

 if [ -z "$APP_USER" ]; then
 APP_USER=${1:-$TERMINAL_USERNAME}
 echo "Using default username: $APP_USER"
 else
 echo "Using username: $APP_USER"
 fi

echo " "


######################################

			
echo "Enter the FULL SYSTEM PATH to the document root of the web server:"
echo "(this does NOT automate setting apache's document root, you would need to do that manually)"
echo "(DO !NOT! INCLUDE A #TRAILING# FORWARD SLASH)"
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
echo "The defined document root directory '$DOC_ROOT' does not exist yet."
echo "Please create this directory structure before running this script."
echo "Exiting..."
exit
fi


######################################


echo "TECHNICAL NOTE:"
echo "This script was designed to install / setup on Ubuntu or Raspberry Pi OS, and MAY also work on other"
echo "Debian-based systems (but it has not been tested for that purpose)."
echo " "

echo "Your operating system has been detected as:"
echo " "
echo "$OS v$VER"
echo " "

echo "Recommended MINIMUM system specs:"
echo " "
echo "1 Gigahertz CPU / 512 Megabytes RAM / HIGH QUALITY 32 Gigabyte MicroSD card (running Nginx or Apache headless with PHP v7.2+)"
echo " "

echo "If you already have unrelated web site files located at $DOC_ROOT on your system, they may be affected."
echo "Please back up any important pre-existing files in that directory before proceeding."
echo " "

if [ -f "/etc/debian_version" ]; then
echo "Your system has been detected as Debian-based, which is compatible with this automated installation script."
echo " "
echo "Continuing..."
echo " "
else
echo "Your system has been detected as NOT BEING Debian-based. Your system is NOT compatible with this automated installation script."
echo " "
echo "Exiting..."
exit
fi
				
				
if [ -f $DOC_ROOT/config.php ]; then
echo "A configuration file from a previous install of Open Crypto Portfolio Tracker (Server Edition) has been detected on your system."
echo " "
echo "During this upgrade / re-install, it will be backed up to:"
echo " "
echo "$DOC_ROOT/config.php.BACKUP.$DATE.[random string]"
echo " "
echo "This will save any custom settings within it."
echo " "
echo "The bundled plugin's configuration files will also be backed up in the same manner."
echo " "
echo "You will need to manually move any CUSTOMIZED DEFAULT settings from backup files to the NEW configuration files with a text editor,"
echo "otherwise you can just ignore or delete the backup files."
echo " "

echo "VERY IMPORTANT UPGRADE NOTES:"
echo " "
echo " "

echo "v5.00.0 and higher of this app HAS COMPLETELY REFACTORED CONFIGURATION VARIABLE NAMES,"
echo "SO YOU'LL NEED TO MIGRATE ANY CUSTOM CONFIGS OVER TO THE PROPER NAMES WHERE APPLICABLE!"
echo " "
echo " "

echo "v4.29.3 and higher of this app HAS COMPLETELY REFACTORED CONFIGURATION VARIABLE NAMES,"
echo "SO YOU'LL NEED TO MIGRATE ANY CUSTOM CONFIGS OVER TO THE PROPER NAMES WHERE APPLICABLE!"
echo " "
echo " "

echo "v4.29.3 and higher of this app HAS COMPLETELY RESTRUCTERED THE PLUGIN FILE NAMES!"
echo "THE AUTO-INSTALL SCRIPT WILL #AUTOMATICALLY MIGRATE# BUNDLED PLUGIN FILES OVER TO"
echo "THE NEW FILE NAMES (AND WILL BACKUP YOUR PREVIOUS BUNDLED PLUGIN CONFIGS)."
echo " "
echo " "

echo "v4.27.0 and higher of this app RESETS ALL PLUGIN CACHES (A DIRECTORY STRUCTURE UPGRADE), "
echo "SO ANY PLUGIN ALERTS / TRACKING WILL BE RESET. THE !FIRST! PLUGIN RUNTIMES AFTER UPGRADING"
echo "WILL AUTOMATICALLY PREFORM THE RESET (ALERTS / TRACKING WILL BE ACCURATE AFTERWARDS)."
echo " "
echo " "

fi
  				

echo "VERY IMPORTANT SECURITY NOTES:"
echo " "
echo "YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA),"
echo "#WHEN YOU FIRST RUN THIS APP AFTER INSTALLATION#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#,"
echo "ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#."
echo " "

echo "!!VERY IMPORTANT INSTALL NOTICE!!:"
echo " "
echo "This auto-install script is ONLY FOR SELF-HOSTED ENVIRONMENTS, THAT #DO NOT# ALREADY"
echo "HAVE A WEB SERVER OR CONTROL PANEL INSTALLED ON THE SYSTEM. If this is a managed hosting"
echo "environment that a service provider has already provisioned, please quit this auto-install"
echo "session, and refer to the \"Manual Install\" section of the README.txt file documentation."
echo " "

echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Portfolio_Tracker/issues"
echo " "
  				
echo "Select 1 or 2 to choose whether to continue with installation, or quit."
echo " "

OPTIONS="continue quit"

select opt in $OPTIONS; do
        if [ "$opt" = "continue" ]; then
        echo " "
        echo "Continuing with setup, please wait..."
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


echo " "

echo "Making sure your system is updated before installation, please wait..."

echo " "
			
apt-get update

#DO NOT RUN dist-upgrade, bad things can happen, lol
apt-get upgrade -y

echo " "
				
echo "System update completed."
				
sleep 3
				
echo " "
        

######################################


echo "We need to know which version of PHP-FPM (fcgi) to use."
echo "Please select a PHP-FPM version NUMBER from the list below..."
echo "(PHP-FPM version 7.2 or greater is REQUIRED)"
echo " "

echo "$PHP_FPM_LIST"
echo " "

FPM_PACKAGE=`expr match "$PHP_FPM_LIST" '.*\(php[0-9][.][0-9]-fpm\)'`

FPM_PACKAGE_VER=`expr match "$FPM_PACKAGE" '.*\([0-9][.][0-9]\)'`

echo "#PREFERRED# PHP-FPM package auto-detected: $FPM_PACKAGE"
echo " "
        
echo "Enter the PHP-FPM version (numeric only) that you want to use:"
echo "(leave blank / hit enter for default of '$FPM_PACKAGE_VER')"
echo " "
        
read PHP_FPM_VER
                
	if [ -z "$PHP_FPM_VER" ]; then
 	PHP_FPM_VER=${1:-$FPM_PACKAGE_VER}
 	echo "Using default PHP-FPM version: $PHP_FPM_VER"
 	else
 	echo "Using custom PHP-FPM version: $PHP_FPM_VER"
 	fi
        
echo " "


######################################


echo "Select 1, 2, or 3 to choose whether to auto-install / remove the PHP web server, or skip."
echo " "

OPTIONS="install_webserver remove_webserver skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_webserver" ]; then
         
         echo " "
			
			echo "Proceeding with PHP web server installation, please wait..."
			echo " "
        
			# !!!RUN FIRST!!! PHP FPM (fcgi) version $PHP_FPM_VER, run SEPERATE in case it fails from package not found
        	INSTALL_FPM_VER="install php${PHP_FPM_VER}-fpm -y"
        
        	apt-get $INSTALL_FPM_VER
        	
			sleep 3
			
			# PHP FPM (fcgi), Apache, required modules, etc
			apt-get install apache2 php php-fpm php-mbstring php-xml php-curl php-gd php-zip libapache2-mod-fcgid apache2-suexec-pristine openssl ssl-cert avahi-daemon -y
			
			sleep 3
			
			echo " "
			
			mv $DOC_ROOT/index.html $DOC_ROOT/index.php > /dev/null 2>&1


			######################################
			

			# SSL / Rewrite setup
			
			echo " "
			
			# Regenerate new self-signed SSL cert keys with ssl-cert (for secure HTTPS web pages)
			make-ssl-cert generate-default-snakeoil --force-overwrite

			echo "New SSL certificate keys have been self-signed, please wait..."
			echo " "

			# Enable SSL (for secure HTTPS web pages)
			a2enmod ssl
			a2ensite default-ssl

			sleep 1
			
			echo " "

			# Enable mod-rewrite, for upcoming REST API features
			a2enmod rewrite	

			sleep 1
			
			# PHP FCGI Proxy
			a2enmod proxy_fcgi
			
			sleep 1
			
        	CONF_FPM_VER="php${PHP_FPM_VER}-fpm"
        	
			# Config PHP FPM (fcgi) version $PHP_FPM_VER
        	a2enconf $CONF_FPM_VER
			
			sleep 1
			
			# Suexec
			a2enmod suexec
			
			# Not needed (for now)
			#a2enmod actions
			
			sleep 1
			
			echo " "
				
				if [ -f /etc/init.d/apache2 ]; then
				echo "New Apache modules have been enabled, restarting the Apache web server, please wait..."
				/etc/init.d/apache2 restart
				echo " "
				else
				echo "New Apache modules have been enabled, YOU MUST RESTART the Apache web server for these to activate."
				echo " "
				fi


			######################################
			
       
         # Enable HTTP (port 80) htaccess
         #a2ensite 000-default
          
         HTTP_CONF="/etc/apache2/sites-available/000-default.conf"
            
            if [ ! -f $HTTP_CONF ]; then
            
            echo "$HTTP_CONF could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTP (port 80):"
            echo " "
            
            read HTTP_CONF
                    
                if [ ! -f $HTTP_CONF ] || [ -z "$HTTP_CONF" ]; then
                echo "No HTTP config file detected, skipping Apache htaccess setup for port 80, please wait..."
                SKIP_HTTP_HTACCESS=1
                else
                echo "Using Apache HTTP config file:"
                echo "$HTTP_CONF"
                CHECK_HTTP=$(<$HTTP_CONF)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTP=$(<$HTTP_CONF)
            
            fi
            
            
            
            if [ "$SKIP_HTTP_HTACCESS" != "1" ] && [[ $CHECK_HTTP != *"cryptocoin_htaccess_80"* ]]; then
            
            echo " "
            
            echo "Enabling htaccess for HTTP (port 80), please wait..."
            echo " "


# Don't nest / indent, or it could malform the setting addition            
read -r -d '' HTACCESS_HTTP <<- EOF
\r
\t#cryptocoin_htaccess_80
\t<Directory $DOC_ROOT>
\t\tOptions Indexes FollowSymLinks MultiViews
\t\tAllowOverride All
\t\tRequire all granted
\t</Directory>
\r
EOF
            
            
            # Backup the HTTP config before editing, to be safe
            \cp $HTTP_CONF $HTTP_CONF.BACKUP.$DATE
            
            
            # Create the new HTTP config
            NEW_HTTP_CONF=$(echo -e "$HTACCESS_HTTP" | sed '/:80>/r /dev/stdin' $HTTP_CONF)
            
            
            # Install the new HTTP config
            echo -e "$NEW_HTTP_CONF" > $HTTP_CONF

				sleep 1
                            
                            
                # Restart Apache
                if [ -f /etc/init.d/apache2 ]; then
                echo "Htaccess has been enabled for HTTP (port 80),"
                echo "restarting the Apache web server, please wait..."
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "Htaccess has been enabled for HTTP (port 80)."
                echo "YOU MUST RESTART the Apache web server for this to take affect."
                echo " "
                fi
            
            
            else
            
            echo " "
            echo "Htaccess was already enabled for HTTP (port 80)."
            echo " "
            
            fi
            

			sleep 2
            
         ######################################
                        
                                                
         # Enable HTTPS (port 443) htaccess
         #a2ensite default-ssl
         
         
         HTTPS_CONF="/etc/apache2/sites-available/default-ssl.conf"
            
            if [ ! -f $HTTPS_CONF ]; then
            
            echo "$HTTPS_CONF could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTPS (port 443):"
            echo " "
            
            read HTTPS_CONF
                    
                if [ ! -f $HTTPS_CONF ] || [ -z "$HTTPS_CONF" ]; then
                echo "No HTTPS config file detected, skipping Apache htaccess setup for port 443, please wait..."
                SKIP_HTTPS_HTACCESS=1
                else
                echo "Using Apache HTTPS config file:"
                echo "$HTTPS_CONF"
                CHECK_HTTPS=$(<$HTTPS_CONF)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTPS=$(<$HTTPS_CONF)
            
            fi
            
            
            
            if [ "$SKIP_HTTPS_HTACCESS" != "1" ] && [[ $CHECK_HTTPS != *"cryptocoin_htaccess_443"* ]]; then
            
            echo " "
            echo "Enabling htaccess for HTTPS (port 443), please wait..."
            echo " "
            
            
            
# Don't nest / indent, or it could malform the setting addition  
read -r -d '' HTACCESS_HTTPS <<- EOF
\r
\t#cryptocoin_htaccess_443
\t<Directory $DOC_ROOT>
\t\tOptions Indexes FollowSymLinks MultiViews
\t\tAllowOverride All
\t\tRequire all granted
\t</Directory>
\r
EOF
            
            
            # Backup the HTTPS config before editing, to be safe
            \cp $HTTPS_CONF $HTTPS_CONF.BACKUP.$DATE
            
            
            # Create the new HTTPS config
            NEW_HTTPS_CONF=$(echo -e "$HTACCESS_HTTPS" | sed '/:443>/r /dev/stdin' $HTTPS_CONF)
            
            
            # Install the new HTTPS config
            echo -e "$NEW_HTTPS_CONF" > $HTTPS_CONF

				sleep 1
                            
                            
                # Restart Apache
                if [ -f /etc/init.d/apache2 ]; then
                echo "Htaccess has been enabled for HTTPS (port 443),"
                echo "restarting the Apache web server, please wait..."
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "Htaccess has been enabled for HTTPS (port 443)."
                echo "YOU MUST RESTART the Apache web server for this to take affect."
                echo " "
                fi
            
            
            else
            
            echo " "
            echo "Htaccess was already enabled for HTTPS (port 443)."
            echo " "
            
            fi


			sleep 2

			######################################
			
			
			echo " "
			echo "PHP web server installation is complete."
         echo " "
			
			
         ######################################
            
            
         # Give the new HTTP server system user a chance to exist for a few seconds, before trying to determine the name / group automatically
         echo "Attempting to auto-detect the web server's user group, please wait..."
         echo " "
         
         sleep 3
           
         #WWW_GROUP=$(ps -ef | egrep '(httpd|httpd2|apache|apache2)' | grep -v `whoami` | grep -v root | head -n1 | awk '{print $1}')
         WWW_GROUP=$(ps axo user,group,comm | egrep '(httpd|httpd2|apache|apache2)' | grep -v ^root | cut -d\  -f 2 | uniq)
            
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
            echo "The web server's user group has been declared as: $WWW_GROUP"
            else
            echo "The web server's user group has been declared as: $CUSTOM_GROUP"
            fi
            
         echo " "
        
        	usermod -a -G $CUSTOM_GROUP $APP_USER
        
        	echo " "
        	echo "Access for user '$APP_USER' within group '$CUSTOM_GROUP' is completed, please wait..."

			sleep 1
        
        	usermod -a -G $APP_USER $CUSTOM_GROUP
        	
        	echo " "
        	echo "Access for user '$CUSTOM_GROUP' within group '$APP_USER' is completed, please wait..."

			sleep 1
			
        	chmod 775 $DOC_ROOT
			
        	echo " "
        	echo "Document root access is completed (chmod 775, owner:group set to '$APP_USER'), please wait..."

			sleep 1
        
        	BASE_HTDOC="$(dirname $DOC_ROOT)"
        
        	RECURSIVE_CHOWN="-R ${APP_USER}:$APP_USER ${BASE_HTDOC}/*"
        
        	#$RECURSIVE_CHOWN must be in double quotes to escape the asterisk at the end
        	chown $RECURSIVE_CHOWN

			sleep 3
        
			echo " "
			echo "PHP web server configuration is complete."
        
        	######################################
         
         
        break
       elif [ "$opt" = "remove_webserver" ]; then
       
        echo " "
        echo "Removing PHP web server, please wait..."
        echo " "
        
        # WE USE --purge TO REMOVE ANY MISCONFIGURATIONS, IN CASE SOMEBODY IS TRYING A UN-INSTALL / RE-INSTALL TO FIX THINGS
        
		  # !!!RUN FIRST!!! PHP FPM (fcgi) version $PHP_FPM_VER, run SEPERATE in case it fails from package not found
        REMOVE_FPM_VER="--purge remove php${PHP_FPM_VER}-fpm -y"
        
        apt-get $REMOVE_FPM_VER
        
		  sleep 3
        
        # SKIP removing openssl / ssl-cert / avahi-daemon, AS THIS WILL F!CK UP THE WHOLE SYSTEM, REMOVING ANY OTHER DEPENDANT PACKAGES TOO!!
		  apt-get --purge remove apache2 php php-fpm php-mbstring php-xml php-curl php-gd php-zip libapache2-mod-fcgid apache2-suexec-pristine -y
        
		  sleep 3
			
		  echo " "
		  echo "PHP web server has been removed from the system."
        
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "Skipping PHP web server setup..."
        
        break
       fi
done

echo " "


######################################


echo "Do you want this script to automatically download the latest version of Open Crypto Portfolio Tracker"
echo "(Server Edition) from Github.com, and install / configure it?"
echo " "

echo "Select 1, 2, or 3 to choose whether to auto-install / remove Open Crypto Portfolio Tracker (Server Edition), or skip."
echo "(!WARNING!: REMOVING Open Crypto Portfolio Tracker WILL DELETE *EVERYTHING* IN $DOC_ROOT !!)"
echo " "

OPTIONS="install_portfolio_app remove_portfolio_app skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_portfolio_app" ]; then
        
        		if [ ! -d "$DOC_ROOT" ]; then
        		
        		echo " "
				
				echo "Directory $DOC_ROOT DOES NOT exist, cannot install Open Crypto Portfolio Tracker."
				echo "Skipping auto-install of Open Crypto Portfolio Tracker."
				else
				
				echo " "
				echo "Proceeding with required component installation, please wait..."
				
				echo " "
				
				# bsdtar installs may fail (essentially the same package as libarchive-tools),
				# SO WE RUN BOTH SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES INSTALL OK AFTERWARDS
				
				echo "(you can safely ignore any upcoming 'bsdtar' install errors, if 'libarchive-tools'"
				echo "installs OK...and visa versa, as they are essentially the same package)"
				echo " "
				
				# Ubuntu 16.x, and other debian-based systems
				apt-get install bsdtar -y
				
				sleep 3
				
				# Ubuntu 18.x and higher
				apt-get install libarchive-tools -y
				
				sleep 3
				
				# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
				apt-get install curl jq pwgen openssl wget -y

				sleep 3
				
				echo " "
				echo "Required component installation completed."
				
				echo " "
				echo "Downloading / installing the latest version of Open Crypto Portfolio Tracker (Server Edition) from Github.com, please wait..."
            echo " "
				
				mkdir DFD-Cryptocoin-Values
				
				cd DFD-Cryptocoin-Values
				
				# Set curl user agent, as the github API REQUIRES ONE
				curl -H "$CUSTOM_CURL_USER_AGENT_HEADER"
				
				ZIP_DL=$(curl -s 'https://api.github.com/repos/taoteh1221/Open_Crypto_Portfolio_Tracker/releases/latest' | jq -r '.zipball_url')
				
				wget -O DFD-Cryptocoin-Values.zip $ZIP_DL
				
				sleep 2
				
				echo " "
				echo "Extracting download archive, please wait..."
				echo " "
				
				bsdtar --strip-components=1 -xvf DFD-Cryptocoin-Values.zip

				sleep 3
				
				rm DFD-Cryptocoin-Values.zip
				
				
					if [ -f $DOC_ROOT/config.php ]; then
					
					# Generate random string 16 characters long
					RAND_STRING=$(pwgen -s 16 1)
					
					
						# If pwgen fails, use openssl
						if [ -z "$RAND_STRING" ]; then
  						RAND_STRING=$(openssl rand -hex 12)
						fi
				
						# If openssl fails, create manually
						if [ -z "$RAND_STRING" ]; then
						echo " "
						echo "Automatic random hash creation has failed, please enter a random alphanumeric string"
						echo "of text (no spaces / symbols) at least 10 characters long."
						echo " "
						echo "IF YOU SKIP THIS, no backup of the previous install's configuration files will be created (for security reasons),"
						echo "and YOU WILL LOSE ALL PREVIOUSLY-CONFIGURED SETTINGS."
						echo " "
  						read RAND_STRING
						fi
				
						# If $RAND_STRING has a value, backup config.php, otherwise don't create backup file (for security reasons)
						if [ ! -z "$RAND_STRING" ]; then
  						
				
						echo " "
						echo "Backing up old configuration file(s) before upgrading, please wait..."
						echo " "
						
  						
  						# MIGRATE OLD FILE NAMING BEFORE BACKING UP...

						
  						# 'address-balance-tracker' plugin config MIGRATION (NEW FILE NAME)
  						MOVE_CONF="/plugins/recurring-reminder"
						mv $DOC_ROOT$MOVE_CONF/plugin-config.php $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						
  						# 'price-target-alert' plugin config MIGRATION (NEW FILE NAME)
  						MOVE_CONF="/plugins/price-target-alert"
						mv $DOC_ROOT$MOVE_CONF/plugin-config.php $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						
  						# 'recurring-reminder' plugin config MIGRATION (NEW FILE NAME)
  						MOVE_CONF="/plugins/recurring-reminder"
						mv $DOC_ROOT$MOVE_CONF/plugin-config.php $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$MOVE_CONF/plug-conf.php > /dev/null 2>&1
						
						sleep 3
						
				
						# NOW THAT WE'VE MIGRATED FROM OLDER FILE NAMES, PROCEED WITH BACKUPS...
						
						
  						# Main config
  						BACKUP_CONF="/config.php"
						cp $DOC_ROOT$BACKUP_CONF $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						
  						# 'address-balance-tracker' plugin config
  						BACKUP_CONF="/plugins/recurring-reminder/plug-conf.php"
						cp $DOC_ROOT$BACKUP_CONF $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						
  						# 'price-target-alert' plugin config
  						BACKUP_CONF="/plugins/price-target-alert/plug-conf.php"
						cp $DOC_ROOT$BACKUP_CONF $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						
  						# 'recurring-reminder' plugin config
  						BACKUP_CONF="/plugins/recurring-reminder/plug-conf.php"
						cp $DOC_ROOT$BACKUP_CONF $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						
						sleep 3
						
						CONFIG_BACKUP=1
				
						
  						else
  						echo " "
  						echo "No backup of the previous install's configuration files was created (for security reasons)."
  						echo "The new install WILL NOW OVERWRITE ALL PREVIOUSLY-CONFIGURED SETTINGS in $DOC_ROOT/config.php..."
  						echo " "
						fi
						
					
  					fi
  				
  				
				echo " "
				echo "Making sure any previous install's DEPRECIATED directories / files are cleaned up, please wait..."
				
  				# Delete old directory / file structures
  				
  				# Directories
  				rm -rf $DOC_ROOT/app-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/backups > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/apis > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/1_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/3_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/7_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/30_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/90_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/180_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/365_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/730_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/1460_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/all_day > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/1_week > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/1_month > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/3_months > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/6_months > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/1_year > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/2_years > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/4_years > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite/all > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/logs/debugging/api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/logs/errors/api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/queue > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/rest-api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/secured/apis > /dev/null 2>&1
  				rm -rf $DOC_ROOT/misc-docs-etc > /dev/null 2>&1
  				rm -rf $DOC_ROOT/templates > /dev/null 2>&1
  				rm -rf $DOC_ROOT/ui-templates > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cron-plugins > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/address-balance-tracker/plugin-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/price-target-alert/plugin-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/recurring-reminder/plugin-lib > /dev/null 2>&1

				sleep 3
				
  				# Files
				rm $DOC_ROOT/DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/CRON_PLUGINS_README.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/CRON-PLUGINS-README.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/RASPBERRY-PI-HEADLESS-WIFI-SSH.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/RASPBERRY-PI-SECURITY.txt > /dev/null 2>&1
				rm $DOC_ROOT/CONFIG.EXAMPLE.txt > /dev/null 2>&1
				rm $DOC_ROOT/HELP-FAQ.txt > /dev/null 2>&1
				rm $DOC_ROOT/PORTFOLIO-IMPORT-EXAMPLE-SPREADSHEET.csv > /dev/null 2>&1
				rm $DOC_ROOT/oauth.php > /dev/null 2>&1
				rm $DOC_ROOT/webhook.php > /dev/null 2>&1
				rm $DOC_ROOT/rest-api.php > /dev/null 2>&1
				rm $DOC_ROOT/logs.php > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/app_config_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_app_config_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_ocpt_conf_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/lite_chart_structure.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_btc_prim_curr_pairing.dat > /dev/null 2>&1
				
				# Force-resets script timeout from config.php (automatically / dynamically re-created by app)
				rm $DOC_ROOT/.htaccess > /dev/null 2>&1 
				rm $DOC_ROOT/.user.ini > /dev/null 2>&1
				#

				sleep 3
				
				echo " "
				echo "Installing Open Crypto Portfolio Tracker (Server Edition), please wait..."
  				
  				# Copy over the upgrade install files to the install directory, after cleaning up dev files
				# No trailing forward slash here
				
				rm -rf .github > /dev/null 2>&1
				rm -rf .git > /dev/null 2>&1

				sleep 3
				
				rm .gitattributes > /dev/null 2>&1
				rm .gitignore > /dev/null 2>&1
				rm .travis.yml > /dev/null 2>&1
				rm CODEOWNERS > /dev/null 2>&1
				
				\cp -r ./ $DOC_ROOT

				sleep 3
				
				cd ../
				
				rm -rf DFD-Cryptocoin-Values
				
				chmod 777 $DOC_ROOT/cache
				chmod 755 $DOC_ROOT/cron.php

				sleep 1
				
				# No trailing forward slash here
				chown -R $APP_USER:$APP_USER $DOC_ROOT

				sleep 3
				
				echo " "
				echo "Open Crypto Portfolio Tracker (Server Edition) has been installed."
				
				
            ######################################
            
            
				echo " "
            echo "If you want to use price alerts or charts, you'll need to setup a cron job for that."
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
                    
                    read SYS_PATH
                    
                        if [ -z "$SYS_PATH" ]; then
                        SYS_PATH=${1:-$DOC_ROOT/cron.php}
                    		echo "Using default system path to cron.php:"
                    		echo " "
                    		echo "$SYS_PATH"
                        else
                    		echo "System path set to cron.php:"
                    		echo " "
                    		echo "$SYS_PATH"
                        fi
                    
                    echo " "
                    echo "Options for choosing a time interval to run the background task (cron job)..."
                    echo " "
                    echo "IT'S RECOMMENDED TO GO #NO LOWER THAN# EVERY 20 MINUTES FOR CHART DATA, OTHERWISE LITE CHART"
                    echo "DISK WRITES MAY BE EXCESSIVE FOR LOWER END HARDWARE (Raspberry PI MicroSD cards etc)."
                    echo " "
                    echo "Enter the time interval in minutes to run this cron job:"
                    echo "(#MUST BE# either 5, 10, 15, 20, or 30...leave blank / hit enter for default of 20)"
                    echo " "
                    
                    read INTERVAL
                    
                        if [ -z "$INTERVAL" ]; then
                        INTERVAL=${2:-20}
                    		echo "Using default time interval of $INTERVAL minutes."
                        else
                    		echo "Time interval set to $INTERVAL minutes."
                        fi
                    
                            
                    # Setup cron (to check logs after install: tail -f /var/log/syslog | grep cron -i)
                    
                    
						  # PHP FULL PATHS
						  PHP_FPM_PATH=$(which php${PHP_FPM_VER})
						  PHP_PATH=$(which php)
         					
         					# If PHP $PHP_FPM_VER specific CLI binary not found, use the standard path
						  		if [ -f $PHP_FPM_PATH ]; then
						  		CRONJOB="*/$INTERVAL * * * * $APP_USER $PHP_FPM_PATH -q $SYS_PATH > /dev/null 2>&1"
						  		else
						  		CRONJOB="*/$INTERVAL * * * * $APP_USER $PHP_PATH -q $SYS_PATH > /dev/null 2>&1"
						  		fi
            
            
                    # Play it safe and be sure their is a newline after this job entry
                    echo -e "$CRONJOB\n" > /etc/cron.d/cryptocoin

						  sleep 1
                      
                    # cron.d entries must be a permission of 644
                    chmod 644 /etc/cron.d/cryptocoin

						  sleep 1
                      
                    # cron.d entries MUST BE OWNED BY ROOT, OR THEY CRASH!
                    chown root:root /etc/cron.d/cryptocoin
                      
                    
                    echo " "
                    echo "A cron job has been setup for user '$APP_USER',"
                    echo "as a command in /etc/cron.d/cryptocoin:"
                    echo " "
                    echo "$CRONJOB"
                    echo " "
				
						  echo " "
						  echo "Open Crypto Portfolio Tracker (Server Edition) has been configured."
                    
                    CRON_SETUP=1
                    
                    break
                   elif [ "$opt" = "skip" ]; then
                   
                    echo " "
                    echo "Skipping cron job setup."
            		  echo " "
            
                    break
                   fi
            done
            
            
            ######################################
            
				
	        	APP_SETUP=1
	        	
   	     	
  				fi

        break
       elif [ "$opt" = "remove_portfolio_app" ]; then
       
        echo " "
        echo "Removing Open Crypto Portfolio Tracker (Server Edition), please wait..."
        
        rm /etc/cron.d/cryptocoin
		  
        rm $DOC_ROOT/.htaccess
        
        rm -rf $DOC_ROOT/*

		  sleep 3
        
		  echo " "
		  echo "Open Crypto Portfolio Tracker (Server Edition) has been removed from the system."
        
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "Skipping auto-install of Open Crypto Portfolio Tracker (Server Edition)."
        
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

echo "If you do use SSH, ---make sure the password for username '$APP_USER' is strong---,"
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
				echo "Initiating raspi-config, please wait..."
				# WE NEED SUDO HERE, or raspi-config fails in bash
				sudo raspi-config
				else
				echo " "
				
				echo "Proceeding with openssh-server installation, please wait..."
				
				echo " "
				
				apt-get install openssh-server -y
				
				sleep 3
				
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

# Return to user's home directory
cd /home/$APP_USER/


echo " "
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo "# SAVE THE INFORMATION BELOW FOR FUTURE ACCESS TO THIS APP #"
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo " "



if [ "$APP_SETUP" = "1" ]; then

echo "Web server setup and installation / configuration of Open Crypto Portfolio Tracker (Server Edition)"
echo "should now be complete (if you chose those options), unless you saw any errors on screen during setup."
echo " "

echo "Open Crypto Portfolio Tracker is located at (and can be edited) inside this folder:"
echo " "
echo "$DOC_ROOT"
echo " "

echo "You may now optionally edit the APP DEFAULT CONFIG (configuration file config.php) remotely via SFTP,"
echo "or by editing app files locally."
echo " "


    if [ "$CONFIG_BACKUP" = "1" ]; then
    
    echo "The previously-installed configuration file $DOC_ROOT/config.php has been backed up to:"
	 echo " "
    echo "$DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING"
	 echo " "
	 echo "The bundled plugin's configuration files were also be backed up in the same manner."
	 echo " "
	 echo "You will need to manually move any CUSTOMIZED DEFAULT settings from backup files to the NEW configuration files with a text editor,"
	 echo "otherwise you can just ignore or delete the backup files."
    echo " "
    
    fi
    
    
    if [ "$CRON_SETUP" = "1" ]; then
    
    echo "A cron job has been setup for user '$APP_USER', as a command in /etc/cron.d/cryptocoin:"
	 echo " "
    echo "$CRONJOB"
    echo " "
    
    fi


else

echo "Web server setup should now be complete (if you chose that option), unless you saw any errors on screen during setup."
echo " "

echo "Web site app files must be placed inside this folder:"
echo " "
echo "$DOC_ROOT"
echo " "

echo "If web server setup has completed successfully, Open Crypto Portfolio Tracker (Server Edition) can now be"
echo "installed (if you haven't already) in $DOC_ROOT remotely via SFTP, or by copying over app files locally."
echo " "

fi



if [ "$SSH_SETUP" = "1" ]; then

echo "SFTP login details are..."
echo " "

echo "INTERNAL NETWORK SFTP host (port 22, on home / internal network):"
echo " "
echo "$IP"
echo " "

echo "SFTP username: $APP_USER"
echo " "
echo "SFTP password: (password for system user $APP_USER)"
echo " "

echo "SFTP remote working directory (where web site files should be placed on web server):"
echo " "
echo "$DOC_ROOT"
echo " "

fi



echo "#INTERNAL# NETWORK SSL / HTTPS (secure / private SSL connection) web addresses are..."
echo " "
echo "IP ADDRESS (may change, unless set as static for this device within the router):"
echo " "
echo "https://$IP"
echo " "
echo "HOST ADDRESS (ONLY works on linux / mac / windows, NOT android as of 2020):"
echo " "
echo "https://${HOSTNAME}.local"
echo " "

echo "IMPORTANT NOTES:"
echo "YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA),"
echo "#WHEN YOU FIRST RUN THIS APP#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#,"
echo "ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#."
echo " "
echo "The SSL certificate created on this web server is SELF-SIGNED (not issued by a CA),"
echo "so your browser ---will give you a warning message--- when you visit the above HTTPS addresses."
echo "This is --normal behavior for self-signed certificates--. Google search for"
echo "'self-signed ssl certificate' for more information on the topic."
echo "THAT SAID, ONLY TRUST SELF-SIGNED CERTIFICATES #IF YOUR COMPUTER CREATED THE CERTIFICATE#."
echo "!NEVER! TRUST SELF-SIGNED CERTIFICATES SIGNED BY THIRD PARTIES!"
echo " "

echo "If you wish to allow external access to this app (when not on your home / internal network),"
echo "port forwarding and dynamic DNS on your router needs to be setup (preferably with strict firewall"
echo "rules using a 'guest network' configuration, to disallow this device requesting access to other machines"
echo "on your home / internal network, and only allow it an access route through the internet gateway)."
echo " "
echo "A #VERY HIGH# port number is recommended (FREE / AVAILABLE port range is 1,024 to 65,535), to help avoid"
echo "port scanning bots from detecting your machine (and then starting hack attempts on your bound port)."
echo " "

echo "SEE /DOCUMENTATION-ETC/RASPBERRY-PI/ for additional information on securing and"
echo "setting up Raspberry Pi OS (disabling bluetooth, firewall setup, remote login, hostname, etc)."
echo " "

echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"


######################################


echo " "
echo "BE SURE TO SAVE ALL THE ACCESS DETAILS PRINTED OUT ABOVE, BEFORE YOU SIGN OFF FROM THIS TERMINAL SESSION."
echo " "
echo "Also check out my 100% FREE open source multi-crypto slideshow ticker for Raspberry Pi LCD screens:"
echo " "
echo "https://sourceforge.net/projects/dfd-crypto-ticker"
echo " "
echo "https://github.com/taoteh1221/Slideshow_Crypto_Ticker"
echo " "

echo "ANY DONATIONS (LARGE OR SMALL) HELP SUPPORT DEVELOPMENT OF MY APPS..."
echo " "
echo "Bitcoin: 3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW"
echo " "
echo "Ethereum: 0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8"
echo " "



######################################



echo "Would you like to ADDITIONALLY install Slideshow Crypto Ticker, multi-crypto slideshow ticker for Raspberry Pi LCD screens on this machine?"
echo " "

echo "Select 1 or 2 to choose whether to install the crypto ticker for Raspberry Pi LCD screens, or skip."
echo " "

OPTIONS="install_crypto_ticker skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_crypto_ticker" ]; then
         
			
			echo " "
			
			echo "Proceeding with crypto ticker installation, please wait..."
			
			echo " "
			
			wget --no-cache -O TICKER-INSTALL.bash https://raw.githubusercontent.com/taoteh1221/Slideshow_Crypto_Ticker/main/TICKER-INSTALL.bash
			
			chmod +x TICKER-INSTALL.bash
			
			chown $APP_USER:$APP_USER TICKER-INSTALL.bash
			
			./TICKER-INSTALL.bash
			
			
        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "Skipping crypto ticker install..."
        break
       fi
done

echo " "


######################################

