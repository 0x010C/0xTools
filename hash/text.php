<?php
	header('Content-type: text/plain');

	$passphrase = "";
	if(isset($_GET['p']))
		$passphrase = $_GET['p'];
	if(isset($_POST['p']))
		$passphrase = $_POST['p'];

	if($passphrase != "")
	{
		foreach (hash_algos() as $i)
			echo $i . "\t" . hash($i, $passphrase, false)  . "\n";
	}
	else
		echo 'Send your passphrase through get or post within a field called "p"';
?>
