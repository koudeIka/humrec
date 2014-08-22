<?php
define("HTML_TITLE", "News");
require_once('application_top.php');
include_once('header.php');

echo "<!--[if lt IE 7]>
  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display='none'; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
      <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>Vous utilisez un navigateur dépassé depuis près de 10 ans!</div>
        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour.</div>
      </div>
      <div style='width: 75px; float: left;'><a href='http://fr.www.mozilla.com/fr/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
      <div style='width: 75px; float: left;'><a href='http://www.microsoft.com/france/windows/internet-explorer/telecharger-ie9.aspx' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/></a></div>
      <div style='width: 73px; float: left;'><a href='http://www.apple.com/fr/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
      <div style='float: left;'><a href='http://www.google.com/chrome?hl=fr' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
    </div>
  </div>
  <![endif]-->";
foreach(news::getAll() as $new_id)
{
	$new = new news($new_id);
	echo "<div class='new'>";
		echo "<div class='new-date'>".substr(date::enToFr($new->new_date), 0, 10)."</div>";
		echo "<h2>".$new->new_title."</h2>";
		$new_content = "new_content_".$_SESSION['lang'];
		$new_content_default =  "new_content_".$INI['general']['default_lang'];

		if (!empty($new->$new_content))
		{
			echo "<div class='new-content'>".$new->$new_content."</div>";
		} elseif (!empty($new->$new_content_default))
		{
			echo "<div class='new-content'>".$new->$new_content_default."</div>";
		}
		//echo "<div class='spacer'>&nbsp;</div>";
	echo "</div>";
}

include_once('footer.php');
?>
