#!/bin/bash


########################################################################################################################
########################################################################################################################


# Copyright 2022 GPLv3, Bluetooth Internet Radio By Mike Kilday: Mike@DragonFrugal.com

# https://github.com/taoteh1221/Bluetooth_Internet_Radio

# Fully automated setup of bluetooth and an internet radio player (PyRadio), on a headless RaspberryPi / DietPi,
# connecting to a stereo system's bluetooth receiver (bash script, chmod +x it to run).

# To install automatically on Ubuntu / DietPi OS / RaspberryPi OS, copy => paste => run the command below in a
# terminal program (using the 'Terminal' app in the system menu, or over remote SSH), while logged in AS THE
# USER THAT WILL RUN THE APP (user must have sudo privileges):

# wget --no-cache -O bt-radio-setup.bash https://tinyurl.com/bt-radio-setup;chmod +x bt-radio-setup.bash;./bt-radio-setup.bash


########################################################################################################################
########################################################################################################################


# Version of this script
APP_VERSION="1.00.4" # 2022/MARCH/18TH

# If parameters are added via command line
# (CLEANEST WAY TO RUN PARAMETER INPUT #TO AUTO-SELECT MULTIPLE CONSECUTIVE OPTION MENUS#)
# (WE CAN PASS THEM #IN QUOTES# AS: command "option1 sub-option2 sub-sub-option3")
if [ "$1" != "" ] && [ "$APP_RECURSE" != "1" ]; then
APP_RECURSE=1
export APP_RECURSE=$APP_RECURSE
printf "%s\n" $1 | ~/radio
exit
else
UNUSED_VAR="dummy command"
#debugging here
#echo "APP_RECURSE=$APP_RECURSE"
#echo "PARAM=$1"
fi

export XAUTHORITY=~/.Xauthority 
				
# Export current working directory, in case we are calling another bash instance in this script
export PWD=$PWD


# EXPLICITLY set any dietpi paths 
# Export too, in case we are calling another bash instance in this script
if [ -f /boot/dietpi/.version ]; then
PATH=/boot/dietpi:$PATH
export PATH=$PATH
fi
				

# EXPLICITLY set any ~/.local/bin paths (for pyradio, etc)
# Export too, in case we are calling another bash instance in this script
if [ -d ~/.local/bin ]; then
PATH=~/.local/bin:$PATH
export PATH=$PATH
fi


# Get date
DATE=$(date '+%Y-%m-%d')


# Bash's FULL PATH
BASH_PATH=$(which bash)
        
        
# Use LATEST version of python available
        
# Look for python3
PYTHON_PATH=$(which python3)

# If 'python3' wasn't found, look for 'python'
if [ -z "$PYTHON_PATH" ]; then
PYTHON_PATH=$(which python)
fi


# git's FULL PATH
GIT_PATH=$(which git)


# curl's FULL PATH
CURL_PATH=$(which curl)


# jq's FULL PATH
JQ_PATH=$(which jq)


# wget's FULL PATH
WGET_PATH=$(which wget)


# sed's FULL PATH
SED_PATH=$(which sed)


# less's FULL PATH
LESS_PATH=$(which less)


# pyradio's FULL PATH
PYRADIO_PATH=$(which pyradio)
				

# Path to expect binary
EXPECT_PATH=$(which expect)
        
        
# pulseaudio's FULL PATH
PULSEAUDIO_PATH=$(which pulseaudio)


# Get logged-in username (if sudo, this works best with logname)
TERMINAL_USERNAME=$(logname)


SCRIPT_NAME=`basename "$0"`

SCRIPT_PATH="$( cd -- "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"

SCRIPT_LOCATION="${SCRIPT_PATH}/${SCRIPT_NAME}"


######################################


# If logname doesn't work, use the $SUDO_USER or $USER global var
if [ -z "$TERMINAL_USERNAME" ]; then

    if [ -z "$SUDO_USER" ]; then
    TERMINAL_USERNAME=$USER
    else
    TERMINAL_USERNAME=$SUDO_USER
    fi

fi


######################################


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

if [ "$TERMINAL_USERNAME" == "root" ]; then 
 echo "${red}Please run as a NORMAL USER WITH 'sudo' PERMISSIONS (NOT LOGGED IN AS 'root').${reset}"
 echo " "
 echo "${cyan}Exiting...${reset}"
 echo " "
 exit
fi


######################################

# Install python if needed
if [ -z "$PYTHON_PATH" ]; then

echo " "
echo "${cyan}Installing required components python python3, please wait...${reset}"
echo " "

sudo apt update

sudo apt install python python3 -y

sleep 5
        
# Use LATEST version of python available
        
# Look for python3
PYTHON_PATH=$(which python3)

    # If 'python3' wasn't found, look for 'python'
    if [ -z "$PYTHON_PATH" ]; then
    PYTHON_PATH=$(which python)
    fi

fi

# Install git if needed
if [ -z "$GIT_PATH" ]; then

echo " "
echo "${cyan}Installing required component git, please wait...${reset}"
echo " "

sudo apt update

sudo apt install git -y

fi


# Install curl if needed
if [ -z "$CURL_PATH" ]; then

echo " "
echo "${cyan}Installing required component curl, please wait...${reset}"
echo " "

sudo apt update

sudo apt install curl -y

fi

# Install jq if needed
if [ -z "$JQ_PATH" ]; then

echo " "
echo "${cyan}Installing required component jq, please wait...${reset}"
echo " "

sudo apt update

sudo apt install jq -y

fi

# Install wget if needed
if [ -z "$WGET_PATH" ]; then

echo " "
echo "${cyan}Installing required component wget, please wait...${reset}"
echo " "

sudo apt update

sudo apt install wget -y

fi

# Install sed if needed
if [ -z "$SED_PATH" ]; then

echo " "
echo "${cyan}Installing required component sed, please wait...${reset}"
echo " "

sudo apt update

sudo apt install sed -y

fi

# Install less if needed
if [ -z "$LESS_PATH" ]; then

echo " "
echo "${cyan}Installing required component less, please wait...${reset}"
echo " "

sudo apt update

sudo apt install less -y

fi


BT_AUTOCONNECT_PATH="${PWD}/bluetooth-autoconnect.py"


# bluetooth_autoconnect function START
bluetooth_autoconnect () {

    # Install bluetooth-autoconnect.py if needed (AND we are #NOT# running as sudo)
    if [ ! -f "$BT_AUTOCONNECT_PATH" ] && [ "$EUID" != 0 ]; then
    
    echo " "
    echo "${cyan}Installing required component bluetooth-autoconnect and dependancies, please wait...${reset}"
    echo " "
    
    sudo apt update
    sudo apt install pip -y
    
    # Install pip packages system-wide with sudo
    sudo pip install --upgrade setuptools
    sudo pip install dbus-python
    sudo pip install python-prctl
    
            
    # SPECIFILLY NAME IT WITH -O, TO OVERWRITE ANY PREVIOUS COPY...ALSO --no-cache TO ALWAYS GET LATEST COPY
    wget --no-cache -O bluetooth-autoconnect.py https://raw.githubusercontent.com/taoteh1221/Bluetooth_Internet_Radio/main/bluetooth-autoconnect/bluetooth-autoconnect.py
    
    sleep 3
    
    chmod +x $BT_AUTOCONNECT_PATH
    
        
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
ExecStart=$PYTHON_PATH $BT_AUTOCONNECT_PATH
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
    if [ -f "$BT_AUTOCONNECT_PATH" ] && [ "$EUID" != 0 ] && [ ! -f /lib/systemd/system/btautoconnect.service ]; then
    $PYTHON_PATH $BT_AUTOCONNECT_PATH
    fi

}
# bluetooth_autoconnect function END


# Call bluetooth_autoconnect function
bluetooth_autoconnect


echo " "

if [ ! -f ~/radio ]; then 

ln -s $SCRIPT_LOCATION ~/radio

echo "${red}IMPORTANT INFORMATION:"
echo " "
echo "~/radio command is now a shortcut for ./$SCRIPT_NAME"
echo " "

echo "IF YOU MOVE $SCRIPT_LOCATION TO A NEW LOCATION,"
echo "you'll have to delete ~/radio and THIS SCRIPT WILL RE-CREATE IT.${reset}"
echo " "

else
echo "${cyan}PRO TIPS:"
echo " "
echo "Shortcut to this script: ${green}~/radio${cyan}"
echo " "
echo "Paired bluetooth reconnects (if disconnected) when you start a terminal session"
echo " "
echo "Auto-selecting multi-option examples ${red}(#MUST# BE IN QUOTES!)${cyan}:"
echo " "
echo "${green}~/radio \"6 2 b\"${cyan}"
echo "(runs pyradio default list in background)"
echo " "
echo "${green}~/radio \"7\"${cyan}"
echo "(stops pyradio background playing)"
echo " "
echo "${green}~/radio \"9 XX:XX:XX:XX:XX:XX\"${cyan}"
echo "(connect bluetooth device by mac address)"
echo " "
echo "${green}~/radio \"10 3\"${cyan}"
echo "(shows paired bluetooth devices)"
echo "${reset} "
fi


######################################


echo " "
echo "${yellow}Enter the NUMBER next to your chosen option:${reset}"
echo " "

OPTIONS="upgrade_check pulseaudio_install pulseaudio_status pulseaudio_fix pyradio_install pyradio_on pyradio_off bluetooth_scan bluetooth_connect bluetooth_devices bluetooth_remove sound_test volume_adjust syslog_logs journal_logs troubleshoot other_apps restart_computer exit"


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
        
            if [ $APP_VERSION != $LATEST_VERSION ]; then 
            
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
                    
                    mv -v --force BT-TEMP.bash bt-radio-setup.bash
                    
                    sleep 3
                
                    chmod +x bt-radio-setup.bash
                    				
                    sleep 1
                    				
                    INSTALL_LOCATION="${PWD}/bt-radio-setup.bash"
                    				
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
        
        # .local support and other needed components that require system-wide intallation
        apt install avahi-daemon screen alsa-utils expect rsyslog -y
        
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
            sleep 2
            sudo bash -c 'echo "load-module module-bluetooth-policy" >> /etc/pulse/default.pa'
            sleep 2
            fi        
        
            if [ "$PULSE_BT_DISCOVER" == "" ]; then 
            echo "${red}No bluetooth discover module loaded in pulseaudio, adding it now, please wait...${reset}"
            echo " "
            sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
            sleep 2
            sudo bash -c 'echo "load-module module-bluetooth-discover" >> /etc/pulse/default.pa'
            sleep 2
            fi        
        
            if [ "$PULSE_BT_CONNECT" == "" ]; then 
            echo "${red}No switch on connect module loaded in pulseaudio, adding it now, please wait...${reset}"
            echo " "
            sudo bash -c 'echo "### REQUIRED FOR AUTO-CONNECT NEW DEVICES!" >> /etc/pulse/default.pa'
            sleep 2
            sudo bash -c 'echo "load-module module-switch-on-connect" >> /etc/pulse/default.pa'
            sleep 2
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
    		
            # Call bluetooth_autoconnect function (this will re-initialize it, since we removed it)
            bluetooth_autoconnect
                    
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
                sleep 2
                sudo bash -c 'echo "load-module module-bluetooth-policy" >> /etc/pulse/default.pa'
                sleep 2
                NO_CONFIG_ISSUE=0
                else
                NO_CONFIG_ISSUE=1
                fi        
            
                if [ "$PULSE_BT_DISCOVER" == "" ]; then 
                echo "${red}No bluetooth discover module loaded in pulseaudio, fixing, please wait...${reset}"
                echo " "
                sudo bash -c 'echo "### REQUIRED FOR BLUETOOTH!" >> /etc/pulse/default.pa'
                sleep 2
                sudo bash -c 'echo "load-module module-bluetooth-discover" >> /etc/pulse/default.pa'
                sleep 2
                NO_CONFIG_ISSUE=0
                else
                NO_CONFIG_ISSUE=1
                fi         
            
                if [ "$PULSE_BT_CONNECT" == "" ]; then 
                echo "${red}No switch on connect module loaded in pulseaudio, fixing, please wait...${reset}"
                echo " "
                sudo bash -c 'echo "### REQUIRED FOR AUTO-CONNECT NEW DEVICES!" >> /etc/pulse/default.pa'
                sleep 2
                sudo bash -c 'echo "load-module module-switch-on-connect" >> /etc/pulse/default.pa'
                sleep 2
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
        
        sudo apt install pip mplayer -y
        
        # Install secondary python packages seperately, so any missing packages don't break installing the others
        sudo apt install python -y
        sudo apt install python3 -y
        
        sleep 5
        
        # Install pip packages system-wide with sudo
        sudo pip install --upgrade setuptools
        sudo pip install requests
        sudo pip install dnspython
        sudo pip install psutil
        
        # SPECIFILLY NAME IT WITH -O, TO OVERWRITE ANY PREVIOUS COPY...ALSO --no-cache TO ALWAYS GET LATEST COPY
        wget --no-cache -O install-pyradio.py https://raw.githubusercontent.com/coderholic/pyradio/master/pyradio/install.py
        
        sleep 2
        
        chmod +x install-pyradio.py
        
        $PYTHON_PATH install-pyradio.py --force
        
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
        SCREENS_DETACHED=$(screen -ls | grep Detached)
        if [ "$SCREENS_DETACHED" != "" ]; then
        echo $SCREENS_DETACHED | cut -d. -f1 | awk '{print $1}' | xargs kill
        fi
        
        echo " "
        echo "${yellow}Select 1 or 2 to choose whether to load a custom stations file, or the default one.${reset}"
        echo " "
        
        OPTIONS="custom_stations default_stations"
        
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

            echo " "
            echo "###########################################################################################"
            echo " "
            echo "We must activate pyradio config files for the first time, before continuing."
            echo " "
            echo "After letting pyradio run for a minute, please exit pyradio, and run this script again."
            echo " "
            echo "Afterwards, this notice will dissapear, and the normal pyradio options will show instead."
            echo " "
            echo "###########################################################################################"
            echo " "
            
            echo "${yellow} "
            read -n1 -s -r -p $'Press y to run pyradio first-time setup (or press n to cancel)...\n' key
            echo "${reset} "
        
                if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
            
    		    echo " "
    			echo "${cyan}Initiating pyradio first-time setup, please wait...${reset}"
                
                sleep 3
    			
    			$PYRADIO_PATH --play
            
                else
                echo "${green}pyradio first-time setup has been cancelled.${reset}"
                echo " "
                fi
                
            
            # OTHERWISE, LET USER CHOOSE WHICH WAY TO RUN PYRADIO
            else
        
         
            echo "${reset}${yellow} "
            read -n1 -s -r -p $'Press b to run pyradio in the background, or s to show on-screen...\n' key
            echo "${reset} "
    
                # Using $PYRADIO_PATH in case any old pip version messed up our bash config?
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
                export PYRADIO_PATH=$PYRADIO_PATH
                export PLAY_NUM=$PLAY_NUM
                export LOAD_CUSTOM_STATIONS=$LOAD_CUSTOM_STATIONS
                screen -dmS radio bash -c '${PYRADIO_PATH} --play ${PLAY_NUM} ${LOAD_CUSTOM_STATIONS}'
            
                elif [ "$key" = 's' ] || [ "$key" = 'S' ]; then
                
                $PYRADIO_PATH --play $LOAD_CUSTOM_STATIONS
                
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
        
        screen -ls | grep Detached | cut -d. -f1 | awk '{print $1}' | xargs kill
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
        
        
        $EXPECT_PATH -c "
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
        
        
        $EXPECT_PATH -c "
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
        
        elif [ "$opt" = "exit" ]; then
       
        echo " "
        echo "${green}Exiting...${reset}"
        echo " "
        
        exit
        
        break
        fi
        
        ##################################################################################################################
        ##################################################################################################################
        
       
done
# done options

exit


