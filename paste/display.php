<!-- Attention, si vous lisez la suite soyez en garde, c'est du code 100% "quick & dirty" -->

<!DOCTYPE html>
<html>
	<head>
		<title>Paste</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
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
			 overflow-x: scroll;
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
		<script src="./js/jquery-1.11.2.min.js"></script>
		<script src="./js/aes.js"></script>
		<script src="./js/highlight/highlight.pack.js"></script>
	</head>
	<body style="color: #444; margin:0; margin-top: 6%; background-color: #fff; width: 100%; text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">
		<h1 style="margin:0; font-size:120px; line-height:120px; font-weight:bold;">Paste</h1>
		<?php
			if(isset($_GET['p']))
			{
				if(file_exists("./files/" . $_GET['p'] . ".paste"))
				{
					$file = fopen("./files/" . $_GET['p'] . ".paste", "r");
					$line = intval(fgets($file));
					$offset = ftell($file);
					fclose($file);

					if($line < time() && $line != -1 && $line != -2)
					{
						unlink("./files/" . $_GET['p'] . ".paste");
						echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:bold;">Bad URL</h2>';
					}
					else
					{
						echo '<label class="nojs">Javascript is required if your text is encrypted.</label><input autocomplete="off" class="js" style="display:none;" type="text" placeholder="Passphrase (if encrypted)" id="e"/><br/>';
						$content = file_get_contents("./files/" . $_GET['p'] . ".paste", NULL, NULL, $offset);
						echo '<div class="terminal"><pre id="content">' . $content . '</pre></div>';
						echo '<div id="hidden" style="display:none;">' . $content . '</div>';
						if($line == -1)
							unlink("./files/" . $_GET['p'] . ".paste");
					}
				}
				else
					echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:bold;">Bad URL</h2>';
			}
			else
				echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:bold;">Bad URL</h2>';
		?>
		<p>
			Powered by <a href="http://0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">0x010C</a>. Use <a href="https://paste.0x010c.fr<?php echo $_SERVER['REQUEST_URI'];?>" style="color:#444; text-decoration: none; font-weight: bold;">https</a> to avoid clear transmission of your text.<br/>
			See <a href="/t" style="color:#444; text-decoration: none; font-weight: bold;">this page</a> to use this tool in text format.
		</p>
		<script>
			$(".js").css("display", "inline");
			$(".nojs").css("display", "none");
			$(function() {
				$('pre code').each(function(i, block) {
					hljs.highlightBlock(block);
				});
				$("#e").keyup(function(e) {
					if($("#e").val().length > 0)
						$("#content").text(CryptoJS.AES.decrypt($("#hidden").text(), $("#e").val()).toString(CryptoJS.enc.Utf8));
					else
						$("#content").text($("#hidden").text());
					if($("#content").text() == "")
						$("#content").text($("#hidden").text());
				});
			});
		</script>
	</body>
</html>

