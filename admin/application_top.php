<?php
session_start();

if (file_exists('includes/config.ini.php'))
{
	$INI 	= parse_ini_file("includes/config.ini.php", true);
} elseif(file_exists('../includes/config.ini.php'))
{
	$INI 	= parse_ini_file("../includes/config.ini.php", true);
} elseif (file_exists('../../includes/config.ini.php'))
{
	$INI 	= parse_ini_file("../../includes/config.ini.php", true);
} else
{
	die("Impossible de trouver config.ini");
}


function __autoload($class_name) {
	global $INI;
	if (file_exists($INI["folder"]["classes"].$class_name.'.class.php'))
	{
		require_once $INI["folder"]["classes"].$class_name.'.class.php';
	} elseif (file_exists($INI["folder"]["puppets"].$class_name.'.class.php'))
	{
		require_once $INI["folder"]["puppets"].$class_name.'.class.php';
	} elseif (file_exists($INI["folder"]["puppets"]."plugins/".$class_name.'.class.php'))
	{
		require_once $INI["folder"]["puppets"]."plugins/".$class_name.'.class.php';
	}
}
$mysql = new mysql($INI["mysql"]["server"], $INI["mysql"]["user"], $INI["mysql"]["password"], $INI["mysql"]["database"]);


define("PAGE_COURANTE", pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME));
date_default_timezone_set('Europe/Paris');
setlocale (LC_TIME, 'fr_FR.utf8','fra');

$FORMATS_IMG = array('120x80', '200x200', '200x320');


if((!empty($_POST['connexion'])) && (PAGE_COURANTE == 'index.php'))
{
	if (($_POST['usr_login'] == 'humrec') &&  ($_POST['usr_passwd'] == 'humus12'))
	{
		$_SESSION['connected'] = true;
	} else
	{
		$_SESSION['connected'] = '';
		$tentative_login = "Mauvais login ou mauvais mot de passe";
	}
}
elseif(PAGE_COURANTE == 'index.php')
{
	$_SESSION['connected'] = '';
}
elseif(empty($_SESSION['connected']))
{
	header("Location: index.php");
	die("Utilisateur non identifi&eacute;");
}

?>