<?php

class html
{/**
  * @class html
  * @brief HTML related manipulation class.
  * Provides different methods to output HTML.
  **/
	public $doctype;				/*!< doctype the website */
  	public $author;					/*!< define the author of the website */
	public $category;				/*!< define the category of the website */
	public $content_langage;		/*!< define the langage(s) to use */
	public $content_type;			/*!< define the charactter table to use */
	public $content_script_type;	/*!< allow the javascript event manager */
	public $copyright;				/*!< define the copyrght of the website */
	public $base;
	public $description; 			/*!< describe the website */
  	public $favicon;				/*!< define the favorite icon */
	public $generator;				/*!< define the program used to create of the website */
	public $googlebot;				/*!< allow/deny caching by google bots (archive|noarchive) */
	public $identifier_url;			/*!< define the domain of the page */
	public $keywords; 				/*!< principal keywords of the website per langage */
	public $publisher;				/*!< define the name of the publisher */
	public $robots;					/*!< allow/deny the indexation by bots (all|none|index|noindex|follow|nofollow) */
	public $title;					/*!< the title of the page */
	public $viewport;				/*!< precise the attribut of viewport (ex: width=200px) */

	public $css;					/*!< links to css files */
	public $scripts;				/*!< links to javascript files */
	public $script_inline;			/*!< insert a javascript code in head */

	public $css_fix;				/*!< links to internet explorer css files */
	public $script_fix;			/*!< links to internet explorer javascript hack */

  	public function __construct($doctype = null)
  	{/** @function __construct()
	 * Create a new html object.
	 *
	 * @return	nothing
	 **/
		$this->doctype					= $doctype;
  		$this->author					= null;
		$this->category					= null;
		$this->content_langage 				= array("fr");
  		$this->content_type				= "utf-8";
  		$this->content_script_type			= null;
		$this->copyright				= null;
		$this->base					= null;
		$this->description				= null;
  		$this->favicon					= null;
  		$this->generator				= null;
  		$this->googlebot				= null;
		$this->identifier_url				= null;
		$this->keywords					= array();
		$this->publisher				= null;
		$this->robots					= null;
		$this->title					= null;
		$this->viewport					= null;
		$this->google_site_verification			= null;
		$this->css					= array();
		$this->scripts					= array();
		$this->script_inline				= array();
		$this->css_fix					= array();
		$this->script_fix				= array();
		$this->ie6_nomore				= false;
   	}
   	public function header()
   	{/** @function header()
	 * Echo the begining of a html page.
	 *
	 * @return	nothing
	 **/
   		switch($this->doctype)
		{
			case "html5":	echo "<!DOCTYPE html>\n".
								 "<html lang='".implode(", ", $this->content_langage)."'>\n".
								 "  <head>\n".
								 "    <meta http-equiv='content-type' content='text/html; charset=".$this->content_type."' />\n";
							break;

			case "xhtml11":	echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'>\n".
								 "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".implode(", ", $this->content_langage)."'>\n".
							   	 " <head>\n".
								 "  <meta http-equiv='content-type' content='text/html; charset=".$this->content_type."' />\n".
								 "  <meta http-equiv='content-language' content='".implode(", ", $this->content_langage)."'/>\n".
								 "  <meta name='language' content='".implode(", ", $this->content_langage)."R' />\n";
							break;

			default:		echo "<?xml version='1.1' encoding='".$this->content_type."'?>\n".
								 "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd'>\n".
								 "<html xmlns='http://www.w3.org/1999/xhtml'>\n".
								 " <head>\n".
								 "  <meta http-equiv='content-type' content='text/html; charset=".$this->content_type."' />\n".
								 "  <meta http-equiv='content-language' content='".implode(", ", $this->content_langage)."'/>\n".
								 "  <meta name='language' content='".implode(", ", $this->content_langage)."' />\n";
							break;
		}

		if(!empty($this->base))							{ echo "  <base href='http://".$this->base."'/>\n"; 				};
		if(!empty($this->copyright))					{ echo "  <meta name='copyright' content='".$this->copyright."'/>\n"; 																};
		if(!empty($this->content_script_type))			{ echo "  <meta http-equiv='content-script-type' content='text/javascript' />\n"; 													};
		if(!empty($this->description))					{ echo "  <meta name='description' content='".htmlentities($this->description, ENT_QUOTES, $this->content_type)."'/>\n";			};
		if(!empty($this->generator))					{ echo "  <meta name='generator' content='".htmlentities($this->generator, ENT_QUOTES, $this->content_type)."'/>\n";				};
		if(!empty($this->identifier_url))				{ echo "  <meta name='identifier-url' content='".$this->identifier_url."'/>\n";														};
		if(count($this->keywords) > 0)					{ echo "  <meta name='keywords' content='".htmlentities(implode(", ", $this->keywords), ENT_QUOTES, $this->content_type)."'/>\n";	};
		if(!empty($this->publisher))					{ echo "  <meta name='publisher' content='".$this->publisher."'/>\n"; 																};
		if(!empty($this->robots))						{ echo "  <meta name='robots' content='".$this->robots."'/>\n"; 																	};
		if(!empty($this->googlebot))					{ echo "  <meta name='googlebot' content='".$this->googlebot."'/>\n";																};
		if(!empty($this->viewport))						{ echo "  <meta name='viewport' content='".$this->viewport."' />\n"; 																};
		if(!empty($this->google_site_verification))		{ echo "  <meta name='google-site-verification' content='".$this->google_site_verification."' />";									};

		if(!empty($this->geolocalisation))
		{//-- geolocation
			if(!empty($this->geolocalisation["region"])) 	{ echo "  <meta name='geo.region' content='".$this->geolocalisation["region"]."' />"; 											};
			if(!empty($this->geolocalisation["placename"])) { echo "  <meta name='geo.placename' content='".$this->geolocalisation["placename"]."' />"; 									};
			if(!empty($this->geolocalisation["position"])) 	{ echo "  <meta name='geo.position' content='".$this->geolocalisation["position"]."' />"; 										};
			if(!empty($this->geolocalisation["ICBM"])) 		{ echo "  <meta name='ICBM' content='".$this->geolocalisation["ICBM"]."' />"; 													};
		}

		if(!empty($this->favicon)) 						{ echo "  <link href='".$this->favicon."' type='image/png' rel='icon'/>\n"; 														};
		if(!empty($this->title))						{ echo "  <title>".$this->title."</title>\n"; 																						};
		foreach($this->css as $css)
		{
			echo "  <link href='".$css."' rel='stylesheet' type='text/css' />\n";
		}

		$scripts_content = "";
		if(isset($scripts['content']))
		{
		      $scripts_content = $scripts['content'];
		}

		foreach($this->scripts as $scripts)
		{
			if(($scripts['position'] == "header") || (empty($scripts['position'])))
			{
				if(isset($scripts['async']) && ($scripts['async'] === true) && ($this->doctype == "html5"))
				{
						$script_string = "  <script type='text/javascript' src='".$scripts['file']."' async='async'>".$scripts_content."</script>\n";
				}
				elseif(isset($scripts['async']) && ($scripts['async'] === true)) // compatible xhtml1.1
				{
						$script_string = "<script type='ext/javascript'>document.write(unescape(\"%3Cscript src='".$scripts['file']."' type='text/javascript'%3E".$scripts_content."%3C/script%3E\"));</script>\n";
				}
				else
				{
						$script_string = "  <script type='text/javascript' src='".$scripts['file']."'>".$scripts_content."</script>\n";
				}
				echo $script_string;
			}
		}

		if(count($this->script_inline) > 0) 			{ echo "  <script type='text/javascript'>\n//<![CDATA[\n".implode(";", $this->script_inline)."\n// ]]>\n</script>\n"; 				};

		foreach($this->css_fix as $css_fix)
		{
					echo "  <!--[if ".$css_fix['target']."]><link href='".$css_fix['file']."' rel='stylesheet' type='text/css' /><![endif]-->\n";
		};

		foreach($this->script_fix as $script_fix)
		{
			if(($script_fix['position'] == "header") || (empty($script_fix['position'])))
			{
						echo "  <!--[if ".$script_fix['target']."]><script type='text/javascript' src='".$script_fix['file']."'></script><![endif]-->\n";
			};
		};

		echo " </head>\n";
		flush();
		echo	 " <body>\n";
		if($this->ie6_nomore === true)
		{
			echo "<!--[if lt IE 7]>
				<div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
				<div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display='none'; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
				<div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>
				<div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>
				<div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
					<div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>Vous utilisez un navigateur dépassé depuis près de 10 ans!</div>
					<div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>Pour une meilleure expérience web, prenez le temps de mettre votre navigateur à jour.</div>
				</div>
				<div style='width: 75px; float: left;'><a href='http://fr.www.mozilla.com/fr/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div>
				<div style='width: 75px; float: left;'><a href='http://www.microsoft.com/windows/Internet-explorer/default.aspx' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/></a></div>
				<div style='width: 73px; float: left;'><a href='http://www.apple.com/fr/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div>
				<div style='float: left;'><a href='http://www.google.com/chrome?hl=fr' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
				</div>
				</div>
			<![endif]-->";
		}
   	}
	public function footer()
	{/** @function footer()
	  * Echo the end of html file.
	  *
	  * @return	nothing
	  **/
	  $scripts_content = "";
	  if(isset($scripts['content']))
	  {
		$scripts_content = $scripts['content'];
	  }

	  foreach($this->scripts as $scripts)
	  {
		if($scripts['position'] == "footer")
		{
			if(isset($scripts['async']) && ($scripts['async'] === true) && ($this->doctype == "html5"))
			{
					$script_string = "  <script type='text/javascript' src='".$scripts['file']."' async='async'>".$scripts_content."</script>\n";
			}
			elseif(isset($scripts['async']) && ($scripts['async'] === true)) // compatible xhtml1.1
			{
					$script_string = "<script type='ext/javascript'>document.write(unescape(\"%3Cscript src='".$scripts['file']."' type='text/javascript'%3E".$scripts_content."%3C/script%3E\"));</script>\n";
			}
			else
			{
					$script_string = "  <script type='text/javascript' src='".$scripts['file']."'>".$scripts_content."</script>\n";
			}
			echo $script_string;
		}
	  };
	  foreach($this->script_fix as $script_fix)
	  {
		if($script_fix['position'] == "footer")
		{
					echo "  <!--[if ".$script_fix['target']."]><script type='text/javascript' src='".$script_fix['file']."'></script><![endif]-->\n";
		};
	  };
	   echo "\n </body>\n".
			"</html>";
	}
	public static function clean($string)
	{/** @function clean()
	  * Strip all html tag in the $string
	  *
	  * @param	string	<string>	the string with html tags to remove
	  *
	  * @return	<string>
	  **/
		return strip_tags($string);
	}
}
?>