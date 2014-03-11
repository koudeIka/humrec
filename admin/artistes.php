<?php
require_once('application_top.php');
include_once('header.php');

echo "<h1>Liste des artistes</h1>";


//$mysql->generateClass("art_has_pho");

echo "<div id='liste_artistes'>";
foreach(artistes::getAll(false) as $art_id)
{
	$artiste = new artistes($art_id);
	echo "
	<div id='art_id_".$artiste->art_id."' data-art_id='".$artiste->art_id."' class='artiste ui-widget ui-widget-header ui-corner-all'>
		<h2>".$artiste->art_name."</h2>
		<label class='label'>Visible sur le site</label>
		";
		if ($artiste->art_show == 1)
		{
			echo "<input type='checkbox' class='art_show' checked='checked' />";
		} else
		{
			echo "<input type='checkbox' class='art_show' />";
		}
	echo "
		<span class='button_delete'>&nbsp;</span>
	</div>";
}
echo "</div>

	<div id='artiste_edit' class='hidden ui-widget ui-widget-content'></div>

	<div id='artiste_add'>Nouvel artiste</div>
";

include_once('footer.php');
?>