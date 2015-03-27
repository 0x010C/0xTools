<?php
	header('Content-type: text/plain');

	if(isset($_GET['p']))
	{
		if(file_exists("./files/" . $_GET['p'] . ".paste"))
		{
			$file = fopen("./files/" . $_GET['p'] . ".paste", "r");
			$line = intval(fgets($file));
			$offset = ftell($file);
			fclose($file);

			if($line < time() && $line != -1 && $line != -2)
				unlink("./files/" . $_GET['p'] . ".paste");
			else
			{
				$content = file_get_contents("./files/" . $_GET['p'] . ".paste", NULL, NULL, $offset);
				echo $content;
				if($line == -1)
					unlink("./files/" . $_GET['p'] . ".paste");
			}
		}
	}
?>
