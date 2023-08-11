<?php lock();

/**
* Init
*
* Some inital garden :)
*
* @package	UCPanel
* @author	wintercounter
* @copyright	Copyright (c) 2013 Viktor Vincze
* @link		http://expression-themes.com
* @since	Version 1.0
*/

global $conf;

require_once 'config.php';

$conf['page']             = 'home';
$conf['inited_module']    = '';
$conf['db_inited']        = FALSE;
$conf['logged_in']        = FALSE;
$conf['registered_methods'] = array();
$conf['installed']        = file_exists($conf['user_path'].'cache/system/installed.tmp');
$LANG                     = array();
$conf['language'] = 'en_US';
$conf['system_language'] = 'en_US';

// Set language cookie
if(isset($_COOKIE['ucp_lang']) && stripos($_COOKIE['ucp_lang'],"/") !== false){
	
	$_COOKIE['ucp_lang'] = stripslashes($_COOKIE['ucp_lang']);

	$conf['language'] = (is_dir($conf['full_path'].$conf['system_dir'].'/language/'.$_COOKIE['ucp_lang'])) ? $_COOKIE['ucp_lang'] : 'en_US';
	
}

// Load Core
loader('core', 'class', FALSE);

// Init system language and load
if($conf['installed']){
	
	loader('database', 'class');
	
	$lang = $conf['db_inited']->get_var("SELECT value FROM preferences WHERE key = 'language'");
	$conf['language'] = $lang;
	$conf['system_language'] = $lang;
}
else{
	// Install DB pls!
	if(isset($_POST['installaction']) && $_POST['installaction'] == '1' && !file_exists($conf['user_path'].'cache/system/installed.tmp')){
		
		if(!file_exists($conf['db_path'] . $conf['db_name'] . '.db')){
			new SQLiteDatabase($conf['db_path'] . $conf['db_name'] . '.db', 0777);
		}
		
		loader('database','class');
		
		$conf['db_inited']->query('CREATE TABLE IF NOT EXISTS "preferences" (
			"id" integer NOT NULL PRIMARY KEY,
			"key" text NOT NULL,
			"value" text NOT NULL
		      )');
		
		$conf['db_inited']->query('CREATE TABLE IF NOT EXISTS "installed" (
			"name" text NOT NULL
		      )');
		
		$conf['db_inited']->query('INSERT INTO preferences ("key", "value") VALUES ("language","en_US")');
	}
}

loader('database', 'class');

// Load Core Language
loader('core', 'language');

$controller = defined('admin') ? 'admin' : 'home';
$controller = (!$conf['installed'] || isset($_POST['installaction']) && $_POST['installaction'] == '1') ? 'install' : $controller;

// Init the controller
load_controller($controller);


// Common functions

// Loader function
// type = Module, Class, Language (All In Lowercase!)
function loader($modName, $type, $createInstance = TRUE, $sub = FALSE) {
	
	global $conf, $LANG;
	
	$initName = $modName;
	$modName  = strtolower($initName);
	
	// Does the mod already loaded?  If so, we're done...		
	if (isset($conf['inited_' . $type][$modName]) && !$sub) {
		return $conf['inited_' . $type][$modName];
	}
	elseif(isset($conf['inited_' . $type][$modName.'_'.$sub]) && $sub){
		return $conf['inited_' . $type][$modName.'_'.$sub];
	}
	
	$loaded = FALSE;
	
	if ($type == 'module') {
		$path_to_file = $conf['full_path'] . $conf['system_dir'] . '/' . $type . '/' . $modName . '/';
	}
	else{
		$path_to_file = $conf['full_path'] . $conf['system_dir'] . '/' . $type . '/';
	}
	
	if (file_exists($path_to_file . $modName . '.' . $type . '.php')) {
		
		include_once $path_to_file . $modName . '.' . $type . '.php';
		$loaded = TRUE;
		
		if ($sub && file_exists($path_to_file . $modName . '.' . $sub . '.' . $type . '.php')) {
			include_once $path_to_file . $modName . '.' . $sub . '.' . $type . '.php';
		}
	
	}
	
	// Load mod language if exists
	$load_lang = false;
	
	$lang_path = $conf['system_path'] . 'language/' . $conf['system_language'] . '/' . $modName . '.language.php' ;
	
	$load_lang = (file_exists($lang_path)) ? $lang_path : false;
		
	if ($load_lang) {
		include_once $load_lang;
		$loaded = TRUE;
	}
	
	// Did we find the class?
	if ($loaded === FALSE) {
		exit('No such ' . $type . ': '.$modName);
	}
	
	switch ($type) {
		case 'module': $initName = $initName . '_Module'; break;
		case 'class': $initName = $initName . '_Class'; break;
		default:break;
	}
	
	// Init new class
	if ($createInstance && $modName != 'core') {
		
		if(!isset($conf['inited_' . $type][$modName])){
			$conf['inited_' . $type][$modName] = new $initName();
		}
		
		if($sub){
			$initName = $initName.'_'.$sub;
			$conf['inited_' . $type][$modName.'_'.$sub] = new $initName();
		}
		
		$conf['inited_' . $type][$modName];
		
	}
}

// Replace variable placeholder in languge string
function change_lang($source, $replace) {
	$out = preg_replace('/{VAR}/', $replace, $source, 1);
	return $out;
}

// Controller loader function
function load_controller($page_name) {
	
	global $conf;
	
	$path = $conf['system_path'] . "controller/" . $page_name . ".controller.php";
	
	if (file_exists($path)) {
		include_once($path);
		$p = ucfirst($page_name);
		return new $p();
	}
	else {
		header("Location: " . $conf['full_url']);
	}
	
}

// Shorthand for vardump+exit
function ev($var = false){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
	exit();
}

?>