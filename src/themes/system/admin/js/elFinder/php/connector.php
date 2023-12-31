<?php

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0   // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
		: ($attr == 'read' || $attr == 'write');  // else set read+write to true, locked+hidden to false
}

$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'       => urldecode($_GET['path']),
			'URL'        => urldecode($_GET['URL']),
			'dirMode'       => 0777,
    	'fileMode'      => 0777,           // disable and hide dot starting files (OPTIONAL)
			'tmbPath' => 'thumbnails',
			'uploadDeny' => array(
				'text/php',
				'text/x-php',
				'application/php',
				'application/x-php',
				'application/x-httpd-php',
				'application/x-httpd-php-source'
			),

			'attributes' => array(
                array( // hide readmes
                    'pattern' => '~/thumbnails$~',
                    'read' => false,
								    'write' => false,
								    'hidden' => true,
								    'locked' => true
                )
			)
		)
	));

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

