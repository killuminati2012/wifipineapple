<?php

if(isset($_GET[drop_caches])) {
	exec("sync");
	exec("echo 3 > /proc/sys/vm/drop_caches");
	echo "<pre>Executed sync; echo 3 > /proc/sys/vm/drop_caches</pre>";
}
?>
<html>
<head>
<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('navbar.php'); ?>

<table border="0" width="100%"><tr><td align="left" valign="top" width="80%">
<pre>

<?php

$cmd = "
	echo '<b>Up Time</b>\n'; 
	uptime; 

	echo '\n<b>Free Memory</b>\n'; 
	free; 
	echo \"\n<a href='resources.php?drop_caches'>echo 3 > /proc/sys/vm/drop_caches</a> <font color='orange'><small>Experimental</small></font>\n\";

	echo '\n<b>Disk Usage</b>\n'; 
	df -h; 
	
	echo '\n<b>USB</b>\n'; 
	lsusb; 
	
	echo '\n<b>Processes</b>\n'; 
	ps;";
	
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}

?>


</pre>
</td></tr></table>
</body>
</html>
