<?php
	header('Content-type: text/plain');

	$input = "";
	if(isset($_GET['p']))
		$input = escapeshellcmd($_GET['p']);
	if(isset($_POST['p']))
		$input = escapeshellcmd($_POST['p']);

	if($input != "")
	{
		exec('whois ' . $input, $result);

		foreach($result as $line)
			echo $line . "\n";
	}
	else
		echo 'Send the domain name or the IP address through get or post within a field called "p"';
?>
