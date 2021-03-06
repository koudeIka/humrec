<?php
//======================================================================//
//== About new_has_pho.class.php
//==--------------------------------------------------------------------//
//== This file is generated by mysql.class.php of puppets library.
//== Copyright (c) 2009-2011 - MAQPRINT
//== Licensed under the GPL version 2.0 license.
//== See LICENSE file or
//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//==--------------------------------------------------------------------//
//== Contributor : Erwan LE LOSTEC
//== Contributor : Patrick PERONNY
//======================================================================//
class new_has_pho
{/** @class new_has_pho
  * @brief new_has_pho table management.
  * Provides different methods for read and write in a new_has_pho table.
  **/
	public $newpho_id;
	public $new_id;
	public $pho_id;
	public $newpho_order;
	public $newpho_type;

	private $mysql;

	public function __construct($newpho_id = null)
	{/** @function new_has_pho()
	  * Create a new new_has_pho object.
	  *
	  * @param	newpho_id		<int(11)>	the id of new_has_pho to load
	  *
	  * @return	<boolean>
	  **/
		global $mysql;

		$this->mysql	= $mysql;

		$this->newpho_id	= $this->mysql->secure($newpho_id);
		$this->new_id	= null;
		$this->pho_id	= null;
		$this->newpho_order	= null;
		$this->newpho_type	= main;
		if($newpho_id != null)
		{
			$req_new_has_pho = $this->mysql->query("SELECT * FROM new_has_pho WHERE newpho_id='".$this->newpho_id."' LIMIT 1;");
			if($req_new_has_pho->numRows() > 0)
			{
				$obj_new_has_pho = $req_new_has_pho->fetchRow();
				$this->newpho_id	= $obj_new_has_pho->newpho_id;
				$this->new_id	= $obj_new_has_pho->new_id;
				$this->pho_id	= $obj_new_has_pho->pho_id;
				$this->newpho_order	= $obj_new_has_pho->newpho_order;
				$this->newpho_type	= $obj_new_has_pho->newpho_type;
			}
			else
			{
				error_log("Error while loading new_has_pho, there no new_has_pho of id : $newpho_id.");
				throw new Exception("Error while loading new_has_pho, there no new_has_pho of id : $newpho_id.");
				return false;
			};
		}
		return true;
	}
	public function save()
	{/** @function save()
	 * Insert or update a new_has_pho.
	 *
	 * @return	<mixed>		return false or the id of inserted/updated new_has_pho.
	 **/
		$flag_error = false;

		$this->mysql->beginTransactions();

		if(empty($this->newpho_id))
		{//-- Insert
			if(!$this->mysql->query("INSERT INTO new_has_pho (new_id, pho_id, newpho_order, newpho_type)
									VALUES (".$this->mysql->secure($this->new_id).", ".$this->mysql->secure($this->pho_id).", ".$this->mysql->secure($this->newpho_order).", '".$this->mysql->secure($this->newpho_type)."');")) { $flag_error = true; };
			$this->newpho_id = $this->mysql->lastInsertId();
		}
		else
		{//-- Update
			if(!$this->mysql->query("UPDATE new_has_pho SET new_id='".$this->mysql->secure($this->new_id)."', pho_id='".$this->mysql->secure($this->pho_id)."', newpho_order='".$this->mysql->secure($this->newpho_order)."', newpho_type='".$this->mysql->secure($this->newpho_type)."'
									WHERE newpho_id=".$this->mysql->secure($this->newpho_id).";")) { $flag_error = true; };
		}

		$this->mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while inserting/updating the new_has_pho.");
			throw new Exception("Error while inserting/updating the new_has_pho.");
			return false;
		}
		else
		{
			return $this->newpho_id;
		};
	}
	public static function delete($newpho_id)
	{/** @function delete()
	 * Delete the new_has_pho.
	 *
	 * @param	newpho_id	<int(11)>	the id of the new_has_pho to delete
	 *
	 * @return	<boolean>
	 **/
		global $mysql;

		$flag_error = false;

		$mysql->beginTransactions();

		if(!$mysql->query("DELETE FROM new_has_pho WHERE newpho_id=".$mysql->secure($newpho_id).";")) { $flag_error = true; };

		$mysql->endTransactions($flag_error);

		if($flag_error)
		{
			error_log("Error while deleting the new_has_pho. - DELETE FROM new_has_pho WHERE newpho_id=".$mysql->secure($newpho_id));
			throw new Exception("Error while deleting the new_has_pho.");
			return false;
		}
		else
		{
			return true;
		};
	}

	public static function getLastOrder($new_id)
	{
		global $mysql;
		if (!empty($art_id))
		{
			$req_last = $mysql->query("SELECT newpho_order FROM new_has_pho WHERE new_id='".$mysql->secure($new_id)."' ORDER BY newpho_order DESC LIMIT 1");
			if($req_last->numRows() > 0)
			{
				$obj_last = $req_last->fetchRow();
				return $obj_last->newpho_order;
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
			foreach($list_id as $position => $newpho_id)
			{
				$pos = $position+1;
				$sql[] = $mysql->query("UPDATE new_has_pho SET newpho_order = '".$pos."' WHERE newpho_id = '".$newpho_id."' ");
			}
			return ($sql);
		}
		else
		{
			return false;
		}
	}

	public static function exists($newpho_id=null, $new_id=null, $pho_id=null)
	{
		global $mysql;
		if(!empty($pho_id) && !empty($new_id))
		{
			$req_exists = $mysql->query("SELECT newpho_id FROM new_has_pho WHERE new_id='".$mysql->secure($new_id)."' and pho_id='".$mysql->secure($pho_id)."' LIMIT 1");
			if($req_exists->numRows() > 0)
			{
				$obj_exists = $req_exists->fetchRow();
				return $obj_exists->newpho_id;
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