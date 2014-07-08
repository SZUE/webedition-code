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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
  @param $query: SQL query; an empty query resets the cache
 */
function getHash($query = '', we_database_base $DB_WE = NULL, $resultType = MYSQL_BOTH){
	static $cache = array();
	if($query == ''){
		$cache = array();
		return $cache;
	}
	$hash = md5($query, true);
	if(!isset($cache[$hash])){
		$DB_WE = $DB_WE ? $DB_WE : $GLOBALS['DB_WE'];
		$DB_WE->query($query);
		$cache[$hash] = ($DB_WE->next_record($resultType) ? $DB_WE->Record : array());
	}
	return $cache[$hash];
}

function f($query, $field = -1, we_database_base $DB_WE = NULL){
	$h = getHash($query, ($DB_WE ? $DB_WE : $GLOBALS['DB_WE']), MYSQL_ASSOC);
	return ($field == -1 ? current($h) : (isset($h[$field]) ? $h[$field] : ''));
}

function escape_sql_query($inp){
	if(is_array($inp)){
		return array_map(__METHOD__, $inp);
	}

	return ($inp && is_string($inp) ?
			strtr($inp, array(
				'\\' => '\\\\',
				"\0" => '\\0',
				"\n" => '\\n',
				"\r" => '\\r',
				"'" => "\\'",
				'"' => '\\"',
				"\x1a" => '\\Z'
			)) :
			$inp);
}

function sql_function($name){
	static $data = 0;
	if(!$data){
		$data = md5(uniqid(__FUNCTION__, true));
	}
	return (is_array($name) ? isset($name['sqlFunction']) && $name['sqlFunction'] == $data :
			array('sqlFunction' => $data, 'val' => $name));
}

function doUpdateQuery(we_database_base $DB_WE, $table, $hash, $where){
	if(empty($hash)){
		return;
	}
	$tableInfo = $DB_WE->metadata($table);
	$fn = array();
	foreach($tableInfo as $f){
		$fieldName = $f['name'];
		if($fieldName != 'ID' && isset($hash[$fieldName])){
			$fn[$fieldName] = $hash[$fieldName];
		}
	}
	return $DB_WE->query('UPDATE `' . $DB_WE->escape($table) . '` SET ' . we_database_base::arraySetter($fn) . ' ' . $where);
}


function doInsertQuery(we_database_base $DB_WE, $table, $hash){
	$tableInfo = $DB_WE->metadata($table);
	$fn = array();
	foreach($tableInfo as $t){
		$fieldName = $t['name'];
		$fn[$fieldName] = isset($hash[$fieldName . '_autobr']) ? nl2br($hash[$fieldName]) : $hash[$fieldName];
	}

	return $DB_WE->query('INSERT INTO `' . $table . '` SET ' . we_database_base::arraySetter($fn));
}
