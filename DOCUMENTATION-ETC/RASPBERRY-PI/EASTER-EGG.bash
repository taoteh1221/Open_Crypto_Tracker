#!/bin/bash

# Copyright 2022 GPLv3, By Mike Kilday: Mike@DragonFrugal.com

# Fully automated setup of bluetooth and an internet radio player (PyRadio),
# on a headless RaspberryPi / DietPi, connecting to a stereo system's
# bluetooth receiver (bash script, chmod +x it to run):


export XAUTHORITY=~/.Xauthority 
				

# EXPLICITLY set any dietpi paths 
if [ -f /boot/dietpi/.version ]; then
PATH=/boot/dietpi:$PATH
fi


# Get date
DATE=$(date '+%Y-%m-%d')


# Bash's FULL PATH
BASH_PATH=$(which bash)
				

# Path to expect binary
EXPECT_PATH=$(which expect)
        
        
# pulseaudio's FULL PATH
PULSEAUDIO_PATH=$(which pulseaudio)


# Get logged-in username (if sudo, this works best with logname)
TERMINAL_USERNAME=$(logname)


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



echo " "
echo "${yellow}Enter the NUMBER next to your chosen option:${reset}"
echo " "

OPTIONS="pulseaudio_install pulseaudio_start_restart pulseaudio_fix pulseaudio_status pyradio_install pyradio_on pyradio_off bluetooth_mac_address bluetooth_connect bluetooth_test volume_adjust troubleshoot skip"

# start options
select opt in $OPTIONS; do

        ##################################################################################################################
        ##################################################################################################################
        
        if [ "$opt" = "pulseaudio_install" ]; then
        
        
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
                echo "We must ENABLE BLUETOOTH in DietPi OS before continuing."
                echo " "
                echo "This script will now launch dietpi-config."
                echo " "
                echo "In ADVANCED OPTIONS, you will need to ENABLE BLUETOOTH."
                echo " "
                echo "You CAN SAFELY REBOOT if needed, and RUN THIS SCRIPT AGAIN AFTERWARDS."
                
                echo "${yellow} "
                read -n1 -s -r -p $'Press y to run dietpi-config (or press n to cancel)...\n' key
                echo "${reset} "
        
                    if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
                
    				echo " "
    				echo "${cyan}Initiating dietpi-config, please wait...${reset}"
                    sleep 3
    				dietpi-config
                
                    else
                    
                    echo "${cyan}dietpi-config bluetooth enabling has been cancelled.${reset}"
                    echo " "
                
                    fi
				
				fi
        
        				
        echo " "
        
        echo "${green}Installing PulseAudio and other required components, please wait...${reset}"
        echo " "
        
        # .local support and other needed components that require system-wide intallation
        apt install avahi-daemon pip screen mplayer alsa-utils expect -y
        
        apt install pulseaudio* -y
        
        sleep 5
        
        usermod -a -G lp $TERMINAL_USERNAME
        
        usermod -a -G pulse-access $TERMINAL_USERNAME
        
        usermod -a -G bluetooth $TERMINAL_USERNAME


# Don't nest / indent, or it could malform the settings            
read -r -d '' AUDIO_STARTUP <<- EOF
\r
[Unit]
Description=PulseAudio system server
# DO NOT ADD ConditionUser=!root
\r
[Service]
Type=notify
ExecStart=pulseaudio --system --daemonize=no --realtime --disallow-exit --disallow-module-loading --log-target=journal
Restart=on-failure
\r
[Install]
WantedBy=multi-user.target
[Unit]
Description=pulseaudio service
Wants=graphical.target
After=graphical.target
\r
EOF

		# Setup service to run at boot, AND START NOW
		
		# Stop / remove any previous instance first
		# (so we are allowed to modify /lib/systemd/system/pulseaudio.service)
		systemctl stop pulseaudio.service
		
		sleep 5
		
		rm /lib/systemd/system/pulseaudio.service > /dev/null 2>&1
		
		sleep 2
		
		# reload services
		systemctl daemon-reload
        
        sleep 2
		
	    
	    # Setup / re-setup latest service config in this script
		echo -e "$AUDIO_STARTUP" > /lib/systemd/system/pulseaudio.service
		
		chown root:root /lib/systemd/system/pulseaudio.service
		
		sleep 2
		
		# start NOW
		systemctl --system enable --now pulseaudio.service
		
		sleep 2
		
		# start AT BOOT
		systemctl enable pulseaudio.service
		
		sleep 2
        
        PULSEAUDIO_STATUS=$(systemctl status pulseaudio.service)
		
		sleep 2
        
        echo " "
        echo "${yellow}PulseAudio status:${reset}"
        echo "${cyan} "
                
        echo "$PULSEAUDIO_STATUS"
        echo "${reset} "
        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pulseaudio_start_restart" ]; then
        
        
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
                    
            echo "${green}Starting / restarting pulseaudio, please wait...${reset}"
            echo " "
                
    		# restart
    		systemctl restart pulseaudio.service
            
            sleep 5
                    
            PULSEAUDIO_STATUS=$(sudo systemctl status pulseaudio.service)
            
            sleep 2
                    
            echo "${yellow}PulseAudio status:${reset}"
            echo "${cyan} "
                    
            echo "$PULSEAUDIO_STATUS"
            echo "${reset} "
                    
            echo "${red}INACTIVE / DEAD doesn't mean it won't start when needed? Currently looking into this.${reset}"
            echo " "
            
            else
            
            echo "PulseAudio not found, must be installed first, please re-run this script and choose that option."
            echo " "
                    
            fi

        
        break
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "pulseaudio_status" ]; then
        
        
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
                    
            PULSEAUDIO_STATUS=$(sudo systemctl status pulseaudio.service)
            
            sleep 2
                    
            echo "${yellow}PulseAudio status:${reset}"
            echo "${cyan} "
                    
            echo "$PULSEAUDIO_STATUS"
            echo "${reset} "
                    
            echo "${red}INACTIVE / DEAD doesn't mean it won't start when needed? Currently looking into this.${reset}"
            echo " "
            
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
                    
            
            rm -r ~/.config/pulse.old > /dev/null 2>&1
            mv ~/.config/pulse/ ~/.config/pulse.old-$DATE > /dev/null 2>&1
                    
            echo "${green}Attempted fixes have been completed.${reset}"
            echo " "
                    
            echo "${red}PULSEAUDIO MUST BE RESTARTED, please re-run this script and choose that option.${reset}"
            echo " "
            
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
        
            if [ "$EUID" -ne 0 ] || [ "$TERMINAL_USERNAME" == "root" ]; then 
             echo "${red}Please run #WITH# 'sudo' PERMISSIONS.${reset}"
             echo " "
             echo "${cyan}Exiting...${reset}"
             echo " "
             exit
            fi
        
        ######################################
       
        
        echo " "
        echo "${green}Installing PyRadio, please wait...${reset}"
        echo " "
        
        pip install pyradio
        
        sleep 2
        
        echo " "
        echo "${green}PyRadio installed.${reset}"
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
       
       
        echo "${yellow} "
        read -n1 -s -r -p $'Press b to run radio in the background, or s to show on-screen...\n' key
        echo "${reset} "

            if [ "$key" = 'b' ] || [ "$key" = 'B' ]; then
        
            echo "${yellow} "
            read -p 'Enter playlist number: ' PLAY_NUM
            echo "${reset} "
            
            echo " "
            echo "${green}Tuning radio app to playlist ${PLAY_NUM}...${reset}"
            echo " "
            
            export PLAY_NUM=$PLAY_NUM
            screen -dm -S radio bash -c 'pyradio --play ${PLAY_NUM}'
        
            elif [ "$key" = 's' ] || [ "$key" = 'S' ]; then
            
            pyradio --play
            
            echo " "
            echo "${cyan}Exited radio app.${reset}"
            echo " "
        
            else
            
            echo "${cyan}Opening radio app cancelled.${reset}"
            echo " "
        
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
        echo "${green}Turning radio app OFF...${reset}"
        echo " "
        
        screen -ls | grep Detached | cut -d. -f1 | awk '{print $1}' | xargs kill
        exit
        
        break    
        
        ##################################################################################################################
        ##################################################################################################################
        
        elif [ "$opt" = "bluetooth_mac_address" ]; then
        
        
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
        echo "We must find out what the mac address of your bluetooth receiver is."
        echo " "
        echo "Put your bluetooth receiver in pairing mode, and get ready to write down what you see as it's mac address (format: XX:XX:XX:XX:XX:XX)."
        echo " "
        echo "When you are done, hold down the 2 keys Ctrl+c at the same time, until you exit this script."
        
        echo "${yellow} "
        read -n1 -s -r -p $'Press y to run the bluetooth scan (or press n to cancel)...\n' key
        echo "${reset} "

            if [ "$key" = 'y' ] || [ "$key" = 'Y' ]; then
        
            bluetoothctl scan on
            echo " "
            
            echo " "
            echo "${cyan}Bluetooth scanning complete.${reset}"
            echo " "
        
            else
            
            echo "${cyan}Bluetooth scanning cancelled.${reset}"
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
        read -p "${yellow}Enter your bluetooth receiver mac address here (format: XX:XX:XX:XX:XX:XX):${reset} " BLU_MAC
        echo " "
        
        bluetoothctl power on
        echo " "
        
        echo "${cyan}Scanning for device $BLU_MAC, please wait about 60 seconds...${reset}"
        echo " "
        
        
        $EXPECT_PATH -c "
        set timeout 45
        spawn bluetoothctl
        send -- \"scan on\r\"
        expect \"$BLU_MAC\"
        send -- \"trust $BLU_MAC\r\"
        expect \"trust succeeded\"
        send -- \"pair $BLU_MAC\r\"
        expect \"Pairing successful\"
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
        
        elif [ "$opt" = "bluetooth_test" ]; then
        
        
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
        echo "${green}Testing with 'Front Center' sound test... ${red}If you did NOT hear these words on your bluetooth speaker, then there is a problem somewhere.${reset}"
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
        
        elif [ "$opt" = "skip" ]; then
       
        echo " "
        echo "${green}Skipping...${reset}"
        echo " "
        
        exit
        
        break
        fi
        
        ##################################################################################################################
        ##################################################################################################################
        
       
done
# done options

exit


