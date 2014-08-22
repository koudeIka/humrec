<?php
$html = new html();
$html->doctype		= "html5";
$html->title = "Hum.rec : Free and LOSS releases | ".HTML_TITLE;
$html->base						= $_SERVER["HTTP_HOST"];
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
			<li><a href='?lang=fr' ".($_SESSION['lang'] == 'fr' ? " class='selected'" : "").">Français</a></li>
			<li><a href='?lang=en' ".($_SESSION['lang'] == 'en' ? " class='selected'" : "").">English</a></li>
		</ul>
	</div>
	<div id='centre'>
		<div id='navigation'>
			<ul class='navi_blur'>
				<li><a href='".$INI['general']['url_public']."' ".(CURRENT_PAGE == 'index.php' ? " class='active'" : "").">News</a></li>
				<li><a href='releases.php' ".(CURRENT_PAGE == 'releases.php' ? " class='active'" : "").">Releases</a>
					<ul id='releases_link' ".(CURRENT_PAGE == 'releases.php' ? " style='display: block;'" : "").">";
					foreach(releases::getAll(true) as $rel_id)
					{
						$release = new releases($rel_id);
						echo "<li><a href='/release::".$release->rel_url."' ".($_GET["rel_url"] == $release->rel_url ? " class='active'" : "").">".$release->rel_number."</a></li>";
					}
echo "				</ul>
				</li>
				<li><a href='artistes.php' ".(CURRENT_PAGE == 'artistes.php' ? " class='active'" : "").">Artistes</a>
					<ul id='artistes_link' ".(CURRENT_PAGE == 'artistes.php' ? " style='display: block;'" : "").">";
					foreach(artistes::getAll(true) as $art_id)
					{
						$artiste = new artistes($art_id);
						echo "<li><a href='/artist::".$artiste->art_url."' ".($_GET["art_url"] == $artiste->art_url ? " class='active'" : "").">".$artiste->art_name."</a></li>";
					}
echo "				</ul>
				</li>
				<li><a href='about.php' ".(CURRENT_PAGE == 'about.php' ? " class='active'" : "").">A propos</a></li>
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