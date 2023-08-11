<?php lock();

/**
* System Core
*
* Core related functions
*
* @package	UCPanel
* @category	Core
* @author	wintercounter
* @copyright	Copyright (c) 2013 Viktor Vincze
* @link		http://expression-themes.com
* @since	Version 1.0
*/

abstract class Core {
	
	public 	$theme_supports = false,
		$home_css = '',
		$home_js = '',
		$admin_css = '',
		$admin_js = '';
	
	/**
	 * Creates support for things :)
	 */
	
	final public function add_support($arr) {
		
		$this->theme_supports = $arr;
		
	}
	
	/**
	 * Getter for supports
	 */
	
	final public function get_support() {
		
		return $this->theme_supports;
		
	}
	
	/**
	 * Get config item
	 *
	 * @access public
	 * @param string $item Array index
	 * @param string $item2 Sub-Array index
	 * @return string|bool
	 */
	
	final public function config($item = FALSE, $item2 = FALSE) {
		
		global $conf;
		
		if ($item2) {
			if (isset($conf[$item][$item2]))
				return $conf[$item][$item2];
			else
				return FALSE;
		} elseif ($item) {
			if (isset($conf[$item]))
				return $conf[$item];
			else
				return FALSE;
		} else
			return $conf;
		
	}
	
	/**
	 * Write config
	 *
	 * @access public
	 * @param string $item Array index
	 * @param string $value Array Elems Value
	 * @param string $item2 2nd dimension key
	 */
	
	final public function write_config($item, $value, $item2 = FALSE) {
		
		global $conf;
		
		if ($item2) {
			$conf[$item][$item2] = $value;
		} else {
			$conf[$item] = $value;
		}
		
	}
	
	final public function pref($item, $value = NULL){
		
		if($value !== NULL){
			
			if($this->DB()->get_var("SELECT COUNT(key) FROM preferences WHERE key = '$item'") != 0){
				
				$this->DB(true)->prepare('UPDATE preferences SET value = ? WHERE key = ?')->execute(array($value, $item));
				
			}else{
				
				$this->DB(true)->prepare("INSERT INTO preferences (key, value) VALUES (?,?)")->execute(array($item, $value));
				
			}

			return true;
			
		}

		return @$this->DB()->get_var("SELECT value FROM preferences WHERE key = '$item'");

	}
	
	/**
	 * Get language item
	 *
	 * @access public
	 * @param string $item Array index
	 * @param string $item2 2nd dimension key
	 * @return string
	 */
	
	final public function lang($item = FALSE, $item2 = FALSE) {
		global $LANG;
		if ($item2)
			return $LANG[$item][$item2];
		elseif ($item)
			return $LANG[$item];
		else
			return $LANG;
	}
	
	/**
	 * Load language file
	 *
	 * @access public
	 * @param string $lang
	 * @return void
	 */
	
	final public function load_lang($lang){
		
		global $LANG;
		
		// Load mod language if exists
		$load_lang = false;
		
		$lang_path = $this->config('system_path') . 'language/' . $this->config('language') . '/' . $lang . '.language.php' ;
		
		$load_lang = (file_exists($lang_path)) ? $lang_path : false;
			
		if ($load_lang) {
			include_once($load_lang);
		}

	}
	
	
	/**
	 * Check if something is installed
	 *
	 * @access public
	 * @param string $key
	 * @param bool $add_entry
	 * @return bool
	 */
	
	final public function installed($key, $add_entry = false){
		
		if($add_entry && !$this->installed($key)){
			$this->DB()->query('INSERT INTO installed ("name") VALUES ("'.$key.'")');
		}
		else{
			return ($this->DB()->get_var("SELECT count(name) FROM installed WHERE name = '$key'") != 0) ? true : false;
		}

	}
	
	/**
	 * Cookie setter
	 *
	 * @access public
	 * @param string $name Cookie name
	 * @param numeric $time Cookie valid time
	 * @param string $value Cookie Value
	 */
	
	final public function set_cookie($name, $time, $value = 1, $https = "0", $path = '/') {
		
		$fulltime = time() + $time;
		
		$domain = '.'.$this->url2domain($this->current_uri());
		
		$this->write_config('cookie_domain', $domain);
	
		setcookie($name, $value, $fulltime, '/', $domain);
	
	}
	
	/**
	 * Get safe POST data
	 *
	 * @access public
	 * @param string $name POST field name
	 * @param numeric $type noBooo functions parameter
	 * @param string $value Cookie Value
	 * @return string|array|bool
	 */
	
	final public function POST($name = FALSE, $type = 1) {
		if ($name) {
			if (isset($_POST[$name]))
				return $this->noBooo($_POST[$name], $type);
			else
				return FALSE;
		} else {
			if (isset($_POST)) {
				$arr = array();
				foreach ($_POST as $key => $value) {
					$arr[$key] = $this->noBooo($value, $type);
				}
				return $arr;
			} else
				return FALSE;
		}
	}
	
	/**
	 * Get safe GET data
	 *
	 * @access public
	 * @param string $name GET field name
	 * @return string|bool
	 */
	
	final public function GET($name) {
		if (isset($_GET[$name]))
			return $this->noBooo($_GET[$name]);
		else
			return FALSE;
	}
	
	/**
	 * Get safe COOKIE data
	 *
	 * @access public
	 * @param string $name COOKIE name
	 * @return string|bool
	 */
	
	final public function COOKIE($name) {
		if (isset($_COOKIE[$name]))
			return $this->noBooo($_COOKIE[$name]);
		else
			return FALSE;
	}
	
	/**
	 * Helps U 2 make UR data inputs safe (You may change the $this->mysql_escape_mimic calls to mysql_real_escape function as it's a safer but always requires database connection!)
	 *
	 * @access public
	 * @param string|array $target Value which needs to be safe
	 * @param string $type Not used anymore, left there for comaptibility
	 * @return string
	 */
	
	final public function noBooo($target, $type = 1) {
		
		if (!is_array($target)) {
			
			$target = trim($target);
			
			$target = $this->mysql_escape_mimic($target);
			
		}
		else {
			
			foreach ($target as $key => $value) {
				
				$target[$key] = $this->noBooo($value);
				
			}
			
		}
		
		return $target;
	
	}
	
	/**
	 * mysql_real_escape replacement function, as this not needs mysql connection to be inited (DANGEROUS! Use noBooo instead which uses native escape)
	 *
	 * @access public
	 * @param string $inp Value
	 * @return string
	 */
	
	final public function mysql_escape_mimic($input) {
		if (is_array($input))
			return array_map(__METHOD__, $input);
		
		if (!empty($input) && is_string($input)) {
			return str_replace(array(
				'\\',
				"\0",
				"\n",
				"\r",
				"'",
				'"',
				"\x1a"
			), array(
				'\\\\',
				'\\0',
				'\\n',
				'\\r',
				"\\'",
				'\\"',
				'\\Z'
			), $input);
		}
		
		return $input;
	}
	
	/**
	 * Checks if a module exists
	 *
	 * @access public
	 * @param string $mod The module
	 * @return bool
	 */
	
	final public function is_module($mod) {
		
		return file_exists($this->config('system_path') . '/module/' . $required_mod . '/' . $required_mod . '.module.php');
		
	}
	
	/**
	 * Module shorthand
	 *
	 * @access public
	 * @param string $name Module name
	 * @param string $getInstance Do we need to initialize as new()? Default: TRUE
	 * @return object
	 */
	
	final public function module($name, $sub = FALSE, $getInstance = TRUE) {
		$subs = ($sub) ? '_'.$sub : FALSE;
		if(!$this->config('inited_module', $name.$subs)){
			loader($name, 'module', $getInstance, $sub);
		}
		return $this->config('inited_module', $name.$subs);
	}

	
	/**
	 * Class shorthand
	 *
	 * @access public
	 * @param string $name Class name
	 * @param string $getInstance Do we need to initialize as new()? Default: TRUE
	 * @return object
	 */
	
	final public function klass($name, $sub = FALSE, $getInstance = TRUE) {
		
		$subs = ($sub) ? '_'.$sub : FALSE;
		
		if(!$this->config('inited_class', $name.$subs)){
			
			loader($name, 'class', $getInstance, $sub);
			
		}
		
		return $this->config('inited_class', $name.$subs);
	
	}
	
	/**
	 * Database shorthand
	 *
	 * @access public
	 * @return object
	 */
	
	final public function DB($use_pdo = false){
		
		if($use_pdo && $this->config('db_inited')){
			
			return $this->config('db_inited')->dbh;
			
		}
		
		return $this->config('db_inited');
	}
	
	/**
	 * Config item checker
	 *
	 * @access public
	 * @param string $item Key1
	 * @param string $item2 Key2
	 * @return bool
	 */
	
	final public function config_isset($item = FALSE, $item2 = FALSE) {
		global $conf;
		if ($item2)
			return isset($conf[$item][$item2]);
		elseif ($item)
			return isset($conf[$item]);
		else
			return isset($conf);
	}
	
	/**
	 * Get current URL
	 *
	 * @access public
	 * @return string
	 */
	
	final public function current_uri() {
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s";
		}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return rtrim($pageURL, '/');
	}
	
	/**
	 * Hook shorthand
	 *
	 * @access public
	 * @param string|bool $type Look for specified hook. Default: false
	 * @return object
	 */
	
	final public function hook($type = false){
		if ($type)
			$this->load_hooks($type);
		return $this->klass('hook');
	}
	
	/**
	 * Hook loader
	 *
	 * @access public
	 * @param string|bool $type Look for specified hook.
	 */
	
	final public function load_hooks($type) {
		if (!$this->config('loaded_hook', $type)) {
			$path = $this->config('system_path');
			if($files = glob($path . 'module/*/hooks.'.$type.'.php')){
				foreach ($files as $file) {
					include_once($file);
				}
			}
			$this->write_config('loaded_hook', true, $type);
		}
	}
	
	/**
	 * Graceful quit as exit or die is evil! Causes memory leak!!!
	 * TODO This function also helps you to create valid AJAX responses
	 *
	 * @access public
	 */
	
	final public function quit($msg = FALSE) {
			
		global $conf, $LANG;
		
		//Free up some memory
		$conf=NULL; unset($conf);
		$LANG=NULL; unset($LANG);
		
		exit($msg);
		
	}	
	
	/**
	 * Template manager
	 *
	 * @access public
	 * @return object
	 */
	
	final public function OUT() {
		
		if(!$this->config('inited_class', 'raintpl')){
			loader('raintpl', 'class');
		}
		
		return $this->config('inited_class', 'raintpl');
	
	}
	
	/**
	 * Shortcut for panel class
	 *
	 * @access public
	 * @return object
	 */
	
	final public function PANEL() {
		
		if(!$this->config('inited_class', 'panel')){
			loader('panel', 'class');
		}
		
		return $this->config('inited_class', 'panel');
	
	}
	
	/**
	 * Shortcut for reaching theme class
	 *
	 * @access public
	 * @return object
	 */
	
	final public function THEME() {
		
		return $this->config('inited_class', 'active_theme');
	
	}
	
	/**
	 * get_file_contents shorthand, no full path needed
	 *
	 * @access public
	 * @param string $file Filepath from root
	 * @return string|bool
	 */
	
	final public function get_file_contents($file) {
		return file_get_contents($this->config('full_path') . $file);
	}
	
	/**
	 * Gets a files name
	 *
	 * @access public
	 * @param string $path Filepath
	 * @param bool $no_extension Need extension too? Default: false
	 * @return string
	 */
	
	final public function get_file_name($path, $no_extension = false) {
		if ($no_extension) {
			$ret = explode('.', basename($path));
			return $ret[0];
		}
		return basename($path);
	}
	
	/**
	 * Gets the extension of a file
	 *
	 * @access public
	 * @param string $path Filepath
	 * @return string
	 */
	
	final public function get_file_extension($path) {
		$ret = explode('.', $this->get_file_name($path));
		return end($ret);
	}
	
	/**
	 * Gets the users IP address
	 *
	 * @access public
	 * @return string
	 */
	
	final public function get_ip() {
		$ip = false;
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else
			$ip = "UNKNOWN";
		return $ip;
		
	}
	
	/**
	 * Helps to generate parameter array. Same as array merge with one big difference: The $params parameter accepts FALSE for value as it's regular in many cases. It's only used to prevent unhandled PHP notices.
	 *
	 * @access public
	 * @param array $default Array with default values
	 * @param array $params Array with new values
	 * @return array
	 */
	
	final public function param_mount($defaults, $params = FALSE){
		
		if($params){
			
			foreach ($params as $p_key => $p_value) {
				
				$defaults[$p_key] = $p_value;
				
			}
			
		}
		
		return $defaults;
	
	}
	
	/**
	 * E-mail validator
	 *
	 * @access public
	 * @param string $email E-mail address
	 * @return array
	 */
	
	final public function valid_email($email){
		
		if(function_exists('filter_var')){
			return filter_var($email, FILTER_VALIDATE_EMAIL);
		}

		return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z])$^", $email);
	
	}
	
	final public function valid_domain($domain_name, $allow_host = false){
		
		$domain_name = trim($domain_name,"/");
		$domain_name = str_replace("http://","",$domain_name);
		$domain_name = str_replace("https://","",$domain_name);
		if(strpos($domain_name,"/")){
			$domain_name = explode("/", $domain_name);
			$domain_name = $domain_name[0];
		}
		if(!$allow_host){
			if(!strpos($domain_name,'.')){
				return false;
			}
		}
		
		$pieces = explode(".",$domain_name);
		
		foreach($pieces as $piece)
		{
		    if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $piece)
			|| preg_match('/-$/', $piece) )
		    {
			return false;
		    }
		}
		
		return true;
	
	}
	
	final public function url2domain($link){	
		if(substr(strtolower($link), 0, 7) == "http://")
			$link = substr($link, 7);
		if(substr(strtolower($link), 0, 8) == "https://")
			$link = substr($link, 8);
		if(substr(strtolower($link),0,4) == "www.")
			$link = substr($link, 4);
		if(strpos($link, "/") !== false )
			$link = substr($link, 0, strpos($link, "/"));	  
			return $link;
	}
	
	final public function labelize($text, $js = false){
		if($js){
			$text = preg_split('/(?=[A-Z])/', $text, -1, PREG_SPLIT_NO_EMPTY);
			return ucwords(implode(" ",$text));
		}
		else{
			return ucwords(str_replace("_",' ', $text));
		}
	}
	
	final public function add_css($file, $type){
		
		$type = $type.'_css';
		
		if($this->config($type)){
			$this->write_config($type, $this->config($type) . "<link rel='stylesheet' href='$file' />\n");
		}
		else{
			$this->write_config($type, "<link rel='stylesheet' href='$file' />\n");
		}
		
	}
	
	final public function add_js($file, $type){
		
		$type = $type.'_js';
		
		if($this->config($type)){
			$this->write_config($type, $this->config($type) . "<script type='text/javascript' src='$file'></script>\n");
		}
		else{
			$this->write_config($type, "<script type='text/javascript' src='$file'></script>\n");
		}
		
	}
	
	final public function extend($content, $type, $page = 'home'){
		
		$type = 'extended_'.$page.'_'.$type;
		
		if($this->config($type)){
			$this->write_config($type, $this->config($type) . "$content\n\n");
		}
		else{
			$this->write_config($type, "$content\n\n");
		}
		
	}
	
	final public function register_method($method){
		
		$r = $this->config('registered_methods');
		$r[$method] = true;
		
		$this->write_config('registered_methods', $r);
		
	}
	
	final public function run_method($method){
		
		if($this->config('registered_methods', $method)){
			
			return $this->THEME()->$method();
			
		}
		
		return 'unregistered';
		
	}
	
	final public function dayt($date, $type = 'datetime'){
		
		switch($type){
			case 'date':
				$date = date($this->lang('core','date_format'), $date);
				break;
			case 'time':
				$date = date($this->lang('core','time_format'), $date);
				break;
			default:
				$date = date($this->lang('core','date_format') . ' ' . $this->lang('core','time_format'), $date);
				break;
		}
		
		return $date;
		
	}
	
	final public function translate_quoted($string) {
		$search  = array("\\t", "\\n", "\\r");
		$replace = array( "\t",  "\n",  "\r");
		return str_replace($search, $replace, $string);
	}

	// CLASS END

}

?>