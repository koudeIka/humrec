<?php
$html = new html();
$html->doctype		= "html5";
$html->title = "Hum.rec : Free and LOSS releases | ".HTML_TITLE;

// CSS COMMON PERSO
$html->css[] 		= $INI['path']['js_library'].'fancybox/jquery.fancybox.css';
$html->css[] 		= $INI['path']['css'].'general.css';
$current_css 		= substr(CURRENT_PAGE, 0, -4).'.css';
if(file_exists($INI['path']['css'].$current_css))
{
	$html->css[] 	= $INI['path']['css'].$current_css;
}

// JS COMMON
$html->scripts[] = array(
		"position" => "footer",
		"file" => $INI['path']['js_library'].'jquery-1.8.2.min.js'
);

$html->scripts[] = array(
		"position" => "footer",
		"file" => $INI['path']['js_library'].'fancybox/jquery.fancybox.pack.js'
);

$html->scripts[] = array(
		"position" => "footer",
		"file" => $INI['path']['js_library'].'jqFancyTransitions.1.8.min.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => $INI['path']['js_library'].'audiojs/audio.min.js'
);

// JS COMMON PERSO
if(empty($_GET['test']))
{
    $html->scripts[] = array(
		    "position" => "footer",
		    "file" => $INI['path']['js_local'].'general.js'
    );
}
$current_js = substr(CURRENT_PAGE, 0, -4).'.js';
if(file_exists($INI['path']['js_local'].$current_js))
{
	$html->scripts[] = array(
			"position" => "footer",
			"file" => $INI['path']['js_local'].$current_js
	);
}
$html->header();

$class_entete = "";
if(!empty($_GET['test']))
{
    $class_entete = "class='test".$_GET['test']."'";
}

echo "<div id='global'>
	<div id='entete'".$class_entete.">";
echo "		<a class='label_title' href='".$INI['general']['url_public']."'>Hum.rec</a>";
echo "
		<p class='label_slogan'>FREE and LOSS releases</p>";
if(empty($_GET['test']))
{
    echo "		<img src='".$INI['path']['images']."banner-humrec-1.jpg' alt='' class='banner' />
		<img src='".$INI['path']['images']."banner-humrec-2.jpg' alt='' class='banner' />
		<img src='".$INI['path']['images']."banner-humrec-3.jpg' alt='' class='banner' />
		<img src='".$INI['path']['images']."banner-humrec-4.jpg' alt='' class='banner' />
		<img src='".$INI['path']['images']."banner-humrec-5.jpg' alt='' class='banner' />";
}
else
{
    echo "<img src='".$INI['path']['images']."banner-".$_GET['test'].".jpg' alt='' class='banner' />";
}
echo "	</div>
	<div id='entete_menu'>
		<ul class='menu_langs'>
			<li><a href='?lang=fr'";
			if($_SESSION['lang'] == 'fr') { echo " class='selected'"; }
			echo ">Français</a></li>
			<li><a href='?lang=en'";
			if($_SESSION['lang'] == 'en') { echo " class='selected'"; }
			echo ">English</a></li>
		</ul>
	</div>
	<div id='centre'>
		<div id='navigation'>
			<ul class='navi_blur'>
				<li><a href='".$INI['general']['url_public']."'";
				if(CURRENT_PAGE == 'index.php') { echo  " class='active'"; }
				echo ">News</a></li>
				<li><a href='releases.php'";
				if(CURRENT_PAGE == 'releases.php') { echo  " class='active'"; }
				echo ">Releases</a></li>
				<li><a href='artistes.php'";
				if(CURRENT_PAGE == 'artistes.php') { echo  " class='active'"; }
				echo ">Artistes</a></li>
				<li><a href='about.php'";
				if(CURRENT_PAGE == 'about.php') { echo  " class='active'"; }
				echo ">A propos</a></li>
			</ul>
			<div id='newsletter'>
			      <label>";
			     if($_SESSION['lang'] == 'fr')
			     {
				  echo "Inscrivez-vous à la newsletter";
			     } else
			     {
				  echo "Subscribe to the newsletter";
			     }
			echo "</label>
			      <input type='text' name='mail' id='newsletter_mail' placeholder='Votre adresse mail' />

			</div>
		</div>
		<div id='contenu'>";
?>