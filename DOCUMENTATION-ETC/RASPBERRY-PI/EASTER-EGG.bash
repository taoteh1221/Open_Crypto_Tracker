#!/bin/bash

########################################################################################################################
########################################################################################################################

# Copyright 2022 GPLv3, Bluetooth Internet Radio By Mike Kilday: Mike@DragonFrugal.com

# https://github.com/taoteh1221/Bluetooth_Internet_Radio

# Fully automated setup of bluetooth and an internet radio player (PyRadio), on a headless RaspberryPi,
# connecting to a stereo system's bluetooth receiver (bash script, chmod +x it to run).

# To install automatically on Ubuntu / RaspberryPi OS, copy => paste => run the command below in a
# terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE
# USER THAT WILL RUN THE APP (user must have sudo privileges):

# wget --no-cache -O bt-radio-setup.bash https://tinyurl.com/bt-radio-setup;chmod +x bt-radio-setup.bash;./bt-radio-setup.bash

# AFTER installation, ~/radio is installed as a shortcut command pointing to this script,
# and paired bluetooth reconnects (if disconnected) when you start a new terminal session. 

# A command line parameter can be passed to auto-select menu choices. Multi sub-option selecting is available too,
# by seperating each sub-option with a space, AND ecapsulating everything in quotes like "option1 sub-option2 sub-sub-option3".

# Running normally (diplays options to choose from):

# ~/radio
 
# Auto-selecting single / multi sub-option examples (MULTI SUB-OPTIONS #MUST# BE IN QUOTES!):
 
# ~/radio "1 y"
# (checks for / confirms script upgrade)
 
# ~/radio "7 1 b"
# (plays pyradio default station in background)
 
# ~/radio 8
# (stops pyradio background playing)
 
# ~/radio "10 XX:XX:XX:XX:XX:XX"
# (connect bluetooth device by mac address)
 
# ~/radio "11 XX:XX:XX:XX:XX:XX"
# (remove bluetooth device by mac address)
 
# ~/radio "12 3"
# (shows paired bluetooth devices)

########################################################################################################################
########################################################################################################################


# Version of this script
APP_VERSION="1.01.1" # 2022/MARCH/29TH


# If parameters are added via command line
# (CLEANEST WAY TO RUN PARAMETER INPUT #TO AUTO-SELECT MULTIPLE CONSECUTIVE OPTION MENUS#)
# (WE CAN PASS THEM #IN QUOTES# AS: command "option1 sub-option2 sub-sub-option3")
if [ "$1" != "" ] && [ "$APP_RECURSE" != "1" ]; then
APP_RECURSE=1
export APP_RECURSE=$APP_RECURSE
printf "%s\n" $1 | ~/radio
exit
fi


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


export XAUTHORITY=~/.Xauthority 
				
# Export current working directory, in case we are calling another bash instance in this script
export PWD=$PWD

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


# pulseaudio's FULL PATH (to run checks later)
PULSEAUDIO_PATH=$(which pulseaudio)

# bluetooth-autoconnect's FULL PATH (to run checks OR install later)
BT_AUTOCONNECT_PATH="${SCRIPT_PATH}/bluetooth-autoconnect.py"

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


# Setup color coding
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


# Quit if ACTUAL USERNAME is root
if [ "$TERMINAL_USERNAME" == "root" ]; then 
 echo " "
 echo "${red}Please run as a NORMAL USER WITH 'sudo' PERMISSIONS (NOT LOGGED IN AS 'root').${reset}"
 echo " "
 echo "${cyan}Exiting...${reset}"
 echo " "
 exit
fi


# Get primary dependency apps, if we haven't yet
    
# If 'python3' wasn't found, install it
# python3's FULL PATH (we DONT want python [which is python2])
PYTHON_PATH=$(which python3)

if [ -z "$PYTHON_PATH" ]; then

echo " "
echo "${cyan}Installing required component python3, please wait...${reset}"
echo " "

sudo apt update

sudo apt install python3 -y

fi


# Install xdg-user-dirs if needed
XDGUSER_PATH=$(which xdg-user-dir)

if [ -z "$XDGUSER_PATH" ]; then

echo " "
echo "${cyan}Installing required component xdg-user-dirs, please wait...${reset}"
echo " "

sudo apt update

sudo apt install xdg-user-dirs -y

fi


# Install rsyslogd if needed
SYSLOG_PATH=$(which rsyslogd)

if [ -z "$SYSLOG_PATH" ]; then

echo " "
echo "${cyan}Installing required component rsyslog, please wait...${reset}"
echo " "

sudo apt update

sudo apt install rsyslog -y

fi


# Install git if needed
GIT_PATH=$(which git)

if [ -z "$GIT_PATH" ]; then

echo " "
echo "${cyan}Installing required component git, please wait...${reset}"
echo " "

sudo apt update

sudo apt install git -y

fi


# Install curl if needed
CURL_PATH=$(which curl)

if [ -z "$CURL_PATH" ]; then

echo " "
echo "${cyan}Installing required component curl, please wait...${reset}"
echo " "

sudo apt update

sudo apt install curl -y

fi


# Install jq if needed
JQ_PATH=$(which jq)

if [ -z "$JQ_PATH" ]; then

echo " "
echo "${cyan}Installing required component jq, please wait...${reset}"
echo " "

sudo apt update

sudo apt install jq -y

fi


# Install wget if needed
WGET_PATH=$(which wget)

if [ -z "$WGET_PATH" ]; then

echo " "
echo "${cyan}Installing required component wget, please wait...${reset}"
echo " "

sudo apt update

sudo apt install wget -y

fi


# Install sed if needed
SED_PATH=$(which sed)

if [ -z "$SED_PATH" ]; then

echo " "
echo "${cyan}Installing required component sed, please wait...${reset}"
echo " "

sudo apt update

sudo apt install sed -y

fi


# Install less if needed
LESS_PATH=$(which less)
				
if [ -z "$LESS_PATH" ]; then

echo " "
echo "${cyan}Installing required component less, please wait...${reset}"
echo " "

sudo apt update

sudo apt install less -y

fi


# Install expect if needed
EXPECT_PATH=$(which expect)
				
if [ -z "$EXPECT_PATH" ]; then

echo " "
echo "${cyan}Installing required component expect, please wait...${reset}"
echo " "

sudo apt update

sudo apt install expect -y

fi


# Install avahi-daemon if needed (for .local names on internal / home network)
AVAHID_PATH=$(which avahi-daemon)

if [ -z "$AVAHID_PATH" ]; then

echo " "
echo "${cyan}Installing required component avahi-daemon, please wait...${reset}"
echo " "

sudo apt update

sudo apt install avahi-daemon -y

fi


# Install bc if needed (for decimal math in bash)
BC_PATH=$(which bc)

if [ -z "$BC_PATH" ]; then

echo " "
echo "${cyan}Installing required component bc, please wait...${reset}"
echo " "

sudo apt update

sudo apt install bc -y

fi

# dependency check END


###############################################################################################
# Primary init complete, now check bt_autoconnect_install and symbolic link status
###############################################################################################


# bt_autoconnect_install function START
bt_autoconnect_install () {

    # Install bluetooth-autoconnect.py if needed (AND we are #NOT# running as sudo)
    if [ ! -f "$BT_AUTOCONNECT_PATH" ] && [ "$EUID" != 0 ]; then
    
    echo " "
    echo "${cyan}Installing required component bluetooth-autoconnect and dependencies, please wait...${reset}"
    echo " "
    
    sudo apt update
    
    # Install python3 prctl
    sudo apt install python3-prctl -y
    
    # Install python3 dbus modules
    sudo apt install python3-dbus python3-slip-dbus python3-pydbus -y
    
            
    # SPECIFILLY NAME IT WITH -O, TO OVERWRITE ANY PREVIOUS COPY...ALSO --no-cache TO ALWAYS GET LATEST COPY
    wget --no-cache -O TEMP-BT-AUTO-CONN.py https://raw.githubusercontent.com/taoteh1221/Bluetooth_Internet_Radio/main/bluetooth-autoconnect/bluetooth-autoconnect.py
    
    sleep 2
    
    mv -v --force TEMP-BT-AUTO-CONN.py $BT_AUTOCONNECT_PATH
    
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
ExecStart=python3 $BT_AUTOCONNECT_PATH
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
    python3 $BT_AUTOCONNECT_PATH
    fi

}
# bt_autoconnect_install function END


# Call bt_autoconnect_install function
bt_autoconnect_install


# bt_autoconnect_check function
bt_autoconnect_check () {
        
# Make sure we are connected to the bluetooth receiver (NOT just paired)
# (SOME DEVICES MAY DISCONNECT AGAIN IF WHEN YOU LOGIN, YOU DON'T #QUICKLY# START A SOUND / RADIO STREAM)
CONNECT_STATUS=$(python3 $BT_AUTOCONNECT_PATH)
        
     if [ -n "$CONNECT_STATUS" ]; then
     echo " "
     echo "$CONNECT_STATUS"
     echo " "
     fi
            
}


if [ ! -f ~/radio ]; then 

ln -s $SCRIPT_LOCATION ~/radio

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
echo "Running normally (diplays options to choose from):"
echo " "
echo "${green}~/radio${cyan}"
echo " "
echo "Auto-selecting single / multi sub-option examples ${red}(MULTI SUB-OPTIONS #MUST# BE IN QUOTES!)${cyan}:"
echo " "
echo "${green}~/radio \"1 y\"${cyan}"
echo "(checks for / confirms script upgrade)"
echo " "
echo "${green}~/radio \"7 1 b\"${cyan}"
echo "(plays pyradio default station in background)"
echo " "
echo "${green}~/radio 8${cyan}"
echo "(stops pyradio background playing)"
echo " "
echo "${green}~/radio \"10 XX:XX:XX:XX:XX:XX\"${cyan}"
echo "(connect bluetooth device by mac address)"
echo " "
echo "${green}~/radio \"11 XX:XX:XX:XX:XX:XX\"${cyan}"
echo "(remove bluetooth device by mac address)"
echo " "
echo "${green}~/radio \"12 3\"${cyan}"
echo "(shows paired bluetooth devices)"
echo "${reset} "
fi


###############################################################################################
# Secondary init / checks complete, now run main app logic
###############################################################################################


echo " "
echo "${yellow}Enter the NUMBER next to your chosen option:${reset}"
echo " "

OPTIONS="upgrade_check pulseaudio_install pulseaudio_fix pulseaudio_status pyradio_install pyradio_fix pyradio_on pyradio_off bluetooth_scan bluetooth_connect bluetooth_remove bluetooth_devices sound_test volume_adjust troubleshoot syslog_logs journal_logs restart_computer exit_app other_apps"


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
            read -n1 -s -r -p $"Press y to upgrade (or press n to cancel)..." key
            echo "${reset} "
                    
                    
                if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
                      
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
                    
                    mv -v --force BT-TEMP.bash $SCRIPT_LOCATION
                    
                    sleep 3
                
                    chmod +x $SCRIPT_LOCATION
                    				
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
        
        
        echo "${cyan}Making sure your system is updated before installation, please wait...${reset}"
        
        echo " "
        			
        apt-get update
        
        #DO NOT RUN dist-upgrade, bad things can happen, lol
        apt-get upgrade -y
        
        echo " "
        				
        echo "${cyan}System update completed.${reset}"
        				
        sleep 3
        

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
                read -n1 -s -r -p $'Press y to run dietpi-config (or #IF# YOU DID THIS ALREADY press n to skip)...\n' key
                echo "${reset} "
        
                    if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
                
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
        apt install alsa-utils -y
        
        apt install pulseaudio* -y
        
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
    		
    		rm $BT_AUTOCONNECT_PATH
    		
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
                    
            echo "${yellow}PulseAudio status: ${red}(HOLD Ctrl+c KEYS DOWN TO EXIT)${yellow}:"
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
        
        elif [ "$opt" = "pyradio_install" ]; then
        
        
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
        
        # https://github.com/coderholic/pyradio/blob/master/build.md
        
        echo " "
        echo "${green}Installing pyradio and required components, please wait...${reset}"
        echo " "
        
        sudo apt update
        
        sleep 1
        
        # Install screen and mpv instead of mplayer, it's more stable
        sudo apt install screen mpv -y
        
        sleep 1
        
        # mplayer as backup if distro doesn't have an mpv package (mpv will be used first automatically if found)
        sudo apt install mplayer -y
        
        sleep 1
        
        # vlc as backup if distro doesn't have an mpv or mplayer package
        sudo apt install vlc -y
        
        sleep 1
        
        # Install pyradio python3 dependencies
        sudo apt install python3-setuptools python3-wheel python3-pip python3-requests python3-dnspython python3-psutil -y
        
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
        
        elif [ "$opt" = "pyradio_fix" ]; then
        
        
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
                sed -i 's/connection_timeout = .*/connection_timeout = 30/g' ~/.config/pyradio/config
         
                echo " "
                echo "${green}Increased pyradio connection timout to 30 seconds.${reset}"
       
                break
               elif [ "$opt" = "system_freezes" ]; then
                
                sudo apt update
                sudo apt install mplayer -y
                
                # mpv crashes low power devices, mplayer does not (and vlc doesn't handle network disruption too well)
                sed -i 's/player = .*/player = mplayer, vlc, mpv/g' ~/.config/pyradio/config
                
                echo " "
                echo "${green}Set mplayer to default pyradio stream player.${reset}"
                
                
                break
               elif [ "$opt" = "mpv_low_volume" ]; then
                
                # mpv default volume set to 100
                sed -i 's/volume=.*/volume=100/g' ~/.config/mpv/mpv.conf
                
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
        
        elif [ "$opt" = "pyradio_on" ]; then
        
        
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
        fi
        
        bt_autoconnect_check
        
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
                    echo " "
                 	echo "${green}Using default stations...${reset}"
                    echo " "
                 	else
                 	LOAD_CUSTOM_STATIONS="-s $CUSTOM_STATIONS_FILE"
                    echo " "
                    echo "${green}Using custom stations from: $CUSTOM_STATIONS_FILE${reset}"
                    echo " "
                 	fi
                
                break
               elif [ "$opt" = "default_stations" ]; then
                echo " "
                echo "${green}Using default stations...${reset}"
                echo " "
                break
               fi
        done
        
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
        echo "https://github.com/coderholic/pyradio/blob/master/README.md#controls"
        echo " "
        
            # IF FIRST RUN, FORCE SHOWING PYRADIO ON SCREEN (SO USER CONFIG FILES GET CREATED IN HOME DIR)
            if [ ! -d /home/pi/.config/pyradio ]; then

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
            read -n1 -s -r -p $'Press y to run pyradio first-time setup (or press n to cancel)...\n' key
            echo "${reset} "
        
                if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
            
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
            
            
                # Raspberry pi device compatibilities
                if [ -f "/usr/bin/raspi-config" ]; then
                
                # mpv crashes a raspberry pi zero, mplayer does not (and vlc doesn't handle network disruption too well)
                sed -i 's/player = .*/player = mplayer, vlc, mpv/g' ~/.config/pyradio/config
                
                sleep 1

                # mpv fails opening streams in pyradio on raspi devices, unless we set the connection timeout high
                sed -i 's/connection_timeout = .*/connection_timeout = 30/g' ~/.config/pyradio/config
                
                # mpv default volume is VERY low on raspi os, so we set it to 100 instead
                sed -i 's/volume=.*/volume=100/g' ~/.config/mpv/mpv.conf
         
                echo " "
                echo "${red}Raspberry Pi compatibility settings for pyradio have been applied.${reset}"
                echo " "
            
                fi
            
            
            echo "${reset}${yellow} "
            read -n1 -s -r -p $'Press b to run pyradio in the background, or s to show on-screen...\n' key
            echo "${reset} "
    
                if [ "$key" = 'b' ] || [ "$key" = 'B' ]; then
            
                echo "${yellow} "
                read -p 'Enter playlist number: ' PLAY_NUM
                echo "${reset} "
                
                    if [ -z "$PLAY_NUM" ]; then
                    PLAY_NUM=1
                    fi
                
                echo " "
                echo "${green}Tuning pyradio to playlist ${PLAY_NUM}...${reset}"
                echo " "
                
                # Export the vars to screen's bash session, OR IT WON'T RUN!
                export PLAY_NUM=$PLAY_NUM
                export LOAD_CUSTOM_STATIONS=$LOAD_CUSTOM_STATIONS
                screen -dmS pyradio bash -c 'pyradio --play ${PLAY_NUM} ${LOAD_CUSTOM_STATIONS}'
            
                elif [ "$key" = 's' ] || [ "$key" = 'S' ]; then
                
                pyradio --play $LOAD_CUSTOM_STATIONS
                
                echo " "
                echo "${cyan}Exited pyradio.${reset}"
                echo " "
            
                else
                
                echo "${cyan}Opening pyradio cancelled.${reset}"
                echo " "
            
                fi
                
            fi
            
        
        break        
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pyradio_off" ]; then
        
        
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
        echo "${green}Turning pyradio OFF...${reset}"
        echo " "
        
            # kill any background instances of pyradio
            SCREENS_DETACHED=$(screen -ls | grep Detached | grep "pyradio")
            if [ "$SCREENS_DETACHED" != "" ]; then
            echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
            fi
        
        exit
        
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
        echo "${red}WHEN YOU ARE DONE: hold down the 2 keys Ctrl+c at the same time, until you exit this script.${reset}"
        
        echo "${yellow} "
        read -n1 -s -r -p $'Press y to run the bluetooth scan (or press n to cancel)...\n' key
        echo "${reset} "

            if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
        
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
        
        bluetoothctl power on
        echo " "
        
        echo "${cyan}Scanning for device $BLU_MAC, ${red}please wait 60 seconds or longer${cyan}...${reset}"
        echo " "
        
        
        expect -c "
        set timeout 20
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
        
        echo "${cyan}Scanning for device $BLU_MAC, ${red}please wait 60 seconds or longer${cyan}...${reset}"
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
        
        bt_autoconnect_check
        
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
                    
        echo "${yellow}bluetooth journal ${red}(HOLD Ctrl+c KEYS DOWN TO EXIT)${yellow}:"
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
        echo "${cyan}Helium: ${green}13xs559435FGkh39qD9kXasaAnB8JRF8KowqPeUmKHWU46VYG1h"
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
        
        fi
        
        ##################################################################################################################
        ##################################################################################################################
        
       
done
# done options

exit


