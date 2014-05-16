<?php
# automatically generated configuration file
# see ewiki config/setup wizard

#Beatles' optimizations




#-- settings
define("EWIKI_NAME", "UnnamedWiki");
define("EWIKI_PAGE_INDEX", "FrontPage");
define("EWIKI_DEFAULT_LANG", "en");
define("EWIKI_SPLIT_TITLE", 0);
define("EWIKI_PRINT_TITLE", 2);
define("EWIKI_LIST_LIMIT", 20);
define("EWIKI_CONTROL_LINE", 1);
define("EWIKI_ALLOW_HTML", 1);
define("EWIKI_HTTP_HEADERS", 1);
define("EWIKI_NO_CACHE", 1);
define("EWIKI_AUTO_EDIT", 1);
define("EWIKI_EDIT_REDIRECT", 1);
define("EWIKI_CASE_INSENSITIVE", 1);
define("EWIKI_HIT_COUNTING", 1);
define("EWIKI_RESOLVE_DNS", 1);
define("EWIKI_PAGE_EMAIL", "ProtectedEmail");
define("EWIKI_EMAILPROT_UNLOCK", 1);
define("EWIKI_SPAGES_DIR", "./guide/spages");
define("EWIKI_SPAGES_BIN", 0);
define("EWIKI_TOC_CAPTION", 1);
define("EWIKI_DB_FAST_FILES", 1);
define("EWIKI_DBFF_ACCURATE", 0);
define("EWIKI_DB_TABLE_NAME", "ewiki");
define("EWIKI_IMGRESIZE_WIN", 0);
define("EWIKI_XHTML", 0);
define("EWIKI_CACHE_FULL", 1);
define("EWIKI_CACHE_ALL", 0);
define("EWIKI_CACHE_DIR", "cache/wiki");
define("EWIKI_CACHE_DB", "/cache/system");
define("MIMETEX_BIN", "mimetex");
define("MIMETEX_DIR", "/home/www/user28494/htdocs/ewiki/var/mimetex/");
define("MIMETEX_PATH", "/ewiki/var/mimetex/");
define("MIMETEX_INLINE", 0);
define("EWIKI_PAGE_ORPHANEDPAGES", "OrphanedPages");
define("EWIKI_PAGE_POWERSEARCH", "PowerSearch");
define("EWIKI_PAGE_RECENTCHANGES", "RecentChanges");
define("EWIKI_WIKIDUMP_ARCNAME", "WikiDump_");
define("EWIKI_WIKIDUMP_DEFAULTTYPE", "TAR");
define("EWIKI_WIKIDUMP_MAXLEVEL", "");

?><?php @define("EWIKI_VERSION", "R1.02b");

/*
  ErfurtWiki - a pretty flexible, fast and user-friendly wiki framework
  
  Is PUBLIC DOMAIN (no license, no warranty); feel free to redistribute
  under any other license, if you want. (c) 2003-2005 WhoEver wants to.

  project+help:
    http://erfurtwiki.sourceforge.net/
    http://ewiki.berlios.de/
  lead by:
    Mario Salzer <mario*erphesfurtde>
    Andy Fundinger <andy*burgisscom>

  call it from within yoursite.php / layout script like this:
    <?php
       include("ewiki.php");
       $CONTENT = ewiki_page();
    ? >
    <HTML>...<BODY>...
    <?php
       echo $CONTENT;
    ? >
    ...</HTML>
*/

#-- for future backwards compatibility to R1.02b (temporary file dependencies)
if (!function_exists("ewiki_page_edit")) { include_once("plugins/edit.php"); }
if (!function_exists("ewiki_format")) { include_once("plugins/format.php"); }
if (!function_exists("ewiki_binary")) { include_once("plugins/feature/binary.php"); }
if (!function_exists("ewiki_author")) { include_once("plugins/misc.php"); }
if (!class_exists("ewiki_database_mysql")) { include_once("plugins/db/mysql.php"); }

        #-------------------------------------------------------- config ---

        #-- this disables most PHPs debugging (_NOTICE) messages
        error_reporting(0x0000377 & error_reporting());
#	error_reporting(E_ALL^E_NOTICE);  // development

	#-- the location of your ewiki-wrapper script
#	define("EWIKI_SCRIPT_URL", "http://../?id=");	# absolute URL

        #-- change to your needs (site lang)
	define("EWIKI_NAME", "UnnamedWiki");		# Wiki title
	define("EWIKI_PAGE_INDEX", "FrontPage");	# default page
	define("EWIKI_PAGE_LIST", "PageIndex");
	define("EWIKI_PAGE_SEARCH", "SearchPages");
	define("EWIKI_PAGE_NEWEST", "NewestPages");
	define("EWIKI_PAGE_HITS", "MostVisitedPages");
	define("EWIKI_PAGE_VERSIONS", "MostOftenChangedPages");
	define("EWIKI_PAGE_UPDATES", "UpdatedPages");	# like RecentChanges

	#-- default settings are good settings - most often ;)
        #- look & feel
	define("EWIKI_PRINT_TITLE", 2);		# <h2>WikiPageName</h2> on top
	define("EWIKI_SPLIT_TITLE", 0);		# <h2>Wiki Page Name</h2>
	define("EWIKI_CONTROL_LINE", 1);	# EditThisPage-link at bottom
	define("EWIKI_LIST_LIMIT", 20);		# listing limit
        #- behaviour
	define("EWIKI_AUTO_EDIT", 1);		# edit box for non-existent pages
	define("EWIKI_EDIT_REDIRECT", 1);	# redirect after edit save
	define("EWIKI_DEFAULT_ACTION", "view"); # (keep!)
	define("EWIKI_CASE_INSENSITIVE", 1);	# wikilink case sensitivity
	define("EWIKI_HIT_COUNTING", 1);
	define("EWIKI_RESOLVE_DNS", 1);		# gethostbyaddr() when editing
	define("UNIX_MILLENNIUM", 1000000000);
        #- rendering
	define("EWIKI_ALLOW_HTML", 1);		# often a very bad idea
	define("EWIKI_HTML_CHARS", 1);		# allows for &#200;
	define("EWIKI_ESCAPE_AT", 1);		# "@" -> "&#x40;"
        #- http/urls
        define("EWIKI_SUBPAGE_LONGTITLE", 0);
        define("EWIKI_SUBPAGE_START", ".:/");   # set to "" to disable [.Sub] getting a link to [CurrentPage.Sub]
#        define("EWIKI_SUBPAGE_CHARS", ".:/-!");
	define("EWIKI_HTTP_HEADERS", 1);	# most often a good thing
	define("EWIKI_NO_CACHE", 1);		# browser+proxy shall not cache
	define("EWIKI_URLENCODE", 1);		# disable when _USE_PATH_INFO
	define("EWIKI_URLDECODE", 1);
#new!	define("EWIKI_URL_UTF8", 1);		# fix UTF-8 parameters
	define("EWIKI_USE_PATH_INFO", 1);
	define("EWIKI_USE_ACTION_PARAM", 0);	# 2 for alternative link style
	define("EWIKI_ACTION_SEP_CHAR", "/");
        define("EWIKI_ACTION_TAKE_ASIS", 1);
	define("EWIKI_UP_PAGENUM", "n");	# _UP_ means "url parameter"
	define("EWIKI_UP_PAGEEND", "e");
	define("EWIKI_UP_BINARY", "binary");
	define("EWIKI_UP_UPLOAD", "upload");
	define("EWIKI_UP_PARENTID", "parent_page");
	define("EWIKI_UP_LISTLIM", "limit");
        #- other stuff
        define("EWIKI_DEFAULT_LANG", "en");
        define("EWIKI_CHARSET", "ISO-8859-1");  # nothing else supported
	#- user permissions
	define("EWIKI_PROTECTED_MODE", 1);	# disable funcs + require auth
	define("EWIKI_PROTECTED_MODE_HIDING", 0);  # hides disallowed actions
	define("EWIKI_AUTH_DEFAULT_RING", 3);	# 0=root 1=priv 2=user 3=view
	define("EWIKI_AUTO_LOGIN", 1);		# [auth_query] on startup

	#-- allowed WikiPageNameCharacters
	define("EWIKI_CHARS_L", "a-z_�$");	# \337-\377
	define("EWIKI_CHARS_U", "A-Z0-9");	#  \300-\336
	define("EWIKI_CHARS", EWIKI_CHARS_L.EWIKI_CHARS_U);

        #-- database
	@define("EWIKI_DB_TABLE_NAME", "ewiki");	# MySQL / ADOdb
	@define("EWIKI_DBFILES_DIRECTORY", "guide");	# see "db_flat_files.php"
	define("EWIKI_DBA", "/tmp/ewiki.db3");		# see "db_dba.php"
	define("EWIKI_DBQUERY_BUFFER", 512*1024);	# 512K
	define("EWIKI_INIT_PAGES", "./init-pages");	# for initialization

	define("EWIKI_DB_F_TEXT", 1<<0);
	define("EWIKI_DB_F_BINARY", 1<<1);
	define("EWIKI_DB_F_DISABLED", 1<<2);
	define("EWIKI_DB_F_HTML", 1<<3);
	define("EWIKI_DB_F_READONLY", 1<<4);
	define("EWIKI_DB_F_WRITEABLE", 1<<5);
	define("EWIKI_DB_F_APPENDONLY", 1<<6);
	define("EWIKI_DB_F_SYSTEM", 1<<7);
	define("EWIKI_DB_F_PART", 1<<8);
	define("EWIKI_DB_F_MINOR", 1<<9);
	define("EWIKI_DB_F_HIDDEN", 1<<10);
	define("EWIKI_DB_F_ARCHIVE", 1<<11);
	define("EWIKI_DB_F_EXEC", 1<<17);
	define("EWIKI_DB_F_TYPE", EWIKI_DB_F_TEXT | EWIKI_DB_F_BINARY | EWIKI_DB_F_DISABLED | EWIKI_DB_F_SYSTEM | EWIKI_DB_F_PART);
	define("EWIKI_DB_F_ACCESS", EWIKI_DB_F_READONLY | EWIKI_DB_F_WRITEABLE | EWIKI_DB_F_APPENDONLY);
	define("EWIKI_DB_F_COPYMASK", EWIKI_DB_F_TYPE | EWIKI_DB_F_ACCESS | EWIKI_DB_F_HIDDEN | EWIKI_DB_F_HTML | EWIKI_DB_F_ARCHIVE);

	define("EWIKI_DBFILES_NLR", '\\n');
	define("EWIKI_DBFILES_ENCODE", 0 || (DIRECTORY_SEPARATOR != "/"));
	define("EWIKI_DBFILES_GZLEVEL", "2");

	#-- internal, auto-discovered
 	define("EWIKI_ADDPARAMDELIM", (strstr(EWIKI_SCRIPT,"?") ? "&" : "?"));
	define("EWIKI_SERVER", ($_SERVER["HTTP_HOST"] ? $_SERVER["HTTP_HOST"] : $_SERVER["SERVER_NAME"]) . ( ($_SERVER["SERVER_PORT"] != "80") ? (":" . $_SERVER["SERVER_PORT"]) : ""));
	define("EWIKI_BASE_URL", (@$_SERVER["HTTPS"] ? "https" : "http") . "://" . EWIKI_SERVER . substr(realpath(dirname(__FILE__)), strlen(realpath($_SERVER["DOCUMENT_ROOT"]))) . "/");	# URL to ewiki dir
	define("EWIKI_BASE_DIR", dirname(__FILE__));

	#-- binary content (images)
	define("EWIKI_ENGAGE_BINARY", 1);
	@define("EWIKI_SCRIPT_BINARY", /*"/binary.php?binary="*/  ltrim(strtok(" ".EWIKI_SCRIPT,"?"))."?".EWIKI_UP_BINARY."="  );
	define("EWIKI_CACHE_IMAGES", 1  &&!headers_sent());
	define("EWIKI_IMAGE_MAXWIDTH", 3072);
	define("EWIKI_IMAGE_MAXHEIGHT", 2048);
	define("EWIKI_IMAGE_MAXALLOC", 1<<19);
	define("EWIKI_IMAGE_RESIZE", 1);
	define("EWIKI_IMAGE_ACCEPT", "image/jpeg,image/png,image/gif,application/x-shockwave-flash");
	define("EWIKI_IDF_INTERNAL", "internal://");
	define("EWIKI_ACCEPT_BINARY", 0);   # for arbitrary binary data files

	#-- misc
        define("EWIKI_TMP", isset($_SERVER["TEMP"]) ? $_SERVER["TEMP"] : "/tmp");
        define("EWIKI_VAR", "./var");		# should be world-writable
	define("EWIKI_LOGLEVEL", -1);		# 0=error 1=warn 2=info 3=debug
	define("EWIKI_LOGFILE", "/tmp/ewiki.log");

	#-- plugins (tasks mapped to function names)
#	$ewiki_plugins["database"][] = "ewiki_database_mysql";
	$ewiki_plugins["database"][] = "ewiki_database_files";
	$ewiki_plugins["edit_preview"][] = "ewiki_page_edit_preview";
	$ewiki_plugins["render"][] = "ewiki_format";
	$ewiki_plugins["init"][-5] = "ewiki_localization";
	if (EWIKI_ENGAGE_BINARY)
        $ewiki_plugins["init"][-1] = "ewiki_binary";
        $ewiki_plugins["handler"][-105] = "ewiki_eventually_initialize";
        $ewiki_plugins["handler"][] = "ewiki_intermap_walking";
        $ewiki_plugins["handler"][] = "ewiki_meta_f_title";
	$ewiki_plugins["view_append"][-1] = "ewiki_control_links";
        $ewiki_plugins["view_final"][-1] = "ewiki_add_title";
        $ewiki_plugins["page_final"][] = "ewiki_http_headers";
        $ewiki_plugins["page_final"][99115115] = "ewiki_page_css_container";
	$ewiki_plugins["edit_form_final"][] = "ewiki_page_edit_form_final_imgupload";
        $ewiki_plugins["format_block"]["pre"][] = "ewiki_format_pre";
        $ewiki_plugins["format_block"]["code"][] = "ewiki_format_pre";
        $ewiki_plugins["format_block"]["htm"][] = "ewiki_format_html";
        $ewiki_plugins["format_block"]["html"][] = "ewiki_format_html";
        $ewiki_plugins["format_block"]["comment"][] = "ewiki_format_comment";

	#-- internal pages
	$ewiki_plugins["page"][EWIKI_PAGE_LIST] = "ewiki_page_index";
	$ewiki_plugins["page"][EWIKI_PAGE_NEWEST] = "ewiki_page_newest";
	$ewiki_plugins["page"][EWIKI_PAGE_SEARCH] = "ewiki_page_search";
	if (EWIKI_HIT_COUNTING) $ewiki_plugins["page"][EWIKI_PAGE_HITS] = "ewiki_page_hits";
	$ewiki_plugins["page"][EWIKI_PAGE_VERSIONS] = "ewiki_page_versions";
	$ewiki_plugins["page"][EWIKI_PAGE_UPDATES] = "ewiki_page_updates";

	#-- page actions
	$ewiki_plugins["action"]["edit"] = "ewiki_page_edit";
//	$ewiki_plugins["action_always"]["links"] = "ewiki_page_links";
	$ewiki_plugins["action"]["info"] = "ewiki_page_info";
	$ewiki_plugins["action"]["view"] = "ewiki_page_view";

	#-- helper vars ---------------------------------------------------
	$ewiki_config["idf"]["url"] = array("http://", "mailto:", EWIKI_IDF_INTERNAL, "ftp://", "https://", "data:", "irc://", "telnet://", "news://", "chrome://", "file://", "gopher://", "httpz://");
	$ewiki_config["idf"]["img"] = array(".jpeg", ".png", ".jpg", ".gif", ".j2k");
	$ewiki_config["idf"]["obj"] = array(".swf", ".svg");

	#-- entitle actions
	$ewiki_config["action_links"]["view"] = array(
		"edit" => "EDITTHISPAGE",	# ewiki_t() is called on these
		"links" => "BACKLINKS",
		"info" => "PAGEHISTORY",
		"like" => "LIKEPAGES",
	) + (array)@$ewiki_config["action_links"]["view"];
	$ewiki_config["action_links"]["info"] = array(
		"view" => "browse",
		"edit" => "fetchback",
	) + (array)@$ewiki_config["action_links"]["info"];

        #-- variable configuration settings (go into '$ewiki_config')
        $ewiki_config_DEFAULTS_tmp = array(
           "edit_thank_you" => 1,
           "edit_box_size" => "77x17",
           "print_title" => EWIKI_PRINT_TITLE,
           "split_title" => EWIKI_SPLIT_TITLE,
           "control_line" => EWIKI_CONTROL_LINE,
           "list_limit" => EWIKI_LIST_LIMIT,
           "script" => EWIKI_SCRIPT,
           "script_url" => (defined("EWIKI_SCRIPT_URL")?EWIKI_SCRIPT_URL:NULL),
           "script_binary" => EWIKI_SCRIPT_BINARY,
           "qmark_links" => "0b?",
	#-- heart of the wiki -- don't try to read this! ;)
           "wiki_pre_scan_regex" =>	'/
		(?<![!~\\\\])
		((?:(?:\w+:)*['.EWIKI_CHARS_U.']+['.EWIKI_CHARS_L.']+){2,}[\w\d]*(?<!_))
		|\^([-'.EWIKI_CHARS_L.EWIKI_CHARS_U.']{3,})
		|\[ (?:"[^\]\"]+" | \s+ | [^:\]#]+\|)*  ([^\|\"\[\]\#]+)  (?:\s+ | "[^\]\"]+")* [\]\#] 
		|(\w{3,9}:\/\/[^\s\[\]\'\"()<>]+[^\s\[\]\'\"()<>!,.\-:;?])
                /x',
           "wiki_link_regex" => "\007 [!~\\\\]?(
		\#?\[[^<>\[\]\n]+\] |
		\w[-_.+\w]+@(\w[-_\w]+[.])+\w{2,} |
		\^[-".EWIKI_CHARS_U.EWIKI_CHARS_L."]{3,} |
		\b([\w]{3,}:)*([".EWIKI_CHARS_U."]+[".EWIKI_CHARS_L."]+){2,}\#?[\w\d]* |
		([a-z]{2,9}://|mailto:|data:)[^\s\[\]\'\"()<>]+[^\s\[\]\'\"()<>,.!\-:;?]
		) \007x",
	#-- rendering ruleset
           "wm_indent" => '<div style="margin-left:15px;" class="indent">',
           "wm_table_defaults" => 'cellpadding="2" border="1" cellspacing="0"',
           "wm_whole_line" => array("&gt;&gt;" => 'div align="right"'),
           "wm_max_header"=>3,
           "wm_publishing_headers"=>0,
           "htmlentities" => array(
		"&" => "&amp;",
		">" => "&gt;",
		"<" => "&lt;",
		"&nbsp;" => "&nbsp;",
           ),
           "wm_source" => array(
		"%%%" => "<br />",
		"&lt;br&gt;" => "<br />",
		"\t" => "        ",
		"\n;:" => "\n      ",   # workaround, replaces the old ;:
           ),
           "wm_list" => array(
		"-" => array('ul type="square"', "", "li"),
		"*" => array('ul type="circle"', "", "li"),
		"#" => array("ol", "", "li"),
		":" => array("dl", "dt", "dd"),
	#<out># ";" => array("dl", "dt", "dd"),
           ),
           "wm_style" => array(
		"'''''" => array("<b><i>", "</i></b>"),
		"'''" => array("<b>", "</b>"),
		";;" => array("<em>", "</em>"),
		"__" => array("<strong>", "</strong>"),
		"^^" => array("<sup>", "</sup>"),
		"==" => array("<tt>", "</tt>"),
	#<off>#	"___" => array("<i><b>", "</b></i>"),
	#<off>#	"***" => array("<b><i>", "</i></b>"),
	#<off>#	"###" => array("<big><b>", "</b></big>"),
 #<broken+bug>#	"//" => array("<i>", "</i>"),   # conflicts with URLs, could only be done with regex
		"**" => array("<b>", "</b>"),
		"##" => array("<big>", "</big>"),
		",," => array("<small>", "</small>"),
           ),
           "wm_start_end" => array(
	#<off># array("[-", "-]", "<s>", "</s>"),
	#<off># array("(*", "*)", "<!--", "-->"),
           ),
	#-- rendering plugins
           "format_block" => array(
		"html" => array("&lt;html&gt;", "&lt;/html&gt;", "html", 0x0000),
		"htm" => array("&lt;htm&gt;", "&lt;/htm&gt;", "html", 0x0003),
		"code" => array("&lt;code&gt;", "&lt;/code&gt;", false, 0x0004),
		"pre" => array("&lt;pre&gt;", "&lt;/pre&gt;", false, 0x0027|4),
		"comment" => array("\n&lt;!--", "--&gt;", false, 0x0030),
                #<off>#  "verbatim" => array("&lt;verbatim&gt;", "&lt;/verbatim&gt;", false, 0x0030),
           ),
           "format_params" => array(
		"scan_links" => 1,
		"html" => EWIKI_ALLOW_HTML,
		"mpi" => 1,
           ),
        );
        #-- copy above settings into real _config[] array
        foreach ($ewiki_config_DEFAULTS_tmp as $set => $val) {
           if (!isset($ewiki_config[$set])) {
              $ewiki_config[$set] = $val;
           }
           elseif (is_array($val)) foreach ($val as $vali=>$valv) {
              if (is_int($vali)) {
                 $ewiki_config[$set][] = $valv;
              }
              elseif (!isset($ewiki_config[$set][$vali])) {
                 $ewiki_config[$set][$vali] = $valv;
              }
           }
        }
        $ewiki_config_DEFAULTS_tmp = $valv = $vali = $val = NULL;
        
        #-- special pre-sets
        $ewiki_config["ua"] = "ewiki/".EWIKI_VERSION
           . " (".PHP_OS."; PHP/".PHP_VERSION.")" . @$ewiki_config["ua"];


	#-- text  (never remove the "C" or "en" sections!)
        #
	$ewiki_t["C"] = (array)@$ewiki_t["C"] + array(
           "DATE" => "%a, %d %b %G %T %Z",
	   "EDIT_TEXTAREA_RESIZE_JS" => '<a href="javascript:ewiki_enlarge()" style="text-decoration:none">+</a><script type="text/javascript"><!--'."\n".'function ewiki_enlarge() {var ta=document.getElementById("ewiki_content");ta.style.width=((ta.cols*=1.1)*10).toString()+"px";ta.style.height=((ta.rows*=1.1)*30).toString()+"px";}'."\n".'//--></script>',
        );
        #
	$ewiki_t["en"] = (array)@$ewiki_t["en"] + array(
	   "EDITTHISPAGE" => "Edit This Page",
           "APPENDTOPAGE" => "Add To",
	   "BACKLINKS" => "Backlinks",
           "EDITCOMPLETE" => 'Your edit has been saved; click <a href="$url">here</a> to see the edited page.',
	   "PAGESLINKINGTO" => "Pages linking to \$title",
	   "PAGEHISTORY" => "History",
	   "INFOABOUTPAGE" => "About Page",
	   "LIKEPAGES" => "Pages Like This",
	   "NEWESTPAGES" => "Newest Pages",
	   "LASTCHANGED" => "Last modified on %c",
	   "DOESNOTEXIST" => "This page does not yet exist, please click on Edit This Page if you'd like to create it.",
	   "DISABLEDPAGE" => "This page is currently not available.",
	   "ERRVERSIONSAVE" => "Sorry, while you edited this page someone else
		did already save a changed version. Please go back to the
		previous screen and copy your changes to your computers
		clipboard to insert it again after you reload the edit
		screen.",
	   "ERRORSAVING" => "An error occoured while saving your changes. Please try again.",
	   "THANKSFORCONTRIBUTION" => "Thank you for your contribution!",
	   "CANNOTCHANGEPAGE" => "This page cannot be changed.",
	   "OLDVERCOMEBACK" => "Make this old version come back to replace the current one",
	   "PREVIEW" => "Preview",
	   "SAVE" => "Save",
	   "CANCEL_EDIT" => "Cancel Editing",
	   "UPLOAD_PICTURE_BUTTON" => "Upload Picture &gt;&gt;&gt;",
	   "EDIT_FORM_1" => "It is <a href=\"".EWIKI_SCRIPT."GoodStyle\">GoodStyle</a>
                to just start writing. With <a href=\"".EWIKI_SCRIPT."WikiMarkup\">WikiMarkup</a>
		you can style your text later.<br />",
	   "EDIT_FORM_2" => "<br />Please do not write things which may make other
		people angry. And please keep in mind that you are not all that
		anonymous in the internet (find out more about your computers
		'<a href=\"http://google.com/search?q=my+computers+IP+address\">IP address</a>' at Google).",
	   "BIN_IMGTOOLARGE" => "Image file is too large!",
	   "BIN_NOIMG" => "This is no image file (unacceptable file format)!",
	   "FORBIDDEN" => "You are not authorized to access this page.",
	);
        #
        $ewiki_t["es"] = (array)@$ewiki_t["es"] + array(
           "EDITTHISPAGE" => "EditarEstaP�ina",
           "BACKLINKS" => "EnlacesInversos",
           "PAGESLINKINGTO" => "P�inas enlazando \$title",
           "PAGEHISTORY" => "InfoP�ina",
           "INFOABOUTPAGE" => "Informaci� sobre la p�ina",
           "LIKEPAGES" => "P�inas como esta",
           "NEWESTPAGES" => "P�inas m� nuevas",
           "LASTCHANGED" => "ltima modificaci� %d/%m/%Y a las %H:%M",
           "DOESNOTEXIST" => "Esta p�ina an no existe, por favor eliga EditarEstaP�ina si desea crearla.",
           "DISABLEDPAGE" => "Esta p�ina no est�disponible en este momento.",
           "ERRVERSIONSAVE" => "Disculpe, mientras editaba esta p�ina algui� m�
		salv�una versi� modificada. Por favor regrese a
		a la pantalla anterior y copie sus cambios a su computador
		para insertalos nuevamente despu� de que cargue
		la pantalla de edici�.",
           "ERRORSAVING" => "Ocurri�un error mientras se salvavan sus cambios. Por favor intente de nuevo.",
           "THANKSFORCONTRIBUTION" => "Gracias por su contribuci�!",
           "CANNOTCHANGEPAGE" => "Esta p�ina no puede ser modificada.",
           "OLDVERCOMEBACK" => "Hacer que esta versi� antigua regrese a remplazar la actual",
           "PREVIEW" => "Previsualizar",
           "SAVE" => "Salvar",
           "CANCEL_EDIT" => "CancelarEdici�",
           "UPLOAD_PICTURE_BUTTON" => "subir gr�ica &gt;&gt;&gt;",
           "EDIT_FORM_1" => "<a href=\"".EWIKI_SCRIPT."BuenEstilo\">BuenEstilo</a> es
		escribir lo que viene a su mente. No se preocupe mucho
		por la apariencia. Tambi� puede agregar <a href=\"".EWIKI_SCRIPT."ReglasDeMarcadoWiki\">ReglasDeMarcadoWiki</a>
		m� adelante si piensa que es necesario.<br />",
           "EDIT_FORM_2" => "<br />Por favor no escriba cosas, que puedan
		enfadar a otras personas. Y por favor tenga en mente que
		usted no es del todo an�imo en Internet 
		(encuentre m� sobre 
		'<a href=\"http://google.com/search?q=my+computers+IP+address\">IP address</a>' de su computador con Google).",
           "BIN_IMGTOOLARGE" => "La gr�ica es demasiado grande!",
           "BIN_NOIMG" => "No es un archivo con una gr�ica (formato de archivo inaceptable)!",
           "FORBIDDEN" => "No est�autorizado para acceder a esta p�ina.",
        );
        #
	$ewiki_t["de"] = (array)@$ewiki_t["de"] + array(
	   "EDITTHISPAGE" => "DieseSeite�dern",
           "APPENDTOPAGE" => "Erg�ze",
	   "BACKLINKS" => "ZurckLinks",
	   "PAGESLINKINGTO" => "Verweise zur Seite \$title",
	   "PAGEHISTORY" => "SeitenInfo",
	   "INFOABOUTPAGE" => "Informationen ber Seite",
	   "LIKEPAGES" => "�nliche Seiten",
	   "NEWESTPAGES" => "Neueste Seiten",
	   "LASTCHANGED" => "zuletzt ge�dert am %d.%m.%Y um %H:%M",
	   "DISABLEDPAGE" => "Diese Seite kann momentan nicht angezeigt werden.",
	   "ERRVERSIONSAVE" => "Entschuldige, aber w�rend Du an der Seite
		gearbeitet hast, hat bereits jemand anders eine ge�derte
		Fassung gespeichert. Damit nichts verloren geht, browse bitte
		zurck und speichere Deine �derungen in der Zwischenablage
		(Bearbeiten->Kopieren) um sie dann wieder an der richtigen
		Stelle einzufgen, nachdem du die EditBoxSeite nocheinmal
		geladen hast.<br />
		Vielen Dank fr Deine Mhe.",
	   "ERRORSAVING" => "Beim Abspeichern ist ein Fehler aufgetreten. Bitte versuche es erneut.",
	   "THANKSFORCONTRIBUTION" => "Vielen Dank fr Deinen Beitrag!",
	   "CANNOTCHANGEPAGE" => "Diese Seite kann nicht ge�dert werden.",
	   "OLDVERCOMEBACK" => "Diese alte Version der Seite wieder zur Aktuellen machen",
	   "PREVIEW" => "Vorschau",
	   "SAVE" => "Speichern",
	   "CANCEL_EDIT" => "�derungenVerwerfen",
	   "UPLOAD_PICTURE_BUTTON" => "Bild hochladen &gt;&gt;&gt;",
	   "EDIT_FORM_1" => "Es ist <a href=\"".EWIKI_SCRIPT."GuterStil\">GuterStil</a>,
		einfach drauf los zu tippen. Mit den <a href=\"".EWIKI_SCRIPT."FormatierungsRegeln\">FormatierungsRegeln</a>
		kannst du den Text sp�er noch umgestalten.<br />",
	   "EDIT_FORM_2" => "<br />Bitte schreib keine Dinge, die andere Leute
		ver�gern k�nten. Und bedenke auch, da�es schnell auf
		dich zurckfallen kann wenn du verschiedene andere Dinge sagst (mehr Informationen zur
		'<a href=\"http://google.de/search?q=computer+IP+adresse\">IP Adresse</a>'
		deines Computers findest du bei Google).",
	);
	$ewiki_t["nl"] = (array)@$ewiki_t["nl"] + array(
	   "EDITTHISPAGE" => "BewerkPagina",
        );

	#-- InterWiki:Links
	$ewiki_config["interwiki"] = (array)@$ewiki_config["interwiki"] +
	array(
           "javascript" => "",  # this actually protects from javascript: links
           "url" => "",
           "jump" => "",        # fallback; if jump plugin isn't loaded
#          "self" => "this",
           "this" => defined("EWIKI_SCRIPT_URL")?EWIKI_SCRIPT_URL:EWIKI_SCRIPT,
           // real entries:
	   "ErfurtWiki" => "http://erfurtwiki.sourceforge.net/",
	   "InterWiki" => "MetaWiki",
	   "MetaWiki" => "http://sunir.org/apps/meta.pl?",
	   "Wiki" => "WardsWiki",
	   "WardsWiki" => "http://www.c2.com/cgi/wiki?",
	   "WikiFind" => "http://c2.com/cgi/wiki?FindPage&value=",
	   "WikiPedia" => "http://www.wikipedia.com/wiki.cgi?",
	   "MeatBall" => "MeatballWiki",
	   "MeatballWiki" => "http://www.usemod.com/cgi-bin/mb.pl?",
	   "UseMod"       => "http://www.usemod.com/cgi-bin/wiki.pl?",
	   "CommunityWiki" => "http://www.emacswiki.org/cgi-bin/community/",
	   "WikiFeatures" => "http://wikifeatures.wiki.taoriver.net/moin.cgi/",
	   "PhpWiki" => "http://phpwiki.sourceforge.net/phpwiki/index.php3?",
	   "LinuxWiki" => "http://linuxwiki.de/",
	   "OpenWiki" => "http://openwiki.com/?",
	   "Tavi" => "http://andstuff.org/tavi/",
	   "TWiki" => "http://twiki.sourceforge.net/cgi-bin/view/",
	   "MoinMoin" => "http://www.purl.net/wiki/moin/",
	   "Google" => "http://google.com/search?q=",
	   "ISBN" => "http://www.amazon.com/exec/obidos/ISBN=",
	   "icq" => "http://www.icq.com/",
	);
	
// end of config




#-------------------------------------------------------------------- init ---


#-- bring up database backend
if (!isset($ewiki_db) && ($pf = $ewiki_plugins["database"][0])) {
   if (class_exists($pf)) {
      $ewiki_db = new $pf;
   }
   elseif (function_exists($pf)) {
      include("plugins/db/oldapi.php"); // eeeyk! temporary workaround!
   }
}

#-- init stuff, autostarted parts (done a 2nd time inside ewiki_page)
if ($pf_a = $ewiki_plugins["init"]) {
   ksort($pf_a);
   foreach ($pf_a as $pf) {
      $pf($GLOBALS);
   }
   unset($ewiki_plugins["init"]);
}



#-------------------------------------------------------------------- main ---

/*  This is the main function, which you should preferrably call to
    integrate the ewiki into your web site; it chains to most other
    parts and plugins (including the edit box).
    If you do not supply the requested pages "$id" we will fetch it
    from the pre-defined possible URL parameters.
*/
function ewiki_page($id=false) {

   global $ewiki_links, $ewiki_plugins, $ewiki_ring, $ewiki_t,
      $ewiki_errmsg, $ewiki_data, $ewiki_title, $ewiki_id,
      $ewiki_action, $ewiki_config;

   #-- output str
   $o = "";

   #-- selected page
   if (!strlen($id)) {
      $id = ewiki_id();
   }

   #-- page action
   $action = EWIKI_DEFAULT_ACTION;
   if ($delim = strpos($id, EWIKI_ACTION_SEP_CHAR)) {
      $a = substr($id, 0, $delim);
      if (EWIKI_ACTION_TAKE_ASIS || in_array($a, $ewiki_plugins["action"]) || in_array($a, $ewiki_plugins["action_always"])) {
         $action = rawurlencode($a);
         $id = substr($id, $delim + 1);
      }
   }
   if (EWIKI_USE_ACTION_PARAM && isset($_REQUEST["action"])) {
      $action = rawurlencode($_REQUEST["action"]);
   }
   $ewiki_data = array();
   $ewiki_id = $id;
   $ewiki_title = ewiki_split_title($id);
   $ewiki_action = $action;

   #-- more initialization
   if ($pf_a = @$ewiki_plugins["init"]) {
      ksort($pf_a);
      foreach ($pf_a as $pf) {
         $o .= $pf();
      }
      unset($ewiki_plugins["init"]);
   }
   #-- micro-gettext stub (for upcoming/current transition off of ewiki_t)
   if (!function_exists("_")) {
      function _($text) { return($text); }
      function gettext($text) { return($text); }
   }

   #-- fetch from db
   $version = false;
   if (!isset($_REQUEST["content"]) && ($version = 0 + @$_REQUEST["version"])) {
      $ewiki_config["forced_version"] = $version;
   }
   $ewiki_data = ewiki_db::GET($id, $version);
   $data = &$ewiki_data;

   #-- pre-check if actions exist
   $pf_page = ewiki_array($ewiki_plugins["page"], $id);

   #-- edit <form> or info/ page for non-existent and empty pages
   if (($action==EWIKI_DEFAULT_ACTION) && empty($data["content"]) && empty($pf_page)) {
      if ($data["version"] >= 2) {
         $action = "info";
      }
      elseif (EWIKI_AUTO_EDIT) {
         $action = "edit";
      }
      else {
         $data["content"] = ewiki_t("DOESNOTEXIST");
      }
   }

   #-- internal "create" action / used for authentication requests
   if (($action == "edit")&&(($data["version"]==0) && !isset($pf_page))) {
      $ewiki_config["create"] = $id;
   }

   #-- require auth
   if (EWIKI_PROTECTED_MODE) {
      if (!ewiki_auth($id, $data, $action, $ring=false, $force=EWIKI_AUTO_LOGIN)) {
         return($o.=$ewiki_errmsg);
      }
   }

   #-- handlers
   $handler_o = "";
   if ($pf_a = @$ewiki_plugins["handler"]) {
      ksort($pf_a);
      foreach ($pf_a as $pf_i=>$pf) {
         if ($handler_o = $pf($id, $data, $action, $pf_i)) { break; }
   }  }

   #-- stop here if page is not marked as _TEXT,
   #   perform authentication then, and let only administrators proceed
   if (!$handler_o) {
      if (!empty($data["flags"]) && (($data["flags"] & EWIKI_DB_F_TYPE) != EWIKI_DB_F_TEXT)) {
         if (($data["flags"] & EWIKI_DB_F_BINARY) && ($pf = $ewiki_plugins["handler_binary"][0])) {
            return($pf($id, $data, $action)); //_BINARY entries handled separately
         }
      elseif ((!EWIKI_PROTECTED_MODE || !ewiki_auth($id, $data, $action, 0, 1)) && ($ewiki_ring!=0)) {
            return(ewiki_t("DISABLEDPAGE"));
         }
      }
   }

   #-- finished by handler
   if ($handler_o) {
      $o .= $handler_o;
   }
   #-- actions that also work for static and internal pages
   elseif (($pf = @$ewiki_plugins["action_always"][$action]) && function_exists($pf)) {
      $o .= $pf($id, $data, $action);
   }
   #-- internal pages
   elseif ($pf_page && function_exists($pf_page)) {
      $o .= $pf_page($id, $data, $action);
   }
   #-- page actions
   else {
      $pf = @$ewiki_plugins["action"][$action];

      #-- fallback to "view" action
      if (empty($pf) || !function_exists($pf)) {

         $pf = "ewiki_page_view";
         $action = "view";     // we could also allow different (this is a
         // catch-all) view variants, but this would lead to some problems
      }

      $o .= $pf($id, $data, $action);
   }

   #-- error instead of page?
   if (empty($o) && $ewiki_errmsg) {
      $o = $ewiki_errmsg;
   }

   #-- html post processing
   if ($pf_a = $ewiki_plugins["page_final"]) {
      ksort($pf_a);
      foreach ($pf_a as $pf) {
         $pf($o, $id, $data, $action);
      }
   }

   if (EWIKI_ESCAPE_AT && !isset($ewiki_config["@"])) {
      $o = str_replace("@", "&#x40;", $o);
   }

   $ewiki_data = &$data;
   unset($ewiki_data["content"]);
   return($o);
}



#-- HTTP meta headers
function ewiki_http_headers(&$o, $id, &$data, $action, $saveasfilename=1) {
   global $ewiki_t, $ewiki_config;
   if (EWIKI_HTTP_HEADERS && !headers_sent()) {
      if (!empty($data)) {
         if (($uu = @$data["id"]) && $saveasfilename) @header('Content-Disposition: inline; filename="' . urlencode($uu) . '.html"');
         if ($uu = @$data["version"]) @header('Content-Version: ' . $uu);
         if ($uu = @$data["lastmodified"]) @header('Last-Modified: ' . gmstrftime($ewiki_t["C"]["DATE"], $uu));
      }
      if (EWIKI_NO_CACHE) {
         header('Expires: ' . gmstrftime($ewiki_t["C"]["DATE"], UNIX_MILLENNIUM));
         header('Pragma: no-cache');
         header('Cache-Control: no-cache, must-revalidate' . (($ewiki_author||EWIKI_PROTECTED_MODE)?", private":"") );
         # ", private" flag only for authentified users / _PROT_MODE
      }
      #-- ETag
      if ($data["version"] && ($etag=ewiki_etag($data)) || ($etag=md5($o))) {
         $weak = "W/" . urlencode($id) . "." . $data["version"];
         header("ETag: \"$etag\"");     ###, \"$weak\"");
         header("X-Server: $ewiki_config[ua]");
      }
   }
}
function ewiki_etag(&$data) {
   return(  urlencode($data["id"]) . ":" . dechex($data["version"]) . ":ewiki:" .
            dechex(crc32($data["content"]) & 0x7FFFBFFF)  );
}



#-- encloses whole page output with a descriptive <div>
function ewiki_page_css_container(&$o, &$id, &$data, &$action) {
   $sterilized_id = trim(preg_replace('/[^\w\d]+/', "-", $id), "-");
   $sterilized_id = preg_replace('/^(\d)/', 'page$1', $sterilized_id);
   $o = "<div class=\"wiki $action $sterilized_id\">\n" . $o . "\n</div>\n";
}



function ewiki_split_title ($id='', $split=-1, $entities=1) {
   if ($split==-1) {
      $split = $GLOBALS["ewiki_config"]["split_title"];
   }
   strlen($id) or ($id = $GLOBALS["ewiki_id"]);
   if ($split) {
      $id = preg_replace("/([".EWIKI_CHARS_L."])([".EWIKI_CHARS_U."]+)/", "$1 $2", $id);
   }
   return($entities ? htmlentities($id) : $id);
}



function ewiki_add_title(&$html, $id, &$data, $action, $go_action="links") {
   if (EWIKI_PRINT_TITLE)
      $html = "<div class=\"text-head\">\n"
         . ewiki_make_title($id, '', 1, $action, $go_action)
         . "\n</div>\n" . $html;
}


function ewiki_make_title($id='', $title='', $class=3, $action="view", $go_action="links", $may_split=1) {

   global $ewiki_config, $ewiki_plugins, $ewiki_title, $ewiki_id;

   #-- advanced handler
   if ($pf = @$ewiki_plugins["make_title"][0]) {
      return($pf($title, $class, $action, $go_action, $may_split));
   }
   #-- disabled
   elseif (!$ewiki_config["print_title"]) {
      return("");
   }

   #-- get id
   if (empty($id)) {
      $id = $ewiki_id;
   }

   #-- get title
   if (!strlen($title)) {
      $title = $ewiki_title;  // already in &html; format
   }
   elseif ($ewiki_config["split_title"] && $may_split) {
      $title = ewiki_split_title($title, $ewiki_config["split_title"], 0&($title!=$ewiki_title));
   }
   else {
      $title = htmlentities($title);
   }

   #-- title mangling
   if ($pf_a = @$ewiki_plugins["title_transform"]) {
      foreach ($pf_a as $pf) { $pf($id, $title, $go_action); }
   }

   #-- clickable link or simple headline
   if ($class <= $ewiki_config["print_title"]) {
      if ($uu = @$ewiki_config["link_title_action"][$action]) {
         $go_action = $uu;
      }
      if ($uu = @$ewiki_config["link_title_url"]) {
         $href = $uu;
         unset($ewiki_config["link_title_url"]);
      }
      else {
         $href = ewiki_script($go_action, $id);
      }
      $o = '<a href="' . $href . '">' . ($title) . '</a>';
   }
   else {
      $o = $title;
   }

   // h2.page.title is obsolete; h2.text-title recommended
   return('<h6 class="text-title page title">' . $o . '</h6>');
}




function ewiki_page_view($id, &$data, $action, $all=1) {

   global $ewiki_plugins, $ewiki_config;
   $o = "";

   #-- render requested wiki page  <-- goal !!!
   $render_args = array(
      "scan_links" => 1,
      "html" => (EWIKI_ALLOW_HTML||(@$data["flags"]&EWIKI_DB_F_HTML)),
   );
   $o .= '<div class="text-body">' . "\n"
      . $ewiki_plugins["render"][0] ($data["content"], $render_args)
      . "</div>\n";
   if (!$all) {
      return($o);
   }

   #-- control line + other per-page info stuff
   if ($pf_a = $ewiki_plugins["view_append"]) {
      ksort($pf_a);
      $o .= "<div class=\"wiki-plugins\">\n";
      foreach ($pf_a as $n => $pf) { $o .= $pf($id, $data, $action); }
      $o .= "</div>\n";
   }
   if ($pf_a = $ewiki_plugins["view_final"]) {
      ksort($pf_a);
      foreach ($pf_a as $n => $pf) { $pf($o, $id, $data, $action); }
   }

   if (!empty($_REQUEST["thankyou"]) && $ewiki_config["edit_thank_you"]) {
      $o = '<div class="text-prefix system-message">'
         . ewiki_t("THANKSFORCONTRIBUTION") . "</div>\n" . $o;
   }

   if (EWIKI_HIT_COUNTING) {
      ewiki_db::HIT($id);
   }

   return($o);
}




#-------------------------------------------------------------------- util ---


/*  retrieves "$id/$action" string from URL / QueryString / PathInfo,
    change this in conjunction with ewiki_script() to customize your URLs
    further whenever desired
*/
function ewiki_id() {
   ($id = @$_REQUEST["id"]) or
   ($id = @$_REQUEST["name"]) or
   ($id = @$_REQUEST["page"]) or
   ($id = @$_REQUEST["file"]) or
   (EWIKI_USE_PATH_INFO)
      and isset($_SERVER["PATH_INFO"])
      and ($_SERVER["PATH_INFO"] != $_SERVER["SCRIPT_NAME"])  // Apache+PHP workaround
      and ($id = ltrim($_SERVER["PATH_INFO"], "/")) or
   (!isset($_REQUEST["id"])) and ($id = trim(strtok($_SERVER["QUERY_STRING"], "&?;")));
   if (!strlen($id) || ($id=="id=")) {
      $id = EWIKI_PAGE_INDEX;
   }
   (EWIKI_URLDECODE) && ($id = urldecode($id));
   return($id);
}




/*  replaces EWIKI_SCRIPT, works more sophisticated, and
    bypasses various design flaws
    - if only the first parameter is used (old style), it can contain
      a complete "action/WikiPage" - but this is ambigutious
    - else $asid is the action, and $id contains the WikiPageName
    - $ewiki_config["script"] will now be used in favour of the constant
    - needs more work on _BINARY, should be a separate function
*/
function ewiki_script($asid, $id=false, $params="", $bin=0, $html=1, $script=NULL) {

   global $ewiki_config, $ewiki_plugins;

   #-- get base script url from config vars
   if (empty($script)) {
      $script = &$ewiki_config[!$bin?"script":"script_binary"];
   }
   $alt_style = (EWIKI_USE_ACTION_PARAM >= 2);
   $ins_prefix = (EWIKI_ACTION_TAKE_ASIS);

   #-- separate $action and $id for old style requests
   if ($id === false) {
      if (strpos($asid, EWIKI_ACTION_SEP_CHAR) !== false) {
         $asid = strtok($asid, EWIKI_ACTION_SEP_CHAR);
         $id = strtok("\000");
      }
      else {
         $id = $asid;
         $asid = "";
      }
   }

   #-- prepare params
   if (is_array($params)) {
      $uu = $params;
      $params = "";
      if ($uu) foreach ($uu as $k=>$v) {
         $params .= (strlen($params)? "&" : "") . rawurlencode($k) . "=" . rawurlencode($v);
      }
   }
   #-- use action= parameter instead of prefix/
   if ($alt_style) {
      $params = "action=$asid" . (strlen($params)? "&": "") . $params;
      if (!$ins_prefix) {
         $asid = "";
      }
   }

   #-- workaround slashes in $id
   if (empty($asid) && (strpos($id, EWIKI_ACTION_SEP_CHAR) !== false) && !$bin && $ins_prefix) {
      $asid = EWIKI_DEFAULT_ACTION;
   }
   /*paranoia*/ $asid = trim($asid, EWIKI_ACTION_SEP_CHAR);

   #-- make url
   if (EWIKI_URLENCODE) {
      $id = urlencode($id);
      $asid = urlencode($asid);
   }
   else {
      // only urlencode &, %, ? for example
   }
   $url = $script;
   if ($asid) {
      $id = $asid . EWIKI_ACTION_SEP_CHAR . $id;  #= "action/PageName"
   }
   if (strpos($url, "%s") !== false) {
      $url = str_replace("%s", $id, $url);
   }
   else {
      $url .= $id;
   }

   #-- add url params
   if (strlen($params)) {
      $url .= (strpos($url,"?")!==false ? "&":"?") . $params;
   }

   #-- fin
   if ($html) {
      $url = str_replace("&", "&amp;", $url);
   }
   return($url);
}


/*  this ewiki_script() wrapper is used to generate URLs to binary
    content in the ewiki database
*/
function ewiki_script_binary($asid, $id=false, $params=array(), $upload=0) {

   $upload |= is_string($params) && strlen($params) || count($params);

   #-- generate URL directly to the plainly saved data file,
   #   see also plugins/db/binary_store
   if (defined("EWIKI_DB_STORE_URL") && !$upload) {
      $url = EWIKI_DB_STORE_URL . urlencode(rawurlencode(strtok($id, "?")));
   }

   #-- else get standard URL (thru ewiki.php) from ewiki_script()
   else {
      $url = ewiki_script($asid, $id, $params, "_BINARY=1");
   }

   return($url);
}


/*  this function returns the absolute ewiki_script url, if EWIKI_SCRIPT_URL
    is set, else it guesses the value
*/
function ewiki_script_url($asid="", $id="", $params="") {

   global $ewiki_action, $ewiki_id, $ewiki_config;

   if ($asid||$id) {
      return ewiki_script($asid, $id, $params, false, true, ewiki_script_url());
   }
   if ($url = $ewiki_config["script_url"]) {
      return($url);
   }

   $scr_template = $ewiki_config["script"];
   $scr_current = ewiki_script($ewiki_action, $ewiki_id);
   $req_uri = $_SERVER["REQUEST_URI"];
   $qs = $_SERVER["QUERY_STRING"]?1:0;
   $sn = $_SERVER["SCRIPT_NAME"];

   if (($p = strpos($req_uri, $scr_current)) !== false) {
      $url = substr($req_uri, 0, $p) . $scr_template;
   }
   elseif (($qs) && (strpos($scr_template, "?") !== false)) {
      $url = substr($req_uri, 0, strpos($req_uri, "?"))
           . substr($scr_template, strpos($scr_template, "?"));
   }
   elseif (($p = strrpos($sn, "/")) && (strncmp($req_uri, $sn, $p) == 0)) {
      $url = $sn . "?id=";
   }
   else {
      return(NULL);   #-- could not guess it
   }
 
   $url = (@$_SERVER["HTTPS"] ? "https" : "http") . "://"
        . EWIKI_SERVER . $url; 
   	
   return($ewiki_config["script_url"] = $url);
}




#------------------------------------------------------------ page plugins ---


#-- links/ action
function ewiki_page_links($id, &$data, $action) {
   $o = ewiki_make_title($id, ewiki_t("PAGESLINKINGTO", array("title"=>$id)), 1, $action, "", "_MAY_SPLIT=1");
   if ($pages = ewiki_get_backlinks($id)) {
      $o .= ewiki_list_pages($pages);
   } else {
      $o .= ewiki_t("This page isn't linked from anywhere else.");
   }
   return($o);
}

#-- get all pages, that are linking to $id
function ewiki_get_backlinks($id) {
   $result = ewiki_db::SEARCH("refs", $id);
   $pages = array();
   $id_i = EWIKI_CASE_INSENSITIVE ? strtolower($id) : $id;
   while ($row = $result->get(0, 0x0077)) {
      if (strpos(EWIKI_CASE_INSENSITIVE ?strtolower($row["refs"]) :$row["refs"], "\n$id_i\n") !== false) {
         $pages[] = $row["id"];
      }
   }
   return($pages);
}

#-- get all existing pages (as array of pagenames), that are linked from $id
function ewiki_get_links($id) {
   if ($data = ewiki_db::GET($id)) {
      $refs = explode("\n", trim($data["refs"]));
      $r = array();
      foreach (ewiki_db::FIND($refs) as $id=>$exists) {
         if ($exists) {
            $r[] = $id;
         }
      }
      return($r);
   }
}



#-- outputs listing from page name array
function ewiki_list_pages($pages=array(), $limit=NULL,
                          $value_as_title=0, $pf_list=false)
{
   global $ewiki_plugins;
   $o = "";

   if (!isset($limit)) {
      ($limit = 0 + $_REQUEST[EWIKI_UP_LISTLIM])
      or ($limit = EWIKI_LIST_LIMIT);
   }
   $is_num = !empty($pages[0]);
   $lines = array();
   $n = 0;

   if ($pages) foreach ($pages as $id=>$add_text) {

      $title = $id;
      $params = "";

      if (is_array($add_text)) {
         list($id, $params, $title, $add_text) = $add_text;
         if (!$title) { $title = $id; }
      }
      elseif ($is_num) {
         $id = $title = $add_text;
         $add_text = "";
      }
      elseif ($value_as_title) {
         $title = $add_text;
         $add_text = "";
      }

      $lines[] = '<a href="' . ewiki_script("", $id, $params) . '">' . ewiki_split_title($title) . '</a> ' . $add_text;

      if (($limit > 0)  &&  ($n++ >= $limit)) {
         break;
      }
   }

   if ($pf_a = @$ewiki_plugins["list_transform"]) {
      foreach ($pf_a as $pf_transform) {
         $pf_transform($lines);
      }
   }

   if (($pf_list) || ($pf_list = @$ewiki_plugins["list_pages"][0])) {
      $o = $pf_list($lines);
   }
   elseif($lines) {
      $o = "&middot; " . implode("<br />\n&middot; ", $lines) . "<br />\n";
   }

   return($o);
}


#---------------------------------------------------------- page plugins ---


#-- list of all existing pages (without hidden + protected)
function ewiki_page_index($id=0, $data=0, $action=0, $args=array()) {

   global $ewiki_plugins;

   $o = ewiki_make_title($id, $id, 2);

   $exclude = $args ? ("\n" . implode("\n", preg_split("/\s*[,;:\|]\s*/", $args["exclude"])) . "\n") : "";
   $sorted = array();
   $sorted = array_keys($ewiki_plugins["page"]);

   $result = ewiki_db::GETALL(array("flags"), EWIKI_DB_F_TYPE, EWIKI_DB_F_TEXT);
   while ($row = $result->get(0, 0x0037, EWIKI_DB_F_TEXT)) {
      if (!stristr($exclude, "\n".$row["id"]."\n")) {
         $sorted[] = $row["id"];
      }
   }
   natcasesort($sorted);

   $o .= ewiki_list_pages($sorted, 0, 0, $ewiki_plugins["list_dict"][0]);
   return($o);
}



#-- scans database for extremes (by given page meta data information),
#   generates page listing then from list
//@TODO: split $asc parameter into $asc and $firstver
function ewiki_page_ordered_list($orderby="created", $asc=0, $print="%n", $title="", $bad_flags=0) {

   $o = ewiki_make_title("", $title, 2, ".list", "links", 0);

   $sorted = array();
   $result = ewiki_db::GETALL(array($orderby));

   while ($row = $result->get(0, 0x0037, EWIKI_DB_F_TEXT)) {
      if ($asc >= 0) {
         // version 1 is most accurate for {hits}
         $row = ewiki_db::GET($row["id"], 1);
      }
      if (! ($bad_flags & $row["flags"])) {
         $sorted[$row["id"]] = $row[$orderby];
      }
   }

   if ($asc != 0) { arsort($sorted); }
   else { asort($sorted); }

   if ($sorted) foreach ($sorted as $name => $value) { 
      if (empty($value)) { $value = "0"; }
      $sorted[$name] = strftime(str_replace('%n', $value, $print), $value);
   }
   $o .= ewiki_list_pages($sorted);
   
   return($o);
}



function ewiki_page_newest($id, $data, $action) {
   return( ewiki_page_ordered_list("created", -1, ewiki_t("LASTCHANGED"), ewiki_t("NEWESTPAGES")) );
}

function ewiki_page_updates($id, $data, $action) {
   return ewiki_page_ordered_list("lastmodified", -1, ewiki_t("LASTCHANGED"), EWIKI_PAGE_UPDATES, EWIKI_DB_F_MINOR);
}

function ewiki_page_hits($id, $data, $action) {
   return( ewiki_page_ordered_list("hits", 1, "%n hits", EWIKI_PAGE_HITS) );
}

function ewiki_page_versions($id, $data, $action) {
   return( ewiki_page_ordered_list("version", -1, "%n changes", EWIKI_PAGE_VERSIONS) );
}







function ewiki_page_search($id, &$data, $action) {

   $o = ewiki_make_title($id, $id, 2, $action);

   if (! ($q = @$_REQUEST["q"])) {

      $o .= '<form action="' . ewiki_script("", $id) . '" method="POST">';
      $o .= ewiki_form("q::30", "") . '<br /><br />';
      $o .= ewiki_form(":submit", $id);
      $o .= '</form>';
   }
   else {
      $found = array();

      $q = preg_replace('/\s*[^\041-\175\200-\377]\s*/', ' ', $q);
      if ($q) foreach (explode(" ", $q) as $search) {

         if (empty($search)) { continue; }

         $result = ewiki_db::SEARCH("content", $search);

         while ($row = $result->get()) {

            #-- show this entry in page listings?
            if (EWIKI_PROTECTED_MODE && EWIKI_PROTECTED_MODE_HIDING && !ewiki_auth($row["id"], $row, "view")) {
               continue;
            }

            $found[] = $row["id"];
         }
      }

      $o .= ewiki_list_pages($found);
   }
 
   return($o);
}








function ewiki_page_info($id, &$data, $action) {

   global $ewiki_plugins, $ewiki_config, $ewiki_links;

   $o = ewiki_make_title($id, ewiki_t("INFOABOUTPAGE")." '{$id}'", 2, $action,"", "_MAY_SPLIT=1"); 

   $flagnames = array(
      "TEXT", "BIN", "DISABLED", "HTML", "READONLY", "WRITEABLE",
      "APPENDONLY", "SYSTEM", "PART", 10=>"HIDDEN", 17=>"EXEC",
   );
   $show = array(
      "version", "author",
      "lastmodified",  "created", "hits", "refs",
      "flags", "meta", "content"
   );
   $no_refs = (boolean)$ewiki_config["info_refs_once"];

   #-- versions to show
   $v_start = $data["version"];
   if ( ($uu=0+@$_REQUEST[EWIKI_UP_PAGENUM]) && ($uu<=$v_start) ) {
      $v_start = $uu;
   }
   $v_end = $v_start - $ewiki_config["list_limit"];
   if ( ($uu=0+@$_REQUEST[EWIKI_UP_PAGEEND]) && ($uu<=$v_start) ) {
      $v_end = $uu;
   }
   $v_end = max($v_end, 1);

   #-- go
   # the very ($first) entry is rendered more verbosely than the others
   for ($v=$v_start,$first=1; ($v>=$v_end); $v--,$first=0) {

      $current = ewiki_db::GET($id, $v);

      if (!strlen(trim($current["id"])) || !$current["version"]) {
         continue;
      }

      $o .= '<table class="version-info" border="1" cellpadding="2" cellspacing="1">' . "\n";

      #-- additional info-actions
      $o .= '<tr><td></td><td class="action-links">';
      $o .= ewiki_control_links_list($id, $data, $ewiki_config["action_links"]["info"], $current["version"]);
      $o .= "</td></tr>\n";

      #-- print page database entry
      foreach($show as $i) {

         $value = @$current[$i];

         #-- show database {fields} differently
         if ($i == "meta") {
            $str = "";
            if ($value) foreach ($value as $n2=>$d2) {
               foreach ((array)$d2 as $n=>$d) {
                  if (is_int($n)) { $n = $n2; } else { $n = "_$n"; }
                  $str .= htmlentities("$n: $d") . "<br />\n";
               }
            }
            $value = $str;
         }
         elseif (($i =="lastmodified")||($i =="created")) {    #-- {lastmodified}, {created}
            $value = strftime("%c", $value);
         }
         elseif ($i == "content") {
            $value = strlen(trim($value)) . " bytes";
            $i = "content size";
         }
         elseif ($first && ($i == "refs") && !(EWIKI_PROTECTED_MODE && (EWIKI_PROTECTED_MODE_HIDING>=2))) {
            $a = explode("\n", trim($value));
            $ewiki_links = ewiki_db::FIND($a);
            ewiki_merge_links($ewiki_links);
            foreach ($a as $n=>$link) {
               $a[$n] = ewiki_link_regex_callback(array("$link"), "force_noimg");
            }
            $value = implode(", ", $a);
         }
         elseif (strpos($value, "\n") !== false) {       #-- also for {refs}
            if ($no_refs && ($i == "refs")) { continue; }
            $value = str_replace("\n", ", ", trim($value));
         }
         elseif ($i == "version") {
            $value = '<a href="' .
               ewiki_script("", $id, array("version"=>$value)) . '">' .
               $value . '</a>';
         }
         elseif ($i == "flags") {
            $fstr = "";
            for ($n = 0; $n < 32; $n++) {
              if ($value & (1 << $n)) {
                 if (! ($s=$flagnames[$n])) { $s = "UU$n"; }
                 $fstr .= $s . " ";
              }
            }
            $value = $fstr;
         }
         elseif ($i == "author") {
            $value = ewiki_author_html($value);
         }

         $o .= '<tr class="page-' . $i . '"><td valign="top"><b>' . $i . '</b></td>' .
               '<td>' . $value . "</td></tr>\n";

      }

      $o .= "</table><br />\n";
   }

   #-- page result split
   if ($v >= 1) {
      $o .= "<br /><div class=\"chunk-list\">\n" . ewiki_chunked_page($action, $id, -1, $v+1, 1) . "\n</div><br />";
   }
   #-- ext info actions
   $o .= '<div class="summary control-links">' . ewiki_control_links_list($id, $data, $ewiki_config["action_links"]["summary"]) . "</div>\n";

   return($o);
}




function ewiki_chunked_page($action, $id, $dir=-1, $start=10, $end=1, $limit=0, $overlap=0.25, $collapse_last=0.67) {

   global $ewiki_config;

   if (empty($limit)) {
      $limit = $ewiki_config["list_limit"];
   }
   if ($overlap < 1) {
      $overlap = (int) ($limit * $overlap);
   }

   $p = "";
   $n = $start;

   while ($n) {

      $n -= $dir * $overlap;

      $e = $n + $dir * ($limit + $overlap);

      if ($dir<0) {
         $e = max(1, $e);
         if ($e <= $collapse_last * $limit) {
            $e = 1;
         }
      }
      else {
         $e = min($end, $e);
         if ($e >= $collapse_last * $limit) {
            $e = $end;
         }
      }

      $o .= ($o?" &middot; ":"")
         . '<a href="'.ewiki_script($action, $id, array(EWIKI_UP_PAGENUM=>$n, EWIKI_UP_PAGEEND=>$e))
         . '">'. "$n-$e" . '</a>';

      if (($n=$e) <= $end) {
         $n = false;
      }
   }

   return('<div class="chunked-result">'. $o .'</div>');
}






function ewiki_page_edit($id, $data, $action) {

   global $ewiki_links, $ewiki_author, $ewiki_plugins, $ewiki_ring,
      $ewiki_errmsg, $ewiki_config;

   $hidden_postdata = array();

   #-- previous version come back
   if ($ewiki_config["forced_version"]) {

      $current = ewiki_db::GET($id);
      $data["version"] = $current["version"];
      unset($current);

      unset($_REQUEST["content"]);
      unset($_REQUEST["version"]);
   }

   #-- edit interception
   if ($pf_a = @$ewiki_plugins["edit_hook"]) foreach ($pf_a as $pf) {
      if ($output = $pf($id, $data, $hidden_postdata)) {
         return($output);
      }
   }

   #-- permission checks   //@TODO: move into above hook, split out flag checks
   if (isset($ewiki_ring)) {
      $ring = $ewiki_ring;
   } else { 
      $ring = 3;
   }
   $flags = @$data["flags"];
   if (!($flags & EWIKI_DB_F_WRITEABLE)) {

      #-- perform auth
      $edit_ring = (EWIKI_PROTECTED_MODE>=2) ? (2) : (NULL);
      if (EWIKI_PROTECTED_MODE && !ewiki_auth($id, $data, $action, $edit_ring, "FORCE")) {
         return($ewiki_errmsg);
      }

      #-- flag checking
      if (($flags & EWIKI_DB_F_READONLY) and ($ring >= 2)) {
         return(ewiki_t("CANNOTCHANGEPAGE"));
      }
      if (($flags) and (($flags & EWIKI_DB_F_TYPE) != EWIKI_DB_F_TEXT) and ($ring >= 1)) {
         return(ewiki_t("CANNOTCHANGEPAGE"));
      }
   }

   #-- "Edit Me"
   $o = ewiki_make_title($id, ewiki_t("EDITTHISPAGE").(" '{$id}'"), 2, $action, "", "_MAY_SPLIT=1");

   #-- normalize to UNIX newlines
   $_REQUEST["content"] = str_replace("\015\012", "\012", $_REQUEST["content"]);
   $_REQUEST["content"] = str_replace("\015", "\012", $_REQUEST["content"]);

   #-- preview
   if (isset($_REQUEST["preview"])) {
      $o .= $ewiki_plugins["edit_preview"][0]($data);
   }

   #-- save
   if (isset($_REQUEST["save"])) {

         #-- check for concurrent version saving
         $error = 0;
         if ((@$data["version"] >= 1) && (($data["version"] != @$_REQUEST["version"]) || (@$_REQUEST["version"] < 1))) {

            $pf = $ewiki_plugins["edit_patch"][0];

            if (!$pf || !$pf($id, $data)) {
               $error = 1;
               $o .= ewiki_t("ERRVERSIONSAVE") . "<br /><br />";
            }

         }
         if (!$error) {

            #-- new pages` flags
            $set_flags = (@$data["flags"] & EWIKI_DB_F_COPYMASK);
            if (($set_flags & EWIKI_DB_F_TYPE) == 0) {
               $set_flags = EWIKI_DB_F_TEXT;
            }
            if (EWIKI_ALLOW_HTML) {
               $set_flags |= EWIKI_DB_F_HTML;
            }

            #-- mk db entry
            $save = array(
               "id" => $id,
               "version" => @$data["version"] + 1,
               "flags" => $set_flags,
               "content" => $_REQUEST["content"],
               "created" => ($uu=@$data["created"]) ? $uu : time(),
               "meta" => ($uu=@$data["meta"]) ? $uu : "",
               "hits" => ($uu=@$data["hits"]) ? $uu : "0",
            );
            ewiki_data_update($save);

            #-- edit storage hooks
            if ($pf_a = @$ewiki_plugins["edit_save"]) {
               foreach ($pf_a as $pf) {
                  $pf($save, $data);
               }
            }

            #-- save
            if (!$save || !ewiki_db::WRITE($save)) {

               $o .= $ewiki_errmsg ? $ewiki_errmsg : ewiki_t("ERRORSAVING");

            }
            else {
               #-- prevent double saving, when ewiki_page() is re-called
               $_REQUEST = $_GET = $_POST = array();

               $o = ewiki_t("THANKSFORCONTRIBUTION") . "<br /><br />";

               if (EWIKI_EDIT_REDIRECT) {
                  $url = ewiki_script("", $id, "thankyou=1", 0, 0, ewiki_script_url());
                  $o .= ewiki_t("EDITCOMPLETE", array("url"=>htmlentities($url)));

                  if (EWIKI_HTTP_HEADERS && !headers_sent()) {
                     header("Status: 303 Redirect for GET");
                     $sid = defined("SID") ? EWIKI_ADDPARAMDELIM.SID : "";
                     header("Location: $url$sid");
                     #header("URI: $url");
                     #header("Refresh: 0; URL=$url");
                  }
                  else {
                     $o .= '<meta http-equiv="Location" content="'.htmlentities($url).'">';
                  }
               }
               else {
                  $o .= ewiki_page($id);
               }

            }

         }

         //@REWORK
         // header("Reload-Location: " . ewiki_script("", $id, "", 0, 0, ewiki_script_url()) );

   }
   else {
      #-- Edit <form>
      $o .= ewiki_page_edit_form($id, $data, $hidden_postdata);

      #-- additional forms
      if ($pf_a = $ewiki_plugins["edit_form_final"]) foreach ($pf_a as $pf) {
         $pf($o, $id, $data, $action);
      }
   }

   return($o);
}


function ewiki_data_update(&$data, $author="") {
   ewiki_db::UPDATE($data, $author);
}


function ewiki_new_data($id, $flags=EWIKI_DB_F_TEXT, $author="") {
   return(ewiki_db::CREATE($id, $flags, $author));
}



#-- edit <textarea>
function ewiki_page_edit_form(&$id, &$data, &$hidden_postdata) {

   global $ewiki_plugins, $ewiki_config;

   #-- previously edited, or db fetched content
   if (@$_REQUEST["content"] || @$_REQUEST["version"]) {
      $data = array(
         "version" => &$_REQUEST["version"],
         "content" => &$_REQUEST["content"]
      );
   }
   else {
      if (empty($data["version"])) {
         $data["version"] = 1;
      }
      @$data["content"] .= "";
   }

   #-- normalize to DOS newlines
   $data["content"] = str_replace("\015\012", "\012", $data["content"]);
   $data["content"] = str_replace("\015", "\012", $data["content"]);
   $data["content"] = str_replace("\012", "\015\012", $data["content"]);

   $hidden_postdata["version"] = &$data["version"];

   #-- edit textarea/form
   $o .= ewiki_t("EDIT_FORM_1")
       . '<form method="POST" enctype="multipart/form-data" action="'
       . ewiki_script("edit", $id) . '" name="ewiki"'
       . ' accept-charset="'.EWIKI_CHARSET.'">' . "\n";

   #-- additional POST vars
   if ($hidden_postdata) foreach ($hidden_postdata as $name => $value) {
       $o .= '<input type="hidden" name="' . $name . '" value="' . $value . '" />' ."\n";
   }

   if (EWIKI_CHARSET=="UTF-8") {
      $data["content"] = utf8_encode($data["content"]);
   }
   ($cols = strtok($ewiki_config["edit_box_size"], "x*/,;:")) && ($rows = strtok("x, ")) || ($cols=70) && ($rows=15);
   $o .= '<textarea wrap="soft" id="ewiki_content" name="content" rows="'.$rows . '" cols="' .$cols. '">'
      . htmlentities($data["content"]) . "</textarea>"
      . $GLOBALS["ewiki_t"]["C"]["EDIT_TEXTAREA_RESIZE_JS"];

   #-- more <input> elements before the submit button
   if ($pf_a = $ewiki_plugins["edit_form_insert"]) foreach ($pf_a as $pf) {
      $o .= $pf($id, $data, $action);
   }

   $o .= "\n<br />\n"
      . ewiki_form("save:submit", " &nbsp; ".ewiki_t("SAVE")." &nbsp; ")
      . " &nbsp; "
      . ewiki_form("preview:submit", " &nbsp; ".ewiki_t("PREVIEW")." &nbsp; ")
      . ' &nbsp; <a class="cancel" href="'. ewiki_script("", $id) . '">' . ewiki_t("CANCEL_EDIT") . '</a><br />';

   #-- additional form elements
   if ($pf_a = $ewiki_plugins["edit_form_append"]) foreach ($pf_a as $pf) {
      $o .= $pf($id, $data, $action);
   }

   $o .= "\n</form>\n"
      . ewiki_t("EDIT_FORM_2");

   return('<div class="edit-box">'. $o .'</div>');
}



#-- pic upload form
function ewiki_page_edit_form_final_imgupload(&$o, &$id, &$data, &$action) {
   if (EWIKI_SCRIPT_BINARY && EWIKI_UP_UPLOAD && EWIKI_IMAGE_MAXSIZE) {
      $o .= "\n<br />\n". '<div class="image-upload">'
      . '<form action='
      . '"'. ewiki_script_binary("", EWIKI_IDF_INTERNAL, "", "_UPLOAD=1") .'"'
      . ' method="POST" enctype="multipart/form-data" target="_upload">'
      . '<input type="file" name="'.EWIKI_UP_UPLOAD.'"'
      . (defined("EWIKI_IMAGE_ACCEPT") ? ' accept="'.EWIKI_IMAGE_ACCEPT.'" />' : "")
      . '<input type="hidden" name="'.EWIKI_UP_BINARY.'" value="'.EWIKI_IDF_INTERNAL.'">'
      . '<input type="hidden" name="'.EWIKI_UP_PARENTID.'" value="'.htmlentities($id).'">'
      . '&nbsp;&nbsp;&nbsp;'
      . '<input type="submit" value="'.ewiki_t("UPLOAD_PICTURE_BUTTON").'">'
      . '</form></div>'. "\n";
  }
}


function ewiki_page_edit_preview(&$data) {
   return( '<div class="preview">'
           . '<hr noshade="noshade" />'
           . "<div align=\"right\">" . ewiki_t("PREVIEW") . "</div><hr noshade=\"noshade\" /><br />\n"
           . $GLOBALS["ewiki_plugins"]["render"][0]($_REQUEST["content"], 1, EWIKI_ALLOW_HTML || (@$data["flags"]&EWIKI_DB_F_HTML))
           . '<hr noshade="noshade" /><br />'
           . "</div></div>"
   );
}







function ewiki_control_links($id, &$data, $action, $hide_hr=0, $hide_mtime=0) {

   global $ewiki_plugins, $ewiki_ring, $ewiki_config;
   $action_links = & $ewiki_config["action_links"][$action];

   #-- disabled
   if (!$ewiki_config["control_line"]) {
      return("");
   }

   $o = "\n"
      . '<div align="right" class="action-links control-links">';
   if (!$hide_hr && !@$ewiki_config["control_line.no_deco"]) {
      $o .=  "\n<br />\n" . '<hr noshade="noshade" />' . "\n";
   }

   if (@$ewiki_config["forced_version"] && ewiki_auth($id, $data, "edit")) {

      $o .= '<form action="' . ewiki_script("edit", $id) . '" method="POST">' .
            '<input type="hidden" name="edit" value="old">' .
            '<input type="hidden" name="version" value="'.$ewiki_config["forced_version"].'">' .
            '<input type="submit" value="' . ewiki_t("OLDVERCOMEBACK") . '"></form> ';
   }
   else {
      $o .= ewiki_control_links_list($id, $data, $action_links);
   }

   if (!$hide_mtime && ($data["lastmodified"] >= UNIX_MILLENNIUM)) { 
      $o .= '<small>' . strftime(ewiki_t("LASTCHANGED"), @$data["lastmodified"]) . '</small>';
   }

   $o .= "</div>\n";
   return($o);
}


#-- the core of ewiki_control_links, separated for use in info and plugins
function ewiki_control_links_list($id, &$data, $action_links, $version=0) {
   global $ewiki_plugins, $ewiki_config;
   ($ins = @$ewiki_config["control_links_enclose"]) or ($ins = ' &middot; ');
   $o = "";

   if ($action_links) foreach ($action_links as $action => $title)
   if (!empty($ewiki_plugins["action"][$action]) || !empty($ewiki_plugins["action_always"][$action]) || strpos($action, ":/"))
   {
      if (EWIKI_PROTECTED_MODE && EWIKI_PROTECTED_MODE_HIDING && !ewiki_auth($id, $data, $action)) {
         continue;
      }
      $o .= '<a href="' .
         ( strpos($action, "://")
            ? $action   # an injected "action" URL
            : ewiki_script($action, $id, $version?array("version"=>$version):NULL)
         ) . '">' . ewiki_t($title) . '</a> ' . $ins;
#++ &nbsp;
   }
   
   $o = $ins[0] . $o . $ins[0];
   return($o);
}




# ============================================================= rendering ===





########  ###   ###  #########  ###  ###   ###  #######
########  ####  ###  #########  ###  ####  ###  #######
###       ##### ###  ###             ##### ###  ###
######    #########  ###  ####  ###  #########  ######
######    #########  ###  ####  ###  #########  ######
###       ### #####  ###   ###  ###  ### #####  ###
########  ###  ####  #########  ###  ###  ####  #######
########  ###   ###  #########  ###  ###   ###  #######


/*
   The _format() function transforms $wiki_source pages into <html> strings,
   also calls various markup and helper plugins during the transformation
   process. The $params array can activate various features and extensions.
   only accepts UNIX newlines!
*/
function ewiki_format (
            $wiki_source,
            $params = array()
         )
{
   global $ewiki_links, $ewiki_plugins, $ewiki_config;

   #-- state vars
   $params = (array)$ewiki_config["format_params"] + (array)$params;
   $s = array(
      "in" => 0,         # current input $iii[] block array index
      "para" => "",
      "line" => "",
      "post" => "",      # string to append after current line/paragraph
      "line_i" => 0,
      "lines" => array(),
      "list" => "",      # lists
      "tbl" => 0,        # open table?
      "indent" => 0,     # indentation
      "close" => array(),
   );
   #-- aliases
   $in = &$s["in"]; 
   $line = &$s["line"];
   $lines = &$s["lines"];
   $para = &$s["para"];
   $post = &$s["post"];
   $list = &$s["list"];

   #-- input and output arrays
   if ($wiki_source[0] == "<") {            # also prepend an empty line 
      $wiki_source = "\n" . $wiki_source;    # for faster strpos() searchs
   }
   $core_flags = 0x137F;            # (0x0001=WikiMarkup, 0x0002=WikiLinks, 0x1000=MoreBlockPlugins)
   $iii = array(
      0 => array(
         0 => $wiki_source."\n",    # body + empty line
         1 => $core_flags,          # rendering / behaviour options
         2 => "core",               # block plugin name
      )
   );
   $ooo = array(
   );
   unset($wiki_source);

   #-- plugins
   $pf_tbl = @$ewiki_plugins["format_table"][0];
   $pf_line = @$ewiki_plugins["format_line"];

   #-- wikimarkup (wm)
   $htmlentities = $ewiki_config["htmlentities"];
   $wm_indent = &$ewiki_config["wm_indent"];
   $s["wm_indent_close"] = "</" . strtok($wm_indent, "< />"). ">";
   $wm_table_defaults = &$ewiki_config["wm_table_defaults"];
   $wm_source = &$ewiki_config["wm_source"];
   $wm_list = &$ewiki_config["wm_list"];
   $wm_list_chars = implode("", array_keys($wm_list));
   $wm_style = &$ewiki_config["wm_style"];
   $wm_start_end = &$ewiki_config["wm_start_end"];
   $wm_max_header = &$ewiki_config["wm_max_header"];
   $wm_publishing_headers = &$ewiki_config["wm_publishing_headers"];
   $wm_whole_line = &$ewiki_config["wm_whole_line"];

   #-- eleminate html
   $iii[0][0] = strtr($iii[0][0], $htmlentities);
   unset($htmlentities["&"]);

   #-- pre-processing plugins (working on wiki source)
   if ($pf_source = $ewiki_plugins["format_source"]) {
      foreach ($pf_source as $pf) $pf($iii[0][0]);
   }

   #-- simple markup
   $iii[0][0] = strtr($iii[0][0], $wm_source);


   #-- separate input into blocks ------------------------------------------
   if ($ewiki_config["format_block"])
   foreach ($ewiki_config["format_block"] as $btype=>$binfo) {

      #-- disabled block plugin?
      if ($binfo[2] && !$params[$binfo[2]])  {
         continue;
      }

      #-- traverse $iii[]
      $in = -1;
      while ((++$in) < count($iii)) {

         #-- search fragment delimeters
         if ($iii[$in][1] & 0x0100)
         while (
            ($c = & $iii[$in][0]) &&
            (($l = strpos($c, $binfo[0])) !== false) &&
            ($r = strpos($c, $binfo[1], $l))   )
         {
            $l_len = strlen($binfo[0]);
            $r_len = strlen($binfo[1]);

            $repl = array();
            // pre-text
            if (($l > 0) && trim($text = substr($c, 0, $l))) {
               $repl[] = array($text, $core_flags, "core");
            }
            // the extracted part
            if (trim($text = substr($c, $l+$l_len, $r-$l-$l_len))) {
               $repl[] = array($text, $binfo[3], "$btype");
            }
            // rest
            if (($r+$r_len < strlen($c)) && trim($text = substr($c, $r+$r_len))) {
               $repl[] = array($text, $core_flags, "core");
            }
            array_splice($iii, $in, 1, $repl);

            $in += 1;
         }
      }
   }

   #-- run format_block plugins
   $in = -1;
   while ((++$in) < count($iii)) {
      if (($btype = $iii[$in][2]) && ($pf_a = @$ewiki_plugins["format_block"][$btype])) {
         $c = &$iii[$in][0];
         if ($iii[$in][1] & 0x0400) {
            $c = strtr($c, array_flip($htmlentities));
         }
         foreach ($pf_a as $pf) {   
            # current buffer $c and pointer $in into $iii[] and state $s
            $pf($c, $in, $iii, $s, $btype);
         }
      }
   }

   #-- wiki markup ------------------------------------------------------
   $para = "";
   $in = -1;   
   while ((++$in) < count($iii)) {
      #-- wikimarkup
      if ($iii[$in][1] & 0x0001) {

         #-- input $lines buffer, and output buffer $ooo array
         $lines = explode("\n", $iii[$in][0]);
         $ooo[$in] = array(
            0 => "",
            1 => $iii[$in][1]
         );
         $out = &$ooo[$in][0];
         $s["bmarkup"] = ($iii[$in][1] & 0x0008);   # lists/tables/paras
         $s["nopara"] = !($s["bmarkup"]);   # disables indentation & paragraphs
# should this disable lists and tables and ...
# shouldn't it rather be a bit flag?

         #-- walk through wiki source lines
         $line_max = count($lines);
         if ($lines) foreach ($lines as $s["line_i"]=>$line) {
 #echo "line={$s[line_i]}:$line\n";

            #-- empty lines separate paragraphs
            if (!ltrim($line)) {
               ewiki_format_close_para($ooo, $s);
               ewiki_format_close_tags($ooo, $s);
               if (!$s["nopara"]) {
                  $out .= "\n";
               }
               $line = '';
            }
    
    
            #-- list/table/headline "BlockMarkup" ---------------------------
            if ($s["bmarkup"]) {
        
                #-- horiz bar
                if (!$list && !strncmp($line, "----", 4)) {
                   $s["para"] .= "<hr noshade=\"noshade\" />\n";
                   continue;
                }
                #-- html comment
                if (!strncmp($line, "&lt;!--", 7)) {
                   $out .= "<!-- " . htmlentities(str_replace("--", "__", substr($line, 7))) . " -->\n";
                   continue;
                }

                strlen($line) && ($c0 = $line[0])
                or ($c0 = "\000");

                #-- tables ------------------------
                if (($c0 == "|") && ($s["tbl"] || ($line[strlen($line)-1] == "|"))) {
                   if (!$s["tbl"]) {
                      ewiki_format_close_para($ooo, $s);
                      ewiki_format_close_tags($ooo, $s);
                      $s["list"] = "";
                   }
                   $line = substr($line, 1);
                   if ($line[strlen($line)-1] == "|") {
                      $line = substr($line, 0, -1);
                   }
                   if ($pf_tbl) { 
                      $pf_tbl($line, $ooo, $s);
                   }
                   else {
                      if (!$s["tbl"]) {  
                         $out .= "<table " . $wm_table_defaults . ">\n";
                         $s["close"][] = "\n</table>"; 
                      }
                      $line = "<tr>\n<td>" . str_replace("|", "</td>\n<td>", $line) . "</td>\n</tr>";
                   }
                   $s["tbl"] = 1;
                   $para = false;
                }
                elseif ($s["tbl"]) {
                   $s["tbl"] = 0;
                }


                #-- headlines
                if (($c0 == "!") && ($excl = strspn($line, "!"))) {
                
                   if ($excl > $wm_max_header) { 
                      $excl = $wm_max_header;
                   }
                   $line = substr($line, $excl);
                   //publishing headers go from h2 smaller "like word"
                   $excl = $wm_publishing_headers? (1+$excl) :5 - $excl;
                   $line = "<h$excl>" . $line . "</h$excl>";
                   if ($para) {
                      ewiki_format_close_para($ooo, $s);
                   }
                   ewiki_format_close_tags($ooo, $s);
                   $para = false;
                }


                #-- whole-line wikimarkup
                foreach ($wm_whole_line as $find=>$replace) {
                  if (substr($line, 0, strlen($find)) == $find) {
                     $line = "<$replace>" . ltrim(substr($line,strlen($find))) . "</".strtok($replace," ").">";
                  }
                }

                #-- indentation (space/tab markup)
                $n_indent = 0;
                if (!$list && (!$s["nopara"]) && ($n_indent = strspn($line, " "))) {
                   $n_indent = (int) ($n_indent / 2.65);
                   while ($n_indent > $s["indent"]) { 
                      $s["para"] .= $wm_indent;
                      $s["indent"]++;
                   }
                }
                while ($n_indent < $s["indent"]) { 
                   $s["para"] .= $s["wm_indent_close"] . "\n";
                   $s["indent"]--;
                }


                #-- list markup -------------------
                if (isset($wm_list[$c0])) {
                   if (!$list) {
                      ewiki_format_close_para($ooo, $s);
                      ewiki_format_close_tags($ooo, $s);
                   }
                   $new_len = strspn($line, $wm_list_chars);
                   $new_list = substr($line, 0, $new_len);
                   $old_len = strlen($list);
                   $lchar = $new_list[$new_len-1];
                   list($lopen, $ltag1, $ltag2) = $wm_list[$lchar];

                   #-- exception: "--" is treated as literal
                   if (($old_len===0) && (($new_len>=2) && ($new_list=="--"))) {
                      $list = '';         # change this ^^ to an OR (||)
                                          # to filter bad list markup
                   }
                   else {
                      #-- cut line
                      $line = substr($line, $new_len);
                      $lspace = "";
                      $linsert = "";
                      if ($ltag1) {
                         $linsert = "<$ltag1>" . strtok($line, $lchar) . "</$ltag1> ";
                         $line = strtok("\000");
                      }

                      #-- enum list types
                      if (($lchar == "#") && ($line[1] == " ") && ($ltype = $line[0])) {
                         if (($ltype >= "0") || ($ltype <= "z")) {
                            $line = substr($line, 2);
                         } else {
                            $ltype = "";
                         }
                      }

                      #-- add another <li>st entry
                      if ($new_len == $old_len) {
                         $lspace = str_repeat("  ", $new_len);
                         $out .=  "</$ltag2>\n" . $lspace . $linsert . "<$ltag2>";
                      }
                      #-- add list
                      elseif ($new_len > $old_len) {
                         while ($new_len > ($old_len=strlen($list))) {
                            $lchar = $new_list[$old_len];
                            $list .= $lchar;
                            list($lopen, $ltag1, $ltag2) = $wm_list[$lchar];
                            $lclose = strtok($lopen, " ");
                            $lspace = str_repeat("  ", $new_len);

                            if (isset($ltype) && $ltype) {
                               $ltype = ($ltype<"A"?"1": ($ltype=="I"?"I": ($ltype=="i"?"i": ($ltype<"a"?"A": "a"))));
                               $lopen .= " type=\"$rltype\"";
                               if ($rltype!=$ltype) { $lopen .= " start=\"$ltype\""; }
                            }
                            
                            $out .= "\n$lspace<$lopen>\n" . "$lspace". $linsert . "<$ltag2>";
                            $s["close"][] = "$lspace</$lclose>";
                            $s["close"][] = "$lspace</$ltag2>";
                         }
                      }
                      #-- close lists
                      else {
                         while ($new_len < ($old_len=strlen($list))) {
                            $remove = $old_len-$new_len;
                            ewiki_format_close_tags($ooo, $s, 2*$remove);
                            $list = substr($list, 0, -$remove);
                         }
                         if ($new_len) {
                            $lspace = str_repeat("  ", $new_len);
                            $out .= "$lspace</$ltag2>\n" . $lspace . $linsert . "<$ltag2>";
                         }
                      }

                      $list = $new_list;
                      $para = false;
                   }
                }
                elseif ($list) {
                   if ($c0 == " ") {
                      $para = false;
                   }
                   else {
                      ewiki_format_close_tags($ooo, $s);
                      $list = "";
                   }
                }

            }#--if $s["bmarkup"] --------------------------------------------


            #-- text style triggers
            foreach ($wm_style as $find=>$replace) {
               $find_len = strlen($find);
               $loop = 20;
               while(($loop--) && (($l = strpos($line, $find)) !== false) && ($r = strpos($line, $find, $l + $find_len))) {
                  $line = substr($line, 0, $l) . $replace[0] .
                          substr($line, $l + strlen($find), $r - $l - $find_len) .
                          $replace[1] . substr($line, $r + $find_len);
               }

            }

            #-- start-end markup
            foreach ($wm_start_end as $d) {
               $len0 = strlen($d[0]);
               $loop = 20;
               while(($loop--) && (($l = strpos($line, $d[0])) !== false) && ($r = strpos($line, $d[1], $l + $len0))) {
                  $len1 = strlen($d[1]);
                  $line = substr($line, 0, $l) . $d[2] .
                          substr($line, $l + $len0, $r - $l - $len0) .
                          $d[1] . substr($line, $r + $len1);
               }
            }

            #-- call wiki source formatting plugins that work on current line
            if ($pf_line) {
               foreach ($pf_line as $pf) $pf($out, $line, $post);
            }


            #-- add formatted line to page-output
            $line .= $post;
            if ($para === false) {
               $out .= $line;
               $para = "";
            }
            else {
               $para .= $line . "\n";
            }

         }

         #-- last block, or flags dictate a WikiSource blocks/para break?
         if (!isset($iii[$in+1]) || (($iii[$in+1][1] & 0x0010) ^ ($iii[$in][1] & 0x0010)) ) {
            ewiki_format_close_para($ooo, $s);
            ewiki_format_close_tags($ooo, $s);
         }
      }
      #-- copy as is into output buffer
      else {
         $ooo[$in] = $iii[$in];
      }
      $iii[$in] = array();
   }


   #-- wiki linking ------------------------------------------------------
   $scan_src = "";
   for ($in=0; $in<count($ooo); $in++) {
// BUG: does not respect the (absence of) flags of individual blocks
      #-- join together multiple WikiSource blocks
      if ($ooo[$in][1] & 0x0022) {
         while (isset($ooo[$in+1]) && ($ooo[$in][1] & 0x0002) && ($ooo[$in+1][1] & 0x0002)) {
            $ooo[$in] = array(
               0 => $ooo[$in][0] . "\n" . $ooo[$in+1][0],
               1 => $ooo[$in][1] | $ooo[$in+1][1],
            );
            array_splice($ooo, $in+1, 1);
         }
      }
      #-- html character entities
      if (EWIKI_HTML_CHARS || ($ooo[$in][1] & 0x0004)) {
         $ooo[$in][0] = str_replace("&amp;#", "&#", $ooo[$in][0]);
      }
      $scan_src .= $ooo[$in][0];
   }

   #-- pre-scan
   if ($params["scan_links"]) {
      ewiki_scan_wikiwords($scan_src, $ewiki_links);
   }
   if ($pf_linkprep = @$ewiki_plugins["format_prepare_linking"]) {
      foreach ($pf_linkprep as $pf) $pf($scan_src);
   }
   $scan_src = NULL;

   #-- finally the link-creation-regex
   for ($in=0; $in<count($ooo); $in++) {
      if ($ooo[$in][1] & 0x0002) {
         ewiki_render_wiki_links($ooo[$in][0]);
      }
   }


   #-- fin: combine all blocks into html string ----------------------------
   $html = "";
   for ($in=0; $in<count($ooo); $in++) {
      $html .= $ooo[$in][0] . "\n";
      $ooo[$in] = 0;
   }
   #-- call post processing plugins
   if ($pf_final = $ewiki_plugins["format_final"]) {
      foreach ($pf_final as $pf) $pf($html);
   }
   return($html);
}



function ewiki_format_close_para(&$ooo, &$s) {
   $out = &$ooo[$s["in"]][0];
   #-- output text block
   if (trim($s["para"])) {
      #-- indentation
      while ($s["indent"]) {
         $s["para"] .= $s["wm_indent_close"];
         $s["indent"]--;
      }
      #-- enclose in <p> tags
      if (!$s["nopara"]) {
         $s["para"] = "\n<p>" . ltrim($s["para"], "\n") . "</p>\n";
      }
      #-- paragraph formation plugins
      if ($pf_a = @$GLOBALS["ewiki_plugins"]["format_para"]) {
         foreach ($pf_a as $pf) {
            $pf($s["para"], $ooo, $s);
         }
      }
      $out .= $s["para"];
      $s["para"] = "";
   }
}


function ewiki_format_close_tags(&$ooo, &$s, $count=100) {
   $out = &$ooo[$s["in"]][0];
   if (!is_array($s) || !is_array($s["close"])) { 
      die("\$s is garbaged == $s!!");
   }
   while (($count--) && ($add = array_pop($s["close"]))) {
      $out .= $add . "\n";
   }
}


function ewiki_format_pre(&$str, &$in, &$iii, &$s, $btype) {
   $str = "<pre class=\"markup $btype\">" . $str . "</pre>";
}


function ewiki_format_html(&$str, &$in, &$iii, &$s) {
   $he = array_reverse($GLOBALS["ewiki_config"]["htmlentities"]);
   $str = strtr($str, array_flip($he));
//   $str = "<span class=\"markup html\">" . $str . "\n</span>\n"; 
   $str = $str . "\n"; 
}


function ewiki_format_comment(&$str, &$in, &$iii, &$s, $btype) {
   $str = "<!-- "  . str_replace("--", "", $str) . " -->";
}




/* unclean pre-scanning for WikiWords in a page,
   pre-query to the db */
function ewiki_scan_wikiwords(&$wiki_source, &$ewiki_links, $se=0) {

   global $ewiki_config, $ewiki_id;

   #-- find matches
   preg_match_all($ewiki_config["wiki_pre_scan_regex"], $wiki_source, $uu);
   $uu = array_merge((array)$uu[1], (array)$uu[2], (array)@$uu[3], (array)$uu[4]);

   #-- clean up list, trim() spaces (allows more unclean regex) - page id unification
   foreach ($uu as $i=>$id) {
      $uu[$i] = trim($id);
   }
   unset($uu[""]);
   $uu = array_unique($uu);

   #-- unfold SubPage names
   if (EWIKI_SUBPAGE_START) {
      foreach ($uu as $i=>$id) {
         if ($id && (strpos(EWIKI_SUBPAGE_START, $id[0]) !== false)) {
            if ($id[1] == "/") { $id = substr($id, 1); }
            $uu[$i] = $ewiki_id . $id;
         }
   }  }

   #-- query db
   $ewiki_links = ewiki_db::FIND($uu);

   #-- strip email adresses
   if ($se) {
      foreach ($ewiki_links as $c=>$uu) {
         if (strpos($c, "@") && (strpos($c, ".") || strpos($c, ":"))) {
            unset($ewiki_links[$c]);
         }
   }  }
}



/* regex on page content,
   handled by callback (see below)
*/
function ewiki_render_wiki_links(&$o) {
   global $ewiki_links, $ewiki_config, $ewiki_plugins;

   #-- merge with dynamic pages list
   ewiki_merge_links($ewiki_links);

   #-- replace WikiWords
   $link_regex = &$ewiki_config["wiki_link_regex"];
   $o = preg_replace_callback($link_regex, "ewiki_link_regex_callback", $o);

   #-- cleanup
///////////   unset($ewiki_links);
}


/* combines with page plugin list,
   and makes all case-insensitive (=lowercased)
   in accord with EWIKI_CASE_INSENSITIVE 
		(handled within ewiki_array)
*/
function ewiki_merge_links(&$ewiki_links) {
   global $ewiki_plugins;
   if ($ewiki_links !== true) {
      foreach ($ewiki_plugins["page"] as $page=>$uu) {
         $ewiki_links[$page] = 1;
      }
      $ewiki_links = ewiki_array($ewiki_links);
   }
}



/* link rendering (p)regex callback
   (ooutch, this is a complicated one)
*/
function ewiki_link_regex_callback($ii, $force_noimg=0) {

   global $ewiki_links, $ewiki_plugins, $ewiki_config, $ewiki_id;

   $str = trim($ii[0]);
   $type = array();
   $states = array();

   #-- link bracket '[' escaped with '!' or '~'
   if (($str[0] == "!") || ($str[0] == "~") || ($str[0] == "\\")) {
      return(substr($str, 1));
   }
   if ($str[0] == "#") {
      $states["define"] = 1;
      $str = substr($str, 1);
   }
   if ($str[0] == "[") {
      $states["brackets"] = 1;
      $str = substr($str, 1, -1);
      if (!strlen($str)) { return("[]"); }  //better: $ii[0]
   }

   #-- explicit title given via [ title | WikiLink ]
   $href = $title = strtok($str, "|");
   if ($uu = strtok("|")) {
      $href = $uu;
      $states["titled"] = 1;
   }
   #-- title and href swapped: swap back
   if (strpos("://", $title) || strpos($title, ":") && !strpos($href, ":")) {
      $uu = $title; $title = $href; $href = $uu;
   }
   #-- new entitling scheme [ url "title" ]
   if ((($l=strpos($str, '"')) < ($r=strrpos($str, '"'))) && ($l!==false) ) {
      $title = substr($str, $l + 1, $r - $l - 1);
      $href = substr($str, 0, $l) . substr($str, $r + 1);
      $states["titled"] = 1;
      if (!$href) { return($ii[0]); }
   }

   #-- strip spaces
   $spaces_l = ($href[0]==" ") ?1:0;
   $spaces_r = ($href[strlen($href)-1]==" ") ?1:0;
   $title = ltrim(trim($title), "^");
   $href = ltrim(trim($href), "^");

   #-- strip_htmlentities()
   if (1&&    (strpos($href, "&")!==false) && strpos($href, ";")) {
      ewiki_stripentities($href);
   }
 
   #-- anchors
   $href2 = "";
   if (($p = strrpos($href, "#")) && ($p) && ($href[$p-1] != "&")) {
      $href2 = trim(substr($href, $p));
      $href = trim(substr($href, 0, $p));
   }
   elseif ($p === 0) {
      $states["define"] = 1;
   }
   if ($href == ".") {
      $href = $ewiki_id;
   }
   


   #-- SubPages
   $c0 = $href[0];
   if ($c0 && (strpos(EWIKI_SUBPAGE_START, $c0) !== false)) {
      $_set = EWIKI_SUBPAGE_LONGTITLE && ($href==$title);
      if (($href[1] == "/")) {   ##($c0 == ".") && 
         $href = substr($href, 1);
      }
      $href = $ewiki_id . $href;
      if ($_set) {
         $title = $href;
      }
   }

   #-- for case-insensitivines
   $href_i = EWIKI_CASE_INSENSITIVE ? strtolower($href) : ($href);

   #-- injected URLs
   if (isset($ewiki_links[$href_i]) && strpos($inj_url = $ewiki_links[$href_i], "://")) {
      if ($href==$title) { $href = $inj_url; }
   }
   $states["title"] = &$title;

   #-- interwiki links
   if (strpos($href, ":") && ($uu = ewiki_interwiki($href, $type, $states))) {
      $href = $uu;
      $str = "<a href=\"$href$href2\">$title</a>";
   }
   #-- action:WikiLinks
   elseif (isset($ewiki_plugins["action"][$a=strtolower(strtok($href, ":"))])) {
      $type = array($a, "action", "wikipage");
      $str = '<a href="' . ewiki_script($a, strtok("\000")) . '">' . $title . '</a>';
   }
   #-- page anchor definitions, if ($href[0]=="#")
   elseif (@$states["define"]) {
      $type = array("anchor");
      if ($title==$href) { 
         $title = "";   // was "&nbsp;" before, but that's not required
      }
      $str = '<a name="' . htmlentities(ltrim($href, "#")) . '">' . ltrim($title, "#") . '</a>';
   }
   #-- inner page anchor jumps
   elseif (strlen($href2) && ($href==$ewiki_id) || ($href[0]=="#") && ($href2=&$href)) {
      $type = array("jump");
      $str = '<a href="' . htmlentities($href2) . '">' . $title . '</a>';
   }
   #-- ordinary internal WikiLinks
   elseif (($ewiki_links === true) || @$ewiki_links[$href_i]) {
      $type = array("wikipage");
      $str = '<a href="' . ewiki_script("", $href) . htmlentities($href2)
           . '">' . $title . '</a>';
   }
   #-- guess for mail@addresses, convert to URI if
   elseif (strpos($href, "@") && !strpos($href, ":")) {
      $type = array("email");
      $href = "mailto:" . $href;
   }

   #-- not found fallback
   else {
      $str = "";
      #-- a plugin may take care
      if ($pf_a = @$ewiki_plugins["link_notfound"]) {
         foreach ($pf_a as $pf) {
            if ($str = $pf($title, $href, $href2, $type)) {
               break;
         }  }
      }
      #-- (QuestionMarkLink to edit/ action)
      if (!$str) {
         $type = array("notfound");
         $t = $ewiki_config["qmark_links"];
         $str = ewiki_script(isset($t[4]) ? "edit" : "", $href);
         if (strlen($t) >= 3) {
            $str = ($t[0] ? "<a href=\"$str\">$t[0]</a>" :'')
                 . ($t[1] ? "<$t[1]>$title</$t[1]>" : $title)
                 . ($t[2] ? "<a href=\"$str\">$t[2]</a>" :'');
         } else {
            $str = "<a href=\"$str\">" . $title . "</a>";
            if ($t<0) { $str .= "?"; }
         }
         $str = '<span class="NotFound">' . $str . '</span>';
      }
   }

   #-- convert standard and internal:// URLs
   $is_url = ereg('^('.implode('|', $ewiki_config["idf"]["url"]).')', $href);
   $is_internal = 0;
   //
   if (!$is_url && ($ewiki_links[$href_i]["flags"] & EWIKI_DB_F_BINARY)) {
      $is_url = 1;
      $is_internal = 1;
   }
   if ($is_url) {
      $type[-2] = "url";
      $type[-1] = strtok($href, ":");

      #-- [http://url titles]
      if (strpos($href, " ") && ($title == $href)) {
         $href = strtok($href, " ");
         $title = strtok("\377");
      }

      #-- URL plugins
      if ($pf_a = $ewiki_plugins["link_url"]) foreach ($pf_a as $pf) {
         if ($str = $pf($href, $title, $status)) { break 2; }
      }
      $meta = @$ewiki_links[$href];

      #-- check for image files
      $ext = substr($href, strrpos($href,"."));
      $nocache = strpos($ext, "no");
      $ext = strtok($ext, "?&#");
      $obj = in_array($ext, $ewiki_config["idf"]["obj"]);
      $img = (strncmp(strtolower($href), "data:image/", 11) == 0) && ($nocache=1)
             || $obj || in_array($ext, $ewiki_config["idf"]["img"]);

      #-- internal:// references (binary files)
      $id = $href;
      if (EWIKI_SCRIPT_BINARY && ((strpos($href, EWIKI_IDF_INTERNAL)===0)  ||
          EWIKI_IMAGE_MAXSIZE && EWIKI_CACHE_IMAGES && $img && !$nocache) ||
          $is_internal )
      {
         $type = array("binary");
         $href = ewiki_script_binary("", $href);
      }

      #-- output html reference
      if (!$img || $force_noimg || !$states["brackets"] || (strpos($href, EWIKI_IDF_INTERNAL) === 0)) {
//@FIX: #add1   || $href2  (breaks #.jpeg hack, but had a purpose?)
         $str = '<a href="' . $href /*#($href2)*/ . '">' . $title . '</a>';
      }
      #-- img tag
      else {
         $type = array("image");
         if (is_string($meta)) {
            $meta = unserialize($meta);
         }
         $str = ewiki_link_img($href, $id, $title, $meta, $spaces_l+2*$spaces_r, $obj, $states);
      }
   }

   #-- icon/transform plugins
   ksort($type);
   if ($pf_a = @$ewiki_plugins["link_final"]) {
      foreach ($pf_a as $pf) { $pf($str, $type, $href, $title, $states); }
   }
   if (isset($states["xhtml"]) && $states["xhtml"]) {
      foreach ($states["xhtml"] as $attr=>$val) {
         $str = str_replace("<a ", "<a $attr=\"$val\" ", $str);
      }
   }

   return($str);
}


/*
   assembles an <img> tag
*/
function ewiki_link_img($href, $id, $title, $meta, $spaces, $obj, $states) {

   #-- size of cached image
   $x = $meta["width"];
   $y = $meta["height"];

   #-- width/height given in url
   if ($p = strpos($id, '?')) {
      $id = str_replace("&amp;", "&", substr($id, $p+1));
      parse_str($id, $meta);
      if ($uu = $meta["x"].$meta["width"]) {
         $x = $uu;
      }
      if ($uu = $meta["y"].$meta["height"]) {
         $y = $uu;
      }
      if ($scale = $meta["r"] . $meta["scale"]) {
         if ($p = strpos($scale, "%")) {
            $scale = strpos($scale, 0, $p) / 100;
         }
         $x *= $scale;
         $y *= $scale;
      }
   }

   #-- alignment
   $align = array('', ' align="right"', ' align="left"', ' align="center"');
   $align = $align[$spaces];
   $size = ($x && $y ? " width=\"$x\" height=\"$y\"" : "");
   
   #-- remove annoyances
   if ($href==$title) {
      $title = "";
   }

   #-- do
   return
     ($obj ? '<embed' : '<img')
     . ' src="' . $href . '"'
     . ' alt="' . htmlentities($title) . '"'
     . (@$states["titled"] ? ' title="' . htmlentities($title) . '"' : '')
     . $size . $align
     . ($obj ? "></embed>" : " />");
   # htmlentities($title)
}


function ewiki_stripentities(&$str) {
   static $un = array("&lt;"=>"<", "&gt;"=>">", "&amp;"=>"&");
   $str = strtr($str, $un);
}


/*
   Returns URL if it encounters an InterWiki:Link or workalike.
*/
function ewiki_interwiki(&$href, &$type, &$s) {
   global $ewiki_config, $ewiki_plugins;

   $l = strpos($href, ":");
   if ($l and (strpos($href,"//") != $l+1) and ($p1 = strtok($href, ":"))) {
      $page = strtok("\000");

      if (($p2 = ewiki_array($ewiki_config["interwiki"], $p1)) !== NULL) {
         $p1 = $p2;
         $type = array("interwiki", $uu);
         while ($p1_alias = $ewiki_config["interwiki"][$p1]) {
             $type[] = $p1;
             $p1 = $p1_alias;
         }
         if (!strpos($p1, "%s")) {
             $p1 .= "%s";
         }
         $href = str_replace("%s", $page, $p1);
         return($href);
      }
      elseif ($pf = $ewiki_plugins["intermap"][$p1]) {
         return($pf($p1, $page));
      }
      elseif ($pf_a = $ewiki_plugins["interxhtml"]) {
         foreach($pf_a as $pf) {
            $pf($p1, $page, $s);
         }
         $href = $page;
      }
   }
}


/* 
   implements FeatureWiki:InterMapWalking
*/
function ewiki_intermap_walking($id, &$data, $action) {
   if (empty($data["version"]) && ($href = ewiki_interwiki($id, $uu, $uu))) {
      header("Location: $href$sid");
      return("<a href=\"$href\">$href</a>");
   }
}



function ewiki_link($pagename, $title="") {
   if (!($url = ewiki_interwiki($pagename, $uu, $uu))) {
      $url = ewiki_script("", $pagename);
   }
   if (!$title) { $title = $pagename; }
   return("<a href=\"$url\">".htmlentities($title)."</a>");
}



# =========================================================================



#####    ##  ##   ##    ##    #####   ##  ##
######   ##  ###  ##   ####   ######  ##  ##
##  ##   ##  ###  ##  ######  ##  ##  ##  ##
#####    ##  #### ##  ##  ##  ######  ######
#####    ##  #######  ######  ####     ####
##  ###  ##  ## ####  ######  #####     ##
##  ###  ##  ##  ###  ##  ##  ## ###    ##
######   ##  ##  ###  ##  ##  ##  ##    ##
######   ##  ##   ##  ##  ##  ##  ##    ##




/*  fetch & store
*/
function ewiki_binary($break=0) {

   global $ewiki_plugins;

   #-- reject calls
   if (!strlen($id = @$_REQUEST[EWIKI_UP_BINARY]) || !EWIKI_IDF_INTERNAL) {
      return(false);
   }
   if (headers_sent()) die("ewiki-binary configuration error");

   #-- upload requests
   $upload_file = @$_FILES[EWIKI_UP_UPLOAD];
   $add_meta = array();
   if ($orig_name = @$upload_file["name"]) {
      $add_meta["Content-Location"] = urlencode($orig_name);
      $add_meta["Content-Disposition"] = 'inline; filename="'.urlencode(basename("remote://$orig_name")).'"';
   }

   #-- what are we doing here?
   if (($id == EWIKI_IDF_INTERNAL) && ($upload_file)) { 
      $do = "upload";
   }
   else {
      $data = ewiki_db::GET($id);
      $flags = @$data["flags"];
      if (EWIKI_DB_F_BINARY == ($flags & EWIKI_DB_F_TYPE)) { 
         $do = "get";
      }
      elseif (empty($data["version"]) and EWIKI_CACHE_IMAGES) {
         $do = "cache";
      }
      else { 
         $do = "nop";
      }
   }

   #-- auth only happens when enforced with _PROTECTED_MODE_XXL setting
   #   (authentication for inline images in violation of the WWW spirit)
   if ((EWIKI_PROTECTED_MODE>=5) && !ewiki_auth($id, $data, "binary-{$do}")) {
      return($_REQUEST["id"]="view/BinaryPermissionError");
   }

   #-- upload an image
   if ($do == "upload"){

      ($title = trim($orig_name, "/")) && ($title = preg_replace("/[^-._\w\d]+/", "_", substr(substr($orig_name, strrpos($title, "/")), 0, 20)))
      && ($title = ' \\"'.$title.'\\"') || ($title="");
      $id = ewiki_binary_save_image($upload_file["tmp_name"], preg_replace("/[^a-zA-Z0-9\._]/", "", $title), $return=0, $add_meta);
      @unlink($upload_file["tmp_name"]);

      if ($id) {
         echo<<<EOF
<html><head><title>File/Picture Upload</title><script language="JavaScript" type="text/javascript"><!--
 opener.document.forms["ewiki"].elements["content"].value += "\\nUPLOADED PICTURE: [$id$title]\\n";
 window.setTimeout("self.close()", 5000);
//--></script></head><body bgcolor="#440707" text="#FFFFFF">Your uploaded file was saved as<br /><big><b>
[$id]
</b></big>.<br /><br /><noscript>Please copy this &uarr; into the text input box:<br />select/mark it with your mouse, press [Ctrl]+[Insert], go back<br />to the previous screen and paste it into the textbox by pressing<br />[Shift]+[Insert] inside there.</noscript></body></html>
EOF;
      }
   }

   #-- request for contents from the db
   elseif ($do == "get") {

      #-- send http_headers from meta
      if (is_array($data["meta"])) {
         foreach ($data["meta"] as $hdr=>$val) {
            if (($hdr[0] >= "A") && ($hdr[0] <= "Z")) {
               header("$hdr: $val");
            }
         }
      }

      #-- fetch from binary store
      if ($pf_a = $ewiki_plugins["binary_get"]) {
         foreach ($pf_a as $pf) { $pf($id, $data["meta"]); }
      }

      #-- else fpassthru
      echo $data["content"];
   }

   #-- fetch & cache requested URL,
   elseif ($do == "cache") {

      #-- check for standard protocol names, to prevent us from serving
      #   evil requests for '/etc/passwd.jpeg' or '../.htaccess.gif'
      if (preg_match('@^\w?(http|ftp|https|ftps|sftp)\w?://@', $id)) {

         #-- generate local copy
         $filename = tempnam(EWIKI_TMP, "ewiki.local.temp.");
            if(!copy($id, $filename)){
              ewiki_log("ewiki_binary: error copying $id to $filename", 0);
            } else {
            $add_meta = array(
               "Content-Location" => urlencode($id),
               "Content-Disposition" => 'inline; filename="'.urlencode(basename($id)).'"',
               'PageType' => 'CachedImage'
            );

            $result = ewiki_binary_save_image($filename, $id, "RETURN", $add_meta);
         }
      }      

      #-- deliver
      if ($result && !$break) {
         ewiki_binary($break=1);
      }
      #-- mark URL as unavailable
      else {
         $data = array(
            "id" => $id,
            "version" => 1, 
            "flags" => EWIKI_DB_F_DISABLED,
            "lastmodified" => time(),
            "created" => time(),
            "author" => ewiki_author("ewiki_binary_cache"),
            "content" => "",
            "meta" => array("Status"=>"404 Absent"),
         );
         ewiki_db::WRITE($data);
         header("Location: $id");
         ewiki_log("imgcache: did not find '$id', and marked it now in database as DISABLED", 2);
      }
      
   }

   #-- "we don't sell this!"
   else {
      if (strpos($id, EWIKI_IDF_INTERNAL) === false) {
         header("Status: 301 Located SomeWhere Else");
         header("Location: $id");
      }
      else {
         header("Status: 404 Absent");
         header("X-Broken-URI: $id");
      }
   }

   // you should not remove this one, it is really a good idea to use it!
   die();
}






function ewiki_binary_save_image($filename, $id="", $return=0,
$add_meta=array(), $accept_all=EWIKI_ACCEPT_BINARY, $care_for_images=1)
{
   global $ewiki_plugins;

   #-- break on empty files
   if (!filesize($filename)) {
      return(false);
   }

   #-- check for image type and size
   $mime_types = array(
      "application/octet-stream",
      "image/gif",
      "image/jpeg",
      "image/png",
      "application/x-shockwave-flash"
   );
   $ext_types = array(
      "bin", "gif", "jpeg", "png", "swf"
   );
   list($width, $height, $mime_i, $uu) = getimagesize($filename);
   (!$mime_i) && ($mime_i=0) || ($mime = $mime_types[$mime_i]);

   #-- images expected
   if ($care_for_images) {

      #-- mime type
      if (!$mime_i && !$accept_all || !filesize($filename)) {
         ewiki_die(ewiki_t("BIN_NOIMG"), $return);
         return;
      }

      #-- resize image
      if ((strpos($mime,"image/")!==false)
      && (EWIKI_IMAGE_RESIZE)) {   // filesize() check now in individual resize plugins
         if ($pf_a = $ewiki_plugins["image_resize"]) foreach ($pf_a as $pf) {
            $pf($filename, $mime, $return);
            clearstatcache();
         }
      }

      #-- reject image if too large
      if(filesize($filename) > EWIKI_IMAGE_MAXSIZE) {
         ewiki_die(ewiki_t("BIN_IMGTOOLARGE"), $return);
         return;
      }

      #-- again check mime type and image sizes
      list($width, $height, $mime_i, $uu) = getimagesize($filename);
      (!$mime_i) && ($mime_i=0) || ($mime = $mime_types[$mime_i]);

   }
   ($ext = $ext_types[$mime_i]) or ($ext = $ext_types[0]);

   #-- binary files
   if ((!$mime_i) && ($pf = $ewiki_plugins["mime_magic"][0])) {
      if ($tmp = $pf($content)) {
         $mime = $tmp;
      }
   }
   if (!strlen($mime)) {
      $mime = $mime_types[0];
   }

   #-- store size of binary file
   $add_meta["size"] = filesize($filename);
   $content = "";

   #-- handler for (large/) binary content?
   if ($pf_a = $ewiki_plugins["binary_store"]) {
      foreach ($pf_a as $pf) {
         $pf($filename, $id, $add_meta, $ext);
      }
   }

   #-- read file into memory (2MB), to store it into the database
   if ($filename) {
      $f = fopen($filename, "rb");
      $content = fread($f, 1<<21);
      fclose($f);
   }

   #-- generate db file name
   if (empty($id)) {
      $md5sum = md5($content);
      $id = EWIKI_IDF_INTERNAL . $md5sum . ".$ext";
      ewiki_log("generated md5sum '$md5sum' from file content");
   }

   #-- prepare meta data
   $meta = array(
      "class" => $mime_i ? "image" : "file",
      "Content-Type" => $mime,
      "Pragma" => "cache",
   ) + (array)$add_meta;
   if ($mime_i) {
      $meta["width"] = $width;
      $meta["height"] = $height;
   }

   #-- database entry
   $data = array(
      "id" => $id,
      "version" => "1", 
      "author" => ewiki_author(),
      "flags" => EWIKI_DB_F_BINARY | EWIKI_DB_F_READONLY,
      "created" => time(),
      "lastmodified" => time(),
      "meta" => &$meta,
      "content" => &$content,
   );
   
   #-- write if not exist
   $exists = ewiki_db::FIND(array($id));
   if (! $exists[$id] ) {
      $result = ewiki_db::WRITE($data);
      ewiki_log("saving of '$id': " . ($result ? "ok" : "error"));
   }
   else {
      ewiki_log("binary_save_image: '$id' was already in the database", 2);
   }

   return($id);
}




# =========================================================================


####     ####  ####   ########     ########
#####   #####  ####  ##########   ##########
###### ######  ####  ####   ###   ####    ###
#############        ####        ####
#############  ####   ########   ####
#### ### ####  ####    ########  ####
####  #  ####  ####        ####  ####
####     ####  ####  ###   ####  ####    ###
####     ####  ####  #########    ##########
####     ####  ####   #######      ########



/* yes! it is not necessary to annoy people with country flags, if
   HTTP already provides means to determine the prefered language!
*/
function ewiki_localization() {

   global $ewiki_t;

   $deflangs = ','.@$_ENV["LANGUAGE"] . ','.@$_ENV["LANG"]
             . ",".EWIKI_DEFAULT_LANG . ",en,C";

   foreach (explode(",", @$_SERVER["HTTP_ACCEPT_LANGUAGE"].$deflangs) as $l) {

      $l = strtok($l, ";");
      $l = strtok($l, "-"); $l = strtok($l, "_"); $l = strtok($l, ".");

      if ($l = trim($l)) {
         $ewiki_t["languages"][] = strtolower($l);
      }
   }
   
   $ewiki_t["languages"] = array_unique($ewiki_t["languages"]);
}




/* poor mans gettext, $repl is an array of string replacements to get
   applied to the fetched text chunk,
   "$const" is either an entry from $ewiki_t[] or a larger text block
   containing _{text} replacement braces of the form "_{...}"
*/
function ewiki_t($const, $repl=array(), $pref_langs=array()) {

   global $ewiki_t;

   #-- use default language wishes
   if (empty($pref_langs)) {
      $pref_langs = $ewiki_t["languages"];
   }

   #-- large text snippet replacing
   if (strpos($const, "_{") !== false) {
      while ( (($l=strpos($const,"_{")) || ($l===0)) && ($r=strpos($const,"}",$l)) ) {
         $const = substr($const, 0, $l)
                . ewiki_t(substr($const, $l+2, $r-$l-2))
                . substr($const,$r+1);
      }
   }

   #-- just one string
   else foreach ($pref_langs as $l) {

      if (is_string($r = @$ewiki_t[$l][$const]) || ($r = @$ewiki_t[$l][strtoupper($const)])) {

         foreach ($repl as $key=>$value) {
            if ($key[0] != '$') {
               $key = '$'.$key;
            }
            $r = str_replace($key, $value, $r);
         }
         return($r);

      }
   }

   return($const);
}




/* takes all ISO-8859-1 characters into account
   but won't work with all databases
*/
function ewiki_lowercase($s) {
   $len = strlen($s);
   for ($i=0; $i<$len; $i++) {
      if (ord($s[$i]) >= 192) {
         $s[$i] = chr(ord($s[$i]) | 0x20);
      }
   }
   return(strtolower($s));
}




function ewiki_log($msg, $error_type=3) {

   if ((EWIKI_LOGLEVEL >= 0) && ($error_type <= EWIKI_LOGLEVEL)) {

      $msg = time() . " - " .
             $_SERVER["REMOTE_ADDR"] . ":" . $_SERVER["REMOTE_PORT"] . " - " .
             $_SERVER["REQUEST_METHOD"] . " " . $_SERVER["REQUEST_URI"] . " - " .
             strtr($msg, "\n\r\000\377\t\f", "\r\r\r\r\t\f") . "\n";
      error_log($msg, 3, EWIKI_LOGFILE);
   }
}




function ewiki_die($msg, $return=0) {
   ewiki_log($msg, 1);
   if ($return) {
      return($GLOBALS["ewiki_error"] = $msg);
   }
   else {
      die($msg);
   }
}



function ewiki_array_hash(&$a) {
   return(count($a) . ":" . implode(":", array_keys(array_slice($a, 0, 3))));
}



/* provides an case-insensitive in_array replacement to search a page name
   in a list of others;
   the supplied $array WILL be lowercased afterwards, unless $dn was set
*/
function ewiki_in_array($value, &$array, $dn=0, $ci=EWIKI_CASE_INSENSITIVE) {

   static $as = array();

   #-- work around pass-by-reference
   if ($dn && $ci) {   $dest = array();   }
              else {   $dest = &$array;   }

   #-- make everything lowercase
   if ($ci) {
      $value = strtolower($value);
      if (empty($as[ewiki_array_hash($array)])) {  // prevent working on the
         foreach ($array as $i=>$v) {              // same array multiple times
            $dest[$i] = strtolower($v);
         }
         $as[ewiki_array_hash($dest)] = 1;
      }
   }

   #-- search in values
   return(in_array($value, $dest));
}



/* case-insensitively retrieves an entry from an $array,
   or returns the given $array lowercased if $key was obmitted
*/
function ewiki_array($array, $key=false, $am=1, $ci=EWIKI_CASE_INSENSITIVE) {

   #-- make everything lowercase
   if ($ci) {
      $key = strtolower($key);

      $r = array();
      foreach ($array as $i=>$v) {
         $i = strtolower($i);
         if (!$am || empty($r[$i])) {
            $r[$i] = $v;
         }
         else {
            $r[$i] .= $v;	//RET: doubling for images`meta won't happen
         }			// but should be "+" here for integers
      }
      $array = &$r;
   }

   #-- search in values
   if ($key) {
      return(@$array[$key]);
   }
   else {
      return($array);
   }
}





/*
   generates {author} string field for page database entry updates
*/
function ewiki_author($defstr="") {

   $author = @$GLOBALS["ewiki_author"];
   ($ip = &$_SERVER["REMOTE_ADDR"]) or ($ip = "127.0.0.0");
   ($port = $_SERVER["REMOTE_PORT"]) or ($port = "null");

   #-- this call may be very slow (~20 sec)
   if (EWIKI_RESOLVE_DNS) {
      $hostname = gethostbyaddr($ip);
   }
   $remote = (($ip != $hostname) ? $hostname . " " : "")
           . $ip . ":" . $port;

   (empty($author)) && (
      ($author = $defstr) ||
      ($author = $_SERVER["HTTP_FROM"]) ||	// RFC2068 sect 14.22
      ($author = $_SERVER["PHP_AUTH_USER"])
   );

   (empty($author))
      && ($author = $remote)
      || ($author = addslashes($author) . " (" . $remote . ")" );

   return($author);
}

/*
   decodes {author} field for display in pages
*/
function ewiki_author_html($orig, $tail=1) {
   $str = strtok($orig, " (|,;/[{<+");
   $tail = $tail ? " " . strtok("\000") : "";
   #-- only IP
   if (strpos($str, ":")) {
      return('<a href="'. strtok($str, ":") . "\">$orig</a>");
   }
   #-- mail address
   elseif (strpos($str, "@")) {
      // email_protect_*() now takes care of plugin pages
      return("<a href=\"mailto:$str\">$str</a>$tail");
   }
   #-- host name
   elseif (strpos($str, ".") < strrpos($str, ".")) {
      return("<a href=\"http://$str/\">$str</a>$tail");
   }
   #-- eventually an AuthorName
   else {
      return('<a href="' . ewiki_script("", $str) . '">' . $str . '</a>' . $tail);
   }
   return($orig);
}









/*  Returns a value of (true) if the currently logged in user (this must
    be handled by one of the plugin backends) is authenticated to do the
    current $action, or to view the current $id page.
  - alternatively just checks current authentication $ring permission level
  - errors are returned via the global $ewiki_errmsg
*/
function ewiki_auth($id, &$data, $action, $ring=false, $request_auth=0) {

   global $ewiki_plugins, $ewiki_ring, $ewiki_author,
      $ewiki_errmsg, $ewiki_config;
   $ok = true;
   $ewiki_errmsg="";

#echo "_a($id,dat,$action,$ring,$request_auth)<br />\n";

   if (EWIKI_PROTECTED_MODE) {

      #-- set required vars
      if (!isset($ewiki_ring)) {
         $ewiki_ring = (int)EWIKI_AUTH_DEFAULT_RING;
      }
      if ($ring===false) {
         $ring = NULL;
      }
      if ($ewiki_config["create"] && ($action=="edit")) {
         $action = "create";  // used only/primarily in authentication plugins
      }

      #-- plugins to call
      $pf_login = @$ewiki_plugins["auth_query"][0];
      $pf_perm = $ewiki_plugins["auth_perm"][0];

      #-- nobody is currently logged in, so try to fetch username,
      #   the login <form> is not yet enforced
      if ($pf_login && empty($ewiki_auth_user)) {
         $pf_login($data, 0);
      }

      #-- check permission for current request (page/action/ring)
      if ($pf_perm) {

         #-- via _auth handler
         $ok = $pf_perm($id, $data, $action, $ring, $request_auth);

         #-- if it failed, we really depend on the login <form>,
         #   and then recall the _perm plugin
         if ($pf_login && (($request_auth >= 2) || !$ok && $request_auth && (empty($ewiki_auth_user) || EWIKI_AUTO_LOGIN) && empty($ewiki_errmsg))) {
//@FIXME: complicated if()  - strip empty(errmsg) ??
            $pf_login($data, $request_auth);
            $ok = $pf_perm($id, $data, $action, $ring, $request_auth=0);
         }
      }
      else {
         $ok = !isset($ring) || isset($ring) && ($ewiki_ring <= $ring);
      }

      #-- return error string
      if (!$ok && empty($ewiki_errmsg)) {
         ewiki_log("ewiki_auth: Access Denied ($action/$id, $ring/$ewiki_ring, $request_auth)");
         $ewiki_errmsg = ewiki_t("FORBIDDEN");
      }
   }

   return($ok);
}


/*
   Queries all registered ["auth_userdb"] plugins for the given
   username, and compares password to against "db" value, sets
   $ewiki_ring and returns(true) if valid.
*/
function ewiki_auth_user($username, $password) {
  global $ewiki_ring, $ewiki_errmsg, $ewiki_auth_user, $ewiki_plugins, $ewiki_author;

  if (empty($username)) {
     return(false);
  }
  if (($password[0] == "$") || (strlen($password) > 12)) {
     ewiki_log("_auth_userdb: password was transmitted in encoded form, or is just too long (login attemp for user '$username')", 2);
     return(false);
  }

  if ($pf_u = $ewiki_plugins["auth_userdb"])
  foreach ($pf_u as $pf) {

     if (function_exists($pf) && ($entry = $pf($username, $password))) {

        #-- get and compare password
        if ($entry = (array) $entry) {
           $enc_pw = $entry[0];
        }
        $success = false
                || ($enc_pw == substr($password, 0, 12))
                || ($enc_pw == md5($password))
                || ($enc_pw == crypt($password, substr($enc_pw, 0, 2)))
                || function_exists("sha1") && ($enc_pw == sha1($password));
        $success &= $enc_pw != "*";

        #-- return if it matches
        if ($success) {
           if (isset($entry[1])) { 
              $ewiki_ring = (int)($entry[1]);
           } else {
              $ewiki_ring = 2;  //(EWIKI_AUTH_DEFAULT_RING - 1);
           }
           if (empty($ewiki_author)) {
              ($ewiki_author = $entry[2]) or
              ($ewiki_author = $username);
           }
           return($success && ($ewiki_auth_user=$username));
        }
     }
  }

  if ($username || $password) {
     ewiki_log("_auth_userdb: wrong password supplied for user '$username', not verified against any userdb", 3);
     $ewiki_errmsg = "wrong username and/or password";
#     ewiki_auth($uu, $uu, $uu, $uu, 2);
  }
  return(false);
}




/*
   Returns <form> field html strings, looks up previously selected values.
   Don't use it for textareas, $value magically elects the input field type.
*/
function ewiki_form($name, $value, $label="", $_text="| |\n", $inj="") {
   global $_EWIKI, $ewiki_id;
   static $fid = 50;
   static $_sel = ' selected="selected"', $_chk = ' checked="checked"';

   #-- prepare
   $o = "";
   $_text = explode("|", $_text);
   list($name, $type, $width, $height) = explode(":", $name);
   $type = $type ? (strpos($type, "a") ? "a" : (strpos($type, "b") ? "b" : $type[0])) : "t";
   if ($inj) { $inj = " $inj"; }
   $old_value = @$_EWIKI["form"][$ewiki_id][$name];

   #-- select fields
   if ((($type=="s") || strpos($value, "|")) && ($v = explode("|", $value))) {
      $value = array();
      foreach ($v as $opt) {
         $opt = strtok($opt, "="); ($title = strtok("|")) or ($title = $opt);
         $value[$opt] = $title;
      }
   }

   #-- label, surrounding text
   $o .= "$_text[0]";
   if ($fid++ && $label) {
      $o .= "<label for=\"ff$fid\">$label</label>";
   }
   echo "$_text[1]";

   #-- submit (as "button")
   if (!$name || ($type=="b")) {
      if ($name) { $name = " name=\"$name\""; }
      $o .= "<input type=\"submit\" id=\"ff$fid\"$name value=\"$value\"$inj />";
   }
   #-- select
   elseif (is_array($value)) {
      $o .= "<select name=\"$name\" id=\"ff$fid\"$inj>";
      $no_val = isset($value[0]);
      foreach ($value as $val=>$title) {
         if ($no_val) { $val = $title; }
         $sel = (!$old_value && strpos($val, "!") || ($old_value==$val)) ? $_sel : "";
         $o .= '<option value="' . trim(rtrim($val, "!")) . '"' . $sel . '>'
            . trim(rtrim($title, "!")) . '</option>';
      }
      $o .= "</select>";
   }
   #-- checkbox
   elseif (($type=="c") || strpos($value, "]")) {
      if (isset($old_value)) { $value = $old_value ? "1x" : ""; }
      $sel = strpos($value, "x") ? $_chk : "";
      $o .= "<input type=\"checkbox\" id=\"ff$fid\" name=\"$name\" value=\"1\"$sel$inj />";
   }
   #-- textarea
   elseif ($type=="a") {
      if ($width && $height) { $inj .= " cols=\"$width\" rows=\"$height\""; }
      $o .= "<textarea id=\"ff$fid\" name=\"$name\"$inj>"
         . htmlentities($value) . "</textarea>";
   }
   #-- input field; text or hidden
   else {
      if ($width) { $inj .= " size=\"$width\""; }
      if (isset($old_value)) { $value = $old_value; }
      $type = ($type == "t") ? "text" : "hidden";
      $o .= "<input type=\"$type\" id=\"ff$fid\" name=\"$name\" value=\""
         . htmlentities($value) . "\"$inj />";
   }

   #-- fin
   $o .= "$_text[2]";
   return($o);
}




/*  reads all files from "./init-pages/" into the database,
    when ewiki is run for the very first time and the FrontPage
    does not yet exist in the database
*/
function ewiki_eventually_initialize(&$id, &$data, &$action) {

   #-- initialize database only if frontpage missing
   if (($id==EWIKI_PAGE_INDEX) && ($action=="edit") && empty($data["version"])) {

      ewiki_db::INIT();
      if ($dh = @opendir($path=EWIKI_INIT_PAGES)) {
         while ($filename = readdir($dh)) {
            if (preg_match('/^(['.EWIKI_CHARS_U.']+['.EWIKI_CHARS_L.']+\w*)+/', $filename)) {
               $found = ewiki_db::FIND(array($filename));
               if (! $found[$filename]) {
                  $content = implode("", file("$path/$filename"));
                  ewiki_scan_wikiwords($content, $ewiki_links, "_STRIP_EMAIL=1");
                  $refs = "\n\n" . implode("\n", array_keys($ewiki_links)) . "\n\n";
                  $save = array(
                     "id" => "$filename",
                     "version" => "1",
                     "flags" => EWIKI_DB_F_TEXT,
                     "content" => $content,
                     "author" => ewiki_author("ewiki_initialize"),
                     "refs" => $refs,
                     "lastmodified" => filemtime("$path/$filename"),
                     "created" => filectime("$path/$filename")   // (not exact)
                  );
                  ewiki_db::WRITE($save);
               }
            }
         }
         closedir($dh);
      }
      else {
         echo "<b>ewiki error</b>: could not read from directory ". realpath($path) ."<br />\n";
      }

      #-- try to view/ that newly inserted page
      if ($data = ewiki_db::GET($id)) {
         $action = "view";
      }
   }
}




#---------------------------------------------------------------------------



########     ###    ########    ###    ########     ###     ######  ########
########     ###    ########    ###    ########     ###     ######  ########
##     ##   ## ##      ##      ## ##   ##     ##   ## ##   ##    ## ##
##     ##   ## ##      ##      ## ##   ##     ##   ## ##   ##    ## ##
##     ##  ##   ##     ##     ##   ##  ##     ##  ##   ##  ##       ##
##     ##  ##   ##     ##     ##   ##  ##     ##  ##   ##  ##       ##
##     ## ##     ##    ##    ##     ## ########  ##     ##  ######  ######
##     ## ##     ##    ##    ##     ## ########  ##     ##  ######  ######
##     ## #########    ##    ######### ##     ## #########       ## ##
##     ## #########    ##    ######### ##     ## #########       ## ##
##     ## ##     ##    ##    ##     ## ##     ## ##     ## ##    ## ##
##     ## ##     ##    ##    ##     ## ##     ## ##     ## ##    ## ##
########  ##     ##    ##    ##     ## ########  ##     ##  ######  ########
########  ##     ##    ##    ##     ## ########  ##     ##  ######  ########




#-- database API (static wrapper around backends)
class ewiki_db {


   #-- load page
   # returns database entry as array for the page whose name was given in
   # $id key, usually fetches the latest version of a page, unless a specific
   # $version was requested
   #
   function GET($id, $version=false) {
      global $ewiki_db;
      $r = $ewiki_db->GET($id, 0+$version);
      ewiki_db::expand($r);
      return($r);
   }

   
   #-- save page
   # stores the page $hash into the database, while not overwriting existing
   # entries unless $overwrite was set; returns 0 on failure, 1 if completed
   #
   function WRITE($hash, $overwrite=0) {
      global $ewiki_db;
      if (is_array($hash) && count($hash) && !defined("EWIKI_DB_LOCK")) {
         #-- settype (for flat-file databases)
         $hash["version"] += 0;
         $hash["hits"] += 0;
         $hash["lastmodified"] += 0;
         $hash["created"] += 0;
         ewiki_db::shrink($hash);
         return $ewiki_db->WRITE($hash, $overwrite);
      }
   }

   #-- search
   # returns dbquery_result object of database entries (also arrays), where
   # the one specified column matches the specified content string;
   # it is not guaranteed to only search/return the latest version of a page;
   # $field may be an array, in which case an OR-search is emulated
   #
   function SEARCH($field, $content, $ci=1, $regex=0, $mask=0x0000, $filter=0x0000) {
      global $ewiki_db;
      $ci = ($ci ? "i" : false);
      #-- multisearch (or connected)
      if (is_array($field)) {
         if (isset($field[0])) { // multiple $field names, just one $content string
            $uu = $field; $field = array();
            foreach ($uu as $f) {
               $field[$f] = $content;
            }
         }
         $r = new ewiki_dbquery_result(array($field));
         foreach ($field as $f=>$c) {
            $add = $ewiki_db->SEARCH($f, $c, $ci, $regex, $mask, $filter);
            $r->entries = array_merge($r->entries, $add->entries);
            unset($add);  // dispose, hopefully
         }
      }
      #-- single query
      else {
         $r = $ewiki_db->SEARCH($field, $content, $ci, $regex, $mask, $filter);
         ewiki_db::dbquery_result($r);
      }
      return($r);
   }

   
   #-- full page list
   # returns an dbquery_result object with __all__ pages, where each entry
   # is made up of at least the fields from the database requested with the
   # $fields array, e.g. array("flags","meta","lastmodified");
   #
   function GETALL($fields, $mask=0x0000, $filter=0x0000) {
      global $ewiki_db;
      $fields[] = "flags";
      $fields[] = "version";
      $fields = array_flip($fields);
      unset($fields["id"]);
      $fields = array_flip($fields);
      $r = $ewiki_db->GETALL($fields);
      ewiki_db::dbquery_result($r);
      return($r);
   }


   #-- check page existence
   # searches for all given page names (in $list) in the database and returns
   # an associative array with page names as keys and booleans as values;
   # (int)0 for missing pages, and for existing ones the associated value is
   # the page {flags} value or the {meta} data array (for binary entries)
   #
   function FIND($list) {
      global $ewiki_db;
      if (!count($list)) {
         return($list);
      }
      return $ewiki_db->FIND($list);
   }


   #-- page hits
   # increases the {hit} counter for the page given by $id
   #
   function HIT($id) {
      global $ewiki_db;
      return $ewiki_db->HIT($id);
   }


   #-- admin functions
   function DELETE($id, $version=false) {
      global $ewiki_db;
      if (!defined("EWIKI_DB_LOCK"))
        return $ewiki_db->DELETE($id, $version);
   }
   function INIT() {
      global $ewiki_db;
      return $ewiki_db->INIT();
   }
   
   
   #-- virtual features
   # ::CREATE() creates a new page hash (template)
   # ::UPDATE() renews meta data (except version) to allow ::WRITE()
   # ::APPEND() adds content to an existing text page
   #
   function CREATE($id, $flags=EWIKI_DB_F_TEXT, $author="") {
      $data = array(
         "id"=>$id, "version"=>1, "flags"=>$flags,
         "content"=>"", "meta"=>array(),
         "hits"=>0, "created"=>time(),
         "lastmodified"=>time(),
         "author"=>ewiki_author($author),
      );
      return($data);
   }
   function UPDATE(&$data, $author="") {
      global $ewiki_links;
      #-- regenerate backlinks entry
      ewiki_scan_wikiwords($data["content"], $ewiki_links, "_STRIP_EMAIL=1");
      $data["refs"] = "\n\n".implode("\n", array_keys($ewiki_links))."\n\n";
      #-- update meta info
      $data["lastmodified"] = time();
      $data["author"] = ewiki_author($author);
      $data["meta"]["user-agent"] = trim($_SERVER["HTTP_USER_AGENT"]);
   }
   function APPEND($id, $text, $textonly=1) {
      ($data = ewiki_db::GET($id))
      or ($data = ewiki_db::CREATE($id));
      if (!strlen(trim($text)) or $textonly && (($data["flags"]&EWIKI_DB_F_TYPE) != EWIKI_DB_F_TEXT)) {
         return;
      }
      $data["content"] .= $text;
      ewiki_db::UPDATE($data);
      $data["version"]++;
      return ewiki_db::WRITE($data);
   }


   #-- helper code
   function expand(&$r) {
      if (isset($r["meta"]) && is_string($r["meta"]) && strlen($r["meta"])) {
         $r["meta"] = unserialize($r["meta"]);
      }
   }
   function shrink(&$r) {
      if (isset($r["meta"]) && is_array($r["meta"])) {
         $r["meta"] = serialize($r["meta"]);
      }
   }
   function dbquery_result(&$r) {
      if (is_array($r)) {
         $z = new ewiki_dbquery_result(array_keys($args));
         foreach ($r as $id=>$row) {
            $z->add($row);
         }
         $r = $z;
      }
   }

} // end of class



#-- returned for SEARCH and GETALL queries, as those operations are
#   otherwise too memory exhaustive
class ewiki_dbquery_result {

   var $keys = array();
   var $entries = array();
   var $buffer = EWIKI_DBQUERY_BUFFER;
   var $size = 0;

   function ewiki_dbquery_result($keys) {
      $keys = array_merge((array)$keys, array(-50=>"id", "version", "flags"));
      $this->keys = array_unique($keys);
   }

   function add($row) {
      if (is_array($row)) {
         if ($this->buffer) {
            $this->size += strlen(serialize($row));
            $this->buffer = $this->size <= EWIKI_DBQUERY_BUFFER;
            ewiki_db::expand($row);
         }
         else {
            $row = $row["id"];
         }
      }
      $this->entries[] = $row;
   }

   function get($all=0, $flags=0x0000, $type=0) {
      $row = array();

      $prot_hide = ($flags&0x0020) && EWIKI_PROTECTED_MODE && EWIKI_PROTECTED_MODE_HIDING;
      $flag_hide = ($flags&0x0003);
      do {
         if (count($this->entries)) {

            #-- fetch very first entry from $entries list
            $r = array_shift($this->entries);

            #-- finish if buffered entry
            if (is_array($r) && !$all) {
               $row = $r;
            }
            #-- else refetch complete entry from database
            else {
               if (is_array($r)) {
                  $r = $r["id"];
               }
               $r = ewiki_db::GET($r);
               if (!$all) {
                  foreach ($this->keys as $key) {
                     $row[$key] = $r[$key];
                  }
               } else { 
                  $row = $r;
               }
            }
            unset($r);
         }
         else { 
            return(NULL);  // no more entries
         }

         #-- expand {meta} field
         if (is_array($row) && is_string(@$row["meta"])) {
            $row["meta"] = unserialize($row["meta"]);
         }

         #-- drop unwanted results
         if ($prot_hide && !ewiki_auth($row["id"], $row, 'view')
         || ($flag_hide && ($row["flags"] & (EWIKI_DB_F_HIDDEN|EWIKI_DB_F_DISABLED)))
         || ($type) && (($row["flags"] & EWIKI_DB_F_TYPE) != $type)) {
            $row = array();
         }
      } while (empty($row) && ($prot_hide || $flag_hide));

      return($row);
   }

   function count() {
      return(count($this->entries));
   }
}



#-- obsolete compatibility wrapper
function ewiki_database($action, $args, $sw1=0, $sw2=0, $pf=false) {
   switch ($action) {
      case "GET":
         return ewiki_db::GET($args["id"], @$args["version"]);
      case "WRITE":
         return ewiki_db::WRITE($args, 0);
      case "OVERWRITE":
         return ewiki_db::WRITE($args, 1);
      case "FIND":
         return ewiki_db::FIND($args);
      case "GETALL":
         return ewiki_db::GETALL($args);
      case "SEARCH":
         return ewiki_db::SEARCH(implode("",array_keys($args)), implode("",$args));
      case "HIT":
         return ewiki_db::HIT($args["id"]);
      case "DELETE":
         return ewiki_db::DELETE($args["id"], $args["version"]);
      case "INIT":
         return ewiki_db::INIT();
   }
   echo "error: unknown database call '$action'<br>\n";
   return false;
}



#-- MySQL database backend (default, but will be teared out soon)
#   Note: this is of course an abuse of the relational database scheme,
#   but necessary for real db independence and abstraction
class ewiki_database_mysql {

   function ewiki_database_mysql() {
      $this->table = EWIKI_DB_TABLE_NAME;
   }


   function GET($id, $version=false) {
      $id = mysql_escape_string($id);
      if ($version) {
         $version = "AND (version=$version)";
      } else  { 
         $version="";
      }
      $result = db_safe_query("SELECT *, pagename as id FROM {$this->table}
          WHERE (pagename='$id') $version  ORDER BY version DESC  LIMIT 1"
      );
      echo mysql_error();
      if ($result && ($r = mysql_fetch_array($result, MYSQL_ASSOC))) {
         unset($r["pagename"]);
         return($r);
      }
   }

   
   function HIT($id) {
      $id = mysql_escape_string($id);
      db_safe_query("UPDATE {$this->table} SET hits=(hits+1) WHERE pagename='$id'");
   }


   function WRITE($hash, $overwrite=0) {

      $COMMAND = $overwrite ? "REPLACE" : "INSERT";
      $sql1 = $sql2 = "";
      $hash["pagename"] = $hash["id"];
      unset($hash["id"]);
      foreach ($hash as $index=>$value) {
         if (is_int($index)) {
            continue;
         }
         $a = ($sql1 ? ', ' : '');
         $sql1 .= $a . $index;
         $sql2 .= $a . "'" . mysql_escape_string($value) . "'";
      }

      $result = db_safe_query("$COMMAND INTO {$this->table} ($sql1) VALUES ($sql2)");
      $result = ($result && mysql_affected_rows()) ?1:0;
      return($result);
   }


   function FIND($list) {
      $r = array();
      $sql = "";
      foreach (array_values($list) as $id) {
         if (strlen($id)) {
            $r[$id] = 0;
            $sql .= ($sql ? " OR " : "") .
                 "(pagename='" . mysql_escape_string($id) . "')";
         }
      }
      $result = db_safe_query("SELECT pagename AS id, meta, flags FROM {$this->table} WHERE $sql");
      if ($result) {
         while ($row = mysql_fetch_array($result)) {
            $id = $row["id"];
            if (strlen($row["meta"])) {
               $r[$id] = unserialize($row["meta"]);
               $r[$id]["flags"] = $row["flags"];
            } else {
               $r[$id] = $row["flags"];
            }
         }
      }
      return($r);
   }


   function GETALL($fields, $mask=0, $filter=0) {
      $fields = implode(", ", $fields);
      $f_sql = $mask ? "WHERE ((flags & $mask) = $filter)" : "";
      $result = db_safe_query("SELECT pagename AS id, $fields FROM
          {$this->table} $f_sql GROUP BY id, version DESC"
      );
      $r = new ewiki_dbquery_result($fields);
      $last = "";
      if ($result) while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $drop = EWIKI_CASE_INSENSITIVE ? strtolower($row["id"]) : $row["id"];
         if (($last != $drop) && ($last = $drop)) {
            $r->add($row);
         }
      }
      return($r);
   }


   function SEARCH($field, $content, $ci="i", $regex=0, $mask=0, $filter=0) {

      $sql_fields = ", $field";
      if ($field == "id") {
         $field = "pagename";
         $sql_fields = "";
      }
      $content = mysql_escape_string($content);
      if ($mask) {
         $sql_flags = "AND ((flags & $mask) = $filter)";
      }
      if ($regex) {
         $sql_strsearch = "($field REGEXP '$content')";
      }
      elseif ($ci) {
         $sql_strsearch = "LOCATE('".strtolower($content)."', LCASE($field))";
      }
      else {
         $sql_strsearch = "LOCATE('$content', $field)";
      }
      
      $result = db_safe_query(
       "SELECT pagename AS id, version, flags  $sql_fields
          FROM {$this->table}
         WHERE $sql_strsearch $sql_flags
         GROUP BY id, version DESC
      ");

      $r = new ewiki_dbquery_result(array("id","version",$field));
      $last = "";
      if ($result) while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $drop = EWIKI_CASE_INSENSITIVE ? strtolower($row["id"]) : $row["id"];
         if (($last != $drop) && ($last = $drop)) {
            $r->add($row);
         }
      }
      return($r);
   }


   function DELETE($id, $version) {
      $id = mysql_escape_string($id);
      db_safe_query("DELETE FROM {$this->table} WHERE pagename='$id' AND version=$version");
   }


   function INIT() {
      db_safe_query("CREATE TABLE {$this->table}
         (pagename VARCHAR(160) NOT NULL,
         version INTEGER UNSIGNED NOT NULL DEFAULT 0,
         flags INTEGER UNSIGNED DEFAULT 0,
         content MEDIUMTEXT,
         author VARCHAR(100) DEFAULT 'ewiki',
         created INTEGER UNSIGNED DEFAULT ".time().",
         lastmodified INTEGER UNSIGNED DEFAULT 0,
         refs MEDIUMTEXT,
         meta MEDIUMTEXT,
         hits INTEGER UNSIGNED DEFAULT 0,
         PRIMARY KEY id (pagename, version) )
      ");
      echo mysql_error();
   }


} // end of class ewiki_database_mysql





?><?php

 # This plugin protects email addresses from getting seen by spambots,
 # by the cost of additional effort for real persons, who really want
 # to mail someone.
 #
 # It is __really safe__ because it protects addresses with a request
 # <FORM> before the real email address gets shown on a page. It seems
 # impossible to me that there are already all that intelligent spambots
 # available, which can automatically fill out a <form> to access the
 # following page. Also bots are unlikely to ever perform POST requests,
 # because this would damage too many web interfaces.
 # The 'cipher' method is really unimportant, when it comes to tricking
 # automated harvesters.
 #
 # Additionally it generates faked/trap email addresses to annoy the
 # marketing mafia.
 

 #-- change these from time to time:
 define("EWIKI_PAGE_EMAIL", "ProtectedEmail");
 define("EWIKI_EMAILPROT_UNLOCK", 1);
 define("EWIKI_UP_ENCEMAIL", "encoded_email");
 define("EWIKI_UP_NOSPAMBOT", "i_am_no_spambot");
 define("EWIKI_UP_REQUESTLV", "rl");
 define("EWIKI_FAKE_EMAIL_LOOP", 8);
 $ewiki_config["feedbots_tarpits"] = "@spamassassin.taint.org,@123webhosting.org,@e.mailsiphon.com,@heypete.com,@ncifcrf.gov";
 $ewiki_config["feedbots_badguys"] = "@riaa.com,@whitehouse.gov,@aol.com,@microsoft.com";
 @$ewiki_config["ua"] .= " EmailProtect/2.1";

 #-- text, translations
 $ewiki_t["en"]["PROTE0"] = "Protected Email Address";
 $ewiki_t["en"]["PROTE1"] = "The email address you've clicked on is protected by this form, so it won't get found by <a href=\"http://google.com/search?q=spambots\">spambots</a> (automated search engines, which crawl the net for addresses just for the entertainment of the marketing mafia).";
 $ewiki_t["en"]["PROTE2"] = "The page you're going to edit contains at least one email address. To protect it we must ensure that no spambot reaches the edit box (with the email address in cleartext).";
 $ewiki_t["en"]["PROTE4"] = "I'm no spambot, really!";
 $ewiki_t["en"]["PROTE5"] = "<b>generate more faked email addresses</b>";
 $ewiki_t["en"]["PROTE6"] = "the email address you've clicked on is:";
 $ewiki_t["en"]["PROTE7"] = "<b>spammers, please eat these:</b>";

 $ewiki_t["de"]["PROTE0"] = "Geschtzte EMail-Adresse";
 $ewiki_t["de"]["PROTE1"] = "Die EMail-Adresse, die du angeklickt hast, wird durch dieses Formular vor <a href=\"http://google.com/search?q=spambots\">spambots</a> (automatisierte Suchwerkzeuge, die das Netz zur Freude der MarketingMafia nach Adressen abgrasen) beschtzt.";
 $ewiki_t["de"]["PROTE2"] = "Die Seite, die du �dern willst, enth�t momentan wenigstens eine EMail-Adresse. Um diese zu schtzen mssen wir sicherstellen, da�kein Spambot an die Edit-Box kommt (weil dort die Adresse ja im Klartext steht).";
 $ewiki_t["de"]["PROTE4"] = "Ich bin wirklich kein Spambot!";
 $ewiki_t["de"]["PROTE5"] = "<b>noch mehr fingierte Adressen anzeigen</b>";
 $ewiki_t["de"]["PROTE6"] = "die EMail-Adresse die du angeklickt hast lautet:";
 $ewiki_t["de"]["PROTE7"] = "<b>Liebe Spammer, bitte fre� das:</b>";

 #-- plugin glue
// $ewiki_plugins["page"][EWIKI_PAGE_EMAIL] = "ewiki_email_protect_form";
// if (!EWIKI_EMAILPROT_UNLOCK || !@$_COOKIE[EWIKI_UP_NOSPAMBOT]) {
//    $ewiki_plugins["link_url"][] = "ewiki_email_protect_link";
//    $ewiki_plugins["edit_hook"][] = "ewiki_email_protect_edit_hook";
//    $ewiki_plugins["page_final"][] = "ewiki_email_protect_enctext";
// }



 /* helps protecting addresses on generated/dynamic pages, and
    for action pages (diff, info, links, ...)    
 */
 function ewiki_email_protect_enctext(&$html, $id, $data, $action) {

    global $ewiki_config;

    #-- only activate if _feedbots() wasn't active, for info/ or
    #   diff/ action or for page plugins
    $a_secure = array("info", "diff");
    if (!isset($ewiki_config["@"]) && (in_array($action, $a_secure) || ewiki_array($GLOBALS["ewiki_plugins"]["page"], $id))) {

       $html = preg_replace('/(?:(?:<a[^>]+href="mailto:)?([-_+\w\d.]+@[-\w\d.]+\.[\w]{2,5})(?:"[^>]*>[-_+\w\d.@]+<\/a>)?)/me',
               '"<a href=\"".ewiki_email_protect_encode("\1",2).
                "\">".ewiki_email_protect_encode("\1",0)."</a>"',
               $html);
    }
 }


 /* ewiki_format() callback function to replace mailto: links with
  * encoded redirection URLs
  */
 function ewiki_email_protect_link(&$href, &$title) {

     if (substr($href, 0, 7) == "mailto:") {

         $href = substr($href, 7);

         $href = ewiki_email_protect_encode($href, 2);
         $title = ewiki_email_protect_encode($title, 0);
     }
 }



 /* the edit box for every page must be protected as well - else all
  * mail addresses would still show up in the wikimarkup (cleartext)
  */
 function ewiki_email_protect_edit_hook($id, &$data, &$hidden_postdata) {

    $hidden_postdata[EWIKI_UP_NOSPAMBOT] = 1;

    if (empty($_REQUEST[EWIKI_UP_NOSPAMBOT])
        && strpos($data["content"], "@")
        && preg_match('/\w\w@([-\w]+\.)+\w\w/', $data["content"])   )
    {
       $url = ewiki_script("edit", $id);
       $o = ewiki_email_protect_form($id, $data, "edit", "PROTE2", $url);
       return($o);
    }

    if (!empty($_POST[EWIKI_UP_NOSPAMBOT]) && empty($_COOKIE[EWIKI_UP_NOSPAMBOT]) && EWIKI_HTTP_HEADERS) {
    //   setcookie(EWIKI_UP_NOSPAMBOT, "grant_access", time()+7*24*3600, "/");
    }

 }



 /* this places a <FORM METHOD="POST"> in between the WikiPage with the
  * encoded mail address URL and the page with the clearly readable
  * mailto: string
  */
 function ewiki_email_protect_form($id, $data=0, $action=0, $text="PROTE1", $url="") {
    global $ewiki_config;

    $html = "<h3>" . ewiki_t("PROTE0") . "</h3>\n";

    #-- get encoded-email parameter
    if ($url || ($email = @$_REQUEST[EWIKI_UP_ENCEMAIL])) {

          #-- check for unlock cookie / post variable
          if (empty($_REQUEST[EWIKI_UP_NOSPAMBOT])) {

             if (empty($url)) {
                $url = ewiki_script("", EWIKI_PAGE_EMAIL);
             }

             $html .= ewiki_t($text) . "<br /><br /><br />\n";

             $html .= '<form action="' . $url .
                      '" method="POST" enctype="multipart/form-data" encoding="iso-8859-1">';
             $html .= '<input type="hidden" name="'.EWIKI_UP_ENCEMAIL.'" value="' . $email . '">';
             foreach (array_merge($_GET, $_POST) as $var=>$value) {
                if (($var != "id") && ($var != EWIKI_UP_ENCEMAIL) && ($var != EWIKI_UP_NOSPAMBOT)) {
                   $html .= '<input type="hidden" name="' . htmlentities($var) . '" value="' . htmlentities($value) . '">';
                }
             }
             $html .= '<input type="checkbox" name="'.EWIKI_UP_NOSPAMBOT.'" value="true" id="no_spambot_checkbox"><label for="no_spambot_checkbox"> ' . ewiki_t("PROTE4") . '</label><br /><br />';
             $html .= '<input type="submit" name="go"></form><br /><br />';

             if (EWIKI_FAKE_EMAIL_LOOP) {
                $html .= "\n" . ewiki_t("PROTE7") . "<br />\n";
                $html .= ewiki_email_protect_feedbots();
             }
          }

          #-- display deciphered email address
          else {
             $email = ewiki_email_protect_encode($email, -1);

             $html .= ewiki_t("PROTE6") . "<br />";
             $html .= '<a href="mailto:' . $email . '">' . $email . '</a>';

             if (EWIKI_HTTP_HEADERS && empty($_COOKIE[EWIKI_UP_NOSPAMBOT])) {
             //   setcookie(EWIKI_UP_NOSPAMBOT, "grant_access", time()+7*24*3600, "/");
             }

             #-- must disable the page plugin mangling filter manually
             $ewiki_config["@"] = 0;  // flag value doesn't matter here
          }
    }
    else {
       // $html .= "This page makes no sense standalone.";
       $html .= "No encoded email address given in parameters.";
    }

    return($html);
 }



 /* security really does not depend on how good "encoding" is, because
  * bots cannot automatically guess that one is actually used
  */
 function ewiki_email_protect_encode($string, $func) {

    switch ($func) {

       case 0:  // garbage shown email address
          if (strpos($string, "mailto:") === 0) {
             $string = substr($string, 7);
          }
          while (($rd = strrpos($string, ".")) > strpos($string, "@")) {
             $string = substr($string, 0, $rd);
          }
          $string = strtr($string, "@.-_", "");
          break;

       case 1:  // encode
          $string = str_rot17($string);
          $string = base64_encode($string);
          break;

       case -1:  // decode
          $string = base64_decode($string);
          $string = str_rot17($string);
          break;       

       case 2:  // url
          $string = ewiki_script("", EWIKI_PAGE_EMAIL,
             array(EWIKI_UP_ENCEMAIL => ewiki_email_protect_encode($string, 1))
          );
          break;

    }

    return($string);
 }



 /* this is a non-portable string encoding function which ensures that
  * encoded strings can only be decoded when requested by the same client
  * or user in the same dialup session (IP address must match)
  * feel free to exchange the random garbage string with anything else
  */
 function str_rot17($string) {
    if (!defined("STR_ROT17")) {
       $i = @$_SERVER["SERVER_SOFTWARE"] .
            @$_SERVER["HTTP_USER_AGENT"] .
            @$_SERVER["REMOTE_ADDR"];
       $i .= 'MxQXF^e-0OKC1\\s{\"?i!8PRoNnljHf65`Eb&A(\':g[D}_|S#~3hG>*9yvdI%<=.urcp/@$ZkqL,TWBw]a;72UzYJ)4mt+ V';
       $f = "";
       while (strlen($i)) {
          if (strpos($f, $i[0]) === false) {
             $f .= $i[0];
          }
          $i = substr($i, 1);
       }
       define("STR_ROT17", $f);
    }
    return(strtr($string, STR_ROT17, strrev(STR_ROT17)));
 }



 /* this function emits some html with random (fake) email addresses
  * and spambot traps
  */
 function ewiki_email_protect_feedbots($limit=EWIKI_FAKE_EMAIL_LOOP) {

    global $ewiki_config;
    
    $html = "";
    srand(time()/17-1000*microtime());

    #-- once _this_ function was called, all @s shall be send as-is
    $ewiki_config["@"] = 1;

    #-- spamtraps, and companys/orgs fighting for spammers rights
    $domains = explode(",",
       $ewiki_config["feedbots_tarpits"]. "," .$ewiki_config["feedbots_badguys"]
    );
    $traps = explode(" ", "blockme@relays.osirusoft.com simon.templar@rfc1149.net james.bond@ada-france.org anton.dvorak@ada.eu.org amandahannah44@hotmail.com usenet@fsck.me.uk meatcan2@beatrice.rutgers.edu heystupid@artsackett.com listme@dsbl.org bill@caradoc.org spamtrap@spambouncer.org spamtrap@woozle.org gfy@spamblocked.com listme@blacklist.woody.ch tarpit@lathi.net");
    $word_parts = explode(" ", "er an Ma ar on in el en le ll Ca ne ri De Mar Ha Br La Co St Ro ie Sh Mc re or Be li ra Al la al Da Ja il es te Le ha na Ka Ch is Ba nn ey nd He tt ch Ho Ke Ga Pa Wi Do st ma Mi Sa Me he to Car ro et ol ck ic Lo Mo ni ell Gr Bu Bo Ra ia de Jo El am An Re rt at Pe Li Je She Sch ea Sc it se Cha Har Sha Tr as ng rd rr Wa so Ki Ar Bra th Ta ta Wil be Cl ur ee ge ac ay au Fr ns son Ge us nt lo ti ss Cr os Hu We Cor Di ton Ri ke Ste Du No me Go Va Si man Bri ce Lu rn ad da ill Gi Th and rl ry Ros Sta sh To Se ett ley ou Ne ld Bar Ber lin ai Mac Dar Na ve no ul Fa ann Bur ow Ko rs ing Fe Ru Te Ni hi ki yn ly lle Ju Del Su mi Bl di lli Gu ine do Ve Gar ei Hi vi Gra Sto Ti Hol Vi ed ir oo em Bre Man ter Bi Van Bro Col id Fo Po Kr ard ber sa Con ick Cla Mu Bla Pr Ad So om io ho ris un her Wo Chr Her Kat Mil Tre Fra ig Mel od nc yl Ale Jer Mcc Lan lan si Dan Kar Mat Gre ue rg Fi Sp ari Str Mer San Cu rm Mon Win Bel Nor ut ah Pi gh av ci Don ot dr lt ger co Ben Lor Fl Jac Wal Ger tte mo Er ga ert tr ian Cro ff Ver Lin Gil Ken Che Jan nne arr va ers all Cal Cas Hil Han Dor Gl ag we Ed Em ran han Cle im arl wa ug ls ca Ric Par Kel Hen Nic len sk uc ina ste ab err Or Am Mor Fer Rob Luc ob Lar Bea ner pe lm ba ren lla der ec ric Ash Ant Fre rri Den Ham Mic Dem Is As Au che Leo nna rin enn Mal Jam Mad Mcg Wh Ab War Ol ler Whi Es All For ud ord Dea eb nk Woo tin ore art Dr tz Ly Pat Per Kri Min Bet rie Flo rne Joh nni Ce Ty Za ins eli ye rc eo ene ist ev Der Des Val And Can Shi ak Gal Cat Eli May Ea rk nge Fu Qu nie oc um ath oll bi ew Far ich Cra The Ran ani Dav Tra Sal Gri Mos Ang Ter mb Jay les Kir Tu hr oe Tri lia Fin mm aw dy cke itt ale wi eg est ier ze ru sc My lb har ka mer sti br ya Gen Hay a b c d e f g h i j k l m n o p q r s t u v w x y z");
    $word_delims = explode(" ", "0 1 2 3 3 3 4 5 5 6 7 8 9 - - - - - - - _ _ _ _ _ _ _ . . . . . . .");
    $n_dom = count($domains)-1;
    $n_trp = count($traps)-1;
    $n_wpt = count($word_parts)-1;
    $n_wdl = count($word_delims)-1;

    for ($n = 1; $n < $limit; $n++) {

       // email name part
       $m = "";
       while (strlen($m) < rand(3,17)) {
          $a = $word_parts[nat_rand($n_wpt)];
          if (!empty($m)) {
             $a = strtolower($a);
             if (rand(1,9)==5) {
                $m .= $word_delims[rand(0,$n_wdl)];
             }
          }
          $m .= $a;
       }

       // add domain
       switch ($dom = $domains[rand(0, $n_dom)]) {

          case "@123webhosting.org":
             $m = strtr($_SERVER["REMOTE_ADDR"], ".", "-")."-".$_SERVER["SERVER_NAME"]."-".time();
             break;

          default:
       }
       $m .= $dom;

       $html .= '<a href="mailto:'.$m.'">'.$m.'</a>'.",\n";
    }

    $html .= '<a href="mailto:'.$traps[rand(0, $n_trp)].'">'.$traps[rand(0, $n_trp)].'</a>';

    if (($rl = 1 + @$_REQUEST[EWIKI_UP_REQUESTLV]) < EWIKI_FAKE_EMAIL_LOOP) {
       $html .= ",\n" . '<br /><a href="' .
             ewiki_script("", EWIKI_PAGE_EMAIL,
               array(
                  EWIKI_UP_ENCEMAIL=>ewiki_email_protect_encode($m, 1),
                  EWIKI_UP_REQUESTLV=>"$rl"
               )
             ) . '">' . ewiki_t("PROTE5") . '</a><br />' . "\n";
       ($rl > 1) && sleep(3);
    }

    sleep(1);
    return($html);
 }



 function nat_rand($max, $dr=0.5) {
    $x = $max+1;
    while ($x > $max) {
       $x = rand(0, $max * 1000)/100;
       $x = $x * $dr + $x * $x / 2 * (1-$dr) / $max;
    }
    return((int)$x);
 }


?><?php

/*
   This plugin adds a page redirection feature. ewiki instantly switches
   to another page, when one of the following markup snippets is found:

      [jump:AnotherPage]
      [goto:SwitchToHere]
   or
      [jump:WardsWiki:WelcomeVisitors]
      [jump:Google:ErfurtWiki:MarioSalzer]
      [jump:http://www.heise.de/]

   One can also use [redirect:] or [location:]. Page switching only occours
   with the "view" action. Sending a HTTP redirect is the default, but in
   place redirects are also possible.
   There exists a loop protection, which limits redirects to 5 (for browsers
   that cannot detect this themselfes).
*/

#-- config 
define("EWIKI_JUMP_HTTP", 1);       #-- issue a HTTP redirect, or jump in place
define("EWIKI_UP_REDIRECT_COUNT", "redir");

#-- text
$ewiki_t["en"]["REDIRECTION_LOOP"] = "<h2>Redirection loop detected<h2>\nOperation stopped, because we're traped in an infinite redirection loop with page \$id.";

#-- plugin glue 
$ewiki_plugins["handler"][] = "ewiki_handler_jump";
$ewiki_config["interwiki"]["jump"] = "";
$ewiki_config["interwiki"]["goto"] = "";


function ewiki_handler_jump(&$id, &$data, &$action) {

   global $ewiki_config;

   static $redirect_count = 5;
   $jump_markup = array("jump", "goto", "redirect", "location");

   #-- we only care about "view" action
   if ($action != "view") {
      return;
   }

   #-- escape from loop
   if (isset($_REQUEST[EWIKI_UP_REDIRECT_COUNT])) {
      $redirect_count = $_REQUEST[EWIKI_UP_REDIRECT_COUNT];
   }
   if ($redirect_count-- <= 0) {
      return(ewiki_t("REDIRECTION_LOOP", array("id"=>$id)));
   }

   #-- search for [jump:...]
   if ($links = explode("\n", trim($data["refs"])))
   foreach ($links as $link) {

      if (strlen($link) && strpos($link, ":")
      && in_array(strtolower(strtok($link, ":")), $jump_markup)
      && ($dest = trim(strtok("\n"))) )
      {
         #-- URL
         $url = "";
         if (strpos($dest, "://")) {
            $url = $dest;
         }
         #-- InterWiki:Link
         else {
            $url = ewiki_interwiki($dest, $uu, $uu2);
         }

         #-- Location:
         if (EWIKI_JUMP_HTTP && EWIKI_HTTP_HEADERS && !headers_sent()) {

            #-- simple PageLink
            if (empty($url)) {
               $url = ewiki_script("", $dest,
                  array(EWIKI_UP_REDIRECT_COUNT=>$redirect_count),
                  0, 0, ewiki_script_url()
               );
               $url .= defined("SID") ? EWIKI_ADDPARAMDELIM.SID : "";
            }
            header("Location: $url");
            die();

         }
         #-- show page as usual, what will reveal dest URL
         elseif ($url) {
            return("");
            # the rendering kernel will just show up the [jump:]!
            # (without the jump: of course)
         }
         #-- it's simply about another WikiPage
         else {

            #-- we'll just restart ewiki
            $data = array();
            $id = $dest;
            return(ewiki_page("view/".$id));
         }
      }
   }#-search
}


?><?php
#
#  The otherwise invisible markup [notify:you@there.net] will trigger a
#  mail, whenever a page is changed. The TLD decides in which language
#  the message will be delivered. One can also append the lang code after
#  a comma or semicolon behind the mail address to set it explicitely:
#  [notify:me@here.org,de] or [notify:you@there.net;eo]
#
#  Nevertheless English will be used as the default automagically, if
#  nothing else was specified, no need to worry about this.
#
#  additional features:
#   * diff inclusion
#   * any mail address (works without notify: prefix) on the "GlobalNotify"
#     page will receive a notification for any changed page
#   * [notify:icq:123456789] - suddenly ICQ.com took the pager service down
#
#  To include a diff, just set the following constant. Also use it to
#  define the minimum number of changed bytes that are necessary to
#  result in a notification mail. Only use it with Linux/UNIX.

define("EWIKI_NOTIFY_WITH_DIFF", 0);       #-- set it to 100 or so
define("EWIKI_NOTIFY_SENDER",'ewiki');
define("EWIKI_NOTIFY_GLOBAL", "GlobalNotify");
define("EWIKI_NOTIFY_DIFF_EXE", "diff");
define("EWIKI_NOTIFY_DIFF_PARAMS", " --ignore-case --ignore-space-change");

#-- glue
$ewiki_plugins["edit_hook"][] = "ewiki_notify_edit_hook";
$ewiki_plugins["format_source"][] = "ewiki_format_remove_notify";
$ewiki_config["interwiki"]["notify"] = "mailto:";


#-- email message text ---------------------------------------------------
$ewiki_t["en"]["NOTIFY_SUBJECT"] = '"$id" was changed [notify:...]';
$ewiki_t["en"]["NOTIFY_BODY"] = <<<_END_OF_STRING
Hi,

A WikiPage has changed and you requested to be notified when this
happens. The changed page was '\$id' and can be found
at the following URL:
\$link

To see just the changes in the new version, you can click here:
\$diff_link

To stop messages like this please strip the [notify:...] with your address
from the page edit box at \$edit_link

(\$wiki_title on http://\$server/)
\$server_admin
_END_OF_STRING;


#-- translation.de
$ewiki_t["de"]["NOTIFY_SUBJECT"] = '"$id" wurde ge�dert [notify:...]';
$ewiki_t["de"]["NOTIFY_BODY"] = <<<_END_OF_STRING
Hi,

Eine WikiSeite hat sich ge�dert, und du wolltest ja unbedingt wissen,
wenn das passiert. Die ge�derte Seite war '\$id' und
ist leicht zu finden unter folgender URL:
\$link

Wenn du diese Benachrichtigungen nicht mehr bekommen willst, solltest du
deine [notify:...]-Adresse aus der entsprechenden Edit-Box herausl�chen:
\$edit_link

(\$wiki_title auf http://\$server/)
\$server_admin
_END_OF_STRING;


#----------------------------------------------------------------------------



#-- implementatition
function ewiki_notify_edit_hook($id, $data, &$hidden_postdata) {

   global $ewiki_t, $ewiki_plugins;
   $ret_err = 0;

   if (!isset($_REQUEST["save"])) {
      return(false);
   }

   #-- list from current page
   $mailto = ewiki_notify_links($data["content"], 0);

   #-- add entries from GlobalNotify page/list
   if ($add = ewiki_db::GET(EWIKI_NOTIFY_GLOBAL)) {
      ewiki_scan_wikiwords($add["content"], $uu, $strip_emails=false);
      foreach ($uu as $add=>$stat) {
         if (strpos($add, "@") && strpos($add, ".")) {
            $mailto[] = str_replace("notify:", "", $add);
         }
      }
   }

   if (!count($mailto)) {
      return(false); 
   }

   #-- generate diff
   $diff = "";
   if (EWIKI_NOTIFY_WITH_DIFF && (DIRECTORY_SEPARATOR=="/")) {

      #-- save page versions temporarily as files
      $fn1 = EWIKI_TMP."/ewiki.tmp.notify.diff.".md5($data["content"]);
      $fn2 = EWIKI_TMP."/ewiki.tmp.notify.diff.".md5($_REQUEST["content"]);
      $f = fopen($fn1, "w");
      fwrite($f, $data["content"]);
      fclose($f);
      $f = fopen($fn2, "w");
      fwrite($f, $_REQUEST["content"]);
      fclose($f);
      #-- set mtime of the old one (GNU diff will report it)
      touch($fn1, $data["lastmodified"]);

      #-- get diff output, rm temp files
      $diff_exe    = EWIKI_NOTIFY_DIFF_EXE;
      $diff_params = EWIKI_NOTIFY_DIFF_PARAMS;
      if ($f = popen("$diff_exe  $diff_params  $fn1 $fn2   2>&1 ", "r")) {

         $diff .= fread($f, 16<<10);
         pclose($f);

         $diff_failed = !strlen($diff)
                     || (strpos($diff, "Files ") === 0);

         #-- do not [notify:] if changes were minimal
         if ((!$diff_failed) && (strlen($diff) < EWIKI_NOTIFY_WITH_DIFF)) {
#echo("WikiNotice: no notify, because too few changes (" .strlen($diff)." byte)\n");
            $ret_err = 1;
         }

         $diff = "\n\n-----------------------------------------------------------------------------\n\n"
               . $diff;
      }
      else {
         $diff = "";
#echo("WikiWarning: diff failed in notify module\n");
      }

      unlink($fn1);
      unlink($fn2);

      if ($ret_err) {
         return(false);
      }
   }

   #-- separate addresses into (TLD) groups
   $mailto_lang = array(
   );
   foreach ($mailto as $m) {

      $lang = "";

      #-- remove lang selection trailer
      $m = strtok($m, ",");
      if ($uu = strtok(",")) {
         $lang = $uu;
      }
      $m = strtok($m, ";");
      if ($uu = strtok(";")) {
         $lang = $uu;
      }

      #-- else use TLD as language code
      if (empty($lang)) {
         $r = strrpos($m, ".");
         $lang = substr($m, $r+1);
      }
      $lang = trim($lang);

      #-- address mangling
      $m = trim($m);
      if (substr($m, 0, 4) == "icq:") {
         $m = substr($m, 4) . "@pager.icq.com";
      }

      $mailto_lang[$lang][] = $m;
   }

   #-- go thru email address groups
   foreach ($mailto_lang as $lang=>$a_mailto) {

      $pref_langs = array(
         "$lang", "en"
      ) + (array)$ewiki_t["languages"];

      ($server = $_SERVER["HTTP_HOST"]) or
      ($server = $_SERVER["SERVER_NAME"]);
      $s_4 = "http".($_SERVER['HTTPS'] == "on" ? 's':'')."://" . $server . $_SERVER["REQUEST_URI"];
      $link = str_replace("edit/$id", "$id", $s_4);
      $difflink = str_replace("edit/$id", "diff/$id", $s_4);

      $m_text = ewiki_t("NOTIFY_BODY", array(
         "id" => $id,
         "link" => $link,
         "diff_link" => $difflink,
         "edit_link" => $s_4,
         "server_admin" => $_SERVER["SERVER_ADMIN"],
         "server" => $server,
         "wiki_title" => EWIKI_PAGE_INDEX,
      ), $pref_langs);
      $m_text .= $diff;

      $m_from = EWIKI_NOTIFY_SENDER."@$server";
      $m_subject = ewiki_t("NOTIFY_SUBJECT", array(
         "id" => $id,
      ), $pref_langs);

      $m_to = implode(", ", $a_mailto);

      mail($m_to, $m_subject, $m_text, "From: \"$s_2\" <$m_from>\nX-Mailer: ErfurtWiki/".EWIKI_VERSION);

   }
}



function ewiki_notify_links(&$source, $strip=1) {
   $links = array();
   $l = 0;
   if (strlen($source) > 10)
   while (($l = @strpos($source, "[notify:", $l)) !== false) {
      $r = strpos($source, "]", $l);
      $n = strpos($source, "\n", $l);
      if ($r && (!$n || ($r<$n))) {
         $str = substr($source, $l, $r + 1 - $l);
         if (!strpos("\n", $str)) {
            $links[] = trim(substr($str, 8, -1));
            if ($strip) {
               $source = substr($source, 0, $l) . substr($source, $r + 1);
            }
         }
      }
      $l++;
   }
   return($links);
}



function ewiki_format_remove_notify(&$source) {
   ewiki_notify_links($source, 1);
}



?><?php

/*
   The StaticPages plugin allows you to put some .html or .php files
   into dedicated directories, which then will get available with their
   basename as ewiki pages. The files can be in wiki format (.txt or no
   extension), they can also be in .html format and they may even contain
   php code (.php). Some binary files may also be thrown into there, but
   you should use PathInfo or a ModRewrite setup then.

   Of course it is not possible to provide anything else, than viewing
   those pages (editing is not possible), but it is of course up to you
   to add php code to achieve some interactivity.
   The idea for this plugin was 'borought' from http://geeklog.org/.

   In your static page .php files you cannot do everything you could
   normally do, there are some restrictions because of the way these static
   pages are processed. You need to use $GLOBALS to access variables other
   than the $ewiki_ ones. To return headers() you must append them to the
   $headers[] or $ewiki_headers[] array.

   If you define("EWIKI_SPAGES_DIR") then this directory will be read
   initially, but you could also just edit the following list/array of 
   directories, or call ewiki_init_spages() yourself.
*/


#-- specify which dirs to search for page files
ewiki_init_spages(
   array(
      "./guide/spages",
      # "/usr/local/share/wikipages",
      # "C:/Documents/StaticPages/",
   )
);
if (defined("EWIKI_SPAGES_DIR")) {
   ewiki_init_spages(EWIKI_SPAGES_DIR);
}
define("EWIKI_SPAGES_BIN", 1);


#-- plugin glue
# - will be added automatically by _init_spages()


#-- return page
function ewiki_spage($id, &$data, $action) {

   global $ewiki_spages, $ewiki_plugins, $ewiki_t;

   $r = "";

   #-- filename from $id
   $fn = $ewiki_spages[strtolower($id)];

   #-- php file
   if (strpos($fn, ".php") || strpos($fn, ".htm")) {

      #-- start new ob level
      ob_start();
      ob_implicit_flush(0);

      #-- prepare environment
      global $ewiki_id, $ewiki_title, $ewiki_author, $ewiki_ring,
             $ewiki_t, $ewiki_config, $ewiki_action, $_EWIKI,
             $ewiki_auth_user, $ewiki_headers, $headers;
      $ewiki_headers = array();
      $headers = &$ewiki_headers;

      #-- execute script
      include($fn);

      #-- close ob
      $r = ob_get_contents();
      ob_end_clean();

      #-- add headers
      if ($ewiki_headers) {
         headers(implode("\n", $ewiki_headers));
      }
      $clean_html = true;
   }

   #-- plain binary file
   elseif (EWIKI_SPAGES_BIN && !headers_sent() && preg_match('#\.(png|gif|jpe?g|zip|tar)#', $fn)) {
      $ct = "application/octet-stream";
      if (function_exists("mime_content_type")) {
         $ct = mime_content_type($fn);
      }
      header("Content-Type: $ct");
      header("ETag: ewiki:spages:".md5($r).":0");
      header("Last-Modified: " . gmstrftime($ewiki_t["C"]["DATE"], filemtime($fn)));
      passthru($r);
   }

   #-- wiki file
   else {
      $f = gzopen($fn, "rb");
      $r = @gzread($f, 256<<10);
      gzclose($f);

      #-- render as text/plain, text/x-wiki
      if ($r) {
         $r = $ewiki_plugins["render"][0]($r);
      }
   }

   #-- strip <html> and <head> parts (if any)
   if ($clean_html) {
      $r = preg_replace('#^.+<body[^>]*>(.+)</body>.+$#is', '$1', $r);
   }

   #-- return body (means successfully handled)
   return($r);
}



#-- init
function ewiki_init_spages($dirs, $idprep="") {

   global $ewiki_spages, $ewiki_plugins;

   if (!is_array($dirs)) {
      $dirs = array($dirs);
   }

   #-- go through list of directories
   foreach ($dirs as $dir) {

      if (empty($dir)) {
         continue;
      }

      #-- read in one directory
      $dh = opendir($dir);
      while ($fn = readdir($dh)) {

         #-- skip over . and ..
         if ($fn[0] == ".") { continue; }

         #-- be recursive
         if ($fn && is_dir("$dir/$fn")) {
            if ($fn != trim($fn, ".")) {
               $fnadd = trim($fn, ".") . ".";
            }
            else {
               $fnadd = "$fn/";
            }

            ewiki_init_spages(array("$dir/$fn"), "$idprep$fnadd");

            continue;
         }

         #-- strip filename extensions
         $id = str_replace(
                  array(".html", ".htm", ".php", ".txt", ".wiki", ".src"),
                  "",
                  basename($fn)
         );

         #-- register spage file and as page plugin (one for every spage)
         $ewiki_spages[strtolower("$idprep$id")] = "$dir/$fn";
         $ewiki_plugins["page"]["$idprep$id"] = "ewiki_spage";

      }
      closedir($dh);
   }

}



?><?php

/*
   This plugin provides translation of the currently shown page via the
   Google, Altavista/Babelfish or InterTran online services. The services
   support different source and destination languages. The bottleneck of
   this plugin however is the detection of the language, the current page is
   in (only detectes English, Spanish, French, German and Italian, Portuguese
   very poorly). So you shouldn't forget to correct EWIKI_DEFAULT_LANG for
   your Wiki setup.
*/


#-- order of this array marks the preference
$language_service = array(
   "google" => array("http://translate.google.com/translate_p?hl=en&amp;ie=ISO-8859-1&amp;prev=/language_tools", "langpair", "u"),
   "babelfish" => array("http://babelfish.altavista.com/babelfish/urlload?tt=url", "lp", "url"),
   "intertran" => array("http://intertran.tranexp.com.com/Translate/index.shtml&amp;type=url", array("from", "to"), "url"),
);
$language_detect = array(
   "en" => "the to of is and this for be if you with an or it on not as that by will are see also can from use only file your at note may no which all false when about has have new one true any else more should without into first these own but must there do other",
   ".en" => "ing ght y",
   "de" => "die der und mit das sie den ist wird von eine im ein zu des auf dem werden oder aus wie datei es als nicht nen bei auch wenn einer einen an sind zur sich einem da�dass diese um zum nur gibt eines kann nach beim dann also dieser durch haben hat dabei alle jedoch noch aber dazu er diesem soll keine",
   ".de" => "en ag",
   "*de" =>  "isch cht wert ung",
   "es" => "de en la el para que un una no los con por es se del como su las puede al p�ina si ud texto ser sobre lo gina ginas esta entre m� debe hay dos son este sus est nueva esto desde versi sin tiene pero dise cual uno sea nuevo mismo nea usar caso as pues ha eso all mucho bien dar estas estar tema tan desee an nz os hace otros ya muy uno tulo nuevos deber poco le esa tal menos cap buena qu muchos hacer trav modo vaya tres lea nada vez vea ar met ve mi il t as�est�ese deja bajo sino muchas an qui est�eso tema",
   "fr" => "de la les le des pour est et l un en vous une sur dans du que par il qui es pas avec ne ce ou tre sont plus au qu me comme donn acc on pr cette peut tout si mais re se votre cela bien je sans autres aux vos cr ces ont fa fait puis cas avez ment ils temps autre sous mes fen donc ainsi tous alors apr te doit son chez deux tant res leur devez soit tat moins sa nom non ci faut peu avant avoir elle ses mot tes vue ceci elles bas ois ma er tait voulez ceux ex quoi",
   "*fr" => "i",
   "it" => "gli di nel ed per i della sono dell e con pi",
   ".it" => "zion zione zioni zie zia ierie i",
   "pt" => "do seu seus",
   ".pt" => "i",
);
$language_comb = array(
   "google" => array(
      "en|es", "en|fr", "en|de", "en|it", "en|pt",
      "es|en", "fr|en", "de|en", "it|en", "pt|en",
      "fr|de", "de|fr",
   ),
   "babelfish" => array(
      "en|zh", "en|fr", "en|de", "en|it", "en|ja", "en|ko", "en|pt", "en|es",
      "zh|en", "fr|en", "de|en", "it|en", "ja|en", "ko|en", "pt|en", "es|en",
      "ru|en", "fr|de", "de|fr",
   ),
   "intertran" => array(
      "*|*",
   ),
);
$language_names = array(  // for intertran
   "ch" => "chi",   "en" => "eng",   "fr" => "fre",   "it" => "ita",
   "jp" => "jpn",   "nl" => "dut",   "po" => "pol",   "pt" => "poe",
   "ru" => "rus",   "de" => "ger",   "ed" => "spa",   "lt" => "ltt",
);
$ewiki_t["C"]["en"] = "English";
$ewiki_t["C"]["de"] = "German";
$ewiki_t["C"]["fr"] = "French";
$ewiki_t["C"]["es"] = "Spanish";
$ewiki_t["C"]["it"] = "Italian";
$ewiki_t["C"]["pt"] = "Portuguese";



$ewiki_plugins["handler"][] = "ewiki_add_action_link_for_translation";



function ewiki_add_action_link_for_translation($id, &$data, $action) {

   global $language_comb, $language_service, $language_names, $ewiki_t,
          $ewiki_config;

   $o = "";
   $url = "";

   if ($data["version"]) {
      $lang_src = ewiki_guess_lang($data["content"]);

      #-- check if page is already in desired language
      if ($ewiki_t["languages"][0] == $lang_src) {
      }
      else {

         foreach ($ewiki_t["languages"] as $lang_dest) {

            $url = "";
            $comb = "$lang_src|$lang_dest";

            foreach ($language_service as $SERVICE=>$params) {

               if (in_array($comb, $language_comb[$SERVICE]) || ($SERVICE=="intertran")) {
                  if ($SERVICE == "babelfish") {
                     $lp = "&amp;" . $params[1] . "=" . strtr($comb, "|", "_");
                  }
                  elseif ($SERVICE == "google") {
                     $lp = "&amp;" . $params[1] . "=" . $comb;
                  }
                  else {
                     $from = $language_names[strtok($comb, "|")];
                     $to = $language_names[strtok("|")];
                     if (!$from || !$to) { 
                        continue;
                     }
                     $lp = "&amp;" . $params[1][0] . "=" . $from
                         . "&amp;" . $params[1][1] . "=" . $to;
                  }
                  $url = $params[0] . $lp
                       . "&amp;" . $params[2] . "="
                       . urlencode( ewiki_script($action, $id, "", 0, 0,
                                                 ewiki_script_url())     );
                  break;
               }
            }
         }
      }
   }
/*---
   return($o);
---*/
}



function ewiki_guess_lang(&$data) {

   global $language_detect;

   #-- prepare
   $detect = array(
      "en"=>0,
      "de"=>0,
   );

   #-- separate words in text page
   $text = strtr(
      "  $data ",
      "\t\n\r\f_<>\$,.;!()[]{}/",
      "                        "
   );

   #-- search for words in text
   foreach ($language_detect as $lang => $word_str) {
      foreach (explode(" ", $word_str) as $word) {
         switch ($lang[0]) {
            case ".":
               $word = "$word ";
            case "*":
               $lang = substr($lang, 1);
               break;
            default:
               $word = " $word ";
         }
         $l = -1;
         while ($l = strpos($text, $word, $l+1)) {
            $detect[$lang]++;
         }
      }
   }

   #-- get entry with most counts
   $lang = EWIKI_DEFAULT_LANG;
   arsort($detect);
   $keys = array_keys($detect);
   if (array_shift($detect) >= 5) {
      $lang = array_shift($keys);
   }

   return($lang);
}



?><?php

/*
  allows to diff freely chooseable page versions
*/


#-- glue


#-- impl
function ewiki_action_verdiff($id, $data, $action) {

   global $ewiki_plugins, $ewiki_diff_versions;

   if (($v0 = (int)$_REQUEST["from"]) && ($v1 = (int)$_REQUEST["to"])) {

      $ewiki_diff_versions = array($v0, $v1);

      return($ewiki_plugins["action"]["diff"]($id, $data, $action));

   }
   else {

      $o = ewiki_make_title($id, "$id version differences");

      $o .= '<form action="' . ewiki_script($action, $id) . '" method="GET">';
      $o .= '<input type="submit" value="diff">';
      $o .= '<input type="hidden" name="id" value="'.$action."/".htmlentities($id).'">';

      $o .= "\n".'<table border="1" class="diff"><tr>'
         .  "<th>from</th> <th>to</th> <th>version</th> <th>mtime</th> "
         .  "<th>size</th> <th>author</th></tr>\n";

      for ($n=$data["version"]; $n>=1; $n--) {

         $data = ewiki_db::GET($id, $n);
         if (EWIKI_DB_F_TEXT == ($data["flags"] & EWIKI_DB_F_TYPE)) {

            $o .= "<tr>"
               .  '<td><input type="radio" name="from" value="'.$n.'"></td>'
               .  '<td><input type="radio" name="to" value="'.$n.'"></td>'
               .  "<td>#$n</td>"
               .  "<td>".strftime("%Y-%m-%d %H:%M",$data["last_modified"])."</td>"
               .  "<td>".strlen($data["content"])."</td>"
               .  "<td>".$data["author"]."</td>"
               .  "</tr>\n";

         }
      }

      $o .= "</table>\n";
      $o .= "</form>\n";

   }

   return($o);
}


?><?php

# this plugin is only utilized by WordIndex and PageIndex, but is
# in fact a ["list_pages"] plugin and could be used with others too
#
# links of the list are grouped into <table> blocks with the first
# letter as block title


$ewiki_plugins["list_dict"][0] = "ewiki_fancy_list_dict";
           // ["list_pages"][0] = ...



function ewiki_fancy_list_dict($links) {


   $o .= '<table border="0" cellpadding="3" cellspacing="2">' . "\n";

   $lfl = false;

   foreach ($links as $line) {

      $nfl = strtoupper(substr($line, strpos($line, ">") + 1));
      $nfl = strtr($nfl, "��0123456789", "AOUS          ");
      while ((($nfl[0] < "A") || ($nfl[0] > "Z")) && ($nfl[0] != " ")) {
         $nfl = substr($nfl, 1);
      }
      $nfl = $nfl[0];

      if ($lfl != $nfl) {

         if ($lfl) {
            $o .= "</td></tr>\n";
         }

         $o .= '<tr><td valign="top" align="center" width="22" bgcolor="#333333" color="#eeeeee" class="darker reverse"><h2>' .
               ($lfl = $nfl) . '</h2></td>' .
               '<td valign="top">';
      }
      else {
         $o .= "<br />";
      }

      $o .= $line ;

   }

   $o .= "</td></tr>\n</table>\n";

   return($o);
}


?><?php

 # this plugin prints out <br /> - separated lists
 # Carsten Senf <ewiki@csenf.de>


 $ewiki_plugins["list_pages"][] = "ewiki_list_pages_fancy3";


 function ewiki_list_pages_fancy3($lines) {

    return join("<br />", $lines);
 }


?><?php

/*

  This authentication plugin maps wiki actions and/or page names to the ring
  level model:
  ring 0 is for admin functionality (superuser)
  ring 1 for advanced / privileged functions (moderators)
  ring 2 are all standard/default things (editors)
  ring 3 only allows access to a small subset of the wiki (browsing only)
    
*/


$ewiki_perm_rings = array_merge(
   array(
	"view"		=> 3,
	"info"		=> 3,
	"links"		=> 3,
	"edit"		=> 2,
	"calendar"	=> 2,
	"upload"	=> 2,
	"view/SecretPage" => 1,
	"delete"	=> 1,
	"control"	=> 0,
	"admin"		=> 0,
	"*"		=> 2,	#- anything else requires this ring level
   ),
   (array)@$ewiki_perm_rings
);



$ewiki_plugins["auth_perm"][0] = "ewiki_auth_handler_ring_permissions";


function ewiki_auth_handler_ring_permissions($id, $data, $action, $required_ring) {

   global $ewiki_plugins, $ewiki_ring, $ewiki_perm_rings;

   if ("ALWAYS_DO_THIS" || ($required_ring===false)) {

      $id = strtolower($id);
      $action = strtolower($action);

      foreach ($ewiki_perm_rings as $string => $ring) {

         $string = strtolower($string);

         if (($string == "*") ||
             ($string == $id) ||
             ($string == $action) ||
             ($string == "$action/$id") ||
             (strtok($string, "/") == $action)  )
         {
            $required_ring = $ring;
            break;
         }
 
     }

   }

   return(($required_ring===false) || isset($ewiki_ring) && ($ewiki_ring <= $required_ring));
}


?><?php

#
#  this plugin prints the "pages linking to" below a page (the same
#  information the "links/" action does)
#
# altered to use ewiki_get_backlinks() by AndyFundinger.

$ewiki_plugins["view_append"][] = "ewiki_view_append_backlinks";

function ewiki_view_append_backlinks($id, $data, $action) {
    $pages = ewiki_get_backlinks($id);
    
    $o="";
    foreach ($pages as $id) {
        $o .= ' <a href="'.ewiki_script("",$id).'">'.$id.'</a>';
    }
    ($o) && ($o = "<div class=\"wiki_backlinks\"><small>Backlinks:</small><br />$o</div>\n");
    
    return($o);
}


?><?php

/*
   Adds a CSS container with links to all listed headlines of the
   current page (but threshold for its activation is 3).

    .wiki .page-toc {
       width: 160px;
       float: left;
       border: 2px #333333 solid;
       background: #777777;
    }

   Modified 20040810 by Jochen
   - makes now use of EWIKI_TOC_CAPTION to print "TOC" above it all
   - indention swapped (biggest headlines are now left,
     and smaller ones are indented to the right)
   - added some \n for more readable html
*/


#-- reg
$ewiki_plugins["format_source"][] = "ewiki_toc_format_source";
$ewiki_plugins["format_final"][] = "ewiki_toc_view_prepend";
define("EWIKI_TOC_CAPTION", 0);
$ewiki_t["en"]["toc"] = "<b>Content</b>";
$ewiki_t["de"]["toc"] = "Inhalt";


#-- wiki page source rewriting
function ewiki_toc_format_source(&$src) {

   $toc = array();

   $src = explode("\n", $src);
   foreach ($src as $i=>$line) {

      if ($line[0] == "!") {
         $n = strspn($line, "!");
//         if (($n <= 3) and ($line[$n]==" ")) {
         if (($n <= 3)) {

            $text = substr($line, $n);
		$text = str_replace('&amp;', '&', $text);
		$text = str_replace('&lt;', '<', $text);
		$text = str_replace('&gt;', '>', $text);
		$text = str_replace('&quot;', '"', $text);
		$text = str_replace('&#039;', "'", $text);
		$text = str_replace('<htm>', '', $text);
		$text = str_replace('</htm>', '', $text);
		$text = str_replace('<h3>', '', $text);
		$text = str_replace('</h3>', '', $text);
            $toc[$i] = str_repeat("&nbsp;", 5*(3-$n)) . " "
                     . '<a href="#line'.$i.'">'
                     . trim($text) . "</a>";

            $src[$i] = str_repeat("!", $n) . $text . " [#line$i]";

         }
      }
   }
   $src = implode("\n", $src);

   $GLOBALS["ewiki_page_toc"] = &$toc;
}


#-- injects toc above page
function ewiki_toc_view_prepend(&$html) {

   global $ewiki_page_toc;

   if (count($ewiki_page_toc) >= 3) {

	$html = "<table class='toc' border='0' cellpadding='3' cellspacing='1'><tr><td style='text-align:center'>"
         . ( EWIKI_TOC_CAPTION ? ewiki_t("toc") : '')
	 . "</td></tr><tr><th style='text-align: left'>"
         . implode("<br />\n", $ewiki_page_toc)
	 . "</th></tr></table>"
	 . $html;
   }

   // $ewiki_page_toc = NULL;
}


?><?php

#  This is a replacement for the ewiki.php internal MySQL database access
#  interface; this one saves all WikiPages in so called "flat files", and
#  there are now two different formats you can choose from:
#    * rfc822-style (or say message/http like),
#      which leads to files you can edit with any available text editor
#    * in a compressed and faster 'binary' format,
#      which supports more functionality (hit counting)
#      enable with EWIKI_DB_FAST_FILES set to 1
#  As this plugin can read both, you are free to switch at any time.
#
#  To enable it, just include() this plugin __before__ the main/core
#  ewiki.php script using:
#
#       include("plugins/db/flat_files.php");
#
#  If you only will use the file database, you could go to the bottom of the
#  "ewiki.php" script and replace the 'ewiki_database_mysql' class with the
#  one defined herein. Then make also sure, that the initialization code knows
#  about it (there is a class name reference in $ewiki_plugins["database"]).
#
#  db_flat_files
#  -------------
#  The config option EWIKI_DBFILES_DIRECTORY must point to a directory
#  allowing write access for www-data (the user id, under which webservers
#  run usually), use 'chmod 757 dirname/' (from ftp or shell) to achieve this
#
#  db_fast_files
#  -------------
#  Some versions of PHP and zlib do not work correctly under Win32, so
#  you should disable it either in the php.ini, or via .htaccess:
#    php_option disable_functions "gzopen gzread gzwrite gzseek gzclose"
#  You need the plugins/db/fakezlib.php script for very old PHP versions.
#
#  db_fast_files` code was contributed_by("Carsten Senf <ewiki@csenf.de>");


#-- choose flat file format
define("EWIKI_DB_FAST_FILES", 1);
define("EWIKI_DBFF_ACCURATE", 0);


#-- plugin registration
$ewiki_plugins["database"][0] = "ewiki_database_files";


#-- backend
class ewiki_database_files {

   function ewiki_database_files() {
   }


   function GET($id, $version=false) {

      if (!$version && !($version = $this->LASTVER($id))) {
         return;
      }
      #-- read file      
      $dbfile = $this->FN("$id.$version");
      if(file_exists("local/$dbfile"))
         $dbfile = "local/$dbfile";
      if ($f = @gzopen($dbfile, "rb")) {
         $dat = @gzread($f, 1<<21);
         gzclose($f);
      }

      #-- decode      
      if ($dat && (substr($dat, 0, 2) == "a:")) {
         $r = unserialize($dat);
      }
      if (empty($r)) {
         $r = array();
         $p = strpos($dat, "\012\015\012");
         $p2 = strpos($dat, "\012\012");
         if ((!$p2) || ($p) && ($p < $p2)) {
            $p = $p + 3;
         }
         else {
            $p = $p2 + 2;
         }
         $r["content"] = substr($dat, $p);
         $dat = substr($dat, 0, $p);

         foreach (explode("\012", $dat) as $h) {
            if ($h = trim($h)) {
               $r[trim(strtok($h, ":"))] = str_replace(EWIKI_DBFILES_NLR, "\n", trim(strtok("\000")));
            }
         }
      }
      return($r);
   }


   function WRITE($hash, $overwrite=0) {

      #-- which file
      $dbfile = $this->FN($hash["id"].".".$hash["version"]);
      if (!$overwrite && file_exists($dbfile)) {
         return(0);
      }

      #-- write
      if (EWIKI_DB_FAST_FILES) {
         $val = serialize($hash);
         if (($f = gzopen($dbfile, "wb".EWIKI_DBFILES_GZLEVEL))) {
            gzwrite($f, $val);
            gzclose($f);
         }
         return(1);
      }
      else {
         $headers = "";
         foreach ($hash as $hn=>$hv) if ($hn != "content") {
            $headers .= $hn . ": " . str_replace("\n", EWIKI_DBFILES_NLR, $hv) . "\015\012";
         }
         if ($f = fopen($dbfile, "wb")) {
            flock($f, LOCK_EX);
            fputs($f, $headers . "\015\012" . $hash["content"]);
            flock($f, LOCK_UN);
            fclose($f);
            return(1);
         }
      }
   }


   function HIT($id) {
      if (EWIKI_DB_FAST_FILES) {
         $dbfile = $this->FN("$id.1");
         if ($f = gzopen($dbfile, "rb")) {
            $r = unserialize(@gzread($ff, 1<<21));
            gzclose($f);
            if ($r) {
               $r["hits"] += 1;
               if ($f = gzopen($dbfile, "wb".EWIKI_DBFILES_GZLEVEL)) {
                  gzwrite($fp, serialize($r));
                  gzclose($fp);
      }  }  }  }
   }
   
   
   function FIND($list) {
      $existing = array_flip($this->ALLFILES());
      $r = array();
      foreach ($list as $id) {
         $dbfile = $this->FN($id, 0);
         $r[$id] = isset($existing[$dbfile]) ?1:0;
         if (EWIKI_DBFF_ACCURATE && $r[$id] && strpos($id, "://")) {
            $uu = $this->GET($id);
            if ($uu["meta"]) {
               $r[$id] = unserialize($uu["meta"]);
               $r[$id]["flags"] = $uu["flags"];
            } else {
               $r[$id] = $uu["flags"];
            } 
         }
      }
      return($r);
   }


   function GETALL($fields, $mask=0, $filter=0) {
      $r = new ewiki_dbquery_result($fields);
      foreach ($this->ALLFILES() as $id) {
         $r->entries[] = $id;
      }
      return($r);
   }

   
   function SEARCH($field, $content, $ci="i", $regex=0, $mask=0, $filter=0) {
      $r = new ewiki_dbquery_result(array($field));
      $strsearch = $ci ? "stristr" : "strpos";
      foreach ($this->ALLFILES() as $id) {
         $row = $this->GET($id);
         if ($mask && ($filter == $row["flags"] & $mask)) {
            continue;
         }
         $match = 
            !$regex && ($strsearch($row[$field], $content)!==false)
            || $regex && preg_match("\007$content\007$ci", $row[$field]);
         if ($match) {
            $r->add($row);
         }
      }
      return($r);
   }
   
   
   function DELETE($id, $version) {
      $fn = $this->FN("$id.$version");
      @unlink($fn);
   }

   function INIT() {
      if (!is_writeable(EWIKI_DBFILES_DIRECTORY) || !is_dir(EWIKI_DBFILES_DIRECTORY)) {
         mkdir(EWIKI_DBFILES_DIRECTORY)
         or die("db_flat_files: database directory '".EWIKI_DBFILES_DIRECTORY."' is not writeable!\n");
      }
   }



   #-- db plugin internal ---------------------------------------------- 

   function FN($id, $prepend_path=1) {
      $fn = EWIKI_DBFILES_ENCODE ? urlencode($id) : strtr($id, '/:', '\\:');
      if ($prepend_path) {
         $fn = EWIKI_DBFILES_DIRECTORY.DIRECTORY_SEPARATOR . $fn;
      }
      return($fn);
   }


   function ID($fn) {
      $id = EWIKI_DBFILES_ENCODE ? urldecode($fn) : strtr($fn, '\\:', '/:');
      return($id);
   }

    
   function LASTVER($id) {
      $find = $this->FN($id, 0);
      $find_n = strlen($find);
      $n = 0;
	      if ($find_n) {
	         $dh = opendir(EWIKI_DBFILES_DIRECTORY);
	         while ($fn = readdir($dh)) {
	            if ( (strpos($fn, $find) === 0) &&     //@FIXME: empty delimiter
	                 ($dot = strrpos($fn, ".")) && ($dot == $find_n) &&
	                 ($uu = substr($fn, ++$dot)) && ($uu > $n)  )
	            {
	               $n = $uu;
	            }
	      }  }
      return($n);
   }


   function ALLFILES() {
      $r = array();
      $dh = opendir(EWIKI_DBFILES_DIRECTORY);
      $n = 0;
      while ($fn = readdir($dh)) {
         if (is_file(EWIKI_DBFILES_DIRECTORY . "/" . $fn)) {
            $id = $this->ID($fn);
            if (($dot = strrpos($id, ".")) && (substr($id, $dot+1) >= 1)) {
               $file = substr($id, 0, $dot);
               $r[] = $file;
            }
            if ($n++ > 1000) {
               $n = 0;
               $r = array_unique($r);
            }
         }
      }
      closedir($dh);
      $r = array_unique($r);
      return($r);
   }
   

} // end of class ewiki_database_files

?><?php
/*
   With this extension loaded you can inject a custom message on top
   of the edit box. You simply set it by giving an "edit: ...." entry
   within the meta data field (see plugins/meta/).
*/


$ewiki_plugins["edit_form_final"][] = "ewiki_meta_edit_message";
function ewiki_meta_edit_message(&$o, $id, &$data, $action) {
   if ($msg = $data["meta"]["meta"]["edit"][0]) {
      $o = "<div class=\"system-message\">$msg</div>\n"
         . $o;
   }
}


?><?php
/*
   Puts a warning message above the edit box, if someone else activated
   the edit screen recently (spiders often interfer with this). This is
   a poor replacement for the 'patchsaving' extension (see ../feature/).
   Needs EWIKI_TMP correctly set.
   
   @feature: edit-warn
   @title: concurrent edit warning
   @desc: if you cannot use 'patchsaving' you should at least warn people if pages are edited concurrently
*/

$ewiki_plugins["edit_form_final"][] = "ewiki_edit_warn";

function ewiki_edit_warn(&$o, $id, &$data, $action) {

   $keep = 420;  // in seconds

   if (!file_exists($dir = EWIKI_TMP."/edit.d/")) {
      mkdir($dir);
   }

   $lockfile = $dir . ewiki_lowercase($id) . ".lock";
   $time = 0;
   if (file_exists($lockfile)) {
      $time = filemtime($lockfile);
   }
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      @unlink($lockfile);
   }
   elseif ($time + $keep > time()) {
      $o = ewiki_t("<p class=\"system-message\"><b>_{Warning}</b>:"
         . " _{This page is currently being edited by someone else}."
         . " _{If you start editing now, your changes may get lost}."
         . "</p>\n")
         . $o;
   }
   elseif ($time) {
      // unlink($lockfile);
      touch($lockfile);
   }
   else {
      touch($lockfile);
   }

}

?><?php

#  if someone uploads an image, which is larger than the allowed
#  image size (EWIKI_IMAGE_MAXSIZE), then this plugin tries to
#  rescale that image until it fits; it utilizes the PHP libgd
#  functions to accomplish this

#  NOTE: It is currently disabled for Win32, because nobody knows, if
#  this will crash the PHP interpreter on those systems.


define("EWIKI_IMGRESIZE_WIN", 0);


if (!strstr(PHP_VERSION, "-dev") && !(extension_loaded("php_gd2.dll") or extension_loaded("gd.so")) && !function_exists("imagecreate") && function_exists("dl")) {   #-- try to load gd lib
   @dl("php_gd2.dll") or @dl("gd.so");
}
if (function_exists("imagecreate")) {
   $ewiki_plugins["image_resize"][] = "ewiki_binary_resize_image_gd";
}


function ewiki_binary_resize_image_gd(&$filename, &$mime, $return=0) {

           /*** this disallows Win32 ***/
   if (    (DIRECTORY_SEPARATOR!="/") && !EWIKI_IMAGERESIZE_WIN
       || (strpos($mime, "image/")!==0) )
   { 
      return(false);
   }

	if(filesize($filename) < EWIKI_IMAGE_MAXSIZE){
		return(true);
	}

   $tmp_rescale = $filename;

   #-- initial rescale
   $r = EWIKI_IMAGE_MAXSIZE / filesize($tmp_rescale);
   $r = ($r) + ($r - 1) * ($r - 1);

   #-- read orig image
   strtok($mime, "/");
   $type = strtok("/");
   if (function_exists($pf = "imagecreatefrom$type")) {
      $orig_image = $pf($filename);
   }
   else {
      return(false);
   }
   $orig_x = imagesx($orig_image);
   $orig_y = imagesy($orig_image);

   #-- change mime from .gif to .png
   if (($type == "gif") && (false || function_exists("imagepng") && !function_exists("imagegif"))) {
      $type = "png";
   }

   #-- retry resizing
   $loop = 20;
   while (($loop--) && (filesize($tmp_rescale) > EWIKI_IMAGE_MAXSIZE)) {

      if ($filename == $tmp_rescale) {
         $tmp_rescale = tempnam(EWIKI_TMP, "ewiki.img_resize_gd.tmp.");
      }

      #-- sizes
      $new_x = (int) ($orig_x * $r);
      $new_y = (int) ($orig_y * $r);

      #-- new gd image
      $tc = function_exists("imageistruecolor") && imageistruecolor($orig_image);
      if (!$tc || ($type == "gif")) {
         $new_image = imagecreate($new_x, $new_y);
         imagepalettecopy($new_image, $orig_image);
      }
      else {
         $new_image = imagecreatetruecolor($new_x, $new_y);
      }

      #-- resize action
      imagecopyresized($new_image, $orig_image, 0,0, 0,0, $new_x,$new_y, $orig_x,$orig_y);

      #-- special things
      if ( ($type == "png") && function_exists("imagesavealpha") ) {
         imagesavealpha($new_image, 1);
      }

      #-- save
      if (function_exists($pf = "image$type")) {
         $pf($new_image, $tmp_rescale);
      }
      else {
         return(false);   # cannot save in orig format (.gif)
      }

      #-- prepare next run
      imagedestroy($new_image);
      clearstatcache();
      $r *= 0.95;
   }

   #-- stop
   imagedestroy($orig_image);

   #-- security check filesizes, abort
   if (!filesize($filename) || !filesize($tmp_rescale) || (filesize($tmp_rescale) > EWIKI_IMAGE_MAXSIZE)) {
      unlink($tmp_rescale);
      return($false);
   }

   #-- set $mime, as it may have changed (.gif)
   $mime = strtok($mime, "/") . "/" . $type;
   if (!strstr($filename, ".$type")) {
      unlink($filename);
      $filename .= ".$type";
   }

   #-- move tmp file to old name
   copy($tmp_rescale, $filename);
   unlink($tmp_rescale);
   return(true);

}

?><?php

/*
   This filter plugin implements minimal html tag balancing, and can also
   convert ewiki_page() output into (hopefully) valid xhtml. It just works
   around some markup problems found in ewiki and that may arise from Wiki
   markup abuse; it however provides no fix for <ul> inside <ul> or even
   <h2> inside <p> problems (this should rather be fixed in the ewiki_format
   function).  So following code is not meant to fix any possible html file,
   and it certainly won't make valid html files out of random binary data. 
   So for full html spec conformance you should rather utilize w3c tidy (by
   using your Webservers "Filter" directive).
*/


define("EWIKI_XHTML", 0);
$ewiki_plugins["page_final"][] = "ewiki_html_tag_balancer";


function ewiki_html_tag_balancer(&$html) {

   #-- vars
   $html_standalone = array(
      "img", "br", "hr",
      "input", "meta", "link",
   );
   $html_tags = array(
      "a", "abbr", "acronym", "address", "applet", "area", "b", "base",
      "basefont", "bdo", "big", "blockquote", "body", "br", "button",
      "caption", "center", "cite", "code", "col", "colgroup", "dd", "del",
      "dfn", "dir", "div", "dl", "dt", "em", "fieldset", "font", "form",
      "h1", "h2", "h3", "h4", "h5", "h6", "head", "hr", "html", "i",
      "iframe", "img", "input", "ins", "kbd", "label", "legend", "li",
      "link", "map", "menu", "meta", "noframes", "noscript", "object", "ol",
      "optgroup", "option", "p", "param", "pre", "q", "s", "samp", "script",
      "select", "small", "span", "strike", "strong", "style", "sub", "sup",
      "table", "tbody", "td", "textarea", "tfoot", "th", "thead", "title",
      "tr", "tt", "u", "ul", "var",
      #-- H2.0  "nextid", "listing", "xmp", "plaintext",
      #-- H3.2  "frame", "frameset",
      #-- X1.1  "rb", "rbc", "rp", "rt", "rtc", "ruby",
   );
   $close_opened_when = array(
      "p", "div", "ul", "td",
   );
   if (!EWIKI_XHTML) {
      $html_tags = array_merge(  (array) $html_tags, array(
         "bgsound", "embed", "layer", "multicol", "nobr", "noembed",
      ));
   }

   #-- walk through all tags
   $tree = array();
   $len = strlen($html);
   $done = "";
   $pos = 0;
   $loop = 1000;
   while (($pos < $len) && $loop--) {

      #-- search next tag
      $l = strpos($html, "<", $pos);
      $r = strpos($html, ">", $l);
      if (($l===false) or ($r===false)) {
         # finish
         $done .= substr($html, $pos);
         break;
      }

      #-- copy plain text part
      if ($l >= $pos) {
         $done .= substr($html, $pos, $l-$pos);
         $pos = $l;
      }

      #-- analyze current html tag
      if ($r >= $pos) {
         $pos = $r + 1;
         $tag = substr($html, $l + 1, $r - $l - 1);

         #-- split into name and attributes
         $tname = strtolower(strtok($tag, " \t\n>"));     // LOWERCASING not needed here really
         ($tattr = strtok(">")) && ($tattr = " $tattr");

         // attribute checking could go here
         // (here we just assume good output from ewiki core)
         // ...

         #-- html comment
         if (substr($tname, 0, 3) == "!--") {
            $r = strpos($html, "-->", $l+4);
            $pos = $r + 3;
            $done .= substr($html, $l, $r-$l+3);
            continue;
         }

         #-- opening tag?
         elseif ($tname[0] != "/") {

            #-- standalone tag
            if (in_array($tname, $html_standalone)) {
               $tattr = rtrim(rtrim($tattr, "/"));
               if (EWIKI_XHTML) {
                  $tattr .= " /";
               }
            }
            #-- normal tag
            else {
               if (in_array($tname, $html_tags)) {
                  #-- ok
               }
               else {
                  $tattr .= " class=\"$tname\"";
                  $tname = "div";
               }
               array_push($tree, $tname);
            }

            $tag = "$tname$tattr";
         }
         #-- closing tag
         else {
            $tname = substr($tname, 1);

            if (!in_array($tname, $html_tags)) {
               $tname= "div";
            }

            #-- check if this is allowed
            if (!$tree) {
               continue;   // ignore closing tag
            }
            $last = array_pop($tree);
            if ($last != $tname) {

               #-- close until last opened block element
               if (in_array($tname, $close_opened_when)) {
                  do {
                     $done .= "</$last>";
                  }
                  while (($last = array_pop($tree)) && ($last!=$tname));
               }
               #-- close last, close current, reopen last
               else {
                  array_push($tree, $last);
                  $done .= "</$last></$tname><$last>";
                  continue;
               }
            }
            else {
               #-- all ok
            }

            #-- readd closing-slash to tag name
            $tag = "/$tname";
         }

         $done .= "<$tag>";
      }
   }

   #-- close still open tags
   while ($tree && ($last = array_pop($tree))) {
      $done .= "</$last>";
   }

   #-- copy back changes
   $html = stripslashes($done);

}


?><?php

/*
   This plugin can cache readily rendered pages either as files or in the
   ewiki database, so they will show up much faster when accessed a second
   time.

   Right now it only supports storing the fully rendered page (_CACHE_FULL).
   Storing of page plugins output (_CACHE_ALL) should be kept disabled,
   because "UpdatedPages" and so on cannot be verified to not have updated
   since the cache entry was written.
   Also you may want to disallow caching of the "links" action, because
   there is no change tracking with it.
*/

#-- how and which pages to store
define("EWIKI_CACHE_FULL", 1);   # including control links line?
define("EWIKI_CACHE_ALL", 0);    # also virtual pages?
define("EWIKI_CACHE_VTIME", 1000);  # time to keep page plugins` output, NYI

#-- where to store the pre-rendered pages (DB or files) - unset one of them:
define("EWIKI_CACHE_DIR", "./var/cache");    # preferred over db storage
define("EWIKI_CACHE_DB", "system/cache/");   # only has effect, if _DIR undefined

#-- when to store rendered pages?
$ewiki_config["cache.actions"] = array(
   "view", "info",
   "links", 
);


#-- plugin glue
if (EWIKI_CACHE_FULL) {
   $ewiki_plugins["handler"][] = "ewiki_handler_cache_full";
   $ewiki_plugins["page_final"][] = "ewiki_store_cache_full";
}
else {
   die("unsupported ewiki caching guideline setting");
}


#-- init
#if (defined("EWIKI_CACHE_DIR") && !file_exists(EWIKI_CACHE_DIR)) {
#   @mkdir(dirname(EWIKI_CACHE_DIR));
#   mkdir(EWIKI_CACHE_DIR);
#}


#-- fetch cache entry for page
function ewiki_get_cache($action, $id) {
	return array();
/*   $row = array();
   if (defined("EWIKI_CACHE_DIR") && EWIKI_CACHE_DIR) {
      $file = EWIKI_CACHE_DIR . "/" . $action . "," . urlencode($id);
      if (file_exists($file)) {
         $f = gzopen($file, "r");
         if ($f) {
            $content = @gzread($f, 1<<17);
            fclose($f);
            if ($content) {
               $row = array(
                  "id" => $id,
                  "version" => 1,
                  "flags" => EWIKI_DB_F_BINARYEWIKI_DB_F_TEXT|EWIKI_DB_F_HTML,
                  "created" => filectime($file),
                  "lastmodified" => filemtime($file),
                  "content" => $content,
                  "meta" => array("class"=>"cache"),
               );
            }
         }
      }
   }
   elseif (defined("EWIKI_CACHE_DB") && (EWIKI_CACHE_DB)) {
      $id = EWIKI_CACHE_DB."$action/$id";
      $row = ewiki_db::GET($id);
   }
   return($row);
*/
}


#-- return rendered page
function ewiki_handler_cache_full($id, &$data, $action) {
   global $ewiki_config;
   if (in_array($action, $ewiki_config["cache.actions"]) && ($cache = ewiki_get_cache($action,$id))) {
      if ($cache["lastmodified"] >= $data["lastmodified"]) {
         $data = &$cache;
         ewiki_http_headers($data["content"], $id, $cache, $action);
         return($data["content"]);
      }
   }
}


#-- if we get here, we should store the rendered page
function ewiki_store_cache_full(&$o, $id, &$data, $action) {
   global $ewiki_config;
   if (in_array($action, $ewiki_config["cache.actions"]) && ($data["version"] || EWIKI_CACHE_ALL) && ($_SERVER["REQUEST_METHOD"]=="GET")) {

      #-- only store, if we got just a few QueryString parameters
      if (count($_GET) <= 2) {
         ewiki_put_cache($action, $id, $o);
      }
   }
}


#-- real save function
function ewiki_put_cache($action, $id, &$o) {
/*   #-- save into cache dir
   if (defined("EWIKI_CACHE_DIR") && EWIKI_CACHE_DIR) {
      $file = EWIKI_CACHE_DIR . "/" . $action . "," . urlencode($id);
      $f = gzopen($file, "w9");
      if ($f) {
         gzwrite($f, $o);
         fclose($f);
      }
   }
   #-- store in ewiki database
   elseif (defined("EWIKI_CACHE_DB") && (EWIKI_CACHE_DB)) {
      $id = EWIKI_CACHE_DB."$action/$id";
      $save = array(
         "id" => $id,
         "version" => 1,
         "flags" => EWIKI_DB_F_BINARYEWIKI_DB_F_TEXT|EWIKI_DB_F_HTML,
         "created" => $data["lastmodified"],
         "lastmodified" => time(),
         "content" => &$o,
         "meta" => array("class"=>"cache"),
         "author" => ewiki_author("ewiki_cache"),
      );
      ewiki_db::WRITE($save, true);
   }
*/}


?><?php
/*
  Creates a cache entry in the database for retrieved objects.

  @depends: http
*/


#-- retrieve URL, but always create/check cache entry
function ewiki_cache_url($url, $cache_min=1200) {
   global $ewiki_cache_ctype;
   
   #-- check if fresh enough in cache (20min)
   $data = ewiki_db::GET($url);
   if (time() <= $data["lastmodified"] + $cache_min) {
      $ewiki_cache_ctype = $data["Content-Type"];
      return($data["content"]);
   }

   #-- retrieve
   $req = new http_request("GET", $url);
   $req->header["Accept"] = "application/atom+xml, application/rss+xml, text/rss, xml/*, */*rss*";
   if ($data["meta"]["Last-Modified"]) {
      $req->headers["If-Modified-Since"] = $data["meta"]["Last-Modified"];
   }
   if ($data["meta"]["Etag"]) {
      $req->headers["If-None-Match"] = $data["meta"]["Etag"];
   }
   $result = $req->go();
   
   #-- create/overwrite cache entry
   if ($result->status == 200) {
      $data = ewiki_db::CREATE($url, 0x0000, "ewiki_cache_url");
      $data["flags"] = 0x0000;
      $data["content"] = $result->body;
      foreach ($result->headers as $i=>$v) {
         $data["meta"][$i] = $v;
      }
      $data["meta"]["class"] = "temp";
      $data["meta"]["kill-after"] = time() + $cache_min;
      if ($t = $data["meta"]["Last-Modified"]) {
   //    $data["lastmodified"] = ewiki_decode_datetime($t);
      }
      ewiki_db::WRITE($data,"_OVERWRITE=1");
   }

   $ewiki_cache_ctype = $data["Content-Type"];
   return($data["content"]);
}

?><?php

/*
   ASCII-Art tables can be used in Wiki pages, if you load this plugin (it
   internally converts them into standard tables).
   Such tables usually look like:

   +----------+--------+----+
   | dl       | 0x0A   | 1  |
   +----------+--------+----+
   | ibbo,    | 0x12   | 2  |
   | nna      |        |    |
   +----------+--------+----+
   | nf,      | 0xFF   | 3  |
   +----------+--------+----+

   It's essentially a list, which rows are separated by horizontal bars, so
   one can have multiple lines making up one cell. If you don't import such 
   tables from an app (mysql outputs such tables), you could shorten writing
   them into:

   --------
   | cell1   | cell2 |
   ------
   | row2, col1 |  col2/cell4  |
   | still row2 |  ...  |
   +-----
   | row 3   | ... |
   -------

   Instead of only using minus signs, you could have some plus signs in it
   (or even a complete line of them).
*/



$ewiki_plugins["format_source"][] = "ewiki_formatsrc_ascii_art_tables";



function ewiki_formatsrc_ascii_art_tables(&$src) {
   $src = preg_replace('/^([+-]{5,}\n\|[^\n]+\n((\|[^\n]+|[+-]+)\n)+)/mse', 'ewiki_formatsrc_asciitbl_cells(stripslashes("\\1"))', $src);
}


function ewiki_formatsrc_asciitbl_cells($str) {
   $rows = preg_split('/^[+-]+\n/m', $str);
   $str = "";
   foreach ($rows as $row) {
      if (empty($row)) {
         continue;
      }
      $cells = array();
      $lines = explode("\n", $row);
      foreach ($lines as $l=>$line) {
         $add = explode("|", trim($line, "|"));
         if (empty($cells)) {
            $cells = $add;
         }
         else {
            foreach ($add as $i=>$text) {
               if (!trim($text) && ($l+1<count($lines))) { 
                  $text = "<br /><br />";
               }
               $cells[$i] .= " $text";
            }
         }
      }
      $str .= "|" . implode("|", $cells) . "|\n";
   }
   $str = preg_replace('/(<br />\s*)+\|/', "|", $str);
   return($str);
}


?><?php

 /*

   this plugin introduces markup for footnotes, use it like:

      ...
      some very scientific sentence {{this is a footnote explaination}}
      ...

   this may be useful in some rare cases; usually one should create
   a WikiLink to explain a more complex task on another page;
   your decision

*/  



$ewiki_plugins["format_source"][] = "ewiki_format_source_footnotes";



function ewiki_format_source_footnotes (&$source) {

   $notenum = 0;

   $l = 0;
   while (
            ($l = strpos($source, "{{", $l))
        &&  ($r = strpos($source, "}}", $l))
         )
   {
      $l += 2;

      #-- skip "{{...\n...}}"
      if (strpos($source, "\n", $l) < $r) {
         continue;
      }

      $notenum++;

      #-- extract "footnote"
      $footnote = substr($source, $l, $r - $l);

      #-- strip "{{footnote}}"
      $source = substr($source, 0, $l - 2)
             . "<a href=\"#fn$notenum\">$notenum</a>"
             . substr($source, $r + 2);

      #-- add "footnote" to the end of the wiki page source
      if ($notenum==1) {
         $source .= "\n----";
      }
      $source .= "\n" .
                 "<a name=\"fn$notenum\">$notenum</a> ". $footnote . "\n<br />";
      
   }
}


?><?php

/*
   You can use the ordinary html <table>, <tr> and <td> tags in all wiki
   pages, if you activate this plugin. Standard attributes are allowed
   (bgcolor, width, class, style, align, ...). It provides only limited 
   tag correction support, but you can often leave out </tr> and </td>:

   <table width="100%">
     <tr> <td> cell1
          <td> cell2
     <tr> <td> row2
          <td> cell4
   </table>
*/


$ewiki_plugins["format_block"]["htmltable"][] = "ewiki_markup_fblock_htmltable";
$ewiki_config["format_block"]["htmltable"] = array("&lt;table", "&lt;/table&gt;", false, 0x0027);


function ewiki_markup_fblock_htmltable(&$c, &$in, &$ooo, &$s) {
   if (($p = strpos($c, "&gt;")) !== false) {


      // clean <table> start and </table> end tag
      $c = "<table " . ewiki_markup_htmltable_attrs(substr($c, 0, $p))
         . ">" . substr($c, $p + 4) . "</table>";
//	echo "<!--$c-->";
      
      // clean <td> and <tr> tags
      $c = str_replace("&lt;", "<", $c);
      $c = str_replace("&gt;", ">", $c);
//	echo "<!--$c-->";
	
   }
}


function ewiki_markup_htmltable_attrs($str) {
   if (preg_match_all('/(\s+(class|style|width|height|align|bgcolor|valign|border|colspan|rowspan|cellspacing|cellpadding)=(\w+|"[^"]+"))/', $str, $uu)) {
      $str = implode("", $uu[1]);
   }
   return stripslashes($str);
}


?><?php

/*
   Using this plugin, ordinary plain text written enumarated lists are
   recognized (transformed into Wiki lists internally).

   1. list
   2. list
   3) list
     a. list
     b. list
   4) list
*/


$ewiki_plugins["format_source"][] = "ewiki_format_src_natural_lists";

function ewiki_format_src_natural_lists(&$src) {

   $src = preg_replace(
      "/^(  )*(\d+|[a-zA-Z])[.)]\s/me", 
      ' str_repeat("#", strlen("$1")>>1) . "#" . (0 ? "" : substr("$2",0,1)) . " " ',
      $src
   );
}


?><?php

/*
     Can be used to allow preserving of certain "safe" HTML <tags>
     (as seen in [sfWiki | http://sfwiki.sf.net/].
     "Safe" tags include Q, S, PRE, TT, H1-H6, KBD, VAR, XMP, B, I
     but just see (or change) ewiki_format() for more. They are not
     accepted if written with mixed lowercase and uppercase letters,
     and they cannot contain any tag attributes.

     RESCUE_HTML was formerly part of the main rendering function, but
     has now been extracted into this plugin, so one only needs to
     include it to get simple html tags working.
*/


$ewiki_plugins["format_source"][] = "ewiki_format_rescue_html";


function ewiki_format_rescue_html(&$wiki_source) {

   $safe_html = EWIKI_RESCUE_HTML;
   $safe_html += 1;

   $rescue_html = array(
      "br", "tt", "b", "i", "strong", "em", "s", "kbd", "var", "xmp", "sup", "sub",
      "q", "h2", "h3", "h4", "h5", "h6", "cite",  "u"
   );

   #-- unescape allowed html
   if ($safe_html) {
    /*
      foreach ($rescue_html as $tag) {
         foreach(array($tag, "/$tag", ($tag=strtoupper($tag)), "/$tag") as $tag) {
            $wiki_source = str_replace('&lt;'.$tag.'&gt;', "<".$tag.">", $wiki_source);
      }  }
    */
      $wiki_source = preg_replace('#&lt;(/?('.implode("|",$rescue_html).'))&gt;#i', '<$1>', $wiki_source);
   }

}


?><?php

/*
   This markup extension, allows for following syntax to merge
   neighboured table cells together:

   | col1 | col2 | col3 |
   | col2 || col2 and 3 |
   ||| this occoupies the whole row |
   | col1 | col2 | col3 |
*/


$ewiki_plugins["format_final"][] = "ewiki_table_colspan";
function ewiki_table_colspan(&$html) {
   $html = preg_replace(
      '#(<td></td>\s*)+<td#e',
      '"<td colspan=\"" . (1+((int)(strlen(preg_replace("/\s+/","","$0"))-3)/9)) . "\""',
      $html
   );
}

?><?php

/*
   This markup extension, allows for following syntax to merge
   neigboured table cells together:

   | col1 | row2 | row3 |
   | col2 || row2 and 3 |
   ||| this occoupies the whole column |
   | row1 | row2 | row3 |
*/


$ewiki_plugins["format_final"][] = "evil_table_rowspan";
function evil_table_rowspan(&$html) {
   $html = preg_replace(
      '#(<td></td>\s*)+<td#e',
      '"<td rowspan=\"" . (1+(int)(strlen("$1")/9)) . "\">"',
      $html
   );
}

?><?php
/*
   This plugin adds the <tex>...</tex> tags which allow you to
   integrate formulas into wiki pages, if you have the MimeTeX
   package (from John Forkosh) installed. You can get it from
   [http://www.forkosh.com/mimetex.html] (source or as binary)
   
   The original idea and implementation of this plugin was done by
   Francois Vanderseypen <illumineo*userssfnet> as you can see at
   [http://netron.sourceforge.net/ewiki/netron.php?id=MimeTeX]
   
   <tex> \aleph = \bigsum_{\alpha,\beta}\Bigint_{0}^{\infty}\:\Gamma_{\alpha\beta}(x)\,dx </tex> 
*/

define("MIMETEX_BIN", "mimetex");
   # the actual mimetex utility (on poorly configured UNIX boxes you would
   # have to give the full path name here)
   
define("MIMETEX_DIR", "/home/www/user28494/htdocs/ewiki/var/mimetex/");
   # where generated images are thrown in (world-writeable!), you could
   # use "/tmp" if _INLINE was ok for your users
   
define("MIMETEX_PATH", "/ewiki/var/mimetex/");
   # where to access the generated images then (prefix for the <img> URLs)
   
define("MIMETEX_INLINE", 0);
   # if you'd instead like data: URIs for images (does not work with IE <7)


$ewiki_plugins["format_block"]["tex"]= array("mimetex_format_block");
$ewiki_config["format_block"]["tex"] = array("&lt;tex&gt;", "&lt;/tex&gt;", false, 0x0410);


function mimetex_format_block(&$str, &$in, &$iii, &$s, $btype) {
   $str = mimetex_generate($str);
}


/*
   calls mimetex to create image or returns link to cached file
*/
function mimetex_generate($formula) {

   $formula = preg_replace("/[\s]+/", "", $formula);
   $filename = md5($formula).".gif";
   $fullname = MIMETEX_DIR."/$filename";
   
   $url = false;
   if (is_file($fullname)) {
      $url = MIMETEX_PATH."/$filename";
   }
   else {
      $cmd = MIMETEX_BIN . " -e $fullname '" . escapeshellarg($formula) . "'";
      system($cmd, $status);
      if (!$status_code) {
         $url = MIMETEX_PATH."/$filename";
      }
   }

   if ($url) {
      if (MIMETEX_INLINE) {
         $url = "data:image/gif;base64," . base64_encode(implode("", file($fullname)));
      }
      return('<img src="'.$url.'" alt="'.htmlentities($formula).'" align="absmiddle" />');
   }
   else {
      return("[MimeTex could not convert formula \"$formula\".]");
   }
}


?><?php

/*
   Evaluates the "title: " given in the {meta}{meta} field and uses
   this for the current page.
*/

function ewiki_meta_f_title($id, &$data, $action) {
   global $ewiki_title;
   if ($t = @$data["meta"]["title"]) {
      $ewiki_title = htmlentities($t);
   }
}

?><?php

#--  show infos about registered plugins (even internal plugins)
#


$ewiki_plugins["page"]["AboutPlugins"] = "ewiki_page_aboutplugins";



function ewiki_page_aboutplugins($id, $data, $action) {

   global $ewiki_plugins;

   $o = ewiki_make_title($id, $id, 2);

   #-- plugin types
   foreach (array("page", "action", "mpi") as $pclass) {

      $o .= "<u>$pclass plugins</u><br />\n";

      switch ($pclass) {
         case "page":
            $o .= "dynamically generated pages<br />\n";
            break;
         case "action":
            $o .= "can be activated on each (real) page<br />\n";
            break;
         case "mpi":
            $o .= "the markup plugins can be utilized to integrate dynamic content into pages<small> (loaded on demand, so rarely shown here)</small><br />\n";
            break;
         default:
      }

      if ($pf_a = $ewiki_plugins[$pclass]) {
          ksort($pf_a);
         if ($pclass=="action") {
            $pf_a = array_merge($pf_a, $ewiki_plugins["action_always"]);
         }
         foreach ($pf_a as $i=>$pf) {

            switch ($pclass) {
               case "page":
                  $i = '<a href="'.ewiki_script("",$i).'">'.$i.'</a>';
                  break;
               case "action":
                  $i = '<a href="'.ewiki_script($i,"Notepad").'">'.$i.'</a>';
                  break;
               case "mpi":
                  $i = '<a href="'.ewiki_script("mpi/$i").'">&lt;?plugin '.$i.'?&gt;</a>';
                  break;
               default:
            }

            $o .= " <b>$i</b> <small>via $pf</small><br />\n";

         }
      }

      $o .= "<br />\n";

   }

   #-- task plugins
   $o .= "<u>task plugins</u> (core stuff)<br />\n";
   $o .= "enhance the wiki engine internally, with widely varying functionality enhancements or changes<br />\n";
   foreach ($ewiki_plugins as $i=>$a) {
      if (is_array($a)) {
         foreach ($a as $n=>$pf) {

            if (is_int($n)) {

               $o .= " <b><tt>$i</tt></b> <small>via $pf</small><br />\n";

            }
         }
      }
   }
   $o .= "<br />\n";


   return($o);

}


?><?php

/*
   -- OBSOLETED by according spages/ plugin --
   this sums up all the hits from all pages, and prints the
   overall score points
*/



$ewiki_plugins["page"]["HitCounter"] = "ewiki_page_hitcounter";


function ewiki_page_hitcounter($id, $data, $action) {

   #-- loop thur all pages, and mk sum
   $n = 0;
   $result = ewiki_db::GETALL(array("hits"));
   while ($r = $result->get()) {
      if ($r["flags"] & EWIKI_DB_F_TEXT) {
        $n += $r["hits"];
      }
   }

   #-- output
   $title = ewiki_make_title($id, $id, 2);
   $AllPages = '<a href="'. ewiki_script("", "PageIndex") .'">AllPages</a>';
   $o = <<< ___
$title
The overall hit score of $AllPages is:
<div class="counter">
  $n
</div>
___
   ;
   return($o);
}


?><?php

/*
   -- OBSOLETED by according spages/ plugin --
   lists all pages, which are not referenced from others
   (works rather unclean and dumb)
*/


define("EWIKI_PAGE_ORPHANEDPAGES", "OrphanedPages");
$ewiki_plugins["page"][EWIKI_PAGE_ORPHANEDPAGES] = "ewiki_page_orphanedpages";


function ewiki_page_orphanedpages($id, $data, $action) {

   global $ewiki_links;

   $o = ewiki_make_title($id, $id, 2);

   $pages = array();
   $refs = array();
   $orphaned = array();

   #-- read database
   $db = ewiki_db::GETALL(array("refs", "flags"));
   $n=0;
   while ($row = $db->get()) {

      $p = $row["id"];

      #-- remove self-reference
      $row["refs"] = str_replace("\n$p\n", "\n", $row["refs"]);

      #-- add to list of referenced pages
      $rf = explode("\n", trim($row["refs"]));
      $refs = array_merge($refs, $rf);
      if ($n++ > 299) {
         $refs = array_unique($refs);
         $n=0;
      } // (clean-up only every 300th loop)

      #-- add page name
      if (($row["flags"] & EWIKI_DB_F_TYPE) == EWIKI_DB_F_TEXT) {
         $pages[] = $row["id"];
      }
   }
   $refs = array_unique($refs);

   #-- check pages to be referenced from somewhere
   foreach ($pages as $p) {
      if (!ewiki_in_array($p, $refs)) {
         if (!EWIKI_PROTECTED_MODE || !EWIKI_PROTECTED_MODE_HIDING || ewiki_auth($p, $uu, "view")) {
            $orphaned[] = $p;    
         }  
      }
   }

   #-- output
   $o .= ewiki_list_pages($orphaned, 0);

   return($o);
}


?><?php

/*
   This plugins provides the internal page PowerSearch, which allows
   to search in page contents and/or titles (or for author names, if any),
   it tries to guess how good the database match matches the requested
   search strings and orders results.
   The top 10 results are printed more verbosely.
*/


define("EWIKI_PAGE_POWERSEARCH", "PowerSearch");
$ewiki_plugins["page"][EWIKI_PAGE_POWERSEARCH] = "ewiki_page_powersearch";
$ewiki_plugins["action"]["search"] = "ewiki_action_powersearch";


function ewiki_action_powersearch(&$id, &$data, &$action) {
    $o = ewiki_make_title(EWIKI_PAGE_POWERSEARCH, EWIKI_PAGE_POWERSEARCH, 2);        
    $o.= ewiki_powersearch($id);
    return ($o);
}


function ewiki_page_powersearch($id, &$data, $action) {
    $q = @$_REQUEST["q"];

    ($where = preg_replace('/[^a-z]/', '', @$_REQUEST["where"]))
    or ($where = "content");

    $o = ewiki_make_title($id, $id, 2);
    
    if (empty($q)) {        
        $o .= '<div class="search-form">
        <form name="powersearch" action="' . ewiki_script("", $id) . '" method="GET">
        <input type="hidden" name="id" value="'.$id.'">
        <input type="text" id="q" name="q" size="30">
        in <select name="where"><option value="content">page texts</option><option value="id">titles</option><option value="author">author names</option></select>
        <br /><br />
        <input type="submit" value=" &nbsp; &nbsp; S E A R C H &nbsp; &nbsp; ">
        </form></div>
        <script type="text/javascript"><!--
        document.powersearch.q.focus();
        //--></script>';
        
        return($o);
    }
    else { 
        $o .= ewiki_powersearch($q, $where);
        return ($o);
    }

    return('');
}


function ewiki_powersearch($q, $where='content'){
    $q = ewiki_lowercase(preg_replace('/\s*[\000-\040]+\s*/', ' ', $q));
    
    $found = array(); 
    $scored = array(); 
    
    #-- initial scan
    foreach (explode(" ", $q) as $search) {
    
     if (empty($search)) {
        continue;
     }
    
     $result = ewiki_db::SEARCH($where, $search);
    
        while ($row = $result->get()) {        
            if (($row["flags"] & EWIKI_DB_F_TYPE) == EWIKI_DB_F_TEXT) {
                
                $id = $row["id"];
                $content = strtolower($row[$where]);
                unset($row);
                
                #-- have a closer look
                $len1 = strlen($content) + 1;
                
                if (!isset($scored[$id])) {
                    $scored[$id] = 1;
                }
                $scored[$id] += 800 * (strlen($search) / $len1);
                $scored[$id] += 65 * (count(explode($search, $content)) - 2);
                $p = -1;
                while (($p = strpos($content, $search, $p+1)) !== false) {
                    $scored[$id] += 80 * (1 - $p / $len1);
                }
            
            }#if-TXT
        }
    }
    
    
    #-- output results
    arsort($scored);
    
    $o = "<ol>\n";
    $n = 0;
    foreach ($scored as $id => $score) {
    
     #-- refetch page for top 10 entries (still cached by OS or DB)
     $row = ($n < 10) ? ewiki_db::GET($id) : NULL;
    
     #-- check access rights in protected mode
     if (EWIKI_PROTECTED_MODE && !ewiki_auth($id, $row, "view", $ring=false, $force=0)) {
        if (EWIKI_PROTECTED_MODE_HIDING) {
            continue;
        } else {
           $row["content"] = ewiki_t("FORBIDDEN");
        }
    }   
    
     $o .= "<li>\n";
     $o .= '<div class="search-result '.($oe^=1?"odd":"even").'">'
         . '<a href="' . ewiki_script("", $id) . '">' . $id . "</a> "
    #<off>#      . "<small><small>(#$score)</small></small>"
         . "\n";
    
     #-- top 10 results are printed more verbosely
    
     if ($n++ < 10) {
    
     preg_match_all('/([_-\w]+)/', $row["content"], $uu);
        $text = htmlentities(substr(implode(" ", $uu[1]), 0, 200));
        $o .= "<br />\n<small>$text\n"
            . "<br />" . strftime(ewiki_t("LASTCHANGED"), $row["lastmodified"])
            . "<br /><br /></small>\n";
     }
    
     $o .= "</div>\n";
    
     $o .= "</li>\n";
    
    }
    
    $o .= "</ol>\n";
    return($o); 
    
}


?><?php

/*
   Gives a more standard RecentChanges (besides the ewiki built-in
   "UpdatedPages") in two different variants.
*/


$ewiki_plugins["rc"][0] = "ewiki_page_rc_usemod";
//$ewiki_plugins["rc"][0] = "ewiki_page_rc_moin";

define("EWIKI_PAGE_RECENTCHANGES", "RecentChanges");

$ewiki_t["en"]["DAY"] = "%a, %d %b %G";
$ewiki_t["en"]["CLOCK"] = "%H:%M";
$ewiki_t["de"]["show last"] = "zeige letzte";
$ewiki_t["de"]["days"] = "Tage";



$ewiki_plugins["page"][EWIKI_PAGE_RECENTCHANGES] = "ewiki_page_recentchanges";
function ewiki_page_recentchanges($recentchanges, $data, $action) {

   global $ewiki_plugins, $ewiki_links;

   #-- start output
   $ewiki_links = true;
   $o = "";
   $o .= ewiki_make_title($recentchanges, $recentchanges, 2);
   
   #-- options
   $minor_edits = $_GET["minor"]?1:0;

   #-- select timeframe
   if (($days = $_REQUEST["days"]) < 1) {
      $days = 7;
   }
   $timeframe = time() - $days * 24 * 3600;

   #-- fetch pages modified in given timeframe
   $result = ewiki_db::GETALL(array("meta", "lastmodified", "author"));
   $changes = array();
   $meta = array();
   while ($row = $result->get(0, 0x0137, EWIKI_DB_F_TEXT)) {

      if ($row["lastmodified"] >= $timeframe) {

         #-- id->time array
         $id = $row["id"];
         $changes[$id] = $row["lastmodified"];

         #-- collect also info for previous changes of current page
         $meta[$id] = array();
         ewiki_page_rc_more($row, $meta[$id], $timeframe, $minor_edits);
      }
   }

   #-- sort results into date catalogue
   arsort($changes);
   $last_date = "";
   $datestr = ewiki_t("DAY");
   $e = array();
   foreach ($changes as $id=>$date) {

      $date = strftime($datestr, $date);
      if ($date != $last_date) {
         $last_date = $date;
      }

      $e[$date][] = $id;
      unset($changes[$id]);
   }


   #-- mk output
   $o .= $ewiki_plugins["rc"][0]($e, $meta);


   #-- add an <form>
   if ($days == 7) {
      $days = 30;
   }
   $url = ewiki_script("", $recentchanges);
   $o .= ewiki_t(<<<EOT
   <br />
   <form action="$url" method="GET">
     <input type="hidden" name="id" value="$recentchanges">
     _{show last} <input type="text" name="days" value="$days" size="5">
     <input type="submit" value="_{days}">
   </form>
   <br />
EOT
   );

   return($o);
}


/*
   UseMod like list output
*/
function ewiki_page_rc_usemod(&$e, &$meta) {

   $clockstr = ewiki_t("CLOCK");

   foreach ($e as $datestr => $pages) {

      $o .= "\n<h4 class=\"date\">$datestr</h4>\n";

      foreach ($pages as $id) {

         $diff = '<a href="'.ewiki_script("diff",$id).'">(diff)</a>';
         $page = '<a href="'.ewiki_script("",$id).'">'.htmlentities($id).'</a>';
         $time = strftime($clockstr, $meta[$id][0][2]);
         $author = ewiki_author_html($meta[$id][0][0], 0);
         $log = htmlentities($meta[$id][0][1]);
         $changes = "";
         if (($n = count($meta[$id])) > 1) {
            $changes = "($n ".'<a href="'.ewiki_script("info", $id).'">changes</a>)';
         }

         $o .= '&middot; '
             . $diff . ' '
             . $page
             . ' ' . $time . ' '
             . $changes . ' '
             . ($log ? '<b class="log">[' . $log . ']</b>' : '')
             . ' . . . . . ' . $author
             . '<br />' . "\n";
      }
   }
   $o .= "\n";
   return($o);
}


/*
   MoinMoin table style changelog output
*/
function ewiki_page_rc_moin(&$e, &$meta) {

   $clockstr = ewiki_t("CLOCK");

   $o .= '<table class="changes" border="0" width="100%">'
      . '<colgroup><col width="35%"><col width="5%"><col width="25%"><col width="35%"></colgroup>';

   foreach ($e as $datestr => $pages) {
      $o .= "\n<tr><td colspan=\"3\"><br /><h4 class=\"date\">$datestr</h4></td></tr>\n";
      foreach ($pages as $id) {

         $link = '<a href="' . ewiki_script("", $id) . '">' . htmlentities($id) . '</a>';
         $time = strftime($clockstr, $meta[$id][0][2]);
         $changes = $meta[$id];
         if (count($changes) >= 2) {

            #-- enum unique author names
            $a = array();
            foreach ($changes as $i=>$str) {
               $str = strtok($str[0], " <(");
               $a[$str][] = ($i+1);
            }
            $str = "";
            foreach ($a as $author=>$i) {
               $author = ewiki_author_html($author, 0);
               $str .= $author. "[".implode(",",$i)."]<br /> ";
            }
            $author = $str;

            #-- enum log entries
            $log = "";
            foreach ($meta[$id] as $i=>$str) {
               if ($str = $str[1]) {
                  $log .= "#".($i+1)." " . htmlentities($str) . "<br />\n";
               }
            }
         }
         else {
            $author = ewiki_author_html($meta[$id][0][0]);
            $log = htmlentities($meta[$id][0][1]);
         }

         $o .= '<tr><td class="page"> &middot; ' . $link . '</td>'
             . '<td class="time"> [' . $time . '] </td>'
             . '<td class="author">' . $author . '</td>'
             . '<td class="log">' . $log . '</td></tr>' . "\n";
      }
   }
   $o .= "</table>\n";
   return($o);
}


/*
   fills $list with changeLOG entries of previous page ($row) versions
*/
function ewiki_page_rc_more(&$row, &$list, $timeframe, $minor_edits) {

   $id = $row["id"];
   $ver = $row["version"];
   while ($ver >= 1) {

      if ($row["lastmodified"] >= $timeframe) {
         if ($minor_edits || !($row["flags"] & EWIKI_DB_F_MINOR)) {
            $list[] = array(
               0 => $row["author"],
               1 => $row["meta"]["log"],
               2 => $row["lastmodified"],
            );
         }
      }
      else {
         return;
      }

      $ver--;
      if (!$ver || !($row = ewiki_db::GET($id, $ver))) { 
         return;  // stops at revision holes
      }
   }
}

?><?php

/*
   Allows to download a tarball including all WikiPages and images that
   currently are in the database.
*/


#-- text
$ewiki_t["en"]["WIKIDUMP"] = "Here you can tailor your WikiDump to your needs.  <br /> When you are ready, click the \"_{DOWNLOAD_ARCHIVE}\" button.";
$ewiki_t["en"]["DOWNLOAD_ARCHIVE"] = "Download WikiDump";

define("EWIKI_WIKIDUMP_ARCNAME", "WikiDump_");
define("EWIKI_WIKIDUMP_DEFAULTTYPE", "TAR");
define("EWIKI_WIKIDUMP_MAXLEVEL", 1);
define('EWIKI_DUMP_FILENAME_REGEX',"/\W/");

#-- glue
if((function_exists(gzcompress) && EWIKI_WIKIDUMP_DEFAULTTYPE=="ZIP") || EWIKI_WIKIDUMP_DEFAULTTYPE=="TAR"){
  $ewiki_plugins["page"]["WikiDump"] = "ewiki_page_wiki_dump_tarball";
  $ewiki_plugins["action"]['wikidump'] = "ewiki_page_wiki_dump_tarball";
}

$ewiki_t["c"]["EWIKIDUMPCSS"] = '
  <style  TYPE="text/css">
  <!--
  body {
    background-color:#eeeeff;
    padding:2px;
  }	
  
  H2 {
    background:#000000;
    color:#ffffff;
    border:1px solid #000000;
  }
  -->
  </style>
  ';  
  

function ewiki_page_wiki_dump_tarball($id=0, $data=0, $action=0) {

   #-- return legacy page
   if (empty($_REQUEST["download_tarball"])) {
    if($action=="wikidump"){
      $url = ewiki_script("", "WikiDump");
      return(ewiki_make_title($id, $id, 2) . ewiki_t(<<<END
_{WIKIDUMP}
<br /><br />
<form action="$url" method="POST" enctype="multipart/form-data">
<input type="hidden" name="dump_id" value="$id">
<input type="hidden" name="dump_depth" value=1>
<input type="submit" name="download_tarball" value= "_{DOWNLOAD_ARCHIVE}">
<br /><br />
<input type="checkbox" name="dump_images" value="1" checked> _{with images}<br />
<input type="hidden" name="dump_fullhtml" value="1">
<input type="hidden" name="dump_virtual" value="0"><br />
Archive Format:
<select NAME="dump_arctype">
  <option VALUE="ZIP">ZIP
  <option VALUE="TAR">TAR
</select>

</form>
END
      ));
      } else {
        return "";
      }
   }
   #-- tarball generation
   else {
      $di = $_REQUEST["dump_images"];
      $fh = $_REQUEST["dump_fullhtml"];
      $vp = $_REQUEST["dump_virtual"];
      $rp = $_REQUEST["dump_id"];
      
      #-- $_REQUEST["dump_depth"]==100 will give a complete dump
      if(($_REQUEST["dump_depth"]>EWIKI_WIKIDUMP_MAXLEVEL) && ($_REQUEST["dump_depth"]!=100)){
        $dd=EWIKI_WIKIDUMP_MAXLEVEL;
      } else {
        $dd = $_REQUEST["dump_depth"];
      }
      $at = $_REQUEST["dump_arctype"];
      $al = 9;#$_REQUEST["dump_arclevel"];
      $_REQUEST = $_GET = $_POST = array();
      if(!ewiki_auth($rp, $str_null, "view")){
        return ewiki_make_title($id, $id, 2)."<p>You either do not have permission to access the page $rp or it does not exist.</p>";
      }
      ewiki_page_wiki_dump_send($di, $fh, $vp, $rp, $dd, $at, $al);
   }
}


function ewiki_page_wiki_dump_send($imgs=1, $fullhtml=0, $virtual=0, $rootid, $depth=1, $arctype=EWIKI_WIKIDUMP_DEFAULTTYPE, $complevel=1) {

  global $ewiki_config, $ewiki_plugins;
  
  #-- disable protected email
  foreach($ewiki_plugins["link_url"] as $key => $linkplugin){
    if($linkplugin == "ewiki_email_protect_link"){
      unset($ewiki_plugins["link_url"][$key]);
    }
  }

  #-- set uservars
  $a_uservars = ewiki_get_uservar("WikiDump", array());
  if(!is_array($a_uservars)){
    $a_uservars = unserialize($a_uservars);
  }
  $a_uservars[$rootid] = $depth;
  ewiki_set_uservar("WikiDump", $a_uservars);
  
  #-- if $fullhtml
  $HTML_TEMPLATE = '<html>
    <head>'.ewiki_t("EWIKIDUMPCSS").'
    <title>$title</title>
    </head>
    <body bgcolor="#ffffff";>
    <div id="PageText">
    <h2>$title</h2>
    $content
    </div>
    </body>
    </html>
    ';
  
  #-- reconfigure ewiki_format() to generate offline pages and files
  $html_ext = ".htm";
  if ($fullhtml) {
    $html_ext = ".html";
  }
  $ewiki_config["script"] = "%s$html_ext";
  $ewiki_config["script_binary"] = "%s";
  
  #-- fetch also dynamic pages
  $a_virtual = array_keys($ewiki_plugins["page"]);
  

  #-- get all pages / binary files
  $a_validpages = ewiki_valid_pages(1, $virtual);
  $a_pagelist = ewiki_sitemap_create($rootid, $a_validpages, $depth, 1);

  foreach($a_pagelist as $key => $value){
    if(is_array($a_validpages[$value]["refs"])){
      foreach($a_validpages[$value]["refs"] as $refs){
        if($a_validpages[$refs]["type"]=="image"){
          $a_pagelist[]=$refs;
        }
      }
    }
  }
  
  foreach($a_pagelist as $key => $value){
    if($a_validpages[$value]["type"]=="image"){
      $a_images[]=urlencode($value);
      $a_rimages[]=urlencode(preg_replace(EWIKI_DUMP_FILENAME_REGEX, "", $value));
      unset($a_validpages[$value]);
    }
  }

  $a_sitemap = ewiki_sitemap_create($rootid, $a_validpages, $depth, 0);

  if ($a_pagelist) {
    #-- create new zip file
    if($arctype == "ZIP"){
      $archivename=EWIKI_WIKIDUMP_ARCNAME."$rootid.zip";
      $archive = new ewiki_virtual_zip();
    } elseif ($arctype == "TAR") {
      $archivename=EWIKI_WIKIDUMP_ARCNAME."$rootid.tar";
      $archive = new ewiki_virtual_tarball();
    } else {
      die();
    }
    
    $a_pagelist = array_unique($a_pagelist);
    
    #-- convert all pages
    foreach($a_pagelist as $pagename){
      if ((!in_array($pagename, $a_virtual))) {
        $id = $pagename;
        #-- not a virtual page
        $row = ewiki_db::GET($pagename);
        $content = "";
      } elseif($virtual) {
        $id = $pagename;
        #-- is a virtual page
        $pf = $ewiki_plugins["page"][$id];
        $content = $pf($id, $content, "view");
        if ($fullhtml) {
          $content = str_replace('$content', $content, str_replace('$title', $id, $HTML_TEMPLATE));
        }
        $fn = urlencode($id);
        $fn = preg_replace(EWIKI_DUMP_FILENAME_REGEX, "", $fn);
        $fn = $fn.$html_ext;
      } else {
        continue;
      }
    
      if (empty($content)){
        switch ($row["flags"] & EWIKI_DB_F_TYPE) {
          case (EWIKI_DB_F_TEXT):
            $content = ewiki_format($row["content"]);
            $content = str_replace($a_images, $a_rimages, $content);
            $fn = preg_replace(EWIKI_DUMP_FILENAME_REGEX, "",  urlencode($id));
            $fn = $fn.$html_ext;
            if ($fullhtml) {
              $content =  str_replace('$content', $content, str_replace('$title', $id, $HTML_TEMPLATE));
            }
            break;
          
          case (EWIKI_DB_F_BINARY):
            if (($row["meta"]["class"]=="image") && ($imgs)) {
              $fn = urlencode(preg_replace(EWIKI_DUMP_FILENAME_REGEX, "", $id));
              $content = &$row["content"];
            }
            else {
              #-- php considers switch statements as loops so continue 2 is needed to 
              #-- hit the end of the for loop 
              continue(2);
            }
            break;
          
          default:
            # don't want it
            continue(2);
        }
      }
  
      $content=preg_replace_callback(
        '/(<a href=")(.*?)(\.html">)/',
        create_function(
        // single quotes are essential here,
        // or alternative escape all $ as \$
        '$matches',
        'return($matches[1].preg_replace(EWIKI_DUMP_FILENAME_REGEX,"",$matches[2]).$matches[3]);'
        ),
        $content
        );

      #-- add file
      $archive->add($content, $fn, array(
        "mtime" => $row["lastmodified"],
        "uname" => "ewiki",
        "mode" => 0664 | (($row["flags"]&EWIKI_DB_F_WRITEABLE)?0002:0000),
        ), $complevel);
    }
    
    #-- create index page
    $timer=array();
    $level=-1;
    $fordump=1;
    $str_formatted="<ul>\n<li><a href=\"".$rootid.$html_ext."\">".$rootid."</a></li>";
    $fin_level=format_sitemap($a_sitemap, $rootid, $str_formatted, $level, $timer, $fordump);
    $str_formatted.="</ul>".str_pad("", $fin_level*6, "</ul>\n");
    $str_formatted=preg_replace_callback(
        '/(<a href=")(.*?)(\.html">)/',
        create_function(
           // single quotes are essential here,
           // or alternative escape all $ as \$
           '$matches',
           'return($matches[1].preg_replace(EWIKI_DUMP_FILENAME_REGEX,"",$matches[2]).$matches[3]);'
        ),
        $str_formatted
      );
  
    #-- add index page
    $archive->add($str_formatted, "Index_$rootid".$html_ext, array(
      "mtime" => $row["lastmodified"],
      "uname" => "ewiki",
      "mode" => 0664 | (($row["flags"]&EWIKI_DB_F_WRITEABLE)?0002:0000),
      ), $complevel);
         
    #-- Headers
    Header("Content-type: application/octet-stream");
    Header("Content-disposition: attachment; filename=\"$archivename\"");
    Header("Cache-control: private");
    Header("Original-Filename: $archivename");    
    Header("X-Content-Type: application/octet-stream");
    Header("Content-Location: $archivename");


    #-- end output
    echo $archive->close();
  
  }
  
  #-- fin 
  die();
}




############################################################################




#-- allows to generate a tarball from virtual files
#   (supports no directories or symlinks and other stuff)
class ewiki_virtual_tarball {

   var $f = 0;
   var $datasec = array(); 

   function close() {
      #-- fill up file
      $this->write(str_repeat("\000", 9*1024));
      $data = implode("", $this -> datasec); 
      return $data;
   }


   function write($str) {
     $this ->datasec[] = $str;
   }


   function oct($int, $len) {
      $o = "\000";
      while (--$len) {
         $o = ($int & 0x07) . $o;
         $int = $int >> 3;
      }
      return($o);
   }


   #-- add virtual file
   function add($content, $filename, $args=array(), $ignored) {
   $args = array_merge($args, array(
         "mode" => 000664,
         "mtime" => time(),
         "ctime" => time(),
         "uid" => 65534,       #-- common for user "nobody"
         "gid" => 65534,
         "uname" => "nobody",
         "gname" => "nobody",
         "type" => "0",
      ));
      $args["mode"] |= 0100000;
      $args["size"] = strlen($content);
      $checksum = "        ";
      $magic = "ustar  \000";
      $filename = substr($filename, 0, 99);

      #-- header record
      $header  = str_pad($filename, 100, "\000")            # 0x0000
               . $this->oct($args["mode"], 8)               # 0x0064
               . $this->oct($args["uid"], 8)                # 0x006C
               . $this->oct($args["gid"], 8)                # 0x0074
               . $this->oct($args["size"], 12)              # 0x007C
               . $this->oct($args["mtime"], 12)             # 0x0088
               . ($checksum)                                # 0x0094
               . ($args["type"])                            # 0x009C
               . str_repeat("\000", 100)                    # 0x009D
               . ($magic)                                   # 0x0101
               . str_pad($args["uname"], 32, "\000")        # 0x0109
               . str_pad($args["gname"], 32, "\000")        # 0x0129
               ;                                            # 0x0149
      $header = str_pad($header, 512, "\000");

      #-- calculate and add header checksum
      $cksum = 0;
      for ($n=0; $n<512; $n++) {
         $cksum += ord($header[$n]);
      }
      $header = substr($header, 0, 0x0094)
              . $this->oct($cksum, 7) . " "
              . substr($header, 0x009C);

      #-- output
      if ($fill = (512 - (strlen($content) % 512))) {
         $content .= str_repeat("\000", $fill);
      }
      $this->write($header . $content);
   }
}


class ewiki_virtual_zip 
{ 
	var $datasec = array(); 
	var $ctrl_dir = array(); 
	var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; 
	var $old_offset = 0; 
	
	function add($data, $name, $ignored, $complevel) { 
		$name = str_replace("\\", "/", $name); 
		$unc_len = strlen($data); 
		$crc = crc32($data); 
		$zdata = gzcompress($data, $complevel); 
		$zdata = substr ($zdata, 2, -4); 
		$c_len = strlen($zdata); 
		$fr = "\x50\x4b\x03\x04"; 
		$fr .= "\x14\x00"; 
		$fr .= "\x00\x00"; 
		$fr .= "\x08\x00"; 
		$fr .= "\x00\x00\x00\x00"; 
		$fr .= pack("V",$crc); 
		$fr .= pack("V",$c_len); 
		$fr .= pack("V",$unc_len); 
		$fr .= pack("v", strlen($name) ); 
		$fr .= pack("v", 0 ); 
		$fr .= $name; 
		$fr .= $zdata; 
		$fr .= pack("V",$crc); 
		$fr .= pack("V",$c_len); 
		$fr .= pack("V",$unc_len); 
		
		$this -> datasec[] = $fr; 
		$new_offset = strlen(implode("", $this->datasec)); 
		
		$cdrec = "\x50\x4b\x01\x02"; 
		$cdrec .="\x00\x00"; 
		$cdrec .="\x14\x00"; 
		$cdrec .="\x00\x00"; 
		$cdrec .="\x08\x00"; 
		$cdrec .="\x00\x00\x00\x00"; 
		$cdrec .= pack("V",$crc); 
		$cdrec .= pack("V",$c_len); 
		$cdrec .= pack("V",$unc_len); 
		$cdrec .= pack("v", strlen($name) ); 
		$cdrec .= pack("v", 0 ); 
		$cdrec .= pack("v", 0 ); 
		$cdrec .= pack("v", 0 ); 
		$cdrec .= pack("v", 0 ); 
		$cdrec .= pack("V", 32 ); 
		$cdrec .= pack("V", $this -> old_offset ); 
		
		$this -> old_offset = $new_offset; 
		
		$cdrec .= $name; 
		$this -> ctrl_dir[] = $cdrec; 
	} 
	
	function close() { 
		$data = implode("", $this -> datasec); 
		$ctrldir = implode("", $this -> ctrl_dir); 
		
		return 
			$data . 
			$ctrldir . 
			$this -> eof_ctrl_dir . 
			pack("v", sizeof($this -> ctrl_dir)) . 
			pack("v", sizeof($this -> ctrl_dir)) . 
			pack("V", strlen($ctrldir)) . 
			pack("V", strlen($data)) . 
			"\x00\x00"; 
	} 
}


?><?php
/*
   This snippet implements HTTP queries, and allows for most request
   methods, content types and encodings. It is useful for contacting
   scripts made to serve HTML forms.
    - does neither depend upon wget or curl, nor any other extension
    - you can add ->$params (form variables) on the fly, it's a hash
    - if the initial URL contains a query string, the vars will be
      extracted first
    - set the ->$enc very carefully, because many CGI apps and HTTP
      servers can't deal with it (else "gzip" and "deflate" are nice)
    - there are abbreviations for the content ->$type values (namely
      "form" , "url" and "php")
    - user:password@ pairs may be included in the initially given URL
    - headers always get normalized to "Studly-Caps"
    - won't support keep-alive connections
    - for PUT and other methods, the ->$params var may just hold the
      request body
    - files can be added to the ->params array as hash with specially
      named fields: "content"/"data", and "filename"/"name" , "type"
    - you can add authentication information using the standard notation
      "http://user:passw@www.example.com/..." for ->$url and ->$proxy

   A response object will have a ->$content field, ->$headers[] and
   ->len, ->type attributes as well. You could also ->decode() the
   body, if it is app/vnd.php.serialized or app/x-www-form-urlencoded.
   
   Public Domain (use freely, transform into any other license, like
   LGPL, BSD, MPL, ...; but if you change this into GPL please be so
   kind and leave your users a hint where to find the free version).
*/


#-- request objects
class http_request {

   var $method = "GET";
   var $proto = "HTTP/1.1";
   var $url = "";
   var $params = array();   // URL/form post vars, or single request body str
   var $headers = array();
   var $cookies = array();
   var $type = "url";       // content-type, abbrv. for x-www-form-...
   var $enc = false;        // "gzip" or "deflate"
   var $error="", $io_err=0, $io_err_s="";
   var $active_client = 1;  // enables redirect-following
   var $redirects = 3;
   var $proxy = false;      // set to "http://host:NN/"
   var $timeout = 15;


   #-- constructor
   function http_request($method="GET", $url="", $params=NULL) {
      $this->headers["User-Agent"] = "http_query/17.2 {$GLOBALS[ewiki_config][ua]}";
      $this->headers["Accept"] = "text/html, application/xml;q=0.9, text/xml;q=0.7, xml/*;q=0.6, text/plain;q=0.5, text/*;q=0.1, image/png;q=0.8, image/*;q=0.4, */*+xml;q=0.3; application/x-msword;q=0.001, */*;q=0.075";
      $this->headers["Accept-Language"] = "en, eo, es;q=0.2, fr;q=0.1, nl;q=0.1, de;q=0.1";
      $this->headers["Accept-Charset"] = "iso-8859-1, utf-8";
      $this->headers["Accept-Feature"] = "textonly, tables, !tcpa, !javascript, !activex, !graphic";
      $this->headers["Accept-Encoding"] = "deflate, gzip, compress, x-gzip, x-bzip2";
      //$this->headers["Referer"] = '$google';
      $this->headers["TE"] = "identity, chunked, binary, base64";
      $this->headers["Connection"] = "close";
      //$this->headers["Content-Type"] = & $this->type;
      if (isset($params)) {
         $this->params = $params;
      }
      if (strpos($method, "://")) {
         $url = $method;  # glue for incompat PEAR::Http_Request
         $method = "GET";
      }
      $this->method($method);
      $this->setURL($url);
   }


   #-- sets request method
   function method($str = "GET") {
      $this->method = $str;
   }

   #-- special headers
   function setcookie($str="name=value", $add="") {
      $this->cookies[strtok($str,"=")] = strtok("\000").$add;
   }


   #-- deciphers URL into server+path and query string
   function setURL($url) {
      if ($this->method == "GET") {
         $this->url = strtok($url, "?");
         if ($uu = strtok("\000")) {
            $this->setQueryString($uu);
         }
      }
      else {
         $this->url = $url;
      }
   }
   
   
   #-- decodes a query strings vars into the $params hash
   function setQueryString($qs) {
      $qs = ltrim($qs, "?");
      parse_str($qs, $this->params);
   }


   #-- returns params as querystring for GET requests
   function getQueryString() {
      $qs = "";
      if (function_exists("http_build_query")) {
         $qs = http_build_query($this->params);
      }
      else {
         foreach ($this->params as $n=>$v) {
            $qs .= "&" . urlencode($n) . "=" . urlencode($v);
         }
         $qs = substr($qs, 1);
      }
      return($qs);
   }


   #-- transforms $params into request body
   function pack(&$path) {
      $m = strtoupper($this->method);

      #-- GET, HEAD
      if (($m == "GET") || ($m == "HEAD")) {
         $BODY = "";
         $path .= (strpos($path, "?") ? "&" : "?") . $this->getQueryString();
      }

      #-- POST
      elseif (($m == "POST") && is_array($this->params)) {

         #-- known encoding types
         $type = $this->type($this->type, 0);
         if ($type == "url") {
            $BODY = $this->getQueryString($prep="");
         }
         elseif ($type == "php") {
            $BODY = serialize($this->params);
         }
         elseif ($type == "form") {
            // boundary doesn't need checking, unique enough
            $bnd = "snip-".dechex(time())."-".md5(serialize($this->params))
                 . "-".dechex(rand())."-snap";
            $BODY = "";
            foreach ($this->params as $i=>$v) {
               $ct = "text/plain";
               $inj = "";
               if (is_array($v)) {
                  ($ct = $v["ct"].$v["type"].$v["content-type"]) || ($ct = "application/octet-stream");
                  $inj = ' filename="' . urlencode($v["name"].$v["file"].$v["filename"]) . '"';
                  $v = $v["data"].$v["content"].$v["body"];
               }
               $BODY .= "--$bnd\015\012"
                     . "Content-Disposition: form-data; name=\"".urlencode($i)."\"$inj\015\012"
                     . "Content-Type: $ct\015\012"
                     . "Content-Length: " . strlen($v) . "\015\012"
                     . "\015\012$v\015\012";
            }
            $BODY .= "--$bnd--\015\012";
            $ct = $this->type("form") . "; boundary=$bnd";
         }
         #-- ignore
         else {
            $this->error = "unsupported POST encoding";
          // return(false);
            $BODY = & $this->params;
         }

         $this->headers["Content-Type"] = isset($ct) ? $ct : $this->type($type, 1);
      }

      #-- PUT, POST, PUSH, P*
      elseif ($m[0] == "P") {
         $BODY = & $this->$params;
      }

      #-- ERROR (but don't complain)
      else {
         $this->error = "unsupported request method '{$this->method}'";
       //  return(false);
         $BODY = & $this->params;
      }

      return($BODY);
   }


   #-- converts content-type strings from/to shortened nick
   function type($str, $long=1) {
      $trans = array(
         "form" => "multipart/form-data",
         "url" => "application/x-www-form-urlencoded",
         "php" => "application/vnd.php.serialized",
      );
      $trans["multi"] = &$trans["form"];
      if ($long) {
         $new = $trans[$str];
      }
      else {
         $new = array_search($str, $trans);
      }
      return( $new ? $new : $str );
   }


   #-- initiate the configured HTTP request ------------------------------
   function go($force=0, $asis=0) {

      #-- prepare parts
      $url = $this->prepare_url();
      if (!$url && !$force) { return; }
      $BODY = $this->body($url);
      if (($BODY===false) && !$force) { return; }
      $HEAD = $this->head($url);

      #-- open socket
      if (!$this->connect($url)) {
         return;
      }

      #-- send request data
      fwrite($this->socket, $HEAD);
      fwrite($this->socket, $BODY);
      $HEAD = false;
      $BODY = false;

      #-- read response, end connection
      while (!feof($this->socket) && (strlen($DATA) <= 1<<22)) {
         $DATA .= fread($this->socket, 32<<10);
      }
      fclose($this->socket);
      unset($this->socket);

      #-- for raw http pings
      if ($asis) { 
         return($DATA);
      }

      #-- decode response
      $r = new http_response();
      $r->from($DATA);        // should auto-unset $DATA

      #-- handle redirects
      if ($this->active_client) {
         $this->auto_actions($r);
      }

      #-- fin      
      return($r);
   }

   #-- alias
   function start($a=0, $b=0) { 
      return $this->go($a, $b);
   }
   
   
   #-- creates socket connection
   function connect(&$url) {
      if ((isset($this->socket) and !feof($this->socket))
      or ($this->socket = fsockopen($url["host"], $url["port"], $this->io_err, $this->io_err_s, $this->timeout))) {
         socket_set_blocking($this->socket, true);
         socket_set_timeout($this->socket, $this->timeout, 555);
         return(true);
      }
      else {
         $this->error = "no socket/connection";
         return(false);
      }
   }


   #-- separate URL into pieces, prepare special headers
   function prepare_url() {
      $this->setURL($this->url);
      if (!$this->proxy) {
         $url = parse_url($this->url);
         if (strtolower($url["scheme"]) != "http") {
            $this->error = "unsupported protocol/scheme";
            return(false);
         }
         if (!$url["host"]) { return; }
         if (!$url["port"]) { $url["port"] = 80; }
         if (!$url["path"]) { $url["path"] = "/"; }
         if ($url["query"]) { $url["path"] .= "?" . $url["query"]; }
         $proxy = "";
      }
      else {
         $url = parse_url($this->proxy);
         $url["path"] = $this->url;
         $proxy = "Proxy-";
         $this->headers["Proxy-Connection"] = $this->headers["Connection"];
      }

      #-- inj auth headers
      if ($url["user"] || $url["pass"]) {
         $this->headers[$proxy."Authorization"] = "Basic " . base64_encode("$url[user]:$url[pass]");
      }
      
      return($url);
   }


   #-- generates request body (if any), must be called before ->head()
   function body(&$url) {

      #-- encoding of variable $params as request body (according to reqmethod)
      $BODY = $this->pack($url["path"]);
      if ($BODY === false) {
         return false;
      }
      elseif ($len = strlen($BODY)) {
         $this->headers["Content-Length"] = $len;
      }
      $enc_funcs = array("gzip"=>"gzencode", "deflate"=>"gzinflate", "bzip2"=>"bzcompress", "x-bzip2"=>"bzcompress", "compress"=>"gzcompress");
      if ((strlen($BODY) >= 1024) && ($f = $enc_funcs[$this->enc]) && function_exists($f)) {
         $BODY = $f($BODY);
         $this->headers["Content-Encoding"] = $this->enc;
         $this->headers["Content-Length"] = strlen($BODY);
      }
      return($BODY);
   }


   #-- generates request head part
   function head(&$url) {
   
      #-- inject cookie header (if any)
      if ($this->cookies) {
         $c = "";
         foreach ($this->cookies as $i=>$v) {
            $c .= "; " . urlencode($i) . "=" . urlencode($v);
         }
         $this->headers["Cookie"] = substr($c, 2);
         $this->headers["Cookie2"] = '$Version="1"';
      }
      
      #-- request head
      $CRLF = "\015\012";
      $HEAD  = "{$this->method} {$url[path]} {$this->proto}$CRLF";
      $HEAD .= "Host: {$url[host]}$CRLF";
      foreach ($this->headers as $h=>$v) {
         $HEAD .= trim($h) . ": " . strtr(trim($v), "\n", " ") . $CRLF;
      }
      $HEAD .= $CRLF;
      return($HEAD);
   }

   #-- perform some things automatically (redirects)
   function auto_actions(&$r) {

      #-- behaviour table
      static $bhv = array(
         "failure" => "204,300,304,305,306",
         "clean_::POST" => "300,301,302,303,307",
         "clean_::PUT" => "300,301,302,303,307",
         "clean_::GET" => "300",  // $params:=undef
         "GET_::POST" => "303",
         "GET_::PUT" => "303",    // downgrade $method:=GET
      );
   
      #-- failure
      if (strstr($this->behaviour_table["failure"], $r->status)) {
         return;
      }

      #-- HTTP redirects
      if (($pri_url=$r->headers["Location"]) || ($pri_url=$r->headers["Uri"])) {

         if ((($this->redirects--) >= 0) && ($r->status >= 300) && ($r->status < 400)) {
            $m = strtoupper($this->method);
            if (strstr($this->behaviour_table["clean_::$m"], $r->status)) {
               unset($this->params);
            }
            if (strstr($this->behaviour_table["GET_::$m"], $r->status)) {
               $this->method("GET");
            }
            $this->setURL($pri_url);
            $this->go();
         }
      }
   }
   
   #-- aliases for compatiblity to PEAR::HTTP_Request
   function sendRequest() {
      return $this->go();
   }
   function setBasicAuth($user, $pw) {
      $this->url = preg_replace("#//(.+?@)?#", "//$user@$pw", $this->url);
   }
   function setMethod($m) {
      $this->method($m);
   }
   function setProxy($host, $port=8080, $user="", $pw="") {
      $auth = ($pw ? "$user:$pw@" : ($user ? "$user@" : ""));
      $this->proxy = "http://$auth$server:$port";
   }
   function addHeader($h, $v) {
      $this->headers[$h] = $v;
   }
   function getResponseStatus() {
      $this->headers[$h] = $v;
   }
}
class http_query extends http_request {
   /* this is just an alias */
}




#-- every query result will be encoded in such an object --------------------
class http_response {

   var $status = 520;
   var $status_str = "";
   var $headers_str = "";
   var $headers = array();
   var $len = 0;
   var $type = "message/x-raw";
   var $content = "";
   
   
   function http_response() {
   }
   

   #-- fill object from given HTTP response BLOB   
   function from(&$SRC) {
      $this->breakHeaders($SRC);  // split data into body + headers
      $SRC = false;
      $this->decodeHeaders();     // normalize header names
      $this->headerMeta();
      $this->decodeTransferEncodings();    // chunked
      $this->decodeContentEncodings();     // gzip, deflate
      $this->len = strlen($this->content);
   }


   #-- separates headers block from response body part
   function breakHeaders(&$DATA) {
      $l = strpos($DATA, "\012\015\012"); $skip = 3;
      $r = strpos($DATA, "\012\012");
      if ($r && ($r<$l)) { $l = $r; $skip = 2; }
      if (!$l) { $l = strlen($DATA); }
      $this->headers_str = rtrim(substr($DATA, 0, $l), "\015");
      $this->content = substr($DATA, $l + $skip);
      $this->body = & $this->content;
      $this->data = & $this->content;  // aliases
      $this->ct = & $this->type;
   }


   #-- splits up the $headers_str into an array and normalizes header names
   function decodeHeaders() {

      #-- normalize linebreaks
      $str = & $this->headers_str;
//      $str = str_replace("\n ", " ", $str);
      $str = str_replace("\r", "", $str);
      
      #-- strip headline
      $nl = strpos($str, "\n") + 1;
      $this->proto = strtok(substr($str, 0, $nl), " ");
      $this->status = (int) strtok(" ");
      $this->status_str = strtok("\000\r\n");
      if ($this->status == 100) {
         $this->full_duplex = 1;
      }

      #-- go through lines, split name:value pairs
      foreach (explode("\n", substr($str, $nl)) as $line) {

         $i = trim(strtok($line, ":"));
         $v = trim(strtok("\000"));

         #-- normalize name look&feel
         $i = strtr(ucwords(strtolower(strtr($i, "-", " "))), " ", "-");

         #-- add to, if key exists
         if (!empty($this->headers[$i])) {
            $this->headers[$i] .= ", ".$v;
         }
         else {
            $this->headers[$i] = $v;
         }

      }
   }


   #-- extract interesting values
   function headerMeta() {
      $this->len = strlen($this->content);
      $this->type = trim(strtok(strtolower($this->headers["Content-Type"]), ";"));
   }
   

   #-- strip any content transformation
   function decodeTransferEncodings() {
      $enc = trim(strtok(strtolower($this->headers["Transfer-Encoding"]), ",;"));
      if ($enc) {
         switch ($enc) {
            case "chunked":
               $this->decodeChunkedEncoding();
               break;
            case "base64":
               $this->content = base64_decode($this->content);
               $this->len = strlen($this->content);
               break;
            case "identity": case "binary":
            case "7bit": case "8bit":
               break;
            default:
               trigger_error("http_response::decodeTransferEncodings: unkown TE of '$enc'\n", E_WARNING);
         }
      }
   }


   #-- scripts on HTTP/1.1 servers may send fragmented response
   function decodeChunkedEncoding() {

      $data = "";	# decoded data
      $p = 0;		# current string position

      while ($p < strlen($this->content)) {

         #-- read len token
         $n = strtok(substr($this->content, $p, 20), "\n");
         $p += strlen($n)+1;

         #-- make integer
         $n = 0 + (int) (trim($n));
         if (!$n) {
            break;
         }

         #-- read data
         $data .= substr($this->content, $p, $n);
         $p += $n;
      }

      $this->content = $data;
      unset($data);
      $this->len = strlen($this->content);
   }


   #-- uncompress response body
   function decodeContentEncodings() {
      $enc = trim(strtok(strtolower($this->headers["Content-Encoding"]), ";,"));
      $dat = &$this->content;
      if ($enc == "deflate") {
         $dat = gzinflate($dat);
      }
      elseif (($enc == "gzip") || ($enc == "x-gzip")) {
         if (function_exists("gzdecode")) {
            $dat = gzdecode($dat);
         }
         else {
            $dat = gzinflate(substr($dat, 10, strlen($dat)-18));
         }
      }
      elseif ($enc == "compress") {
         $dat = gzuncompress($dat);
      }
      elseif (($enc == "x-bzip2") || ($enc == "bzip2")) {
         if (function_exists("bzdecompress")) {
            $dat = bzdecompress($dat);
         }
         else trigger_error("http_response::decodeContentEncoding: bzip2 decoding isn't supported with this PHP interpreter version", E_WARNING);
      }
      $this->len = strlen($this->content);
   }


   #-- can handle special content-types (multipart, serialized, form-data)
   function decode() {
      $t = http_request::type($this->type, 0);
      if ($t == "php") {
         return(unserialize($this->content));
      }
      elseif ($t == "url") {
         parse_str($this->content, $r);
         return($r);
      }
      elseif ($t == "form") {
         // oh, not yet exactly
      }
   }

   #-- aliases for compatiblity to PEAR::HTTP_Request
   function getResponseBody() {
      return $this->content;
   }
   function getResponseStatus() {
      return $this->status;
   }
   function getResponseCode() {
      return $this->status;
   }
   function getResponseHeader($i=NULL) {
      if (!isset($i)) {
         return $this->headers;
      }
      $i = strtolower($i);
      foreach ($this->headers as $h=>$v) {
         if (strtolower($h)==$i) {
            return $v;
         }
      }
   }
}



?>
<?php

/*
  This plugin provides per-page administrative functions, for easier access
  to some settings and tools. Currently supports page renaming and page flag
  setting.
  requires _PROTECTED_MODE, see ewiki_auth() in README, and $ewiki_ring==0

  The functions have following ring permission level equirements:
     delete: ring<=1 moderators
     rename: ring<=1 moderators
     meta:   ring=0 admins
     flags:  ring=0 admins, moderators may change just some flags

  For styling purposes following CSS selectors could be used:
    .wiki.control  .flags  {...}
    .wiki.control  .rename  {...}
    .wiki.control  .meta  {...}
    .wiki.control  .delete  {...}
*/


#-- which flags moderators may change
define("EWIKI_DB_F_MODERATORFLAGS",  0x0070 | 0x0004 | 0x0008);
                             # == EWIKI_DB_F_ACCESS | EWIKI_DB_F_DISABLED | EWIKI_DB_F_HTML


#-- glue
$ewiki_plugins["action"]["control"] = "ewiki_action_control_page";
$ewiki_config["action_links"]["view"]["control"] = "Page Control";


#-- implementation
function ewiki_action_control_page($id, &$data, $action) {
   global $ewiki_ring, $ewiki_config, $ewiki_plugins;

   $a_flagnames = array(
      "_TEXT", "_BINARY", "_DISABLED", "_HTML", "_READONLY", "_WRITEABLE",
      "_APPENDONLY", "_SYSTEM", "_PART", "_MINOR", "_HIDDEN", "_ARCHIVE",
      "_UU12", "_UU13", "_UU14", "_UU15", "_UU16", "_EXEC", "_UU18", "_UU19",
   );
   

   $o = ewiki_make_title($id, "control $id", 2);

   #-- admin requ. ---------------------------------------------------------
   if (!ewiki_auth($id,$data,$action, $ring=0, "_FORCE_LOGIN=1") || !isset($ewiki_ring) || ($ewiki_ring > 1)) {

      if (is_array($data)) {
         $data = "You'll need to be admin. See ewiki_auth() and _PROTECTED_MODE in the README.";
      }
      $o .= $data;
     
   }

   #-- page flags ---------------------------------------------------------
   elseif (@$_REQUEST["pgc_setflags"]) {

      #-- setted new flags
      $new_f = 0;
      foreach ($_REQUEST["sflag"] as $n=>$b) {
         if ($b) {
            $new_f |= (1 << $n);
         }
      }
      #-- administrator may change all flags
      if ($ewiki_ring==0) {
         $data["flags"] = $new_f;
      }
      #-- moderators only a few
      else {
         $data["flags"] = ($data["flags"] & ( ~ EWIKI_DB_F_MODERATORFLAGS))
                        | ($new_f & EWIKI_DB_F_MODERATORFLAGS);
      }
      $data["lastmodified"] = time();
      $data["version"]++;

      if (ewiki_db::WRITE($data)) {
         $o .= "Page flags were updated correctly.";
         ewiki_log("page flags of '$id' were set to $data[flags]");
      }
      else {
         $o .= "A database error occoured.";
      }
   }

   #-- renaming -----------------------------------------------------------
   elseif  (@$_REQUEST["pgc_rename"] && strlen($new_id = $_REQUEST["mv_to"])) {

      $old_id = $id;
      $report = "";

      $preg_id = "/". addcslashes($old_id, ".+*?|/\\()$[]^#") ."/"
                 . ($_REQUEST["mv_cr1"] ? "i" : "");

      #-- check if new name does not already exist in database
      $exists = ewiki_db::GET($new_id);
      if ($exists || !empty($exists)) {
         return($o .= "Cannot overwrite an existing database entry.");
      }

      #-- copy from old name to new name
      $max_ver = $data["version"];
      $data = array();
      for ($v=1; $v<=$max_ver; $v++) {

         $row = ewiki_db::GET($old_id, $v);
         $row["id"] = $new_id;
         $row["lastmodified"] = time();
         $row["content"] = preg_replace($preg_id, $new_id, $row["content"]);
         ewiki_scan_wikiwords($row["content"], $links, "_STRIP_EMAIL=1");
         $row["refs"] = "\n\n".implode("\n", array_keys($links))."\n\n";
         $row["author"] = ewiki_author("control/");

         if (!ewiki_db::WRITE($row)) {
            $report .= "error while copying version $v,<br />\n";
              
         }
      }

      #-- proceed if previous actions error_free
      if (empty($report)) {

         #-- deleting old versions
         for ($v=1; $v<=$max_ver; $v++) {
            ewiki_db::DELETE($old_id, $v);
         }

         #-- adjust links/references to old page name
         if ($_REQUEST["mv_cr0"]) {

            $result = ewiki_db::SEARCH("refs", $old_id);
            while ($result && ($row = $result->get())) {

               $row = ewiki_db::GET($row["id"]);

               if (preg_match($preg_id, $row["content"], $uu)) {

                  $row["content"] = preg_replace($preg_id, $new_id, $row["content"]);
                  $row["lastmodified"] = time();
                  $row["version"]++;
                  ewiki_scan_wikiwords($row["content"], $links, "_STRIP_EMAIL=1");
                  $row["refs"] = "\n\n".implode("\n", array_keys($links))."\n\n";
                  $row["author"] = ewiki_author("control/");

                  if (!ewiki_db::WRITE($row)) {
                     $report .= "could not update references in ".$row['id'].",<br />\n";
                  } 
                  else {
                     $report .= "updated references in ".$row['id'].",<br />\n";
                  }
               }

            }

         }

         $o .= "This page was correctly renamed from '$old_id' to '$new_id'.<br /><br />\n$report";
         ewiki_log("page renamed from '$old_id' to '$new_id'", 2);

      }
      else {

         $o .= "Some problems occoured while processing your request, therefor the old page still exists:<br />\n" . $report;
      }

   }

   #-- meta data -----------------------------------------------------------
   elseif (@$_REQUEST["pgc_setmeta"] && ($ewiki_ring==0) && ($set = explode("\n", $_REQUEST["pgc_meta"]))) {

      $new_meta = array();
      foreach ($set as $line) {
         if (($line=trim($line)) && ($key=trim(strtok($line, ":"))) && ($value=trim(strtok("\000"))) ) {
            $new_meta[$key] = $value;
         }
      }

      $data["meta"] = $new_meta;
      $data["lastmodified"] = time();
      $data["version"]++;

      if (ewiki_db::WRITE($data)) {
         $o .= "The {meta} field was updated.";
      }
      else {
         $o .= "A database error occoured.";
      }
   }

   #-- deletion -----------------------------------------------------------
   elseif (@$_REQUEST["pgc_purge"] && $_REQUEST["pgc_purge1"]) {

      $loop = 3;
      do {
         $verZ = $data["version"];
         while ($verZ > 0) {
            ewiki_db::DELETE($id, $verZ);
            $verZ--;
         }
      } while ($loop-- && ($data = ewiki_db::GET($id)));

      if (empty($data)) {
         $o .= "Page completely removed from database.";
         ewiki_log("page '$id' was deleted from db", 2);
      }
      else {
         $o .= "Page still here.";
      }
   }

   #-- function list -------------------------------------------------------
   else {
      $o .= '<form action="'.ewiki_script("$action",$id).'" method="POST" enctype="text/html">'
          . '<input type="hidden" name="id" value="'."$action/$id".'">';

      #-- flags
      $o .= '<div class="flags">';
      $o .= "<h4>page flags</h4>\n";
      foreach ($a_flagnames as $n=>$s) {
         $disabled = (($ewiki_ring==1) && !((1<<$n) & EWIKI_DB_F_MODERATORFLAGS)) ? ' disabled="disabled"' : "";
         $checked = $data["flags"] & (1<<$n) ? ' checked="checked"': "";
         $a[$n] = '<input type="checkbox" name="sflag['.$n.']" value="1"'.
               $checked . $disabled .'> ' . $s;
      }
      $o .= '<table border="0" class="list">' . "\n";
      for ($n=0; $n<count($a_flagnames); $n++) {
         $y = $n >> 2;
         $x = $n & 0x03;
         if ($x==0) $o .= "<tr>";
         $o .= "<td>" . $a[4*$y + $x] . "</td>";
         if ($x==3) $o .= "</tr>\n";
      }
      $o .= '</table>';
      $o .= '<input type="submit" name="pgc_setflags" value="chmod">';
      $o .= "\n<br /><br /><hr></div>\n"; 

      #-- rename
      $o .= '<div class="rename">';
      $o .= "<h4>rename page</h4>\n";
      $o .= 'new page name: <input type="text" size="30" name="mv_to" value="'.htmlentities($id).'">'
          . '<br />'
          . '<input type="checkbox" name="mv_cr0" value="1" checked> also try to change all references from other pages accordingly '
          . '(<input type="checkbox" name="mv_cr1" value="1" checked> and act case-insensitive when doing so) ';
      $o .= '<br /><input type="submit" name="pgc_rename" value="mv">';
      $o .= "\n<br /><br /><hr></div>\n"; 

      #-- meta
      if (isset($ewiki_ring) && ($ewiki_ring==0)) {
      $o .= '<div class="meta">';
      $o .= "<h4>meta data</h4>\n";
      $o .= '<textarea cols="40" rows="6" name="pgc_meta">';
      if (($uu = @$data["meta"]) && is_array($uu))
      foreach ($uu as $key=>$value) {
         if (is_array($value)) { $value = serialize($array); }
         $o .= htmlentities($key.": ".trim($value)) . "\n";
      }
      $o .= "</textarea>\n";
      $o .= '<br /><input type="submit" name="pgc_setmeta" value="set">';
      $o .= "\n<br /><br /><hr></div>\n"; 
      }

      #-- delete
      $o .= '<div class="delete">';
      $o .= "<h4>delete page</h4>\n";
      $o .= '<input type="checkbox" name="pgc_purge1" value="1"> I\'m sure';
      $o .= '<br /><input type="submit" name="pgc_purge" value="rm">';
      $o .= "\n<br /><br /><hr></div>\n"; 

      $o .= '</form>';
   }

   return($o);
}



?>
<?php

/*
   -- OBSOLETED by according spages/ plugin --
   lists pages which were referenced but not yet written
*/


$ewiki_plugins["page"]["WantedPages"] = "ewiki_page_wantedpages";
#<off># $ewiki_plugins["page"]["DanglingSymlinks"] = "ewiki_page_wantedpages";


function ewiki_page_wantedpages($id, $data, $action) {

    #-- collect referenced pages
    $result = ewiki_db::GETALL(array("refs"));
    while ($row = $result->get()) {
        if (EWIKI_PROTECTED_MODE && EWIKI_PROTECTED_MODE_HIDING && !ewiki_auth($row["id"], $uu, "view")) {
            continue;
        }   
        $refs .= $row["refs"];
    }

   #-- build array
   $refs = array_unique(explode("\n", $refs));

   #-- strip existing pages from array
   $refs = ewiki_db::FIND($refs);
    foreach ($refs as $id=>$exists) {
        if (EWIKI_PROTECTED_MODE && EWIKI_PROTECTED_MODE_HIDING && !ewiki_auth($row["id"], $uu, "view")) {
            continue;
        }   
        if (!$exists && !strstr($id, "://") && strlen(trim($id))) {
            $wanted[] = $id;
        }
    }

   #-- print out
   $o .= "<ul>\n";
   foreach ($wanted as $page) {

      $o .= "  <li>" . ewiki_link($page) . "</li>\n";

   }
   $o .= "<ul>\n";

   return($o);
}


?>
<?php


 # this plugin does spell checking via a real "ispell" (based upon
 # the orig. spellcheck plugin)
 # Andy Fundinger (@burgiss.com)



 $ewiki_spellcheck_language = "en";
 $ewiki_plugins["edit_preview"][0] = "ewiki_page_edit_preview_spellcheck";




 function ewiki_page_edit_preview_spellcheck($data) {

    $html .= ewiki_page_edit_preview($data);
	
    ewiki_spellcheck_init($GLOBALS["ewiki_spellcheck_language"]);

    $regex = '(<.+?>)|([\w]{2,256})';  //Word characters only to prevent shell relevent characters
    preg_match_all("/".$regex."/", $html, $words);
    $words = $words[2];
    $replacements = ewiki_spellcheck_list($words);

    $html = preg_replace("/$regex/e", ' ( empty($replacements["$2"]) ? "$1$2" : "$1".$replacements["$2"] ) ', $html);

	
    return($html);

 }



 function ewiki_spellcheck_init($lang="en") {
    global $spell_bin;
    $spell_bin="ispell -a -S -C ";
 }



 function ewiki_spellcheck_list($ws) {

    global $spell_bin;

    #-- every word once only
    $words = array();
    foreach (array_unique($ws) as $word) {
       if (!empty($word)) {
          $words[] = $word;
       }
    }

    #-- via ispell binary
    if ($spell_bin) {

       #-- pipe word list through ispell
       $r = implode(" ", $words);

       $results = explode("\n", $r=shell_exec("echo $r | $spell_bin"));
       $results = array_slice($results, 1);

    }

    #-- build replacement html hash from results
    $r = array();

      foreach ($results as $currline) {

		switch ($currline[0]) {
             case "-":
             case "+":
             case "*":
		//unset($repl);
                //$repl = "{".$word."}";
                break;

             default:
			 	//set word to the first word in the line form is * <WORD> ## ### ....
			 	preg_match('/. (.*?) .*/',$currline,$temp);
				$word= $temp[1];
                $repl = '<s title="'. htmlentities($currline) .'" style="color:#ff5555;" class="wrong">'.$word.'</s>';
		        $r[$word] = $repl;

          }
       }
     
 
    return($r);
 }

$ewiki_plugins["format_final"][] = "ewiki_break_headings";
function ewiki_break_headings(&$html) {
	$html = preg_replace('#(</h[1-6]>)#', '\\1<br />', $html);
}

?>
