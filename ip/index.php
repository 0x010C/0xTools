<!DOCTYPE html>
<html>
	<head>
		<title>IP</title>
	</head>
	<body style="color: #444; margin:0; margin-top: 13%; background-color: #fff; width: 100%; text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">
		<h1 style="margin:0; font-size:120px; line-height:120px; font-weight:bold;"><?php echo $_SERVER['REMOTE_ADDR'];?></h1>
		<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:bold;"><?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']);?></h2>
		<p>Powered by <a href="http://0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">0x010C</a>. See <a href="/t" style="color:#444; text-decoration: none; font-weight: bold;">this page</a> to get your ip address in text format.</p>
	</body>
</html>

