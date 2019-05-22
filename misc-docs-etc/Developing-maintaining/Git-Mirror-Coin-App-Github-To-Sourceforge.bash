#!/bin/bash

#enter your build directory
cd /home/taoteh1221/Build/

#uncomment first run only
#git clone --bare --mirror https://github.com/taoteh1221/DFD_Cryptocoin_Values.git

#enter your repo directory
cd DFD_Cryptocoin_Values.git

#uncomment first run only
#git remote add sourceforge ssh://taoteh1221@git.code.sf.net/p/dfd-cryptocoin-values/code

#uncomment first run only
#git config remote.sourceforge.mirror true


########################################
#run every time to mirror original repo#
########################################

git fetch --quiet origin
git push --quiet sourceforge

git config alias.update-mirror '!git fetch -q origin && git push -q sourceforge'
git update-mirror

echo 'Sourceforge Mirror updated'