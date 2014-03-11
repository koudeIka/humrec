<?php
try {
	require_once('../application_top.php');

	if(!empty($_POST['action']))
	{
		$action		= $_POST["action"];
	} elseif(!empty($_GET['action']))
	{
		$action		= $_GET["action"];
	}

	switch($action)
	{

		case 'save_show':
			$release = new releases($_POST['rel_id']);
			if ($_POST['rel_show'] == "checked")
			{
				$release->rel_show = 1;
			} else
			{
				$release->rel_show = 0;
			}
			$release->save();
			break;


		case 'load_release':
			$release = new releases($_POST['rel_id']);
			echo "
			<fieldset class='sublab_id'>
					<legend>Type de release</legend>
					<select>
						<option value=''>...</option>";
					foreach (sub_labels::getAll() as $sublab_id)
					{
						$sub_label = new sub_labels($sublab_id);
						if ($sub_label->sublab_id == $release->sublab_id)
						{
							echo "<option selected='selected' value='".$sub_label->sublab_id."'>".$sub_label->sublab_name."</option>";
						} else
						{
							echo "<option value='".$sub_label->sublab_id."'>".$sub_label->sublab_name."</option>";
						}
					}
					echo "
					</select>
			</fieldset>
			<fieldset class='art_id'>
				<legend>Artiste</legend>
				<select>
					<option value=''>...</option>";
				foreach (artistes:: getAll(false) as $art_id)
				{
					$artiste = new artistes($art_id);
					if (in_array($artiste->art_id, $release->art_ids))
					{
						echo "<option value='".$artiste->art_id."' selected='selected'>".$artiste->art_name."</option>";
					} else
					{
						echo "<option value='".$artiste->art_id."'>".$artiste->art_name."</option>";
					}
				}
				echo "
				</select>
			</fieldset>
			<fieldset class='rel_number'>
				<legend>Numéro</legend>
				<input type='text' value=\"".$release->rel_number."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='rel_title'>
				<legend>Titre</legend>
				<input type='text' value=\"".$release->rel_title."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='rel_date'>
				<legend>Date</legend>
				<input type='text' value=\"".substr(date::enToFr($release->rel_date), 0, 10)."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='rel_content_fr'>
				<legend>Description FRENCH</legend>
				<textarea>".$release->rel_content_fr."</textarea>
			</fieldset>
			<fieldset class='rel_content_en'>
				<legend>Description ENGLISH</legend>
				<textarea>".$release->rel_content_en."</textarea>
			</fieldset>
			<fieldset class='rel_download'>
				<legend>Lien de téléchargement</legend>
				<input type='text' value=\"".$release->rel_download."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='rel_paypal'>
				<legend>Paypal</legend>
				<p>
					<label>ID (on0)</label>
					<input name='rel_paypal_id' type='text' value=\"".$release->rel_paypal_id."\" class='ui-state-default' />
				</p>
				<p>
					<label>Prix EU (os0)</label>
					<input name='rel_paypal_priceue' type='text' value=\"".$release->rel_paypal_priceue."\" class='ui-state-default' />
				</p>
				<p>
					<label>Prix WORLD (os0)</label>
					<input name='rel_paypal_priceworld' type='text' value=\"".$release->rel_paypal_priceworld."\" class='ui-state-default' />
				</p>
				<p>
					<label>Monnaie (currency_code)</label>
					<input name='rel_paypal_currency_code' type='text' value=\"".$release->rel_paypal_currency_code."\" class='ui-state-default' />
				</p>
				<p>
					<label>Clé cryptée (encrypted)</label>
					<input name='rel_paypal_encrypted' type='text' value=\"".$release->rel_paypal_encrypted."\" class='ui-state-default' />
				</p>
			</fieldset>
			<fieldset class='rel_tracklist'>
				<legend>Tracklist</legend>
			";
			echo "<table>";
				echo "<thead><tr>";
					echo "<th>Numéro</th>";
					echo "<th>Nom</th>";
					echo "<th>URL</th>";
				echo "</tr></thead>";
				echo "<tbody>";
				if(count($release->tracks) > 0)
				{
					foreach($release->tracks as $track)
					{
						echo "<tr data-tra_id='".$track->tra_id."' data-tra_order='".$track->tra_order."'>";
							echo "<td class='tra_digit'><input type='text' class='ui-state-default'  value=\"".$track->tra_digit."\" /></td>";
							echo "<td class='tra_name'><textarea class='ui-state-default'>".str_replace("<br />", "\n", $track->tra_name)."</textarea></td>";
							echo "<td class='tra_url'><input type='text' class='ui-state-default' value=\"".$track->tra_url."\" /></td>";
							echo "<td class='delete'>&nbsp;</td>";
						echo "</tr>";
					}
				}
				echo "</tbody>";
			echo "</table>";
			echo "<div class='menu_tab'>";
				echo "<span class='add_row'>Ajouter une ligne</span>";
			echo "</div>";

			echo "</fieldset>";
			echo "<fieldset class='rel_pictures'>
					<legend>Images</legend>";
					if (count($release->pho_ids)>0)
					{
					    echo "<ul id='photo_list'>";
					    foreach($release->pho_ids as $pho_id)
					    {
						$photo = new photos($pho_id);
						// data-sortable='relpho_id_".$rel_has_pho->relpho_id."'
						echo "<li data-pho_id='".$photo->pho_id."'>
							    <img src='../".$INI['photos_path']['200x200'].$photo->pho_name."' alt='".$photo->pho_name."' class='rel_pho' />
							    <span class='delete'>&nbsp;</span>
						      </li>";
					    }
					    echo "</ul>";
					}
			echo "		<div class='menu_img'>
						<span class='add_img'>Ajouter une image</span>
						<span class='open_biblio'>Ouvrir bibliothèque</span>
					</div>";
			echo "</fieldset>";
			break;

		case 'save_sublab_id':
			$release = new releases($_POST['rel_id']);
			$release->sublab_id = $_POST['sublab_id'];
			$release->save();
			break;

		case 'save_art_id':
			art_has_rel::cleanUp($_POST['rel_id']);
			$art_has_rel = new art_has_rel();
			$art_has_rel->rel_id = $_POST['rel_id'];
			$art_has_rel->art_id = $_POST['art_id'];
			$art_has_rel->artrel_order = art_has_rel::getLastFromArtist($_POST['art_id'])+1;
			$art_has_rel->save();
			break;

		case 'save_rel_number':
			$release = new releases($_POST['rel_id']);
			$release->rel_number = $_POST['rel_number'];
			$release->save();
			break;

		case 'save_rel_title':
			$release = new releases($_POST['rel_id']);
			$release->rel_title = $_POST['rel_title'];
			$release->save();
			break;

		case 'save_rel_date':
			$release = new releases($_POST['rel_id']);
			$release->rel_date = date::frtoEn($_POST['rel_date']);
			$release->save();
			break;

		case 'save_rel_content':
			$release = new releases($_POST['rel_id']);
			if(!empty($_POST['rel_content_fr']))
			{
				$rel_content_fr = str_replace("%5C%22%5C%22", "", $_POST['rel_content_fr']);
				$rel_content_fr = str_replace('\\"', "'", $rel_content_fr);
				$rel_content_fr = str_replace("\\'", "'", $rel_content_fr);
				//$rel_content_fr = str_replace("\\\"", "", $rel_content_fr);
				//$rel_content_fr = str_replace("\\", "", $rel_content_fr);
				//$rel_content_fr = str_replace("/>", ">", $rel_content_fr);
				$release->rel_content_fr = $rel_content_fr;
			}
			if(!empty($_POST['rel_content_en']))
			{
				$rel_content_en = str_replace("%5C%22%5C%22", "", $_POST['rel_content_en']);
				$rel_content_en = str_replace('\\"', "'", $rel_content_en);
				$rel_content_en = str_replace("\\'", "'", $rel_content_en);
				//$rel_content_en = str_replace("\\\"", "", $rel_content_en);
				//$rel_content_en = str_replace("\\", "", $rel_content_en);
				//$rel_content_en = str_replace("/>", ">", $rel_content_en);
				$release->rel_content_en = $rel_content_en;
			}
			$release->save();
			break;

		case 'save_rel_download':
			$release = new releases($_POST['rel_id']);
			$release->rel_download = $_POST['rel_download'];
			$release->save();
			break;

		case 'save_rel_paypal':
			$release = new releases($_POST['rel_id']);
			$release->rel_paypal = $_POST['rel_paypal'];
			$release->save();
			break;

		case 'add_new':
			$release = new releases();
			$release->rel_title = "Nouvelle release";
			$release->rel_show = 0;
			$release->sublab_id = 1;
			$release->save();

			echo $release->rel_id.":::
			<div id='rel_id_".$release->rel_id."' data-rel_id='".$release->rel_id."' class='release ui-widget ui-widget-header ui-corner-all'>
				<h2>".$release->rel_title."</h2>
				<label class='label'>Visible sur le site</label>
				<input type='checkbox' class='rel_show' />
				<span class='button_delete'>&nbsp;</span>
			</div>";

			break;

		case 'delete_release':
					releases::delete($_POST['rel_id']);
					break;

		case 'add_track':
					$tracklist = new tracklists();
					$tracklist->tra_order = tracklists::getLastOrder()+1;
					$tracklist->rel_id = $_POST['rel_id'];
					$tracklist->save();
					
					echo "<tr data-tra_id='".$tracklist->tra_id."' data-tra_order='".$tracklist->tra_order."'>";
						echo "<td class='tra_digit'><input type='text' class='ui-state-default'  value='' /></td>";
						echo "<td class='tra_name'><textarea class='ui-state-default'></textarea></td>";
						echo "<td class='tra_url'><input type='text' class='ui-state-default' value='' /></td>";
						echo "<td class='delete'>&nbsp;</td>";
					echo "</tr>";
				
					break;

		case 'save_track':
					$tracklist = new tracklists($_POST['tra_id']);
					$tracklist->tra_digit = $_POST['tra_digit'];
					print_r($_POST['tra_name']);
					$_POST['tra_name'] = str_replace("\n", "<br />", $_POST['tra_name']);
					print_r($_POST['tra_name']);
					$_POST['tra_name'] = str_replace("  ", "&nbsp;&nbsp;", $_POST['tra_name']);
					$_POST['tra_name'] = str_replace("   ", "&nbsp;&nbsp;&nbsp;", $_POST['tra_name']);
					$tracklist->tra_name = $_POST['tra_name'];
					print_r($_POST['tra_name']);
					$tracklist->tra_url = $_POST['tra_url'];
					$tracklist->tra_order = $_POST['tra_order'];
					$tracklist->save();
					break;

		case 'del_track':
					tracklists::delete($_POST['tra_id']);
					break;


		case 'upload_pho':
					$input = fopen("php://input", "r");
					$temp = tmpfile();
					$realSize = stream_copy_to_stream($input, $temp);
					fclose($input);
					if($realSize != (int)$_SERVER["CONTENT_LENGTH"])
					{
						die("ça a fail grave");
					}

					$target = fopen($INI['photos_folder']['real_size'].$_GET['qqfile'], "w");
					fseek($temp, 0, SEEK_SET);
					stream_copy_to_stream($temp, $target);
					fclose($target);

					// traitement après transfert
					$file_name_origin = $_GET['qqfile'];

					// nettoyage du nom
					$file_name_clean = photos::cleanName($file_name_origin);
					// resize
					photos::resizeMe($file_name_origin, $file_name_clean);
					// enresitrement


					$photo			= new photos();
					$photo->pho_name 	= $file_name_clean;
					$photo->pho_legend_fr 	= $file_name_origin;
					$photo->pho_legend_en 	= $file_name_origin;
					$photo->save();
					echo htmlspecialchars(json_encode($photo->pho_id), ENT_NOQUOTES);
					break;

		case 'add_pho':
					$release = new releases($_POST['rel_id']);

					$relpho_id = $release->addPhoto($_POST['pho_id']);
					$rel_has_pho = new rel_has_pho($relpho_id);
					$photo = new photos($rel_has_pho->pho_id);
					//$relpho_legend = "relpho_legend_".$_POST['lan_code'];

					echo "	<li data-pho_id='".$photo->pho_id."' data-sortable='relpho_id_".$rel_has_pho->relpho_id."'>
							<img src='../".$INI["photos_path"]["200x200"].$photo->pho_name."' alt='".$photo->pho_name."' class='rel_pho' />
							<span class='delete'>&nbsp;</span>
						</li>";
					break;

		case 'del_pho':
					$release = new releases($_POST['rel_id']);
					$release->removePhoto($_POST['pho_id']);
					break;

		case 'save_paypal':
					$release = new releases($_POST['rel_id']);
					if(!empty($_POST['rel_paypal_id']))
					{
					      $release->rel_paypal_id = $_POST['rel_paypal_id'];
					}
					if(!empty($_POST['rel_paypal_priceue']))
					{
					      $release->rel_paypal_priceue = $_POST['rel_paypal_priceue'];
					}
					if(!empty($_POST['rel_paypal_priceworld']))
					{
					      $release->rel_paypal_priceworld = $_POST['rel_paypal_priceworld'];
					}
					if(!empty($_POST['rel_paypal_currency_code']))
					{
					      $release->rel_paypal_currency_code = $_POST['rel_paypal_currency_code'];
					}
					if(!empty($_POST['rel_paypal_encrypted']))
					{
					      $release->rel_paypal_encrypted = $_POST['rel_paypal_encrypted'];
					}
					$release->save();
					break;


/*###################################################################
################## BIBLIOTHEQUE PICTOS ###########################################
###################################################################*/

	case "show_biblio":
					$nb_tofs = 36;

					if($_POST['step'] == 'initialisation')
					{
						$photos = photos::getAll($_POST['rel_id']);
						$nbpage = ceil(count($photos)/$nb_tofs);
						$start_p1 = 0;
						$start_p2 = $nb_tofs;
						$pho_p2 = photos::getAll($_POST['rel_id'], $start_p1, $nb_tofs);
						$pho_p3 = photos::getAll($_POST['rel_id'], $start_p2, $nb_tofs);

						if(count($photos)>0)
						{
							echo "
							<div class='biblio_wrapper'>
							<ul class='biblio_img'>
								<li class='biblio_page'>
								</li>
								<li class='biblio_page' data-page='1' data-start='".$start_p1."'>
									<ul>
									";
									foreach($pho_p2 as $pho_id)
									{
										$photo = new photos($pho_id);
										echo "
										<li data-id='".$photo->pho_id."' class='ui-state-default'><a href=\"".'../'.$INI['photos_path']['real_size'].$photo->pho_name."\" title=\"".$photo->pho_name."\"><img src=\"".'../'.$INI['photos_path']['120x80'].$photo->pho_name."?".mt_rand()."\"  class='img_cache' alt=\"".$photo->pho_name."\" /></a></li>
										";
									}
									echo "
									</ul>
								</li>
								<li class='biblio_page' data-page='2' data-start='".$start_p2."'>
									<ul>
									";
									foreach($pho_p3 as $pho_id)
									{
										$photo = new photos($pho_id);
										echo "
										<li data-id='".$photo->pho_id."' class='ui-state-default'><a href=\"".'../'.$INI['photos_path']['real_size'].$photo->pho_name."\" title=\"".$photo->pho_name."\"><img src=\"".'../'.$INI['photos_path']['120x80'].$photo->pho_name."?".mt_rand()."\" class='img_cache' alt=\"".$photo->pho_name."\" /></a></li>
										";
									}
									echo "
									</ul>
								</li>
							</ul>
							</div>
							<ul class='biblio_pagination'>
								<li class='biblio_pagination-prev'><img src=\"".$INI['path']['images']."biblio_arrow_left.png\" alt='Page précédente' title='Page précédente' /></li>
								<li class='biblio_pagination-page'>
									Page <input type='text' value='1' class='current_page ui-state-default'> /<span class='max_page'>".$nbpage."</span>
								</li>
								<li class='biblio_pagination-next'><img src=\"".$INI['path']['images']."biblio_arrow_right.png\" alt='Page suivante' title='Page suivante' /></li>
							</ul>
							<div class='biblio_search'>
								<label for='input_biblio_search'>Recherche</label><input type='text' id='input_biblio_search' /><span id='reset_biblio_search'>Réinitialiser</span>
							</div>
							";
						}
					}
					else
					{
						if(!empty($_POST['page_num']))
						{
							$start = ($_POST['page_num']-1)*$nb_tofs;

							$photos = photos::getAll($_POST['rel_id'],$start,$nb_tofs,$_POST['search']);

							echo "<li class='biblio_page' data-page='".$_POST['page_num']."' data-start='".$start."'>
									<ul>
									";
									foreach($photos as $pho_id)
									{
										$photo = new photos($pho_id);
										echo "
										<li data-id='".$photo->pho_id."' class='ui-state-default'><a href=\"".'../'.$INI['photos_path']['real_size'].$photo->pho_name."\" title=\"".$photo->pho_name."\"><img src=\"".'../'.$INI['photos_path']['120x80'].$photo->pho_name."?".mt_rand()."\" class='img_cache' alt=\"".$photo->pho_name."\" /></a></li>
										";
									}
									echo "
									</ul>
								</li>";
							if($_POST['type'] == 'searchCurrent')
							{
								$photos = photos::getAll($_POST['rel_id'], null, null, $_POST['search']);
								$nbpage = ceil(count($photos)/$nb_tofs);
								echo ":::Page <input type='text' value='1' class='current_page ui-state-default'> /<span class='max_page'>".$nbpage."</span>";
							}
						}
						elseif($_POST['page_num'] == 0)
						{
							echo "<li class='biblio_page'>&nbsp;</li>";
						}
					}
					break;
	}
}
catch(Exception $e) { echo $e->getMessage()."<br/>on ".$e->getFile()." line ".$e->getLine(); };
?>