<!-- Attention, si vous lisez la suite soyez en garde, c'est du code 100% "quick & dirty" -->

<!DOCTYPE html>
<html>
	<head>
		<title>Paste</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<style>
		table{
			border-collapse: collapse;
			border-spacing: 0;
			font-family: sans-serif;
			border:1px solid #cbcbcb;
			margin:0;
			padding:0;
			margin: auto;
			margin-top: 1em;
			table-layout:fixed;
			width: 75%;
		}
		tr:nth-child(even){
			background-color:#ffffff;
		}
		tr:nth-child(odd){
			background-color:#f2f2f2;
		}
		td{
			vertical-align:middle;
			border:1px solid #cbcbcb;
			border-width:0px 0px 1px 0px;
			padding:14px;
			font-size:16px;
			font-weight:normal;
			color:#777777;
			text-align:left;
			word-wrap: break-word;
		}
		td:first-child{
			width: 100px;
		}
		.flexbox{
			display: flex;
			width: 60%;
			justify-content: space-between;
			margin: auto;
			flex-wrap: wrap;
		}
		label{
			font-size: 16px;
			display: inline-block;
			line-height: 1.6;
		}
		input[type="radio"]{
			margin: 0;
			display: inline-block;
			vertical-align: -2px;
			margin-right: 3px;
		}
		textarea, input[type="text"]{
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
			width: 60%;
		}
		input[type="radio"] > label{
			
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
		<script src="./js/jquery-1.11.2.min.js"></script>
		<script src="./js/aes.js"></script>
	</head>
	<body style="color: #444; margin:0; margin-top: 6%; background-color: #fff; width: 100%; text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">
		<h1 style="margin:0; font-size:120px; line-height:120px; font-weight:bold;">Paste</h1>
		<?php
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
				if($_POST['isEncrypted'] == "no")
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
				
				if($_SERVER['HTTPS'] != "")
					$protocol = "https://";
				else
					$protocol = "http://";
				echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:normal;">Text saved at <a style="color: #444; font-weight: bold; text-decoration: none;" href="' . $protocol . 'paste.0x010c.fr/' . $hash . '">paste.0x010c.fr/' . $hash . '</a></h2>';
			}
		?>
		<form method="post" action="" id="form">
			<div class="flexbox">
				<label for="t-1"><input type="radio" name="t" value="-1" id="t-1" checked="checked" checked/>read and burn</label>
				<label for="t600"><input type="radio" name="t" value="600" id="t600"/>10 minutes</label>
				<label for="t3600"><input type="radio" name="t" value="3600" id="t3600"/>1 hour</label>
				<label for="t86400"><input type="radio" name="t" value="86400" id="t86400"/>1 day</label>
				<label for="t604800"><input type="radio" name="t" value="604800" id="t604800"/>1 week</label>
				<label for="t2678400"><input type="radio" name="t" value="2678400" id="t2678400"/>1 month</label>
				<label for="t-2"><input type="radio" name="t" value="-2" id="t-2"/>for ever (or nearly)</label>
			</div>
			<textarea rows="11" name="p" id="p" placeholder="Text or code to share"></textarea><br/>
			<label class="nojs">Javascript is required if you want to encrypt your text.</label><input autocomplete="off" class="js" style="display:none;" type="text" placeholder="Secret passphrase (optional)" id="e"/><br/>
			<input type="submit" value="send"/>
			<input type="hidden" value="no" id="isEncrypted" name="isEncrypted" />
		</form>
		<p>
			Powered by <a href="http://0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">0x010C</a>. Use <a href="https://paste.0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">https</a> to avoid clear transmission of your text.<br/>
			See <a href="/t" style="color:#444; text-decoration: none; font-weight: bold;">this page</a> to use this tool in text format.
		</p>
		<script>
			$(".js").css("display", "inline");
			$(".nojs").css("display", "none");
			$(function() {
				$("#form").submit(function(event) {
					if($("#e").val().length > 0 && $("#p").val().length > 0)
					{
						$("#p").val(CryptoJS.AES.encrypt($("#p").val(), $("#e").val()));
						$("#isEncrypted").val("yes");
					}
				});
			});
		</script>
	</body>
</html>

