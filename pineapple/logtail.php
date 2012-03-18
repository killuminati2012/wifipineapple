<?
//$cmd = "cat /www/pineapple/dhcp";
$cmd = "cat /tmp/dhcp.leases; echo '\n'; cat /proc/net/arp; echo '\n'; grep KARMA /tmp/karma.log | grep -v -e enabled | grep -v -e malloc | grep -v -e CTRL_IFACE | grep -v -e KARMA_STATE | grep -v -e Request | uniq | sed '1!G;h;$!d'";
exec("$cmd 2>&1", $output);
foreach($output as $outputline) {
 echo ("$outputline\n");
}
?>
