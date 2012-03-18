<html>
<head>
<title>Pineapple Control Center</title>
<script  type="text/javascript" src="jquery.min.js"></script>
</head>
<body bgcolor="black" text="white" alink="green" vlink="green" link="green">

<?php require('navbar.php'); ?>
<pre>
<?php

$cmd = "logread";
exec ($cmd, $output);                                                              
foreach($output as $outputline) {
echo ("$outputline\n");}   

?>
<a name="bottom"></a>
<a href="logs.php#bottom">Refresh</a>
</pre>
</body>
</html>
