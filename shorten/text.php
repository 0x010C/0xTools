<?php
	include_once("../../db.inc.php");

	if(isset($_SERVER['HTTPS']))
		$protocol = "https://";
	else
		$protocol = "http://";
	if($_SERVER["SERVER_NAME"] == "0c.lt")
	{
		header('Location: ' . $protocol . 'shorten.0x010c.fr/t');
		exit();
	}
	
	header('Content-type: text/plain');
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
				echo 'Alias already used';
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
			echo '0c.lt/' . $alias;
		}
		
		
	}
	else
		echo 'Send your link through get or post within a field called "l", and if you which one, send your custom alias also through get or post within a field called "a"';
?>
