<?php
class artistes
{/** @class artistes
  * @brief artistes table management.
  * Provides different methods for read and write in a artistes table.
  **/
	public $art_id;
	public $art_name;
	public $art_url;
	public $art_description_fr;
	public $art_description_en;
	public $art_homepage;
	public $art_show;
	//public $photos;
	public $pho_ids;
	public $releases;

	private $mysql;

	public function __construct($art_id = null)
	{/** @function artistes()
	  * Create a new artistes object.
	  *
	  * @param	art_id		<int(11)>	the id of artistes to load
	  *
	  * @return	<boolean>
	  **/
		global $mysql;

		$this->mysql	= $mysql;

		$this->art_id	= $this->mysql->secure($art_id);
		$this->art_name	= null;
		$this->art_url	= null;
		$this->art_description_fr	= null;
		$this->art_description_en	= null;
		$this->art_homepage	= null;
		$this->art_show	= 0;
		//$this->photos = array();
		$this->pho_ids	= array();
		$this->releases = array();

		if($art_id != null)
		{
			$req_artistes = $this->mysql->query("SELECT * FROM artistes WHERE art_id='".$this->art_id."' LIMIT 1;");
			if($req_artistes->numRows() > 0)
			{
				$obj_artistes = $req_artistes->fetchRow();
				$this->art_id	= $obj_artistes->art_id;
				$this->art_name	= $obj_artistes->art_name;
				$this->art_url	= $obj_artistes->art_url;
				$this->art_description_fr	= $obj_artistes->art_description_fr;
				$this->art_description_en	= $obj_artistes->art_description_en;
				$this->art_homepage	= $obj_artistes->art_homepage;
				$this->art_show	= $obj_artistes->art_show;
				//$this->photos = $this->getPhotos();
				$this->pho_ids = $this->getPhotos();
				$this->releases = $this->getReleases();
			}
			else
			{
				error_log("Error while loading artistes, there no artistes of id : $art_id.");
				throw new Exception("Error while loading artistes, there no artistes of id : $art_id.");
				return false;
			};
		}
		return true;
	}
	public function save()
	{/** @function save()
	 * Insert or update a artistes.
	 *
	 * @return	<mixed>		return false or the id of inserted/updated artistes.
	 **/
		$flag_error = false;

		$this->mysql->beginTransactions();

		if(empty($this->art_id))
		{//-- Insert
			if(!$this->mysql->query("INSERT INTO artistes (art_name, art_url, art_description_fr, art_description_en, art_homepage, art_show)
									VALUES ('".$this->mysql->secure($this->art_name)."', '".$this->mysql->secure($this->art_url)."', \"".$this->mysql->secure($this->art_description_fr)."\", \"".$this->mysql->secure($this->art_description_en)."\", '".$this->mysql->secure($this->art_homepage)."', ".$this->mysql->secure($this->art_show).");")) { $flag_error = true; };
			$this->art_id = $this->mysql->lastInsertId();
		}
		else
		{//-- Update
			if(!$this->mysql->query("UPDATE artistes SET art_name='".$this->mysql->secure($this->art_name)."', art_url='".$this->mysql->secure($this->art_url)."', art_description_fr=\"".$this->mysql->secure($this->art_description_fr)."\", art_description_en=\"".$this->mysql->secure($this->art_description_en)."\", art_homepage='".$this->mysql->secure($this->art_homepage)."', art_show='".$this->mysql->secure($this->art_show)."'
									WHERE art_id=".$this->mysql->secure($this->art_id).";")) { $flag_error = true; };
		}

		$this->mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while inserting/updating the artistes.");
			throw new Exception("Error while inserting/updating the artistes.");
			return false;
		}
		else
		{
			return $this->art_id;
		};
	}
	public static function delete($art_id)
	{/** @function delete()
	 * Delete the artistes.
	 *
	 * @param	art_id	<int(11)>	the id of the artistes to delete
	 *
	 * @return	<boolean>
	 **/
		global $mysql;

		$flag_error = false;

		$mysql->beginTransactions();

		if(!$mysql->query("DELETE FROM artistes WHERE art_id=".$mysql->secure($art_id).";")) { $flag_error = true; };

		$mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while deleting the artistes. - DELETE FROM artistes WHERE art_id=".$mysql->secure($art_id));
			throw new Exception("Error while deleting the artistes.");
			return false;
		}
		else
		{
			return true;
		};
	}

	public static function getAll($online=true)
	{
		global $mysql;
		if ($online === true)
		{
			$req_all = $mysql->query("SELECT art_id FROM artistes WHERE art_show='1' ORDER BY art_name ASC");
		} else
		{
			$req_all = $mysql->query("SELECT art_id FROM artistes ORDER BY art_name ASC");
		}
		$arts = array();
		while($obj_all = $req_all->fetchRow())
		{
			$arts[] = $obj_all->art_id;
		}
		return $arts;
	}
	
	public static function getByUrl($art_url)
	{
		global $mysql;
		$rels = array();
		$req_all = $mysql->query("	SELECT art_id FROM artistes WHERE art_url='".$mysql->secure($art_url)."' LIMIT 1");
		if($req_all->numRows() > 0)
		{
			$obj_all = $req_all->fetchRow();
			return $obj_all->art_id;
		}
		return false;
	}

// 	public function getPhotos()
// 	{
// 		$req_pho = $this->mysql->query("SELECT * FROM art_has_pho WHERE art_id='".$this->art_id."' ORDER BY artpho_order ASC");
// 		$phos = array();
// 		while($obj_pho = $req_pho->fetchRow())
// 		{
// 			$phos[$obj_pho->artpho_type] = array(
// 				"artpho_id" => $obj_pho->artpho_id,
// 				"pho_id" => $obj_pho->pho_id
// 			);
// 		}
// 		return $phos;
// 	}

	private function getPhotos()
	{
		$phos = array();
		$req_pho = $this->mysql->query("SELECT * FROM art_has_pho WHERE art_id='".$this->art_id."' ORDER BY artpho_order ASC");
		while($obj_pho = $req_pho->fetchRow())
		{
			$phos[] = $obj_pho->pho_id;
		}
		return $phos;
	}

	public function getReleases()
	{
		$req_rel = $this->mysql->query("SELECT * FROM art_has_rel WHERE art_id='".$this->art_id."' ORDER BY artrel_order DESC");
		$rels = array();
		while($obj_rel = $req_rel->fetchRow())
		{
			$rels[] = array(
				"artrel_id" => $obj_rel->artrel_id,
				"art_id" => $obj_rel->art_id
			);
		}
		return $rels;
	}

	public static function exists($art_id)
	{
		global $mysql;

		$req_exists = $mysql->query("SELECT * FROM artistes WHERE art_id='".$mysql->secure($art_id)."';");
		if($req_exists->numRows() > 0)
		{
			return true;
		} else
		{
			return false;
		}
	}

	public static function getNames($art_ids)
	{
		global $mysql;
		$arts = array();
		if(count($art_ids)>0)
		{
			$req_names = $mysql->query("SELECT art_name FROM artistes WHERE art_id IN (".implode(",", $art_ids).");");
			while($obj_names = $req_names->fetchRow())
			{
				$arts[] = $obj_names->art_name;
			}
		}
		return $arts;
	}

	public function addPhoto($pho_id)
	{
		$this->removePhoto($pho_id);
		$art_has_pho = new art_has_pho();
		$art_has_pho->art_id = $this->art_id;
		$art_has_pho->pho_id = $pho_id;
		$art_has_pho->artpho_order = art_has_pho::getLastOrder($this->art_id)+1;
		$art_has_pho->save();
		return $art_has_pho->artpho_id;
	}

	public function removePhoto($pho_id)
	{
		$relpho_id = art_has_pho::exists(null, $this->art_id, $pho_id);
		if ($relpho_id !== false)
		{
			art_has_pho::delete($relpho_id);
		}
	}
}
?>