<?php
$filename='/root/.ssh/known_hosts';
if (!file_exists($filename)) {
	echo "exists";
} else {
	echo "doesn't";
	//exec("touch /root/.ssh/known_hosts");
	//echo "does now";
	//test
}
?>
