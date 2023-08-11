<?php
    /**
    * UCPanel
    *
    * @package	        UCPanel
    * @author	        wintercounter
    * @copyright	Copyright (c) 2013 Viktor Vincze
    * @link		http://expression-themes.com
    * @since		Version 1.0
    */


    // I hate to hide notices, but in this case i have to because of undefined template variables for support other modules.
    error_reporting(0);
    //error_reporting(E_ALL); //turn on for debug
    
    if(function_exists('ini_set')){
          ini_set('display_errors',1);
    }
    
    // Locker function
    function lock(){
          if(!defined('UCP')) exit('Who\'s there???');
    }
          
    // Constans for locker to prevent direct file access
    define('UCP', true);
    
    // Load admin controller instead of home
    define('admin',true);
    
    // The ***Magic*** :)
    require_once "../system/init.php";
    
    //Free up some memory
    $conf=NULL; unset($conf);
    $LANG=NULL; unset($LANG);

?>