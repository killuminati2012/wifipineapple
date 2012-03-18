<?php 
$cronjobs = ( exec("ps -all | grep [c]ron"));
if(isset($_GET[start])){                     
exec("/etc/init.d/cron enable");
exec("/etc/init.d/cron start"); 
$cronjobs = "true";             
}                              
if(isset($_GET[stop])){
exec("/etc/init.d/cron stop");  
exec("/etc/init.d/cron disable");
$cronjobs = "";                  
}

if(isset($_GET[goback])){ header('Location:index.php'); } ?>
<html>
<head>
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


if ($cronjobs != ""){
echo "Cron Jobs are currently <font color=\"lime\"><b>enabled</b></font>. | <a href=\"jobs.php?stop\"><b>Disable</b></a><br />";
} else { echo "Cron Jobs <font color=\"red\"><b>disabled</b></font>. | <a href=\"jobs.php?start\"><b>Enable</b></a><br />"; }


?>
<pre>

<table border="0" width="100%" >
<tr><td width="700">
<tr><td>

<?php
$filename = "/etc/crontabs/root";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>Cron Jobs:</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/crontabs/root'>
<br><input type='submit' value='Update Crontab'>
</form>";
?>
</td><td valign="top" align="left">
<pre>Cronjob Configuration.

* * * * * command to be executed
- - - - -
| | | | |
| | | | +- - - - day of week (0 - 6) (Sunday=0)
| | | +- - - - - month (1 - 12)
| | +- - - - - - day of month (1 - 31)
| +- - - - - - - hour (0 - 23)
+- - - - - - - - minute (0 - 59)

Examples:

Run myscript.sh at 2:30 AM every day
30 2 * * * /root/myscript.sh

Run myscript.sh every 15 minutes
*/15 * * * * /root/myscript.sh
 
</pre></td></tr>

<tr><td width="700">


<?php
$filename = "/etc/rc.local";
  $fh = fopen($filename, "r") or die("Could not open file!");
    $data = fread($fh, filesize($filename)) or die("Could not read file!");
      fclose($fh);
       echo "<b>Execute on Boot:</b>
       <form action='$_SERVER[php_self]' method= 'post' >
       <textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
       <input type='hidden' name='filename' value='/etc/rc.local'>
       <br><input type='submit' value='Update rc.local'>
       </form>";
?>




</td><td valign="top">
This script executes on boot.
</td></tr>
</table>


</pre>
</body>
</html>
