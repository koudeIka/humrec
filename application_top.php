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
error_reporting(E_ERROR);


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

if(strpos($_SERVER['PHP_SELF'], "releases.php") !== false)
{
	$_SERVER['PHP_SELF'] = "releases.php";
}
elseif(strpos($_SERVER['PHP_SELF'], "artistes.php") !== false)
{
	$_SERVER['PHP_SELF'] = "artistes.php";
}
define("CURRENT_PAGE", pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME));


date_default_timezone_set('Europe/Paris');
setlocale (LC_TIME, 'fr_FR.utf8','fra');

if(!empty($_GET['lang']))
{
	$_SESSION['lang'] = $_GET['lang'];
}
else
{
	if(empty($_SESSION['lang']))
	{
		if(strtolower(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2)) == 'fr')
		{
			$_SESSION['lang'] = 'fr';
		} else
		{
			$_SESSION['lang'] = 'en';
		}
	}
}

?>