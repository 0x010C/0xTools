<!-- Attention, si vous lisez la suite soyez en garde, c'est du code 100% "quick & dirty" -->
<?php
	include_once("../../db.inc.php");

	if(isset($_SERVER['HTTPS']))
		$protocol = "https://";
	else
		$protocol = "http://";

	$redirect = "";
	if(isset($_GET['r']))
		$redirect = $_GET['r'];
	if(isset($_POST['r']))
		$redirect = $_POST['r'];
	if($redirect != "")
	{
		$requete_get = $db->prepare("SELECT link as l
									 FROM tools_shorten
									 WHERE alias = :alias
									 ");
		$requete_get->execute(array('alias' => $redirect));
		$reponse_get = $requete_get->fetch();
		if(isset($reponse_get['l']))
		{
			$requete_update = $db->prepare("UPDATE tools_shorten
											SET nbVisits = nbVisits+1, date = NOW()
											WHERE alias = :alias
											");
			$requete_update->execute(array('alias' => $redirect));
			header('Location: ' . $reponse_get['l']);
			exit();
		}
	}

	if($_SERVER["SERVER_NAME"] == "0c.lt")
	{
		header('Location: ' . $protocol . 'shorten.0x010c.fr');
		exit();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Shorten</title>
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
	</head>
	<body style="color: #444; margin:0; margin-top: 6%; background-color: #fff; width: 100%; text-align: center; font: normal 14px Arial, Helvetica, sans-serif;">
		<h1 style="margin:0; font-size:120px; line-height:120px; font-weight:bold;">Shorten</h1>
		<?php
			$link = "";
			if(isset($_GET['l']))
				$link = $_GET['l'];
			if(isset($_POST['l']))
				$link = $_POST['l'];
			if($link != "" && !preg_match("#^https?://#", $link))
				$link = "http://".$link;

			$alias = "";
			if(isset($_GET['a']))
				$alias = $_GET['a'];
			if(isset($_POST['a']))
				$alias = $_POST['a'];

			if($link != "")
			{
				if($alias != "")
				{
					$requete_existe = $db->prepare("SELECT
													COUNT(*) AS recherche
													FROM tools_shorten
													WHERE alias = :alias
													");
					$requete_existe->execute(array('alias' => $alias));
					$reponse_existe = $requete_existe->fetch();
					if($reponse_existe['recherche'] != 0)
					{
						$alias = "";
						echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:normal;">Alias already used</h2>';
					}
				}
				else
				{
					$i = 0;
					do
					{
						$alias = hash("crc32b", $link . $i++, false);
						$requete_existe = $db->prepare("SELECT
														COUNT(*) AS recherche
														FROM tools_shorten
														WHERE alias = :alias
														");
						$requete_existe->execute(array('alias' => $alias));
						$reponse_existe = $requete_existe->fetch();
					} while($reponse_existe['recherche'] != 0);
				}
				if($alias != "")
				{
					$requete_save = $db->prepare("INSERT INTO tools_shorten
												  VALUES ('', NOW(), :ip, :link, :alias, '')
												  ");
					$requete_save->execute(array('ip' => $_SERVER['REMOTE_ADDR'],
												 'link' => $link,
												 'alias' => $alias
												  ));
					echo '<h2 style="margin-top:20px;font-size: 30px; line-height: 20px; font-weight:normal;">Short URL : <a style="color: #444; font-weight: bold; text-decoration: none;" href="' . $protocol . '0c.lt/' . $alias . '">0c.lt/' . $alias . '</a></h2>';
				}
				
				
			}
		?>
		<form method="post" action="" id="form">
			<input autocomplete="off" type="text" placeholder="Link to shorten" id="l" name="l"/><br/>
			<input autocomplete="off" type="text" placeholder="Custom alias (optional)" id="a" name="a"/><br/>
			<input type="submit" value="shorten"/>
		</form>
		<p>
			Powered by <a href="http://0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">0x010C</a>. Use <a href="https://shorten.0x010c.fr" style="color:#444; text-decoration: none; font-weight: bold;">https</a> to avoid clear transmission.<br/>
			See <a href="/t" style="color:#444; text-decoration: none; font-weight: bold;">this page</a> to use this tool in text format.<br/>
			Note that all unused links for over a year will be automatically deleted.
		</p>
	</body>
</html>

