#!/bin/bash

cd /home/taoteh1221/Build/
rm -rf DFD_Cryptocoin_Values.git

git clone --bare --mirror https://github.com/taoteh1221/DFD_Cryptocoin_Values.git
cd DFD_Cryptocoin_Values.git

#libsecret / gnome-keyring on ubuntu?
#git config credential.helper store

git remote add sourceforge ssh://taoteh1221@git.code.sf.net/p/dfd-cryptocoin-values/code
git config remote.sourceforge.mirror true

git fetch --quiet origin
git push --quiet sourceforge

git config alias.update-mirror '!git fetch -q origin && git push -q sourceforge'
git update-mirror

echo 'Mirror updated'