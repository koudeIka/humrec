<?php
class rel_has_pho
{/** @class rel_has_pho
  * @brief rel_has_pho table management.
  * Provides different methods for read and write in a rel_has_pho table.
  **/
	public $relpho_id;
	public $rel_id;
	public $pho_id;
	public $relpho_order;

	private $mysql;

	public function __construct($relpho_id = null)
	{/** @function rel_has_pho()
	  * Create a new rel_has_pho object.
	  * 
	  * @param	relpho_id		<int(11)>	the id of rel_has_pho to load
	  * 
	  * @return	<boolean>
	  **/
		global $mysql;

		$this->mysql	= $mysql;

		$this->relpho_id	= $this->mysql->secure($relpho_id);
		$this->rel_id	= null;
		$this->pho_id	= null;
		$this->relpho_order	= null;
		if($relpho_id != null)
		{
			$req_rel_has_pho = $this->mysql->query("SELECT * FROM rel_has_pho WHERE relpho_id='".$this->relpho_id."' LIMIT 1;");
			if($req_rel_has_pho->numRows() > 0)
			{
				$obj_rel_has_pho = $req_rel_has_pho->fetchRow();
				$this->relpho_id	= $obj_rel_has_pho->relpho_id;
				$this->rel_id	= $obj_rel_has_pho->rel_id;
				$this->pho_id	= $obj_rel_has_pho->pho_id;
				$this->relpho_order	= $obj_rel_has_pho->relpho_order;
			}
			else
			{
				error_log("Error while loading rel_has_pho, there no rel_has_pho of id : $relpho_id.");
				throw new Exception("Error while loading rel_has_pho, there no rel_has_pho of id : $relpho_id.");
				return false; 
			};
		}
		return true;
	}
	public function save()
	{/** @function save()
	 * Insert or update a rel_has_pho.
	 *
	 * @return	<mixed>		return false or the id of inserted/updated rel_has_pho.
	 **/
		$flag_error = false;

		$this->mysql->beginTransactions();

		if(empty($this->relpho_id))
		{//-- Insert
			if(!$this->mysql->query("INSERT INTO rel_has_pho (rel_id, pho_id, relpho_order)
									VALUES (".$this->mysql->secure($this->rel_id).", ".$this->mysql->secure($this->pho_id).", ".$this->mysql->secure($this->relpho_order).");")) { $flag_error = true; };
			$this->relpho_id = $this->mysql->lastInsertId();
		}
		else
		{//-- Update
			if(!$this->mysql->query("UPDATE rel_has_pho SET rel_id='".$this->mysql->secure($this->rel_id)."', pho_id='".$this->mysql->secure($this->pho_id)."', relpho_order='".$this->mysql->secure($this->relpho_order)."'
									WHERE relpho_id=".$this->mysql->secure($this->relpho_id).";")) { $flag_error = true; };
		}

		$this->mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while inserting/updating the rel_has_pho.");
			throw new Exception("Error while inserting/updating the rel_has_pho.");
			return false;
		}
		else
		{
			return $this->relpho_id;
		};
	}
	public static function delete($relpho_id)
	{/** @function delete()
	 * Delete the rel_has_pho.
	 *
	 * @param	relpho_id	<int(11)>	the id of the rel_has_pho to delete
	 *
	 * @return	<boolean>
	 **/
		global $mysql;

		$flag_error = false;

		$mysql->beginTransactions();

		if(!$mysql->query("DELETE FROM rel_has_pho WHERE relpho_id=".$mysql->secure($relpho_id).";")) { $flag_error = true; };

		$mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while deleting the rel_has_pho. - DELETE FROM rel_has_pho WHERE relpho_id=".$mysql->secure($relpho_id));
			throw new Exception("Error while deleting the rel_has_pho.");
			return false;
		}
		else
		{
			return true;
		};
	}

	public static function getLastOrder($rel_id)
	{
		global $mysql;
		if (!empty($rel_id))
		{
			$req_last = $mysql->query("SELECT relpho_order FROM rel_has_pho WHERE rel_id='".$mysql->secure($rel_id)."' ORDER BY relpho_order DESC LIMIT 1");
			if($req_last->numRows() > 0)
			{
				$obj_last = $req_last->fetchRow();
				return $obj_last->relpho_order;
			} else
			{
				return 0;
			}
		} else
		{
			return false;
		}
	}

	public static function order($list_id=array())
	{
		global $mysql;
		if(count($list_id)>0)
		{
			foreach($list_id as $position => $relpho_id)
			{
				$pos = $position+1;
				$sql[] = $mysql->query("UPDATE rel_has_pho SET relpho_order = '".$pos."' WHERE relpho_id = '".$relpho_id."' ");
			}
			return ($sql);
		}
		else
		{
			return false;
		}
	}

	public static function exists($relpho_id=null, $rel_id=null, $pho_id=null)
	{
		global $mysql;
		if(!empty($pho_id) && !empty($rel_id))
		{
			$req_exists = $mysql->query("SELECT relpho_id FROM rel_has_pho WHERE rel_id='".$mysql->secure($rel_id)."' and pho_id='".$mysql->secure($pho_id)."' LIMIT 1");
			if($req_exists->numRows() > 0)
			{
				$obj_exists = $req_exists->fetchRow();
				return $obj_exists->relpho_id;
			}
			else
			{
				return false;
			}
		}
		return false;
	}
}
?>