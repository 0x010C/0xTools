<?php
	header('Content-type: text/plain');

	$message = "";
	if(isset($_GET['p']))
		$message = $_GET['p'];
	if(isset($_POST['p']))
		$message = $_POST['p'];				

	$time = 0;
	if(isset($_GET['t']))
		$time = intval($_GET['t']);
	if(isset($_POST['t']))
		$time = intval($_POST['t']);

	if($message != "" && $time != 0 && $time >= -2)
	{
		$message = htmlspecialchars($message);
		$found = true;
		$i = 0;
		
		while($found)
		{
			$i++;
			$hash = hash("crc32b", $passphrase . $i, false);
			$found = file_exists("./files/" . $hash . ".paste");
		}

		$timestamp = time() + $time;
		if($time == -2)
			$timestamp = "-2";
		if($time == -1)
			$timestamp = "-1";

		$file = fopen("./files/" . $hash . ".paste", "w");
		fputs($file, $timestamp . "\n" . $message);
		fclose($file);

		echo "paste.0x010c.fr/" . $hash . "\npaste.0x010c.fr/t/" . $hash;
	}
	else
		echo "Send your text to share through get or post within a field called \"p\".\nSend the time (in seconds) you want to keep it through get or post within a field called \"t\"\nIf this last field contains -1, then the text will be destroyed after reading ; if it contains -2 then the text will be preserved indefinitely.";
?>
