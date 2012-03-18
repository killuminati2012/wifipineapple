<html>
<head>
<title>Pineapple Control Center</title>
<!--<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">-->
<script type="text/javascript" src="ajax.js"> </script>
<script type="text/javascript" src="logtail.js"> </script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green" onload="getLog('start');">

<?php require('navbar.php'); ?>
<pre>

<table border="0" width="100%"><tr><td valign="top" align="left" width="350">
<b>Services</b><br />
<?php

$iswlanup = exec("ifconfig wlan0 | grep UP | awk '{print $1}'");
if ($iswlanup == "UP") {
echo "&nbsp;Wireless  <font color=\"lime\"><b>enabled</b></font>.<br />";
} else { echo "&nbsp;Wireless  <font color=\"red\"><b>disabled</b></font>.<br />"; }

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

/*$isngrepup = exec("ps auxww | grep ngrep | grep -v -e \"grep ngrep\" | awk '{print $1}'");
if ($isngrepup != "") {
echo "&nbsp;&nbsp;&nbsp;&nbsp;ngrep  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"stopngrep.php\"><b>Stop</b></a>";
} else { echo "&nbsp;&nbsp;&nbsp;&nbsp;ngrep  <font color=\"red\"><b>disabled</b></font>. | <a href=\"startngrep.php\"><b>Start</b></a> | <a href=\"config.php#ngrep\"><b>Edit</b></a><br/>"; }
*/

if (exec("grep 3g.sh /etc/rc.local") != ""){                                                         
echo "3G bootup  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"3g.php?disable&disablekeepalive&goback\"><b>Disable</b></a><br />";
} else { echo "3G bootup <font color=\"red\"><b>disabled</b></font>. | <a href=\"3g.php?enable&goback\"><b>Enable</b></a><br />"; }              
                                                                                                                                                        
if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {                                                                              
echo "3G redial <font color='red'><b>disabled</b></font>. | <a href='3g.php?enablekeepalive&enable&goback'><b>Enable</b></a><br />";             
} else { echo "3G redial <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='3g.php?disablekeepalive&goback'><b>Disable</b></a><br />"; } 

if (exec("ps aux | grep [s]sh | grep -v -e ssh.php") == "") {                                                                                             
echo "&nbsp; &nbsp; &nbsp; SSH <font color=\"red\"><b>offline</b></font>. &nbsp;| <a href=\"ssh.php?connect\"><b>Connect</b></a><br /><br />";        
} else {                                                                                                                                                 
echo "&nbsp; &nbsp; &nbsp; SSH <font color=\"lime\"><b>online</b></font>. &nbsp; | <a href=\"ssh.php?disconnect\"><b>Disconnect</b></a><br /><br />";
} 

                                                                                                                                                        
echo "<br/><b>Interfaces</b><br />";

echo "&nbsp;PoE / LAN Port: " . exec("ifconfig br-lan | grep inet | awk '{print $2}' | cut -c6-16") . "<br />";
echo "&nbsp;&nbsp; USB 3G Modem: " . exec("ifconfig 3g-wan2 | grep inet | awk '{print $2}' | cut -c6-16") . "<br />";
echo "&nbsp;WAN / LAN Port: " . exec("ifconfig eth1 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'") . "<br />";
echo "Public Internet: "; 
if (isset($_GET[revealpublic])) { 
	echo exec("wget -qO- http://whatismyip.org") . "<br />"; 
} else { 
	echo "<a href=\"index.php?revealpublic\">reveal public ip</a><br />"; 
}

?>

</td><td valign="top" align="left" width="*">



<pre>
<a href="#" onclick="getLog('start');return false"><b>Resume Log</b></a> | <a href="#" onclick="stopTail();return false"><b>Pause Log</b></a> | <?php if (isset($_GET[report])) { echo "<a href='index.php'><b>Dismiss Detailed Report</b></a>"; } else { echo "<a href='index.php?report'><b>Generate Detailed Report</b></a>"; } ?><br />

<?php
if (isset($_GET[report])) {
	echo "<br /><b>Detailed Report</b> &nbsp; &nbsp; <small><font color='gray'>CPU Intensive. Do not re-run reports in rapid succession</font></small><br /><br />";
	$cmd="/www/pineapple/karmaclients.sh";
	exec("$cmd 2>&1", $output);                                                                                                                                     
	foreach($output as $outputline) {
		 echo ("$outputline\n");         
	 }
} else {

	echo "<div id='log'>Karma Log:</div>";

}
 
?>


</pre>
</td></tr></table>
</pre><!-- http://www.youtube.com/watch?v=KqL_nsSl_Fs //easter egg -->
</body>
</html>
