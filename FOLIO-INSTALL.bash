#!/bin/bash

# Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


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


# For setting user agent header in curl, since some API servers !REQUIRE! a set user agent OR THEY BLOCK YOU
CUSTOM_CURL_USER_AGENT_HEADER="User-Agent: Curl (${OS}/$VER; compatible;)"


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
echo "The custom document root directory '$DOC_ROOT' does not exist yet."
echo "Please create this directory structure before running this script."
echo "Exiting..."
exit
fi


######################################


echo "TECHNICAL NOTE:"
echo "This script was designed to install / setup on the Raspbian operating system,"
echo "and was developed / created on Raspbian Linux v10, for Raspberry Pi computers."
echo " "

echo "Your operating system has been detected as:"
echo "$OS v$VER"
echo " "

echo "Recommended minimum system specs: Raspbian Lite / Raspberry Pi Zero / 512 megabytes of RAM"
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
echo "IMPORTANT UPGRADE NOTES: "
echo " "
echo "v4.06.0 / v4.07.7 / v4.10.1 HAVE MAJOR OVERHAULS TO CONFIGURATION VARIABLE NAMES (FOR MODULARITY,"
echo "SEMANTICS, AND EASY-TO-UNDERSTAND NAMES). MIGRATE ANY PRE-EXISTING CUSTOM CONFIGURATION TO THE #NEW# VARIABLE NAMES."
echo " "
echo "v4.08.4 AND HIGHER SWITCHED FROM 'FREE MEMORY' SYSTEM STATS, OVER TO 'MEMORY USED' SYSTEM STATS"
echo "(WHICH #NOW# CORRECTLY DOES NOT INCLUDE MEMORY BUFFERS / CACHE). SYSTEM STATS CHARTS AFTER UPGRADE"
echo "TO v4.09.0 OR HIGHER WILL STILL RETAIN OLD SYSTEM STATS DATA FOR MEMORY USAGE, BUT WILL BE LABELED"
echo "DIFFERENTLY ('USED MEMORY' INSTEAD OF 'FREE MEMORY'). JUST IGNORE THE OLDER MEMORY DATA, OR RESET"
echo "YOUR SYSTEM STATS CHARTS BY DELETING: /cache/charts/system/"
echo " "
echo "v4.06.0 AND HIGHER HAS MAJOR DIRECTORY STRUCTURE CHANGES. FOR CLEAN UPGRADES, THIS AUTO-INSTALL SCRIPT"
echo "WILL DELETE #EVERY PREVIOUSLY-USED SUB-DIRECTORY NAME# EXCEPT FOR THE 'CACHE' DIRECTORY BEFOREHAND."
echo " "
echo "v4.04.3 and higher of this app RESETS PRICE ALERT VOLUME STORED TO USE PAIRING VOLUME INSTEAD"
echo "OF ASSET VOLUME, SO !FIRST! PRICE ALERTS AFTER UPGRADING WILL HAVE INCORRECT VOLUME CHANGE"
echo "PERCENTAGES (BUT WILL BE ACCURATE AFTERWARDS)."
echo " "
echo "v4.03.0 and higher of this app REQUIRES DELETING YOUR PREVIOUS CHART DATA FROM ANY EARLIER VERSIONS."
echo "MAJOR IMPROVEMENTS TO THE USER EXPERIENCE HAVE BEEN MADE, WHICH ARE !NOT! BACKWARDS-COMPATIBLE."
echo " "
fi
  				

echo "IMPORTANT SECURITY NOTES:"
echo "YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA),"
echo "#WHEN YOU FIRST RUN THIS APP AFTER INSTALLATION#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#,"
echo "ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#."
echo " "

  				
echo "Select 1 or 2 to choose whether to continue with installation, or quit."
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


echo " "

echo "Making sure your system is updated before installation..."

echo " "
			
/usr/bin/sudo /usr/bin/apt-get update

#DO NOT RUN dist-upgrade, bad things can happen, lol
/usr/bin/sudo /usr/bin/apt-get upgrade -y

echo " "
				
echo "System update completed."
				
/bin/sleep 3
				
echo " "


######################################


echo "Select 1, 2, or 3 to choose whether to auto-install / remove the PHP web server, or skip."
echo " "

OPTIONS="install_webserver remove_webserver skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_webserver" ]; then
         
         echo " "
			
			echo "Proceeding with PHP web server installation..."
			
			echo " "
			
			/usr/bin/apt-get install apache2 php php-mbstring php-curl php-gd php-zip libapache2-mod-php openssl ssl-cert avahi-daemon -y
			
			/bin/sleep 3
			
			echo " "
			
			mv -v $DOC_ROOT/index.html $DOC_ROOT/index.php


			######################################
			

			# SSL / Rewrite setup
			
			echo " "
			
			# Regenerate new self-signed SSL cert keys with ssl-cert (for secure HTTPS web pages)
			/usr/sbin/make-ssl-cert generate-default-snakeoil --force-overwrite

			echo "New SSL certificate keys self-generated..."
			echo " "

			# Enable SSL (for secure HTTPS web pages)
			/usr/sbin/a2enmod ssl
			/usr/sbin/a2ensite default-ssl

			/bin/sleep 1
			
			echo " "

			# Enable mod-rewrite, for upcoming REST API features
			/usr/sbin/a2enmod rewrite	

			/bin/sleep 1
			
			echo " "
				
				if [ -f /etc/init.d/apache2 ]; then
				echo "Mod-rewrite and SSL (for secure HTTPS web pages) have been enabled,"
				echo "restarting the Apache web server..."
				/etc/init.d/apache2 restart
				echo " "
				else
				echo "Mod-rewrite and SSL (for secure HTTPS web pages) have been enabled."
				echo "You must restart the Apache web server for this to take affect."
				echo " "
				fi


			######################################
			
       
         # Enable HTTP (port 80) htaccess
         #/usr/sbin/a2ensite 000-default
          
         HTTP_CONFIG="/etc/apache2/sites-available/000-default.conf"
            
            if [ ! -f $HTTP_CONFIG ]; then
            
            echo "$HTTP_CONFIG could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTP (port 80):"
            echo " "
            
            read HTTP_CONFIG
                    
                if [ ! -f $HTTP_CONFIG ] || [ -z "$HTTP_CONFIG" ]; then
                echo "No HTTP config file detected, skipping Apache htaccess setup for port 80..."
                SKIP_HTTP_HTACCESS=1
                else
                echo "Using Apache HTTP config file:"
                echo "$HTTP_CONFIG"
                CHECK_HTTP=$(<$HTTP_CONFIG)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTP=$(<$HTTP_CONFIG)
            
            fi
            
            
            
            if [ "$SKIP_HTTP_HTACCESS" != "1" ] && [[ $CHECK_HTTP != *"cryptocoin_htaccess_80"* ]]; then
            
            echo " "
            
            echo "Enabling htaccess for HTTP (port 80)..."
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
            \cp $HTTP_CONFIG $HTTP_CONFIG.BACKUP.$DATE
            
            
            # Create the new HTTP config
            NEW_HTTP_CONFIG=$(echo -e "$HTACCESS_HTTP" | /bin/sed '/:80>/r /dev/stdin' $HTTP_CONFIG)
            
            
            # Install the new HTTP config
            echo -e "$NEW_HTTP_CONFIG" > $HTTP_CONFIG

				/bin/sleep 1
                            
                            
                # Restart Apache
                if [ -f /etc/init.d/apache2 ]; then
                echo "Htaccess has been enabled for HTTP (port 80),"
                echo "restarting the Apache web server..."
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "Htaccess has been enabled for HTTP (port 80)."
                echo "You must restart the Apache web server for this to take affect."
                echo " "
                fi
            
            
            else
            
            echo " "
            echo "Htaccess was already enabled for HTTP (port 80)."
            echo " "
            
            fi
            

			/bin/sleep 2
            
         ######################################
                        
                                                
         # Enable HTTPS (port 443) htaccess
         #/usr/sbin/a2ensite default-ssl
         
         
         HTTPS_CONFIG="/etc/apache2/sites-available/default-ssl.conf"
            
            if [ ! -f $HTTPS_CONFIG ]; then
            
            echo "$HTTPS_CONFIG could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTPS (port 443):"
            echo " "
            
            read HTTPS_CONFIG
                    
                if [ ! -f $HTTPS_CONFIG ] || [ -z "$HTTPS_CONFIG" ]; then
                echo "No HTTPS config file detected, skipping Apache htaccess setup for port 443..."
                SKIP_HTTPS_HTACCESS=1
                else
                echo "Using Apache HTTPS config file:"
                echo "$HTTPS_CONFIG"
                CHECK_HTTPS=$(<$HTTPS_CONFIG)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTPS=$(<$HTTPS_CONFIG)
            
            fi
            
            
            
            if [ "$SKIP_HTTPS_HTACCESS" != "1" ] && [[ $CHECK_HTTPS != *"cryptocoin_htaccess_443"* ]]; then
            
            echo " "
            echo "Enabling htaccess for HTTPS (port 443)..."
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
            \cp $HTTPS_CONFIG $HTTPS_CONFIG.BACKUP.$DATE
            
            
            # Create the new HTTPS config
            NEW_HTTPS_CONFIG=$(echo -e "$HTACCESS_HTTPS" | /bin/sed '/:443>/r /dev/stdin' $HTTPS_CONFIG)
            
            
            # Install the new HTTPS config
            echo -e "$NEW_HTTPS_CONFIG" > $HTTPS_CONFIG

				/bin/sleep 1
                            
                            
                # Restart Apache
                if [ -f /etc/init.d/apache2 ]; then
                echo "Htaccess has been enabled for HTTPS (port 443),"
                echo "restarting the Apache web server..."
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "Htaccess has been enabled for HTTPS (port 443)."
                echo "You must restart the Apache web server for this to take affect."
                echo " "
                fi
            
            
            else
            
            echo " "
            echo "Htaccess was already enabled for HTTPS (port 443)."
            echo " "
            
            fi


			/bin/sleep 2

			######################################
			
			
			echo " "
			echo "PHP web server installation is complete."
         echo " "
			
			
         ######################################
            
            
         # Give the new HTTP server system user a chance to exist for a few seconds, before trying to determine the name / group automatically
         echo "Attempting to auto-detect the web server's user group, please wait..."
         echo " "
         
         /bin/sleep 3
           
         #WWW_GROUP=$(/bin/ps -ef | /bin/egrep '(httpd|httpd2|apache|apache2)' | /bin/grep -v `whoami` | /bin/grep -v root | /usr/bin/head -n1 | /usr/bin/awk '{print $1}')
         WWW_GROUP=$(/bin/ps axo user,group,comm | /bin/egrep '(httpd|httpd2|apache|apache2)' | /bin/grep -v ^root | /usr/bin/cut -d\  -f 2 | /usr/bin/uniq)
            
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
        
        
        	/usr/sbin/usermod -a -G $CUSTOM_GROUP $SYS_USER
        
        	echo " "
        	echo "Web server editing access for user name '$SYS_USER', in web server user group '$CUSTOM_GROUP', is completed."

			/bin/sleep 1
        
        	/usr/sbin/usermod -a -G $SYS_USER $CUSTOM_GROUP
        	
        	echo " "
        	echo "Web server editing access for web server user name '$CUSTOM_GROUP', in user group '$SYS_USER', is completed."

			/bin/sleep 1
			
        	/bin/chmod 775 $DOC_ROOT
			
        	echo " "
        	echo "Root web directory group permissions setup (chmod 775, owner/group set to username '$SYS_USER') is completed."

			/bin/sleep 1
        
        	BASE_HTDOC="$(dirname $DOC_ROOT)"
        
        	RECURSIVE_CHOWN="-R ${SYS_USER}:$SYS_USER ${BASE_HTDOC}/*"
        
        	#$RECURSIVE_CHOWN must be in double quotes to escape the asterisk at the end
        	/bin/chown $RECURSIVE_CHOWN

			/bin/sleep 3
        
			echo " "
			echo "PHP web server configuration is complete."
        
        	######################################
         
         
        break
       elif [ "$opt" = "remove_webserver" ]; then
       
        echo " "
        echo "Removing PHP web server..."
        echo " "
        
        # Skip removing openssl / ssl-cert, in case they were already on the system
        /usr/bin/apt-get remove apache2 php php-mbstring php-curl php-gd php-zip libapache2-mod-php -y
        
		  /bin/sleep 3
			
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


echo "Do you want this script to automatically download the latest version of"
echo "DFD Cryptocoin Values from Github.com, and install / configure it?"
echo " "

echo "Select 1, 2, or 3 to choose whether to auto-install / remove DFD Cryptocoin Values, or skip."
echo "(!WARNING!: REMOVING DFD Cryptocoin Values WILL DELETE *EVERYTHING* IN $DOC_ROOT !!)"
echo " "

OPTIONS="install_coin_app remove_coin_app skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_coin_app" ]; then
        
        		if [ ! -d "$DOC_ROOT" ]; then
        		
        		echo " "
				
				echo "Directory $DOC_ROOT DOES NOT exist, cannot install DFD Cryptocoin Values."
				echo "Skipping auto-install of DFD Cryptocoin Values."
				else
				
				echo " "
				echo "Proceeding with required component installation..."
				
				echo " "
				
				/usr/bin/apt-get install curl jq bsdtar pwgen openssl -y

				/bin/sleep 3
				
				echo " "
				echo "Required component installation completed."
				
				echo " "
				echo "Downloading and installing the latest version of DFD Cryptocoin Values, from Github.com..."
            echo " "
				
				mkdir DFD-Cryptocoin-Values
				
				cd DFD-Cryptocoin-Values
				
				# Set curl user agent, as the github API REQUIRES ONE
				/usr/bin/curl -H "$CUSTOM_CURL_USER_AGENT_HEADER"
				
				ZIP_DL=$(/usr/bin/curl -s 'https://api.github.com/repos/taoteh1221/DFD_Cryptocoin_Values/releases/latest' | /usr/bin/jq -r '.zipball_url')
				
				/usr/bin/wget -O DFD-Cryptocoin-Values.zip $ZIP_DL
				
				/bin/sleep 2
				
				echo " "
				echo "Extracting download archive..."
				echo " "
				
				/usr/bin/bsdtar --strip-components=1 -xvf DFD-Cryptocoin-Values.zip

				/bin/sleep 3
				
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
						
						/bin/chown $SYS_USER:$SYS_USER $DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING
						
						CONFIG_BACKUP=1
						
  						else
  						echo "No backup of the previous install's $DOC_ROOT/config.php file was created (for security reasons)."
  						echo "The new install WILL NOW OVERWRITE ALL PREVIOUSLY-CONFIGURED SETTINGS in $DOC_ROOT/config.php..."
  						echo " "
						fi
						
					
  					fi
  				
  				
				echo " "
				echo "Cleaning any previous install..."
				echo " "
				
  				# Delete old directory / file structures (overhauled in v4.06.0 higher), for a clean upgrade
  				# Directories
  				rm -rf $DOC_ROOT/app-lib
  				rm -rf $DOC_ROOT/backups
  				rm -rf $DOC_ROOT/cache/apis
  				rm -rf $DOC_ROOT/cache/queue
  				rm -rf $DOC_ROOT/misc-docs-etc
  				rm -rf $DOC_ROOT/ui-templates
  				rm -rf $DOC_ROOT/templates/interface/php
  				# Files
				rm $DOC_ROOT/DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt # (Renamed /DOCUMENTATION-ETC/CONFIG-EXAMPLE.txt)
				rm $DOC_ROOT/DOCUMENTATION-ETC/CRON_PLUGINS_README.txt # (Renamed /DOCUMENTATION-ETC/CRON-PLUGINS-README.txt)
				rm $DOC_ROOT/CONFIG.EXAMPLE.txt
				rm $DOC_ROOT/HELP-FAQ.txt
				rm $DOC_ROOT/PORTFOLIO-IMPORT-EXAMPLE-SPREADSHEET.csv
				rm $DOC_ROOT/oauth.php
				rm $DOC_ROOT/webhook.php

				/bin/sleep 3
				
				echo " "
				echo "Installing DFD Cryptocoin Values..."
  				
  				# Copy over the upgrade install files to the install directory
				# No trailing forward slash here
				\cp -r ./ $DOC_ROOT

				/bin/sleep 3
				
				cd ../
				
				rm -rf DFD-Cryptocoin-Values
				
				rm -rf $DOC_ROOT/.github

				/bin/sleep 3
				
				rm $DOC_ROOT/.gitattributes
				
				rm $DOC_ROOT/.gitignore
				
				rm $DOC_ROOT/CODEOWNERS
				
				/bin/chmod 777 $DOC_ROOT/cache
				
				/bin/chmod 664 $DOC_ROOT/.htaccess
				
				/bin/chmod 755 $DOC_ROOT/cron.php

				/bin/sleep 1
				
				# No trailing forward slash here
				/bin/chown -R $SYS_USER:$SYS_USER $DOC_ROOT

				/bin/sleep 3
				
				echo " "
				echo "DFD Cryptocoin Values has been installed."
				
				
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
                    
                            
                      # Setup cron (to check logs after install: tail -f /var/log/syslog | grep cron -i)
                            
                      /usr/bin/touch /etc/cron.d/cryptocoin
                            
                    CRONJOB="*/$INTERVAL * * * * $SYS_USER /usr/bin/php -q $PATH > /dev/null 2>&1"
            
                      # Play it safe and be sure their is a newline after this job entry
                      echo -e "$CRONJOB\n" > /etc/cron.d/cryptocoin

							 /bin/sleep 1
                      
                      # cron.d entries must be a permission of 644
                      /bin/chmod 644 /etc/cron.d/cryptocoin

							 /bin/sleep 1
                      
                      # cron.d entries MUST BE OWNED BY ROOT
                      /bin/chown root:root /etc/cron.d/cryptocoin
                      
                    
                    echo " "
                    echo "A cron job has been setup for user '$SYS_USER',"
                    echo "as a command in /etc/cron.d/cryptocoin:"
                    echo "$CRONJOB"
                    echo " "
                    
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

				
				echo " "
				echo "DFD Cryptocoin Values has been configured."
				
	        	APP_SETUP=1
   	     	
  				fi

        break
       elif [ "$opt" = "remove_coin_app" ]; then
       
        echo " "
        echo "Removing DFD Cryptocoin Values..."
        
        rm /etc/cron.d/cryptocoin
		  
        rm $DOC_ROOT/.htaccess
        
        rm -rf $DOC_ROOT/*

		  /bin/sleep 3
        
		  echo " "
		  echo "DFD Cryptocoin Values has been removed from the system."
        
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "Skipping auto-install of DFD Cryptocoin Values."
        
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
				
				echo "Proceeding with openssh-server installation..."
				
				echo " "
				
				/usr/bin/apt-get install openssh-server -y
				
				/bin/sleep 3
				
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

echo "DFD Cryptocoin Values is located at (and can be edited) inside this folder:"
echo "$DOC_ROOT"
echo " "

echo "You may now optionally edit the DFD Cryptocoin Values configuration file"
echo "(config.php) remotely via SFTP, or by editing app files locally."
echo " "


    if [ "$CONFIG_BACKUP" = "1" ]; then
    
    echo "The previously-installed DFD Cryptocoin Values configuration"
    echo "file $DOC_ROOT/config.php has been backed up to:"
    echo "$DOC_ROOT/config.php.BACKUP.$DATE.$RAND_STRING"
    echo "You will need to manually move any custom settings in this backup file to the new config.php file with a text editor."
    echo " "
    
    fi
    
    
    if [ "$CRON_SETUP" = "1" ]; then
    
    echo "A cron job has been setup for user '$SYS_USER',"
    echo "as a command in /etc/cron.d/cryptocoin:"
    echo "$CRONJOB"
    echo " "
    
    fi


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



echo "#INTERNAL# NETWORK SSL / HTTPS (secure / private SSL connection) web addresses are..."
echo "IP ADDRESS (may change, unless set as static for this device within the router):"
echo "https://$IP"
echo " "
echo "HOST ADDRESS (ONLY works on linux / mac / windows, NOT android):"
echo "https://${HOSTNAME}.local"
echo " "

echo "IMPORTANT NOTES:"
echo "YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA),"
echo "#WHEN YOU FIRST RUN THIS APP#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#,"
echo "ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#."
echo " "
echo "The SSL certificate created on this web server is SELF-SIGNED (not issued by a CA),"
echo "so your browser ---will give you a warning message--- when you visit the above HTTPS address."
echo "This is --normal behavior for self-signed certificates--. Google search for"
echo "'self-signed ssl certificate' for more information on the topic."
echo "THAT SAID, ONLY TRUST SELF-SIGNED CERTIFICATES #IF YOUR COMPUTER CREATED THE CERTIFICATE#."
echo "!NEVER! TRUST SELF-SIGNED CERTIFICATES SIGNED BY THIRD PARTIES!"
echo " "

echo "If you wish to allow internet access (when not on your home / internal network),"
echo "port forwarding on your router needs to be setup (preferably with strict firewall"
echo "rules, routing through a dedicated minipc running pfsense / ipfire / etc, to"
echo "disallow this device requesting access to other machines on your home / internal"
echo "network, and only allow it a forwarding route through the internet gateway)."
echo " "
echo "A #VERY HIGH# port number is recommended (greater than 100,000), to help avoid"
echo "port scanning bots detecting it (and then making hack attempts on it)."
echo " "

echo "SEE /DOCUMENTATION-ETC/RASPBERRY-PI-SECURITY.txt for additional setup related to"
echo "securing your Raspberry Pi (disabling bluetooth, etc)."
echo " "

echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"


######################################


echo " "
echo "BE SURE TO SAVE ALL THE ACCESS DETAILS PRINTED OUT ABOVE, BEFORE YOU SIGN OFF FROM THIS TERMINAL SESSION."
echo " "
echo "See my other cryptocurrency-related free / private / open source software at:"
echo "https://sourceforge.net/u/taoteh1221/profile"
echo "https://github.com/taoteh1221"
echo " "


#PHP fastCGI and suexec...not yet a good automated script, so disabled for now

#echo "https://cwiki.apache.org/confluence/display/httpd/PHP-FPM"
#echo "https://geekanddummy.com/how-to-raspberry-pi-tutorial-part-3-web-file-hosting-with-webmin-virtualmin"

#echo "Making sure your system is updated before installing required components..."
				
#/usr/bin/apt-get update
				
#/usr/bin/apt-get upgrade -y
				
#echo "Proceeding with required component installation..."

#/usr/bin/apt-get install php-fpm apache2-suexec-custom -y

#/bin/sleep 3
				
#echo "Required component installation completed."

# Activates in Apache2 with following commands
#/usr/sbin/a2enmod proxy_fcgi setenvif
#/usr/sbin/a2enconf php7.3-fpm
#/usr/sbin/a2enmod suexec
#/usr/sbin/a2enmod actions


######################################


