<?php defined('APPATH') or die('No direct script access.');
/**
 * Persony Web Conferencing 2.0
 * @author      Web Editors, Inc. <alexander@webeditors.com>
 * @copyright   Copyright 2009 Persony, Inc.
 * @version     2.0
 * 
 */

class Model {
	
	private $db_file;
	/**
	* 
	*/
	public function __construct($db = 'default')
	{
		$this->db_file = Config::$setting['database'][$db];
	}
	/**
    * 
    * @param mixed $unique_id
    * @param mixed $file_name
    */
	public function startQueue($unique_id, $file_name)
	{
		$datestring = date('Y') . '-' . date('m') . '-' . date('d') . ' ' . date('H') . ':' . date('i') . ':' . date('s');
		$email = addslashes($_REQUEST['email']);

		$db = new SQLiteDatabase($this->db_file);
		
		$q = @$db->query('SELECT queue_file_name FROM ppt_queue WHERE queue_file_name = "1" AND queue_customer_email = "1" AND queue_complete = "1" AND queue_unique_id = "1"');
		if ($q === false)
		{
			$db->queryExec('CREATE TABLE ppt_queue(queue_file_name TEXT, queue_customer_email TEXT, queue_start DATETIME, queue_end DATETIME, queue_complete TEXT, queue_unique_id TEXT)');
			$db->queryExec("INSERT INTO ppt_queue (queue_file_name, queue_customer_email, queue_complete, queue_unique_id) VALUES ('1','1','1','1')");
		}
		
		$sql = "INSERT INTO ppt_queue (queue_file_name, queue_customer_email, queue_start, queue_complete, queue_unique_id) VALUES ('$file_name','$email','$datestring','NO', '$unique_id')";
		$db->queryExec($sql);
	}
    /**
    * 
    * @param mixed $unique_id
    * @param mixed $file_name
    */
    public function endQueue($unique_id, $file_name)
    {
        $db = new SQLiteDatabase($this->db_file);
        $query = $db->queryExec("UPDATE ppt_queue SET queue_complete= 'YES' WHERE queue_unique_id= '$unique_id'");
    }
    /**
    * 
    */
    public function queryQueue()
    {
        $db = new SQLiteDatabase($this->db_file);
        $result = $db->query("SELECT * FROM ppt_queue WHERE queue_complete= 'NO'");
        
        $array = FALSE;
        // iterate through the retrieved rows 
        while ($result->valid())
        {
            // fetch current row
            $row = $result->current();
            
            // add item to array
            $array[$row[5]] = $row[0];
            
            // move pointer to next row
            $result->next();
        }
        return $array;
    }
}