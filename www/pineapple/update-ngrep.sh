#!/bin/sh
while true
do
#echo '' > /www/pineapple/ngrep-clean.log
cat /tmp/ngrep.log | grep -e "Host:" -e "Cookie:" >> /www/pineapple/ngrep-clean.log
echo '' > /tmp/ngrep.log
sleep 10
done
