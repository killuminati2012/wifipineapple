#!/bin/sh
while true
do grep KARMA: /tmp/karma.log | uniq | sed -e 's/KARMA: //' | grep -v -e Probe > associations.log
sleep 10
done
