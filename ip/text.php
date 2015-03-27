<?php
	header('Content-type: text/plain');
	echo $_SERVER['REMOTE_ADDR'] . "\n" . gethostbyaddr($_SERVER['REMOTE_ADDR']);
?>
