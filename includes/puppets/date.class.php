<?php
//======================================================================//
//== About date.class.php
//==--------------------------------------------------------------------//
//== This file is part of puppets library.
//== Copyright (c) 2009-2012 MAQPRINT
//== Licensed under the GPL version 2.0 license.
//== See LICENSE file or
//== http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//==--------------------------------------------------------------------//
//== Contributor : Patrick Peronny
//======================================================================//
class date
{/**
  * @class date
  * @brief Date manipulation class. 
  * Provides different methods manipulate date
  **/
	public static function enToFr($datetime=null, $separationvoulue='/')
	{/** @function enToFr()
	  * Transform a date 2010-08-31 17:00:00 (mysql format) to 31-08-2010 17:00:00.
	  *
	  * @param	datetime	<date>
	  * @param	separationvoulue	<string>
	  *
	  * @return	<mixed> new date or null
	  **/
		if (!empty($datetime))
		{
			$tmp = explode(' ', $datetime);
			$date = explode('-', $tmp[0]);
			list($year, $month, $day) = $date;
			$date = $day.$separationvoulue.$month.$separationvoulue.$year;
			return $date.' '.$tmp[1];
		} else { return null; }
	}

	public static function frToEn($datetime=null, $separationorigine='/')
	{/** @function frToEn()
	  * Transform a date 31-08-2010 17:00:00 to 2010-08-31 17:00:00 (mysql format)
	  *
	  * @param	datetime	<date>
	  * @param	separationorigine	<string>
	  *
	  * @return	<mixed> new date or null
	  **/
		if (!empty($datetime))
		{
			$tmp = explode(' ', $datetime);
			$date = explode($separationorigine, $tmp[0]);
			list($day, $month, $year) = $date;
			$date = $year.'-'.$month.'-'.$day;
			return $date.' '.$tmp[1];
		} else { return null; }
	}
}
