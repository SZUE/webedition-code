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
 * Definition of webEdition Base Model
 *
 */
class weModelBase{
	var $db;
	var $table = '';
	var $persistent_slots = array();
	var $keys = array('ID');
	var $isnew = true;

	/**
	 * Default Constructor
	 */
	public function __construct($table, we_database_base $db = null, $load = true){
		$this->db = ($db ? : new DB_WE()); //FIXME: => ?:
		$this->table = $table;
		if($load){
			$this->loadPresistents();
		}
	}

	function loadPresistents(){//fixme: set datatype from db
		$this->persistent_slots = array();
		$tableInfo = $this->db->metadata($this->table);
		foreach($tableInfo as $info){
			$fname = $info["name"];
			$this->persistent_slots[] = $fname;
			if(!isset($this->$fname)){
				$this->$fname = "";
			}
		}
	}

	/**
	 * Load entry from database
	 */
	function load($id = 0, $isAdvanced = false){
		if($id){
			$this->ID = $id;
		}
		if($this->isKeyDefined()){
			//if($id){
			//	$this->ID = $id;
			//}
			//#6338: Kode vor den if-Block geschoben
			//$tableInfo = $this->db->metadata($this->table);

			if(($data = getHash('SELECT * FROM `' . $this->table . '` WHERE ' . $this->getKeyWhere(), $this->db, MYSQL_ASSOC))){
				foreach($data as $fieldName => $value){
					if(($isAdvanced ? isset($this->persistent_slots[$fieldName]) : in_array($fieldName, $this->persistent_slots))){
						$this->{$fieldName} = $value;
					}
				}
				$this->isnew = false;
				return true;
			}
		}
		return false;
	}

	/**
	 * save entry in database
	 */
	function save($force_new = false, $isAdvanced = false){
		$sets = array();
		if($force_new){
			$this->isnew = true;
		}
		foreach($this->persistent_slots as $key => $val){
			$val = ($isAdvanced ? $key : $val);
			if(isset($this->{$val})){
				$sets[$val] = is_array($this->{$val}) ? serialize($this->{$val}) : $this->{$val};
			}
		}
		$where = $this->getKeyWhere();
		$set = we_database_base::arraySetter($sets);

		if($this->isKeyDefined()){
			if($this->isnew){
				$ret = $this->db->query('REPLACE INTO ' . $this->db->escape($this->table) . ' SET ' . $set, false, true);
				# get ID #
				if($ret){
					$this->ID = $this->db->getInsertId();
					$this->isnew = false;
				}
				return $ret;
			}
			return $this->db->query('UPDATE ' . $this->db->escape($this->table) . ' SET ' . $set . ' WHERE ' . $where);
		}

		return false;
	}

	/**
	 * delete entry from database
	 */
	function delete(){
		if(!$this->isKeyDefined()){
			return false;
		}
		$this->db->query('DELETE FROM ' . $this->db->escape($this->table) . ' WHERE ' . $this->getKeyWhere());
		return true;
	}

	function getKeyWhere(){
		$wheres = array();
		foreach($this->keys as $f){
			$wheres[] = '`' . $f . '`="' . escape_sql_query($this->$f) . '"';
		}
		return implode(' AND ', $wheres);
	}

	function isKeyDefined(){
		$defined = true;
		foreach($this->keys as $prim){
			if(!isset($this->$prim)){
				$defined = false;
			}
		}
		return $defined;
	}

	function setKeys($keys){
		$this->keys = $keys;
	}

	public function __sleep(){
		$tmp = get_object_vars($this);
		unset($tmp['db']);
		return array_keys($tmp);
	}

	public function __wakeup(){
		$this->db = new DB_WE();
	}

}
