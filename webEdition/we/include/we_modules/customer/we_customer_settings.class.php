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
 * General Definition of Customer's Prefers
 *
 */
define('DATE_FORMAT', 'Y-m-d H:i:s');
define('DATE_ONLY_FORMAT', 'Y-m-d');

class we_customer_settings{
	private $db;
	public $customer;
	public $properties = array();
	private $changedFieldTypes = array(
		'dateTime' => 'varchar(24)',
		'country' => 'varchar(4)',
		'language' => 'varchar(2)',
		'select' => 'varchar(200)',
	);
	public $field_types = array(
		'input' => 'varchar(255)',
		'number' => 'int(11)',
		'select' => 'enum',
		'multiselect' => 'set',
		'textarea' => 'text',
		'dateTime' => 'datetime',
		'date' => 'date',
		'password' => 'varchar(32)',
		'img' => 'bigint(20)',
		'country' => "enum('AF','AX','AL','DZ','AS','AD','AO','AI','AQ','AG','AR','AM','AW','AU','AT','AZ','BS','BH','BD','BB','BY','BE','BZ','BJ','BM','BT','BO','BQ','BA','BW','BV','BR','IO','BN','BG','BF','BI','KH','CM','CA','CV','KY','CF','TD','CL','CN','CX','CC','CO','KM','CG','CD','CK','CR','CI','HR','CU','CW','CY','CZ','DK','DJ','DM','DO','EC','EG','SV','GQ','ER','EE','ET','FK','FO','FJ','FI','FR','GF','PF','TF','GA','GM','GE','DE','GH','GI','GR','GL','GD','GP','GU','GT','GG','GN','GW','GY','HT','HM','VA','HN','HK','HU','IS','IN','ID','IR','IQ','IE','IM','IL','IT','JM','JP','JE','JO','KZ','KE','KI','KP','KR','KW','KG','LA','LV','LB','LS','LR','LY','LI','LT','LU','MO','MK','MG','MW','MY','MV','ML','MT','MH','MQ','MR','MU','YT','MX','FM','MD','MC','MN','ME','MS','MA','MZ','MM','NA','NR','NP','NL','NC','NZ','NI','NE','NG','NU','NF','MP','NO','OM','PK','PW','PS','PA','PG','PY','PE','PH','PN','PL','PT','PR','QA','RE','RO','RU','RW','BL','SH','KN','LC','MT','PM','VC','WS','SM','ST','SA','SN','SR','SC','SL','SG','SX','SK','SI','SB','SO','ZA','GS','ES','LK','SD','SR','SJ','SZ','SE','CH','SY','TW','TJ','TZ','TH','TL','TG','TK','TO','TT','TN','TR','TM','TC','TV','UG','UA','AE','GB','US','UM','UY','UZ','VU','VA','VE','VN','VG','VI','WF','EH','YE','YU','ZM','ZW')",
		'language' => "enum('ab','af','an','ar','as','az','be','bg','bn','bo','br','bs','ca','ce','co','cs','cu','cy','da','de','el','en','eo','es','et','eu','fa','fi','fj','fo','fr','fy','ga','gd','gl','gv','he','hi','hr','ht','hu','hy','id','is','it','ja','jv','ka','kg','ko','ku','kw','ky','la','lb','li','ln','lt','lv','mg','mk','mn','mo','ms','mt','my','nb','ne','nl','nn','no','oc','pl','pt','rm','ro','ru','sc','se','sk','sl','so','sq','sr','sv','sw','tk','tr','ty','uk','ur','uz','vi','vo','yi','zh')",
	);
	private $special_field_types = array(
		'select' => 'enum',
		'multiselect' => 'set',
	);
	public $FieldAdds = array();
	public $SortView = array();
	private $Prefs = array(
		'treetext_format' => '#Username (#Forename #Surname)',
		'start_year' => 1900,
		'default_sort_view' => '',
		'default_order' => '',
	);
	private $EditSort = '';
	public $OrderTable = array(
		'ASC' => 'ASC',
		'DESC' => 'DESC'
	);
	public $FunctionTable = array(
		'FIRSTCHAR' => 'UPPER(SUBSTRING(%s,1,1))',
		/* 'ALPH2' => 'UPPER(SUBSTRING(%s,1,2))',
		  'ALPH3' => 'UPPER(SUBSTRING(%s,1,3))', */
		'MINUTE' => 'DATE_FORMAT(%s,\'%%i\')',
		'HOUR' => 'DATE_FORMAT(%s,\'%%H\')',
		'DAY' => 'DAYOFMONTH(%s)',
		'MONTH' => 'MONTH(%s)',
		'YEAR' => 'YEAR(%s)',
		'DAYOFWEEK' => 'DAYOFWEEK(%s)',
		'DAYOFMONTH' => 'DAYOFMONTH(%s)',
		'DAYOFYEAR' => 'DAYOFYEAR(%s)',
		'DAYNAME' => 'DAYNAME(%s)',
		'MONTHNAME' => 'MONTHNAME(%s)',
		'QUARTER' => 'QUARTER(%s)'
	);
	private $TypeFunction = array(
		'FIRSTCHAR' => 'input,select,textarea,password',
		/* 'ALPH2' => 'input,select,textarea,password',
		  'ALPH3' => 'input,select,textarea,password', */
		'MINUTE' => 'date',
		'HOUR' => 'date',
		'DAY' => 'date',
		'MONTH' => 'date',
		'YEAR' => 'date',
		'DAYOFWEEK' => 'date',
		'DAYOFMONTH' => 'date',
		'DAYOFYEAR' => 'date',
		'DAYNAME' => 'date',
		'MONTHNAME' => 'date',
		'QUARTER' => 'date'
	);
	private $PropertyTitle = array();
	private $MaxSearchResults = 99999;
	private $reservedWords = array('select', 'straight_join', 'sql_small_result', 'sql_buffer_result',
		'sql_cache', 'sql_no_cache', 'sql_cals_found_rows', 'high_priority', 'distinct', 'distinctrow', 'all', 'into',
		'outfile', 'dumpfile', 'from', 'where', 'group', 'by', 'asc', 'desc', 'with', 'rollup', 'having', 'order', 'limit',
		'procedure', 'for', 'update', 'lock', 'in', 'share', 'mode', 'insert', 'alter', 'grant', 'option', 'to', 'require',
		'none', 'revoke', 'privileges', 'password', 'low_priority', 'delayed', 'ignore', 'values', 'on', 'duplicate',
		'key', 'set', 'enum', 'default', 'where', 'group', 'by', 'order', 'add', 'column', 'table', 'index', 'constraint', 'primary',
		'unique', 'foreign', 'change', 'modify', 'drop', 'disable', 'enable', 'character', 'collate', 'first', 'rename',
		'fulltext', 'quick', 'using', 'truncate',
		'id', 'username', 'isfolder', 'icon', 'parentid', 'membersince', 'lastlogin', 'lastaccess', 'path', 'text', 'forename', 'surname', 'logindenied', 'autologin', 'autologindenied'
	);
	public $treeTextFormatSQL = '';
	public $formatFields = array();

	function __construct(){
		$this->db = new DB_WE();
		$this->customer = new we_customer_customer();
		$this->properties = array(
			'default_saveRegisteredUser_register' => 'false',
		);

		$this->PropertyTitle = array(
			'Username' => g_l('modules_customer', '[username]'),
			'Password' => g_l('modules_customer', '[password]'),
			'Forename' => g_l('modules_customer', '[Forname]'),
			'Surname' => g_l('modules_customer', '[Surname]'),
			'LoginDenied' => g_l('modules_customer', '[login]'),
			'AutoLoginDenied' => g_l('modules_customer', '[autologin]'),
			'AutoLogin' => g_l('modules_customer', '[autologin]'),
			'MemberSince' => g_l('modules_customer', '[MemeberSince]'),
			'LastLogin' => g_l('modules_customer', '[LastLogin]'),
			'LastAccess' => g_l('modules_customer', '[LastAccess]'),
			'ID' => 'ID',
		);
	}

	function load($tryFromSession = true){
		$modified = false;
		$this->db->query('SELECT pref_name,pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="webadmin"');
		while($this->db->next_record()){
			$this->properties[$this->db->f('pref_name')] = $this->db->f('pref_value');
		}

		if(isset($this->properties['SortView'])){
			$this->SortView = we_unserialize($this->properties['SortView']);
		}
		if(!is_array($this->SortView)){
			$this->SortView = array();
		}

		if(isset($this->properties['EditSort'])){
			$this->EditSort = $this->properties['EditSort'];
		} else {
			$orderedarray = $this->customer->persistent_slots;
			$sortarray = range(0, count($orderedarray) - 1);
			$this->EditSort = implode(',', $sortarray);
		}


		if(isset($this->properties['FieldAdds'])){
			$this->FieldAdds = we_unserialize($this->properties['FieldAdds']);
			//check if all fields are set
			$fields = $this->customer->getFieldset();
			foreach($fields as $name){
				$old = '';
				if(!isset($this->FieldAdds[$name]['type']) || $old=$this->FieldAdds[$name]['type'] === 'input'){
					$tmp = $this->customer->getFieldDbProperties($name);
					$this->FieldAdds[$name]['type'] = $this->getOldFieldType($tmp['Type'], $this->FieldAdds[$name]['default']);
					$modified |= ($old != $this->FieldAdds[$name]['type']);
				}
				if(!isset($this->FieldAdds[$name]['encrypt'])){
					$modified = true;
					$this->FieldAdds[$name]['encrypt'] = 0;
				}
			}
		}

		$defprefs = $this->Prefs;
		if(isset($this->properties['Prefs'])){
			$this->Prefs = we_unserialize($this->properties['Prefs']);
		}

		foreach($defprefs as $k => $v){
			if(!isset($this->Prefs[$k])){
				$this->Prefs[$k] = $v;
			}
		}

		$this->treeTextFormatSQL = 'COALESCE(NULLIF(TRIM(CONCAT("' . $this->Prefs['treetext_format'] . '")),""),Username)';
		$field_names = array_keys($this->customer->getFieldsDbProperties());

		foreach($field_names as $fieldname){
			if(strpos($this->treeTextFormatSQL, '#' . $fieldname) !== false){
				$this->formatFields[] = $fieldname;
			}
			$this->treeTextFormatSQL = str_replace('#' . $fieldname, '",`' . $fieldname . '`,"', $this->treeTextFormatSQL);
		}

		if($modified){
			$this->save();
		}
	}

	function save(){
		//FIXME: make Fieldadds more fields in DB
		$this->properties['FieldAdds'] = we_serialize($this->FieldAdds, SERIALIZE_JSON);
		$this->properties['SortView'] = we_serialize($this->SortView, SERIALIZE_JSON);
		$this->properties['EditSort'] = $this->EditSort;
		$this->properties['Prefs'] = we_serialize($this->Prefs, SERIALIZE_JSON);

		foreach($this->properties as $key => $value){
			$this->db->query('REPLACE INTO ' . SETTINGS_TABLE . ' SET tool="webadmin",pref_value="' . $this->db->escape($value) . '",pref_name="' . $key . '"');
		}
		return true;
	}

	function getEditSort(){
		return $this->EditSort;
	}

	function setEditSort($sortstring){
		$this->EditSort = $sortstring;
	}

	function getPref($pref_name){
		return $this->properties[$pref_name];
	}

	function setPref($pref_name, $pref_value){
		$this->properties[$pref_name] = $pref_value;
	}

	function isFunctionForField($function, $field){
		if(strpos($field, g_l('modules_customer', '[other]') !== FALSE)){
			$field = str_replace(g_l('modules_customer', '[other]') . '_', '', $field);
		}
		$fieldprops = $this->customer->getFieldDbProperties($field);

		$fieldtype = $this->getFieldType($fieldprops['Type']);
		if($fieldtype != ''){
			foreach($this->TypeFunction as $fk => $fv){
				$tmp = explode(',', $fv);
				if(($fk == $function) && (in_array($fieldtype, $tmp))){
					return true;
				}
			}
		}
		return false;
	}

	//returns db field type
	function getDbType($field_type, $fieldname){
		if(isset($this->special_field_types[$field_type])){
			if(empty($this->FieldAdds[$fieldname]['default'])){
				return $this->field_types['input'];
			}
			return $this->special_field_types[$field_type] . '(\'' . implode('\',\'', explode(',', $this->FieldAdds[$fieldname]['default'])) . '\')';
		} else {
			switch($field_type){
				case 'date':
					return $this->field_types[$field_type] . ' DEFAULT "0000-00-00" ';
				case 'dateTime':
					return $this->field_types[$field_type] . ' DEFAULT "0000-00-00 00:00:00" ';
				default:
					return $this->field_types[$field_type];
			}
		}
	}

	function getFieldType($name){
		return isset($this->FieldAdds[$name]) ? $this->FieldAdds[$name]['type'] : (isset($this->FieldAdds['input']) ? $this->FieldAdds['input']['type'] : '');
	}

//returns predefined  field type
	private function getOldFieldType($field_type, &$default){
		$expl = explode('(', $field_type);
		$type = $expl[0];
		foreach($this->field_types as $k => $v){
			if($v == $field_type){
				return $k;
			}

			if($v == $type){
				//if this matches we have to extract fieldvalues
				$data = explode(',', rtrim($expl[1], ')'));
				foreach($data as &$d){
					$d = trim($d, '\'');
				}
				$default = implode(',', $data);
				return $k;
			}
		}
		foreach($this->changedFieldTypes as $k => $v){
			if($v == $field_type){
				return $k;
			}
		}

		return 'input';
	}

	function getPropertyTitle($prop){
		return (isset($this->PropertyTitle[$prop]) ? $this->PropertyTitle[$prop] : $prop);
	}

	function initCustomerWithDefaults(&$customer){
		if(is_array($this->FieldAdds)){
			foreach($this->FieldAdds as $k => $v){
				if(in_array($k, $customer->persistent_slots) && isset($v['default'])){
					$value = $v['default'];
					if($this->getFieldType($k) === 'select'){
						$tmp = explode(',', $value);
						$value = $tmp[0];
					}
					$customer->{$k} = $value;
				}
			}
		}
	}

	//field adds operations
	function storeFieldAdd($fieldName, $addName, $value){
		$this->FieldAdds[$fieldName][$addName] = $value;
	}

	function retriveFieldAdd($fieldName, $addName, $default = ''){
		return isset($this->FieldAdds[$fieldName][$addName]) ? $this->FieldAdds[$fieldName][$addName] : $default;
	}

	function removeFieldAdd($fieldName){
		unset($this->FieldAdds[$fieldName]);
	}

	function renameFieldAdds($old, $new){
		foreach($this->FieldAdds as $k => $v){
			if($k == $old){
				$tmp = $this->FieldAdds[$k];
				unset($this->FieldAdds[$k]);
				$this->FieldAdds[$new] = $tmp;
			}
		}
	}

	function getMaxSearchResults(){
		return $this->MaxSearchResults;
	}

	function isReserved($field){
		return in_array(trim(strtolower($field)), $this->reservedWords);
	}

	function getSettings($settings){
		return (isset($this->Prefs[$settings]) ? $this->Prefs[$settings] : '');
	}

	function setSettings($settings, $value){
		$this->Prefs[$settings] = $value;
	}

	function getAllSettings(){
		return $this->Prefs;
	}

}
