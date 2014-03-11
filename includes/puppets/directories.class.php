<?php
class directories
{/**
  * class directories
  * brief Directories manipulation class.
  * Provides different methods for directories manipulation.
  **/
	public $fileInfo;

	function __construct($path)
	{/** function dir()
	 * Create a new directory object.
	 *
	 * param	path	<string>	the path of the directory
	 *
	 * return	nothing
	 **/
		$this->fileInfo = new SplFileInfo($path);
	}

	public function hasChildren()
	{/** function hasChildren()
	  * check if the dir has at least one subfile or one subdir.
	  *
	  * return	<mixed>		return true if it has children, or false
	  **/
		$result = false;
		if($dh = opendir($this->fileInfo->getRealPath()))
		{
			while(!$result && ($file = readdir($dh)) !== false)
			{
				$result = $file !== "." && $file !== "..";
			}
			closedir($dh);
		}
		return $result;
	}

	public function getParentName()
	{/** function getParent()
	  * check if the dir has at least one subfile or one subdir.
	  *
	  * return	<mixed>		return true if it has children, or false
	  **/
		return basename(dirname($this->fileInfo->getPathname()));

	}

	public static function scan($path, $recursive = false, $skip_files = array())
	{/** function scan()
	  * scan a dir.
	  * on Windows, use the path format like this //computername/share/filename or \\\\computername\share\filename
	  *
	  * param	path			<string>	the path to the folder.
	  * param	recursive		<bool>	.
	  * param	skip_files		<array>		the files to be excluded (by default '.', '..', '.htaccess', '.htpasswd', '.svn')
	  *
	  * return	<mixed>		return true if ok, or false if not
	  **/
		if(!in_array(".", $skip_files))
		{
			array_push($skip_files, ".", "..", ".htaccess", ".htpasswd", ".svn");
		}
		$files = array();
		foreach(scandir($path) as $file_name)
		{
			if(!in_array($file_name, $skip_files))
			{
				$fileInfo = new SplFileInfo($path.$file_name);
				$files[] = $fileInfo->getRealPath();
				if( $fileInfo->isDir() && ($recursive === true) )
				{
					$files = array_merge($files, directories::scan($fileInfo->getRealPath().'/', $recursive, $skip_files));
				}
			}
		}
		return $files;
	}

	public static function buildTree($path, $type = "html", $path_filter = null)
	{
		if($type == 'html')
		{
			$files_list = directories::scan($path, false);
			if(count($files_list) > 0)
			{
				echo "<ul class='closed'>";
				foreach($files_list as $file_path)
				{
					$file = new directories($file_path);
					echo "<li class='".$file->fileInfo->getType()."' data-puppets-filetype='".$file->fileInfo->getType()."'>
							<a data-puppets-path-from-root='".str_replace($path_filter, '', $file->fileInfo->getRealPath())."' href='".str_replace($path_filter, '', $file->fileInfo->getRealPath())."'>".$file->fileInfo->getFilename()."</a>";
							if($file->fileInfo->isDir() && $file->hasChildren())
							{
								directories::buildTree($file->fileInfo->getRealPath()."/", $type, $path_filter);
							}
					echo "</li>";
				}
				echo "</ul>";
			}
		}
	}

	public static function move($from, $to)
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

	public static function copy($origin, $to, $skips_pattern = "/(^\.$|^\.\.$|^\..*$)/i")
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


}
?>