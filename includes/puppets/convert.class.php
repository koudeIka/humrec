<?php
//======================================================================//
//== About convert.class.php
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
class convert
{/**
  * @class convert
  * @brief Conversion manipulation class.
  * Provides different methods to make conversion.
  **/
	public static function mmToPx($value, $dpi = 72)
	{/** @function mmToPx()
	 * Convert the $value mm into pixel value depend on $dpi.
	 *
	 * @param	value	<float>		the value to be convert
	 * @param	length	<dpi>		the dots per inch resolution
	 *
	 * @return	<float>
	 **/
		return ($value / (25.4 / $dpi));
	}
	public static function htmlToRgb($color)
	{/** @function left()
	 * Convert the html hexadecimal color value into an RGB array values.
	 *
	 * @param	color	<string>		the color value to be convert
	 *
	 * @return	<array>
	 **/
		if(substr_count(trim($color), "#") > 0)
		{
			$color = str_replace ("#", "", trim($color));

			$R = round(hexdec(substr($color, 0, 2)) / 255, 2);
			$G = round(hexdec(substr($color, 2, 2)) / 255, 2);
			$B = round(hexdec(substr($color, strlen($color) - 2, 2)) / 255, 2);

			return array($R, $G, $B);
		}
	}

	public static function arrayToJson($arr)
	{/** @function array2json()
	* Convert a php array in a json data.
	* Script basiquement pecho sur http://www.bin-co.com/php/scripts/array2json/
	*
	* @param	array	<string>		the array
	*
	* @return	<json>
	**/
		if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
		$parts = array();
		$is_list = false;

		//Pour vérifier que le tableau est un tableau numériqe
		$keys = array_keys($arr);
		$max_length = count($arr)-1;
		if(($keys[0] == 0) && ($keys[$max_length] == $max_length))
		{
			$is_list = true;
			for($i=0; $i<count($keys); $i++)
			{ //chaque clé correspond à sa position
				if($i != $keys[$i])
				{ // Si ça failt c un tableau associatif
					$is_list = false;
					break;
				}
			}
		}

		foreach($arr as $key=>$value)
		{
			if(is_array($value))
			{ //si c'est un tableau => recursion
				if($is_list) $parts[] = array2json($value);
				else $parts[] = '"' . $key . '":' . array2json($value);
			}
			else
			{
				$str = '';
				if(!$is_list)
				{
					$str = '"' . $key . '":';
				}

				// affichage selon le type de données
				if(is_numeric($value)) $str .= $value; //Numbers
				elseif($value === false) $str .= 'false'; //booleans
				elseif($value === true) $str .= 'true';
				else $str .= '"' . addslashes($value) . '"'; //Tous les autres
				// :TODO: A voir pour les données de type object

				$parts[] = $str;
			}
		}
		$json = implode(',',$parts);

		if($is_list)
		{
			return '[' . $json . ']'; // JSON de type numérique simple
		}
		return '{' . $json . '}'; // JSON type tableau associatif
	}


	public static function nlToBr($string)
	{/** @function nl2br()
	* Convert a carriage return to html entity
	*
	* @param	string <string>
	*
	* @return	<string>
	**/
		return strtr($string, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));
	}

	public static function brToNl($string)
	{/** @function br2nl()
	* Convert a <br> or <br/> or <br /> to carriage return
	*
	* @param	string <string>
	*
	* @return	<string>
	**/
		return preg_replace('/<br(\s+)?\/?>/i', "\n", $string);
	}
}
?>