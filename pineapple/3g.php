<?php 

if (isset($_GET[force])) {
echo "<pre>Attempting 3G connection. This may take a minute and require manual refresh of this page. Check <a href=\"logs.php\"><b>Logs</b></a> for details.</pre>";
exec("echo /www/pineapple/3g.sh | at now");}

if (isset($_GET[enablekeepalive])) {
	if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {
		exec("echo '*/5 * * * * /www/pineapple/3g-keepalive.sh' >> /etc/crontabs/root");
		echo "<pre>3G Keep Alive script added to Cron Jobs. Be sure to enable Cron Daemon from <a href='jobs.php'><b>Jobs</b></a>.</pre>";
	} else {
		echo "<pre>3G Keep Alive script already in Crontab. Check <a href='jobs.php'><b>Jobs</b></a> to troubleshoot.</pre>";
	}
}

if (isset($_GET[disablekeepalive])) {
	exec("sed -i '/3g-keepalive.sh/d' /etc/crontabs/root");
	echo "<pre>3G Keep Alive script removed from Cron Jobs. See <a href='jobs.php'><b>Jobs</b></a></pre>";
}



$auto3g = (exec("grep 3g.sh /etc/rc.local"));

if (isset($_GET[enable])) {

	if (exec("grep 3g.sh /etc/rc.local") == "") {
		exec("sed -i '/exit 0/d' /etc/rc.local");
		exec("echo /www/pineapple/3g.sh >> /etc/rc.local");
		exec("echo exit 0 >> /etc/rc.local");
		echo "<pre>3G on boot enabled.</pre>";
		$auto3g = "true";
	} else {
		echo "<pre>3G on boot already enabled in rc.local, no changes there.</pre>";
	}
}                              

if (isset($_GET[disable])) {
	exec("sed -i '/3g.sh/d' /etc/rc.local");
	echo "<pre>3G on boot disabled.</pre>";
	$auto3g = "";                  
}
?>

<html>
<head>
<?php if(isset($_GET[goback])){ 
echo "<meta http-equiv=\"refresh\" content=\"0; url=/pineapple/\">";
} ?>

<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('navbar.php'); ?>
<pre>

<?php
$filename =$_POST['filename'];
$newdata = $_POST['newdata'];

if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
 $fw = fopen($filename, 'w') or die('Could not open file!');
 $fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
 fclose($fw);
 echo "Updated " . $filename . "<br /><br />";
} 


if ($auto3g != ""){
echo "3G on boot is currently <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"3g.php?disable&disablekeepalive\"><b>Disable</b></a><br />";
} else { echo "3G on boot is currently <font color=\"red\"><b>disabled</b></font>. | <a href=\"3g.php?enable\"><b>Enable</b></a><br />"; }

if (exec("grep 3g-keepalive.sh /etc/crontabs/root") == "") {
echo "Keep Alive is currently <font color='red'><b>disabled</b></font>. | <a href='3g.php?enablekeepalive&enable'><b>Enable</b></a><br />";
} else { echo "Keep Alive is currently <font color='lime'><b>enabled</b></font>.&nbsp; | <a href='3g.php?disablekeepalive'><b>Disable</b></a><br />"; }

echo "<br /><a href=\"3g.php?force\"><b>Force</b></a> connection now. This executes the below 3G script now, potentially saving a reboot. <font color='orange'><small>Experimental</small></font><br /><br />";

echo "<b>USB Connections:</b> <a href='3g.php'><small>refresh</small></a><br />";
echo exec("lsusb"); 

?>
<pre>


<?php
$filename = "/www/pineapple/3g.sh";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>Mobile Broadband Configuration:</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='140' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/www/pineapple/3g.sh'>
<br><input type='submit' value='Update 3G script'>
</form>";
?>

<pre>
<a name="ifconfig">
<b>Interface Configuration:</b> <a href='3g.php#ifconfig'><small>refresh</small></a><br />
<? $cmd="ifconfig";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}
?>


<b>Help</b><br />
If enabled this script executes on boot. It can also be forced.
Mobile Broadband requires a compatible USB 3G / 4G modem.

Connection is two phase. First the modem must be actived, then the network configuration sets paramaters used by pppd and gchat.
Since most 3G / 4G modems identify as CD-ROM or USB Storage devices an activation script, typically using usb_modeswitch or sdparm, is executed.
Activation forces the USB device to reveal its modem component.
The modem component is configured as a USB Serial device, typically /dev/ttyUSB0, which is addressed by the network configuration.
Network Configuration specifies the interface as WAN2. GSM and CDMA protocols are supported. ifconfig typically shows the interface as 3g-wan2.
The pppd is responsible for making the point-to-point connection with the USB Serial device. Configuration in /etc/ppp/options
Comgt is responsible for talking to the modem. EVDO and 3G (GSM) modem commands are specified in /etc/chatscripts/
For the most part neither of these files need modification. 
Support outside of the listed supported modems is experimental, though help can be found on the Jasager forums. Most USB modems share similar configuration.
Updated 3G connection scripts with additional modem support can be found at wifipineapple.com
Additionally a 3G-KeepAlive script is available, which periodically checks for Internet connectivity and re-establishes if necessary.
This is done by attempting to send three pings to 8.8.8.8. If none are successful "ifup wan" is executed.

</td></tr>
<tr><td>

</pre>
</body>
</html>
