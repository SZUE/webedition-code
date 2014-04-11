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
 * Class we_backup
 *
 * Provides functions for exporting and importing backups.
 */
define('BACKUP_TABLE', TBL_PREFIX . 'tblbackup');

//FIXME: try to remove this class
abstract class we_backup_base{

	const COMPRESSION = 'gzip';
	const NO_COMPRESSION = 'none';

	var $backup_db;
	var $errors = array();
	var $warnings = array();
	var $extables = array();
	var $mysql_max_packet = 1048576;
	var $dumpfilename = '';
	var $tempfilename = '';
	var $handle_options = array();
	var $default_backup_steps = 30;
	var $default_backup_len = 150000;
	var $default_offset = 100000;
	var $default_split_size = 150000;
	var $backup_step;
	var $backup_steps;
	var $backup_phase = 0;
	var $backup_extern = 0;
	var $export2server = 0;
	var $export2send = 0;
	var $partial;
	var $current_insert = '';
	var $table_end = 0;
	var $description = array();
	var $current_description = '';
	var $offset = 0;
	var $dummy = array();
	var $table_map = array(
		'tblbackup' => BACKUP_TABLE,
		'tblcategorys' => CATEGORY_TABLE,
		'tblcleanup' => CLEAN_UP_TABLE,
		'tblcontent' => CONTENT_TABLE,
		'tbldoctypes' => DOC_TYPES_TABLE,
		'tblerrorlog' => ERROR_LOG_TABLE,
		'tblfile' => FILE_TABLE,
		'tbllink' => LINK_TABLE,
		'tbltemplates' => TEMPLATES_TABLE,
		'tbltemporarydoc' => TEMPORARY_DOC_TABLE,
		'tblprefs' => PREFS_TABLE,
		'tblrecipients' => RECIPIENTS_TABLE,
		'tblupdatelog' => UPDATE_LOG_TABLE,
		'tblfailedlogins' => FAILED_LOGINS_TABLE,
		'tblthumbnails' => THUMBNAILS_TABLE,
		'tblvalidationservices' => VALIDATION_SERVICES_TABLE
	);
	var $fixedTable = array(
		'tblbackup', 'tblhelpindex', 'tblhelptopic', 'tblhelplink',
		'tblerrorlog', 'tblcleanup', 'tbllock',
		'tblfailedlogins', 'tblupdatelog');
	var $tables = array();
	var $properties = array(
		'default_backup_steps', 'backup_step', 'backup_steps', 'backup_phase', 'backup_extern',
		'export2server', 'export2send', 'partial', 'current_insert', 'table_end', 'current_description', 'offset'
	);

	/*	 * ***********************************************************************
	 * CONSTRUCTOR
	 * *********************************************************************** */

	/**
	 * Constructor of class.
	 *
	 * @param      $handle_users                           bool
	 * @param      $handle_customers                       bool
	 * @param      $handle_shop                            bool
	 * @param      $handle_workflow                        bool
	 * @param      $handle_todo                            bool
	 * @param      $handle_newsletter                      bool
	 *
	 * @return     we_backup
	 */
	function __construct($handle_options = array()){
		$this->backup_db = new DB_WE();
		$this->backup_steps = $this->default_backup_steps;
		$this->partial = false;

		$this->handle_options = $handle_options;

		$this->mysql_max_packet = f('SHOW VARIABLES LIKE "max_allowed_packet"', 'Value', $this->backup_db);

		$this->table_map['tbluser'] = USER_TABLE;

		if(defined('SCHEDULE_TABLE')){
			$this->table_map['tblschedule'] = SCHEDULE_TABLE;
		}

		if(defined('CUSTOMER_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblwebuser' => CUSTOMER_TABLE,
				'tblwebadmin' => CUSTOMER_ADMIN_TABLE));
		}

		if(defined('OBJECT_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblobject' => OBJECT_TABLE,
				'tblobjectfiles' => OBJECT_FILES_TABLE,
				'tblobject_' => OBJECT_X_TABLE));
		}

		if(defined('SHOP_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblanzeigeprefs' => ANZEIGE_PREFS_TABLE,
				'tblorders' => SHOP_TABLE));
		}

		if(defined('WORKFLOW_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblworkflowdef' => WORKFLOW_TABLE,
				'tblworkflowstep' => WORKFLOW_STEP_TABLE,
				'tblworkflowtask' => WORKFLOW_TASK_TABLE,
				'tblworkflowdoc' => WORKFLOW_DOC_TABLE,
				'tblworkflowdocstep' => WORKFLOW_DOC_STEP_TABLE,
				'tblworkflowdoctask' => WORKFLOW_DOC_TASK_TABLE,
				'tblworkflowlog' => WORKFLOW_LOG_TABLE
				)
			);
		}

		if(defined('MSG_TODO_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tbltodo' => MSG_TODO_TABLE,
				'tbltodohistory' => MSG_TODOHISTORY_TABLE,
				'tblmessages' => MESSAGES_TABLE,
				'tblmsgaccounts' => MSG_ACCOUNTS_TABLE,
				'tblmsgaddrbook' => MSG_ADDRBOOK_TABLE,
				'tblmsgfolders' => MSG_FOLDERS_TABLE,
				'tblmsgsettings' => MSG_SETTINGS_TABLE
				)
			);
		}
		if(defined('NEWSLETTER_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblnewsletter' => NEWSLETTER_TABLE,
				'tblnewslettergroup' => NEWSLETTER_GROUP_TABLE,
				'tblnewsletterblock' => NEWSLETTER_BLOCK_TABLE,
				'tblnewsletterlog' => NEWSLETTER_LOG_TABLE,
				'tblnewsletterprefs' => NEWSLETTER_PREFS_TABLE,
				'tblnewsletterconfirm' => NEWSLETTER_CONFIRM_TABLE
				)
			);
		}

		if(defined('BANNER_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblbanner' => BANNER_TABLE,
				'tblbannerclicks' => BANNER_CLICKS_TABLE,
				'tblbannerprefs' => BANNER_PREFS_TABLE,
				'tblbannerviews' => BANNER_VIEWS_TABLE
				)
			);
		}
		if(defined('EXPORT_TABLE')){
			$this->table_map = array_merge($this->table_map, array(
				'tblexport' => EXPORT_TABLE
				)
			);
		}
		if(defined('VOTING_TABLE')){
			$this->table_map['tblvoting'] = VOTING_TABLE;
		}

		$this->tables = array(
			'settings' => array('tblprefs', 'tblrecipients', 'tblvalidationservices'),
			'configuration' => array(),
			'users' => array('tbluser'),
			'customers' => array('tblwebuser', 'tblwebadmin'),
			'shop' => array('tblanzeigeprefs', 'tblorders'),
			'workflow' => array(
				'tblworkflowdef', 'tblworkflowstep', 'tblworkflowtask',
				'tblworkflowdoc', 'tblworkflowdocstep', 'tblworkflowdoctask',
				'tblworkflowlog'
			),
			'todo' => array(
				'tbltodo', 'tbltodohistory', 'tblmessages', 'tblmsgaccounts',
				'tblmsgaddrbook', 'tblmsgfolders', 'tblmsgsettings'
			),
			'newsletter' => array(
				'tblnewsletter', 'tblnewslettergroup',
				'tblnewsletterblock', 'tblnewsletterlog',
				'tblnewsletterprefs', 'tblnewsletterconfirm'
			),
			'temporary' => array('tbltemporarydoc'),
			'banner' => array(
				'tblbanner', 'tblbannerclicks',
				'tblbannerprefs', 'tblbannerviews'
			),
			'schedule' => array(
				'tblschedule'
			),
			'export' => array(
				'tblexport'
			),
			'voting' => array(
				'tblvoting'
			),
		);

		$this->setDescriptions();


		$this->clearOldTmp();
	}

	function setDescriptions(){
		$this->description = array(
			'import' => array(
				strtolower(CONTENT_TABLE) => g_l('backup', '[import_content]'),
				strtolower(FILE_TABLE) => g_l('backup', '[import_files]'),
				strtolower(DOC_TYPES_TABLE) => g_l('backup', '[import_doctypes]'),
				strtolower(USER_TABLE) => g_l('backup', '[import_user_data]'),
				defined('CUSTOMER_TABLE') ? strtolower(CUSTOMER_TABLE) : 'CUSTOMER_TABLE' => g_l('backup', '[import_customers_data]'),
				defined('SHOP_TABLE') ? strtolower(SHOP_TABLE) : 'SHOP_TABLE' => g_l('backup', '[import_shop_data]'),
				defined('ANZEIGE_PREFS_TABLE') ? strtolower(ANZEIGE_PREFS_TABLE) : 'ANZEIGE_PREFS_TABLE' => g_l('backup', '[import_prefs]'),
				strtolower(TEMPLATES_TABLE) => g_l('backup', '[import_templates]'),
				strtolower(TEMPORARY_DOC_TABLE) => g_l('backup', '[import][temporary_data]'),
				strtolower(BACKUP_TABLE) => g_l('backup', '[external_backup]'),
				strtolower(LINK_TABLE) => g_l('backup', '[import_links]'),
				strtolower(INDEX_TABLE) => g_l('backup', '[import_indexes]'),
			),
			'export' => array(
				strtolower(CONTENT_TABLE) => g_l('backup', '[export_content]'),
				strtolower(FILE_TABLE) => g_l('backup', '[export_files]'),
				strtolower(DOC_TYPES_TABLE) => g_l('backup', '[export_doctypes]'),
				strtolower(USER_TABLE) => g_l('backup', '[export_user_data]'),
				defined('CUSTOMER_TABLE') ? strtolower(CUSTOMER_TABLE) : 'CUSTOMER_TABLE' => g_l('backup', '[export_customers_data]'),
				defined('SHOP_TABLE') ? strtolower(SHOP_TABLE) : 'SHOP_TABLE' => g_l('backup', '[export_shop_data]'),
				defined('ANZEIGE_PREFS_TABLE') ? strtolower(ANZEIGE_PREFS_TABLE) : 'ANZEIGE_PREFS_TABLE' => g_l('backup', '[export_prefs]'),
				strtolower(TEMPLATES_TABLE) => g_l('backup', '[export_templates]'),
				strtolower(TEMPORARY_DOC_TABLE) => g_l('backup', '[export][temporary_data]'),
				strtolower(BACKUP_TABLE) => g_l('backup', '[external_backup]'),
				strtolower(LINK_TABLE) => g_l('backup', '[export_links]'),
				strtolower(INDEX_TABLE) => g_l('backup', '[export_indexes]'),
			)
		);
	}

	/*	 * ***********************************************************************
	 * FUNCTIONS
	 * *********************************************************************** */

	/**
	 * This function checks if a given path exists in the database.
	 *
	 * @param      $path                                   string
	 *
	 * @see        putFileInDB()
	 * @see        putDirInDB()
	 *
	 * @return     bool
	 */
	function isPathExist($path){
		$ret = f('SELECT 1  FROM ' . FILE_TABLE . " WHERE Path='" . $this->backup_db->escape($path) . "'", '', $this->backup_db) == '1';
		$ret|=f('SELECT 1 FROM ' . TEMPLATES_TABLE . " WHERE Path='" . $this->backup_db->escape($path) . "'", '', $this->backup_db) == '1';
		return $ret;
	}

	/**
	 * This function puts a given file into the database.
	 *
	 * @param      $file                                   string
	 *
	 * @see        isPathExist()
	 *
	 * @return     bool
	 */
	function putFileInDB($file){
		$nl = "\n";
		$rootdir = rtrim(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '/');
		$path = substr($file, strlen($rootdir), strlen($file) - strlen($rootdir));
		$ok = true;
		if(!$this->isPathExist($path)){
			if(@filesize($file) > $this->mysql_max_packet){
				$ok = false;
				$this->setWarning(sprintf(g_l('backup', '[too_big_file]'), $file));
			} else {
				if(($contents = we_base_file::load($file)) === false){
					$this->setError(sprintf(g_l('backup', '[can_not_open_file]'), $file));
					return false;
				}
			}

			if($ok){
				$contents = str_replace(array("\n", "\r"), array("\\n", "\\r"), $contents);
				$q = 'INSERT INTO ' . BACKUP_TABLE . ' SET ' . we_database_base::arraySetter(array(
						'Path' => $path,
						'Data' => $contents,
						'IsFolder' => 0,));
				we_base_file::save($this->dumpfilename, $q . ';' . $nl, 'ab');
				$this->backup_db->query($q);
			}
		}
		return true;
	}

	/**
	 * This function puts a given directory completely into the
	 * database by partly using the function putFileInDB.
	 *
	 * @param      $dir                                    string
	 *
	 * @see        isPathExist()
	 *
	 * @return     bool
	 */
	function putDirInDB($dir){
		$nl = "\n";
		$rootdir = rtrim(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '/');
		$path = substr($dir, strlen($rootdir), strlen($dir) - strlen($rootdir));
		if(!$this->isPathExist($path)){
			$q = 'INSERT INTO ' . BACKUP_TABLE . ' SET ' . we_database_base::arraySetter(array(
					'Path' => $path,
					'Data' => '',
					'IsFolder' => 1));
			we_base_file::save($this->dumpfilename, $q . ';' . $nl, 'ab');
			$this->backup_db->query($q);
		}
		$dir = str_replace("\\", "/", $dir);
		if(substr($dir, -1) != "/"){
			$dir .= "/";
		}
		$d = @dir($dir);
		if($d){
			while(false !== ($entry = $d->read())){
				if($entry != "." && $entry != ".."){
					if(is_dir($dir . $entry)){
						if($entry != "." && $entry != ".."){
							$this->putDirInDB($dir . $entry);
						}
					} else {
						if(!$this->putFileInDB($dir . $entry)){
							return false;
						}
					}
				}
			}
			$d->close();
		}
		return true;
	}

	/**
	 * This function returns the definition (paramaters) of a
	 * given table.
	 *
	 * @param      $table                                  string
	 * @param      $nl                                     string
	 *
	 * @return     string
	 */
	function tableDefinition($table, $nl, $noprefix){
		$foo = 'DROP TABLE IF EXISTS ' . $this->backup_db->escape($noprefix) . ";$nl" .
			'CREATE TABLE ' . $this->backup_db->escape($noprefix) . " ($nl";
		$this->backup_db->query('SHOW FIELDS FROM ' . $this->backup_db->escape($table));
		while($this->backup_db->next_record()){
			$row = $this->backup_db->Record;
			$foo .= "   $row[Field] $row[Type]";
			if(isset($row["Default"]) && (($row["Default"]) || $row["Default"] == '0')){
				$foo .= " DEFAULT '$row[Default]'";
			}
			if($row["Null"] != "YES"){
				$foo .= " NOT NULL";
			}
			if($row["Extra"] != ""){
				$foo .= " $row[Extra]";
			}
			$foo .= ",$nl";
		}
		$foo = preg_replace('/,' . $nl . '$/', '', $foo);
		$this->backup_db->query("SHOW KEYS FROM " . $this->backup_db->escape($table));
		while($this->backup_db->next_record()){
			$row = $this->backup_db->Record;
			$key = $row['Key_name'];
			if(($key != "PRIMARY") && ($row['Non_unique'] == 0)){
				$key = "UNIQUE|$key";
			}
			if(!isset($index[$key])){
				$index[$key] = array();
			}
			$index[$key][] = $row['Column_name'];
		}
		while((list($k, $v) = @each($index))){
			$foo .= ",$nl";
			if($k == "PRIMARY"){
				$foo .= "   PRIMARY KEY (" . implode($v, ", ") . ")";
			} else if(substr($k, 0, 6) == "UNIQUE"){
				$foo .= "   UNIQUE " . substr($k, 7) . " (" . implode($v, ", ") . ")";
			} else {
				$foo .= "   KEY $k (" . implode($v, ", ") . ")";
			}
		}
		$foo .= "$nl) ENGINE = MYISAM";
		return stripslashes($foo);
	}

	/**
	 * Function: makeBackup
	 *
	 * Description: This function initializes the creation of a backup.
	 */
	abstract function makeBackup();

	/**
	 * Function: buildBackupTable
	 *
	 * Description: This function builds the table if the users chooses to
	 * backup external files.
	 */
	function buildBackupTable(){
		$this->current_description = g_l('backup', "[external_backup]");
		$rootdir = rtrim(str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']), '/') . '/';
		$count = 0;
		$done = 0;
		$len = 0;
		$finish = 0;
		$d = @dir($rootdir);
		while(false !== ($entry = $d->read())){
			$count++;
			if($entry != "." && $entry != ".." && $entry != "CVS" && $entry != "webEdition" && $this->backup_step < $count){
				if(is_dir($rootdir . $entry)){
					if(!$this->putDirInDB($rootdir . $entry)){
						return -1;
					}
				} elseif(!$this->putFileInDB($rootdir . $entry)){
					return -1;
				}
				$len = $len + filesize($rootdir . $entry);
				$done++;
				if(($done == $this->backup_steps) || ($len > $this->default_backup_len)){
					$finish = 1;
					break;
				}
			}
		}
		$d->close();
		$this->backup_step = $count;
		return $finish;
	}

	/**
	 * Function: exportTables
	 *
	 * Description: This function saves the files in the previously builded
	 * table if the users chose to backup external files.
	 */
	abstract function exportTables();

	/**
	 * Function: printDump
	 *
	 * Description: This function saves a given file into the dump.
	 */
	function printDump(){
		$fh = @fopen($this->dumpfilename, 'rb');
		if($fh){
			while(!@feof($fh)){
				print @fread($fh, 52428);
				update_time_limit(80);
			}
			@fclose($fh);
		} else {
			$this->setError(sprintf(g_l('backup', "[can_not_open_file]"), $this->dumpfilename));
			return false;
		}
		return true;
	}

	/**
	 * Function: printDump2BackupDir
	 *
	 * Description: This function saves the dump to the backup directory.
	 */
	function printDump2BackupDir(){
		if($this->export2server == 1){
			$backupfilename = $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "weBackup_" . time() . ".php";
			return @copy($this->dumpfilename, $backupfilename);
		}
		return true;
	}

	/**
	 * Function: setTmpFilename
	 *
	 * Description: This function sets the output filename of the backup if the
	 * user chose to save it on the server.
	 */
	function setTmpFilename($filename){
		if($this->isFileInTmpDir($filename)){
			if(is_file(TEMP_PATH . '/' . $filename)){
				$this->tempfilename = $filename;
				$this->dumpfilename = TEMP_PATH . '/' . $filename;
				return true;
			}
		}
		return false;
	}

	/**
	 * Function: isFileInTmpDir
	 *
	 * Description: This function checks if a file is in the temporary
	 * directory used for backups.
	 */
	function isFileInTmpDir($file_name){
		$dir = TEMP_PATH . '/';
		$d = @dir($dir);
		$ret = false;
		if($d){
			while(false !== ($entry = $d->read())){
				if($entry == $file_name){
					$ret = true;
				}
			}
			$d->close();
		}
		return $ret;
	}

	/**
	 * Function: getTmpFilename
	 *
	 * Description: This function returns the filename of a file located in the
	 * temporary directory used for backups.
	 */
	function getTmpFilename(){
		return $this->tempfilename;
	}

	/**
	 * Function: removeDumpFile
	 *
	 * Description: This function deletes a database dump.
	 */
	function removeDumpFile(){
		if(is_file($this->dumpfilename)){
			@unlink($this->dumpfilename);
		}

		$this->dumpfilename = '';
		$this->tempfilename = '';
	}

	/**
	 * Function: restoreFiles
	 *
	 * Description: This function initializes the import of a backup.
	 */
	function restoreFiles(){
		$tab = $this->backup_db->table_names(BACKUP_TABLE);
		$exist = !empty($tab);
		/* while (list($tname)=@mysql_fetch_array($tab)) {
		  if(strtolower($tname)==strtolower(BACKUP_TABLE))
		  $exist=true;
		  } */
		if($exist){
			/* $link = mysql_connect($this->backup_db->Host, $this->backup_db->User, $this->backup_db->Password);
			  mysql_select_db($this->backup_db->Database); */
			$mydb = new DB_WE();
			$mydb->query('SELECT * FROM ' . BACKUP_TABLE . ' ORDER BY IsFolder DESC, Path ASC', false, true);

			while($mydb->next_record(MYSQL_ASSOC)){
				$line = $mydb->Record;
				update_time_limit(80);
				if($line["IsFolder"]){
					$dir = $_SERVER['DOCUMENT_ROOT'] . $line["Path"];
					$sdir = str_replace("\\", "/", dirname($dir));
					while((!file_exists($sdir)) && ($sdir != "/")){
						we_util_File::createLocalFolder($sdir);
						$sdir = str_replace("\\", "/", dirname($sdir));
					}
					if(!file_exists($dir)){
						we_util_File::createLocalFolder($dir);
					}
				} else {
					$sdir = str_replace("\\", "/", dirname($_SERVER['DOCUMENT_ROOT'] . $line["Path"]));
					if(!we_base_file::save($_SERVER['DOCUMENT_ROOT'] . $line["Path"], $line["Data"], 'wb')){
						$this->setError(g_l('backup', "[can_not_open_file]"), $line["Path"]);
						return false;
					}
				}
			}
			$mydb->free();
		}
		return true;
	}

	/**
	 * Function: splitFile
	 *
	 * Description: This function splits a file.
	 */
	function splitFile($backup_select){
		$buff = '';

		$this->current_description = g_l('backup', "[preparing_file]");

		$filename = $backup_select;
		$backup_select = we_base_file::getUniqueId();

		$filename_tmp = "";
		$fh = fopen($filename, "rb");
		$num = -1;
		$open_new = true;
		$fsize = 0;

		if($fh){

			while(!@feof($fh)){
				update_time_limit(60);
				$line = "";
				$findline = false;

				while($findline == false && !@feof($fh)){
					$line .= @fgets($fh, 4096);
					if(substr($line, -1) == "\n"){
						$findline = true;
					}
				}

				if($open_new){
					$num++;
					$filename_tmp = $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . '/tmp/' . basename($filename) . '_' . $num;
					$fh_temp = fopen($filename_tmp, "wb");
					$open_new = false;
				}
				if($fh_temp){
					if(substr($line, 0, 1) != "#"){
						$buff.=$line;
						if((substr($buff, -2) == ";\n") || (substr($buff, -3) == ";\r\n")){
							$fsize+=strlen($buff);
							fwrite($fh_temp, $buff);
							if($fsize > $this->default_split_size){
								$open_new = true;
								@fclose($fh_temp);
								$fsize = 0;
							}
							$buff = "";
						}
					}
				} else {
					$this->setError(g_l('backup', "[can_not_open_file]"), basename($filename) . "_" . $num);
					return -1;
				}
			}
		} else {
			$this->setError(g_l('backup', "[can_not_open_file]"), basename($filename) . "_" . $num);
			return -1;
		}
		if($fh_temp){
			@fclose($fh_temp);
		}
		@fclose($fh);
		if(defined("WORKFLOW_TABLE")){
			$this->backup_db->query('TRUNCATE TABLE' . WORKFLOW_DOC_TABLE);
			$this->backup_db->query('TRUNCATE TABLE' . WORKFLOW_DOC_STEP_TABLE);
			$this->backup_db->query('TRUNCATE TABLE' . WORKFLOW_DOC_TASK_TABLE);
			$this->backup_db->query('TRUNCATE TABLE' . WORKFLOW_LOG_TABLE);
		}
		return $num + 1;
	}

	/**
	 * Function: restoreFromBackup
	 *
	 * Description: This function restores a backup.
	 */
	function restoreFromBackup($filename, $restore_extra = 0){
		$buff = "";
		$fh = fopen("$filename", "rb");

		if($fh){
			while(!@feof($fh)){
				update_time_limit(60);
				$line = "";
				$findline = false;

				while($findline == false && !@feof($fh)){
					$line .= @fgets($fh, 4096);
					if(substr($line, -1) == "\n"){
						$findline = true;
					}
				}

				if(substr($line, 0, 1) != "#"){
					$buff.=$line;
					if((substr($buff, -2) == ";\n") || (substr($buff, -3) == ";\r\n")){
						if(preg_match("/;\r?\n.?$/", $buff)){
							$buff = preg_replace("/\r?\n/", " ", $buff);
						}
						$buff = trim($buff);

						$ctbl = $this->isCreateQuery($buff);
						$itbl = $this->isInsertQuery($buff);
						if($itbl != ""){
							$ctbl = "";
						} else if($ctbl != ""){
							$itbl = "";
						}
						$upd = array();
						if(($ctbl != "") || ($itbl != "")){
							if(strlen($buff) < $this->mysql_max_packet){
								if((!$this->isFixed($ctbl . $itbl)) || ((strtolower($ctbl . $itbl) == strtolower(BACKUP_TABLE)) && ($restore_extra))){
									$clear_name = $this->fixTableName($ctbl . $itbl);
									if(trim($clear_name) != ""){
										$buff = str_replace($ctbl . $itbl, $clear_name, $buff);
										if(($ctbl != "") && (strtolower(substr($buff, 0, 6)) == "create")){
											if(defined("OBJECT_X_TABLE") && substr(strtolower($ctbl), 0, 10) != strtolower(OBJECT_X_TABLE)){
												$this->getDiff($buff, $clear_name, $upd);
											}
											$this->backup_db->query("DROP TABLE IF EXISTS " . $this->backup_db->escape($clear_name) . ";");
											$this->backup_db->query($buff);
										}
										if(($itbl != "") && (strtolower(substr($buff, 0, 6)) == "insert")){
											if(defined("OBJECT_X_TABLE") && substr(strtolower($itbl), 0, 10) == strtolower(OBJECT_X_TABLE)){
												if(preg_match("|VALUES[[:space:]]*\([[:space:]]*\'?0\'?[[:space:]]*,[[:space:]]*\'?0\'?[[:space:]]*,|i", $buff)){
													$this->dummy[] = $buff;
												} else {
													$this->backup_db->query($buff);
												}
											} else {
												$this->backup_db->query($buff);
											}
										}

										foreach($upd as $k => $v){
											$this->backup_db->query($v);
										}
									}
								}
							} else {
								$this->setWarning(g_l('backup', "[query_is_too_big]"), $this->mysql_max_packet);
							}
						}

						$buff = "";
					}
				}
			}
		} else {
			$this->setError(sprintf(g_l('backup', "[can_not_open_file]"), $filename));
			return false;
		}
		@fclose($fh);
		unlink($filename);
		$tn = strtolower($ctbl . $itbl);

		$this->current_description = (isset($this->description["import"]["$tn"]) && $this->description["import"]["$tn"] ?
				$this->description["import"][$tn] :
				g_l('backup', "[working]"));


		if($restore_extra && !$this->restoreFiles()){
			return false;
		}
		return true;
	}

	/**
	 * Function: removeBackup
	 *
	 * Description: This function removes a backup from the database.
	 */
	function removeBackup(){
		$this->backup_db->query('DROP TABLE IF EXISTS ' . BACKUP_TABLE);

		//import dummys
		if(is_array($this->dummy)){
			foreach($this->dummy as $query){
				$this->backup_db->query($query);
			}
		}
		we_updater::doUpdate();
		if(!$this->handle_options['temporary']){
			$this->backup_db->query('TRUNCATE TABLE ' . TEMPORARY_DOC_TABLE);
		}
	}

	/**
	 * Function: getDiff
	 *
	 * Description: This function checks for differences between the table
	 * structure of the current database and the table structure of the
	 * backup file.
	 */
	function getDiff(&$q, $tab, &$fupdate){
		$fnames = array();
		$fields = '';
		$sub_parts = array();
		$len = strlen($q);
		$br = 0;
		$run = 0;
		for($i = 0; $i < $len; $i++){
			if($q[$i] == "("){
				$run = 1;
				$br++;
			} else if($q[$i] == ")"){
				$br--;
			} else if($br > 0){
				$fields.=$q[$i];
			}
			if($br == 0 && $run){
				break;
			}
		}
		$parts = explode(',', $fields);
		foreach($parts as $v){
			$sub_parts = explode(' ', trim($v));
			switch($sub_parts[0]){
				case '':
				case 'PRIMARY':
				case 'UNIQUE':
				case 'KEY':
					break;
				default:
					$fnames[] = strtolower($sub_parts[0]);
			}
		}

		$this->backup_db->query("SHOW TABLES LIKE '" . $this->backup_db->escape($tab) . "'");
		if($this->backup_db->next_record()){
			$this->backup_db->query('SHOW COLUMNS FROM ' . $this->backup_db->escape($tab));
			while($this->backup_db->next_record()){
				if(!in_array(strtolower($this->backup_db->f("Field")), $fnames)){
					$fupdate[] = "ALTER TABLE " . $this->backup_db->escape($tab) . ' ADD ' . $this->backup_db->f("Field") . ' ' . $this->backup_db->f("Type") . " DEFAULT '" . $this->backup_db->f("Default") . "'" . ($this->backup_db->f("Null") == "YES" ? " NOT NULL" : '');
				}
			}
		}
		return true;
	}

	/**
	 * Function: isCreateQuery
	 *
	 * Description: This function returns whether the given query is a "CREATE"
	 * query or not.
	 */
	function isCreateQuery($q){
		$m = array();
		return (preg_match("/CREATE[[:space:]]+TABLE[[:space:]]+([a-zA-Z0-9_+-]+)/", $q, $m) ?
				$m[1] : '');
	}

	/**
	 * Function: fixTableNames
	 *
	 * Description: The function convert default table names to
	 * real table names
	 */
	function fixTableNames(&$arr){
		foreach($arr as $key => $val){
			$name = $this->fixTableName($val);
			$arr[$key] = $name;
		}
		array_unique($arr);
	}

	/**
	 * Function: fixTableName
	 *
	 * Description: This function checks and returns the real name of a
	 * given default table name.
	 */
	function fixTableName($tabname){
		$tabname = strtolower($tabname);

		if(substr($tabname, 0, 10) == "tblobject_" && defined("OBJECT_X_TABLE")){
			return str_ireplace("tblobject_", OBJECT_X_TABLE, $tabname);
		}

		foreach($this->table_map as $k => $v){
			if($tabname == strtolower($k)){
				return $v;
			}
		}

		return $tabname;
	}

	/**
	 * Function: getDefaultTableName
	 *
	 * Description: The function returns default name for given
	 * real table name
	 */
	function getDefaultTableName($tabname){
		$tabname = strtolower($tabname);
		if(defined("OBJECT_X_TABLE") && stripos($tabname, OBJECT_X_TABLE) !== false){
			return str_ireplace(OBJECT_X_TABLE, "tblobject_", $tabname);
		}

		foreach($this->table_map as $k => $v){
			if($tabname == strtolower($v)){
				return $k;
			}
		}

		return $tabname;
	}

	/**
	 * Function: isWeTable
	 *
	 * Description: The function checks if given  name
	 * is webEdition table name
	 */
	function isWeTable($tabname){
		if(in_array(strtolower($tabname), array_keys($this->table_map))){
			return true;
		}
		if(defined("OBJECT_X_TABLE")){
			$object_x_table = stripTblPrefix(OBJECT_X_TABLE);

			return stripos($tabname, $object_x_table) !== false;
		}
		return false;
	}

	/**
	 * Function: isFixed
	 *
	 * Description: This function checks if a table name has its correct value.
	 */
	function isFixed($tab){
		$table = strtolower($tab);
		$fixTable = $this->fixedTable;

		foreach($this->handle_options as $hok => $hov){
			if(!$hov){
				$fixTable = array_merge($fixTable, $this->tables[$hok]);
			}
		}

		return (in_array($table, $fixTable));
	}

	/**
	 * Function: isInsertQuery
	 *
	 * Description: This function returns whether the given query is a "INSERT"
	 * query or not.
	 */
	function isInsertQuery($q){
		$m = array();
		return (preg_match("/INSERT[[:space:]]+INTO[[:space:]]+([a-zA-Z0-9_+-]+)/", $q, $m) ? $m[1] : '');
	}

	/**
	 * Function: setError
	 *
	 * Description: This function sets a value for an error.
	 */
	function setError($errtxt){
		$this->errors[] = $errtxt;
	}

	/**
	 * Function: setWarning
	 *
	 * Description: This function sets a value for a warning.
	 */
	function setWarning($wartxt){
		$this->warnings[] = $wartxt;
	}

	/**
	 * Function: getErrors
	 *
	 * Description: This function returns errors if any were set.
	 */
	function getErrors(){
		return $this->errors;
	}

	/**
	 * Function: getWarnings
	 *
	 * Description: This function returns warnings if any were set.
	 */
	function getWarnings(){
		return $this->warnings;
	}

	/**
	 * Function: arrayintersect
	 *
	 * Description:
	 */
	function arrayintersect($array1, $array2){
		$ret = array();
		foreach($array1 as $v){
			if(!is_array($v) && in_array($v, $array2)){
				$ret[] = $v;
			}
		}
		return $ret;
	}

	/**
	 * Function: arraydiff
	 *
	 * Description:
	 */
	function arraydiff($array1, $array2){
		$ret = array();
		foreach($array1 as $v){
			if(!is_array($v) && !in_array($v, $ret) && !in_array($v, $array2)){
				$ret[] = $v;
			}
		}
		return $ret;
	}

	protected function _saveState(){
		//FIXME: use __sleep/__wakeup + serialize/unserialize
		//		// Initialize variable
		return '
$this->errors=' . var_export($this->errors, true) . ';
$this->warnings=' . var_export($this->warnings, true) . ';
$this->extables=' . var_export($this->extables, true) . ';
$this->dumpfilename=' . var_export($this->dumpfilename, true) . ';
$this->tempfilename=' . var_export($this->tempfilename, true) . ';
$this->handle_options=' . var_export($this->handle_options, true) . ';
$this->properties=' . var_export($this->properties, true) . ';
$this->dummy=' . var_export($this->dummy, true) . ';
';
	}

	/**
	 * Function: saveState
	 *
	 * Description:
	 */
	function saveState($of = ""){
		$of = ($of ? $of : we_base_file::getUniqueId()); // #6590, changed from: uniqid(time())
		we_base_file::save($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . 'tmp/' . $of, $this->_saveState(), 'wb');
		return $of;
	}

	/**
	 * Function: restoreState
	 *
	 * Description:
	 */
	function restoreState($temp_filename){
		//FIXME: use __sleep/__wakeup + serialize/unserialize
		if(($save = we_base_file::load($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "tmp/" . $temp_filename, "rb")) !== false){
			eval($save);
			return $temp_filename;
		} else {
			return 0;
		}
	}

	/**
	 * Function: getDownloadFile
	 *
	 * Description: This function copies a backup file to the download directory
	 * and returns its filename plus location.
	 */
	function getDownloadFile(){
		$download_filename = "weBackup_" . $_SESSION["user"]["Username"] . ".sql";
		if(copy($this->dumpfilename, $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "download/" . $download_filename)){
			we_util_File::insertIntoCleanUp($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "download/" . $download_filename, time());
			return $download_filename;
		} else {
			return '';
		}
	}

	function clearOldTmp(){
		if(!is_writable($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "tmp")){
			$this->setError(sprintf(g_l('backup', "[cannot_save_tmpfile]"), BACKUP_DIR));
			return -1;
		}

		$d = dir($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "tmp");
		$co = -1;
		$limit = time() - 86400;
		while(false !== ($entry = $d->read())){
			if($entry != "." && $entry != ".." && $entry != "CVS" && !@is_dir($entry)){
				if(filemtime($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . '/tmp/' . $entry) < $limit){
					unlink($_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . '/tmp/' . $entry);
				}
			}
		}
		$d->close();
	}

}
