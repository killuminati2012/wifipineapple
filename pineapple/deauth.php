<html>
<head>
<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<table border="0" width="100%" bgcolor="green"><tr width="100%"><td><pre>
<a href="index.php"><font color="black"><b>Status</b></font></a> | <a href="config.php"><font color="black"><b>Configuration</b></font></a> | <a href="advanced.php"><font color="black"><b>Advanced</b></font></a> | <a href="about.php"><font color="black"><b>About</b></font></a>
</pre></td></tr></table>

<table border="0" width="100%"><tr><td align="left" valign="top" width="80%">
<pre>

<?php
if(isset($_POST[deauthtarget])) {

$bssid = exec("ifconfig wlan0 | grep HWaddr | awk {'print $5'}");

echo "Deauth Host: $bssid <br />";
echo "Deauth Target: $_POST[deauthtarget] <br />";
echo "Deauth client: $_POST[deauthtargetClient] <br />";
echo "Deauth Times: $_POST[deauthtimes] <br />";

if($_POST[deauthtargetClient] != "ClientMAC"){
$cmd = "aireplay-ng -0 $_POST[deauthtimes] -c $_POST[deauthtargetClient] -a $_POST[deauthtarget] --ignore-negative-one mon.wlan0";
} else { $cmd = "aireplay-ng -0 $_POST[deauthtimes] -a $_POST[deauthtarget] --ignore-negative-one mon.wlan0"; }

echo "<br />Executing: <b><font color='green'>" . $cmd . "</font></b><br /><br />";

exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}

}
?>

</pre>
</td><td valign="top" align="left" width="*">
<pre>



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

</pre>
</td></tr></table>
</body>
</html>
