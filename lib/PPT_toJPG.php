<?php defined('APPATH') or die('No direct script access.');
/**
 * Persony Web Conferencing 2.0
 * @author      Persony, Inc. <info@persony.com>, Web Editors, Inc. <alex.morales@webeditors.com>
 * @copyright   Copyright 2008 Persony, Inc.
 * @version     2.0
 * 
 */

final class PPT_toJPG {
	
	private $uploaded_ppt_file;
	private $ppt_filename;
	private $output_path;
	private $unique_id;
	private $output_directory;
	
	private $ppt_exe;
	private $ptt_width;
	private $ptt_height;
	private $ppt_dst_type;
	
	private $bat_file;
	private $xml_file;
	
	/**
	* PPT to JPG conveter
	*/
	function __construct($uploaded_ppt_file, $ppt_filename, $output_path, $unique_id)
	{
		$this->uploaded_ppt_file	= $uploaded_ppt_file;
		$this->ppt_filename			= $ppt_filename;
		$this->output_path			= $output_path;
		$this->unique_id			= $unique_id;
		$this->output_directory 	= $this->output_path . DIRECTORY_SEPARATOR . $this->unique_id;
		
		$this->ppt_exe 				= Config::$setting['ppt.convertexe'];
		$this->ptt_width			= Config::$setting['ppt.width'];
		$this->ptt_height			= Config::$setting['ppt.height'];
		$this->ppt_dst_type			= Config::$setting['ppt.dst_type'];
		
		$this->bat_file = $this->output_directory . DIRECTORY_SEPARATOR . $this->ppt_filename . ".bat";
		$this->xml_file = $this->output_directory . DIRECTORY_SEPARATOR . $this->ppt_filename . ".xml";
	}
	/**
	*
	*/
	public function prepare()
	{
		// create a directory for the ouput slides
		if (!(is_dir($this->output_directory)))
		{
			if (!mkdir($this->output_directory, 0777))
			{
				// do something better here Alex (die all errors and create notification afterwards)
				die("Couldn't create " . $this->output_directory);
			}
		}
		
		// create bat file!
		$this->createBatFile();
	}
	/**
	* 
	*/
	public function convertPPT() 
	{		
		// exec the bat file
		$this->runCommand();
		
		// create global xml data
		$dateTime = date('Y-m-d H:i:s');
		$xmlFp = fopen($this->xml_file, "w");
		fwrite($xmlFp, "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n");
		fwrite($xmlFp, "<slides id=\"$this->ppt_filename\" xmlFile=\"$this->ppt_filename\" dateTime=\"$dateTime\" title=\"$this->ppt_filename\" width=\"$this->ptt_width\" height=\"$this->ptt_height\" type=\"PPT\" author=\"\" copyright=\"\" keywords=\"\" description=\"\">\n");
		$i = 1;
		while(1)
		{
			$slideFile = $this->output_directory . DIRECTORY_SEPARATOR . "Slide" . $i . "." . $this->ppt_dst_type;
			if (!file_exists($slideFile))
			{
				break;
			}
			$thumbFile = "Slide" . $i . "." . $this->ppt_dst_type;
			fwrite($xmlFp, "<slide title=\"Slide $i\" fileName=\"$thumbFile\" thumbnail=\"$thumbFile\" notes=\"\"/>\n");
			$i++;
		}
		fwrite($xmlFp, "</slides>\n");
		fclose($xmlFp);
		
		// remove uploaded file
		unlink($this->uploaded_ppt_file);
	}
	/**
	* Prepare bat to launch a shell command to execute the following command line:
	* pptconvert.exe {output_dir_path}, jpg, 960, 720, {input_file_path}
	* example: pptconvert.exe C:/.../4983ac69, jpg, 960, 720, C:/.../4983ac69.pptx
	* create a .bat file and run it 
	*/
	private function createBatFile()
	{
		$content="
@REM default

SET EXE_PATH=$this->ppt_exe
SET SRC_PPT=$this->uploaded_ppt_file
SET DST_FOLDER=$this->output_directory
SET WIDTH=$this->ptt_width
SET HEIGHT=$this->ptt_height
SET DST_TYPE=$this->ppt_dst_type

\"%EXE_PATH%\" %DST_FOLDER%, %DST_TYPE%, %WIDTH%, %HEIGHT%, %SRC_PPT%
";
		$fp = fopen($this->bat_file, "wb");
		if ($fp)
		{
			fwrite($fp, $content);
			fclose($fp);
		}
		else
		{
			die("Couldn't create file " . $this->bat_file);
		}
	}
	/**
	* string exec ( string $command [, array &$output [, int &$return_var ]] )
	*/
	private function runCommand()
	{
		exec($this->bat_file, $output, $return_var);
		if($return_var != 0)
		{
			die("Couldn't run " . $this->bat_file);
		}
		unlink($this->bat_file);
	}
}