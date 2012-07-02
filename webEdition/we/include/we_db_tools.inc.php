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
function getHash($query, $DB_WE){
	static $cache = array();
	if($query == ''){
		$cache = array();
		return $cache;
	}
	if(!isset($cache[$query])){
		if(!is_object($DB_WE)){
			t_e('missing DB connection');
			return array();
		}
		$DB_WE->query($query);
		$cache[$query] = ($DB_WE->next_record() ? $DB_WE->Record : array());
	}
	return $cache[$query];
}

function f($query, $field, $DB_WE){
	$h = getHash($query, $DB_WE);
	return isset($h[$field]) ? $h[$field] : '';
}

function doUpdateQuery($DB_WE, $table, $hash, $where){
	$tableInfo = $DB_WE->metadata($table);
	$fn = array();
	for($i = 0; $i < sizeof($tableInfo); $i++){
		$fieldName = $tableInfo[$i]["name"];
		if($fieldName != "ID"){
			$fn[$fieldName] = isset($hash[$fieldName]) ? $hash[$fieldName] : '';
		}
	}
	return $DB_WE->query('UPDATE `' . $table . '` SET ' . we_database_base::arraySetter($fn) . ' ' . $where);
}

function escape_sql_query($inp){
	if(is_array($inp)){
		return array_map(__METHOD__, $inp);
	}

	if(!empty($inp) && is_string($inp)){
		return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp);
	}
	return $inp;
}

function doInsertQuery($DB_WE, $table, $hash){
	$tableInfo = $DB_WE->metadata($table);
	$fn = array();
	foreach($tableInfo as $t){
		$fieldName = $t['name'];
		$fn[$fieldName] = isset($hash[$fieldName . '_autobr']) ? nl2br($hash[$fieldName]) : $hash[$fieldName];
	}

	return $DB_WE->query('INSERT INTO `' . $table . '` SET ' . we_database_base::arraySetter($fn));
}
