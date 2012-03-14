#!/bin/sh
while true
do
	echo "SSH: Connecting"
	logger "SSH: Connecting"
	
	#/bin/sh /www/pineapple/ssh-command.sh
	
	ssh -y -R 4255:localhost:22 root@173.214.173.151 -i /etc/dropbear/id_rsa 
	
	logger "SSH: Connection dropped. Taking a short nap then reconnecting"
	echo "SSH: Connection dropped. Taking a short nap then then reconnecting"
	sleep 15
done
