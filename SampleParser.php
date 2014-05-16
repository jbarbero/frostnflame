<?php
/* =======================================================================
 *     SAMPLE PARSER OBJECT CLASS WITH SOME FUNCTIONS
 * Author   : Bart Meuris
 * Startdate: 24/09/2002
 * File     : defaulttags.php
 * Changelog:
 *  31-12-2002
 *     - Added the [br] tag.
 *     - The [li][/li] tag is now more intelligent
 *     - Updated the addTag(...) function calls to use the newline parameter correctly
 *     - the [img] tag now always centers
 *     - added a getversion
 *     - updated the renderCode function so it handles the htmlspecialchars() correctly.
 *     - improved (more generic) parent constructor calling...
 * ======================================================================= */

require_once('TagParser.php');

class SampleParser extends TagParser
{
	var $QUOTE_IGNORE_LEVEL;
	var $VERSION;
	var $IMG_MAX_HEIGHT;
	var $IMG_MAX_WIDTH;
	
	function SampleParser()
	{
		// Call the parent's constuctor...
		$parentclass = get_parent_class($this);
		$this->$parentclass();
		
		// Set the version
		$this->VERSION = "1.0.1";
		
$this->addTag('b',     'renderDefaults'   );
$this->addTag('i',     'renderDefaults'   );
$this->addTag('u',     'renderDefaults'   );
$this->addTag('center','renderDefaults'   );
$this->addTag('url',   'renderUrlTag'     , true , true , false, true);
$this->addTag('code',  'renderCode'       , false, true , false, false, true);
$this->addTag('img',   'renderImageTag'   , true , true , false, false, true);
$this->addTag('li',    'renderList'       , true , true , true , true , true);

$this->addSmiley(':huh:', 'img/huh.gif');
$this->addSmiley(':)', 'img/smile.gif');
$this->addSmiley(':-)', 'img/smile.gif');
$this->addSmiley('=)', 'img/smile.gif');
$this->addSmiley(':P', 'img/tongue.gif');
$this->addSmiley('=P', 'img/tongue.gif');
$this->addSmiley(':-P', 'img/tongue.gif');
$this->addSmiley(':D', 'img/biggrin.gif');
$this->addSmiley(':-D', 'img/biggrin.gif');
$this->addSmiley('=D', 'img/biggrin.gif');
$this->addSmiley('XD', 'img/biggrin.gif');
$this->addSmiley(':o', 'img/ohmy.gif');
$this->addSmiley(':0', 'img/ohmy.gif');
$this->addSmiley('B)', 'img/cool.gif');
$this->addSmiley('B-)', 'img/cool.gif');
$this->addSmiley('8-)', 'img/cool.gif');
$this->addSmiley(';)', 'img/wink.gif');
$this->addSmiley(';-)', 'img/wink.gif');
$this->addSmiley(':lol:', 'img/laugh.gif');
$this->addSmiley(':blink:', 'img/blink.gif');
$this->addSmiley(':(', 'img/sad.gif');
$this->addSmiley(':-(', 'img/sad.gif');
$this->addSmiley('=(', 'img/sad.gif');
$this->addSmiley(':rolleyes:', 'img/rolleyes.gif');
$this->addSmiley(':unsure:', 'img/unsure.gif');
$this->addSmiley(':angry:', 'img/mad.gif');
$this->addSmiley(':fear:', 'img/fear.gif');
$this->addSmiley('^^', 'img/happy.gif');
$this->addSmiley('^.^', 'img/happy.gif');
$this->addSmiley('^_^', 'img/happy.gif');
$this->addSmiley('-_-', 'img/sleep.gif');
$this->addSmiley('-.-', 'img/sleep.gif');
$this->addSmiley(':wacko:', 'img/wacko.gif');

$this->PARSE_ENABLE_FIX_URLS = true;
$this->PARSE_ENABLE_SMILEYS = true;

$this->IMG_MAX_HEIGHT = 500;
$this->IMG_MAX_WIDTH = 500;

// Use the [url] tag handler for fixing url's
$this->PARSE_ENABLE_FIX_URLS	    = 'url';
	}
	
	function getVersion()
	{
		return get_class($this) . ' ' . $this->VERSION;
	}
	
	function setDebug($level = 2)
	{
		$this->PARSE_DUMP_TREE = ($level > 0);
		$this->PARSE_DUMP_TREE_RECURSE_CALL = ($level > 1);
	}
	
	/**
	 * The default renderfunction for the
	 * "[b][/b]"
	 * "[i][/i]"
	 * "[u][/u]"
	 * and other "simple" <tag></tag> html tags with the same name as the tag to render.
	*/
	function renderDefaults($name, $params, $text, $parser)
	{
		return "<$name>$text</$name>";
	}
	
	/**
	 * Render a bulletlist (<ul><li></li><li></li>...</ul>)
	 * When containing multiple newlines, each line is made a separate "bullet"
	*/
	function renderList($name, $params, $text, $parser)
	{
		$text = trim($text);
		if (is_numeric(strpos($text, "\n"))) {
			// multiple lines...
			$arr = explode("\n", $text);
			$ret = '<ul>';
			foreach ($arr as $line) {
				// DON't add a newline - if we would do so we could not use lists inside lists
				if (($line = trim($line)) != '')
					$ret .= "<li>$line</li>";
			}
			return $ret . '</ul>';
		}
		else {
			return "<li>$text</li>";
		}
	}
	
	/**
	 * This function to render an url supports the following formats:
	 * [url=http://www.website.com]text to display[/url]
	 * [url]http://www.website.com[/url]
	 * [url]http://www.website.com[link]text to display[/url]
	 * This function is also misused for the image tag at the moment,
	 * where the "text to display" is used as "ALT" parameter for an image tag,
	 * and the "url" used for the image source.
	*/
	function renderUrlTag($name, $params, $text, $parser)
	{
		if (!isSet($params)) {
			if (($linkpos = strpos($text, "[link]")) !== false) {
				$url = substr($text, 0, $linkpos);
				$text = substr($text, $linkpos + strlen("[link]"));
			}
			else {
				$url = $text;
			}
		}
		else {
			$url = $params;
		}
		// url tag
		if (strpos($url, '://'))
			return "<a href=\"$url\" target=\"_blank\">$text</a>";
		else 
			return "<a href=\"$url\">$text</a>";
	}
	
	/**
	 * Dedicated image tag renderer.
	 * You can give a parameter, to which the image will link using a <a href= ... tag.
	 * This function also includes maximum image size checks, and automatic linking
	 * this image to itself when image is too large and no link parameter was given,
	 * so the user can view the full image by clicking the "small" version.
	 * Note that for the sizechecks, both "IMG_MAX_WIDTH" and "IMG_MAX_HEIGHT" both
	 * have to be defined. Both should be numeric values.
	 */
	function renderImageTag($name, $params, $text, $parser)
	{
		if (isSet($params)) {
			$hrstart = "<div align=center><a href=\"$params\" target=\"_blank\">";
			$hrstop  = '</a></div>';
		}
		else {
			$hrstart = '<div align=center>';
			$hrstop  = '</div>';
		}
		
		

		if (isSet($this->IMG_MAX_WIDTH) && isSet($this->IMG_MAX_HEIGHT))	{

			if(!@fopen($text,"r"))
				return "";
			else if(function_exists('getimagesize'))
				$imgprops = @getimagesize($text);
			else
				$imgprops = @$this->get_imgsize($text);

			if ( $imgprops == null || $imgprops[0] == 0 || $imgprops[1] == 0) {
				// unable to retreive image props!!
				// display error image??
				// Only limit the height...
				return "$hrstart<img border=0 src=\"$text\" alt=\"$text\" width=" . $this->IMG_MAX_WIDTH . " height=" . $this->IMG_MAX_HEIGHT . ">$hrstop";
			}
			else {
				$width = $imgprops[0];
				$height = $imgprops[1];
				//echo "$text size = $width X $height <br />\n";
				$proportion = (float)((float)$height / (float)$width);
				$hr = false;
				if ($height > $this->IMG_MAX_HEIGHT) {
					$width = (int)((float)$this->IMG_MAX_HEIGHT / $proportion);
					$height = $this->IMG_MAX_HEIGHT;
					$hr = true;
				}
				if ($width > $this->IMG_MAX_WIDTH) {
					$height = (int)((float)$this->IMG_MAX_WIDTH * $proportion);
					$width = $this->IMG_MAX_WIDTH;
					$hr = true;
				}
				if ($hr && !isSet($hrstart) && !isSet($hrstop)) {
					$hrstart = "<a href=\"$text\" target=\"_blank\">";
					$hrstop  = '</a>';
				}
				return "$hrstart<img border=0 width=$width height=$height src=\"$text\" alt=\"$text\">$hrstop";
			}
		}
		else {
			return "$hrstart<img border=0 src=\"$text\" alt=\"$text\">$hrstop";
		}
	}
	/** Functions to catch most of the "URL" cases uses */
	function get_imgsize($url)
	{
		GLOBAL $HTTP_SERVER_VARS;
		if ( (($sz = $this->ParserGetURLImageSize($url)) == null) && 
		     (($sz = $this->ParserGetURLImageSize("{$HTTP_SERVER_VARS['DOCUMENT_ROOT']}/$url")) == null) &&
		     (($sz = $this->ParserGetURLImageSize("http://{$HTTP_SERVER_VARS['HTTP_HOST']}/$url")) == null) ) {
		     return null;
		}
		return $sz;
	}
	/** 
	 * Function to get the image size.
	 * uses Filipe Laborde-Basto's GetURLImageSize function (fil@rezox.com / http://www.rezox.com)
	 * This is just a wrapper since the code above expects the function to return false if failed.
	*/
	function ParserGetURLImageSize($url)
	{
		//echo "IMAGESIZE!!<br />\n";
		require_once('lib/imagesize.php');
		$sz = GetURLImageSize($url);
		if (($sz === false) || (($sz[0] == 0) && ($sz[1] == 0) && ($sz[2] == 0))) return null;
		return $sz;
	}
	
	/**
	 * Renders the [code][/code] tag or [code=Code Type][/code] or [ign][/ign] tags.
	 * The [code][/code] will output a blockquoted text with 2 "<HR>"'s, one on top,
	 * and one at the bottom of the tag. It will also output a title, default is "CODE:",
	 * but this can be overridden by using a parameter ([code=Test][/code])
	 * If the parameter is equal to "php", the php highlighter is used.
	 * The [ign][/ign] tag is just used to ignore all tags inside, for example to be able
	 * to explain how certain tags work, and display example code for tags and smileys.
	*/
	function renderCode($name, $params,$text, $parser)
	{
		if ($name == 'code') {
			if (isSet($params) && ($params == 'php')) {
				$params = 'PHP Code:';
				// Make sure we don't have 2 newlines between each line :)
				$text = trim(str_replace('<br />', '', highlight_string("<?php $text ?>", true)));
			}
			else {
				// Fallback
				$text = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', htmlspecialchars($text));
				$text = str_replace(' ', '&nbsp;', $text);
				if (!isSet($params)) $params = 'CODE:';
			}
			return "<blockquote><hr><b>$params</b><font face='Courier' size=2>$text</font><hr></blockquote>";
		}
		else {
			$text = htmlspecialchars($text);
			$text = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $text);
			$text = str_replace(' ', '&nbsp;', $text);
			return $text;
		}
	}
	
	/**
	 * Simple "quote" renderer
	*/
	function renderQuote($name, $params, $text, $parser)
	{
		if (isSet($params)) $params = "$params wrote:";
		else $params = 'Quote:';
		if (isSet($this->QUOTE_IGNORE_LEVEL) && is_integer($this->QUOTE_IGNORE_LEVEL)) {
			$tagcnt = array_count_values($parser->tagstack);
			//echo "<HR>TESTJE<HR><HR>{$tagcnt[$name]}<HR>";
			if (isSet($tagcnt[$name]) && ($tagcnt[$name] >= $this->QUOTE_IGNORE_LEVEL)) {
				// modif code in the future...
				$text = "...";
			}
		}
		return "<blockquote><hr><b>$params</b>\n$text<hr></blockquote>";
	}
	
	/**
	 * Very very simple renderer for the "head" tag ([h][/h])
	*/
	function renderHead($name, $params, $text, $parser)
	{
		if (isSet($params) && (is_numeric($params))) 
			$HEAD_NUMBER = "$params - ";
		else {
			$HEAD_NUMBER = $parser->PARSE_STATISTICS["levels"][$parser->level][$name] . " - ";
		}
		return "<b><u>$HEAD_NUMBER$text</u></b>";
	}
	
	/** Render a newline.
	 * Should be used as "single tag" only - if not - all contents are thrown away...
	*/
	function renderNewLine($name, $params, $text, $parser)
	{
		return '<br />';
	}
}
?>
