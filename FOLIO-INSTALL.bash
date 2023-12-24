#!/bin/bash

# Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


echo " "
echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "
echo "Initializing, please wait..."
echo " "


# EXPLICITLY set any dietpi paths 
# Export too, in case we are calling another bash instance in this script
if [ -f /boot/dietpi/.version ]; then
PATH=/boot/dietpi:$PATH
export PATH=$PATH
fi
				

# EXPLICITLY set any ~/.local/bin paths
# Export too, in case we are calling another bash instance in this script
if [ -d ~/.local/bin ]; then
PATH=~/.local/bin:$PATH
export PATH=$PATH
fi
				

# EXPLICITLY set any /usr/sbin path
# Export too, in case we are calling another bash instance in this script
if [ -d /usr/sbin ]; then
PATH=/usr/sbin:$PATH
export PATH=$PATH
fi


######################################


# Path to app (CROSS-DISTRO-COMPATIBLE)
get_app_path() {
app_path_result=$(whereis -b $1)
app_path_result="${app_path_result#*$1: }"
app_path_result=${app_path_result%%[[:space:]]*}
app_path_result="${app_path_result#*$1:}"
echo "$app_path_result"
}


######################################

# https://stackoverflow.com/questions/5947742/how-to-change-the-output-color-of-echo-in-linux

if hash tput > /dev/null 2>&1; then

red=`tput setaf 1`
green=`tput setaf 2`
yellow=`tput setaf 3`
blue=`tput setaf 4`
magenta=`tput setaf 5`
cyan=`tput setaf 6`

reset=`tput sgr0`

else

red=``
green=``
yellow=``
blue=``
magenta=``
cyan=``

reset=``

fi


######################################


# Get logged-in username (if sudo, this works best with logname)
TERMINAL_USERNAME=$(logname)


# If logname doesn't work, use the $SUDO_USER or $USER global var
if [ -z "$TERMINAL_USERNAME" ]; then

    if [ -z "$SUDO_USER" ]; then
    TERMINAL_USERNAME=$USER
    else
    TERMINAL_USERNAME=$SUDO_USER
    fi

fi


# Get date / time
DATE=$(date '+%Y-%m-%d')
TIME=$(date '+%H:%M:%S')

# Current timestamp
CURRENT_TIMESTAMP=$(date +%s)


# If a symlink, get link target for script location
 # WE ALWAYS WANT THE FULL PATH!
if [[ -L "$0" ]]; then
SCRIPT_LOCATION=$(readlink "$0")
else
SCRIPT_LOCATION="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )/"$(basename "$0")""
fi

# Now set path / file vars, after setting SCRIPT_LOCATION
SCRIPT_PATH="$( cd -- "$(dirname "$SCRIPT_LOCATION")" >/dev/null 2>&1 ; pwd -P )"
SCRIPT_NAME=$(basename "$SCRIPT_LOCATION")


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


echo " "

if [ "$EUID" -ne 0 ] || [ "$TERMINAL_USERNAME" == "root" ]; then 
 echo "${red}Please run as a NORMAL USER WITH 'sudo' PERMISSIONS (NOT LOGGED IN AS 'root').${reset}"
 echo " "
 echo "${cyan}Exiting...${reset}"
 echo " "
 exit
fi


if [ -f "/etc/debian_version" ]; then
echo "${cyan}Your system has been detected as Debian-based, which is compatible with this automated installation script."
PACKAGE_INSTALL="sudo apt install"
PACKAGE_REMOVE="sudo apt --purge remove"
echo " "
echo "Continuing...${reset}"
echo " "
elif [ -f "/etc/arch-release" ]; then
echo "${cyan}Your system has been detected as Arch-based, which is compatible with this automated installation script."
PACKAGE_INSTALL="sudo pacman -S"
PACKAGE_REMOVE="sudo pacman -R"
echo " "
echo "Continuing...${reset}"
echo " "
else
echo "${red}Your system has been detected as NOT BEING Debian-based. Your system is NOT compatible with this automated installation script."
echo " "
echo "Exiting...${reset}"
exit
fi


######################################


# clean_system_update function START
clean_system_update () {


     if [ -z "$ALLOW_FULL_UPGRADE" ]; then
     
     echo " "
     echo "${yellow}Does the Operating System on this device update using the \"Rolling Release\" model (Kali, Manjaro, Ubuntu Rolling Rhino, Debian Unstable, etc), or the \"Long-Term Release\" model (Ubuntu, Raspberry Pi OS, Armbian Stable, Diet Pi, etc)?"
     echo " "
     echo "${red}(You can SEVERLY MESS UP a \"Rolling Release\" Operating System IF YOU DO NOT CHOOSE CORRECTLY HERE! In that case, you can SAFELY choose \"I don't know\".)${reset}"
     echo " "
     
     echo "Enter the NUMBER next to your chosen option.${reset}"
     
     echo " "
     
          OPTIONS="rolling long_term i_dont_know"
          
          select opt in $OPTIONS; do
                  if [ "$opt" = "long_term" ]; then
                  ALLOW_FULL_UPGRADE="yes"
                  echo " "
                  echo "${green}Allowing system-wide updates before installs.${reset}"
                  break
                 else
                  ALLOW_FULL_UPGRADE="no"
                  echo " "
                  echo "${green}Disabling system-wide updates before installs.${reset}"
                  break
                 fi
          done
            
     echo " "
     
     fi


     if [ "$PACKAGE_CACHE_REFRESHED" != "1" ]; then
     
     PACKAGE_CACHE_REFRESHED=1


          if [ -f "/etc/debian_version" ]; then

          echo "${cyan}Making sure your APT sources list is updated before installations, please wait...${reset}"
          
          echo " "
          
          # In case package list was ever corrupted (since we are about to rebuild it anyway...avoids possible errors)
          sudo rm -rf /var/lib/apt/lists/* -vf > /dev/null 2>&1
          
          sleep 2
          
          sudo apt update
          
          sleep 2
          
          echo " "
     
          echo "${cyan}APT sources list update complete.${reset}"
          
          echo " "
     
          fi
          
     
          if [ "$ALLOW_FULL_UPGRADE" == "yes" ]; then

          echo "${cyan}Making sure your system is updated before installations, please wait...${reset}"
          
          echo " "
          
          
               if [ -f "/etc/debian_version" ]; then
               #DO NOT RUN dist-upgrade, bad things can happen, lol
               sudo apt upgrade -y
               elif [ -f "/etc/arch-release" ]; then
               sudo pacman -Syu
               fi
          
          
          sleep 2
          
          echo " "
          				
          echo "${cyan}System updated.${reset}"
          				
          echo " "
          
          fi
     
     fi

}
# clean_system_update function END


######################################


# Get SIMILAR (CROSS-DISTRO) primary dependency apps, if we haven't yet
    
# Install git if needed
GIT_PATH=$(get_app_path "git")

if [ -z "$GIT_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component git, please wait...${reset}"
echo " "

$PACKAGE_INSTALL git -y

fi


# Install curl if needed
CURL_PATH=$(get_app_path "curl")

if [ -z "$CURL_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component curl, please wait...${reset}"
echo " "

$PACKAGE_INSTALL curl -y

fi


# Install jq if needed
JQ_PATH=$(get_app_path "jq")

if [ -z "$JQ_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component jq, please wait...${reset}"
echo " "

$PACKAGE_INSTALL jq -y

fi


# Install wget if needed
WGET_PATH=$(get_app_path "wget")

if [ -z "$WGET_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component wget, please wait...${reset}"
echo " "

$PACKAGE_INSTALL wget -y

fi


# Install sed if needed
SED_PATH=$(get_app_path "sed")

if [ -z "$SED_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component sed, please wait...${reset}"
echo " "

$PACKAGE_INSTALL sed -y

fi


# Install less if needed
LESS_PATH=$(get_app_path "less")
				
if [ -z "$LESS_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component less, please wait...${reset}"
echo " "

$PACKAGE_INSTALL less -y

fi


# Install expect if needed
EXPECT_PATH=$(get_app_path "expect")
				
if [ -z "$EXPECT_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component expect, please wait...${reset}"
echo " "

$PACKAGE_INSTALL expect -y

fi


# Install avahi-daemon if needed (for .local names on internal / home network)
AVAHID_PATH=$(get_app_path "avahi-daemon")

if [ -z "$AVAHID_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component avahi-daemon, please wait...${reset}"
echo " "

$PACKAGE_INSTALL avahi-daemon -y

fi


# Install bc if needed (for decimal math in bash)
BC_PATH=$(get_app_path "bc")

if [ -z "$BC_PATH" ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

echo " "
echo "${cyan}Installing required component bc, please wait...${reset}"
echo " "

$PACKAGE_INSTALL bc -y

fi

# SIMILAR (CROSS-DISTRO) dependency check END


######################################


# Install bsdtar if needed (for opening archives)
BSDTAR_PATH=$(get_app_path "bsdtar")


# Distro-specific logic, to set variables, get dependencies, etc
if [ -f "/etc/debian_version" ]; then

# Get the host ip address
IP=`hostname -I` 

# Get a list of PHP-FPM packages THAT ARE AVAILABLE TO INSTALL
PHP_FPM_LIST=$(apt-cache search php-fpm)

FPM_PACKAGE=`expr match "$PHP_FPM_LIST" '.*\(php[0-9][.][0-9]-fpm\)'`

FPM_PACKAGE_VER=`expr match "$FPM_PACKAGE" '.*\([0-9][.][0-9]\)'`
				

     if [ -z "$BSDTAR_PATH" ]; then
     
     # Clears / updates cache, then upgrades (if NOT a rolling release)
     clean_system_update
     
     echo " "
     echo "${cyan}Installing required component libarchive-tools, please wait...${reset}"
     echo " "
     
     # Ubuntu 18.x and higher
     $PACKAGE_INSTALL libarchive-tools -y
     
     fi
     

elif [ -f "/etc/arch-release" ]; then

# Get the host ip address
IP=$(ip -json route get 8.8.8.8 | jq -r '.[].prefsrc') 

# Get a list of PHP-FPM packages THAT ARE AVAILABLE TO INSTALL
PHP_FPM_LIST=$(pacman --sync --search php-fpm)

FPM_PACKAGE=`expr match "$PHP_FPM_LIST" '.*\(php-fpm [0-9][.][0-9]\)'`

FPM_PACKAGE_VER=`expr match "$FPM_PACKAGE" '.*\([0-9][.][0-9]\)'`
				

     if [ -z "$BSDTAR_PATH" ]; then
     
     # Clears / updates cache, then upgrades (if NOT a rolling release)
     clean_system_update
     
     echo " "
     echo "${cyan}Installing required component libarchive, please wait...${reset}"
     echo " "
     
     # Ubuntu 18.x and higher
     $PACKAGE_INSTALL libarchive -y
     
     fi


# Install cronie if needed (for a crond impementation)
CRONIE_PATH=$(get_app_path "crond")	


     if [ -z "$CRONIE_PATH" ]; then
     
     # Clears / updates cache, then upgrades (if NOT a rolling release)
     clean_system_update
     
     echo " "
     echo "${cyan}Installing required component cronie, please wait...${reset}"
     echo " "
     
     $PACKAGE_INSTALL cronie -y
     
     sleep 3
     
     systemctl enable --now cronie.service
     
     fi
     

fi


######################################


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
echo " "
echo "${yellow}We need to know the SYSTEM username you'll be logging in as on this machine to edit web files..."
echo " "
        
echo "Enter the SYSTEM username to allow web server editing access for:"
echo "(leave blank / hit enter for default username '${TERMINAL_USERNAME}')${reset}"
echo " "
        
read APP_USER
echo " "

 if [ -z "$APP_USER" ]; then
 APP_USER=${1:-$TERMINAL_USERNAME}
 echo "${green}Using default username: $APP_USER${reset}"
 else
 echo "${green}Using username: $APP_USER${reset}"
 fi

echo " "


######################################

			
echo "${yellow}Enter the FULL SYSTEM PATH to the document root of the web server:"
echo "(this does NOT automate setting apache's document root, you would need to do that manually)"
echo "(DO !NOT! INCLUDE A #TRAILING# FORWARD SLASH)"
echo " "
echo "(leave blank / hit enter to use the default value: /var/www/html)${reset}"
echo " "

read DOC_ROOT
echo " "
        
if [ -z "$DOC_ROOT" ]; then
DOC_ROOT=${1:-/var/www/html}
echo "${green}Using default website document root:"
echo "$DOC_ROOT${reset}"
else
echo "${green}Using custom website document root:"
echo "$DOC_ROOT${reset}"
fi

echo " "

if [ ! -d "$DOC_ROOT" ] && [ "$DOC_ROOT" != "/var/www/html" ]; then
echo "The defined document root directory '$DOC_ROOT' does not exist yet."
echo "Please create this directory structure before running this script."
echo "Exiting..."
exit
fi


######################################


echo " "
echo "${yellow}TECHNICAL NOTE:"
echo " "
echo "This script was designed to install on popular Debian-based (MATURE / STABLE) / Arch-based (STILL UNSTABLE!!) operating systems (Ubuntu, Raspberry Pi OS [Raspbian], Armbian, DietPi, Arch, Manjaro, etc),"
echo "for running as an app server WHICH IS LEFT TURNED ON 24/7 (ALL THE TIME).${reset}"
echo " "

echo "${yellow}This script MAY NOT work on ALL Debian-based / Arch-based system setups.${reset}"
echo " "

echo "${cyan}Your operating system has been detected as:"
echo " "
echo "$OS v$VER${reset}"
echo " "

echo "${red}Recommended MINIMUM system specs:${reset}"
echo " "
echo "${yellow}1 Gigahertz CPU / 512 Megabytes RAM / HIGH QUALITY 16 Gigabyte MicroSD card (running Nginx or Apache headless with PHP v7.2+)${reset}"
echo " "

echo "${red}If you already have unrelated web site files located at $DOC_ROOT on your system, they may be affected."
echo "Please back up any important pre-existing files in that directory before proceeding.${reset}"
echo " "
				
				
if [ -f $DOC_ROOT/config.php ]; then
echo "${yellow}Configuration files from a previous install of Open Crypto Tracker (Server Edition) have been detected on your system."
echo " "
echo "${green}During this upgrade / re-install, they will be backed up to:"
echo " "
echo "$DOC_ROOT/[filename].php.BACKUP.$DATE.[random string]${reset}"
echo " "
echo "This will save any custom settings within them, so you can migrate settings to the new config files MANUALLY."
echo " "
echo "(ALL plugin configuration files will be backed up in the same manner)"
echo " "
echo "You will need to manually move any CUSTOMIZED DEFAULT settings from backup files to the NEW configuration files with a text editor,"
echo "otherwise you can just ignore or delete the backup files."
echo " "

echo "${red}IF ANYTHING STOPS WORKING AFTER UPGRADING, CLEAR YOUR BROWSER CACHE (temporary files), AND RELOAD OR RESTART THE APP. This will load the latest Javascript / Style Sheet upgrades properly.${reset}"
echo " "

echo "${red}IMPORTANT *UPGRADE* NOTICES:"
echo " "
echo " "

echo "v6.00.33 and higher changed a few duplicate text gateway keys, so if you use"
echo "email-to-text gateways in this app, MAKE SURE YOURS STILL HAS THE SAME NAME."
echo " "
echo " "

echo "v6.00.32 and higher COMPLETELY RESTRUCTURES THE PLUGIN CONFIGURATION FORMAT. USE THE LATEST/UPGRADED PLUG_CONF.PHP,"
echo "FOR *ALL* BUNDLED PLUGINS, AND MIGRATE ANY CUSTOM PLUGIN CONFIGS TO THE NEW FORMAT (see revised plugin developer documentation)."
echo " "
echo " "

echo "v6.00.32 and higher renames / restructures MANY settings in the config. USE THE LATEST/UPGRADED CONFIG.PHP,"
echo "AND MIGRATE YOUR EXISTING CUSTOM SETTINGS TO THE NEW FORMAT."
echo " "
echo " "

echo "v6.00.32 AND HIGHER RESETS LIGHT (TIME PERIOD) CHARTS FROM ARCHIVAL DATA, ONE-TIME DURING"
echo "UPGRADES FROM V6.00.31 OR LOWER."
echo " "
echo " "

echo "v6.00.31 and higher restructures the mobile text gateways config formatting. USE THE LATEST/UPGRADED CONFIG.PHP,"
echo "AND MIGRATE YOUR EXISTING CUSTOM GATEWAYS TO THE NEW FORMAT."
echo " "
echo " "

echo "v6.00.31 and higher restructures the price alerts / price charts config formatting. USE THE LATEST/UPGRADED CONFIG.PHP,"
echo "AND MIGRATE YOUR EXISTING CUSTOM PRICE ALERTS / PRICE CHARTS TO THE NEW FORMAT."
echo " "
echo " "

echo "v6.00.29 and higher restructures the 'price-target-alert' plugin. USE THE LATEST/UPGRADED PLUG_CONF.PHP,"
echo "AND MIGRATE YOUR EXISTING CUSTOM PRICE TARGET ALERTS TO THE NEW FORMAT."
echo " "
echo " "

echo "${reset} "


fi
  				

echo "${red}VERY IMPORTANT *SECURITY* NOTES:"
echo " "
echo "YOU WILL BE PROMPTED TO CREATE AN ADMIN LOGIN (FOR SECURITY OF THE ADMIN AREA),"
echo "#WHEN YOU FIRST RUN THIS APP AFTER INSTALLATION#. IT'S #HIGHLY RECOMMENDED TO DO THIS IMMEDIATELY#,"
echo "ESPECIALLY ON PUBLIC FACING / KNOWN SERVERS, #OR SOMEBODY ELSE MAY BEAT YOU TO IT#."
echo " "

echo "!!VERY IMPORTANT *HOSTING* NOTICE!!:"
echo " "
echo "This auto-install script is ONLY FOR SELF-HOSTED ENVIRONMENTS, THAT #DO NOT# ALREADY"
echo "HAVE A WEB SERVER OR CONTROL PANEL INSTALLED ON THE SYSTEM. If this is a managed hosting"
echo "environment that a service provider has already provisioned, please quit this auto-install"
echo "session, and refer to the \"Manual Install\" section of the README.txt file documentation.${reset}"
echo " "

echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "

echo "${yellow} "
read -n1 -s -r -p $"Press y to continue (or press n to exit)..." key
echo "${reset} "

    if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
    
    echo " "
    echo "${green}Continuing...${reset}"
         
    # If all clear for takeoff, make sure a group exists with same name as user,
    # AND user is a member of it (believe it or not, I've seen this not always hold true!)
    groupadd -f $APP_USER > /dev/null 2>&1
    sleep 3
    usermod -a -G $APP_USER $APP_USER > /dev/null 2>&1

    echo " "

    else
    echo " "
    echo "${green}Exiting...${reset}"
    echo " "
    exit
    fi

echo " "


######################################


echo " "

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update


######################################

            
echo " "
echo "${yellow}OPTIONAL SECURITY-HARDENING:"
echo " "
echo "Before we install a PHP web server and Open Crypto Tracker (server edition), let's review a few OPTIONAL security-hardening configurations below. If you enable all of these security options, it will significantly improve the security of this app server (HIGHLY RECOMMENDED)...${reset}"
echo " "


######################################

            
echo "${yellow} "
read -n1 -s -r -p $'Disable bluetooth, for higher app server security? (press y to run, or press n to skip)...\n' keystroke
echo "${reset} "
        
if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
            
echo " "
echo "${cyan}Disabling bluetooth, please wait...${reset}"
                
systemctl disable hciuart.service

systemctl disable bluealsa.service

systemctl disable bluetooth.service


       # Raspberry pi devices
       if [ -f "/usr/bin/raspi-config" ]; then

         
         # Enhanced security for raspi config
         RASPI_CONF="/boot/config.txt"
            
         CHECK_RASPI_CONF=$(sed -n '/disable-bt/p' $RASPI_CONF)
            
            
            # Raspi security
            if [ "$CHECK_RASPI_CONF" == "" ]; then
            
            echo " "
            echo "${cyan}Disabling bluetooth in $RASPI_CONF, please wait...${reset}"
            echo " "
            
            
            
# Don't nest / indent, or it could malform the setting addition  
read -r -d '' RASPI_SECURITY <<- EOF
\r
# Disable on-board bluetooth
dtoverlay=disable-bt 
\r
EOF
            
            
            # Backup config before editing, to be safe
            \cp $RASPI_CONF $RASPI_CONF.BACKUP.$DATE
            
            sleep 1
            
            # APPEND the config
            echo -e "$RASPI_SECURITY" >> $RASPI_CONF

		  sleep 1
                            
            echo "${green}Disabling bluetooth in $RASPI_CONF has been completed.${reset}"
            echo " "
            
            else
            
            echo " "
            echo "${green}Bluetooth was already disabled in $RASPI_CONF.${reset}"
            echo " "
            
            fi


	  sleep 2
			
	  echo " "
       echo " "
         

       fi
          
     
echo " "
echo "${green}Disabling bluetooth has been completed.${reset}"
echo " "      
echo "${red}YOU MUST RESTART the computer for this to take affect (ONLY AFTER THIS SCRIPT IS FINISHED RUNNING).${reset}"
echo " "
            
else
echo "${green}Disabling bluetooth has been skipped.${reset}"
echo " "
fi
                

######################################

            
echo "${yellow} "
read -n1 -s -r -p $'Install / configure a firewall, for higher app server security? (press y to run, or press n to skip)...\n' keystroke
echo "${reset} "
        
if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update
            
echo " "
echo "${cyan}Installing / configuring a firewall, please wait...${reset}"
                
$PACKAGE_INSTALL ufw -y

ufw allow ssh

ufw limit ssh/tcp

ufw allow 80

ufw allow 443

ufw enable

echo " "
echo "${green}Installing / configuring a firewall has been completed."
echo " "
echo "USER GUIDE: https://www.linux.com/training-tutorials/introduction-uncomplicated-firewall-ufw/"
echo "${reset}"
echo " "
            
else
echo "${green}Installing / configuring a firewall has been skipped.${reset}"
echo " "
fi
                

######################################


# Raspberry pi devices require sudo password
if [ -f "/usr/bin/raspi-config" ]; then

echo "${yellow} "
read -n1 -s -r -p $'Require sudo password, for higher app server security? (press y to run, or press n to skip)...\n' keystroke
echo "${reset} "
        
     if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
                 
     echo " "
     echo "${cyan}Setting up requiring sudo password, please wait...${reset}"
                     
     sed -i "s/NOPASSWD/PASSWD/g" /etc/sudoers.d/010_pi-nopasswd > /dev/null 2>&1
     
     echo " "
     echo "${green}Setting up requiring sudo password has been completed.${reset}"
     echo " "
                 
     else
     echo "${green}Setting up requiring sudo password has been skipped.${reset}"
     echo " "
     fi

fi
                

######################################

            
echo "${yellow} "
read -n1 -s -r -p $'Make your home directory private, for higher app server security? (press y to run, or press n to skip)...\n' keystroke
echo "${reset} "
        
if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
            
echo " "
echo "${cyan}Making your home directory private, please wait...${reset}"

APP_USER_HOME="/home/$APP_USER"

chmod 750 $APP_USER_HOME
        
HOME_RECURSIVE_CHOWN="-R ${APP_USER}:$APP_USER ${APP_USER_HOME}/*"
        
#$RECURSIVE_CHOWN must be in double quotes to escape the asterisk at the end
chown $HOME_RECURSIVE_CHOWN

echo " "
echo "${green}Making your home directory private has been completed.${reset}"
echo " "
            
else
echo "${green}Making your home directory private has been skipped.${reset}"
echo " "
fi
                

######################################



echo "We need to know which version of PHP-FPM (fcgi) to use."
echo "Please select a PHP-FPM version NUMBER from the list below..."
echo "(PHP-FPM version 7.2 or greater is REQUIRED)"
echo " "

echo "$PHP_FPM_LIST"
echo " "

echo "${yellow}#PREFERRED# PHP-FPM package auto-detected: $FPM_PACKAGE"
echo " "
   
echo "Enter the PHP-FPM version (numeric only) that you want to install / uninstall:"
echo "(leave blank / hit enter for default of '$FPM_PACKAGE_VER')${reset}"
echo " "
   
read PHP_FPM_VER
echo " "
 
	if [ -z "$PHP_FPM_VER" ]; then
 	PHP_FPM_VER=${1:-$FPM_PACKAGE_VER}
 	echo "${green}Using default PHP-FPM version: $PHP_FPM_VER${reset}"
 	else
 	echo "${green}Using custom PHP-FPM version: $PHP_FPM_VER${reset}"
 	fi
   
echo " "

INSTALL_FPM_VER="php${PHP_FPM_VER}-fpm php${PHP_FPM_VER}-mbstring php${PHP_FPM_VER}-xml php${PHP_FPM_VER}-curl php${PHP_FPM_VER}-gd php${PHP_FPM_VER}-zip php${PHP_FPM_VER}-mysql -y"

INSTALL_APACHE="apache2 php php-fpm php-db php-mbstring php-xml php-curl php-gd php-zip libapache2-mod-fcgid apache2-suexec-custom ssl-cert -y"

# There are #NOT# PHP-FPM version choices on arch-based systems
if [ -f "/etc/arch-release" ]; then

#PHP_FPM_VER="${PHP_FPM_VER//./}"
PHP_FPM_VER=""

echo " "
echo "${cyan}php${PHP_FPM_VER} packages will be installed on your system.${reset}"
echo " "

INSTALL_FPM_VER="php php-fpm php-cgi php-gd php-xsl -y"

INSTALL_APACHE="apache php-apache -y"

fi


######################################


echo "${yellow}Select 1, 2, or 3 to choose whether to auto-install / remove the PHP web server, or skip.${reset}"
echo " "

OPTIONS="install_webserver remove_webserver skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_webserver" ]; then

          # Clears / updates cache, then upgrades (if NOT a rolling release)
          clean_system_update
         
          echo " "
			
	     echo "${green}Proceeding with PHP web server installation, please wait...${reset}"
		echo " "
        
		# !!!RUN FIRST!!! PHP FPM (fcgi) version $PHP_FPM_VER, run SEPERATE in case it fails from package not found
        
        	$PACKAGE_INSTALL $INSTALL_FPM_VER
        	
			sleep 3
			
			# PHP FPM (fcgi), Apache, required modules, etc
			$PACKAGE_INSTALL $INSTALL_APACHE
			
			sleep 3
			
			echo " "
			
			mv $DOC_ROOT/index.html $DOC_ROOT/index.php > /dev/null 2>&1


			######################################
			

			# SSL / Rewrite setup
			
			echo " "
			
			# Regenerate new self-signed SSL cert keys with ssl-cert (for secure HTTPS web pages)
			make-ssl-cert generate-default-snakeoil --force-overwrite
			
			#https://www.digitalocean.com/community/tutorials/how-to-create-a-ssl-certificate-on-apache-on-arch-linux

			echo "${cyan}New SSL certificate keys have been self-signed, please wait...${reset}"
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
			a2enmod proxy_fcgi setenvif
			
			sleep 1
			
        	     CONF_FPM_VER="php${PHP_FPM_VER}-fpm"
        	
			# Config PHP FPM (fcgi) version $PHP_FPM_VER
        	     a2enconf $CONF_FPM_VER
			
			sleep 1
			
			# Suexec
			a2enmod suexec
			
			# Fcgid
			a2enmod fcgid
			
			# actions
			a2enmod actions
			
			
			# Set PHP-FPM user to $APP_USER (to run Apache PHP as this user)
			sed -i "s/user = .*/user = $APP_USER/g" /etc/php/$PHP_FPM_VER/fpm/pool.d/www.conf > /dev/null 2>&1
			sed -i "s/group = .*/group = $APP_USER/g" /etc/php/$PHP_FPM_VER/fpm/pool.d/www.conf > /dev/null 2>&1
			
			
			sleep 1
			
			echo " "
				
				if [ -f /etc/init.d/apache2 ]; then
				echo "${cyan}New Apache modules have been enabled, restarting the Apache web server, please wait...${reset}"
				/etc/init.d/apache2 restart
				echo " "
				else
				echo "${red}New Apache modules have been enabled, YOU MUST RESTART the Apache web server for these to activate.${reset}"
				echo " "
				fi


			######################################
         
         # Enhanced security for apache MAIN config
         APACHE_CONF="/etc/apache2/apache2.conf"
            
            if [ ! -f $APACHE_CONF ]; then
            
            echo "${red}$APACHE_CONF could NOT be found on your system."
            echo "Please enter the FULL path to the MAIN Apache config file:${reset}"
            echo " "
            
            read APACHE_CONF
            echo " "
                    
                if [ ! -f $APACHE_CONF ] || [ -z "$APACHE_CONF" ]; then
                echo "${red}No MAIN Apache config file detected, skipping enhanced security setup.${reset}"
                SKIP_APACHE_CONF_EDIT=1
                else
                echo "${green}Using MAIN Apache config file:"
                echo "$APACHE_CONF${reset}"
                CHECK_APACHE_1=$(sed -n '/ServerTokens/p' $APACHE_CONF)
                CHECK_APACHE_2=$(sed -n '/ServerSignature/p' $APACHE_CONF)
                fi
            
            echo " "
            
            else
            
            CHECK_APACHE_1=$(sed -n '/ServerTokens/p' $APACHE_CONF)
            CHECK_APACHE_2=$(sed -n '/ServerSignature/p' $APACHE_CONF)
            
            fi
            
            
            # Apache security
            if [ "$SKIP_APACHE_CONF_EDIT" != "1" ] && [ "$CHECK_APACHE_1" == "" ] && [ "$CHECK_APACHE_2" == "" ]; then
            
            echo " "
            echo "${cyan}Enabling enhanced security for the MAIN Apache config, please wait...${reset}"
            echo " "
            
            
            
# Don't nest / indent, or it could malform the setting addition  
read -r -d '' APACHE_SECURITY <<- EOF
\r
# Disable showing apache product name and version number
ServerTokens Prod
ServerSignature Off 
\r
EOF
            
            
            # Backup the MAIN Apache config before editing, to be safe
            \cp $APACHE_CONF $APACHE_CONF.BACKUP.$DATE
            
            sleep 1
            
            # APPEND the config
            echo -e "$APACHE_SECURITY" >> $APACHE_CONF

		  sleep 1
                            
                            
                # Restart Apache
                if [ -f /etc/init.d/apache2 ]; then
                echo "${cyan}Enhanced security has been enabled for the MAIN Apache config,"
                echo "restarting the Apache web server, please wait...${reset}"
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "${red}Enhanced security has been enabled for the MAIN Apache config."
                echo "YOU MUST RESTART the Apache web server for this to take affect.${reset}"
                echo " "
                fi
            
            
            elif [ "$CHECK_APACHE_1" == "" ] && [ "$CHECK_APACHE_2" == "" ]; then
            
            echo " "
            echo "Enhanced security NOT DETECTABLE for the MAIN Apache config."
            
            else
            
            echo " "
            echo "Enhanced security was already enabled for the MAIN Apache config."
            echo " "
            
            fi


	    sleep 2
			
	    echo " "
         echo " "
			
			
         ######################################
			
       
         # Enable HTTP (port 80) htaccess
         #a2ensite 000-default
          
         HTTP_CONF="/etc/apache2/sites-available/000-default.conf"
            
            if [ ! -f $HTTP_CONF ]; then
            
            echo "${red}$HTTP_CONF could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTP (port 80):${reset}"
            echo " "
            
            read HTTP_CONF
            echo " "
                    
                if [ ! -f $HTTP_CONF ] || [ -z "$HTTP_CONF" ]; then
                echo "${red}No HTTP config file detected, skipping Apache config setup for port 80, please wait...${reset}"
                SKIP_HTTP_CONF_EDIT=1
                else
                echo "${green}Using Apache HTTP config file:"
                echo "$HTTP_CONF${reset}"
                CHECK_HTTP=$(<$HTTP_CONF)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTP=$(<$HTTP_CONF)
            
            fi

            
            
            # Htaccess port 80
            if [ "$SKIP_HTTP_CONF_EDIT" != "1" ] && [[ $CHECK_HTTP != *"cryptocoin_htaccess_80"* ]]; then
            
            echo " "
            
            echo "${cyan}Enabling htaccess for HTTP (port 80), please wait...${reset}"
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
                echo "${cyan}Htaccess has been enabled for HTTP (port 80),"
                echo "restarting the Apache web server, please wait...${reset}"
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "${red}Htaccess has been enabled for HTTP (port 80)."
                echo "YOU MUST RESTART the Apache web server for this to take affect.${reset}"
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
            
            echo "${red}$HTTPS_CONF could NOT be found on your system."
            echo "Please enter the FULL Apache config file path for HTTPS (port 443):${reset}"
            echo " "
            
            read HTTPS_CONF
            echo " "
                    
                if [ ! -f $HTTPS_CONF ] || [ -z "$HTTPS_CONF" ]; then
                echo "${red}No HTTPS config file detected, skipping Apache config setup for port 443, please wait...${reset}"
                SKIP_HTTPS_CONF_EDIT=1
                else
                echo "${green}Using Apache HTTPS config file:"
                echo "$HTTPS_CONF${reset}"
                CHECK_HTTPS=$(<$HTTPS_CONF)
                fi
            
            echo " "
            
            else
            
            CHECK_HTTPS=$(<$HTTPS_CONF)
            
            fi
            
            
            
            # Htaccess port 443
            if [ "$SKIP_HTTPS_CONF_EDIT" != "1" ] && [[ $CHECK_HTTPS != *"cryptocoin_htaccess_443"* ]]; then
            
            echo " "
            echo "${cyan}Enabling htaccess for HTTPS (port 443), please wait...${reset}"
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
                echo "${cyan}Htaccess has been enabled for HTTPS (port 443),"
                echo "restarting the Apache web server, please wait...${reset}"
                /etc/init.d/apache2 restart
                echo " "
                else
                echo "${red}Htaccess has been enabled for HTTPS (port 443)."
                echo "YOU MUST RESTART the Apache web server for this to take affect.${reset}"
                echo " "
                fi
            
            
            else
            
            echo " "
            echo "Htaccess was already enabled for HTTPS (port 443)."
            echo " "
            
            fi


	    sleep 2
			
	    echo " "
         echo " "
			
			
         ######################################
            
            
         # Give the new HTTP server system user a chance to exist for a few seconds, before trying to determine the name / group automatically
         echo "${cyan}PHP web server installation completed, auto-detecting it's configuration, please wait...${reset}"
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
         echo "${yellow}Enter the web server's user group:"
         echo "(leave blank / hit enter to use default group '$WWW_GROUP')${reset}"
         echo " "
            
         read APACHE_USERNAME
         echo " "
                    
            if [ -z "$APACHE_USERNAME" ]; then
            APACHE_USERNAME=${1:-$WWW_GROUP}
            echo "${green}The web server's user group has been declared as: $WWW_GROUP${reset}"
            else
            echo "${green}The web server's user group has been declared as: $APACHE_USERNAME${reset}"
            fi
            
            
	    sed -i "s/${APACHE_USERNAME}/${APP_USER}/g" /usr/lib/tmpfiles.d/php${PHP_FPM_VER}-fpm.conf > /dev/null 2>&1
			
			
	    sleep 1
			
	    echo " "
				
	       if [ -f /etc/init.d/apache2 ]; then
		  echo "${cyan}Updated /usr/lib/tmpfiles.d/php${PHP_FPM_VER}-fpm.conf, restarting the Apache web server, please wait...${reset}"
		  /etc/init.d/apache2 restart
		  echo " "
		  else
		  echo "${red}Updated /usr/lib/tmpfiles.d/php${PHP_FPM_VER}-fpm.conf, YOU MUST RESTART the Apache web server for these to activate.${reset}"
		  echo " "
		  fi
            
          echo " "
        
          # We no longer need to have the app user added to the web server's default group
          # (since we now run PHP-FPM AS THE APP USER, so we remove it for TIGHTER SECURITY)
          # PARAMS ARE *BACKWARDS* COMPARED TO "usermod -a -G"
        	gpasswd -d $APP_USER $APACHE_USERNAME > /dev/null 2>&1

		sleep 1
          
          # We STILL NEED to add the web server user to the app user's default group
          # (for access to files like .htaccess / .user.ini)
        	usermod -a -G $APP_USER $APACHE_USERNAME
        	
        	echo " "
        	echo "${cyan}Access for user '$APACHE_USERNAME' within group '$APP_USER' is completed, please wait...${reset}"
          
		sleep 1
			
        	chmod 770 $DOC_ROOT
			
        	echo " "
        	echo "${cyan}Document root access is completed (chmod 770, owner:group set to '$APP_USER'), please wait...${reset}"

		sleep 1
        
        	BASE_HTDOC="$(dirname $DOC_ROOT)"
        
        	RECURSIVE_CHOWN="-R ${APP_USER}:$APP_USER ${BASE_HTDOC}/*"
        
        	#$RECURSIVE_CHOWN must be in double quotes to escape the asterisk at the end
        	chown $RECURSIVE_CHOWN

		sleep 3
		
		OS_INFO=$(lsb_release -a)
		
		
		     # If Kali OS, set apache and php-fpm to run at boot (as they won't by default)
		     IS_KALI='Kali'
               if [[ "$OS_INFO" == *"$IS_KALI"* ]]; then
		     echo " "
		     echo "${cyan}Telling Kali to start Apache / PHP-FPM at boot, please wait...${reset}"
		     echo " "
               update-rc.d apache2 enable
               systemctl enable php${PHP_FPM_VER}-fpm
               fi
        
        
		echo " "
		echo "${green}PHP web server configuration is complete.${reset}"
        	echo " "

          echo "${red}You MUST RESTART YOUR DEVICE (#after# you finish running this auto-install script) TO ALLOW THE SYSTEM TO PROPERLY RUN THE PHP WEB SERVER CONFIGURATIONS DONE (or you may get configuration errors), by running this command:"
          echo " "
          echo "sudo reboot"
          echo "${reset} "
	     
	     SERVER_SETUP=1
        
        
        	######################################
         
         
        break
       elif [ "$opt" = "remove_webserver" ]; then

       # Clears / updates cache, then upgrades (if NOT a rolling release)
       clean_system_update
       
        echo " "
        echo "${green}Removing PHP web server, please wait...${reset}"
        echo " "
        
        # WE USE --purge TO REMOVE ANY MISCONFIGURATIONS, IN CASE SOMEBODY IS TRYING A UN-INSTALL / RE-INSTALL TO FIX THINGS
        
		  # !!!RUN FIRST!!! PHP FPM (fcgi) version $PHP_FPM_VER, run SEPERATE in case it fails from package not found
        REMOVE_FPM_VER="php${PHP_FPM_VER}-fpm php${PHP_FPM_VER}-mbstring php${PHP_FPM_VER}-xml php${PHP_FPM_VER}-curl php${PHP_FPM_VER}-gd php${PHP_FPM_VER}-zip -y"
        
        $PACKAGE_REMOVE $REMOVE_FPM_VER
        
		  sleep 3
        
        # SKIP removing openssl / ssl-cert / avahi-daemon, AS THIS WILL F!CK UP THE WHOLE SYSTEM, REMOVING ANY OTHER DEPENDANT PACKAGES TOO!!
		  $PACKAGE_REMOVE apache2 php php-fpm php-mbstring php-xml php-curl php-gd php-zip libapache2-mod-fcgid apache2-suexec-pristine apache2-suexec-custom -y
        
		  sleep 3
			
		  echo " "
		  echo "${green}PHP web server has been removed from the system.${reset}"
        
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "${green}Skipping PHP web server setup...${reset}"
        
        break
       fi
done

echo " "


######################################


echo "Do you want this script to automatically download the latest version of Open Crypto Tracker"
echo "(Server Edition) from Github.com, and install / configure it?"
echo " "

echo "${yellow}Select 1, 2, or 3 to choose whether to auto-install / remove Open Crypto Tracker (Server Edition), or skip.${reset}"
echo " "
echo "${red}(!WARNING!: REMOVING Open Crypto Tracker WILL DELETE *EVERYTHING* IN $DOC_ROOT !!)${reset}"
echo " "

OPTIONS="install_portfolio_app remove_portfolio_app skip"

select opt in $OPTIONS; do
        if [ "$opt" = "install_portfolio_app" ]; then
        
        		if [ ! -d "$DOC_ROOT" ]; then

               # Clears / updates cache, then upgrades (if NOT a rolling release)
               clean_system_update
        		
        		echo " "
				
				echo "${red}Directory $DOC_ROOT DOES NOT exist, cannot install Open Crypto Tracker."
				echo "Skipping auto-install of Open Crypto Tracker.${reset}"
				else
				
				echo " "
				echo "${cyan}Proceeding with required component installation, please wait...${reset}"
				
				echo " "
				
				# bsdtar installs may fail (essentially the same package as libarchive-tools),
				# SO WE RUN BOTH SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES INSTALL OK AFTERWARDS
				
				echo "${yellow}(you can safely ignore any upcoming 'bsdtar' install errors, if 'libarchive-tools'"
				echo "installs OK...and visa versa, as they are essentially the same package)${reset}"
				echo " "
				
				# Ubuntu 16.x, and other debian-based systems
				$PACKAGE_INSTALL bsdtar -y
				
				sleep 3
				
				# Ubuntu 18.x and higher
				$PACKAGE_INSTALL libarchive-tools -y
				
				sleep 3
				
				# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
				$PACKAGE_INSTALL pwgen openssl -y

				sleep 3
				
				echo " "
				echo "${cyan}Required component installation completed.${reset}"
				
				echo " "
				echo "${cyan}Downloading the latest version of Open Crypto Tracker (Server Edition) from Github.com, please wait...${reset}"
            echo " "
				
				mkdir DFD-Cryptocoin-Values-TEMP
				
				cd DFD-Cryptocoin-Values-TEMP
				
				# Set curl user agent, as the github API REQUIRES ONE
				curl -H "$CUSTOM_CURL_USER_AGENT_HEADER"
				
				ZIP_DL=$(curl -s 'https://api.github.com/repos/taoteh1221/Open_Crypto_Tracker/releases/latest' | jq -r '.zipball_url')
				
				wget -O DFD-Cryptocoin-Values-TEMP.zip $ZIP_DL
				
				sleep 2
				
				echo " "
				echo "${cyan}Extracting download archive, please wait...${reset}"
				echo " "
				
				bsdtar --strip-components=1 -xvf DFD-Cryptocoin-Values-TEMP.zip

				sleep 3
				
				rm DFD-Cryptocoin-Values-TEMP.zip
				
				
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
						echo "${red}Automatic random hash creation has failed, please enter a random alphanumeric string"
						echo "of text (no spaces / symbols) at least 10 characters long."
						echo " "
						echo "IF YOU SKIP THIS, no backup of the previous install's configuration files will be created (for security reasons),"
						echo "and YOU WILL LOSE ALL PREVIOUSLY-CONFIGURED SETTINGS.${reset}"
						echo " "
  						read RAND_STRING
                        echo " "
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
						
  						# Dynamic config
  						BACKUP_CONF="/dynamic-config.php"
						cp $DOC_ROOT$BACKUP_CONF $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
						chown $APP_USER:$APP_USER $DOC_ROOT$BACKUP_CONF.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1


						     # Backup all plugin configs too
                                   for plugin_dir in $DOC_ROOT/plugins/*; do
     						cp $plugin_dir/plug-conf.php $plugin_dir/plug-conf.php.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
     						chown $APP_USER:$APP_USER $plugin_dir/plug-conf.php.BACKUP.$DATE.$RAND_STRING > /dev/null 2>&1
                                   done
                                   
						
						sleep 3
						
						CONFIG_BACKUP=1
				
						
  						else
  						echo " "
  						echo "${red}No backup of the previous install's configuration files was created (for security reasons)."
  						echo "The new install WILL NOW OVERWRITE ALL PREVIOUSLY-CONFIGURED SETTINGS in $DOC_ROOT/config.php...${reset}"
  						echo " "
						fi
						
					
  					fi
  				
  				
				echo " "
				echo "${cyan}Making sure any previous install's DEPRECIATED directories / files are cleaned up, please wait...${reset}"
				
				
				# Move to new locations
				
				mv $DOC_ROOT/cache/vars/app_version.dat $DOC_ROOT/cache/vars/state-tracking/app_version.dat > /dev/null 2>&1
				mv $DOC_ROOT/cache/vars/default_bitcoin_primary_currency_pair.dat $DOC_ROOT/cache/vars/state-tracking/default_bitcoin_primary_currency_pair.dat > /dev/null 2>&1
				mv $DOC_ROOT/cache/vars/default_ct_conf_md5.dat $DOC_ROOT/cache/vars/state-tracking/default_ct_conf_md5.dat > /dev/null 2>&1
				mv $DOC_ROOT/cache/vars/light_chart_struct.dat $DOC_ROOT/cache/vars/state-tracking/light_chart_struct.dat > /dev/null 2>&1
				mv $DOC_ROOT/cache/vars/php_timeout.dat $DOC_ROOT/cache/vars/state-tracking/php_timeout.dat > /dev/null 2>&1
				mv $DOC_ROOT/cache/vars/upgrade_check_latest_version.dat $DOC_ROOT/cache/vars/state-tracking/upgrade_check_latest_version.dat > /dev/null 2>&1
				
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
  				rm -rf $DOC_ROOT/cache/logs/debugging > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/logs/errors > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/secured/external_api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/internal-api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/queue > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/rest-api > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/secured/apis > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/events/lite_chart_rebuilds > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/system/lite > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cache/charts/spot_price_24hr_volume/lite > /dev/null 2>&1
  				rm -rf $DOC_ROOT/misc-docs-etc > /dev/null 2>&1
  				rm -rf $DOC_ROOT/templates > /dev/null 2>&1
  				rm -rf $DOC_ROOT/ui-templates > /dev/null 2>&1
  				rm -rf $DOC_ROOT/cron-plugins > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/debt-tracker > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/crypto-data-bot > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/transaction-fee-charts > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/address-balance-tracker/plugin-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/price-target-alert/plugin-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/recurring-reminder/plugin-lib > /dev/null 2>&1
  				rm -rf $DOC_ROOT/plugins/transaction-fee-charts > /dev/null 2>&1

				sleep 3
				
  				# Files
				rm $DOC_ROOT/CONFIG.EXAMPLE.txt > /dev/null 2>&1
				rm $DOC_ROOT/HELP-FAQ.txt > /dev/null 2>&1
				rm $DOC_ROOT/PORTFOLIO-IMPORT-EXAMPLE-SPREADSHEET.csv > /dev/null 2>&1
				rm $DOC_ROOT/oauth.php > /dev/null 2>&1
				rm $DOC_ROOT/webhook.php > /dev/null 2>&1
				rm $DOC_ROOT/rest-api.php > /dev/null 2>&1
				rm $DOC_ROOT/logs.php > /dev/null 2>&1
				rm $DOC_ROOT/dynamic-config-only.php > /dev/null 2>&1
				rm $DOC_ROOT/cache/cacert.pem > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/notifications-queue-processing.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/check-domain-security.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/email-debugging-logs.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/purge-debugging-logs.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/email-error-logs.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/purge-error-logs.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/charts-first-run.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/cron-first-run.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/emulated-cron-lock.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/ui_upgrade_alert.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/events/upgrade_check_reminder.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/logs/errors.log > /dev/null 2>&1
				rm $DOC_ROOT/cache/logs/error.log > /dev/null 2>&1
				rm $DOC_ROOT/cache/logs/debugging.log > /dev/null 2>&1
				rm $DOC_ROOT/cache/logs/debug.log > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/app_config_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_app_config_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_ocpt_conf_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_pt_conf_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_oct_conf_md5.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_btc_prim_curr_pairing.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_btc_prim_currency_pairing.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/default_btc_prim_currency_pair.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/lite_chart_structure.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/lite_chart_struct.dat > /dev/null 2>&1
				rm $DOC_ROOT/cache/vars/beta_v6_admin_pages.dat > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/CONFIG.EXAMPLE.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/CRON_PLUGINS_README.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/CRON-PLUGINS-README.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/RASPBERRY-PI-HEADLESS-WIFI-SSH.txt > /dev/null 2>&1
				rm $DOC_ROOT/DOCUMENTATION-ETC/RASPBERRY-PI-SECURITY.txt > /dev/null 2>&1
				
				# Force-resets script timeout from config.php (automatically / dynamically re-created by app)
				rm $DOC_ROOT/.htaccess > /dev/null 2>&1 
				rm $DOC_ROOT/.user.ini > /dev/null 2>&1
				#

				sleep 3
				
				echo " "
				echo "${cyan}Installing Open Crypto Tracker (Server Edition), please wait...${reset}"
  				
  				# Copy over the upgrade install files to the install directory, after cleaning up dev files
				# No trailing forward slash here
				
				rm -rf .github > /dev/null 2>&1
				rm -rf .git > /dev/null 2>&1

				sleep 3
				
				rm .whitesource > /dev/null 2>&1
				rm .gitattributes > /dev/null 2>&1
				rm .gitignore > /dev/null 2>&1
				rm .travis.yml > /dev/null 2>&1
				rm CODEOWNERS > /dev/null 2>&1
				
				\cp -r ./ $DOC_ROOT

				sleep 3
				
				cd ../
				
				rm -rf DFD-Cryptocoin-Values-TEMP
				
				# Group read/write/exec
				chmod 770 $DOC_ROOT/cache
				chmod 770 $DOC_ROOT/plugins
				
				# Group exec
				chmod 750 $DOC_ROOT/cron.php

				sleep 1
				
				# No trailing forward slash here
				chown -R $APP_USER:$APP_USER $DOC_ROOT

				sleep 3
				
				echo " "
				echo "${green}Open Crypto Tracker (Server Edition) has been installed.${reset}"
				
				
                    ######################################
                    
                    
          		  echo " "
                    echo "If you want to use price alerts or charts, you'll need to setup a background task (cron job) for that."
                    echo " "
                    
                    echo "${yellow}Select 1 or 2 to choose whether to setup a background task (cron job) for price alerts / charts, or skip it.${reset}"
                    echo " "
                    
                    OPTIONS="auto_setup_cron skip"
                    
                    select opt in $OPTIONS; do
                            if [ "$opt" = "auto_setup_cron" ]; then
                            
                            echo " "
                            echo "${yellow}Enter the FULL system path to cron.php:"
                            echo "(leave blank / hit enter for default of $DOC_ROOT/cron.php)${reset}"
                            echo " "
                            
                            read SYS_PATH
                            echo " "
                            
                                if [ -z "$SYS_PATH" ]; then
                                SYS_PATH=${1:-$DOC_ROOT/cron.php}
                            		echo "${green}Using default system path to cron.php:"
                            		echo " "
                            		echo "$SYS_PATH${reset}"
                                else
                            		echo "${green}System path set to cron.php:"
                            		echo " "
                            		echo "$SYS_PATH${reset}"
                                fi
                            
                            echo " "
                            echo "${yellow}Options for choosing a time interval to run the background task (cron job)..."
                            echo " "
                            echo "${red}IT'S RECOMMENDED TO GO #NO LOWER THAN# EVERY 20 MINUTES FOR CHART DATA, OTHERWISE LIGHT CHART"
                            echo "DISK WRITES MAY BE EXCESSIVE FOR LOWER END HARDWARE (Raspberry PI MicroSD cards etc)."
                            echo " "
                            echo "${yellow}Enter the time interval in minutes to run this cron job:"
                            echo "(#MUST BE# either 5, 10, 15, 20, or 30...leave blank / hit enter for default of 20)${reset}"
                            echo " "
                            
                            read INTERVAL
                            echo " "
                            
                                if [ -z "$INTERVAL" ]; then
                                INTERVAL=${2:-20}
                            		echo "${green}Using default time interval of $INTERVAL minutes.${reset}"
                                else
                            		echo "${green}Time interval set to $INTERVAL minutes.${reset}"
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
                            echo "${green}A background task (cron job) has been setup for user '$APP_USER',"
                            echo "as a command in /etc/cron.d/cryptocoin:"
                            echo " "
                            echo "$CRONJOB"
                            echo " "
          				
          				    echo " "
          					echo "Open Crypto Tracker (Server Edition) has been configured.${reset}"
                            
                            CRON_SETUP=1
                            
                            break
                           elif [ "$opt" = "skip" ]; then
                           
                            echo " "
                            echo "${green}Skipping cron job setup.${reset}"
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
        echo "${green}Removing Open Crypto Tracker (Server Edition), please wait...${reset}"
        
        rm /etc/cron.d/cryptocoin > /dev/null 2>&1
		  
        rm $DOC_ROOT/.htaccess > /dev/null 2>&1
	   
	   rm $DOC_ROOT/.user.ini > /dev/null 2>&1
        
        rm -rf $DOC_ROOT/* > /dev/null 2>&1

		sleep 3
        
		echo " "
		echo "${green}Open Crypto Tracker (Server Edition) has been removed from the system.${reset}"
        
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "${green}Skipping auto-install of Open Crypto Tracker (Server Edition).${reset}"
        
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
echo "${yellow}Select 1 or 2 to choose whether to setup SSH (under 'Interfacing Options' in raspi-config), or skip it.${reset}"
echo " "
echo "${red}IF YOU CHOOSE OPTION 1, AND IT ASKS IF YOU WANT TO REBOOT AFTER CONFIGURATION, CHOOSE 'NO'"
echo "OTHERWISE #THIS AUTO-INSTALL WILL ABORT PREMATURELY#! ONLY REBOOT #AFTER# AUTO-INSTALL WITH: sudo reboot${reset}"
else
echo "${yellow}Select 1 or 2 to choose whether to setup SSH, or skip it.${reset}"
fi

echo " "

OPTIONS="setup_ssh skip"

select opt in $OPTIONS; do
        if [ "$opt" = "setup_ssh" ]; then
        

				if [ -f "/usr/bin/raspi-config" ]; then
				echo " "
				echo "${cyan}Initiating raspi-config, please wait...${reset}"
				# WE NEED SUDO HERE, or raspi-config fails in bash
				sudo raspi-config
				elif [ -f /boot/dietpi/.version ]; then
				echo " "
				echo "${cyan}Initiating dietpi-software, please wait...${reset}"
				dietpi-software
				else

                    # Clears / updates cache, then upgrades (if NOT a rolling release)
                    clean_system_update
				
				echo " "
				echo "${green}Proceeding with openssh-server installation, please wait...${reset}"
				echo " "
				
				$PACKAGE_INSTALL openssh-server -y
				
				sleep 3
				
				echo " "
				echo "${green}openssh-server installation completed.${reset}"
				
				fi
        
        
        SSH_SETUP=1
        break
       elif [ "$opt" = "skip" ]; then
        echo " "
        echo "${green}Skipping SSH setup.${reset}"
        break
       fi
done
       
echo " "


######################################

# Return to user's home directory
cd /home/$APP_USER/


echo "${yellow} "
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo "# SAVE THE INFORMATION BELOW FOR FUTURE ACCESS TO THIS APP #"
echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
echo "${reset} "



if [ "$SERVER_SETUP" = "1" ]; then

echo "${red}REMINDER...You MUST RESTART YOUR DEVICE (#after# you finish running this auto-install script) TO ALLOW THE SYSTEM TO PROPERLY RUN THE PHP WEB SERVER CONFIGURATIONS DONE (or you may get configuration errors), by running this command:"
echo " "
echo "sudo reboot"
echo "${reset} "

fi


if [ "$APP_SETUP" = "1" ]; then

echo "${cyan}Web server setup and installation / configuration of Open Crypto Tracker (Server Edition)"
echo "should now be complete (if you chose those options), unless you saw any errors on screen during setup."
echo " "

echo "${green}Open Crypto Tracker is located at (and can be edited) inside this folder:"
echo " "
echo "$DOC_ROOT"
echo " "

echo "${yellow}You may now optionally edit the APP DEFAULT CONFIG (configuration file config.php) remotely via SFTP,"
echo "or by editing it locally with nano or any other installed text editor."
echo "${reset} "


    if [ "$CONFIG_BACKUP" = "1" ]; then
    
     echo "${green}The previously-installed configuration files $DOC_ROOT/config.php AND $DOC_ROOT/dynamic-config.php have been backed up to:"
	echo " "
     echo "$DOC_ROOT/[filename].php.BACKUP.$DATE.$RAND_STRING"
	echo " "
	echo "${yellow}The bundled plugin's configuration files were also be backed up in the same manner."
	echo " "
	echo "You will need to manually move any CUSTOMIZED DEFAULT settings from backup files to the NEW configuration files with a text editor,"
	echo "otherwise you can just ignore or delete the backup files."
     echo "${reset} "

     echo "${red}IF ANYTHING STOPS WORKING AFTER UPGRADING, CLEAR YOUR BROWSER CACHE (temporary files), AND RELOAD OR RESTART THE APP. This will load the latest Javascript / Style Sheet upgrades properly.${reset}"
     echo " "
    
    fi
    
    
    if [ "$CRON_SETUP" = "1" ]; then
    
    echo "${green}A background task (cron job) has been setup for user '$APP_USER', as a command in /etc/cron.d/cryptocoin:"
    echo " "
    echo "$CRONJOB"
    echo "${reset} "
    
    fi


else

echo "${yellow}Web server setup should now be complete (if you chose that option), unless you saw any errors on screen during setup."
echo " "

echo "${green}Web site app files must be placed inside this folder:"
echo " "
echo "$DOC_ROOT"
echo " "

echo "${yellow}If web server setup has completed successfully, Open Crypto Tracker (Server Edition) can now be"
echo "installed (if you haven't already) in $DOC_ROOT remotely via SFTP, or by copying over app files locally."
echo "${reset} "

fi



if [ "$SSH_SETUP" = "1" ]; then

echo "${yellow}SFTP login details are..."
echo " "

echo "${green}INTERNAL NETWORK SFTP host (port 22, on home / internal network):"
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
echo "${reset} "

fi



echo "${yellow}#INTERNAL# NETWORK SSL / HTTPS (secure / private SSL connection) web addresses are..."
echo " "
echo "${green}IP ADDRESS (may change, unless set as static for this device within the router):"
echo " "
echo "https://$IP"
echo " "
echo "HOST ADDRESS (ONLY works on linux / mac / windows, NOT android as of 2020):"
echo " "
echo "https://${HOSTNAME}.local${reset}"
echo " "

echo "${red}IMPORTANT NOTES:"
echo " "
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

echo "${yellow}If you wish to allow external access to this app (when not on your home / internal network),"
echo "a static internal ip address / port forwarding / dynamic DNS service on your router needs to be setup"
echo "(preferably with strict firewall rules using a 'guest network' configuration, to disallow this device"
echo "requesting access to other machines on your home / internal network, and only allow it an access"
echo "route through the internet gateway)."
echo " "
echo "A #VERY HIGH# port number is recommended (NON-STANDARD is above 1,023 / UNREGISTERED is from 49,152 to 65,535),"
echo "to help avoid port scanning bots from detecting your machine (and then starting hack attempts on your bound port)."
echo " "

if [ "$ALLOW_FULL_UPGRADE" == "yes" ]; then
echo "${red}FOR ADDED SECURITY, YOU SHOULD #ALWAYS KEEP THIS OPERATING SYSTEM UP-TO-DATE# WITH THIS TERMINAL COMMAND:"
echo " "
echo "${green}sudo apt update;sudo apt upgrade -y"
echo " "
fi

echo "${yellow}SEE /DOCUMENTATION-ETC/RASPBERRY-PI/ for additional information on securing and setting"
echo "up Raspberry Pi OS (disabling bluetooth, firewall setup, remote login, hostname, etc)."
echo " "

echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~${reset}"
echo " "


######################################


echo "${yellow}ANY DONATIONS (LARGE OR SMALL) HELP SUPPORT DEVELOPMENT OF MY APPS..."
echo " "
echo "${cyan}Bitcoin: ${green}3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW"
echo " "
echo "${cyan}Ethereum: ${green}0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8"
echo " "
echo "${cyan}Solana: ${green}GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU"
echo " "


######################################


# Mark the portfolio install as having run already, to avoid showing
# the OPTIONAL portfolio install options at end of the ticker install
export FOLIO_INSTALL_RAN=1

                    
if [ -z "$TICKER_INSTALL_RAN" ]; then

echo " "
echo "${red}!!!!!BE SURE TO SCROLL UP, TO SAVE #ALL THE PORTFOLIO APP USAGE DOCUMENTATION#"
echo "PRINTED OUT ABOVE, BEFORE YOU SIGN OFF FROM THIS TERMINAL SESSION!!!!!${reset}"

echo " "
echo "Also check out my 100% FREE open source multi-crypto slideshow ticker for Raspberry Pi LCD screens:"
echo " "
echo "https://sourceforge.net/projects/dfd-crypto-ticker"
echo " "
echo "https://github.com/taoteh1221/Slideshow_Crypto_Ticker"
echo " "

echo "Would you like to ${red}ADDITIONALLY / OPTIONALLY${reset} install Slideshow Crypto Ticker,"
echo "multi-crypto slideshow ticker for Raspberry Pi LCD screens on this machine?"
echo " "

echo "Select 1 or 2 to choose whether to ${red}optionally${reset} install the crypto ticker"
echo "for Raspberry Pi LCD screens, or skip."
echo " "

OPTIONS="install_crypto_ticker skip"

	select opt in $OPTIONS; do
        if [ "$opt" = "install_crypto_ticker" ]; then
         
			
			echo " "
			
			echo "${green}Proceeding with crypto ticker installation, please wait...${reset}"
			
			echo " "
			
			wget --no-cache -O TICKER-INSTALL.bash https://raw.githubusercontent.com/taoteh1221/Slideshow_Crypto_Ticker/main/TICKER-INSTALL.bash
			
			chmod +x TICKER-INSTALL.bash
			
			chown $APP_USER:$APP_USER TICKER-INSTALL.bash
			
			./TICKER-INSTALL.bash
			
			
        break
       elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "${green}Skipping the ${red}OPTIONAL ${green}crypto ticker install...${reset}"
		echo " "
		echo "${cyan}Installation / setup has finished, exiting to terminal...${reset}"
        echo " "
		exit
		  
        break
        
       fi
	done


else

echo " "
echo "${cyan}Installation / setup has finished, exiting to terminal...${reset}"
echo " "
echo "${red}!!!!!BE SURE TO SCROLL UP, TO SAVE #ALL THE PORTFOLIO APP USAGE DOCUMENTATION#"
echo "PRINTED OUT ABOVE, BEFORE YOU SIGN OFF FROM THIS TERMINAL SESSION!!!!!${reset}"
echo " "
exit

fi


######################################

