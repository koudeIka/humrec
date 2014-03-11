<?php
require_once('application_top.php');
include_once('header.php');

echo "<h1>Liste des releases</h1>";

ini_set('display_errors', 1);
error_reporting(E_ALL); 

echo "<div id='liste_releases'>";
foreach(releases::getAll(false) as $rel_id)
{
	$release = new releases($rel_id);
	$art_names = artistes::getNames($release->art_ids);

	echo "
	<div id='rel_id_".$release->rel_id."' data-rel_id='".$release->rel_id."' class='release ui-widget ui-widget-header ui-corner-all'>
		<h2>".$release->	rel_number." | ".implode(" & ", $art_names)." - ".$release->rel_title."</h2>
		<label class='label'>Visible sur le site</label>
		";
		if ($release->rel_show == 1)
		{
			echo "<input type='checkbox' class='rel_show' checked='checked' />";
		} else
		{
			echo "<input type='checkbox' class='rel_show' />";
		}
	echo "
		<span class='button_delete'>&nbsp;</span>
	</div>";
}
echo "</div>

	<div id='release_edit' class='hidden ui-widget ui-widget-content'></div>

	<div id='release_add'>Nouvelle release</div>
";

include_once('footer.php');
?>