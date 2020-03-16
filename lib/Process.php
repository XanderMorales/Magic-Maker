<?php
/**
 * Persony Web Conferencing 2.0
 * @author      Persony, Inc. <info@persony.com>
 * @copyright   Copyright 2008 Persony, Inc.
 * @version     2.0
 * 
 */

// NOT DONE YET.
/** This script should perform the following function:
 *  - Start a critical session to:
 * 		- Check if another Process is in progress. Quit if another Process is alreay in progress.
 * 		- Find the first available job in the the process queue. Quit if no jobs are availble.
 * 		- Claim the job for processing
 *  - Create a new Converter object based on the input and out document type
 *  - Create an entry in the conversion logs
 *  - Start the conversion and wait for it to finish
 *  - Log the conversion results
 *  - Launch Notication to report conversion results
 *  - Launch another Process.
 */

	set_time_limit(300);
	if (!isset($_GET['file']))
		die ("ERROR");
	$filename=$_GET['file'];
	$title=$_GET['title'];
	$process_prefix="~";
	if (defined("LOG_DIR") && LOG_DIR!='')	
		$logFile=LOG_DIR."pptconvert.log";
	else
		$logFile='';
	
	$ip='';
	if (isset($_SERVER['REMOTE_ADDR']))
		$ip = $_SERVER['REMOTE_ADDR'];

	if ($logFile!='')
		$logFp=@fopen($logFile, "a");
	else
		$logFp=null;
		
	if ($logFp)
		fwrite($logFp, date('Y-m-d H:i:s')." ".basename($filename)." ".$ip."\r\n");
				

	$conveter=new PConveter_PPT_JPG();
	$converter->Convert($filename);
		
	if ($logFp)
		fclose($logFp);
	

?>