#!/bin/bash

printf "\e[32mConfiguring SAMBA share 'openeyes'. NOTE: This script should only be run once, otherwise it will mess up your /etc/samba/smb.conf\e[0m"

sudo apt update
sudo apt install samba -y

echo -e "vagrant\nvagrant" | sudo smbpasswd -s -a root

sudo echo "
[openeyes]
        path = /var/www/
        valid users = vagrant root
        admin users = root vagrant
        write list = root vagrant
        force user = root
        force group = root
        group = root
        read only = No

" >> /etc/samba/smb.conf

sudo service smbd restart
sudo testparm -s

printf "\e[32m
************************************************

Added samba share \\\\\\openeyes.vm\\openeyes

Connect as:-
User: root
Password: vagrant

************************************************\e[0m"
