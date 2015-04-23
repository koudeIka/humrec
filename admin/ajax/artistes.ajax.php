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
			$artiste = new artistes($_POST['art_id']);
			if ($artiste->art_show == 0)
			{
				$artiste->art_show = 1;
			} else
			{
				$artiste->art_show = 0;
			}
			$artiste->save();
			break;


		case 'load_artiste':
			$artiste = new artistes($_POST['art_id']);
			echo "
			<fieldset class='art_name'>
				<legend>Nom</legend>
				<input type='text' value=\"".$artiste->art_name."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='art_url'>
				<legend>Url personnalisé</legend>
				<input type='text' value=\"".$artiste->art_url."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='art_homepage'>
				<legend>Homepage</legend>
				<input type='text' value=\"".$artiste->art_homepage."\" class='ui-state-default' />
			</fieldset>
			<fieldset class='art_description_fr'>
				<legend>Description FRENCH</legend>
				<textarea>".$artiste->art_description_fr."</textarea>
			</fieldset>
			<fieldset class='art_description_en'>
				<legend>Description ENGLISH</legend>
				<textarea>".$artiste->art_description_en."</textarea>
			</fieldset>
			";
			echo "<fieldset class='art_pictures'>
					<legend>Images</legend>";
					if (count($artiste->pho_ids)>0)
					{
					    echo "<ul id='photo_list'>";
					    foreach($artiste->pho_ids as $pho_id)
					    {
						$photo = new photos($pho_id);
						// data-sortable='relpho_id_".$rel_has_pho->relpho_id."'
						echo "<li data-pho_id='".$photo->pho_id."'>
							    <img src='../".$INI['photos_path']['200x200'].$photo->pho_name."' alt='".$photo->pho_name."' class='art_pho' />
							    <span class='delete'>&nbsp;</span>
						      </li>";
					    }
					    echo "</ul>";
					}
			echo "	<div class='menu_img'>
						<span class='add_img'>Ajouter une image</span>
						<span class='open_biblio'>Ouvrir bibliothèque</span>
					</div>";
			echo "</fieldset>";
			break;

		case 'save_art_name':
			$artiste = new artistes($_POST['art_id']);
			$artiste->art_name = $_POST['art_name'];
			$artiste->save();
			break;
			
		case 'save_art_url':
			$artiste = new artistes($_POST['art_id']);
			$artiste->art_url = $_POST['art_url'];
			$artiste->save();
			break;

		case 'save_art_homepage':
			$artiste = new artistes($_POST['art_id']);
			$artiste->art_homepage = $_POST['art_homepage'];
			$artiste->save();
			break;

		case 'save_art_description':
			$artiste = new artistes($_POST['art_id']);
			if(!empty($_POST['art_description_fr']))
			{
				$art_description_fr = str_replace("%5C%22%5C%22", "", $_POST['art_description_fr']);
				$art_description_fr = str_replace('\\"', "'", $art_description_fr);
				$art_description_fr = str_replace("\\'", "'", $art_description_fr);
				//$art_description_fr = str_replace("\\\"", "", $art_description_fr);
				//$art_description_fr = str_replace("\\", "", $art_description_fr);
				//$art_description_fr = str_replace("/>", ">", $art_description_fr);
				$artiste->art_description_fr = $art_description_fr;
			}
			if(!empty($_POST['art_description_en']))
			{
				$art_description_en = str_replace("%5C%22%5C%22", "", $_POST['art_description_en']);
				$art_description_en = str_replace('\\"', "'", $art_description_en);
				$art_description_en = str_replace("\\'", "'", $art_description_en);
				//$art_description_en = str_replace("\\\"", "", $art_description_en);
				//$art_description_en = str_replace("\\", "", $art_description_en);
				//$art_description_en = str_replace("/>", ">", $art_description_en);
				$artiste->art_description_en = $art_description_en;
			}
			$artiste->save();
			break;

// 		case 'upload_image':
// 			$ext = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
// 			$ext = strtolower($ext);
//
// 			$nom_fichier = "accueil-".$_POST['acc_id'].".".$ext;
//
// 			if (move_uploaded_file($_FILES['userfile']['tmp_name'], $INI['folder']['accueil'].$nom_fichier))
// 			{
// 				$accueil = new accueil($_POST['acc_id']);
// 				$accueil->acc_img = $nom_fichier;
// 				$accueil->save();
// 			}
//
//
// 			break;
//
//
// 		case 'load_image_standalone':
// 			$accueil = new accueil($_POST['acc_id']);
// 			echo "<span class='delete_img ui-state-default ui-corner-all'><span class='ui-icon ui-icon-circle-close'>&nbsp;</span></span><img src='".$INI['path']['accueil'].$accueil->acc_img."' />";
// 			break;
//
// 		case 'delete_image':
// 			$accueil = new accueil($_POST['acc_id']);
// 			@unlink($INI['folder']['accueil'].$accueil->acc_img);
// 			$accueil->acc_img = "";
// 			$accueil->save();
// 			break;
//
// 		case 'load_popup_link':
//
// 			switch($_POST['type'])
// 			{
// 				case "actualites":
// 					echo "<label>Lien vers l'actualité...</label><br/><select name='actualite'>
// 						<option value=''>...</option>
// 					";
// 					foreach (actualites::getAll() as $act_id)
// 					{
// 						$actualite = new actualites($act_id);
// 						echo "<option value='".$actualite->act_id."'>".$actualite->act_title."</option>";
// 					}
// 					echo "</select>";
// 					break;
//
// 				case "promotions":
// 					echo "<label>Lien vers la promotion...</label><br/><select name='promotion'>
// 						<option value=''>...</option>
// 					";
// 					foreach (promotions::getAll() as $pro_id)
// 					{
// 						$promotion = new promotions($pro_id);
// 						echo "<option value='".$promotion->pro_id."'>".$promotion->pro_title."</option>";
// 					}
// 					echo "</select>";
// 					break;
// 			}
//
//
// 			break;
//
// 		case 'save_link':
// 			$accueil = new accueil($_POST['acc_id']);
// 			$accueil->acc_link = $_POST['acc_link'];
// 			$accueil->acc_link_type = $_POST['acc_link_type'];
// 			$accueil->acc_link_show = $_POST['acc_link_show'];
// 			$accueil->save();
// 			break;
//
		case 'add_new':
			$artiste = new artistes();
			$artiste->art_name = "Nouveau artiste";
			$artiste->art_show = 0;
			$artiste->save();

			echo $artiste->art_id.":::
			<div id='art_id_".$artiste->art_id."' data-art_id='".$artiste->art_id."' class='artiste ui-widget ui-widget-header ui-corner-all'>
				<h2>".$artiste->art_name."</h2>
				<label class='label'>Visible sur le site</label>
				<input type='checkbox' class='new_show' />
				<span class='button_delete'>&nbsp;</span>
			</div>";

			break;

		case 'delete_artiste':
			artistes::delete($_POST['art_id']);
			break;

/* ############## PHOTOS ####################################### */

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


					$photo				= new photos();
					$photo->pho_name 		= $file_name_clean;
					$photo->pho_legend_fr 	= $file_name_origin;
					$photo->pho_legend_en 	= $file_name_origin;
					$photo->save();
					echo htmlspecialchars(json_encode($photo->pho_id), ENT_NOQUOTES);
					break;

		case 'add_pho':
					$artiste = new artistes($_POST['art_id']);

					$artpho_id = $artiste->addPhoto($_POST['pho_id']);
					$art_has_pho = new art_has_pho($artpho_id);
					$photo = new photos($art_has_pho->pho_id);
					//$relpho_legend = "relpho_legend_".$_POST['lan_code'];

					echo "	<li data-pho_id='".$photo->pho_id."' data-sortable='artpho_id_".$art_has_pho->artpho_id."'>
							<img src='../".$INI["photos_path"]["200x200"].$photo->pho_name."' alt='".$photo->pho_name."' class='art_pho' />
							<span class='delete'>&nbsp;</span>
						</li>";
					break;

		case 'del_pho':
					$artiste = new artistes($_POST['art_id']);
					$artiste->removePhoto($_POST['pho_id']);
					break;

/*###################################################################
################## BIBLIOTHEQUE PICTOS ###########################################
###################################################################*/

	case "show_biblio":
					$nb_tofs = 36;

					if($_POST['step'] == 'initialisation')
					{
						$photos = photos::getAll($_POST['art_id']);
						$nbpage = ceil(count($photos)/$nb_tofs);
						$start_p1 = 0;
						$start_p2 = $nb_tofs;
						$pho_p2 = photos::getAll($_POST['art_id'], $start_p1, $nb_tofs);
						$pho_p3 = photos::getAll($_POST['art_id'], $start_p2, $nb_tofs);

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

							$photos = photos::getAll($_POST['art_id'],$start,$nb_tofs,$_POST['search']);

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
								$photos = photos::getAll($_POST['art_id'], null, null, $_POST['search']);
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