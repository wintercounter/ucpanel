<?php lock();

global $conf;

/////////////////////////////////////
// System Configs
/////////////////////////////////////

$conf['system_dir'] = 'system';
$conf['system_version'] = '1.0';
$conf['user_dir'] = 'install';
$conf['full_path'] = str_replace($conf['system_dir'].'/config.php', '' ,__FILE__);
$conf['system_path'] = $conf['full_path'] . $conf['system_dir'] . '/';
$conf['user_path'] = $conf['full_path'] . $conf['user_dir'] . '/';
$conf['system_language'] = 'en_US';
$conf['full_url'] = explode("?",(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$conf['full_url'] = trim(str_replace("index.php",'',str_replace($conf['user_dir'], '',$conf['full_url'][0])),"/").'/';
$conf['system_url'] = $conf['full_url'] . $conf['system_dir'] . '/';
$conf['theme_path'] = $conf['full_path'] . 'themes/';
$conf['site_theme_path'] = $conf['theme_path'] . 'site/';
$conf['system_theme_path'] = $conf['theme_path'] . 'system/';
$conf['site_theme_url'] = $conf['full_url'] . 'themes/site/';
$conf['system_theme_url'] = $conf['full_url'] . 'themes/system/';

// Database
$conf['db_name'] = 'database';
$conf['db_type'] = 'pdo'; // Currently only PDO is supported!
$conf['db_path'] = $conf['user_path'] . 'cache/sqlite/';  // Only for sqlite

// Only for MySQL (not supported yet)
$conf['db_hostname'] = 'localhost';
$conf['db_username'] = 'sample_db_username';
$conf['db_password'] = 'sample_db_password';


?>
