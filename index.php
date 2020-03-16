<?php
/**
 * Persony Web Conferencing 2.0
 * @author      Web Editors, Inc. <alex.morales@webeditors.com>
 * @copyright   Copyright 2009 Persony, Inc.
 * @version     2.0
 * 
 */

define('DOCROOT', getcwd() . DIRECTORY_SEPARATOR);
require_once(DOCROOT . 'app/Config.php');
Config::setup();

date_default_timezone_set(Config::$setting['default.time_zone']);

define('IN_PRODUCTION', Config::$setting['default.in_production']);
define('VARPATH', DOCROOT . 'var' . DIRECTORY_SEPARATOR);
define('APPATH', DOCROOT . 'app' . DIRECTORY_SEPARATOR);

if(! IN_PRODUCTION)
{
	version_compare(PHP_VERSION, '5.2', '<') and exit('Sorry, Script requires PHP 5.2 or newer.');
	error_reporting(E_ALL & ~E_NOTICE);
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
}
else
{
	error_reporting(E_ALL);
	ini_set('display_errors', '0');
	ini_set('display_startup_errors', '0');
}

ini_set('include_path',ini_get('include_path') . '.' . PATH_SEPARATOR . DOCROOT . 'lib' . DIRECTORY_SEPARATOR . '.' . PATH_SEPARATOR . APPATH);

require_once('Controller.php');
$controller = new Controller();
?>