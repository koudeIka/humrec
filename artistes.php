<?php
define("HTML_TITLE", "Artistes");
require_once('application_top.php');
include_once('header.php');

//echo "<h1>Artistes</h1>";
foreach(artistes::getAll() as $art_id)
{
	$artiste = new artistes($art_id);

	echo "<div class='artiste'>";
		if (count($artiste->pho_ids)>0)
		{
		  $photo = new photos($artiste->pho_ids[0]);
		   echo "<a href='".$INI['photos_path']['real_size'].$photo->pho_name."' class='photo'><img class='art_pho_big' src='".$INI['photos_path']['200x200'].$photo->pho_name."' alt='".$photo->pho_name."' /></a>";
		}
		echo "<h2>".$artiste->art_name."</h2>";
		$art_description = "art_description_".$_SESSION['lang'];
		$art_description_default =  "art_description_".$INI['general']['default_lang'];

		if (!empty($artiste->$art_description))
		{
			echo "<div class='art-description'>".$artiste->$art_description."</div>";
		} elseif (!empty($artiste->$art_description_default))
		{
			echo "<div class='art-description'>".$artiste->$art_description_default."</div>";
		}

		if (!empty($artiste->art_homepage))
		{
			echo "<p class='art_homepage'><span>Homepage</span>: <a href='".$artiste->art_homepage."' target='_blank' class='link_in_text'>".$artiste->art_homepage."</a></p>";
		}
		echo "<div class='spacer'>&nbsp;</div>";
	echo "</div>";
}


include_once('footer.php');
?>