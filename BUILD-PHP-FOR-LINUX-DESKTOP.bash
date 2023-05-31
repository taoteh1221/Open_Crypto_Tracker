#!/bin/bash

# Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


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


echo " "

        
if [ "$EUID" == 0 ]; then 
 echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
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
elif [ -f "/etc/redhat-release" ]; then
echo "${cyan}Your system has been detected as Redhat-based, which is compatible with this automated installation script."
PACKAGE_INSTALL="sudo yum install"
PACKAGE_REMOVE="sudo yum remove"
echo " "
echo "Continuing...${reset}"
echo " "
else
echo "${red}Your system has been detected as NOT BEING Debian-based OR Redhat-based. Your system is NOT compatible with this automated installation script."
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
          
          # Clears / updates cache, then upgrades (if NOT a rolling release)
          clean_system_update
          
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
               elif [ -f "/etc/redhat-release" ]; then
               sudo yum upgrade -y
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
				

     if [ -z "$BSDTAR_PATH" ]; then
     
     # Clears / updates cache, then upgrades (if NOT a rolling release)
     clean_system_update
     
     echo " "
     echo "${cyan}Installing required component libarchive-tools, please wait...${reset}"
     echo " "
     
     # Ubuntu 18.x and higher
     $PACKAGE_INSTALL libarchive-tools -y
     
     fi
     

elif [ -f "/etc/redhat-release" ]; then

# Get the host ip address
IP=$(ip -json route get 8.8.8.8 | jq -r '.[].prefsrc')
				

     if [ -z "$BSDTAR_PATH" ]; then
     
     # Clears / updates cache, then upgrades (if NOT a rolling release)
     clean_system_update
     
     echo " "
     echo "${cyan}Installing required component libarchive, please wait...${reset}"
     echo " "
     
     # Ubuntu 18.x and higher
     $PACKAGE_INSTALL libarchive -y
     
     fi
     

fi


######################################



# For setting user agent header in curl, since some API servers !REQUIRE! a set user agent OR THEY BLOCK YOU
CUSTOM_CURL_USER_AGENT_HEADER="User-Agent: Curl (${OS}/$VER; compatible;)"
				
            
######################################


# Parent directory of the script location
PARENT_DIR="$(dirname "$SCRIPT_LOCATION")"


if [ -d "$SCRIPT_LOCATION/INSTALL_CRYPTO_TRACKER_HERE" ] && [ -f $SCRIPT_LOCATION/libcef.so ]; then

APP_ROOT="$SCRIPT_LOCATION"

echo " "
echo "${green}Using auto-detected Desktop Edition location:"
echo " "
echo "$SCRIPT_LOCATION${reset}"
echo " "

elif [ -d "$PARENT_DIR/INSTALL_CRYPTO_TRACKER_HERE" ] && [ -f $PARENT_DIR/libcef.so ]; then

APP_ROOT="$PARENT_DIR"

echo " "
echo "${green}Using auto-detected Desktop Edition location:"
echo " "
echo "$PARENT_DIR${reset}"
echo " "

else

DEFAULT_LOCATION="/home/$TERMINAL_USERNAME/Desktop/Open_Crypto_Tracker-linux-desktop"

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
    echo " "
    echo "$APP_ROOT${reset}"
    echo " "
    else
    echo "${green}Using custom Desktop Edition location:"
    echo " "
    echo "$APP_ROOT${reset}"
    echo " "
    fi

fi


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


# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update

				
echo " "
echo "${cyan}Proceeding with required component installation, please wait...${reset}"
echo " "


if [ -f "/etc/debian_version" ]; then

echo "${yellow}(Debian-based system detected)${reset}"
echo " "

# bsdtar installs may fail (essentially the same package as libarchive-tools),
# SO WE RUN BOTH SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES STILL INSTALL OK AFTERWARDS

echo "${yellow}(you can safely ignore any upcoming 'bsdtar' install errors, if 'libarchive-tools'"
echo "installs OK...and visa versa, as they are essentially the same package)${reset}"
echo " "

# Ubuntu 16.x, and other debian-based systems
$PACKAGE_INSTALL bsdtar -y

sleep 3

# Ubuntu 18.x and higher
$PACKAGE_INSTALL libarchive-tools -y

sleep 3

# Dev libs (including for the extensions we want to add)
# WE RUN SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES STILL INSTALL OK AFTERWARDS
$PACKAGE_INSTALL libssl-dev -y
sleep 1
$PACKAGE_INSTALL libcurl4-openssl-dev -y
sleep 1
$PACKAGE_INSTALL libzip-dev -y
sleep 1
$PACKAGE_INSTALL libbz2-dev -y
sleep 1
$PACKAGE_INSTALL libxml2-dev -y
sleep 1
$PACKAGE_INSTALL libsqlite3-dev -y
sleep 1
$PACKAGE_INSTALL libonig-dev -y

sleep 3

# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
$PACKAGE_INSTALL pkg-config build-essential autoconf bison re2c -y


elif [ -f "/etc/redhat-release" ]; then

echo "${yellow}(Redhat-based system detected)${reset}"
echo " "

$PACKAGE_INSTALL libarchive -y

sleep 3

# Dev libs (including for the extensions we want to add)
# WE RUN SEPERATELY IN CASE AN ERROR THROWS, SO OTHER PACKAGES STILL INSTALL OK AFTERWARDS
$PACKAGE_INSTALL openssl-devel -y
sleep 1
$PACKAGE_INSTALL libcurl-devel -y
sleep 1
$PACKAGE_INSTALL libzip libzip-devel -y
sleep 1
$PACKAGE_INSTALL bzip2-libs bzip2-devel -y
sleep 1
$PACKAGE_INSTALL libxml2-devel -y
sleep 1
$PACKAGE_INSTALL sqlite-devel -y
sleep 1
$PACKAGE_INSTALL oniguruma-devel -y
sleep 1
$PACKAGE_INSTALL libpng-devel -y
sleep 1
$PACKAGE_INSTALL freetype-devel -y

sleep 3

sudo yum groupinstall 'Development Tools' -y

# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
$PACKAGE_INSTALL autoconf bison re2c -y


fi


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
echo "${cyan}UNLESS YOU SEE ANY ERRORS ABOVE, ${green}the old PHP CGI binary '$APP_ROOT/php-cgi-custom' within your Desktop Edition should have just been replaced with a new custom PHP CGI binary, that should be compatible with your system. Try to run linux Desktop Edition of this crypto tracker now, and it should work...IF it was indeed a shared library issue.${reset}"
echo " "
echo "PLEASE REPORT ANY ISSUES HERE: https://github.com/taoteh1221/Open_Crypto_Tracker/issues"
echo " "


######################################


echo "${yellow} "
read -n1 -s -r -p $"ONE LAST THING: Press d to delete the temporary CUSTOM PHP source / binaries we created at $HOME/php-source AND $HOME/php-binaries, (or press k if you prefer to keep them [we don't need them to run the app])..." key
echo " "
echo "${reset} "

    if [ "$key" = 'd' ] || [ "$key" = 'D' ]; then
    echo " "
    echo "${cyan}Deleting CUSTOM PHP source / binaries, please wait...${reset}"
    echo " "
    
    rm -rf $HOME/php-binaries
    rm -rf $HOME/php-source
    
    echo "${green}CUSTOM PHP source / binaries were deleted, now exiting this script...${reset}"
    echo " "


    else
    echo " "
    echo "${green}Skipping deletion of CUSTOM PHP source / binaries, now exiting this script...${reset}"
    echo " "
    exit
    fi

echo " "



