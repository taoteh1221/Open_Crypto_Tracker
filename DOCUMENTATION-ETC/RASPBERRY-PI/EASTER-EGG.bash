#!/bin/bash


COPYRIGHT_YEARS="2022-2025"

# Version of this script
APP_VERSION="1.12.1" # 2025/FEBRUARY/23RD


########################################################################################################################
########################################################################################################################

# Copyright 2022-2025 GPLv3, Bluetooth Internet Radio By Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)

# https://github.com/taoteh1221/Bluetooth_Internet_Radio

# Fully automated setup of bluetooth, internet radio player (PyRadio), local music files player (mplayer), on a headless RaspberryPi,
# connecting to a stereo system's bluetooth receiver (bash script, chmod +x it to run).

# To install automatically on Ubuntu / RaspberryPi OS / Armbian, copy => paste => run the command below in a
# terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE
# USER THAT WILL RUN THE APP (user must have sudo privileges):

# wget --no-cache -O bt-radio-setup.bash https://tinyurl.com/bt-radio-setup;chmod +x bt-radio-setup.bash;./bt-radio-setup.bash

# AFTER installation, ~/radio is installed as a shortcut command pointing to this script,
# and paired bluetooth reconnects (if disconnected) when you start a new terminal session. 

# A command line parameter can be passed to auto-select menu choices. Multi sub-option selecting is available too,
# by seperating each sub-option with a space, AND ecapsulating everything in quotes like "option1 sub-option2 sub-sub-option3".

# Running normally (displays options to choose from):

# ~/radio
 
# Auto-selecting single / multi option examples (MULTI OPTIONS #MUST# BE IN QUOTES!):
 
# ~/radio "1 y"
# ~/radio "upgrade y"
# (checks for / confirms script upgrade)
 
# ~/radio "7 1 b3"
# ~/radio "internet 1 b3"
# (plays default INTERNET playlist in background, 3rd station)
# ~/radio "internet 1 b3vlc"
# (plays default INTERNET playlist in background, 3rd station, RESET default player to: vlc)
 
# ~/radio "9 bsr"
# ~/radio "local bsr"
# (rescans music files / plays LOCAL music folder ~/Music/MPlayer [RECURSIVELY] in background, shuffling)
 
# ~/radio 10
# ~/radio off
# (stops audio playback)
 
# ~/radio "12 XX:XX:XX:XX:XX:XX"
# ~/radio "connect XX:XX:XX:XX:XX:XX"
# (connect bluetooth device by mac address)
 
# ~/radio "13 XX:XX:XX:XX:XX:XX"
# ~/radio "remove XX:XX:XX:XX:XX:XX"
# (remove bluetooth device by mac address)
 
# ~/radio "14 3"
# ~/radio "devices paired"
# (shows paired bluetooth devices)

########################################################################################################################
########################################################################################################################


# If parameters are added via command line
# (CLEANEST WAY TO RUN PARAMETER INPUT #TO AUTO-SELECT MULTIPLE CONSECUTIVE OPTION MENUS#)
# (WE CAN PASS THEM #IN QUOTES# AS: command "option1 sub-option2 sub-sub-option3")
if [ "$1" != "" ] && [ "$APP_RECURSE" != "1" ]; then

# Flag recursion and export it
APP_RECURSE=1
export APP_RECURSE=$APP_RECURSE

# Convert any human-readable params to their numeric counterpart(s)
convert="$1"

# Multi-options MUST be converted FIRST
# (helps avoid mis-converting PRIMARY options [if we add a lot in the future])

# devices internal
convert=$(echo "$convert" | sed -r "s/devices internal/14 1/g")

# devices available
convert=$(echo "$convert" | sed -r "s/devices available/14 2/g")

# devices paired
convert=$(echo "$convert" | sed -r "s/devices paired/14 3/g")

# devices trusted
convert=$(echo "$convert" | sed -r "s/devices trusted/14 4/g")

# upgrade
convert=$(echo "$convert" | sed -r "s/upgrade/1/g")

# internet
convert=$(echo "$convert" | sed -r "s/internet/7/g")

# local
convert=$(echo "$convert" | sed -r "s/local/9/g")

# off
convert=$(echo "$convert" | sed -r "s/off/10/g")

# stop (backwards compatibility)
convert=$(echo "$convert" | sed -r "s/stop/10/g")

# connect
convert=$(echo "$convert" | sed -r "s/connect/12/g")

# remove
convert=$(echo "$convert" | sed -r "s/remove/13/g")


     if [ ! -f ~/radio ]; then
     echo " "
     echo "Setting up a few things first, PLEASE RUN WITH YOUR CLI PARAMETERS *AGAIN AFTER* THIS SETUP COMPLETES..."
     echo " "
     else
     # Pipe it through
     printf "%s\n" $convert | ~/radio
     exit
     fi


fi


######################################


ISSUES_URL="https://github.com/taoteh1221/Bluetooth_Internet_Radio/issues"

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


# Get the *INTERNAL* NETWORK ip address
IP=$(ip -o route get to 8.8.8.8 | sed -n 's/.*src \([0-9.]\+\).*/\1/p')


######################################


# Are we running on an ARM-based CPU?
if [ -f "/etc/debian_version" ]; then
IS_ARM=$(dpkg --print-architecture | grep -i "arm")
elif [ -f "/etc/redhat-release" ]; then
IS_ARM=$(uname -r | grep -i "aarch64")
fi


######################################


# Get date / time
DATE=$(date '+%Y-%m-%d')
TIME=$(date '+%H:%M:%S')

# Current timestamp
CURRENT_TIMESTAMP=$(date +%s)

# Are we running on Ubuntu OS?
IS_UBUNTU=$(cat /etc/os-release | grep -i "ubuntu")


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


# Quit if ACTUAL USERNAME is root
if [ "$TERMINAL_USERNAME" == "root" ]; then 
 echo " "
 echo "${red}Please run as a NORMAL USER WITH 'sudo' PERMISSIONS (NOT LOGGED IN AS 'root').${reset}"
 echo " "
 echo "${cyan}Exiting...${reset}"
 echo " "
 exit
fi


######################################


# Find out what display manager is being used on the PHYSICAL display
DISPLAY_SESSION=$(loginctl show-user "$TERMINAL_USERNAME" -p Display --value)
DISPLAY_SESSION=$(echo "${DISPLAY_SESSION}" | xargs) # trim whitespace

# Are we using x11 display manager?
RUNNING_X11=$(loginctl show-session "$DISPLAY_SESSION" -p Type | grep -i x11)

# Are we using wayland display manager?
RUNNING_WAYLAND=$(loginctl show-session "$DISPLAY_SESSION" -p Type | grep -i wayland)


# Are we running a wayland compositor?
if [ "$RUNNING_WAYLAND" != "" ]; then

# Are we using wayfire compositor?
RUNNING_WAYFIRE=$(ps aux | grep wayfire | grep -v grep) # EXCLUDE THE WORD GREP!
	   
# Are we using labwc compositor?
RUNNING_LABWC=$(ps aux | grep labwc | grep -v grep) # EXCLUDE THE WORD GREP!

elif [ "RUNNING_X11" != "" ]; then

     # Are we using lightdm, as the display manager?
     if [ -f "/etc/debian_version" ]; then
     LIGHTDM_DISPLAY=$(cat /etc/X11/default-display-manager | grep "lightdm")
     elif [ -f "/etc/redhat-release" ]; then
     LIGHTDM_DISPLAY=$(ls -al /etc/systemd/system/display-manager.service | grep "lightdm")
     fi

fi


if [ -f "/etc/debian_version" ]; then

echo "${green}Your system has been detected as Debian-based, which is compatible with this automated script."

# USE 'apt-get' IN SCRIPTING!
# https://askubuntu.com/questions/990823/apt-gives-unstable-cli-interface-warning
PACKAGE_INSTALL="sudo apt-get install"
PACKAGE_REMOVE="sudo apt-get --purge remove"

echo " "
echo "Continuing...${reset}"
echo " "

elif [ -f "/etc/redhat-release" ]; then

echo "${yellow}Your system has been detected as Redhat-based, which is ${red}CURRENTLY STILL IN DEVELOPMENT TO EVENTUALLY BE (BUT IS *NOT* YET) ${yellow} fully compatible with this automated script."

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


######################################


# Ubuntu uses snaps for very basic libraries these days, so we need to configure for possible snap installs
if [ "$IS_UBUNTU" != "" ]; then

sudo apt install snapd -y

sleep 3
          
UBUNTU_SNAP_INSTALL="sudo snap install"

fi


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

     echo "*PLEASE* REPORT THIS ISSUE HERE, *IF THIS SCRIPT OR THE INSTALLED APP FAILS TO RUN PROPERLY FROM THIS POINT ONWARD*:" > /dev/tty
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
          
          if [ -f "/etc/debian_version" ]; then
          
          
               # bsdtar on Ubuntu 18.x and higher
               if [ "$1" == "bsdtar" ]; then
               SYS_PACK="libarchive-tools"
               
               # xdg-user-dir (package name differs)
               elif [ "$1" == "xdg-user-dir" ]; then
               SYS_PACK="xdg-user-dirs"
     
               # rsyslogd (package name differs)
               elif [ "$1" == "rsyslogd" ]; then
               SYS_PACK="rsyslog"
     
               # snap (package name differs)
               elif [ "$1" == "snap" ]; then
               SYS_PACK="snapd"
     
               # xorg (package name differs)
               elif [ "$1" == "xorg" ]; then
               SYS_PACK="xserver-xorg"
     
               # chromium-browser (package name differs)
               elif [ "$1" == "chromium-browser" ]; then
               SYS_PACK="chromium"
     
               # epiphany-browser (package name differs)
               elif [ "$1" == "epiphany-browser" ]; then
               SYS_PACK="epiphany"
     
               else
               SYS_PACK="$1"
               fi
          
          
          elif [ -f "/etc/redhat-release" ]; then
          
          
               if [ "$1" == "xdg-user-dir" ]; then
               SYS_PACK="xdg-user-dirs"
     
               # rsyslogd (package name differs)
               elif [ "$1" == "rsyslogd" ]; then
               SYS_PACK="rsyslog"
     
               # xorg (package name differs)
               elif [ "$1" == "xorg" ]; then
               SYS_PACK="gnome-session-xsession"
     
               # chromium-browser (package name differs)
               elif [ "$1" == "chromium-browser" ]; then
               SYS_PACK="chromium"
     
               # epiphany-browser (package name differs)
               elif [ "$1" == "epiphany-browser" ]; then
               SYS_PACK="epiphany"
     
               # avahi-daemon (package name differs)
               elif [ "$1" == "avahi-daemon" ]; then
               SYS_PACK="avahi"
     
               else
               SYS_PACK="$1"
               fi
               
               
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
          if [ "$IS_UBUNTU" != "" ] && [ $SYS_PACK != "snapd" ]; then
          
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


######################################


# Make sure automatic suspend / sleep is disabled
if [ ! -f "${HOME}/.sleep_disabled.dat" ]; then

echo "${red}We need to make sure your system will NOT AUTO SUSPEND / SLEEP, or your app server could stop running.${reset}"

echo "${yellow} "
read -n1 -s -r -p $"PRESS F to fix this (disables auto suspend / sleep), OR any other key to skip fixing..." key
echo "${reset} "

    if [ "$key" = 'f' ] || [ "$key" = 'F' ]; then

    echo " "
    echo "${cyan}Disabling auto suspend / sleep...${reset}"
    echo " "
    
    echo -e "ran" > ${HOME}/.sleep_disabled.dat
    
         if [ -f "/etc/debian_version" ]; then
         sudo systemctl mask sleep.target suspend.target hibernate.target hybrid-sleep.target > /dev/null 2>&1
         elif [ -f "/etc/redhat-release" ]; then
         sudo -u gdm dbus-run-session gsettings set org.gnome.settings-daemon.plugins.power sleep-inactive-ac-timeout 0 > /dev/null 2>&1
         fi
	   
    else

    echo " "
    echo "${green}Skipping...${reset}"
    echo " "
    
    fi

fi


######################################


# ON ARM REDHAT-BASED SYSTEMS ONLY:
# Do we have kernel updates disabled?
if [ -f "/etc/redhat-release" ]; then

# Are we auto-selecting the NEWEST kernel, to boot by default in grub?
KERNEL_BOOTED_UPDATES=$(sudo sed -n '/UPDATEDEFAULT=yes/p' /etc/sysconfig/kernel)


     if [ "$IS_ARM" != "" ] && [ "$KERNEL_BOOTED_UPDATES" != "" ]; then
     
     echo "${red}Your ARM-based device is CURRENTLY setup to UPDATE the grub bootloader to boot from THE LATEST KERNEL. THIS MAY CAUSE SOME ARM-BASED DEVICES TO NOT BOOT (without MANUALLY selecting a different kernel at boot time).${reset}"
     
     echo "${yellow} "
     read -n1 -s -r -p $"PRESS F to fix this (disable grub auto-selecting NEW kernels to boot), OR any other key to skip fixing..." key
     echo "${reset} "
     
         if [ "$key" = 'f' ] || [ "$key" = 'F' ]; then
     
         echo " "
         echo "${cyan}Disabling grub auto-selecting NEW kernels to boot...${reset}"
         echo " "
         
         sudo sed -i 's/UPDATEDEFAULT=.*/UPDATEDEFAULT=no/g' /etc/sysconfig/kernel > /dev/null 2>&1
     
         echo "${red} "
         read -n1 -s -r -p $"Press ANY KEY to REBOOT (to assure this update takes effect)..." key
         echo "${reset} "
                  
                  
                 if [ "$key" = 'y' ] || [ "$key" != 'y' ]; then
                      
                 echo " "
                 echo "${green}Rebooting...${reset}"
                 echo " "
                      
                 sudo shutdown -r now
                      
                 exit
                      
                 fi
                  
                  
         echo " "
          
         else
     
         echo " "
         echo "${green}Skipping...${reset}"
         echo " "
         
         fi
     
     
     fi


fi
              

######################################


# Armbian freeze kernel updates
if [ -f "/usr/bin/armbian-config" ] && [ ! -f "${HOME}/.armbian_kernel_alert.dat" ]; then
echo "${red}YOU MAY NEED TO *DISABLE* KERNEL UPDATES ON YOUR ARMBIAN DEVICE (IF YOU HAVE NOT ALREADY), SO YOUR DEVICE ALWAYS BOOTS UP PROPERLY."
echo " "
echo "${green}Run this command, and then choose 'System > Updates > Disable Armbian firmware upgrades':"
echo " "
echo "sudo armbian-config${reset}"
echo " "
echo "${red}This will assure you always use a kernel compatible with your device."
echo " "

echo "${yellow} "
read -n1 -s -r -p $"PRESS F to run armbian-config and fix this NOW, OR any other key to skip fixing..." key
echo "${reset} "

    if [ "$key" = 'f' ] || [ "$key" = 'F' ]; then

    sudo armbian-config
    
    sleep 1

    echo " "
    echo "${cyan}Resuming auto-installer..."
    echo " "
    echo "${red}DON'T FORGET TO REBOOT BEFORE ALLOWING ANY SYSTEM UPGRADES!${reset}"
    echo " "
	   
    else

    echo " "
    echo "${green}Skipping...${reset}"
    echo " "
    
    fi


echo -e "ran" > ${HOME}/.armbian_kernel_alert.dat

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
     echo "${red}(You can SEVERELY MESS UP a \"Rolling Release\" Operating System IF YOU DO NOT CHOOSE CORRECTLY HERE! In that case, you can SAFELY choose \"I don't know\".)${reset}"
     echo " "
     
     
          if [ ! -f /usr/bin/raspi-config ] && [ "$IS_ARM" != "" ]; then
          
          echo "${red}(Your ARM-based device MAY NOT BOOT IF YOU RUN SYSTEM UPGRADES [if you have NOT freezed kernel firmware updating / rebooted FIRST]. To play it safe, you can SAFELY choose \"NOT Raspberry Pi OS Software\", OR \"I don't know\")${reset}"
          echo " "
     
          echo "Enter the NUMBER next to your chosen option.${reset}"
     
          echo " "
          
          OPTIONS="rolling long_term i_dont_know not_raspberrypi_os_software"
          
          else
     
          echo "Enter the NUMBER next to your chosen option.${reset}"
     
          echo " "
          
          OPTIONS="rolling long_term i_dont_know"
          
          fi
     
          
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


# Dependencies SPECIFICALLY for this bluetooth internet radio script...

# pulseaudio's FULL PATH (to run checks later)
PULSEAUDIO_PATH=$(get_app_path "pulseaudio")

# python3's FULL PATH (we DONT want python [which is python2])
PYTHON_PATH=$(get_app_path "python3")

# Install xdg-user-dirs if needed
XDGUSER_PATH=$(get_app_path "xdg-user-dir")

# Install rsyslogd if needed
SYSLOG_PATH=$(get_app_path "rsyslogd")


######################################


###############################################################################################
# Primary init complete, now check bt_autoconnect_install and symbolic link status
###############################################################################################


# bluetooth-autoconnect's FULL PATH (to run checks OR install later)
BT_AUTOCONNECT_PATH="${SCRIPT_PATH}/bluetooth-autoconnect.py"

# bt_autoconnect_install function START
bt_autoconnect_install () {

    # Install bluetooth-autoconnect.py if needed (AND we are #NOT# running as sudo)
    if [ ! -f "$BT_AUTOCONNECT_PATH" ] && [ "$EUID" != 0 ]; then

    # Clears / updates cache, then upgrades (if NOT a rolling release)
    clean_system_update
    
    echo " "
    echo "${cyan}Installing required component bluetooth-autoconnect and dependencies, please wait...${reset}"
    echo " "
    
    # Install python3 prctl
    $PACKAGE_INSTALL python3-prctl -y
    
    # Install python3 dbus modules
    $PACKAGE_INSTALL python3-dbus python3-slip-dbus python3-pydbus -y
    
            
    # SPECIFILLY NAME IT WITH -O, TO OVERWRITE ANY PREVIOUS COPY...ALSO --no-cache TO ALWAYS GET LATEST COPY
    wget --no-cache -O TEMP-BT-AUTO-CONN.py https://raw.githubusercontent.com/taoteh1221/Bluetooth_Internet_Radio/main/bluetooth-autoconnect/bluetooth-autoconnect.py
    
    sleep 2
    
    mv -v --force TEMP-BT-AUTO-CONN.py "$BT_AUTOCONNECT_PATH"
    
    sleep 2
    
        
        # bluetooth-autoconnect systemd service start at boot
        if [ -d "/lib/systemd/system" ] && [ ! -f $HOME/.local/share/systemd/user/btautoconnect.service ]; then
        
        echo " "
        echo "${cyan}Installing bluetooth-autoconnect as a systemd service, please wait...${reset}"
        echo " "


# Don't nest / indent, or it could malform the settings            
read -r -d '' BT_AUTOCONNECT_STARTUP <<- EOF
\r
[Unit]
Description=Bluetooth autoconnect
After=pulseaudio.service
\r
[Service]
Type=simple
\r
ExecStart=python3 "$BT_AUTOCONNECT_PATH"
[Install]
WantedBy=pulseaudio.service
\r
EOF

        # Setup service to run at login
        # https://superuser.com/questions/1037466/how-to-start-a-systemd-service-after-user-login-and-stop-it-before-user-logout
        
        mkdir -p $HOME/.local/share/systemd/user
        
        sleep 3
        					
        echo -e "$BT_AUTOCONNECT_STARTUP" > $HOME/.local/share/systemd/user/btautoconnect.service
        
        sleep 3
        					
        systemctl --user enable btautoconnect.service
        
        echo " "
        echo "${cyan}bluetooth-autoconnect systemd service is setup, and will run on terminal login.${reset}"
        echo " "
        					
        fi	   
    
    
    fi
        


    # Run bluetooth-autoconnect.py (IF we are #NOT# running as sudo, AND no systemd startup service is installed)
    if [ -f "$BT_AUTOCONNECT_PATH" ] && [ "$EUID" != 0 ] && [ ! -f $HOME/.local/share/systemd/user/btautoconnect.service ]; then
    python3 "$BT_AUTOCONNECT_PATH"
    fi

}
# bt_autoconnect_install function END


# Call bt_autoconnect_install function
bt_autoconnect_install


# bt_autoconnect_check function
bt_autoconnect_check () {
        
# Make sure we are connected to the bluetooth receiver (NOT just paired)
# (SOME DEVICES MAY DISCONNECT AGAIN IF WHEN YOU LOGIN, YOU DON'T #QUICKLY# START A SOUND / RADIO STREAM)
CONNECT_STATUS=$(python3 "$BT_AUTOCONNECT_PATH")
        
     if [ -n "$CONNECT_STATUS" ]; then
     echo " "
     echo "$CONNECT_STATUS"
     echo " "
     fi
            
}


if [ ! -f ~/radio ]; then 

ln -s "$SCRIPT_LOCATION" ~/radio

echo " "
echo "${red}IMPORTANT INFORMATION:"
echo " "
echo "~/radio command is now a shortcut for ./$SCRIPT_NAME"
echo " "

echo "IF YOU MOVE $SCRIPT_LOCATION TO A NEW LOCATION, #OR RENAME IT#,"
echo "you'll have to delete ~/radio and THIS SCRIPT WILL RE-CREATE THIS SHORTCUT.${reset}"
echo " "

else
echo " "
echo "${cyan}PRO TIPS:"
echo " "
echo "Shortcut to this script: ${green}~/radio${cyan}"
echo " "
echo "Paired bluetooth reconnects (if disconnected) when you start a terminal session"
echo " "
echo "Running normally (displays options to choose from):"
echo " "
echo "${green}~/radio${cyan}"
echo " "
echo "Auto-selecting single / multi option examples ${red}(MULTI OPTIONS #MUST# BE IN QUOTES!)${cyan}:"
echo " "
echo "${green}~/radio \"1 y\""
echo "${green}~/radio \"upgrade y\"${cyan}"
echo "(checks for / confirms script upgrade)"
echo " "
echo "${green}~/radio \"7 1 b3\""
echo "${green}~/radio \"internet 1 b3\"${cyan}"
echo "(plays default INTERNET playlist in background, 3rd station)"
echo "${green}~/radio \"internet 1 b3vlc\"${cyan}"
echo "(plays default INTERNET playlist in background, 3rd station, RESET default player to: vlc)"
echo " "
echo "${green}~/radio \"9 bsr\""
echo "${green}~/radio \"local bsr\"${cyan}"
echo "(rescans music files / plays LOCAL music folder ~/Music/MPlayer [RECURSIVELY] in background, shuffling)"
echo " "
echo "${green}~/radio 10"
echo "${green}~/radio off${cyan}"
echo "(stops audio playback)"
echo " "
echo "${green}~/radio \"12 XX:XX:XX:XX:XX:XX\""
echo "${green}~/radio \"connect XX:XX:XX:XX:XX:XX\"${cyan}"
echo "(connect bluetooth device by mac address)"
echo " "
echo "${green}~/radio \"13 XX:XX:XX:XX:XX:XX\""
echo "${green}~/radio \"remove XX:XX:XX:XX:XX:XX\"${cyan}"
echo "(remove bluetooth device by mac address)"
echo " "
echo "${green}~/radio \"14 3\""
echo "${green}~/radio \"devices paired\"${cyan}"
echo "(shows paired bluetooth devices)"
echo "${reset} "
fi


###############################################################################################
# Secondary init / checks complete, now run main app logic
###############################################################################################


if [ -f ~/.config/radio.alsamixer.state ]; then

echo " "
echo "${cyan}Loading customized alsamixer settings from: ~/.config/radio.alsamixer.state ${reset}"
echo " "

# RELIABLY persist volume / other alsamixer setting changes
# https://askubuntu.com/questions/50067/how-to-save-alsamixer-settings
alsactl --file ~/.config/radio.alsamixer.state restore

fi

echo " "
echo "${yellow}Enter the NUMBER next to your chosen option:${reset}"
echo " "

OPTIONS="upgrade_check pulseaudio_install pulseaudio_fix pulseaudio_status internet_player_install internet_player_fix internet_player_on local_player_install local_player_on any_player_off bluetooth_scan bluetooth_connect bluetooth_remove bluetooth_devices bluetooth_status sound_test volume_adjust troubleshoot syslog_logs journal_logs restart_computer exit_app other_apps about_this_app"


# start options
select opt in $OPTIONS; do

        
        ##################################################################################################################
        ##################################################################################################################
        
        if [ "$opt" = "upgrade_check" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
       
        # Check for newer version
        API_VERSION_DATA=$(curl -s 'https://api.github.com/repos/taoteh1221/Bluetooth_Internet_Radio/releases/latest')
        
        LATEST_VERSION=$(echo "$API_VERSION_DATA" | jq -r '.tag_name')
        
        APP_MAJOR_MINOR=$(echo "${APP_VERSION%.*}" | xargs) #X.XX trim whitespace
        APP_BUG_FIXES=$(echo "${APP_VERSION##*.}" | xargs) #X trim whitespace
        
        LATEST_MAJOR_MINOR=$(echo "${LATEST_VERSION%.*}" | xargs) #X.XX trim whitespace
        LATEST_BUG_FIXES=$(echo "${LATEST_VERSION##*.}" | xargs) #X trim whitespace
        
        
            if ( [ $(echo "$LATEST_MAJOR_MINOR > $APP_MAJOR_MINOR" |bc -l) -eq 1 ] ) || ( [ $(echo "$LATEST_MAJOR_MINOR == $APP_MAJOR_MINOR" |bc -l) -eq 1 ] && [ $(echo "$LATEST_BUG_FIXES > $APP_BUG_FIXES" |bc -l) -eq 1 ] ); then 
            
            # Remove any sourceforge link in the description, with sed
            UPGRADE_DESC=$(echo "$API_VERSION_DATA" | jq -r '.body' | sed 's/\[.*//g')
            
            echo " "
            echo "${red}An upgrade is available to v${LATEST_VERSION} (you are running v${APP_VERSION})${reset}"
            echo " "
            echo "${cyan}Upgrade Description:"
            echo " "
            echo "$UPGRADE_DESC"
            echo "${reset} "
            echo "${yellow}Do you want to upgrade to v${LATEST_VERSION} now?${reset}"
            
            echo "${yellow} "
            read -n1 -s -r -p $"Press Y to upgrade (or press N to cancel)..." keystroke
            echo "${reset} "
                    
                    
                if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
                      
                echo " "
                echo "${cyan}Initiating upgrade, please wait...${reset}"
                echo " "
                				
                sleep 3
                
                UPGRADE_FILE="https://raw.githubusercontent.com/taoteh1221/Bluetooth_Internet_Radio/${LATEST_VERSION}/bt-radio-setup.bash"
                
                wget --no-cache -O BT-TEMP.bash $UPGRADE_FILE
                
                sleep 3
                
                FILE_SIZE=$(stat -c%s BT-TEMP.bash)
                
                    # If we got back a file greater than 0 bytes (NOT a 404 error)
                    if [ $FILE_SIZE -gt 0 ]; then
                
                    # Remove system link, to reset automatically after upgrade (in case script location changed)
                    rm ~/radio > /dev/null 2>&1
                    
                    mv -v --force BT-TEMP.bash "$SCRIPT_LOCATION"
                    
                    sleep 3
                
                    chmod +x "$SCRIPT_LOCATION"
                    				
                    sleep 1
                    				
                    INSTALL_LOCATION="${SCRIPT_LOCATION}"
                    				
                    # Re-create system link, with latest script location
                    ln -s $INSTALL_LOCATION ~/radio
                    				
                    echo " "
                    echo "${green}Upgrade has completed.${reset}"
                    echo " "
                    echo "${red}Please re-run this script, since we just completed an upgrade to it."
                    echo " "
                    echo "Exiting..."
                    echo "${reset} "
                    exit
                    
                    else
                    echo " "
                    echo "${red}Upgrade download failed, please try again.${reset}"
                    echo " "
                    fi
                
                else
                echo " "
                echo "${green}Upgrade has been cancelled.${reset}"
                echo " "
                fi
            
            else
            echo " "
            echo "${green}You are already running the latest version.${reset}"
            echo " "
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pulseaudio_install" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" -ne 0 ] || [ "$TERMINAL_USERNAME" == "root" ]; then 
             echo "${red}Please run #WITH# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        echo "${red} "
        echo "NOTICE: YOUR COMPUTER WILL REBOOT AFTER CONFIGURATION OF THIS COMPONENT!"
        echo " "
        read -n1 -s -r -p $"Press Y to continue (or press N to exit)..." key
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
        				
        sleep 3
        
        echo " "
        

				if [ -f /boot/dietpi/.version ]; then
				
                echo " "
                echo "${red}We must ENABLE BLUETOOTH in DietPi OS before continuing (#IF# YOU HAVEN'T ALREADY).${reset}"
                echo " "
                echo "This script will now launch dietpi-config."
                echo " "
                echo "In ADVANCED OPTIONS, you will need to ENABLE BLUETOOTH."
                echo " "
                echo "You #CAN# SAFELY REBOOT if asked to, and RUN THE PULSEAUDIO INSTALL OPTION #AGAIN# AFTERWARDS."
                
                echo "${yellow} "
                read -n1 -s -r -p $'Press Y to run dietpi-config (or #IF# YOU DID THIS ALREADY press N to skip)...\n' keystroke
                echo "${reset} "
        
                    if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
                
    				echo " "
    				echo "${cyan}Initiating dietpi-config, please wait...${reset}"
                    sleep 3
    				dietpi-config
                
                    else
                    
                    echo "${green}dietpi-config bluetooth enabling routine has been skipped.${reset}"
                    echo " "
                
                    fi
				
				fi
        

                # DISABLE IF ALREADY RUNNING AS A SYSTEM SERVICE,
                # AS IT'S A #HUGE NIGHTMARE# TRYING TO USE BLUETOOTH THAT WAY RELIABLY!!
				if [ -f /lib/systemd/system/pulseaudio.service ]; then
        		
        		# Stop / remove any existing instance of pulseaudio.service FROM EARLY VERSIONS OF THIS SCRIPT!
        		# (so we are allowed to remove /lib/systemd/system/pulseaudio.service)
        		systemctl stop pulseaudio.service
        		
        		sleep 5
        		
        		rm /lib/systemd/system/pulseaudio.service > /dev/null 2>&1
        		
        		sleep 2
        		
        		# reload services, to complete purging the old pulseaudio service setup
        		systemctl daemon-reload
                
                sleep 2
				
				fi
        				
        echo " "
        
        echo "${green}Installing pulseaudio and other required components, please wait...${reset}"
        echo " "
        
        # needed components
        $PACKAGE_INSTALL alsa-utils -y
        
        $PACKAGE_INSTALL pulseaudio* -y
        
        sleep 5
        
        usermod -a -G lp $TERMINAL_USERNAME
        
        usermod -a -G pulse-access $TERMINAL_USERNAME
        
        usermod -a -G bluetooth $TERMINAL_USERNAME

        echo " "
        echo "${cyan}Now making sure /etc/pulse/default.pa has bluetooth modules, please wait...${reset}"
        echo " "
		
        PULSE_BT_POLICY=$(sed -n '/module-bluetooth-policy/p' /etc/pulse/default.pa)
        PULSE_BT_DISCOVER=$(sed -n '/module-bluetooth-discover/p' /etc/pulse/default.pa)
        PULSE_BT_CONNECT=$(sed -n '/module-switch-on-connect/p' /etc/pulse/default.pa)
        
            if [ "$PULSE_BT_POLICY" == "" ]; then 
            echo "${red}No bluetooth policy module loaded in pulseaudio, adding it now, please wait...${reset}"
            echo " "
            sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".ifexists module-bluetooth-policy.so" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo "load-module module-bluetooth-policy" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
            sleep 1
            fi        
        
            if [ "$PULSE_BT_DISCOVER" == "" ]; then 
            echo "${red}No bluetooth discover module loaded in pulseaudio, adding it now, please wait...${reset}"
            echo " "
            sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".ifexists module-bluetooth-discover.so" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo "load-module module-bluetooth-discover" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
            sleep 1
            fi        
        
            if [ "$PULSE_BT_CONNECT" == "" ]; then 
            echo "${red}No switch on connect module loaded in pulseaudio, adding it now, please wait...${reset}"
            echo " "
            sudo bash -c 'echo "### REQUIRED FOR AUTO-CONNECT NEW DEVICES!" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".ifexists module-switch-on-connect.so" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo "load-module module-switch-on-connect" >> /etc/pulse/default.pa'
            sleep 1
            sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
            sleep 1
            fi        
        
        echo " "
        echo "${green}pulseaudio installation complete.${reset}"
        echo " "
		
		echo " "
		echo "${red}Rebooting your system, please wait, and log back in afterwards...${reset}"
		echo " "
		
		sleep 5
		
		reboot
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pulseaudio_fix" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        echo "${red} "
        echo "NOTICE: YOUR COMPUTER WILL REBOOT AFTER CONFIGURATION OF THIS COMPONENT!"
        echo " "
        read -n1 -s -r -p $"Press Y to continue (or press N to exit)..." key
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
        
            
            # If 'pulseaudio' was found, run the fix
            if [ -f "$PULSEAUDIO_PATH" ]; then
                    
            # Remove any user configs (sometimes pulseaudio bluetooth is fixed doing this)
            rm -r ~/.config/pulse.old > /dev/null 2>&1
            mv ~/.config/pulse/ ~/.config/pulse.old-$DATE > /dev/null 2>&1
            
    		# Stop / remove any existing bluetooth-autoconnect service
    		# (so we can trigger re-install afterwards, to get any updated configs in latest script)
    		# (also allows us to remove /lib/systemd/system/pulseaudio.service afterwards)
    		systemctl --user stop btautoconnect.service
    		
    		sleep 5
    		
    		rm $HOME/.local/share/systemd/user/btautoconnect.service > /dev/null 2>&1
    		
    		rm "$BT_AUTOCONNECT_PATH"
    		
    		sleep 2
    		
    		# reload services
    		systemctl --user daemon-reload
    		
    		sleep 2
    		
            # Call bt_autoconnect_install function (this will re-initialize it, since we removed it)
            bt_autoconnect_install
                    
            echo "${green}Attempted USER FILES fixes completed (old configs at ~/.config/pulse.old-$DATE, btautoconnect.service re-initialized).${reset}"
            echo " "

            echo "${cyan}Now checking /etc/pulse/default.pa for missing bluetooth modules, please wait...${reset}"
            echo " "
		
            PULSE_BT_POLICY=$(sudo sed -n '/module-bluetooth-policy/p' /etc/pulse/default.pa)
            PULSE_BT_DISCOVER=$(sudo sed -n '/module-bluetooth-discover/p' /etc/pulse/default.pa)
            PULSE_BT_CONNECT=$(sed -n '/module-switch-on-connect/p' /etc/pulse/default.pa)
            
            
                if [ "$PULSE_BT_POLICY" == "" ]; then 
                echo "${red}No bluetooth policy module loaded in pulseaudio, fixing, please wait...${reset}"
                echo " "
                sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".ifexists module-bluetooth-policy.so" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo "load-module module-bluetooth-policy" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
                sleep 1
                NO_CONFIG_ISSUE=0
                else
                NO_CONFIG_ISSUE=1
                fi        
            
                if [ "$PULSE_BT_DISCOVER" == "" ]; then 
                echo "${red}No bluetooth discover module loaded in pulseaudio, fixing, please wait...${reset}"
                echo " "
                sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".ifexists module-bluetooth-discover.so" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo "load-module module-bluetooth-discover" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
                sleep 1
                NO_CONFIG_ISSUE=0
                else
                NO_CONFIG_ISSUE=1
                fi         
            
                if [ "$PULSE_BT_CONNECT" == "" ]; then 
                echo "${red}No switch on connect module loaded in pulseaudio, fixing, please wait...${reset}"
                echo " "
                sudo bash -c 'echo "### REQUIRED FOR AUTO-CONNECT NEW DEVICES!" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".ifexists module-switch-on-connect.so" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo "load-module module-switch-on-connect" >> /etc/pulse/default.pa'
                sleep 1
                sudo bash -c 'echo ".endif" >> /etc/pulse/default.pa'
                sleep 1
                NO_CONFIG_ISSUE=0
                else
                NO_CONFIG_ISSUE=1
                fi         
            
                if [ "$NO_CONFIG_ISSUE" == "1" ]; then 
                echo "${green}No known pulseaudio DEFAULT configuration issues detected.${reset}"
                echo " "
                sleep 2
                fi     
        
        
            echo " "
            echo "${green}All pulseaudio attempted fixes complete.${reset}"
            echo " "   
		
    		echo " "
    		echo "${red}Rebooting your system, please wait, and log back in afterwards...${reset}"
    		echo " "
    		
    		sleep 5
    		
    		sudo reboot
            
            else
            
            echo "pulseaudio not found, must be installed first, please re-run this script and choose that option."
            echo " "
                    
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pulseaudio_status" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
            
            # If 'pulseaudio' was found, start it
            if [ -f "$PULSEAUDIO_PATH" ]; then
                    
            echo "${yellow}PulseAudio status: ${red}(HOLD Ctrl+C KEYS DOWN TO EXIT)${yellow}:"
            echo "${reset} "
            systemctl --user status pulseaudio.service
            exit
            
            else
            
            echo "PulseAudio not found, must be installed first, please re-run this script and choose that option."
            echo " "
                    
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "internet_player_install" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS."
             echo " "
             echo "(some components for pyradio are installed as a regular user)${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        echo "${red} "
        echo "NOTICE: YOUR COMPUTER WILL REBOOT AFTER CONFIGURATION OF THIS COMPONENT!"
        echo " "
        read -n1 -s -r -p $"Press Y to continue (or press N to exit)..." key
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
        
        # https://github.com/coderholic/pyradio/blob/master/build.md

        # Clears / updates cache, then upgrades (if NOT a rolling release)
        clean_system_update
        
        echo " "
        echo "${green}Installing pyradio and required components, please wait...${reset}"
        echo " "
        
        sleep 1
        
        # Install screen and mpv instead of mplayer, it's more stable
        $PACKAGE_INSTALL screen mpv -y
        
        sleep 1
        
        # mplayer as backup if distro doesn't have an mpv package (mpv will be used first automatically if found)
        $PACKAGE_INSTALL mplayer -y
        
        sleep 1
        
        # vlc as backup if distro doesn't have an mpv or mplayer package
        $PACKAGE_INSTALL vlc -y
        
        sleep 1
        
        # Install pyradio python3 dependencies
        $PACKAGE_INSTALL python3-setuptools python3-wheel python3-pip python3-requests python3-dnspython python3-psutil python3-rich -y
        
        sleep 3
        
        # SPECIFILLY NAME IT WITH -O, TO OVERWRITE ANY PREVIOUS COPY...ALSO --no-cache TO ALWAYS GET LATEST COPY
        # Renaming pyradio's installation script may not work...
        wget --no-cache -O install.py https://raw.githubusercontent.com/coderholic/pyradio/master/pyradio/install.py
        
        sleep 2
        
        python3 install.py --force
        
        sleep 2
        
        echo " "
        echo "${green}pyradio installation complete.${reset}"
        echo " "
		
		echo " "
		echo "${red}Rebooting your system, please wait, and log back in afterwards...${reset}"
		echo " "
		
		sleep 5
		
		sudo reboot
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "internet_player_fix" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
       
       
        echo " "
        echo "${yellow}Select which pyradio fix to attempt, or skip it."
        echo "(ONLY RUN #AFTER# PYRADIO FIRST-TIME SETUP)${reset}"
        echo " "
        
        OPTIONS="connection_failed system_freezes mpv_low_volume skip"
        
        select opt in $OPTIONS; do
                if [ "$opt" = "connection_failed" ]; then

                # mpv fails opening streams in pyradio on low power devices, unless we set the connection timeout high
                sed -i 's/connection_timeout = .*/connection_timeout = 30/g' ~/.config/pyradio/config > /dev/null 2>&1
         
                echo " "
                echo "${green}Increased pyradio connection timout config to 30 seconds.${reset}"
       
                break
               elif [ "$opt" = "system_freezes" ]; then

               # Clears / updates cache, then upgrades (if NOT a rolling release)
               clean_system_update
                
                $PACKAGE_INSTALL mplayer -y
                
                # mpv crashes low power devices, mplayer does not (and vlc doesn't handle network disruption too well)
                sed -i 's/player = .*/player = mplayer, vlc, mpv/g' ~/.config/pyradio/config > /dev/null 2>&1
                
                echo " "
                echo "${green}RESET pyradio stream players config (to: mplayer, vlc, mpv).${reset}"
                
                
                break
               elif [ "$opt" = "mpv_low_volume" ]; then
                
                # mpv default volume set to 100
                sed -i 's/volume=.*/volume=100/g' ~/.config/mpv/mpv.conf > /dev/null 2>&1
                
                echo " "
                echo "${green}Increased mpv volume to 100.${reset}"
                
                
                break
               elif [ "$opt" = "skip" ]; then
                echo " "
                echo "${green}Skipping pyradio fixes.${reset}"
                break
               fi
        done
               
        echo " "
        
        break    
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "internet_player_on" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        
            # kill any background instances of pyradio
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "pyradio")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o pyradio > /dev/null 2>&1
            fi

            
            # kill any background instances of mplayer
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "mplayer")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o mplayer > /dev/null 2>&1
            fi

        
        bt_autoconnect_check > /dev/null 2>&1
        
        echo " "
        echo "${yellow}Select 1 or 2 to choose whether to load a custom stations file, or the default one.${reset}"
        echo " "
        
        OPTIONS="default_stations custom_stations"
        
        select opt in $OPTIONS; do
                if [ "$opt" = "custom_stations" ]; then
        
                echo " "
                echo "${yellow}Enter the #FULL SYSTEM PATH# (example: start with /home/$TERMINAL_USERNAME/ for your home directory)"
                echo "to your CUSTOM stations file, OR leave blank to use the default one.${reset}"
                echo " "
                
                read CUSTOM_STATIONS_FILE
                echo " "
                                
                	if [ -z "$CUSTOM_STATIONS_FILE" ]; then
                 	LOAD_CUSTOM_STATIONS=""
                 	PLAYLIST_DESC="default"
                 	else
                 	LOAD_CUSTOM_STATIONS="-s $CUSTOM_STATIONS_FILE"
                 	PLAYLIST_DESC="custom"
                 	fi
                
                break
               elif [ "$opt" = "default_stations" ]; then
                PLAYLIST_DESC="default"
                break
               fi
        done
        
        
            if [ -z "$LOAD_CUSTOM_STATIONS" ]; then
            echo " "
            echo "${green}Using default stations...${reset}"
            echo " "
            else
            echo " "
            echo "${green}Using custom stations from: $CUSTOM_STATIONS_FILE${reset}"
            echo " "
            fi
            
        
        echo " "
        echo "${cyan}PRO TIPS:"
        echo " "
        echo "Press the q OR Esc key to exit pyradio"
        echo " "
        echo "Navigate with the up / down arrows, and choose a station with the enter / return key"
        echo " "
        echo "Default stations are here (created after first-run): /home/$TERMINAL_USERNAME/.config/pyradio/stations.csv"
        echo "(you can overwrite this with your own default stations file [filename must stay the same])"
        echo " "
        echo "Full list of controls:"
        echo " "
        echo "https://github.com/coderholic/pyradio/blob/master/docs/index.md#controls"
        echo " "
        
            # IF FIRST RUN, FORCE SHOWING PYRADIO ON SCREEN (SO USER CONFIG FILES GET CREATED IN HOME DIR)
            if [ ! -d /home/$TERMINAL_USERNAME/.config/pyradio ]; then

            echo "${red} "
            echo "###########################################################################################"
            echo " "
            echo "We must activate pyradio config files for the first time, before continuing."
            echo " "
            echo "After letting pyradio run for a minute, please exit pyradio, and run this script again."
            echo " "
            echo "Afterwards, this notice will dissapear, and the normal pyradio options will show instead."
            echo " "
            
                # Raspberry pi device compatibilities NOTICE
                if [ -f "/usr/bin/raspi-config" ]; then
                echo "IF YOU GET 'connection failed' OR LOW VOLUME, DON'T WORRY, WE AUTO-FIX THAT FOR RASPBERRY PI DEVICES #NEXT TIME# YOU RUN PYRADIO."
                echo " "
                fi
                
            echo "###########################################################################################"
            echo "${reset} "
            
            echo "${yellow} "
            read -n1 -s -r -p $'Press Y to run pyradio first-time setup (or press N to cancel)...\n' keystroke
            echo "${reset} "
        
                if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
            
    		      echo " "
    			 echo "${cyan}Initiating pyradio first-time setup, please wait...${reset}"
                
                sleep 3
    			
    			 pyradio --play
            
                else
                echo "${green}pyradio first-time setup has been cancelled.${reset}"
                echo " "
                fi
                
            
            # OTHERWISE, LET USER CHOOSE WHICH WAY TO RUN PYRADIO
            else
            
            # We have already initialized pyradio and mpv beforehand,
            # so we can tweak a few config settings now before we start them up
            
            echo "${yellow} "
            echo "Enter B to run pyradio in the background, or S to show on-screen..." 
            echo "(to include the playlist number, enter b[num] / s[num] instead, eg: b2)"
            echo "(to reset default player, add player name, eg: b2mplayer)"
            echo "(valid options are: mplayer, vlc, mpv)"
            echo "${reset} "
            
            read keystroke
                
            SET_PLAYER="${keystroke:2:3}"
                
                
                # If set player param WAS NOT correctly included (AND NOT BLANK), set it to defaults
                if [[ $SET_PLAYER != "" ]] && [[ $SET_PLAYER != "mpl" ]] && [[ $SET_PLAYER != "vlc" ]] && [[ $SET_PLAYER != "mpv" ]]; then
                SET_PLAYER=""
                PLAYER_DESC=" (using player defaults [INVALID player value entered])"
                elif [[ $SET_PLAYER == "mpl" ]]; then
                SET_PLAYER="mplayer"
                PLAYER_DESC=" (setting player to: ${SET_PLAYER})"
                elif [[ $SET_PLAYER != "" ]]; then
                PLAYER_DESC=" (setting player to: ${SET_PLAYER})"
                fi
            
                
                # If set player param was CORRECTLY included
                if [[ $SET_PLAYER != "" ]]; then
                sed -i "s/player = .*/player = ${SET_PLAYER}/g" ~/.config/pyradio/config > /dev/null 2>&1
                sleep 1
                fi
                
            
            PLAY_NUM="${keystroke:1:1}"
            
                
                # If playlist number WAS NOT included 
                if [ -z "$PLAY_NUM" ]; then
                echo "${yellow} "
                read -p 'Enter playlist number: ' PLAY_NUM
                echo "${reset} "
                fi
            
                
                # If playlist number STILL WAS NOT included, set to 1
                if [ -z "$PLAY_NUM" ]; then
                PLAY_NUM=1
                fi
                
                
                echo " "
                echo "${green}Tuning pyradio to station ${PLAY_NUM}, in the ${PLAYLIST_DESC} playlist${PLAYER_DESC}...${reset}"
                echo " "
    
    
                if [[ ${keystroke:0:1} == "b" ]] || [[ ${keystroke:0:1} == "B" ]]; then
                
                # Export the vars to screen's bash session, OR IT WON'T RUN!
                export PLAY_NUM=$PLAY_NUM
                export LOAD_CUSTOM_STATIONS=$LOAD_CUSTOM_STATIONS
                screen -dmS pyradio bash -c 'pyradio --play ${PLAY_NUM} ${LOAD_CUSTOM_STATIONS}'
            
                elif [[ ${keystroke:0:1} == "s" ]] || [[ ${keystroke:0:1} == "S" ]]; then
                
                pyradio --play $PLAY_NUM $LOAD_CUSTOM_STATIONS
                
                echo " "
                echo "${cyan}Exited pyradio.${reset}"
                echo " "
            
                else
                
                echo "${cyan}Incorrect play mode does NOT exist (your input was: ${keystroke:0:1}), opening pyradio cancelled.${reset}"
                echo " "
            
                fi

                
            fi
            
        
        break 
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "local_player_install" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS."
             echo " "
             echo "(some components for mplayer are installed as a regular user)${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        echo "${red} "
        echo "NOTICE: YOUR COMPUTER WILL REBOOT AFTER CONFIGURATION OF THIS COMPONENT!"
        echo " "
        read -n1 -s -r -p $"Press Y to continue (or press N to exit)..." key
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
        echo "${green}Installing mplayer and required components, please wait...${reset}"
        echo " "
        
        sleep 1
        
        # Install screen
        $PACKAGE_INSTALL screen -y
        
        sleep 1
        
        # mplayer
        $PACKAGE_INSTALL mplayer -y
        
        sleep 3
        
        mkdir -p $HOME/Music/MPlayer
        
        echo " "
        echo "${green}mplayer installation complete.${reset}"
        echo " "
		
		echo " "
		echo "${red}Rebooting your system, please wait, and log back in afterwards...${reset}"
		echo " "
		
		sleep 5
		
		sudo reboot
        
        break 
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "local_player_on" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

        
            # kill any background instances of pyradio
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "pyradio")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o pyradio > /dev/null 2>&1
            fi

            
            # kill any background instances of mplayer
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "mplayer")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o mplayer > /dev/null 2>&1
            fi

        
        bt_autoconnect_check > /dev/null 2>&1
        
        MUSIC_DIR="$HOME/Music/MPlayer"
               
        
            # IF WE NEED TO CREATE THE MUSIC DIRECTORY
            if [ ! -d "$MUSIC_DIR" ]; then

            echo "${red} "
            echo "###########################################################################################"
            echo " "
            echo "We must create the mplayer MUSIC FOLDER: ~/Music/MPlayer (PUT ALL YOUR MUSIC FILES IN HERE AFTERWARDS)."
            echo " "
                
            echo "###########################################################################################"
            echo "${reset} "
            
            echo "${yellow} "
            read -n1 -s -r -p $'Press Y to create the mplayer MUSIC FOLDER (or press N to cancel)...\n' keystroke
            echo "${reset} "
        
                if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
    			
    			 mkdir -p $HOME/Music/MPlayer
    			 
    		      echo " "
    			 echo "${cyan}mplayer MUSIC FOLDER created at: ~/Music/MPlayer"
                echo " "
    			 echo "Please re-run this script AFTER MOVING YOUR MUSIC TO THIS FOLDER, exiting...${reset}"
                echo " "
                
                exit
            
                else

                echo "${green}mplayer MUSIC FOLDER setup has been cancelled, exiting...${reset}"
                echo " "
                exit
                
                fi
                
            
            # OTHERWISE, LET USER CHOOSE WHICH WAY TO RUN mplayer
            else
                
                
                recursive_media_scan () {
                     
                shopt -s nullglob dotglob
                    
                        for pathname in "$1"/*; do
                        
                            if [ -d "$pathname" ]; then
                                recursive_media_scan "$pathname"
                            else
                                case "$pathname" in
                                    *.mp3|*.ogg|*.wav|*.flac|*.mp4)
                                        printf '%s\n' "$pathname"
                                esac
                            fi

                        done
                        
                }
               
                
            echo "${yellow} "
            echo "Enter B to run mplayer in the background, or S to show on-screen..." 
            echo "(to SHUFFLE append S, eg: BS...append N or nothing to skip shuffling, eg: BN)"
            echo "(to RESCAN to include NEW music files, append R [AFTER SHUFFLE VALUE], eg: BSR...append N or nothing to skip rescanning, eg: BSN)"
            echo "${reset} "
            
            read keystroke
                
            IS_RESCAN="${keystroke:2:1}"
                
                
                # If rescan param WAS NOT included, set to N
                if [ -z "$IS_RESCAN" ]; then
                IS_RESCAN="N"
                fi
                
                
                if [[ $IS_RESCAN == "r" ]] || [[ $IS_RESCAN == "R" ]]; then
                SCAN_DESC="RE-scanning"
                else
                SCAN_DESC="Scanning"
                fi
            
               
                if [ ! -f ${MUSIC_DIR}/playlist.dat ] || [[ $IS_RESCAN == "r" ]] || [[ $IS_RESCAN == "R" ]]; then
                     
                rm ${MUSIC_DIR}/playlist.dat > /dev/null 2>&1
                     
                sleep 1
                
                echo "${green}${SCAN_DESC} media, and creating a NEW playlist at: ${MUSIC_DIR}/playlist.dat${reset}"
                echo " "

                MPLAYER_PLAYLIST=$(recursive_media_scan "$MUSIC_DIR")
                
                echo -e "$MPLAYER_PLAYLIST" > ${MUSIC_DIR}/playlist.dat

                sleep 3
                
                else
             
                echo "${green}Playlist already exists, SKIPPING media SCAN.${reset}"
                echo " "
    
                fi
                
            
            IS_SHUFFLED="${keystroke:1:1}"
            
                
                # If shuffle param WAS NOT included, set to N
                if [ -z "$IS_SHUFFLED" ]; then
                IS_SHUFFLED="N"
                fi
                
                
                if [[ $IS_SHUFFLED == "s" ]] || [[ $IS_SHUFFLED == "S" ]]; then
                MPLAYER_COMMAND="mplayer -shuffle"
                SHUFF_DESC="Shuffling"
                else
                MPLAYER_COMMAND="mplayer"
                SHUFF_DESC="Playing"
                fi
    
                
            echo " "
            echo "${green}${SHUFF_DESC} mplayer, in ${MUSIC_DIR} music directory...${reset}"
            echo " "
                
    
                if [[ ${keystroke:0:1} == "b" ]] || [[ ${keystroke:0:1} == "B" ]]; then
                
                echo " "
                echo "${green}BACKGROUND mode enabled...${reset}"
                echo " "
                
                # Export the vars to screen's bash session, OR IT WON'T RUN!
                export MPLAYER_COMMAND=$MPLAYER_COMMAND
                export MUSIC_DIR=$MUSIC_DIR
                screen -dmS mplayer bash -c '${MPLAYER_COMMAND} -playlist ${MUSIC_DIR}/playlist.dat'
            
                elif [[ ${keystroke:0:1} == "s" ]] || [[ ${keystroke:0:1} == "S" ]]; then
                
                echo " "
                echo "${green}SHOW mode enabled...${reset}"
                echo "${red}WHEN YOU ARE DONE LISTENING: hold down the 2 keys Ctrl+C at the same time, until you exit this script.${reset}"
                echo " "
                
                ${MPLAYER_COMMAND} -playlist ${MUSIC_DIR}/playlist.dat
                
                echo " "
                echo "${cyan}Exited mplayer.${reset}"
                echo " "
            
                else
                
                echo "${cyan}Opening mplayer cancelled.${reset}"
                echo " "
            
                fi

                
            fi
            
        
        break             
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "any_player_off" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
       
       
        echo " "
        echo "${green}Turning audio player OFF...${reset}"
        echo " "

        
            # kill any background instances of pyradio
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "pyradio")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o pyradio > /dev/null 2>&1
            fi

            
            # kill any background instances of mplayer
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "mplayer")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            else
            pkill -o mplayer > /dev/null 2>&1
            fi

        
        exit
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "bluetooth_status" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" -ne 0 ] || [ "$TERMINAL_USERNAME" == "root" ]; then 
             echo "${red}Please run #WITH# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
            
            # If 'pulseaudio' was found, start it
            if [ -f "$PULSEAUDIO_PATH" ]; then
                    
            echo "${yellow}bluetooth status: ${red}(HOLD Ctrl+C KEYS DOWN TO EXIT)${yellow}:"
            echo "${reset} "
            sudo systemctl status bluetooth.service
            exit
            
            else
            
            echo "PulseAudio not found, must be installed first, please re-run this script and choose that option."
            echo " "
                    
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "bluetooth_scan" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################

       
        echo " "
        echo "${yellow}We must find out what the mac address of your bluetooth receiver is."
        echo " "
        echo "Put your bluetooth receiver in pairing mode, and get ready to write down what you see as it's mac address (format: XX:XX:XX:XX:XX:XX).${reset}"
        echo " "
        echo "${red}WHEN YOU ARE DONE: hold down the 2 keys Ctrl+C at the same time, until you exit this script.${reset}"
        
        echo "${yellow} "
        read -n1 -s -r -p $'Press Y to run the bluetooth scan (or press N to cancel)...\n' keystroke
        echo "${reset} "

            if [ "$keystroke" = 'y' ] || [ "$keystroke" = 'Y' ]; then
        
            bluetoothctl scan on
            echo " "
            
            echo " "
            echo "${green}Bluetooth scanning complete.${reset}"
            echo " "
        
            else
            
            echo "${green}Bluetooth scanning cancelled.${reset}"
            echo " "
        
            fi
        
        break        
       elif [ "$opt" = "bluetooth_connect" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
        echo " "
        echo "${cyan}PRO TIP:"
        echo " "
        echo "WAIT UNTIL THE #CONNECTION ATTEMPT TIMES OUT#, TO SEE A #RESULTS SUMMARY# FOR YOUR CONNECTION ATTEMPT.${reset}"
        echo " "
        read -p "${yellow}Enter your bluetooth receiver mac address here (format: XX:XX:XX:XX:XX:XX):${reset} " BLU_MAC
        echo " "
        
        echo " "
        echo "${red}Checking $BLU_MAC pairing status (for STALE pairings), this may take a few minutes (running silently in the background), please wait..."
        
        sleep 5
        
        # Hide remove logic output, to avoid confusion with the add logic run after
        export APP_RECURSE=0 #RESET, TO ALLOW RE-RECURSION HERE
        ~/radio "remove $BLU_MAC" > /dev/null 2>&1
        
        sleep 10
        
        bluetoothctl power on
        
        echo " "
        echo "${red}Scanning for $BLU_MAC (to add / pair it), please wait up to a few minutes...${reset}"
        echo " "
        
        expect -c "
        set timeout 100
        spawn bluetoothctl
        send -- \"scan on\r\"
        expect \"$BLU_MAC\"
        send -- \"pair $BLU_MAC\r\"
        expect \"Pairing successful\"
        send -- \"trust $BLU_MAC\r\"
        expect \"trust succeeded\"
        send -- \"connect $BLU_MAC\r\"
        expect \"Connection successful\"
        send -- \"exit\r\"
        "
        
        
        sleep 3
        
        echo " "
        echo "${yellow}Bluetooth connection results:${reset}"
        echo "${cyan} "
        
        bluetoothctl info $BLU_MAC
        
        echo "${reset} "
        echo " "
        
        
        break        
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "bluetooth_remove" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
        echo "${cyan}PRO TIP:"
        echo " "
        echo "WAIT UNTIL THE #CONNECTION REMOVAL TIMES OUT#, TO SEE A #RESULTS SUMMARY# FOR YOUR CONNECTION REMOVAL.${reset}"
        echo " "
        read -p "${yellow}Enter your bluetooth receiver mac address here (format: XX:XX:XX:XX:XX:XX):${reset} " BLU_MAC
        echo " "
        
        bluetoothctl power on
        
        echo " "
        echo "${red}Scanning for $BLU_MAC (to remove / un-pair it), please wait 60 seconds or longer...${reset}"
        echo " "
        
        
        expect -c "
        set timeout 20
        spawn bluetoothctl
        send -- \"scan on\r\"
        expect \"$BLU_MAC\"
        send -- \"remove $BLU_MAC\r\"
        expect \"Device has been removed\"
        send -- \"exit\r\"
        "
        
        
        sleep 3
        
        echo " "
        echo "${green}Bluetooth device $BLU_MAC was removed.${reset}"
        echo " "
        
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "bluetooth_devices" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
            
        ######################################
                  

        echo " "
        echo "${yellow}Enter a NUMBER to choose whether to view the system's (INTERNAL) bluetooth devices, available devices, paired devices, or trusted devices (may not be available).${reset}"
        echo " "
            
            OPTIONS="internal_devices available_devices paired_devices trusted_devices"
            
            select opt in $OPTIONS; do
                    echo " "
                    
                    if [ "$opt" = "internal_devices" ]; then
                   
                    echo " "
                    echo "${yellow}System's (INTERNAL) bluetooth devices:"
                    echo "${reset} "
                    bluetoothctl list
                    echo " "
                    
                    break
                   elif [ "$opt" = "available_devices" ]; then
                   
                    echo " "
                    echo "${yellow}Avialable bluetooth devices:"
                    echo "${reset} "
                    bluetoothctl devices
                    echo " "
                    
                   break
                   elif [ "$opt" = "paired_devices" ]; then
                   
                    echo " "
                    echo "${yellow}Paired bluetooth devices:"
                    echo "${reset} "
                    bluetoothctl paired-devices
                    echo " "
                   
                   break
                   elif [ "$opt" = "trusted_devices" ]; then
                   
                    echo " "
                    echo "${yellow}Trusted bluetooth devices:"
                    echo "${reset} "
                    BT_TRUSTED=$(sudo grep -Ri trust /var/lib/bluetooth)
                    
                    # Cleanup results with sed
                    BT_TRUSTED=$(echo "$BT_TRUSTED" | sed 's/\/info\:.*//g')
                    BT_TRUSTED=$(echo "$BT_TRUSTED" | sed 's/.*bluetooth\///g')
                    BT_TRUSTED=$(sed 's|/|, |g' <<< $BT_TRUSTED) # replace "/" with ", "
                    
                    echo $BT_TRUSTED
                    echo " "
                   
                   break
                   
                   fi
            done
        
        break    
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "sound_test" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
        bt_autoconnect_check > /dev/null 2>&1
        
        sleep 2
       
        echo " "
        echo "${green}Testing with 'Center' sound test..."
        echo " "
        echo "${red}If you did NOT hear this word on your bluetooth speaker, there likely is a problem somewhere.${reset}"
        echo " "
        
        aplay /usr/share/sounds/alsa/Front_Center.wav
        exit
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "volume_adjust" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
       
       
        alsamixer
        
        echo " "
        echo "${green}Saving customized alsamixer settings to: ~/.config/radio.alsamixer.state${reset}"
        echo " "
        
        sleep 1
        
        # RELIABLY persist volume / other alsamixer setting changes
        # https://askubuntu.com/questions/50067/how-to-save-alsamixer-settings
        alsactl --file ~/.config/radio.alsamixer.state store
       
        echo " "
        echo "${cyan}Exiting volume control...${reset}"
        echo " "
        
        exit
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "troubleshoot" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
       
        echo " "
        echo "${red}IF THE BLUETOOTH CONNECTION SUDDENLY FAILS, AFTER IT WAS WORKING FINE BEFORE:${reset} Restart the transmitting AND receiving devices (reboot / temporarily unplug / etc)."
        echo " "
        
        exit
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "syslog_logs" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" -ne 0 ] || [ "$TERMINAL_USERNAME" == "root" ]; then 
             echo "${red}Please run #WITH# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        
            
            # If 'pulseaudio' was found, start it
            if [ -f "$PULSEAUDIO_PATH" ]; then
                    
            echo "${yellow}pulseaudio / bluetoothd logs:${reset}"
            echo " "
            less /var/log/syslog | grep "bluetoothd\|pulseaudio"
            
            else
            
            echo "pulseaudio not found, must be installed first, please re-run this script and choose that option."
            echo " "
                    
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "journal_logs" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
                    
        echo "${yellow}bluetooth journal ${red}(HOLD Ctrl+C KEYS DOWN TO EXIT)${yellow}:"
        echo "${reset} "
        journalctl -u bluetooth.service -u pulseaudio.service -u btautoconnect.service --since today
        exit
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "restart_computer" ]; then
       
        echo " "
        echo "${green}Rebooting...${reset}"
        echo " "
        
        sudo reboot
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "exit_app" ]; then
       
        echo " "
        echo "${green}Exiting...${reset}"
        echo " "
        
        exit
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "other_apps" ]; then
        
        
        ######################################
        
        echo " "
        
            if [ "$EUID" == 0 ]; then 
             echo "${red}Please run #WITHOUT# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
        

        echo " "
        echo "${yellow}100% FREE open source PRIVATE cryptocurrency investment portfolio tracker,"
        echo "with email / text / Alexa / Ghome / Telegram alerts, charts, mining calculators,"
        echo "leverage / gain / loss / balance stats, news feeds and more:${reset}"
        echo " "
        echo "${green}https://taoteh1221.github.io${reset}"
        echo " "
        echo "${green}https://github.com/taoteh1221/Open_Crypto_Tracker${reset}"
        echo " "

        echo " "
        echo "${yellow}100% FREE open source multi-crypto slideshow ticker for Raspberry Pi LCD screens:${reset}"
        echo " "
        echo "${green}https://sourceforge.net/projects/dfd-crypto-ticker${reset}"
        echo " "
        echo "${green}https://github.com/taoteh1221/Slideshow_Crypto_Ticker${reset}"
        echo " "
        
        echo " "
        echo "${yellow}ANY DONATIONS (LARGE OR SMALL) HELP SUPPORT DEVELOPMENT OF MY APPS..."
        echo " "
        echo "${cyan}Bitcoin: ${green}3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW"
        echo " "
        echo "${cyan}Ethereum: ${green}0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8"
        echo " "
        echo "${cyan}Solana: ${green}GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU"
        echo " "

       
        echo " "
        echo "${yellow}Would you like to install any of these other apps, or skip them?${reset}"
        echo " "
        
        OPTIONS="install_open_crypto_tracker install_slideshow_crypto_ticker skip"
        
        select opt in $OPTIONS; do
                if [ "$opt" = "install_open_crypto_tracker" ]; then
         
    			echo " "
    			
    			echo "${green}Proceeding with portfolio tracker installation, please wait...${reset}"
    			
    			echo " "
    			
    			wget --no-cache -O FOLIO-INSTALL.bash https://raw.githubusercontent.com/taoteh1221/Open_Crypto_Tracker/main/FOLIO-INSTALL.bash
    			
    			chmod +x FOLIO-INSTALL.bash
    			
    			chown $APP_USER:$APP_USER FOLIO-INSTALL.bash
    			
    			sudo ./FOLIO-INSTALL.bash
			
                break
               elif [ "$opt" = "install_slideshow_crypto_ticker" ]; then
			
    			echo " "
    			
    			echo "${green}Proceeding with crypto ticker installation, please wait...${reset}"
    			
    			echo " "
    			
    			wget --no-cache -O TICKER-INSTALL.bash https://raw.githubusercontent.com/taoteh1221/Slideshow_Crypto_Ticker/main/TICKER-INSTALL.bash
    			
    			chmod +x TICKER-INSTALL.bash
    			
    			chown $APP_USER:$APP_USER TICKER-INSTALL.bash
    			
    			sudo ./TICKER-INSTALL.bash
			
                break
               elif [ "$opt" = "skip" ]; then
                echo " "
                echo "${green}Skipping other apps installation.${reset}"
                break
               fi
        done
               
        echo " "
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "about_this_app" ]; then
       
        echo "${red} "
        echo "Copyright $COPYRIGHT_YEARS GPLv3, Bluetooth Internet Radio By Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)"
        
        echo "${yellow} "
        echo "Version: ${APP_VERSION}"
        echo " "
        echo "https://github.com/taoteh1221/Bluetooth_Internet_Radio"
        echo " "
        
        echo "${cyan}Fully automated setup of bluetooth, internet radio player (PyRadio), local music files player (mplayer), on a headless RaspberryPi,"
        echo "connecting to a stereo system's bluetooth receiver (bash script, chmod +x it to run)."
        echo " "
        
        echo "To install automatically on Ubuntu / RaspberryPi OS / Armbian, copy => paste => run the command below in a"
        echo "terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE"
        echo "USER THAT WILL RUN THE APP (user must have sudo privileges):"
        echo " "
        
        echo "${yellow}wget --no-cache -O bt-radio-setup.bash https://tinyurl.com/bt-radio-setup;chmod +x bt-radio-setup.bash;./bt-radio-setup.bash"
        echo " "
        
        echo "${cyan}AFTER installation, ~/radio is installed as a shortcut command pointing to this script,"
        echo "and paired bluetooth reconnects (if disconnected) when you start a new terminal session."
        echo " "
        
        echo "A command line parameter can be passed to auto-select menu choices. Multi sub-option selecting is available too,"
        echo "by seperating each sub-option with a space, AND ecapsulating everything in quotes like \"option1 sub-option2 sub-sub-option3\"."
        echo " "
        
        echo "Running normally (displays options to choose from):"
        echo " "
        echo "${green}~/radio${cyan}"
        echo " "
        echo "Auto-selecting single / multi option examples ${red}(MULTI OPTIONS #MUST# BE IN QUOTES!)${cyan}:"
        echo " "
        echo "${green}~/radio \"1 y\""
        echo "${green}~/radio \"upgrade y\"${cyan}"
        echo "(checks for / confirms script upgrade)"
        echo " "
        echo "${green}~/radio \"7 1 b3\""
        echo "${green}~/radio \"internet 1 b3\"${cyan}"
        echo "(plays default INTERNET playlist in background, 3rd station)"
        echo "${green}~/radio \"internet 1 b3vlc\"${cyan}"
        echo "(plays default INTERNET playlist in background, 3rd station, RESET default player to: vlc)"
        echo " "
        echo "${green}~/radio \"9 bsr\""
        echo "${green}~/radio \"local bsr\"${cyan}"
        echo "(rescans music files / plays LOCAL music folder ~/Music/MPlayer [RECURSIVELY] in background, shuffling)"
        echo " "
        echo "${green}~/radio 10"
        echo "${green}~/radio off${cyan}"
        echo "(stops audio playback)"
        echo " "
        echo "${green}~/radio \"12 XX:XX:XX:XX:XX:XX\""
        echo "${green}~/radio \"connect XX:XX:XX:XX:XX:XX\"${cyan}"
        echo "(connect bluetooth device by mac address)"
        echo " "
        echo "${green}~/radio \"13 XX:XX:XX:XX:XX:XX\""
        echo "${green}~/radio \"remove XX:XX:XX:XX:XX:XX\"${cyan}"
        echo "(remove bluetooth device by mac address)"
        echo " "
        echo "${green}~/radio \"14 3\""
        echo "${green}~/radio \"devices paired\"${cyan}"
        echo "(shows paired bluetooth devices)"
        echo "${reset} "
        echo " "
        
        exit
        
        break
        
        fi
        
        ##################################################################################################################
        ##################################################################################################################
        
       
done
# done options

exit


