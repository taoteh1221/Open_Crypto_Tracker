#!/bin/bash

# Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


# Sets which version of PHP to use, via github.com release tags:
# https://github.com/php/php-src/tags
# ALWAYS verify the version used here is secure (with NO vulnerabilities):
# https://www.cvedetails.com/vulnerability-list/vendor_id-74/PHP.html
PHP_GITHUB_RELEASE_TAG="php-8.3.8"


ISSUES_URL="https://github.com/taoteh1221/Open_Crypto_Tracker/issues"


echo " "
echo "PLEASE REPORT ANY ISSUES HERE: $ISSUES_URL"
echo " "
echo "Initializing, please wait..."
echo " "


######################################


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


# In case we are recursing back into this script (for filtering params etc),
# flag export of a few more basic sys vars if present

# Authentication of X sessions
export XAUTHORITY=~/.Xauthority 
# Working directory
export PWD=$PWD


######################################


# Get date / time
DATE=$(date '+%Y-%m-%d')
TIME=$(date '+%H:%M:%S')

# Current timestamp
CURRENT_TIMESTAMP=$(date +%s)

# Are we running on Ubuntu OS?
IS_UBUNTU=$(cat /etc/os-release | grep "PRETTY_NAME" | grep "Ubuntu")


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

# Parent directory of the script location
PARENT_DIR="$(dirname "$SCRIPT_LOCATION")"


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


if [ "$EUID" == 0 ]; then 

echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"

echo "${yellow} "
read -n1 -s -r -p $"PRESS ANY KEY to exit..." key
echo "${reset} "

    if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
    echo " "
    echo "${green}Exiting...${reset}"
    echo " "
    exit
    fi

fi


######################################


if [ -f "/etc/debian_version" ]; then

echo "${cyan}Your system has been detected as Debian-based, which is compatible with this automated script."

# USE 'apt-get' IN SCRIPTING!
# https://askubuntu.com/questions/990823/apt-gives-unstable-cli-interface-warning
PACKAGE_INSTALL="sudo apt-get install"
PACKAGE_REMOVE="sudo apt-get --purge remove"

echo " "
echo "Continuing...${reset}"
echo " "

elif [ -f "/etc/redhat-release" ]; then

echo "${cyan}Your system has been detected as Redhat-based, which is compatible with this automated script."

PACKAGE_INSTALL="sudo yum install"
PACKAGE_REMOVE="sudo yum remove"

echo " "
echo "Continuing...${reset}"
echo " "

else

echo "${red}Your system has been detected as NOT BEING Debian-based OR Redhat-based. Your system is NOT compatible with this automated script."

echo "${yellow} "
read -n1 -s -r -p $"PRESS ANY KEY to exit..." key
echo "${reset} "

    if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
    echo " "
    echo "${green}Exiting...${reset}"
    echo " "
    exit
    fi

fi


echo "${cyan} "
echo "Using PHP (Version) Github Release Tag: ${yellow}${PHP_GITHUB_RELEASE_TAG}"
echo " "
echo "${red}TO CHOOSE A DIFFERENT VERSION, DETERMINE THE RELEASE TAG NAME HERE:"
echo "https://github.com/php/php-src/tags"
echo " "
echo "THEN CHANGE THIS VARIABLE AT THE TOP OF THIS SCRIPT:"
echo "PHP_GITHUB_RELEASE_TAG"
echo "${reset} "
     
echo "${yellow} "
read -n1 -s -r -p $"PRESS ANY KEY to continue..." key
echo "${reset} "
     
    if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
    echo " "
    echo "${green}Continuing...${reset}"
    echo " "
    fi
     
echo " "
     

######################################


# Path to app (CROSS-DISTRO-COMPATIBLE)
get_app_path() {

app_path_result=$(whereis -b $1)
app_path_result="${app_path_result#*$1: }"
app_path_result=${app_path_result%%[[:space:]]*}
app_path_result="${app_path_result#*$1:}"
     
     
     # If we have found the library already installed on this system
     if [ ! -z "$app_path_result" ]; then
     
     PATH_CHECK_REENTRY="" # Reset reentry flag
     
     echo "$app_path_result"
     
     # If we are re-entering from the else statement below, quit trying, with warning sent to terminal (NOT function output)
     elif [ ! -z "$PATH_CHECK_REENTRY" ]; then
     
     PATH_CHECK_REENTRY="" # Reset reentry flag
     
     echo "${red} " > /dev/tty
     echo "System path for '$1' NOT FOUND, even AFTER package installation attempts, giving up." > /dev/tty
     echo " " > /dev/tty

     echo "*PLEASE* REPORT THIS ISSUE HERE, *IF THIS SCRIPT FAILS TO RUN PROPERLY FROM THIS POINT ONWARD*:" > /dev/tty
     echo " " > /dev/tty
     echo "$ISSUES_URL" > /dev/tty
     echo "${reset} " > /dev/tty
     
     echo "${yellow} " > /dev/tty
     read -n1 -s -r -p $"PRESS ANY KEY to continue..." key
     echo "${reset} " > /dev/tty
     
         if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
         echo " " > /dev/tty
         echo "${green}Continuing...${reset}" > /dev/tty
         echo " " > /dev/tty
         fi
     
     echo " " > /dev/tty
     
     # If library not found, attempt package installation
     else
     
     
          # Handle package name exceptions...
          
          # bsdtar on Ubuntu 18.x and higher
          if [ "$1" == "bsdtar" ] && [ -f "/etc/debian_version" ]; then
          SYS_PACK="libarchive-tools"
          
          # xdg-user-dir (debian package name differs slightly)
          elif [ "$1" == "xdg-user-dir" ] && [ -f "/etc/debian_version" ]; then
          SYS_PACK="xdg-user-dirs"

          # rsyslogd (debian package name differs slightly)
          elif [ "$1" == "rsyslogd" ] && [ -f "/etc/debian_version" ]; then
          SYS_PACK="rsyslog"

          else
          SYS_PACK="$1"
          fi
          
          
          # Terminal alert for good UX...
          if [ "$1" != "$SYS_PACK" ]; then
          echo " " > /dev/tty
          echo "${yellow}'$1' is found WITHIN '$SYS_PACK', changing package request accordingly...${reset}" > /dev/tty
          echo " " > /dev/tty
          fi


     echo " " > /dev/tty
     echo "${cyan}Installing required component '$SYS_PACK', please wait...${reset}" > /dev/tty
     echo " " > /dev/tty
     
     sleep 3
               
     $PACKAGE_INSTALL $SYS_PACK -y > /dev/tty
     
     
          # If UBUNTU (*NOT* any other OS) snap was detected on the system, try a snap install too
          # (as they moved some libs over to snap / snap-only? now)
          if [ ! -z "$UBUNTU_SNAP_PATH" ]; then
          
          UBUNTU_SNAP_INSTALL="sudo $UBUNTU_SNAP_PATH install"
          
          echo " " > /dev/tty
          echo "${yellow}CHECKING FOR UBUNTU SNAP PACKAGE '$SYS_PACK', please wait...${reset}" > /dev/tty
          echo " " > /dev/tty
          
          sleep 3
          
          $UBUNTU_SNAP_INSTALL $SYS_PACK > /dev/tty
          
          fi
     
     
     sleep 2
     
     PATH_CHECK_REENTRY=1 # Set reentry flag, right before reentry
     
     echo $(get_app_path "$1")
           
     fi


}


# Ubuntu uses snaps for very basic libraries these days, so we need to configure for possible snap installs
if [ "$IS_UBUNTU" != "" ]; then
UBUNTU_SNAP_PATH=$(get_app_path "snap")
fi


######################################


# ON DEBIAN-BASED SYSTEMS ONLY:
# Do we have less than 900MB PHYSICAL RAM (IN KILOBYTES),
# AND no swap / less swap virtual memory than 900MB (IN BYTES)?
if [ -f "/etc/debian_version" ] && [ "$(awk '/MemTotal/ {print $2}' /proc/meminfo)" -lt 900000 ] && (
[ "$(free | awk '/^Swap:/ { print $2 }')" = "0" ] || [ "$(free --bytes | awk '/^Swap:/ { print $2 }')" -lt 900000000 ]
); then

echo "${red}YOU HAVE LESS THAN 900MB *PHYSICAL* MEMORY, AND ALSO HAVE LESS THAN 900MB SWAP *VIRTUAL* MEMORY. This MAY cause your system to FREEZE, *IF* you have a desktop display attached!${reset}"

echo "${yellow} "
read -n1 -s -r -p $"PRESS F to fix this (sets swap virtual memory to 1GB), OR any other key to skip fixing..." key
echo "${reset} "

    if [ "$key" = 'f' ] || [ "$key" = 'F' ]; then

    echo " "
    echo "${cyan}Changing Swap Virtual Memory size to 1GB, please wait (THIS MAY TAKE AWHILE ON SMALLER SYSTEMS)...${reset}"
    echo " "
    
    # Required components check...
    
    # dphys-swapfile
    DPHYS_PATH=$(get_app_path "dphys-swapfile")

    # sed
    SED_PATH=$(get_app_path "sed")
    
    sudo $DPHYS_PATH swapoff
    
    sleep 5
         
        if [ -f /etc/dphys-swapfile ]; then
			    
	   DETECT_SWAP_CONF=$(sudo sed -n '/CONF_SWAPSIZE=/p' /etc/dphys-swapfile)
			
		   if [ "$DETECT_SWAP_CONF" != "" ]; then 
             sudo sed -i "s/CONF_SWAPSIZE=.*/CONF_SWAPSIZE=1024/g" /etc/dphys-swapfile
             elif [ "$DETECT_SWAP_CONF" == "" ]; then 
             sudo bash -c "echo 'CONF_SWAPSIZE=1024' >> /etc/dphys-swapfile"
	        fi
	        
	   sudo $DPHYS_PATH setup
	   
	   sleep 5
	   
	   sudo $DPHYS_PATH swapon
	   
	   sleep 5
	   
        echo " "
        echo "${green}Swap Memory size has been updated to 1GB.${reset}"
        echo " "
        
        else
	   
        echo " "
        echo "${red}Swap Memory config file could NOT be located, skipping update of Swap Memory size!${reset}"
        echo " "
	        
	   fi
	   
    else

    echo " "
    echo "${green}Skipping...${reset}"
    echo " "
    
    fi

fi


######################################


# clean_system_update function START
clean_system_update () {


     if [ -z "$ALLOW_FULL_UPGRADE" ]; then
     
     echo " "
     echo "${yellow}Does the Operating System on this device update using the \"Rolling Release\" model (Kali, Manjaro, Ubuntu Rolling Rhino, Debian Unstable, etc), or the \"Long-Term Release\" model (Debian, Ubuntu, Raspberry Pi OS, Armbian Stable, Diet Pi, etc)?"
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


     if [ -z "$PACKAGE_CACHE_REFRESHED" ]; then


          if [ -f "/etc/debian_version" ]; then

          echo "${cyan}Making sure your APT sources list is updated before installations, please wait...${reset}"
          
          echo " "
          
          # In case package list was ever corrupted (since we are about to rebuild it anyway...avoids possible errors)
          sudo rm -rf /var/lib/apt/lists/* -vf > /dev/null 2>&1
          
          sleep 2
          
          sudo apt-get update
          
          echo " "
     
          echo "${cyan}APT sources list update complete.${reset}"
          
          echo " "
     
          fi
          
     
          if [ "$ALLOW_FULL_UPGRADE" == "yes" ]; then

          echo "${cyan}Making sure your system is updated before installations, please wait...${reset}"
          
          echo " "
          
          
               if [ -f "/etc/debian_version" ]; then
               #DO NOT RUN dist-upgrade, bad things can happen, lol
               sudo apt-get upgrade -y
               elif [ -f "/etc/redhat-release" ]; then
               sudo yum upgrade -y
               fi
          
          
          sleep 2
          
          echo " "
          				
          echo "${cyan}System updated.${reset}"
          				
          echo " "
          
          fi
     
     
     PACKAGE_CACHE_REFRESHED=1
     
     fi

}
# clean_system_update function END

# Clears / updates cache, then upgrades (if NOT a rolling release)
clean_system_update


######################################


# Get PRIMARY dependency lib's paths (for bash scripting commands...auto-install is attempted, if not found on system)
# (our usual standard library prerequisites [ordered alphabetically], for 99% of advanced bash scripting needs)

# avahi-daemon
AVAHID_PATH=$(get_app_path "avahi-daemon")

# bc
BC_PATH=$(get_app_path "bc")

# bsdtar
BSDTAR_PATH=$(get_app_path "bsdtar")

# curl
CURL_PATH=$(get_app_path "curl")

# expect
EXPECT_PATH=$(get_app_path "expect")
    
# git
GIT_PATH=$(get_app_path "git")

# jq
JQ_PATH=$(get_app_path "jq")

# less
LESS_PATH=$(get_app_path "less")

# sed
SED_PATH=$(get_app_path "sed")

# wget
WGET_PATH=$(get_app_path "wget")

# PRIMARY dependency lib's paths END
				

######################################


# Get the *INTERNAL* NETWORK ip address
IP=$(ip -o route get to 8.8.8.8 | sed -n 's/.*src \([0-9.]\+\).*/\1/p')

            
######################################


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
echo "${yellow}ENTER THE FULL SYSTEM PATH to the Desktop Edition main folder:"
echo "(DO !NOT! INCLUDE A #TRAILING# FORWARD SLASH)"
echo " "
echo "(LEAVE BLANK / HIT ENTER to use the default value: $DEFAULT_LOCATION)${reset}"
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
echo "${red}The defined Desktop Edition location '$APP_ROOT' does not exist yet. Please create this directory structure before running this script again.${reset}"

echo "${yellow} "
read -n1 -s -r -p $"PRESS ANY KEY to exit..." key
echo "${reset} "

    if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
    echo " "
    echo "${green}Exiting...${reset}"
    echo " "
    exit
    fi

echo " "
fi


echo "${cyan}PLEASE REPORT ANY ISSUES HERE: $ISSUES_URL"
echo "${reset} "


echo "${yellow}THIS SCRIPT ATTEMPTS TO AUTOMATICALLY FIX LINUX-BASED 'DESKTOP EDITION' INSTALLATIONS OF OPEN CRYPTO TRACKER. BUILDING PHP BINARIES WILL TAKE AWHILE, EVEN ON A FAST MACHINE (YOU WILL SEE SCROLLING MESSAGES FOR AWHILE, WHICH IS NORMAL DURING COMPILATION)."
echo "${reset} "


echo "${red}PLEASE ***SHUT DOWN THE DESKTOP EDITION*** BEFORE CONTINUING, ***OTHERWISE WE CANNOT AUTOMATICALLY UPDATE*** THE PHP LIBRARY!"
echo "${reset} "


echo "${yellow} "
read -n1 -s -r -p $"PRESS Y to continue (or PRESS N to exit)..." key
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


if [ -f "/etc/debian_version" ]; then

echo "${yellow}(Debian-based system detected)${reset}"
echo " "

echo "${green}Making sure GTK / libxss support is enabled for Debian, please wait...${reset}"
echo " "

sleep 2

# 32-bit GTK2 Debian support (for the 'RUN_CRYPTO_TRACKER' binary)
$PACKAGE_INSTALL libgtk2.0-dev -y

sleep 2

# libxss support
$PACKAGE_INSTALL libxss-dev -y
				
echo " "
echo "${cyan}Proceeding with installation of PHP's required Debian libraries, please wait...${reset}"
echo " "

sleep 2

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
sleep 1
$PACKAGE_INSTALL libpng-dev -y
sleep 1
$PACKAGE_INSTALL libfreetype-dev -y

sleep 2

# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
$PACKAGE_INSTALL pkg-config build-essential autoconf bison re2c -y


elif [ -f "/etc/redhat-release" ]; then

echo "${yellow}(Redhat-based system detected)${reset}"
echo " "

echo "${green}Making sure GTK / libxss support is enabled for RedHat, please wait...${reset}"
echo " "

sleep 2

# 32-bit GTK2 RedHat support (for the 'RUN_CRYPTO_TRACKER' binary)
$PACKAGE_INSTALL gtk2 -y

sleep 2

# libxss support
# CASE-SENSITIVE!
$PACKAGE_INSTALL libXScrnSaver -y
				
echo " "
echo "${cyan}Proceeding with installation of PHP's required RedHat libraries, please wait...${reset}"
echo " "

sleep 2

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

sleep 2

sudo yum group install -y --skip-broken --skip-unavailable development-tools

# Safely install other packages seperately, so they aren't cancelled by 'package missing' errors
$PACKAGE_INSTALL autoconf bison re2c -y


fi


sleep 2

echo " "
echo "${cyan}Required component installation completed.${reset}"
echo " "


######################################


echo " "
echo "${cyan}Getting PHP source code, please wait...${reset}"
echo " "


mkdir $HOME/php-source > /dev/null 2>&1

cd $HOME/php-source

git clone https://github.com/php/php-src.git

cd php-src

git checkout tags/$PHP_GITHUB_RELEASE_TAG


echo " "
echo "${cyan}Building (compiling) the required PHP binary files, please wait...${reset}"
echo " "


./buildconf --force


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
echo "${cyan}Installing the PHP binary files (to a UNIQUE location you can remove after processing), please wait...${reset}"
echo " "


make install

\cp $HOME/php-binaries/bin/php-cgi $APP_ROOT/php-cgi-custom > /dev/null 2>&1


echo " "
echo "${cyan}UNLESS YOU SEE ANY ERRORS ABOVE, ${green}the old PHP-CGI binary '$APP_ROOT/php-cgi-custom' within your Desktop Edition should have just been replaced with a new custom PHP-CGI binary, which should be compatible with your particular Linux system. Additionally, we made sure 'GTK2' and 'libxss' for your particular Linux system were installed, which helps assure compatibility with the 32-bit binary 'RUN_CRYPTO_TRACKER'.${reset}"
echo " "
echo "${yellow}PLEASE REPORT ANY ISSUES HERE: $ISSUES_URL${reset}"
echo " "


######################################


echo "${yellow} "
read -n1 -s -r -p $"ONE LAST THING: PRESS D to delete the temporary CUSTOM PHP source / binaries we created at $HOME/php-source AND $HOME/php-binaries, (OR PRESS K if you prefer to keep them [we don't need them to run the app])..." key
echo " "
echo "${reset} "

    if [ "$key" = 'd' ] || [ "$key" = 'D' ]; then
    echo " "
    echo "${cyan}Deleting CUSTOM PHP source / binaries, please wait...${reset}"
    echo " "
    
    rm -rf $HOME/php-binaries > /dev/null 2>&1
    rm -rf $HOME/php-source > /dev/null 2>&1
    
    echo "${green}CUSTOM PHP source / binaries were deleted, now exiting this script...${reset}"
    echo " "

    else
    
    echo " "
    echo "${green}Skipping deletion of CUSTOM PHP source / binaries, now exiting this script...${reset}"
    echo " "
    
    fi


sleep 3

exit

echo " "



