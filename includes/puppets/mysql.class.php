<?php
//======================================================================//
//== About mysql.class.php
//==--------------------------------------------------------------------//
//== This file is part of puppets library.
//== Copyright (c) 2009-2011 - MAQPRINT
//== Licensed under the GPL version 2.0 license.
//== See LICENSE file or
//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//==--------------------------------------------------------------------//
//== Contributor : Erwan LE LOSTEC
//== Contributor : Patrick PERONNY
//======================================================================//
class mysql
{/**
 * @class mysql
 * @brief MySQL manipulation class.
 * Provides different methods for read and write in a mysql database.
 **/
	public $ID;
	public $server;
	public $user;
	public $password;
	public $database;
	public $charset;
	public $recordset;

	public function mysql($server, $user, $password, $database = null)
	{/** @function mysql()
	 * Create a new mysql connection. set the charset to UTF8.
	 *
	 * @param	server		<string>	the url or ip address of the mysql server
	 * @param	user		<string>	the user authorized to open connection
	 * @param	password	<string>	the password of the user
	 * @param	database	<string>	the database to use
	 *
	 * @return	<handle>
	 **/
		$this->server 		= $server;
		$this->user 		= $user;
		$this->password 	= $password;
		$this->database 	= $database;
		$this->charset 		= "UTF8";

		if(function_exists("mysqli_connect")) { $this->ID = mysqli_connect($this->server, $this->user, $this->password, $this->database); } else { $this->ID = mysql_connect($this->server, $this->user, $this->password, $this->database); };
		if($this->ID)
		{
			if(!function_exists("mysqli_connect")) { $this->selectDatabase($database); };
			$this->setCharset($this->charset);

			return true;
		}
		else { throw New Exception ("Enable to contact the mysql server : ".$this->error().", try again later. If the problem persist contact your system's administrator."); return false; };
	}
	public function error()
	{/** @function error()
	 * Get the last mysql error message.
	 *
	 * @return	<string>
	 **/
		if(function_exists("mysqli_error")) { return mysqli_error($this->ID); } else { mysql_error($this->ID); };
	}
	public function setCharset($charset)
	{/** @function setCharset()
	 * Select a specific charset.
	 *
	 * @param	charset	<string>	the charset code to use (UTF-8, CP1251, ISO 8859-1, ISO 8859-15, ...)
	 *
	 * @return	<boolean>
	 **/
		if(function_exists("mysqli_set_charset")) { $result = mysqli_set_charset($this->ID, $charset); } else { $result = mysql_set_charset($charset, $this->ID); };
		if($result) { return true; } else { throw New Exception ("Enable to change the charset on the mysql server : ".$this->error().", try again later. If the problem persist contact your system's administrator."); return false; };
	}
	public function selectDatabase($database)
	{/** @function selectDatabase()
	 * Select a specific database.
	 *
	 * @param	database	<string>	the database to use.
	 *
	 * @return	<boolean>
	 **/
		$this->database 	= $database;

		if(function_exists("mysqli_select_db")) { $result = mysqli_select_db($this->ID, $this->database); } else { $result = mysql_select_db($this->database, $this->ID); };
		if($result) { return true; } else { throw New Exception ("Enable to select this database on the mysql server : ".$this->error().", try again later. If the problem persist contact your system's administrator."); return false; };
	}
	public function query($sql)
	{/** @function query()
	 * Execute a SQL query
	 *
	 * @param	sql		<string>	the SQL query to execute.
	 *
	 * @return	<boolean>
	 **/
		if(function_exists("mysqli_query")) { $query_resultat = mysqli_query($this->ID, $sql); } else { $query_resultat = mysql_query($sql, $this->ID); };

		if(!$query_resultat) 	{ throw New Exception ("The request ($sql) cannot be perform due to an error : ".$this->error().", try again later. If the problem persist contact your system's administrator."); return false; }
		else 					{ $recordset = new recordset($query_resultat); return $recordset; }
	}
	public function lastInsertId()
	{/** @function lastInsertId()
	 * Return the last insert Id.
	 *
	 * @return	nothing
	 **/
		if(function_exists("mysqli_query")) { return mysqli_insert_id($this->ID); } else { return mysql_insert_id($this->ID); };
	}
	public function beginTransactions()
	{/** @function beginTransactions()
	 * Begin the transactions mode of mysql.
	 *
	 * @return	<integer>
	 **/
		if(function_exists("mysqli_query")) { mysqli_query($this->ID, "BEGIN"); } else { mysql_query("BEGIN", $this->ID); };
	}
	public function endTransactions($flag)
	{/** @function endTransactions()
	 * End the transactions mode of mysql, COMMIT if $flag = false, or ROLLBACK if true.
	 *
	 * @param	flag		<boolean>	the boolean to commit or not.
	 *
 	 * @return	nothing
	 **/
		if($flag) { if(function_exists("mysqli_query")) { mysqli_query($this->ID, "ROLLBACK"); } else { mysql_query("ROLLBACK", $this->ID); }; } else { if(function_exists("mysqli_query")) { mysqli_query($this->ID, "COMMIT"); } else { mysql_query("COMMIT", $this->ID); }; };
	}
	public function secure($string)
	{/** @function secure()
	 * Secure the input string against the mysql injection.
	 *
	 * @param	string		<string>	the string to secure.
	 *
	 * @return	<string>
	 **/
		if(function_exists("mysqli_query")) { $string = mysqli_real_escape_string($this->ID, $string); } else { $string = mysql_real_escape_string($string, $this->ID); };

		return $string;
	}
	function generateClass($table, $path = null)
	{/** @function generateClass()
	 * Get the informations of a mysql table and write a PHP class of it.
	 *
	 * @param	string		<string>	the string to secure.
	 * @param	string		<string>	the string to secure.
	 *
	 * @return	<string>
	 **/
		$class = "";

		$RqColumns = $this->query("SHOW COLUMNS FROM ".$table.";");
		if($RqColumns->numRows() > 0)
		{//-- Get all columns object of the table
			while($objColumns = $RqColumns->fetchRow())
			{
				$columns[] = $objColumns;
				if($objColumns->Key == "PRI") { $id = $objColumns; };
			}
		}

		$class .= "<?php\n".
				  "//======================================================================//\n".
				  "//== About $table.class.php\n".
				  "//==--------------------------------------------------------------------//\n".
				  "//== This file is generated by mysql.class.php of puppets library.\n".
				  "//== Copyright (c) 2009-2011 - MAQPRINT\n".
				  "//== Licensed under the GPL version 2.0 license.\n".
				  "//== See LICENSE file or\n".
				  "//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html\n".
				  "//==--------------------------------------------------------------------//\n".
				  "//== Contributor : Erwan LE LOSTEC\n".
				  "//== Contributor : Patrick PERONNY\n".
				  "//======================================================================//\n".
				  "class $table\n".
				  "{/** @class $table\n".
				  "  * @brief $table table management.\n".
				  "  * Provides different methods for read and write in a $table table.\n".
				  "  **/\n";
		for($c = 0; $c < count($columns); $c++)
		{
			$class .= "	public \$".$columns[$c]->Field.";\n";
		}

		$class .= "\n	private \$mysql;\n\n";
		{//-- constructor
			$class .= "	public function __construct(\$".$id->Field." = null)\n".
					  "	{/** @function $table()\n".
					  "	  * Create a new $table object.\n".
					  "	  * \n".
					  "	  * @param	".$id->Field."		<".$id->Type.">	the id of $table to load\n".
					  "	  * \n".
					  "	  * @return	<boolean>\n".
					  "	  **/\n".
					  "		global \$mysql;\n";
			$class .= "\n		\$this->mysql	= \$mysql;\n";
			for($c = 0; $c < count($columns); $c++)
			{
				if($columns[$c]->Key != "PRI")
				{
					if($columns[$c]->Default != "") { $class .= "		\$this->".$columns[$c]->Field."	= ".$columns[$c]->Default.";\n"; }
					else 							{ $class .= "		\$this->".$columns[$c]->Field."	= null;\n"; };
				}
				else
				{
					$class .= "\n		\$this->".$columns[$c]->Field."	= \$this->mysql->secure(\$".$id->Field.");\n";
					$pri_field = $columns[$c]->Field;
				}
			}
			$class .= "		if(\$".$id->Field." != null)\n".
					  "		{\n".
					  "			\$req_".$table." = \$this->mysql->query(\"SELECT * FROM $table WHERE ".$id->Field."='\".\$this->".$pri_field.".\"' LIMIT 1;\");\n".
					  "			if(\$req_".$table."->numRows() > 0)\n".
					  "			{\n".
					  "				\$obj_".$table." = \$req_".$table."->fetchRow();\n";
			for($c = 0; $c < count($columns); $c++)
			{
				$class .= "				\$this->".$columns[$c]->Field."	= \$obj_".$table."->".$columns[$c]->Field.";\n";
			}
			$class .=  "			}\n".
					  "			else
			{
				error_log(\"Error while loading $table, there no $table of id : $".$id->Field.".\");
				throw new Exception(\"Error while loading $table, there no $table of id : $".$id->Field.".\");\n				return false; \n			};\n".
					  "		}\n";

			$class .= "		return true;\n".
					  "	}\n";
		}
		{//-- save
			$class .= "	public function save()\n".
					  "	{/** @function save()\n".
					  "	 * Insert or update a $table.\n".
					  "	 *\n".
					  "	 * @return	<mixed>		return false or the id of inserted/updated $table.\n".
					  "	 **/\n".
					  "		\$flag_error = false;\n\n".
					  "		\$this->mysql->beginTransactions();\n\n".
					  "		if(empty(\$this->".$id->Field."))\n".
					  "		{//-- Insert\n".
					  "			if(!\$this->mysql->query(\"INSERT INTO $table (";
			for($c = 0; $c < count($columns); $c++)
			{
				if($columns[$c]->Key != "PRI")
				{
					$class .= $columns[$c]->Field;
					if( ($c + 1) < count($columns) ) { $class .= ", "; };
				}
			}
			$class .= ")\n									VALUES (";
			for($c = 0; $c < count($columns); $c++)
			{
				if($columns[$c]->Key != "PRI")
				{
					if(preg_match_all("/(tinyint|smallint|mediumint|int|bigint|decimal|float|double|real|bit|bool|serial)/i", $columns[$c]->Type, $tmp) > 0) { $class .= "\".\$this->mysql->secure(\$this->".$columns[$c]->Field.").\""; }
					else { $class .= "'\".\$this->mysql->secure(\$this->".$columns[$c]->Field.").\"'"; };

					if( ($c + 1) < count($columns) ) { $class .= ", "; };
				}
			}
			$class .= ");\")) { \$flag_error = true; };\n".
					  "			\$this->".$id->Field." = \$this->mysql->lastInsertId();\n".
					  "		}\n".
				 	  "		else\n".
					  "		{//-- Update\n".
					  "			if(!\$this->mysql->query(\"UPDATE $table SET ";
			for($c = 0; $c < count($columns); $c++)
			{
				if($columns[$c]->Key != "PRI")
				{
					if(preg_match_all("/(tinyint|smallint|mediumint|int|bigint|decimal|float|double|real|bit|bool|serial)/i", $columns[$c]->Type, $tmp) > 0) { $class .= $columns[$c]->Field."='\".\$this->mysql->secure(\$this->".$columns[$c]->Field.").\"'"; }
					else { $class .= $columns[$c]->Field."='\".\$this->mysql->secure(\$this->".$columns[$c]->Field.").\"'"; };

					if( ($c + 1) < count($columns) ) { $class .= ", "; };
				}
			}
			$class .= "\n									WHERE ".$id->Field."=\".\$this->mysql->secure(\$this->".$id->Field.").\";\")) { \$flag_error = true; };\n".
					  "		}\n\n".
					  "		\$this->mysql->endTransactions(\$flag_error);\n\n".
					  "		if(\$flag_error)\n		{\n			error_log(\"Error while inserting/updating the $table.\");\n			throw new Exception(\"Error while inserting/updating the $table.\");\n			return false;\n		}\n".
					  "		else
		{\n			return \$this->".$id->Field.";\n		};\n".
					  "	}\n";
		}
		{//-- delete
			$class .= "	public static function delete(\$".$id->Field.")\n".
					  "	{/** @function delete()\n".
					  "	 * Delete the $table.\n".
					  "	 *\n".
					  "	 * @param	".$id->Field."	<".$id->Type.">	the id of the $table to delete\n".
					  "	 *\n".
					  "	 * @return	<boolean>\n".
					  "	 **/\n".
					  "		global \$mysql;\n\n".
					  "		\$flag_error = false;\n\n".
					  "		\$mysql->beginTransactions();\n\n".
					  "		if(!\$mysql->query(\"DELETE FROM $table WHERE ".$id->Field."=\".\$mysql->secure(\$".$id->Field.").\";\")) { \$flag_error = true; };\n\n".
					  "		\$mysql->endTransactions(\$flag_error);\n\n".
					  "		if(\$flag_error)\n		{\n			error_log(\"Error while deleting the $table. - DELETE FROM $table WHERE ".$id->Field."=\".\$mysql->secure(\$".$id->Field."));\n			throw new Exception(\"Error while deleting the $table.\");\n			return false;\n		}\n".
					  "		else\n		{\n			return true;\n		};\n".
					  "	}\n";
		}
		$class .= "}\n".
				  "?>";

		if($file = fopen($path.strtolower($table).".class.php", "w"))
		{//-- Write the file with the generated class
			fwrite($file, $class);
			fclose($file);
		}
	}
}

class recordset
{/**
 * @class recordset
 * @brief MySQL record set class.
 * Provides different methods retrieve variable of a mysql query.
 **/
	public $recordset;

	public function recordset($recordset)
	{/** @function recordset()
	 * Constructor.
	 *
	 * @param	recordset	<object>	the recordset of the sql query
	 *
	 * @return	nothing
	 **/
		$this->recordset	= $recordset;
	}
	public function numRows()
	{/** @function numRows()
	 * Return the number of rows of the query.
	 *
	 * @return	<integer>
	 **/
		if(function_exists("mysqli_num_rows")) { return mysqli_num_rows($this->recordset); } else { return mysql_num_rows($this->recordset); };
	}
	public function fetchRow()
	{/** @function fetchRow()
	 * Fetch to object, the recordset of the query.
	 *
	 * @return	<object>
	 **/
		if(function_exists("mysqli_fetch_object")) { return mysqli_fetch_object($this->recordset); } else { return mysql_fetch_object($this->recordset); };
	}
}
