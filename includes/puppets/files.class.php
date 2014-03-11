<?php
//======================================================================//
//== About files.class.php
//==--------------------------------------------------------------------//
//== This file is part of puppets library.
//== Copyright © 2009-2010 - Erwan LE LOSTEC
//== Licensed under the GPL version 2.0 license.
//== See LICENSE file or
//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//==--------------------------------------------------------------------//
//== Contributor : Erwan LE LOSTEC
//======================================================================//
class file
{/**
  * class file
  * brief Files manipulation class.
  * Provides different methods for files manipulation.
  **/
	public $name;
	public $path;
	public $size;
	public $extension;
	public $date_access;
	public $date_edit;
	public $dir_parent;

	function __construct($path)
	{/** function file()
	 * Create a new file object.
	 *
	 * param	$path	<string>	the path of the file
	 *
	 * return	nothing
	 **/
		$path				= str_replace("//", "/", $path);
		$tmp				= explode("/", $path);
		$dir_parent 		= array_splice($tmp, (int)end($tmp), -1);

		$this->type 		= "file"; //-- utile pour la fonction scan  Note de Erwan : pas besoin de ça ! utilisez get_class()
		$this->name 		= pathinfo($path, PATHINFO_BASENAME);
		$this->path 		= $path;
		$this->size			= filesize($path);
		$this->extension 	= pathinfo($path, PATHINFO_EXTENSION);
		$this->dir_parent 	= implode("/", $dir_parent);
		$this->date_access 	= date("d/m/Y H:i:s", filectime($path));
		$this->date_edit	= date("d/m/Y H:i:s", filemtime($path));
	}
	public static function exist($path)
	{/** function exist()
	  * check if the specified file exist.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the file to check.
	  *
	  * return	<mixed>		return true if exist, or false if not exist 
	  **/
		if(file_exists($path)) { return true; } else { return false; };
	}
	public static function readable($path)
	{/** function readable()
	  * check if the specified path is a readeable file.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the file to check.
	  *
	  * return	<mixed>		return true if reabale, or false if not reabale 
	  **/
		if(is_readable($path)) { return true; } else { return false; };
	}
	public static function writable($path)
	{/** function writable()
	  * check if the specified path is a writable file.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the file to check.
	  *
	  * return	<mixed>		return true if writable, or false if not writable 
	  **/
		if(is_writable($path)) { return true; } else { return false; };
	}	
	public static function move($link_file_old=null, $link_file_new=null)
	{
		if ((!empty($link_file_old)) && (!empty($link_file_new)))
		{
			if(!rename("".$link_file_old."","".$link_file_new."")) { return false; } else { return true; }
		} else
		{
			return false;
		}
	}
}
class dir
{/**
  * class dir
  * brief Directories manipulation class.
  * Provides different methods for directories manipulation.
  **/
	public $name;
	public $path;
	public $date_access;
	public $date_edit;
	public $dir_parent;

	function __construct($path)
	{/** function dir()
	 * Create a new directory object.
	 *
	 * param	path	<string>	the path of the directory
	 *
	 * return	nothing
	 **/
		$path 				= str_replace("//", "/", $path);
		$tmp 				= explode("/", $path);
		
		$dir_parent			= array_splice($tmp, (int)end($tmp), -1);

		$this->type 		= 'directory'; // utile pour la fonction scan
		$this->name 		= pathinfo($path, PATHINFO_BASENAME);
		$this->path 		= $path;
		//$this->size			= filesize($path); BORKED
		$this->dir_parent 	= implode("/", $dir_parent);
		$this->date_access 	= date("d/m/Y H:i:s", filectime($path));
		$this->date_edit	= date("d/m/Y H:i:s", filemtime($path));
	}
	public static function scan($folder, $invisibleFiles = null)
	{/** function scan()
	 * Scan a directory, and return a array of files/directories object.
	 *
	 * param	folder			<string>	the path of the folder to scan.
	 * param	invisibleFiles	<array>		an array of string of expression to be exluded from scan.
	 *
	 * return	<array>
	 **/
		//-- set filenames invisible if you want
		if(empty($invisibleFiles)) { $invisibleFiles = array(".", "..", ".htaccess", ".htpasswd"); };

		$dir_content = scandir($folder);
		foreach($dir_content as $key => $content)
		{
			$path = $folder."/".$content;
			if(!in_array($content, $invisibleFiles) && substr($content, 0, 1) != ".")
			{//-- filter all files not accessible
				$tmpPathArray = explode("/", $path);

				if(is_file($path) && is_readable($path))
				{//-- if content is file & readable, add to array
					$files[] = new file($path);
				}
				elseif(is_dir($path) && is_readable($path))
				{//-- if content is a directory and readable, add path and name
					$files[] = new dir($path);
				}
			}
		}

		return $files;
	}
	public static function exist($path)
	{/** function exist()
	  * check if the specified path exist.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the folder to check.
	  *
	  * return	<mixed>		return true if exist, or false if not exist 
	  **/
		if(file_exists($path)) { return true; } else { return false; };
	}
	public static function readable($path)
	{/** function readable()
	  * check if the specified path is a readeable dir.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the folder to check.
	  *
	  * return	<mixed>		return true if reabale, or false if not reabale 
	  **/
		if(is_readable($path)) { return true; } else { return false; };
	}
	public static function writable($path)
	{/** function writable()
	  * check if the specified path is a writable dir.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the folder to check.
	  *
	  * return	<mixed>		return true if writable, or false if not writable 
	  **/
		if(is_writable($path)) { return true; } else { return false; };
	}
	public static function create($path, $mode = 0777, $recursive = true)
	{/** function create()
	  * create a dir on path, with $mode right.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path of the folder to create.
	  * param	mode			<string>	dir's right (0777 is the max).
	  * param	recursive		<booelan>	if sub dir can be created. default=true.
	  *
	  * return	<mixed>		return true if writable, or false if not writable 
	  **/
		if(mkdir($path, $mode, $recursive)) { return true; } else { return false; };
	}
	static function move($from, $to)
	{/** function move()
	  * move a dir on path.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	from			<string>	the path to be moved.
	  * param	to				<string>	the path to be.
	  *
	  * return	<mixed>		return true if ok, or false if not 
	  **/
		if(!rename((string)$from, (string)$to)) { return false; } else { return true; }
	}
	static function copy($origin, $to, $skips_pattern = "/(^\.$|^\.\.$|^\..*$)/i")
	{/** function copy()
	  * create a dir on path, with $mode right.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	origin			<string>	the path to be copied.
	  * param	to				<string>	the path to be.
	  * param	skips_pattern	<regexp>	the files to be excluded (by default '.', '..', '.*'
	  *
	  * return	<mixed>		return true if ok, or false if not 
	  **/
		if(substr($origin, -1) != "/")		{ $origin .= "/"; }
		if(substr($to, -1) != "/") 			{ $to 	  .= "/"; }
		
		if(is_dir($origin))
		{
			if($origin_handle = opendir($origin))
			{
				if(!is_dir($to)) { if(!mkdir($to, 0777)) { throw new Exception("copy failed : can't create distant directory"); return false; }; };
				
				while(($file = readdir($origin_handle)) !== false)
				{
					preg_match_all($skips_pattern, $file, $result);
					
					if(count($result[1]) == 0)
					{
						if(is_dir($origin.$file)) 	{ if(!dir::copy($origin.$file."/" , $to.$file."/"))	{ return false; }; }
						else						{ if(!copy($origin.$file , $to.$file )) 			{ throw new Exception("copy failed : can't copy $file"); return false; }; };
					}
				}
				closedir($origin_handle);
			}
			else { throw new Exception("copy failed : can't read $origin directory");  return false; };
			
			return true;
		};
		
		throw new Exception("copy failed : $origin is not a directory");
		return false;
	}
	static function clear($path)
	{/** function clear()
	  * delete all files and dirs included in path, and then delete dir.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path		<string>	the path to be clear.
	  *
	  * return	<mixed>		return true if ok, or false if not 
	  **/
	  	$skips_pattern = "/(^\.$|^\.\.$)/i";
  		
  		if(substr($path, -1) != "/")		{ $path .= "/"; }

		if($handle = opendir($path))
		{
			while(($file = readdir($handle)) !== false)
			{
				preg_match_all($skips_pattern, $file, $result);
				if(count($result[1]) == 0)
				{
					if(is_dir($path.$file)) 	{ if(!dir::clear($path.$file."/"))	{ return false; }; }
					else						{ if(!unlink($path.$file)) 			{ throw new Exception("clear failed : can't delete $file"); return false; }; };
				};
			};
			
			closedir($handle);
			
			if(!rmdir($path)) { return false; };
			
			return true;
		}
		else { throw new Exception("clear failed : can't read $origin directory");  return false; };
		
		throw new Exception("clear failed : $origin is not a directory");
		return false;
	}
}
