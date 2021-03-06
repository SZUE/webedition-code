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
 * Base class for app models
 *
 * @category   app
 * @package none
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class toolfactory_models_Default extends we_app_Model{
	/**
	 * id attribute
	 *
	 * @var integer
	 */
	public $ContentType = "toolfactory/item";

	/**
	 * _appName attribute
	 *
	 * @var string
	 */
	protected $_appName = 'toolfactory';

	/**
	 * _requiredFields attribute
	 *
	 * @var array
	 */
	public $_requiredFields = array('Text', 'classname');

	/**
	 * classname attribute
	 *
	 * @var string
	 */
	public $classname = '';

	/**
	 * maintable attribute
	 *
	 * @var string
	 */
	public $maintable = '';

	/**
	 * datasource attribute
	 *
	 * @var string
	 */
	public $datasource = 'table:';

	/**
	 * makeTable attribute
	 *
	 * @var boolean
	 */
	public $makeTable = false;

	/**
	 * makeTags attribute
	 *
	 * @var boolean
	 */
	public $makeTags = true;

	/**
	 * makeServices attribute
	 *
	 * @var boolean
	 */
	public $makeServices = true;

	/**
	 * makePerms attribute
	 *
	 * @var boolean
	 */
	public $makePerms = true;

	/**
	 * makeBackup attribute
	 *
	 * @var boolean
	 */
	public $makeBackup = true;

	/**
	 * tags attribute
	 *
	 * @var array
	 */
	public $tags = array();

	/**
	 * services attribute
	 *
	 * @var array
	 */
	public $services = array();

	/**
	 * languages attribute
	 *
	 * @var array
	 */
	public $languages = array();

	/**
	 * permissions attribute
	 *
	 * @var array
	 */
	public $permissions = array();

	/**
	 * backupTables attribute
	 *
	 * @var array
	 */
	public $backupTables = array();

	/**
	 * Constructor
	 *
	 * @param string $toolfactoryID
	 * @return void
	 */
	function __construct($toolfactoryID = 0){
		parent::__construct('');
		if($toolfactoryID){
			$this->{$this->_primaryKey} = $toolfactoryID;
			$this->load($toolfactoryID);
		}

		$this->setPersistentSlots(array('ID', 'Text', 'classname', 'maintable', 'datasource', 'makeTable', 'makeTags', 'makeServices', 'makePerms', 'makeBackup'));
	}

	/**
	 * set Fields
	 *
	 * @param array $fields
	 * @return void
	 */
	public function setFields($fields){
		parent::setFields($fields);
	}

	/**
	 * converts real appname to intern appname
	 *
	 * @param string $realname
	 * @return string
	 */
	function realNameToIntern($name){

		$name = preg_replace('/[^a-z0-9]/', '', strtolower($name));

		return $name;
	}

	/**
	 * Load entry from database
	 *
	 * @param integer $loadId
	 */
	function load($id = 0){

		$myid = $this->realNameToIntern($id);

		$props = we_tool_lookup::getToolProperties($myid);

		if(empty($props)){
			$props = we_tool_lookup::getToolProperties($id);
			$myid = $id;
		}

		foreach($props as $key => $prop){
			$this->$key = $prop;
		}

		$this->appconfig = we_app_Common::getManifest($myid);

		$name = isset($props['text']) ? $props['text'] : $props['classname'];

		$this->Text = htmlspecialchars_decode($name);

		$this->ID = $this->Text;

		$this->tags = we_tool_lookup::getAllToolTags($myid, true);

		$this->services = we_tool_lookup::getAllToolServices($myid, true);

		$this->languages = we_tool_lookup::getAllToolLanguages($myid, '/lang', true);
		if(!($this->languages)){
			$this->languages = array('a', 'b');
		}

		$this->backupTables = we_tool_lookup::getBackupTables($myid, true);

		$appDir = Zend_Controller_Front::getInstance()->getParam('appDir');

		$permFile = WE_APPS_PATH . $props['name'] . '/conf/permission.conf.php';

		if(file_exists($permFile)){
			include ($permFile);
			$this->permissions = $perm_defaults[$perm_group_name];
		}
	}

	/**
	 * replace TOOLNAME and CLASSNAME in created files
	 * @param string $name
	 * @param string $TOOLNAME
	 * @param string $CLASSNAME
	 * @return string
	 */
	function getNewFileName($name, $TOOLNAME, $CLASSNAME){

		$_newname = str_replace("TOOLNAME", $TOOLNAME, $name);
		$_newname = str_replace("CLASSNAME", $CLASSNAME, $_newname);

		return $_newname;
	}

	/**
	 * save entry in database and create application files
	 *
	 * @return boolean
	 */
	function save($skipHook = 0){

		$text = oldHtmlspecialchars($this->Text, ENT_NOQUOTES);

		$TOOLNAMELANG = $text;
		$TOOLNAME = $this->classname;
		$CLASSNAME = $this->classname;
		$TABLENAME = TBL_PREFIX . $this->maintable;
		$TABLENAMENOPREFIX = $this->maintable;
		$TABLECONSTANT = ($this->maintable) ? strtoupper($this->classname) . '_TABLE' : '';
		$DATASOURCE = ($this->maintable) ? 'table:' . $this->maintable : 'custom:';
		if($DATASOURCE === 'table:' . $this->maintable){
			$TABLEEXISTS = true;
			$this->makeTable = true;
		} else {
			$TABLEEXISTS = false;
			$this->makeTable = false;
		}
		$ACTIVECONSTANT = 'WEAPP_' . strtoupper($this->classname) . '_ACTIVE';

		if($this->makePerms){
			$PERMISSIONCONDITION = 'USE_APP_' . strtoupper($this->classname);
			$DELETECONDITION = 'DELETE_APP_' . strtoupper($this->classname);
		} else {
			$PERMISSIONCONDITION = '';
			$DELETECONDITION = '';
		}
		$WEVERSION = we_util_Strings::version2number(WE_VERSION, false);
		$SDKVERSION = we_util_Strings::version2number(WE_VERSION, false);
		$_templateDir = WE_APPS_PATH . 'toolfactory/pattern';

		$_toolDir = WE_APPS_DIR . $TOOLNAME . '/';

		$_files = array();

		we_tool_lookup::getFilesOfDir($_files, $_templateDir);

		foreach($_files as $_file){

			$_newname = str_replace($_templateDir, WE_APPS_PATH . $TOOLNAME, $_file);
			$_newname = dirname($_newname) . '/' . $this->getNewFileName(basename($_newname), $TOOLNAME, $CLASSNAME);
			$length = strlen(WE_APPS_PATH);
			$replaceString = substr($_newname, $length);
			$_newname = str_replace($replaceString, $this->getNewFileName($replaceString, $TOOLNAME, $CLASSNAME), $_newname);


			if($this->shouldInclude($_newname)){

				$_ext = substr($_file, -4);
				$is_php = ($_ext === '.php');

				if(!$is_php){
					if($_ext === '.sql'){
						ob_start();
						include($_file);
						$_content = ob_get_clean();
					} elseif($_ext === '.css'){
						ob_start();
						include($_file);
						$_content = strtr(ob_get_clean(), array('{$TOOLNAME}' => $TOOLNAME, '{$TOOLNAMELANG}' => $TOOLNAMELANG));
					} else {
						$_content = we_base_file::load($_file);
					}
					if($_ext === '.xml'){
						$_content = we_base_file::load($_file);
						$start = strpos($_content, '<?xml ');
						$end = strpos($_content, '</tmx>') + $start;

						$_content = str_replace('{$TOOLNAME}', $TOOLNAME, $_content);
						$_content = str_replace('{$TOOLNAMELANG}', $TOOLNAMELANG, $_content);

						$_content = str_replace('{$WEVERSION}', $WEVERSION, $_content);
						$_content = str_replace('{$SDKVERSION}', $SDKVERSION, $_content);
					}
				} else {
					ob_start();
					include($_file);
					$_content = '<?php' .
						PHP_EOL .
						ob_get_clean() .
						PHP_EOL . '?>';
				}

				if(!is_dir(dirname($_newname))){
					$path = dirname($_newname);
					$pathteile = explode("/", $path);
					$path = "";
					for($i = 1; $i < count($pathteile); $i++){
						$path .= "/" . $pathteile[$i];
						if(!is_dir($path)){
							mkdir($path);
						}
					}
				}

				$_dirSelectorFile = WE_APPS_PATH . $TOOLNAME . '/we_' . $TOOLNAME . 'DirSelect.php';
				$_dirSelectorClass = WE_APPS_PATH . $TOOLNAME . '/we_' . $TOOLNAME . 'DirSelector.class.php';
				$_sqlFile = WE_APPS_PATH . $TOOLNAME . '/' . $TOOLNAME . '.sql';
				if(($_sqlFile === $_newname || $_dirSelectorFile === $_newname || $_dirSelectorClass === $_newname) && !$this->makeTable){

				} else {
					//print "Saving file " . $_newname . "<br/>";
					if(stripos($_newname, '_UTF-8.inc.php') === false){
						//$_content = utf8_encode($_content);
					}
					we_base_file::save($_newname, $_content);
				}
			}
		}

		if($this->makeTable){
			$_sqlDumpFile = WE_APPS_PATH . $TOOLNAME . '/' . $TOOLNAME . '.sql';
			$_sqlDump = file($_sqlDumpFile);
			$_db = new DB_WE();
			foreach($_sqlDump as $_sql){
				//print "Execute query " . $_sql . "<br/>";
				$_sql = str_replace('###TBLPREFIX###', TBL_PREFIX, $_sql);
				$_db->query($_sql);
			}
		}
		/* hook */
		if(!$skipHook){
			$hook = new weHook('save', $this->_appName, array($this));
			$hook->executeHook();
		}


		/* generate new toc.xml */
		we_app_Common::rebuildAppTOC();

		return true;
	}

	/**
	 * checks the file include for the application
	 *
	 * @return boolean
	 */
	function shouldInclude($file){

		$_dn = dirname($file);
		$_bn = basename($file);

		if($_bn != $this->Text . '.sql' && $_bn != 'permission.conf.php' && $_bn != 'backup.conf.php' && $_dn != WE_APPS_PATH . $this->Text . '/tagwizard' && $_dn != WE_APPS_PATH . $this->Text . '/tags' && $_dn != WE_APPS_PATH . $this->Text . '/service/cmds' && $_dn != WE_APPS_PATH . $this->Text . '/service/views/json' && $_dn != WE_APPS_PATH . $this->Text . '/service/views/text'){
			return true;
		}

		if($this->makePerms && $_bn === 'permission.conf.php'){
			return true;
		}
		if($this->makeBackup && $_bn === 'backup.conf.php'){
			return true;
		}

		if($this->makeTags && ($_dn === WE_APPS_PATH . $this->Text . '/tags')){
			return true;
		}
		if($this->makeTags && ($_dn === WE_APPS_PATH . $this->Text . '/tagwizard')){
			return true;
		}

		if($this->makeServices && ($_dn === WE_APPS_PATH . $this->Text . '/service/cmds' || $_dn === WE_APPS_PATH . $this->Text . '/service/views')){
			return true;
		}

		if($this->makeServices && ($_dn === WE_APPS_PATH . $this->Text . '/service/views/text' || $_dn === WE_APPS_PATH . $this->Text . '/service/views/json')){
			return true;
		}

		if($this->makeTable && $_bn === $this->Text . '.sql'){
			return true;
		}

		return false;
	}

	/**
	 * checks Text for file name
	 *
	 * @return boolean
	 */
	function textNotValid(){
		// comma not allowed because it causes broken webEdition navigation
		if(stripos($this->Text, ',') === false){
			return false;
		} else {
			return true;
		}
	}

	/**
	 * checks classname
	 *
	 * @return boolean
	 */
	function classnameNotValid(){
		if(preg_match('/[^a-z0-9]/i', $this->classname) || is_numeric(substr($this->classname, 0, 1))){
			return true;
		}
		return false;
	}

	/**
	 * checks maintable
	 *
	 * @return boolean
	 */
	function maintablenameNotValid(){
		return preg_match('/[^a-z0-9_-]/i', $this->maintable);
	}

	/**
	 * checks if model class exists
	 *
	 * @param string $classname
	 * @return boolean
	 */
	function modelclassExists($classname){

		$_menuItems = we_tool_lookup::getAllTools(true, false, true);

		$_prohibit_classnames = array($_menuItems);

		foreach($_menuItems as $_menuItem){
			$_prohibit_classnames[] = $_menuItem["classname"];
		}

		return (in_array($classname, $_prohibit_classnames));
	}

}
