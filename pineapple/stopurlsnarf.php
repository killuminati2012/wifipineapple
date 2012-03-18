<?php
exec ("echo '' > /www/pineapple/urlsnarf.log");
exec ("kill `ps auxww | grep \"/www/pineapple/urlsnarf.sh\" | grep -v -e grep | awk '{print $1}'`");
exec ("kill `ps auxww | grep \"/www/pineapple/update-urlsnarf.sh\" | grep -v -e grep | awk '{print $1}'`");
exec ("kill `ps auxww | grep \"urlsnarf -i br-lan\" | grep -v -e grep | awk '{print $1}'`");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/pineapple/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny stops snarfing urls, having a mojito instead.";
?>
</pre></head></body>
