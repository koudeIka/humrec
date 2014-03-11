<?php
define("HTML_TITLE", "Releases");
require_once('application_top.php');
include_once('header.php');

if($_SESSION['lang'] == 'fr')
{
	$l10n_description_footer = "Le prix inclut les frais de ports :";
	$l10n_download = "Télécharger";
} else
{
	$l10n_description_footer = "Price includes shipping :";
	$l10n_download = "Download";
}

//echo "<h1>Releases</h1>";
foreach (releases::getAll() as $rel_id)
{
	$release = new releases($rel_id);

	$art_names = array();

	foreach($release->art_ids as $art_id)
	{
		$artiste = new artistes($art_id);
		$art_names[] = $artiste->art_name;
	}
	$pho_names = array();
	foreach($release->pho_ids as $pho_id)
	{
		$photo = new photos($pho_id);
		$pho_names[] = $photo->pho_name;
	}
	echo "<div class='release' data-rel_id='".$release->rel_id."'>";
		echo "<a href='".$INI['photos_path']['real_size'].$pho_names[0]."' class='photo'><img src='".$INI['photos_path']['200x320'].$pho_names[0]."' alt='".$pho_names[0]."' class='release-cover' /></a>";
		echo "<h2>".implode(" &amp; ", $art_names)." - ".$release->rel_title."</h2>";
		echo "<h3>".$release->rel_number." | ".$release->rel_date."</h3>";

		$rel_description = "rel_content_".$_SESSION['lang'];
		echo "<p class='release-content'>".$release->$rel_description."</p>";

		echo "<p class='release-content-footer'>".$l10n_description_footer."</p>";
		echo "<div class='release-options'>";
		if (!empty($release->rel_download))
		{
			echo "<div class='release-download'><a href='".$release->rel_download."' class='link_in_text' target='_blank'>".$l10n_download."</a></div>";
			echo "<span class='separator'>|</span>";
		}
		if(!empty($release->rel_paypal_id))
		{
			echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' class='paypalForm' target='_blank'>";
			echo "<input type='hidden' name='cmd' value='_s-xclick' />";
			echo "<input type='hidden' name='currency_code' value='".$release->rel_paypal_currency_code."'>";
			echo "<input type='hidden' name='on0' value='".$release->rel_paypal_id."' />";
			echo "<input type='hidden' name='os0' value='UE' />";
			echo "<input type='hidden' name='encrypted' value=\"".$release->rel_paypal_encrypted."\" />";
			echo "</form>";

			echo "<div class='release-buy'>
				    <a href='https://www.paypal.com/cgi-bin/webscr' class='link_in_text' data-region='UE'><b>UE</b> ".$release->rel_paypal_priceue."&euro;</a>
			      </div>";
			echo "<span class='separator'>|</span>";
			echo "<div class='release-buy'>
				    <a href='https://www.paypal.com/cgi-bin/webscr' class='link_in_text' target='_blank' data-region='World'><b>WORLD</b> ".$release->rel_paypal_priceworld."&euro;</a>
			      </div>";
		}
		echo "</div>";
		
		echo "<audio preload='none' src='".$release->tracks[0]->tra_url."'></audio>";
	
		echo "<ul class='tracklist'>";
		foreach($release->tracks as $track)
		{
			echo "	<li class='track-details' data-track_id='".$track->tra_id."'>
				<a href='".$track->tra_name."' data-src='".$track->tra_url."'><span>".$track->tra_digit."</span> ".$track->tra_name."</a>
			</li>";
		}
		echo "</ul>";

		

		echo "<div class='clear'>&nbsp;</div>";
	echo "</div>";
}
include_once('footer.php');
?>