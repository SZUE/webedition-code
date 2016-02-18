<?php
/**
 * webEdition CMS
 *
 * $Rev$
 * $Author$
 * $Date$
 *
 * This source is part of webEdition CMS. webEdition CMS is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile
 * webEdition/licenses/webEditionCMS/License.txt
 *
 * @category   webEdition
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Class taskFragment()
 *
 * This class offers methods to split tasks in more than one fragment.
 * It is needed if you need to do a lot of work, which takes time
 * longer than the timeout of some servers
 */
class we_fragment_base{
	/**
	 * Number of all tasks.
	 * @var        int
	 */
	var $numberOfTasks = 1;

	/**
	 * Number of current tasks.
	 * @var        int
	 */
	var $currentTask = 0;

	/**
	 * Number of tasks per fragment.
	 * @var        int
	 */
	var $taskPerFragment = 1;

	/**
	 * Array of the data.
	 * @var        array
	 */
	var $alldata = array();

	/**
	 * Data for the current task.
	 * @var        mixed
	 */
	var $data = null;

	/**
	 * Name for the whole fragment action.
	 * This variable is used for a reference, so it must be unique
	 * @var        string
	 */
	var $name;

	/**
	 * Pause for each task in ms.
	 * @var        int
	 */
	var $pause;
	var $initdata = null;

	/**
	 * init Data.
	 * @var        array
	 */

	/**
	 * This is the constructor of the class. Everything will be done here.
	 *
	 * @param      string $name
	 * @param      int $taskPerFragment
	 * @param      int $pause
	 * @param      int $bodyAttributes
	 * @param      array $initdata
	 */
	public function __construct($name, $taskPerFragment, $pause = 1, $bodyAttributes = "", $initdata = ""){
		$this->name = $name;
		$this->taskPerFragment = $taskPerFragment;
		$this->pause = $pause;
		if($initdata){
			$this->initdata = $initdata;
		}
		//FIXME: make this DB entries; create method for early creation, since the whole data might be too much for memory!
		$filename = WE_FRAGMENT_PATH . $this->name;
		$this->currentTask = we_base_request::_(we_base_request::INT, 'fr_' . $this->name . '_ct', 0);
		if(file_exists($filename) && $this->currentTask){
			$ser = we_base_file::load($filename);
			if(!$ser){
				exit('Could not read: ' . $filename);
			}
			$this->alldata = we_unserialize($ser);
		} else {
			$this->taskPerFragment = $taskPerFragment;
			$this->init();
			if(!we_base_file::save($filename, we_serialize($this->alldata, SERIALIZE_JSON))){
				exit('Could not write: ' . $filename);
			}
		}
		$this->numberOfTasks = count($this->alldata);
		static::printHeader();
		$this->printBodyTag($bodyAttributes);
		for($i = 0; $i < $this->taskPerFragment; $i++){
			if($i > 0){
				$this->currentTask++; // before: currentTask was incremented with $i;
			}
			if($this->currentTask == $this->numberOfTasks){

				unlink($filename);
				$this->finish();
				break;
			} else {
				$this->data = $this->alldata[$this->currentTask];
				$this->doTask();
			}
		}
		$this->printFooter();
	}

	/**
	 * Prints the body tag.
	 *
	 * @param      array $attributes
	 */
	function printBodyTag($attributes = ''){
		$nextTask = $this->currentTask + $this->taskPerFragment;
		$attr = "";
		if($attributes){
			foreach($attributes as $k => $v){
				$attr .= " $k=\"$v\"";
			}
		}
		$tail = ""; //FIXME: make this a post request
//Fixme: use http_build_query
		foreach($_REQUEST as $i => $v){
			if(is_array($v)){
				foreach($v as $k => $av){
					$tail .= "&" . rawurlencode($i) . "[" . rawurlencode($k) . "]=" . rawurlencode($av);
				}
			} elseif($i != "fr_" . rawurlencode($this->name) . "_ct"){
				$tail .= "&" . rawurlencode($i) . "=" . rawurlencode($v);
			}
		}

		$onload = "document.location='" . $_SERVER['SCRIPT_NAME'] . '?fr_' . rawurlencode($this->name) . '_ct=' . ($nextTask) . $tail . "';";

		if($this->pause){
			$onload = 'setTimeout(function(){
' . $onload . '
},' . $this->pause . ');';
		}
		echo "<body" .
		$attr .
		(($nextTask <= $this->numberOfTasks) ?
			(' onload="' . $onload . '"') :
			"") .
		">";
	}

	/**
	 * Prints a javascript for reloading next task.
	 *
	 * @param      array $attributes
	 */
	function printJSReload(){
		$nextTask = $this->currentTask + $this->taskPerFragment;
		$tail = ""; //FIXME: make this a post request
		//Fixme: use http_build_query
		foreach($_REQUEST as $i => $v){
			if(is_array($v)){
				foreach($v as $k => $av){
					$tail .= "&" . rawurlencode($i) . "[" . rawurlencode($k) . "]=" . rawurlencode($av);
				}
			} elseif($i != "fr_" . rawurlencode($this->name) . "_ct"){
				$tail .= "&" . rawurlencode($i) . "=" . rawurlencode($v);
			}
		}

		$onload = "document.location='" . $_SERVER["SCRIPT_NAME"] . "?fr_" . rawurlencode($this->name) . "_ct=" . ($nextTask) . $tail . "';";

		if($this->pause){
			$onload = 'setTimeout(function(){
' . $onload . '
},' . $this->pause . ');';
		}
		if(($nextTask <= $this->numberOfTasks)){
			echo we_html_element::jsElement($onload);
		}
	}

	/**
	 * Prints the Footer.
	 *
	 */
	function printFooter(){
		echo '</body></html>';
	}

	// overwrite the following functions

	/**
	 * Prints the header.
	 * This Function should be overwritten
	 *
	 */
	static function printHeader(){
		echo we_html_tools::getHtmlTop(''/* FIXME: missing title */, '', '', ' ');
	}

	/**
	 * Overwrite this function to initialize the array taskFragment::alldata.
	 *
	 */
	function init(){
		$this->alldata = $this->initdata;
	}

	/**
	 * Overwrite this function to do the work for each task.
	 *
	 */
	function doTask(){

	}

	/**
	 * Overwrite this function to do the work when everything is finished.
	 *
	 */
	function finish(){

	}

}

/*
  //EXAMPLE:


class myFrag extends taskFragment{

	function init(){
		$this->alldata = array(
							array("color"=>"red","size"=>30),
							array("color"=>"green","size"=>10),
							array("color"=>"blue","size"=>20),
							array("color"=>"yellow","size"=>50)
							);
	}

	function doTask(){
		$id = $this->data;
		print "Color:".$this->data["color"]."<br/>";
		print "Size:".$this->data["size"]."<br/><br/>";
	}

	function finish(){
		print "FINISHED!";
	}

	function printHeader(){
		print "<html><head><title>myFragment</title></head>";
	}
}

$fr = new myFrag("holeg",1,800,array("bgcolor"=>"silver"));


*/