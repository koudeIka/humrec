<?php
//======================================================================//
//== About text.class.php
//==--------------------------------------------------------------------//
//== This file is part of puppets library.
//== Copyright (c) 2009-2010 - Erwan LE LOSTEC
//== Licensed under the GPL version 2.0 license.
//== See LICENSE file or
//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//==--------------------------------------------------------------------//
//== Contributor : Erwan LE LOSTEC
//======================================================================//
class text
{/**
  * @class text
  * @brief Strings manipulation class. 
  * Provides different methods for string manipulation.
  **/
	public static function left($string, $length)
	{/** @function left()
	 * Get the $length caracters of the string begining on left.
	 * 
	 * @param	string	<string>	the string to be trunk
	 * @param	length	<integer>	the lenght of string to keep
	 * 
	 * @return	<string>
	 **/
		return substr($string, 0, $length);
	}
	public static function right($string, $length)
	{/** @function right()
	 * Get the $length caracters of the string begining on right.
	 * 
	 * @param	string	<string>	the string to be trunk
	 * @param	length	<integer>	the lenght of string to keep
	 * 
	 * @return	<string>
	 **/
		return substr($string, strlen($string) - $length, strlen($string) - 1);
	}
	public static function middle($string, $begin, $length)
	{/** @function middle()
	 * Get the caracters of the string between $begin and $length.
	 * 
	 * @param	string	<string>	the string to be trunk
	 * @param	begin	<integer>	the begining cursor of string to keep
	 * @param	length	<integer>	the lenght of string to keep
	 * 
	 * @return	<string>
	 **/
		return substr($string, $begin, $length);
	}
	public static function replace($regexp, $replace, $string)
	{/** @function replace()
	 * Search an expression to replace by an another one.
	 * 
	 * @param	$regexp		<string>	the search string
	 * @param	$replace	<string>	the string to replace
	 * @param	$string		<string>	the string to deal
	 * 
	 * @return	<array>
	 **/		
		return preg_replace($regexp, $replace, $string);
	}	
	public static function parseCsv($file, $delimiter = ",")
	{/** @function parseCsv()
	 * Read a csv file and return it to array form or false if the file cannot be open.
	 * 
	 * @param	file		<string>	the file name of csv file
	 * @param	delimiter	<character>	the separation character of the csv file
	 * 
	 * @return	<mixed>
	 **/
		if($file_handle = @fopen($file, "r"))
		{
			$array = Array();
			
			while(!feof($file_handle)) 
			{
				$string		= fgets($file_handle, 4096);
				
				if(function_exists("mb_convert_encoding")) { $string = mb_convert_encoding($string, "UTF-8"); };
				$string 	= preg_replace("/(*ANYCRLF)\.$/m", "", $string);
				
				if(!empty($string)) { $array[] = explode($delimiter, str_replace("\"", "", str_replace("'", "", $string))); };
			}
			
			
			return $array;
		}
		else { return false; };
	}	
	public static function putCharBefore($string, $lenght, $character = "#")
	{/** @function putCharBefore()
	 * Put length characters before the string. Usefull for put 0 before numbers.
	 * 
	 * @param	string		<string>	the string to fill.
	 * @param	lenght		<integer>	the size the tring must be.
	 * @param	character	<character>	the fill character.
	 * 
	 * @return	<array>
	 **/
		$string_length = strlen($string);
		for($i = 0 ; $i  < ($lenght - $string_length) ; $i++)
		{
			$string = $character.$string;
		}
		return $string;
	}
	public static function normalize($string)
	{/** @function normalize()
	 * Replace all accent character into non accent one.
	 * 
	 * @param	string		<string>	the string to convert.
	 * 
	 * @return	<string>
	 **/
		 $table = array("Š"=>"S", "š"=>"s", "Đ"=>"Dj","đ"=>"dj","Ž"=>"Z", "ž"=>"z", "Č"=>"C", "č"=>"c", "Ć"=>"C", "ć"=>"c",
						"À"=>"A", "Á"=>"A", "Â"=>"A", "Ã"=>"A", "Ä"=>"A", "Å"=>"A", "Æ"=>"AE","Ç"=>"C", "È"=>"E", "É"=>"E",
						"Ê"=>"E", "Ë"=>"E", "Ì"=>"I", "Í"=>"I", "Î"=>"I", "Ï"=>"I", "Ñ"=>"N", "Ò"=>"O", "Ó"=>"O", "Ô"=>"O",
						"Õ"=>"O", "Ö"=>"O", "Ø"=>"O", "Ù"=>"U", "Ú"=>"U", "Û"=>"U", "Ü"=>"U", "Ý"=>"Y", "Þ"=>"B", "ß"=>"Ss",
						"à"=>"a", "á"=>"a", "â"=>"a", "ã"=>"a", "ä"=>"a", "å"=>"a", "æ"=>"ae","ç"=>"c", "è"=>"e", "é"=>"e",
						"ê"=>"e", "ë"=>"e", "ì"=>"i", "í"=>"i", "î"=>"i", "ï"=>"i", "ð"=>"o", "ñ"=>"n", "ò"=>"o", "ó"=>"o",
						"ô"=>"o", "õ"=>"o", "ö"=>"o", "ø"=>"o", "ù"=>"u", "ú"=>"u", "û"=>"u", "ü"=>"u", "ý"=>"y", "ý"=>"y", 
						"þ"=>"b", "ÿ"=>"y", "Ŕ"=>"R", "ŕ"=>"r",
					);
	   
		return stripslashes(strtr($string, $table));
	}
}
