<?php
/**
* Configure your framework here.
*
* @package    Config
* @author     Web Editors Team
* @copyright  (c) 2008 Web Editors Team
*/

final class Config {
	public static $setting = array();
	
	public static function setup()
	{
		/*
		* ----------------------------------------------------------------------------------------------
		* DEFAULT DATABASE CONFIGURATION
		* ----------------------------------------------------------------------------------------------
		*/
		// Define the application environment status. When this constant is set to FALSE, some debugging
		// information will be displayed and or logged, otherwise set to TRUE for production mode.
		self::$setting['default.in_production'] = FALSE;
		
		// PHP DEFAULT TIMEZONE
		// Set the default timezone used by all date/time functions in a script
		// For more info visit: http://php.net/date_default_timezone_set
		self::$setting['default.time_zone'] = 'America/Los_Angeles';
		
		/*
		* ----------------------------------------------------------------------------------------------
		* DATABASE CONFIGURATION
		* ----------------------------------------------------------------------------------------------
		*/
		// Database connection settings, defined as arrays, or "groups".
		// Each group can be connected to independantly, and multiple groups can be connected at once if needed.
		self::$setting['database'] = array
		(
			'default'	=>	DOCROOT . 'var' . DIRECTORY_SEPARATOR . 'sqlite' . DIRECTORY_SEPARATOR . 'ppt_converter'
		);
		
		/*
		* ----------------------------------------------------------------------------------------------
		* PPT CONFIGURATIONS
		* ----------------------------------------------------------------------------------------------
		*/
		self::$setting['ppt.convertexe'] = DOCROOT . 'lib' . DIRECTORY_SEPARATOR . 'pptconvert.exe';
		self::$setting['ppt.width'] = '960';
		self::$setting['ppt.height'] = '720';
		self::$setting['ppt.dst_type'] = 'jpg';

		/*
		* ----------------------------------------------------------------------------------------------
		* UPLOAD CONFIGURATIONS
		* ----------------------------------------------------------------------------------------------
		*/
		// note: tmp_name will return empty if this value is not big enough and script will catch error
		// if max_file_size < php.ini upload_max_filesize error catching will be performed
		self::$setting['upload.max_file_size'] = '5242880'; // bytes
		// Change friendly error here.
		self::$setting['upload.err_msg'] = array(
		    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
		    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
		    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
		    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
		    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
		    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
		    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
		);
		
		/*
		* ----------------------------------------------------------------------------------------------
		* EMAIL CONFIGURATIONS
		* ----------------------------------------------------------------------------------------------
		*/
		self::$setting['email.server'] = 'mail.webeditors.com';
		self::$setting['email.user'] = 'webeditors';
		self::$setting['email.pass'] = '86753Oh9';
	}
}