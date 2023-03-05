#!/bin/bash


# COMMAND PARAMETERS (USAGE):
#
# FIRST RUN (clones the GITHUB repository to a local directory on this machine)
# ./SCRIPT_PATH first_run
#
# Delete a branch named 'v5.xx' in remote / local MIRROR:
# ./SCRIPT_PATH v5.xx
#
# Delete any ALREADY-MERGED branches in remote / local MIRROR:
# ./SCRIPT_PATH merged


######################################
# START configs
######################################


# Local build directory
local_build_dir="/home/taoteh1221/Compiling"


# Name of main / master branch in git repository on GITHUB
github_main_branch="main"


# Name of remote mirror repository
# (CAN BE ANYTHING, PREFERABLY DESCRIPTIVE THOUGH)
mirror_name="sourceforge"


# GITHUB git repository remote address
github_remote_repo="https://github.com/taoteh1221/DFD_Cryptocoin_Values.git"


# MIRROR git repository remote address
mirror_remote_repo="ssh://taoteh1221@git.code.sf.net/p/dfd-cryptocoin-values/code"


######################################
# END configs
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


cd $local_build_dir


# first run only (clone GITHUB repo LOCALLY)
if [ "$1" == "first_run" ]; then
git clone --bare --mirror $github_remote_repo
sleep 1
fi


local_repo_dir=$(echo $github_remote_repo | sed 's:.*/::')
cd $local_repo_dir

sleep 1

git remote add $mirror_name $mirror_remote_repo > /dev/null 2>&1


# Delete any branch(es) included in CLI parameters
if [ "$1" != "" ] && [ "$1" != "first_run" ]; then

# Deleting remote branches is NOT compatible with the 'mirror' attribute set to true
git config --unset remote.${mirror_name}.mirror > /dev/null 2>&1

     
     # Delete only already-merged branches
     if [ "$1" == "merged" ]; then
     
     echo " "
     echo "Deleting all local / remote branches that have already been merged on ${mirror_name} mirror, please wait..."
     echo " "
     
     # Remote
     mirror_remote_branches=$(git branch -r --merged ${mirror_name}/${github_main_branch} | grep -v ${github_main_branch})

     
          if [ "$mirror_remote_branches" != "" ]; then
          
          echo $mirror_remote_branches | grep "${mirror_name}/" | cut -d "/" -f 2- | xargs -n 20 git push ${mirror_name} --delete
     
          echo " "
          
          else
          
          echo " "
          echo "${yellow}No already-merged remote mirror branches found${reset}"
          echo " "
          
          fi
          
          
     # Local
     mirror_local_branches=$(git branch --merged | grep -v ${github_main_branch})
     
     
          if [ "$mirror_local_branches" != "" ]; then
          
          echo $mirror_local_branches | xargs git branch -D
     
          echo " "
          
          else
          
          echo " "
          echo "${yellow}No already-merged local mirror branches found${rest}"
          echo " "

          fi
          
     
     # Delete a specific branch
     else
     
     echo " "
     echo "${green}Deleting local / remote branch '${1}' on ${mirror_name} mirror, please wait...${reset}"
     echo " "
     
     # Remote
     mirror_remote_branches=$(git branch -r | grep "${mirror_name}/$1")

     
          if [ "$mirror_remote_branches" != "" ]; then
          
          git push ${mirror_name} --delete $1
          
          echo " "
          
          else
          
          echo " "
          echo "${yellow}No remote mirror branch named '${1}' found${reset}"
          echo " "
          
          fi
     
     
     # Local
     mirror_local_branches=$(git branch | grep $1)
     
     
          if [ "$mirror_local_branches" != "" ]; then
          
     
          git branch -D $1
          
          echo " "
          
          else
          
          echo " "
          echo "${yellow}No local mirror branch named '${1}' found${reset}"
          echo " "

          fi
     

     fi


# Mirror the github repo
else

# Make sure the mirror attribute is set
git config remote.${mirror_name}.mirror true > /dev/null 2>&1

git fetch --quiet origin

git push --quiet ${mirror_name}

git config alias.update-mirror "!git fetch -q origin && git push -q ${mirror_name}"

git update-mirror

fi



