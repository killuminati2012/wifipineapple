<html>
<head>
<title>Pineapple Control Center</title>
<!--<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">-->
<script  type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
var auto_refresh = setInterval(
function ()
{
$('#arp').load('arp').fadeIn("slow");
}, 4000); // refresh in milliseconds

var auto_refresh = setInterval(
function ()
{
$('#karma').load('associations.log').fadeIn("slow");
}, 4000);

var auto_refresh = setInterval(
function ()
{
$('#urlsnarf').load('urlsnarf-clean.log').fadeIn("slow");
}, 4000);

var auto_refresh = setInterval(
function ()
{
$('#dhcp').load('dhcp').fadeIn("slow");
}, 4000);

var auto_refresh = setInterval(
function ()
{
$('#ngrep').load('ngrep-clean.log').fadeIn("slow");
}, 4000);

var auto_refresh = setInterval(
function ()
{
$('#phish').load('phish.log').fadeIn("slow");
}, 4000);

</script>

</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('navbar.php'); ?>
<pre>

<table border="0" width="100%"><tr><td valign="top" align="left" width="50%">

<?php

$iswlanup = exec("ifconfig wlan0 | grep UP | awk '{print $1}'");
if ($iswlanup == "UP") {
echo "Interface  <font color=\"lime\"><b>enabled</b></font>.<br />";
} else { echo "Interface  <font color=\"red\"><b>disabled</b></font>.<br />"; }

if ( exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_state | tail -1") == "ENABLED" ){
$iskarmaup = true;
}
if ($iskarmaup != "") {
echo "MK4 Karma  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"stopkarma.php\"><b>Stop</b></a><br />";
} else { echo "MK4 Karma  <font color=\"red\"><b>disabled</b></font>. | <a href=\"startkarma.php\"><b>Start</b></a> <br />"; }

$autoKarma = ( exec("if grep -q 'hostapd_cli -p /var/run/hostapd-phy0 karma_enable' /etc/rc.local; then echo 'true'; fi") );
if ($autoKarma != ""){
echo "Autostart  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"autoKarmaStop.php\"><b>Stop</b></a><br />";
} else { echo "Autostart  <font color=\"red\"><b>disabled</b></font>. | <a href=\"autoKarmaStart.php\"><b>Start</b></a><br />"; }

$cronjobs = ( exec("ps -all | grep [c]ron"));
if ($cronjobs != ""){
echo "Cron Jobs <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"jobs.php?stop&goback\"><b>Stop</b></a><br />";
} else { echo "Cron Jobs <font color=\"red\"><b>disabled</b></font>. | <a href=\"jobs.php?start&goback\"><b>Start</b></a> | <a href=\"jobs.php\"><b>Edit</b></a><br />"; }

$isurlsnarfup = exec("ps auxww | grep urlsnarf.sh | grep -v -e grep");
if ($isurlsnarfup != "") {
echo "URL Snarf  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"stopurlsnarf.php\"><b>Stop</b></a><br />";
} else { echo "URL Snarf  <font color=\"red\"><b>disabled</b></font>. | <a href=\"starturlsnarf.php\"><b>Start</b></a><br />"; }

$isdnsspoofup = exec("ps auxww | grep dnsspoof.sh | grep -v -e grep");
if ($isdnsspoofup != "") {
echo "DNS Spoof  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"stopdnsspoof.php\"><b>Stop</b></a><br />";
} else { echo "DNS Spoof  <font color=\"red\"><b>disabled</b></font>. | <a href=\"startdnsspoof.php\"><b>Start</b></a> | <a href=\"config.php#spoofhost\"><b>Edit</b></a><br/>"; }

$isngrepup = exec("ps auxww | grep ngrep | grep -v -e \"grep ngrep\" | awk '{print $1}'");
if ($isngrepup != "") {
echo "&nbsp;&nbsp;&nbsp;&nbsp;ngrep  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"stopngrep.php\"><b>Stop</b></a>";
} else { echo "&nbsp;&nbsp;&nbsp;&nbsp;ngrep  <font color=\"red\"><b>disabled</b></font>. | <a href=\"startngrep.php\"><b>Start</b></a> | <a href=\"config.php#ngrep\"><b>Edit</b></a><br/>"; }

if (exec("grep 3g.sh /etc/rc.local") != ""){                                                         
echo "3G bootup  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"3g.php?disable&disablekeepalive&goback\"><b>Disable</b></a><br />";
} else { echo "3G bootup <font color=\"red\"><b>disabled</b></font>. | <a href=\"3g.php?enable&goback\"><b>Enable</b></a><br />"; }              
                                                                                                                                                        
if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {                                                                              
echo "3G redial <font color='red'><b>disabled</b></font>. | <a href='3g.php?enablekeepalive&enable&goback'><b>Enable</b></a><br />";             
} else { echo "3G redial <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='3g.php?disablekeepalive&goback'><b>Disable</b></a><br />"; } 
                                                                                                                                                        

?>

</td><td valign="top" align="right" width="50%">
<?php
if ( exec("ifconfig mon.wlan0 | grep HWaddr | awk {'print $1'}") == "mon.wlan0") {
echo "Airmon-ng  <font color='lime'><b>enabled</b></font>. |"; } else { 
echo "Airmon-ng  <font color='red'><b>disabled</b></font>. | <a href='startairmon.php'><b>Start</b></a>";}
?>

<br /><br /><form action='deauth.php' method= 'post' >
<input type="text" name="deauthtarget" size="30" style='font-family:courier; font-weight:bold; background-color:black; color:gray; border-style:dotted;' value="BSSID" onFocus="if(this.value == 'BSSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'BSSID';}"><input type="text" name="deauthtimes" size="7" style='font-family:courier; font-weight:bold; background-color:black; color:gray; border-style:dotted;'  value="5" onFocus="if(this.value == '5') {this.value = '';}" onBlur="if (this.value == '') {this.value = '5';}"><br />
<input type="text" name="deauthtargetClient" size="30" style='font-family:courier; font-weight:bold; background-color:black; color:gray; border-style:dotted;' value="ClientMAC" onFocus="if(this.value == 'ClientMAC') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'ClientMAC';}"><input type='submit' value='Deauth'></form>
</td></tr></table>


<table border="0" width="100%"><tr><td align="left" valign="top" width="50%">
<b>ARP Log</b>
<pre><div id="arp"> </div></pre>
</td><td align="left" valign="top" width="50%">
<b>DHCP Log</b>
<pre><div id="dhcp"> </div></pre>
</td></tr></table>


<table border="0" width="100%"><tr><td align="left" valign="top" width="50%">
<b>Association Log</b>
<pre><div id="karma"> </div></pre>
</td><td align="left" valign="top" width="50%">
<b>URL Snarfer</b>
<pre><div id="urlsnarf"> </div></pre>
</td></tr></table>

<table border="0" width="100%"><tr><td align="left" valign="top" width="100%">
<b>Network Grepper</b>
<pre><div id="ngrep"></pre>
</td></tr><tr><td valign="left" align="top" width="100%">
<b>Phishing Net</b>
<pre><div id="phish"></pre>
</td></tr></table>

<!--
<font color="white">
                    \               
                  \  \          
                \  \  \</font><font color="green">              
<,  .v ,  // </font><font color="white">) ) )  )  )</font><font color="green">                  
 \\; \// //     </font><font color="white">/  /  /</font><font color="green">                          
  ;\\|||//;       </font><font color="white">/  /</font><font color="yellow">
 ,'<\/><\/`         </font><font color="white">/</font><font color="yellow">                    
,.`X/\><\\>`                      
;>/>><\\><\/`                        
|<\\>>X/<>/\|
`<\/><\/><\\;                            
 '/\<>/\<>/'                       
   `<\/><;`</font><font color="white">wifi_pineapple</font>
-->

</pre><!-- http://www.youtube.com/watch?v=KqL_nsSl_Fs //easter egg -->
</body>
</html>
