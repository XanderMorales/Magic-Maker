<?php defined('APPATH') or die('No direct script access.');
/**
 * Persony Web Conferencing 2.0
 * @author      Web Editors, Inc. <alex.morales@webeditors.com>
 * @copyright   Copyright 2009 Persony, Inc.
 * @version     2.0
 * 
 */

class Controller {
	
	public static $page;
	public $model;
	public static $view_msg; // array (for displaying messages in the views)
	/**
	* 
	*/
	public function __construct()
	{
        // phpinfo();exit();
		Controller::$page = (!(isset($_REQUEST['page']))) ? "default" : $_REQUEST['page'];
		// Remove extra slashes from the segments that could cause erroneous routing
		Controller::$page = preg_replace('!//+!', '/', trim(Controller::$page, '/'));
		
		require_once('Model.php');
		$this->model = new Model();
		
		$this->index();
	}
	/**
	* Switch though page param to perform and control requested operation.
	*/
	public function index()
	{
		switch(Controller::$page)
		{
			case 'default':
				$this->loadView('upload_form.php');
				break;
			case 'upload_ppt':
				$this->uploadPPT();
				break;
            case 'run_queu':
                $this->runQueue();
                break;
			default:
				$this->loadView('upload_form.php');
				break;
		}
	}
    /**
    * 
    */
    public function runQueue()
    {
        $unique_id = $_REQUEST['unique_id'];
        $file_name = $_REQUEST['file_name'];
        
        // instatiate object
        require_once('PPT_toJPG.php');
        $convert = new PPT_toJPG(VARPATH . 'upload' . DIRECTORY_SEPARATOR . $file_name, $file_name, VARPATH . 'output', $unique_id);
        
        // load the FIFO Queue
        require_once('Queue.php');
        $queu = new Queue($convert, $unique_id, $file_name);
    }
	/**
	* 
	*/
	private function uploadPPT()
	{
		// validatation & catch every possible that can occur on file upload
		$file_name = $_FILES['file']['name'];
		
		if($file_name == '')
		{
			Controller::$view_msg['error'] = '<li>Please slect a file to upload.</li>';
			$this->loadView('upload_form.php');
			exit();
		}
		
		$file_error = $_FILES['file']['error'];
		switch($file_error)
		{
			case UPLOAD_ERR_INI_SIZE:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_FORM_SIZE:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_PARTIAL:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_NO_FILE:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_NO_TMP_DIR:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_CANT_WRITE:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
			case UPLOAD_ERR_EXTENSION:
				Controller::$view_msg['error'] = '<li>' . Config::$setting['upload.err_msg'][$file_error] . '</li>';
				$this->loadView('upload_form.php');
				exit();
		}
		
		// we passed all posible errors now lets do our majick.
		$file_type = $_FILES['file']['type'];
		$file_size = $_FILES['file']['size'];
		
		if($_FILES['file']['type'] == "application/vnd.ms-powerpoint")
		{
			if(! move_uploaded_file($_FILES['file']['tmp_name'], VARPATH . 'upload' . DIRECTORY_SEPARATOR . $file_name))
			{
				// do something better here Alex (die all errors and create notification afterwards)
				die("The File Uploaded could not be moved into the target folder"); 
			}
		}
		else
		{
			Controller::$view_msg['error'] = '<li>Upload failed, you attempted to upload a restricted file type - Only Power Point Files Allowed.</li>';
			$this->loadView('upload_form.php');
			exit();
		}
		// queue the converter
		$this->convertQueuePPT($file_name);
	}
	/**
	* 
	*/
	public function convertQueuePPT($file_name)
	{
		// create unique_id
		$unique_id = md5(uniqid(rand(), true)) . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s');
		
		//start the queue
		$this->model->startQueue($unique_id, $file_name);
		
		// instatiate object
		require_once('PPT_toJPG.php');
		$convert = new PPT_toJPG(VARPATH . 'upload' . DIRECTORY_SEPARATOR . $file_name, $file_name, VARPATH . 'output', $unique_id);
		
        // load the FIFO Queue
        require_once('Queue.php');
        $queu = new Queue($convert, $unique_id, $file_name);
        
        // check if process is running
        if($queu->checkInQueue() == 'FALSE')
        {
            // process is currently running and set to queue
        }
	}
	/**
	*
	*/
	public function emailErrorNotification($error_msg)
	{
	 	echo $error_msg;
	}
	/**
	*
	*/
	private function loadView($view)
	{
		require_once('views' . DIRECTORY_SEPARATOR . $view);
	}
}
