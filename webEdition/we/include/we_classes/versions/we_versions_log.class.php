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
class we_versions_log{
	const VERSIONS_DELETE = 'delete';
	const VERSIONS_RESET = 'reset';
	const VERSIONS_PREFS = 'prefs';

	public $action;
	public $data;
	public $db;
	public $table = VERSIONSLOG_TABLE;
	public $userID;
	public $timestamp;
	public $persistent_slots = [];

	public function __construct(){
		$this->db = new DB_WE();
		$this->userID = $_SESSION['user']['ID'];
		$this->timestamp = time();
		$this->loadPresistents();
	}

	function loadPresistents(){
		$this->persistent_slots = $this->db->metadata($this->table, we_database_base::META_NAME);
		foreach($this->persistent_slots as $columnName){
			if(!isset($this->$columnName)){
				$this->{$columnName} = "";
			}
		}
	}

	function load(){
		$content = [];
		$tableInfo = $this->db->metadata($this->table, we_database_base::META_NAME);
		$this->db->query('SELECT ID,timestamp,typ,userID FROM ' . $this->db->escape($this->table) . ' ORDER BY timestamp DESC');
		$m = 0;
		while($this->db->next_record()){
			foreach($tableInfo as $columnName){
				if(in_array($columnName, $this->persistent_slots)){
					$content[$m][$columnName] = $this->db->f($columnName);
				}
			}
			$m++;
		}

		return $content;
	}

	function saveLog(){
		$set = [];

		foreach($this->persistent_slots as $val){
			if(isset($this->$val)){
				$set[$val] = $this->$val;
			}
		}

		if($set){
			$this->db->query('INSERT INTO ' . $this->db->escape($this->table) . ' SET ' . we_database_base::arraySetter($set));
		}
	}

	function saveVersionsLog($logArray, $action = ""){
		$this->typ = $action;
		$this->data = we_serialize($logArray, SERIALIZE_JSON);
		$this->saveLog();
	}

}