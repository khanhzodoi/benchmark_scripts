#!/bin/bash

passmark_link=https://www.passmark.com/downloads/pt_linux_x64.zip
iozone_link=http://www.iozone.org/src/current/iozone-3-491.x86_64.rpm
passmark_dir="/passmark_lib"
log_file_name="prescript.log"
centos_ver="`cat /etc/redhat-release 2> /dev/null | cut -d " " -f 4 | awk -F[.] '{print $1}'`"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
PLAIN='\033[0m'

# check root
[[ $EUID -ne 0 ]] && echo -e "${RED}Error:${PLAIN} This script must be run as root!" && exit 1

## Install wget, unzip, fio
if [ ! -e '/usr/bin/wget' ] || [ ! -e '/usr/bin/fio' ] || [ ! -e '/usr/bin/unzip' ] || [ ! -e '/usr/bin/git' ]
then
    yum clean all > /dev/null 2>&1 && yum install -y epel-release > /dev/null 2>&1 && yum install -y wget fio unzip git > /dev/null 2>&1 || (  apt-get update > /dev/null 2>&1 && apt-get install -y wget fio unzip git > /dev/null 2>&1 )
fi

## Install passmark
PWD=`pwd`
wget -q $passmark_link -O pt_linux_x64.zip > /dev/null 2>&1
unzip -d $PWD pt_linux_x64.zip > /dev/null 2>&1

yum install ncurses-compat-libs -y > /dev/null 2>&1 || sudo apt-get install libncurses5 -y > /dev/null 2>&1

# Make some changes to lib
if [[ $centos_ver == "7" ]]
then
    mkdir -p $passmark_dir
    ls -l /lib64/libstdc++.so.6 > $passmark_dir/vagranlib.bk
    cd $passmark_dir && git clone https://github.com/DevopsRizwan/requiredlibbin.git > /dev/null 2>&1
    cp "$passmark_dir/requiredlibbin/libstdc++.so.6.0.20" /lib64/libstdc++.so.6.0.20
    chmod +x /lib64/libstdc++.so.6.0.20
    unlink /lib64/libstdc++.so.6
    ln -s  /lib64/libstdc++.so.6.0.20 /lib64/libstdc++.so.6

    cd $PWD > /dev/null 2>&1
    echo "Created $passmark_dir folder to download lib for running passmark" >> $log_file_name
    echo "Backup old soft link of /lib64/libstdc++.so.6 at $passmark_dir/vagranlib.bk file." >> $log_file_name
    echo "===========================" >> $log_file_name
fi
cd $PWD > /dev/null 2>&1
cp pt_linux_x64 /usr/bin/pt_linux_x64
rm -rf ./pt_linux_x64 pt_linux_x64.zip

## Install iozone
if [ ! -e '/opt/iozone/bin/iozone' ];
then
    wget $iozone_link -O iozone-3-491.x86_64.rpm > /dev/null 2>&1 &&  sudo rpm -Uvh ./iozone-3-491.x86_64.rpm > /dev/null 2>&1 || apt-get install iozone3 -y > /dev/null 2>&1
    ln -s /opt/iozone/bin/iozone /usr/bin/iozone > /dev/null 2>&1
    rm -rf iozone-3-491.x86_64.rpm

fi

echo "Install Done."