<?php
exec ("echo '' > /tmp/karma.log");
exec ("rm -rf /www/pineapple/associations.log");
exec ("echo /www/pineapple/startkarma.sh | at now");
exec ("echo /www/pineapple/update-associations.sh | at now");
?>
<html><head>
<meta http-equiv="refresh" content="2; url=wait.php">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Activated";
?>
</pre></head></body>
