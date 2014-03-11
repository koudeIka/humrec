<?php
require_once('application_top.php');
include_once('header.php');

echo "<h1>La page d'accueil</h1>";

echo "<div id='liste_news'>";
foreach(news::getAll(false) as $new_id)
{
	$new = new news($new_id);
	echo "
	<div id='new_id_".$new->new_id."' data-new_id='".$new->new_id."' class='new ui-widget ui-widget-header ui-corner-all'>
		<h2>".$new->new_title."</h2>
		<label class='label'>Visible sur le site</label>
		";
		if ($new->new_show == 1)
		{
			echo "<input type='checkbox' class='new_show' checked='checked' />";
		} else
		{
			echo "<input type='checkbox' class='new_show' />";
		}
	echo "
		<span class='button_delete'>&nbsp;</span>
	</div>";
}
echo "</div>

	<div id='new_edit' class='hidden ui-widget ui-widget-content'></div>

	<div id='new_add'>Nouvelle news  sur la page d'accueil</div>
";

include_once('footer.php');
?>