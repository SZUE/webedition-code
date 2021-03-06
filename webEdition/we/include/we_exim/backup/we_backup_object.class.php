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
class we_backup_object extends we_object{
	private $_ObjectBaseElements = array(
		'ID', 'OF_ID', 'OF_ParentID', 'OF_Text', 'OF_Path', 'OF_Url', 'OF_TriggerID', 'OF_Workspaces', 'OF_ExtraWorkspaces', 'OF_ExtraWorkspacesSelected',
		'OF_Templates', 'OF_ExtraTemplates', 'OF_Category', 'OF_Published', 'OF_IsSearchable', 'OF_Charset', 'OF_WebUserID', 'OF_Language', 'variant_weInternVariantElement'
	);
	protected $isAddFieldNoSave = false;
	protected $isModifyFieldNoSave = false;
	protected $isDropFieldNoSave = false;
	protected $isForceDropOnSave = false;

	public function __construct(){
		parent::__construct();
		$this->ClassName = 'we_object'; //for we_backup_object, otherwise ist will save its own classname, or needs its own constructor
	}

	function saveToDB(){
		$this->wasUpdate = $this->ID > 0;

		$this->i_savePersistentSlotsToDB();
		$ctable = OBJECT_X_TABLE . intval($this->ID);

		if(!$this->wasUpdate){
			$qarr = array(
				'OF_ID' => 'BIGINT NOT NULL',
				'OF_ParentID' => 'BIGINT NOT NULL',
				'OF_Text' => 'VARCHAR(255) NOT NULL',
				'OF_Path' => 'VARCHAR(255) NOT NULL',
				'OF_Url' => 'VARCHAR(255) NOT NULL',
				'OF_TriggerID' => 'BIGINT NOT NULL  default "0"',
				'OF_Workspaces' => 'VARCHAR(255) NOT NULL',
				'OF_ExtraWorkspaces' => 'VARCHAR(255) NOT NULL',
				'OF_ExtraWorkspacesSelected' => 'VARCHAR(255) NOT NULL',
				'OF_Templates' => 'VARCHAR(255) NOT NULL',
				'OF_ExtraTemplates' => 'VARCHAR(255) NOT NULL',
				'OF_Category' => 'VARCHAR(255) NOT NULL',
				'OF_Published' => 'int(11) NOT NULL',
				'OF_IsSearchable' => 'tinyint(1) NOT NULL default "1"',
				'OF_Charset' => 'VARCHAR(64) NOT NULL',
				'OF_WebUserID' => 'BIGINT NOT NULL',
				'OF_Language' => 'VARCHAR(5) default "NULL"',
			);

			$indexe = array(
				'PRIMARY KEY (OF_ID)',
			);

			$this->SerializedArray = we_unserialize($this->DefaultValues);
			$this->SerializedArray = is_array($this->SerializedArray) ? $this->SerializedArray : array();

			$noFields = array('WorkspaceFlag', 'elements', 'WE_CSS_FOR_CLASS');
			foreach($this->SerializedArray as $key => $value){
				if(!in_array($key, $noFields)){
					$arr = explode('_', $key);
					$len = isset($value['length']) ? $value['length'] : $this->getElement($key . 'length', "dat");
					$type = $this->switchtypes2($arr[0], $len);
					if(!empty($type)){
						$qarr[$key] = $type;
//add index for complex queries
						if($arr[0] === 'object'){
							$indexe[] = 'KEY ' . $key . ' (' . $key . ')';
						}
					}
				}
			}

			$this->DB_WE->delTable($ctable);
			$this->DB_WE->addTable($ctable, $qarr, $indexe);

//dummy eintrag schreiben
			$this->DB_WE->query('INSERT INTO ' . $ctable . ' SET OF_ID=0');


// folder in object schreiben
			if(!($this->OldPath && ($this->OldPath != $this->Path))){
				$fold = new we_class_folder();
				$fold->initByPath($this->getPath(), OBJECT_FILES_TABLE);
			}

////// resave the line O to O.....
			$this->DB_WE->query('REPLACE INTO ' . $ctable . ' SET OF_ID=0');
////// resave the line O to O.....
		} else {
			$this->SerializedArray = we_unserialize($this->DefaultValues);
			$this->SerializedArray = is_array($this->SerializedArray) ? $this->SerializedArray : array();

			$noFields = array('WorkspaceFlag', 'elements', 'WE_CSS_FOR_CLASS');
			$tableInfo = $this->DB_WE->metadata($ctable, true);

			$add = $drop = $alter = $addKey = array();

			foreach($this->SerializedArray as $fieldname => $value){
				$arr = explode('_', $fieldname);
				if(!isset($arr[1])){
					continue;
				}

				$fieldtype = $this->getFieldType($arr[0]);
				$len = (isset($value['length']) ? $value['length'] : 0);
				$type = $this->switchtypes2($arr[0], $len);
				$isObject = ($arr[0] === 'object');

				if(isset($tableInfo['meta'][$fieldname])){
					$props = $tableInfo[$tableInfo['meta'][$fieldname]];
// the field exists
					if(!empty($fieldtype) && (strtolower($fieldtype) == strtolower($props['type'])) && $len != $props['len']){
						$alter[$fieldname] = $type;
					}
				} elseif(!empty($type)){
					$add[$fieldname] = $type;
					if($isObject){
						$addKey[$fieldname . '_key'] = ' INDEX (`' . $fieldname . '`)';
					}
				}
			}

			if(isset($tableInfo['meta'])){
				foreach($tableInfo['meta'] as $key => $value){
					if(!isset($this->SerializedArray[$key]) && substr($key, 0, 3) != 'OF_' && $key != 'ID'){
						$drop[$key] = $key;
					}
				}
			}

//FIXME: deactivated for #9899 - some elements are not present (e.g. object-references) & will be deleted therefore
//With $this->isForceDropOnSave drops can be activated
			if($this->isForceDropOnSave){
				foreach($drop as $key => $value){
					$this->DB_WE->delCol($ctable, $value);
				}
			}

			foreach($alter as $key => $value){
				$this->DB_WE->changeColType($ctable, $key, $value);
			}

			foreach($add as $key => $value){
				$this->DB_WE->addCol($ctable, $key, $value);
			}
			foreach($addKey as $key => $value){
				$this->DB_WE->addKey($ctable, $value);
			}
		}

		unset($this->elements);
		$this->i_getContentData();
	}

	function getFieldType($type){
		switch($type){
			case 'country':
			case 'language':
			case 'meta':
			case 'link':
				return 'meta';
			case 'href':
			case 'input':
				return 'string';
			case 'float':
				return 'real';
			case 'img':
			case 'flashmovie':
			case 'quicktime':
			case 'binary':
			case 'object':
			case 'date':
			case 'checkbox':
			case 'int':
				return 'int';
			case 'text':
				return 'longtext';
		}
		return '';
	}

	private function switchtypes2($type, $len = 0){
		switch($type){
			case "meta":
				return " VARCHAR(" . (($len > 0 && ($len < 256)) ? $len : 255) . ") NOT NULL ";
			case "date":
				return " INT(11) NOT NULL ";
			case "input":
				return " VARCHAR(" . (($len > 0 && ($len < 4096)) ? $len : 255) . ") NOT NULL ";
			case "country":
			case "language":
				return " VARCHAR(2) NOT NULL ";
			case "link":
			case "href":
				return " TEXT NOT NULL ";
			case "text":
				return " LONGTEXT NOT NULL ";
			case "img":
			case "flashmovie":
			case "quicktime":
			case "binary":
				return " INT(11) DEFAULT '0' NOT NULL ";
			case "checkbox":
				return " INT(1) DEFAULT '0' NOT NULL";
			case "int":
				return " INT(" . (($len > 0 && ($len < 256)) ? $len : "11") . ") DEFAULT NULL ";
			case "float":
				return " DOUBLE DEFAULT NULL ";
			case "object":
				return " BIGINT(20) DEFAULT '0' NOT NULL ";
			case we_objectFile::TYPE_MULTIOBJECT:
				return " TEXT NOT NULL ";
			case 'shopVat':
				return ' TEXT NOT NULL';
		}
		return '';
	}

	function isFieldExists($name, $type = ''){
		$this->SerializedArray = we_unserialize($this->DefaultValues);
		$noFields = array('WorkspaceFlag', 'elements', 'WE_CSS_FOR_CLASS');
		foreach($this->SerializedArray as $fieldname => $value){
			$arr = explode('_', $fieldname);
			if(!isset($arr[0]))
				continue;
			$fieldtype = $arr[0];
			unset($arr[0]);
			$fieldname = implode('_', $arr);
			if($type === ''){
				if($fieldname == $name){
					return true;
				}
			} elseif($fieldname == $name && $fieldtype == $type){
				return true;
			}
		}
		return false;
	}

	function getFieldPrefix($name){
		$this->SerializedArray = we_unserialize($this->DefaultValues);
//$noFields = array('WorkspaceFlag', 'elements', 'WE_CSS_FOR_CLASS');
		foreach(array_keys($this->SerializedArray) as $fieldname){
			$arr = explode('_', $fieldname);
			if(!isset($arr[1])){
				continue;
			}
			$fieldtype = $arr[0];
			unset($arr[0]);
			$fieldname = implode('_', $arr);
			if($fieldname == $name){
				return $fieldtype;
			}
			return false;
		}
	}

	function getDefaultArray($name, $type = '', $default = ''){
		$defaultArr = array(
			'default' => '',
			'defaultThumb' => '',
			'defaultdir' => '',
			'rootdir' => '',
			'autobr' => '',
			'dhtmledit' => '',
			'commands' => '',
			'contextmenu' => '',
			'height' => 200,
			'width' => 618,
			'class' => '',
			'max' => '',
			'cssClasses' => '',
			'fontnames' => '',
			'fontsizes' => '',
			'formats' => '',
			'tinyparams' => '',
			'templates' => '',
			'xml' => '',
			'removefirstparagraph' => '',
			'showmenus' => '',
			'forbidhtml' => '',
			'forbidphp' => '',
			'inlineedit' => '',
			'users' => '',
			'required' => '',
			'editdescription' => '',
			'int' => '',
			'intID' => '',
//			'intPath' => '',
			'hreftype' => '',
			'hrefdirectory' => '',
			'hreffile' => '',
			'shopcatField' => '',
			'shopcatShowPath' => 'true',
			'shopcatRootdir' => '',
			'shopcatLimitChoice' => 0,
			'uniqueID' => md5(uniqid(__FUNCTION__, true)),
		);
		switch($type){
			case 'text':
			case 'input':
			case 'int':
				$defaultArr['meta'] = array($type . '_' . $name . 'defaultkey0' => '');
				break;
			case we_objectFile::TYPE_MULTIOBJECT:
				$defaultArr['meta'] = array('');
				break;
		}

		if($default != '' && is_array($default)){
			foreach($default as $k => $v){
				$defaultArr[$k] = $v;
			}
		}
		return $defaultArr;
	}

	function renameField($name, $newname){
		$ctable = OBJECT_X_TABLE . intval($this->ID);
		$this->wasUpdate = true;
		$this->SerializedArray = we_unserialize($this->DefaultValues);
		$type = $this->getFieldPrefix($name);
		$this->SerializedArray[$type . '_' . $newname] = $this->SerializedArray[$type . '_' . $name];
		unset($this->SerializedArray[$type . '_' . $name]);
		$this->DefaultValues = we_serialize($this->SerializedArray);
		$this->DB_WE->renameCol($ctable, $type . '_' . $name, $type . '_' . $newname);
		$this->DB_WE->changeColType($ctable, $type . '_' . $newname, $this->switchtypes2($type));
		unset($this->elements);
		$this->i_savePersistentSlotsToDB();
		$this->i_getContentData();
	}

	function addField($name, $type = '', $default = ''){
		$defaultArr = $this->getDefaultArray($name, $type, $default);
		$this->SerializedArray = we_unserialize($this->DefaultValues);
		$this->SerializedArray[$type . '_' . $name] = $defaultArr;
		$this->DefaultValues = we_serialize($this->SerializedArray);
		if(isset($this->strOrder)){
			$arrOrder = explode(',', $this->strOrder);
			$arrOrder[] = max($arrOrder) + 1;
			$this->strOrder = implode(',', $arrOrder);
		} else {
			$this->strOrder = '';
		}
		if($this->isAddFieldNoSave){
			return true;
		}
		return $this->saveToDB(true);
	}

	function dropField($name, $type = ''){
		$this->SerializedArray = we_unserialize($this->DefaultValues);
		$isfound = false;
		foreach($this->SerializedArray as $field => $value){
			$arr = explode('_', $field);
			if(!isset($arr[1])){
				continue;
			}
			$fieldtype = $arr[0];
			unset($arr[0]);
			$fieldname = implode('_', $arr);
			if($type === ''){
				if($fieldname == $name){
					unset($this->SerializedArray[$field]);
					$isfound = true;
					break;
				}
			} elseif($fieldname == $name && $fieldtype == $type){
				unset($this->SerializedArray[$field]);
				$isfound = true;
				break;
			}
		}
		if($isfound){
			$this->DefaultValues = we_serialize($this->SerializedArray);
			$arrOrder = explode(',', $this->strOrder);

			unset($arrOrder[array_search(max($arrOrder), $arrOrder)]);

			$this->strOrder = implode(',', $arrOrder);
			if($this->isDropFieldNoSave){
				return true;
			}
			return $this->saveToDB(true);
		}

		return false;
	}

	function modifyField($name, $newtype, $type, $default = '', $delete = ''){
		$this->SerializedArray = we_unserialize($this->DefaultValues);
		$defaultArr = $this->SerializedArray[$type . '_' . $name];
		if($newtype == $type){
			if($default != '' && is_array($default)){
				foreach($default as $k => $v){
					$defaultArr[$k] = $v;
				}
				if($delete != '' && is_array($delete)){
					foreach($delete as $delkey){
						unset($defaultArr[$delkey]);
					}
				}
			} else {
				$defaultArr = $this->getDefaultArray($name, $newtype, $default);
			}
			$this->SerializedArray[$type . '_' . $name] = $defaultArr;
		} else {
			unset($this->SerializedArray[$type . '_' . $name]);
			if($default != '' && is_array($default)){
				foreach($default as $k => $v){
					$defaultArr[$k] = $v;
				}
				if($delete != '' && is_array($delete)){
					foreach($delete as $delkey){
						unset($defaultArr[$delkey]);
					}
				}
			} else {
				$defaultArr = $this->getDefaultArray($newtype, $default);
			}
			$this->SerializedArray[$newtype . '_' . $name] = $defaultArr;
		}
		$this->DefaultValues = we_serialize($this->SerializedArray);

		return ($this->isModifyFieldNoSave ?
				true :
				$this->saveToDB(true));
	}

	function resetOrder(){
		unset($this->elements['we_sort']);
		$this->setSort();
		$we_sort = $this->getElement('we_sort');
		$this->strOrder = implode(',', $we_sort);
		$this->we_save();
	}

	function setOrder($order, $writeToDB = false){
		$ctable = OBJECT_X_TABLE . intval($this->ID);
		$metadata = $this->DB_WE->metadata($ctable, true);
		if(is_array($order) && $writeToDB){
			$last = '';
			foreach($order as $oval){
				if($last === ''){
					$last = 'OF_Language';
				}
				$ovalname = $this->getFieldPrefix($oval) . '_' . $oval;
				if(array_key_exists($ovalname, $metadata['meta'])){
					$nummer = $metadata['meta'][$ovalname];
					$type = $metadata[$nummer]['type'];
					if($type === 'string'){
						$len = $metadata[$nummer]['len'];
						$type = 'VARCHAR(' . $len . ')';
					}
					$this->DB_WE->moveCol($ctable, $ovalname, $last);
					//query('ALTER TABLE ' . $ctable . ' MODIFY COLUMN ' . $ovalname . ' ' . $type . ' AFTER ' . $last);
					$last = $ovalname;
				} else {
					t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ') Field not found: Field: ' . $ovalname);
				}
			}
		}
		if(is_array($order) && !$writeToDB){

			$metas = array_keys($metadata['meta']);
			$consider = array_diff($metas, $this->_ObjectBaseElements);
			$consider = array_combine(range(0, count($consider) - 1), $consider);
			$neworder = array();
			foreach($order as $oval){
				$zw = $this->getFieldPrefix($oval) . '_' . $oval;
				if($zw){
					$neworder[] = $zw;
				} else {
					t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ')  No Field-Prefix found in for ' . $oval);
				}
			}
			if(count($neworder) != count($consider)){
				if(count($neworder) > count($consider)){
					$thedifference = array_diff($neworder, $consider);
					t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ')  Order-Array (' . count($neworder) . ') has larger length than generated Fields Array (' . count($consider) . '), Missing: (' . implode(',', $thedifference) . ') Order-Array:(' . implode(',', $neworder) . ') Fields-Array:(' . implode(',', $consider) . ') ');
				} else {
					$thedifference = array_diff($consider, $neworder);
					t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ')  Order-Array (' . count($neworder) . ') has smaller length than generated Fields Array (' . count($consider) . '), Missing: (' . implode(',', $thedifference) . ') Order-Array:(' . implode(',', $neworder) . ') Fields-Array:(' . implode(',', $consider) . ') ');
				}
			} else {
				$neworder = array_flip($neworder);
				$theorder = array();
				foreach($consider as $ck => $cv){
					$theorder[str_replace('.', '', uniqid(__FUNCTION__, true))] = $neworder[$cv];
				}
				$this->setElement("we_sort", $theorder);
				$this->strOrder = implode(',', $theorder);
				$this->saveToDB();
			}
		}
	}

	function getFieldsOrdered($withoutPrefix = false){
		$ctable = OBJECT_X_TABLE . intval($this->ID);
		$metadata = $this->DB_WE->metadata($ctable, true);
		$metas = array_keys($metadata['meta']);
		$consider = array_diff($metas, $this->_ObjectBaseElements);
		if($withoutPrefix){
			foreach($consider as &$value){
				$zw = explode('_', $value, 2);
				$value = $zw[1];
			}
		}
		if(!empty($consider)){
			$consider = array_values($consider);
			$akeys = explode(',', $this->strOrder);
			$order = array();
			foreach($akeys as $k => $v){
				$order[] = $consider[$v];
			}
			return $order;
		}
		return false;
	}

	function checkFields($fields){
		$ctable = OBJECT_X_TABLE . intval($this->ID);
		$metadata = $this->DB_WE->metadata($ctable, true);
		$metas = array_keys($metadata['meta']);
		$consider = array_diff($metas, $this->_ObjectBaseElements);
		$theKeys = explode(',', $this->strOrder);
		if(count($theKeys) != count($consider)){
			$this->resetOrder();
			$theKeys = explode(',', $this->strOrder);
		}
		if(count($theKeys) == count($consider)){
			$consider = array_combine($theKeys, $consider);
			$isOK = true;
			foreach($fields as $field){
				if(!in_array($field, $consider)){
					t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ')  Field ' . $field . ' not found');
					$isOK = false;
				}
			}
			return $isOK;
		} else {
			t_e('warning', __METHOD__ . ' ' . $ctable . ' (' . $this->Text . ') different field count - not recoverable bei resetOrder strOrder');
		}
	}

	/* setter for for property isAddFieldNoSave which allows to construct Classes from within Apps */

	function setIsAddFieldNoSave($isAddFieldNoSave){
		$this->isAddFieldNoSave = $isAddFieldNoSave;
	}

	/* getter for for property isAddFieldNoSave which allows to construct Classes from within Apps */

	function getIsAddFieldNoSave(){
		return $this->isAddFieldNoSave;
	}

	/* setter for for property isModifyFieldNoSave which allows to construct Classes from within Apps */

	function setIsModifyFieldNoSave($isModifyFieldNoSave){
		$this->isModifyFieldNoSave = $isModifyFieldNoSave;
	}

	/* getter for property isModifyFieldNoSave which allows to construct Classes from within Apps */

	function getIsModifyFieldNoSave(){
		return $this->isModifyFieldNoSave;
	}

	/* setter for property isDropFieldNoSave which allows to construct Classes from within Apps */

	function setIsDropFieldNoSave($isDropFieldNoSave){
		$this->isDropFieldNoSave = $isDropFieldNoSave;
	}

	/* getter for property isDropFieldNoSave which allows to construct Classes from within Apps */

	function getIsDropFieldNoSave(){
		return $this->isDropFieldNoSave;
	}

	/* setter for property isForceDropOnSave which allows to construct Classes from within Apps */

	function setIsForceDropOnSave($isForceDropOnSave){
		$this->isForceDropOnSave = $isForceDropOnSave;
	}

	/* getter for property isForceDropOnSave which allows to construct Classes from within Apps */

	function getIsForceDropOnSave(){
		return $this->isForceDropOnSave;
	}

}
