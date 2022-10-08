#!/bin/bash

# Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


echo " "
echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "
echo "Initializing, please wait..."
echo " "
				

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

echo " "
        
if [ "$EUID" == 0 ]; then 
 echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
 echo " "
 echo "${cyan}Exiting...${reset}"
 echo " "
 exit
fi


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


SCRIPT_LOCATION="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"


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


# Get primary dependency apps, if we haven't yet
    
# Install git if needed
GIT_PATH=$(which git)

if [ -z "$GIT_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component git, please wait...${reset}"
echo " "

sudo apt update

sudo apt install git -y

fi


# Install curl if needed
CURL_PATH=$(which curl)

if [ -z "$CURL_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component curl, please wait...${reset}"
echo " "

sudo apt update

sudo apt install curl -y

fi


# Install jq if needed
JQ_PATH=$(which jq)

if [ -z "$JQ_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component jq, please wait...${reset}"
echo " "

sudo apt update

sudo apt install jq -y

fi


# Install wget if needed
WGET_PATH=$(which wget)

if [ -z "$WGET_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component wget, please wait...${reset}"
echo " "

sudo apt update

sudo apt install wget -y

fi


# Install sed if needed
SED_PATH=$(which sed)

if [ -z "$SED_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component sed, please wait...${reset}"
echo " "

sudo apt update

sudo apt install sed -y

fi


# Install less if needed
LESS_PATH=$(which less)
				
if [ -z "$LESS_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component less, please wait...${reset}"
echo " "

sudo apt update

sudo apt install less -y

fi


# Install expect if needed
EXPECT_PATH=$(which expect)
				
if [ -z "$EXPECT_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component expect, please wait...${reset}"
echo " "

sudo apt update

sudo apt install expect -y

fi


# Install bc if needed (for decimal math in bash)
BC_PATH=$(which bc)

if [ -z "$BC_PATH" ]; then

DEPS_MISSING=1

echo " "
echo "${cyan}Installing required component bc, please wait...${reset}"
echo " "

sudo apt update

sudo apt install bc -y

fi

# dependency check END


######################################


# For setting user agent header in curl, since some API servers !REQUIRE! a set user agent OR THEY BLOCK YOU
CUSTOM_CURL_USER_AGENT_HEADER="User-Agent: Curl (${OS}/$VER; compatible;)"

echo " "

if [ -f "/etc/debian_version" ]; then
echo "${cyan}Your system has been detected as Debian-based, which is compatible with this automated installation script."
echo " "
echo "Continuing...${reset}"
echo " "
else
echo "${red}Your system has been detected as NOT BEING Debian-based. Your system is NOT compatible with this automated installation script."
echo " "
echo "Exiting...${reset}"
echo " "
exit
fi
				
            
######################################


if [ -d "$SCRIPT_LOCATION/INSTALL_CRYPTO_TRACKER_HERE" ]; then
DEFAULT_LOCATION="$SCRIPT_LOCATION"
else
DEFAULT_LOCATION="/home/$TERMINAL_USERNAME/Desktop/Open_Crypto_Tracker-linux-desktop"
fi

			
echo " "
echo "${yellow}Enter the FULL SYSTEM PATH to the Desktop Edition main folder:"
echo "(DO !NOT! INCLUDE A #TRAILING# FORWARD SLASH)"
echo " "
echo "(leave blank / hit enter to use the default value: $DEFAULT_LOCATION)${reset}"
echo " "

read APP_ROOT
echo " "
        
if [ -z "$APP_ROOT" ]; then
APP_ROOT="$DEFAULT_LOCATION"
echo "${green}Using default Desktop Edition location:"
echo "$APP_ROOT${reset}"
else
echo "${green}Using custom Desktop Edition location:"
echo "$APP_ROOT${reset}"
fi

echo " "


if [ ! -d "$APP_ROOT" ]; then
echo "The defined Desktop Edition location '$APP_ROOT' does not exist yet."
echo "Please create this directory structure before running this script."
echo "Exiting..."
echo " "
exit
fi


echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "

echo "${yellow} "
read -n1 -s -r -p $"Press y to continue (or press n to exit)..." key
echo "${reset} "

    if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
    echo " "
    echo "${green}Continuing...${reset}"
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

echo "${cyan}Making sure your system is updated before installation, please wait...${reset}"

echo " "
			
sudo apt-get update

#DO NOT RUN dist-upgrade, bad things can happen, lol
sudo apt-get upgrade -y

echo " "
				
echo "${cyan}System update completed.${reset}"
				
sleep 3
				
echo " "
echo "${cyan}Proceeding with required component installation, please wait...${reset}"

echo " "

# bsdtar installs may fail (essentially the same package as libarchive-tools),
# SO WE RUN BOTH SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES INSTALL OK AFTERWARDS

echo "${yellow}(you can safely ignore any upcoming 'bsdtar' install errors, if 'libarchive-tools'"
echo "installs OK...and visa versa, as they are essentially the same package)${reset}"
echo " "

# Ubuntu 16.x, and other debian-based systems
sudo apt-get install bsdtar -y

sleep 3

# Ubuntu 18.x and higher
sudo apt-get install libarchive-tools -y

sleep 3

# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
sudo apt-get install pkg-config build-essential autoconf bison re2c libxml2-dev libsqlite3-dev -y

sleep 3

echo " "
echo "${cyan}Required component installation completed.${reset}"

echo " "


######################################


echo " "
echo "${cyan}Getting PHP source code, please wait...${reset}"
echo " "


mkdir $HOME/php-source

cd $HOME/php-source

git clone https://github.com/php/php-src.git

cd php-src

git checkout master


echo " "
echo "${cyan}Building the PHP binary files, please wait...${reset}"
echo " "


./buildconf

./configure \
  --enable-bcmath \
  --enable-gd \
  --enable-calendar \
  --enable-dba \
  --enable-exif \
  --enable-ftp \
  --enable-fpm \
  --enable-mbstring \
  --enable-shmop \
  --enable-sigchild \
  --enable-soap \
  --enable-sockets \
  --enable-sysvmsg \
  --with-libdir=lib64 \
  --with-zip \
  --with-bz2 \
  --with-curl \
  --with-gettext \
  --with-openssl \
  --with-pdo-mysql \
  --with-zlib \
  --with-libxml \
  --with-freetype \
  --prefix=$HOME/php-binaries

make


echo " "
echo "${cyan}Installing the PHP binary files, please wait...${reset}"
echo " "


make install

\cp $HOME/php-binaries/bin/php-cgi $APP_ROOT/php-cgi-custom


echo " "
echo "${green} The old PHP CGI binary '$APP_ROOT/php-cgi-custom' within your Desktop Edition has been replaced with a new custom PHP CGI binary, that should be compatible with your system. Try to run linux Desktop Edition of this crypto tracker now, and it should work, IF it was indeed a shared library issue.${reset}"
echo " "
echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "



