#!/bin/sh
while true
do cat /www/pineapple/urlsnarf.log | awk {'print $1 $8'} | sed 's,http://, ,' | sed 's/.lan//' | sed 's%/.*$%%' | uniq > /www/pineapple/urlsnarf-clean.log
sleep 10
done
