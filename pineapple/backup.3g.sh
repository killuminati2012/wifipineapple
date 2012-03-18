#!/bin/sh
# --------------------------------------------------------
# 3G Connection Script for WiFi Pineapple. Does the thing.
# --------------------------------------------------------


# -----------------------------------------------------------
# Configure /etc/ppp/options with hard-coded working settings
# -----------------------------------------------------------
echo "
logfile /dev/null
noaccomp
nopcomp
nocrtscts
lock
maxfail 0" > /etc/ppp/options

# --------------------------------------------------------------------------------------------------
# Check for known usb modem vendor and product IDs then switch 'em from storage to serial modem mode
# --------------------------------------------------------------------------------------------------
echo "Searching for attached 3G Modems"
MODEM=$(lsusb | awk '{ print $6 }')
echo $MODEM

case "$MODEM" in

*19d2:1523*)    echo "ZTE MF591 (T-Mobile) detected. Attempting mode switch"
		logger "attempting usb_modeswitch on zte mf591"
		usb_modeswitch -v 19d2 -p 1523 -V 19d2 -P 1525 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10
		logger "attempting rmmod usbserial"
		rmmod usbserial
		sleep 3
		logger "attempting usbserial vendor=0x19d2 product=0x1525"
		insmod usbserial vendor=0x19d2 product=0x1525
		
		logger "uci reset network defaults"
		uci delete network.wan2
		uci set network.wan2=interface
		uci set network.wan2.ifname=ppp0
		uci set network.wan2.proto=3g
		uci set network.wan2.service=umts
		uci set network.wan2.device=/dev/ttyUSB0
		uci set network.wan2.username=internet
		uci set network.wan2.password=internet
		uci set network.wan2.defaultroute=1
		uci set network.wan2.apn=epc.tmobile.com
		uci commit network
		
		SWITCHED=1
		;;
*1410:5031*)	echo "Novatel MC760 (Virgin Mobile) detected. Attempting mode switch"
		usb_modeswitch -v 1410 -p 5031 -V 1410 -P 6002 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10
		rmmod usbserial
		sleep 3
		insmod usbserial vendor=-x1410 product=0x6002
		SWITCHED=1
		;;
*1410:5030*)	echo "Novatel MC760 (Ting) detected. Attempting mode switch"
		usb_modeswitch -v 1410 -p 5030 -V 1410 -P 6000 -M 5553424312345678000000000000061b000000020000000000000000000000 -n 1 -s 20
		sleep 10
		rmmod usbserial
		sleep 3
		insmod usbserial vendor=0x1410 product=0x6000
		SWITCHED=1
		;;
esac

# ---------------------------------------------------------------------------------------------
# If a modem has been activated then do some iptables magic to make WiFi <---> 3G routing happy
# ---------------------------------------------------------------------------------------------
#if ["$SWITCHED" == "1"]; then 
sleep 5
iptables -t nat -A POSTROUTING -s 172.16.42.0/24 -o 3g-wan2 -j MASQUERADE                                                   
iptables -A FORWARD -s 172.16.42.0/24 -o 3g-wan2 -j ACCEPT                                                                  
iptables -A FORWARD -d 172.16.42.0/24 -m state --state ESTABLISHED,RELATED -i 3g-wan2 -j ACCEPT
/etc/init.d/firewall disable
/etc/init.d/firewall stop
/etc/init.d/network restart
#fi
