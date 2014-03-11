<?php
$html = new html();
$html->doctype		= "html5";
$html->title = "Humus | FREE  and LOSS label";

// CSS COMMON PERSO
$html->css[] 		= '../'.$INI['path']['js_library'].'jqueryui/css/pepper-grinder/jquery-ui-1.8.16.custom.css';
$html->css[] 		= '../'.$INI['path']['js_library'].'cleditor/jquery.cleditor.css';
$html->css[] 		= '../'.$INI['path']['js_library'].'fancybox/jquery.fancybox.css';

// JS COMMON
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'jquery-1.7.2.min.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'jqueryui/jquery-ui-1.8.16.custom.min.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'cleditor/jquery.cleditor.min.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'cleditor/jquery.cleditor.xhtml.min.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'valums-fileuploader/fileuploader.js'
);
$html->scripts[] = array(
		"position" => "footer",
		"file" => '../'.$INI['path']['js_library'].'fancybox/jquery.fancybox.pack.js'
);


// CSS COMMON PERSO
$html->css[] 		= $INI['path']['css'].'general.css';
$current_css 		= substr(PAGE_COURANTE, 0, -4).'.css';
if(file_exists($INI['path']['css'].$current_css))
{
	$html->css[] 	= $INI['path']['css'].$current_css;
}


// JS COMMON PERSO

$html->scripts[] = array(
			"position"	=> "footer",
			"file"		=> $INI['path']['js_local'].'general.js'
		);
$current_js = substr(PAGE_COURANTE, 0, -4).'.js';
if(file_exists($INI['path']['js_local'].$current_js))
{
	$html->scripts[] = array(
			"position" => "footer",
			"file" => $INI['path']['js_local'].$current_js
	);
}
$html->header();

$dom_contenu_class = '';
if (empty($_SESSION['connected']))
{
	$dom_contenu_class = " class='connexion'";
}
$dom_navigation_class = $dom_contenu_class;

echo "<div id='global'>
	<div id='entete'>
		<a class='label_title' href='".$INI['general']['url_admin']."'>ADMIN ~ Humus</a>
	</div>
	<div id='entete_menu'>
		&nbsp;
	</div>
	<div id='centre'>
		<div id='navigation' ".$dom_navigation_class.">
			<ul class='navi_blur'>
				<li><a href='news.php'>News</a></li>
				<li><a href='releases.php'>Releases</a></li>
				<li><a href='artistes.php'>Artistes</a></li>
			</ul>
		</div>
		<div id='contenu' ".$dom_contenu_class.">";
?>