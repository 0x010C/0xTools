<!-- Attention, si vous lisez la suite soyez en garde, c'est du code 100% "quick & dirty" -->

<!DOCTYPE html>
<html>
	<head>
		<title>Whois</title>
		<style>
		.terminal{
			text-align: left;
			width: 70%;
			background: #191919;
			color: white;
			font-family: sans-serif;
			padding: 15px;
			border-radius: 5px;
			border: 2px solid #AAA;
			margin: auto;
			box-shadow: 0px 0px 8px #BBB;
			margin-top: 1em;
		}
		input[type="text"]{
			padding: 0.5em 0.6em;
			display: inline-block;
			border: 1px solid #CCC;
			box-shadow: 0px 1px 3px #DDD inset;
			border-radius: 3px;
			box-sizing: border-box;
			font-family: sans-serif;
			font-weight: 100;
			letter-spacing: 0.01em;
			font-size: 100%;
			margin: 0px;
			margin-bottom: 5px;
			color: #777;
			width: 30%;
		}
		input[type="submit"]{
			box-sizing: border-box;
			font-family: inherit;
			background-color: #0078E7;
			color: #FFF;
			font-size: 100%;
			padding: 0.5em 1em;
			border: 0px none transparent;
			text-decoration: none;
			border-radius: 3px;
			display: inline-block;
			line-height: normal;
			white-space: nowrap;
			vertical-align: baseline;
			text-align: center;
			cursor: pointer;
			-moz-user-select: none;
			font-weight: 100;
			letter-spacing: 0.01em;
			text-transform: none;
			margin: 0px;
		}
		input[type="submit"]:hover, input[type="submit"]:focus{
			background-image: linear-gradient(transparent, rgba(0, 0, 0, 0.05) 40%, rgba(0, 0, 0, 0.1));
		}
		</style>
	</head>
	<body style="color: #444; margin:0; margin-top: 10%; background-color: #fff; width: 100%; text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">
		<h1 style="margin:0; font-size:120px; line-height:120px; font-weight:bold;">Whois</h1>
		<form method="post" action="">
			<input type="text" placeholder="Domain name or IP address" id="p" name="p"/>
			<input type="submit" value="who is?"/>
		</form>
		<?php
			$input = "";
			if(isset($_GET['p']))
				$input = escapeshellcmd($_GET['p']);
			if(isset($_POST['p']))
				$input = escapeshellcmd($_POST['p']);
			if($input != "")
			{
				exec('whois ' . $input, $result);

				echo '<div class="terminal">';
				foreach($result as $line)
					echo $line . '<br />';
				echo '</div>';
			}
		?>
		<p>
			Powered by <a href="http://0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">0x010C</a>. See <a href="/t" style="color:#444; text-decoration: none; font-weight: bold;">this page</a> to get the result in text format.
		</p>
	</body>
</html>

