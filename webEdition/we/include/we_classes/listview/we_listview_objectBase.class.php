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
 * class

 *
 */
//FIXME: is this class not ~ listview_object? why is this not the base class???
abstract class we_listview_objectBase extends we_listview_base{
	var $classID = 0; /* ID of a class */
	var $triggerID = 0; /* ID of a document which to use for displaying thr detail page */
	var $condition = ''; /* condition string (like SQL) */
	var $Path = ''; /* internal: Path of document which to use for displaying thr detail page */
	var $IDs = [];
	protected $languages = ''; //string of Languages, separated by ,
	var $objectseourls = false;
	var $hidedirindex = false;

	function f($key){
		return $this->DB_WE->f('we_' . $key);
	}

	protected function fillMatrix(&$matrix, $classID, $withVariant = false){
		$joinWhere = $regs = [];
		$table = OBJECT_X_TABLE . $classID;
		$tableInfo = we_objectFile::getSortedTableInfo($classID, true, $this->DB_WE, $withVariant);
		foreach($tableInfo as $fieldInfo){
			if(preg_match('/(.+?)_(.*)/', $fieldInfo['name'], $regs)){
				list(, $type, $name) = $regs;
				if($type === 'object' && $name != $this->classID){
					if(empty($matrix['we_object_' . $name]['type'])){
						$matrix['we_object_' . $name] = [
							'type' => $type,
							'table' => $table,
							'table2' => OBJECT_X_TABLE . $name,
							'classID' => $classID,
						];
						$foo = $this->fillMatrix($matrix, $name);
						$joinWhere[] = OBJECT_X_TABLE . intval($classID) . '.' . we_object::QUERY_PREFIX . $name . '=' . OBJECT_X_TABLE . $name . '.OF_ID';
						if($foo){
							$joinWhere[] = $foo;
						}
					}
				} else {
					if(!isset($matrix[$name])){
						$matrix[$name] = [
							'type' => $type,
							'table' => $table,
							'table2' => $table,
							'classID' => $classID,
						];
					}
				}
			}
		}
		return implode(' AND ', $joinWhere);
	}

	protected function makeSQLParts($matrix, $classID, $order, $cond, $useTable2){
		if(!$classID){
			t_e('no classid given!');
			return;
		}
		$from = $orderArr = $descArr = $ordertmp = [];

		$cond = ' ' . preg_replace_callback("/'([^']*)'/", function (array $match){
				$in = $match[1];
				$out = '';
				for($i = 0; $i < strlen($in); $i++){
					$out .= '&' . ord(substr($in, $i, 1)) . ';';
				}
				return "'" . $out . "'";
			}, strtr($cond, array('&gt;' => '>', '&lt;' => '<'))) . ' ';

		if($order){
			foreach(array_map('trim', explode(',', $order)) as $f){
				$g = explode(' ', $f);
				$orderArr[] = $g[0];
				$descArr[] = isset($g[1]) && strtolower(trim($g[1])) === 'desc';
			}
		}

		//get Metadata for class (default title, etc.)
		//BugFix #4629
		$fieldnames = getHash('SELECT DefaultDesc,DefaultTitle,DefaultKeywords,CreationDate,ModDate FROM ' . OBJECT_TABLE . ' WHERE ID=' . $classID, $this->DB_WE);
		$selFields = '';
		foreach($fieldnames as $key => $val){
			if(!$val || $val === '_'){ // bug #4657
				continue;
			}
			if(!is_numeric($key) && $val){
				switch($key){
					case 'DefaultDesc':
						$selFields .= OBJECT_X_TABLE . $classID . '.`' . $val . '` AS we_Description,' . OBJECT_X_TABLE . $classID . '.`' . $val . '` AS WE_Description,';
						break;
					case 'DefaultTitle':
						$selFields .= OBJECT_X_TABLE . $classID . '.`' . $val . '` AS we_Title,' . OBJECT_X_TABLE . $classID . '.`' . $val . '` AS WE_Title,';
						break;
					case 'DefaultKeywords':
						$selFields .= OBJECT_X_TABLE . $classID . '.`' . $val . '` AS we_Keywords,' . OBJECT_X_TABLE . $classID . '.`' . $val . '` AS WE_Keywords,';
						break;
				}
			}
		}
		$fields = array_keys(getHash('SELECT * FROM ' . OBJECT_FILES_TABLE . ' LIMIT 1'));
		$extraFields = '';
		//FIXME: change we_wedoc_ to OF_ when columns from table have been removed; the latter is then obsolete as well
		foreach($fields as $cur){
			$extraFields.=',of.' . $cur . ' AS we_wedoc_' . $cur;
		}
		$f = 'of.ID,of.Templates AS OF_Templates,of.ID AS OF_ID,of.Category AS OF_Category,of.Text AS OF_Text,of.Url AS OF_Url,of.TriggerID AS OF_TriggerID,of.WebUserID AS OF_WebUserID,of.Language AS OF_Language' .
			$extraFields . ',' . ($selFields ? $selFields : '');
		$charclass = '[\!\=%&\(\)\*\+\.\/<>\|~, ]';
		foreach($matrix as $n => $p){
			$n2 = $n;
			if(strpos($n, 'we_object_') === 0){
				$n = substr($n, 10);
			}
			$f .= '`' . $p['table'] . '`.`' . $p['type'] . '_' . $n . '` AS `we_' . $n2 . '`,';
			$from[] = $p['table'];
			if($useTable2){
				$from[] = $p['table2'];
			}
			if(($pos = array_search($n, $orderArr)) !== false){
				$ordertmp[$pos] = '`' . $p['table'] . '`.`' . $p['type'] . '_' . $n . '`' . ($descArr[$pos] ? ' DESC' : '');
			}
			$cond = preg_replace('/(' . $charclass . ')' . $n . '(' . $charclass . ')/', '${1}' . $p['table'] . '.`' . $p['type'] . '_' . $n . '`$2', $cond);
		}
		$cond = preg_replace_callback("/'([^']*)'/", function (array $match){
			return "'" . preg_replace_callback("/&([^;]+);/", function (array $match){
					return chr($match[1]);
				}, $match[1]) . "'";
		}, $cond);

		$cond = strtr($cond, array(
			'we_creationdate' => 'of.CreationDate',
			'wedoc_CreationDate' => 'of.CreationDate',
			'wedoc_ModDate' => 'of.ModDate',
			'we_moddate' => 'of.ModDate',
			'wedoc_Published' => 'of.Published',
			'we_published' => 'of.Published',
			'wedoc_ParentID' => 'of.ParentID',
			'wedoc_Text' => 'of.Text',
			'we_filename' => 'of.Text',
			'WE_ID' => 'of.ID',
			'we_id' => 'of.ID',
			'we_path' => 'of.Path',
		));

		foreach($orderArr as $pos => $curOrd){
			switch(strtolower($curOrd)){
				case 'wedoc_id':
				case 'we_id':
					$ordertmp[$pos] = 'of.ID' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'wedoc_filename':
				case 'we_filename':
					$ordertmp[$pos] = 'of.Text' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'wedoc_path':
				case 'we_path':
					$ordertmp[$pos] = 'of.Path' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'wedoc_published':
				case 'we_published':
					$ordertmp[$pos] = 'of.Published' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'wedoc_moddate':
				case 'we_moddate':
					$ordertmp[$pos] = 'of.ModDate' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'wedoc_creationdate':
				case 'we_creationdate':
					$ordertmp[$pos] = 'of.CreationDate' . ($descArr[$pos] ? ' DESC' : '');
					break;
				case 'random()':
					$ordertmp = [];
					$order = 'RANDOM ';
					break 2;
			}
		}
		if($ordertmp){
			ksort($ordertmp);
			$order = implode(',', $ordertmp);
		}
		$tb = array_unique($from);

		$publ_cond = [];
		foreach($tb as &$t){
			$t = '`' . $t . '`';
			$publ_cond[] = '(' . $t . '.OF_Published>0 OR ' . $t . '.OF_ID=0)';
		}

		return array(//FIXME: maybe random can be changed by time%ID or sth. which is faster and quite rand enough
			'fields' => rtrim($f, ',') . ($order === 'RANDOM ' ? ', RAND() AS RANDOM ' : ''),
			'order' => trim($order) ? ' ORDER BY ' . trim($order) : '',
			'tables' => implode(' JOIN ', $tb),
			'groupBy' => (count($tb) > 1) ? ' GROUP BY ' . OBJECT_X_TABLE . $classID . '.OF_ID ' : '',
			'publ_cond' => $publ_cond ? ' ( ' . implode(' AND ', $publ_cond) . ' ) ' : '',
			'cond' => trim($cond)
		);
	}

	public function getCustomerRestrictionQuery($specificCustomersQuery, $classID, $mfilter, $listQuery){
		return //at least check only documents of the specified class
			'FROM ' . CUSTOMER_FILTER_TABLE . ' cf JOIN ' . OBJECT_X_TABLE . $classID . ' obx ON (cf.modelId=obx.OF_ID AND cf.modelTable="' . stripTblPrefix(OBJECT_FILES_TABLE) . '") WHERE ' . $mfilter . ' AND (' . $listQuery . ' OR ' . $specificCustomersQuery . ')';
	}

	public function getFoundDocument(){
		static $doc = null;
		static $id = 0;
		if($id == ($docID = $this->f('WE_ID'))){
			return $doc;
		}
		$id = $docID;
		$model = new we_objectFile();
		$model->initByID($docID, OBJECT_FILES_TABLE);
		$doc = $model;
		return $doc;
	}

}
