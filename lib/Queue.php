<?php 

final class Queue
{ 
    private $convert; // object
    
    private $unique_id;
    private $lock_file;
    
    public $in_queue;
    
    public $model;
    public $file_name;
    /**
    * 
    */
    public function __construct($convert, $unique_id, $file_name)
    {
        $this->convert = $convert;
        $this->unique_id = $unique_id;
        $this->lock_file = VARPATH . 'upload' . DIRECTORY_SEPARATOR . $unique_id . '.lock';
        
        $this->model = new Model();
        $this->file_name = $file_name;
        
        $this->prepare(); // prepare bat
    }
    /**
    * Prepare the bat file for the process
    */
    private function prepare()
    {
        // creat bat file to execute
        $this->convert->prepare();
    }
    /**
    * Run process if lock file not found
    */
    public function checkInQueue()
    {
        if (file_exists($this->lock_file))
        {
            $this->in_queue = FALSE;
        }
        else
        {
            $this->in_queue = TRUE;
            $this->executeQueue();
        }

    }
    /**
    * Execute the Queue
    */
    private function executeQueue()
    {
        // lock file so only one process can run creation.
        $file_handle = fopen($this->lock_file, 'w');
        fclose($file_handle);
        
        $this->convert->convertPPT();
        
        $this->model->endQueue($this->unique_id, $this->file_name);
        
        unlink($this->lock_file);
        
        $items_to_queue = $this->model->queryQueue(); // array
        
        foreach($items_to_queue as $name => $value)
        {
            echo "C:\Program Files\NuSphere\PhpED\php5 php " . DOCROOT . "index.php \"page=run_queu&unique_id=$name&file_name=$value\"<br />";
            system("php " . DOCROOT . "index.php \"page=run_queu&unique_id=$name&file_name=$value\"", $status);
        }
    }
}
