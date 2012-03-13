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
 * Class weBackup
 *
 * Provides functions for exporting and importing backups. Extends we_backup.
 */
class weBackup extends we_backup{

	var $header;
	var $footer;
	var $nl;
	var $mode = "sql";
	var $filename;
	var $compress = "none";
	var $rebuild;
	var $file_list = array();
	var $file_counter = 0;
	var $file_end = 0;
	var $backup_dir;
	var $backup_dir_tmp;
	var $row_count = 0;
	var $file_list_count = 0;
	var $old_objects_deleted = 0;
	var $backup_binary = 1;

	function __construct($handle_options = array()){
		$this->nl = "\n";

		$this->header = "<?xml version=\"1.0\" encoding=\"" . $GLOBALS['WE_BACKENDCHARSET'] . "\" standalone=\"yes\"?>" . $this->nl .
			"<webEdition version=\"" . WE_VERSION . "\" type=\"backup\" xmlns:we=\"we-namespace\">" . $this->nl;
		$this->footer = $this->nl . "</webEdition>";

		$this->properties[] = "mode";
		$this->properties[] = "filename";
		$this->properties[] = "compress";
		$this->properties[] = "backup_binary";
		$this->properties[] = "rebuild";
		$this->properties[] = "file_counter";
		$this->properties[] = "file_end";
		$this->properties[] = "row_count";
		$this->properties[] = "file_list_count";
		$this->properties[] = "old_objects_deleted";

		parent::__construct($handle_options);

		$this->tables["core"] = array("tblfile", "tbllink", "tbltemplates", "tblindex", "tblcontent", "tblcategorys", "tbldoctypes", "tblthumbnails");
		$this->tables["object"] = array("tblobject", "tblobjectfiles", "tblobject_");

		$this->mode = "xml";

		$this->backup_dir = $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR;
		$this->backup_dir_tmp = $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . "tmp/";
	}

	function splitFile2(){
		if($this->filename == ""){
			return -1;
		}
		if($this->mode == "sql"){
			return parent::splitFile($this->filename);
		}

		return weXMLExIm::splitFile($this->filename, $this->backup_dir_tmp, $this->backup_steps);

		$path = $this->backup_dir_tmp;
		//FIXME: use RegEx
		$marker = "<!-- webackup -->";
		$marker2 = "<!--webackup -->"; //Backup 5089
		$pattern = basename($this->filename) . "_%s";


		$this->compress = ($this->isCompressed($this->filename) ? "gzip" : "none");

		$header = $this->header;

		$buff = "";
		$filename_tmp = "";

		$fh = ($this->compress != "none" ?
				@gzopen($this->filename, "rb") :
				@fopen($this->filename, "rb"));

		$num = -1;
		$open_new = true;
		$fsize = 0;

		$elnum = 0;

		$marker_size = strlen($marker);
		$marker2_size = strlen($marker2); //Backup 5089

		if($fh){
			while(!@feof($fh)) {
				@set_time_limit(240);
				$line = "";
				$findline = false;

				while($findline == false && !@feof($fh)) {

					if($this->compress != "none")
						$line .= @gzgets($fh, 4096);
					else
						$line .= @fgets($fh, 4096);

					if(substr($line, -1) == "\n"){
						$findline = true;
					}
				}

				if($open_new){
					$num++;
					$filename_tmp = sprintf($path . $pattern, $num);
					$fh_temp = fopen($filename_tmp, "wb");
					fwrite($fh_temp, $header);
					if($num == 0)
						$header = "";
					$open_new = false;
				}

				if($fh_temp){
					if((substr($line, 0, 2) != "<?") && (substr($line, 0, 11) != "<webEdition") && (substr($line, 0, 12) != "</webEdition")){

						$buff.=$line;
						$write = false;
						if($marker_size){
							if((substr($buff, (0 - ($marker_size + 1))) == $marker . "\n") || (substr($buff, (0 - ($marker_size + 2))) == $marker . "\r\n" ) || (substr($buff, (0 - ($marker2_size + 1))) == $marker2 . "\n") || (substr($buff, (0 - ($marker2_size + 2))) == $marker2 . "\r\n" ))
								$write = true;
							else
								$write = false;
						}
						else
							$write = true;

						if($write){
							$fsize+=strlen($buff);
							fwrite($fh_temp, $buff);
							if($marker_size){
								$elnum++;
								if($elnum >= $this->backup_steps){
									$elnum = 0;
									$open_new = true;
									fwrite($fh_temp, $this->footer);
									@fclose($fh_temp);
								}
								$fsize = 0;
							}
							$buff = "";
						}
					} else{
						if(((substr($line, 0, 2) == "<?") || (substr($line, 0, 11) == "<webEdition")) && $num == 0){
							$header.=$line;
						}
					}
				} else{
					return -1;
				}
			}
		} else{
			return -1;
		}
		if($fh_temp){
			if($buff){
				fwrite($fh_temp, $buff);
				fwrite($fh_temp, $this->footer);
			}
			@fclose($fh_temp);
		}
		if($this->compress != "none")
			@gzclose($fh);
		else
			@fclose($fh);

		return $num + 1;
	}

	function recoverTable($nodeset, &$xmlBrowser){
		$attributes = $xmlBrowser->getAttributes($nodeset);

		$tablename = $attributes["name"];
		if(!$this->isFixed($tablename) && $tablename != ""){
			$tablename = $this->fixTableName($tablename);
			if(isset($this->description["import"][strtolower($tablename)]) && $this->description["import"][strtolower($tablename)]){
				$this->current_description = $this->description["import"][strtolower($tablename)];
			} else{
				$this->current_description = g_l('backup', "[working]");
			}

			$object = weContentProvider::getInstance("weTable", 0, $tablename);
			$node_set2 = $xmlBrowser->getSet($nodeset);
			foreach($node_set2 as $set2){
				$node_set3 = $xmlBrowser->getSet($set2);
				foreach($node_set3 as $nsv){
					$tmp = $xmlBrowser->nodeName($nsv);
					if($tmp == "Field")
						$name = $xmlBrowser->getData($nsv);
					$object->elements[$name][$tmp] = $xmlBrowser->getData($nsv);
				}
			}

			if(
				((defined("OBJECT_TABLE") && $object->table == OBJECT_TABLE) ||
				(defined("OBJECT_FILES_TABLE") && $object->table == OBJECT_FILES_TABLE))
				&& $this->old_objects_deleted == 0){
				$this->delOldTables();
				$this->old_objects_deleted = 1;
			}
			$object->save();
		}
	}

	function recoverTableItem($nodeset, &$xmlBrowser){
		$content = array();
		$node_set2 = $xmlBrowser->getSet($nodeset);
		$classname = "weTableItem";

		foreach($node_set2 as $nsk => $nsv){
			$index = $xmlBrowser->nodeName($nsv);
			if(weContentProvider::needCoding($classname, $index)){
				$content[$index] = weContentProvider::decode($xmlBrowser->getData($nsv));
			} else{
				$content[$index] = $xmlBrowser->getData($nsv);
			}
		}
		$attributes = $xmlBrowser->getAttributes($nodeset);

		$tablename = $attributes["table"];
		if(!$this->isFixed($tablename) && $tablename != ""){
			$tablename = $this->fixTableName($tablename);

			$object = weContentProvider::getInstance($classname, 0, $tablename);
			weContentProvider::populateInstance($object, $content);

			$object->save(true);
		}
	}

	function recoverBinary($nodeset, &$xmlBrowser){
		$content = array();
		$node_set2 = $xmlBrowser->getSet($nodeset);
		$classname = weContentProvider::getContentTypeHandler("weBinary");
		foreach($node_set2 as $nsk => $nsv){
			$index = $xmlBrowser->nodeName($nsv);
			if(weContentProvider::needCoding($classname, $index))
				$content[$index] = weContentProvider::decode($xmlBrowser->getData($nsv));
			else
				$content[$index] = $xmlBrowser->getData($nsv);
		}
		$object = weContentProvider::getInstance($classname, 0);
		weContentProvider::populateInstance($object, $content);

		if($object->ID && $this->backup_binary){
			$object->save(true);
		} else if($this->handle_options["settings"] && $object->Path == "/webEdition/we/include/conf/we_conf_global.inc.php"){
			weBackup::recoverPrefs($object);
		} else if(!$object->ID && $this->backup_extern){
			$object->save(true);
		}
	}

	function recoverPrefs(&$object){
		$file = "/webEdition/we/tmp/we_conf_global.inc.php";
		$object->Path = $file;
		$object->save(true);
		$parser = weConfParser::getConfParserByFile($_SERVER['DOCUMENT_ROOT'] . $file);

		$newglobals = $parser->getData();

		foreach($newglobals as $k => $v){
			if($v != ''){
				weConfParser::setGlobalPref($k, $v);
			}
		}
		@unlink($_SERVER['DOCUMENT_ROOT'] . $file);
	}

	function recoverInfo($nodeset, &$xmlBrowser){
		$content = array();
		$node_set2 = $xmlBrowser->getSet($nodeset);

		$classname = weContentProvider::getContentTypeHandler("weBinary");
		$db2 = new DB_WE();
		foreach($node_set2 as $nsk => $nsv){
			$index = $xmlBrowser->nodeName($nsv);
			if($index == "we:map"){
				$attributes = $xmlBrowser->getAttributes($nsv);
				$tablename = $attributes["table"];
				if($tablename == $this->getDefaultTableName(TEMPLATES_TABLE)){
					$id = $attributes["ID"];
					$path = $attributes["Path"];
					//$this->backup_db->query("SELECT ".FILE_TABLE.".ID AS ID,".FILE_TABLE.".TemplateID AS TemplateID,".TEMPLATES_TABLE.".Path AS TemplatePath FROM ".FILE_TABLE.",".TEMPLATES_TABLE." WHERE ".FILE_TABLE.".TemplateID=".TEMPLATES_TABLE.".ID;");
					$this->backup_db->query("SELECT ID FROM " . TEMPLATES_TABLE . " WHERE Path=" . $this->backup_db->escape($path) . ";");
					if($this->backup_db->next_record()){
						if($this->backup_db->f("ID") != $id)
							$db2->query("UPDATE " . FILE_TABLE . " SET TemplateID=" . intval($this->backup_db->f("ID")) . " WHERE TemplateID=" . intval($id));
					}
				}
			}
		}
	}

	function recover($chunk_file){
		if(!is_readable($chunk_file))
			return false;
		@set_time_limit(240);

		$xmlBrowser = new weXMLBrowser($chunk_file);
		$xmlBrowser->mode = "backup";

		foreach($xmlBrowser->nodes as $key => $val){
			$name = $xmlBrowser->nodeName($key);
			switch($name){
				case "we:table":
					weBackup::recoverTable($key, $xmlBrowser);
					break;
				case "we:tableitem":
					weBackup::recoverTableItem($key, $xmlBrowser);
					break;
				case "we:binary":
					weBackup::recoverBinary($key, $xmlBrowser);
					break;
			}
		}
		return true;
	}

	function backup($id){

	}

	/**
	 * Function: makeBackup
	 *
	 * Description: This function initializes the creation of a backup.
	 */
	function makeBackup(){
		$phase_start = false;
		$ret = 0;
		if(!$this->tempfilename){
			$this->tempfilename = $this->filename;
			$this->dumpfilename = $this->backup_dir_tmp . $this->tempfilename;
			$this->backup_step = 0;

			if(!weFile::save($this->dumpfilename, $this->header)){
				$this->setError(sprintf(g_l('backup', "[can_not_open_file]"), $this->dumpfilename));
				return -1;
			}
		}

		if($this->backup_extern == 1 && $this->backup_phase == 0){
			$this->exportExtern();
			$ret = true;
		} else{
			$ret = $this->exportTables();
		}
		return $ret;
	}

	/**
	 * Function: exportTables
	 *
	 * Description: This function saves the files in the previously builded
	 * table if the users chose to backup external files.
	 */
	function exportTables(){

		$tab = array();
		$tabtmp = array();
		$tables = array();
		$tab = $this->backup_db->table_names();

		$xmlExport = new weXMLExIm();
		$xmlExport->setBackupProfile();

		foreach($tab as $k => $v){
			$noprefix = $this->getDefaultTableName($v["table_name"]);
			if($noprefix && $this->isWeTable($noprefix))
				array_push($tabtmp, $v["table_name"]);
		}

		$tables = $this->arraydiff($tabtmp, $this->extables);
		$num_tables = sizeof($tables);
		if($num_tables){
			$i = 0;
			while($i < $num_tables) {

				$table = $tables[$i];
				$noprefix = $this->getDefaultTableName($table);

				if(!$this->isFixed($noprefix)){

					//$metadata = $this->backup_db->metadata($table);

					if(!$this->partial){
						$xmlExport->exportChunk(0, "weTable", $this->dumpfilename, $table, $this->backup_binary);

						$this->backup_step = 0;
						$this->table_end = 0;

						$this->table_end = f("SELECT COUNT(1) AS Count FROM " . $this->backup_db->escape($table), "Count", $this->backup_db);
					}

					if(isset($this->description["export"][strtolower($table)])){
						$this->current_description = $this->description["export"][strtolower($table)];
					} else{
						$this->current_description = g_l('backup', "[working]");
					}

					$keys = weTableItem::getTableKey($table);
					$this->partial = false;

					$query = $this->getBackupQuery($table, $keys);
					$this->backup_db->query($query);

					while($this->backup_db->next_record()) {

						$keyvalue = array();
						foreach($keys as $key)
							$keyvalue[] = $this->backup_db->f($key);

						$this->row_count++;

						$xmlExport->exportChunk(implode(",", $keyvalue), "weTableItem", $this->dumpfilename, $table, $this->backup_binary);
						$this->backup_step++;
					}
				}
				$i++;
				if($this->backup_step < $this->table_end && $this->backup_db->num_rows() != 0){

					$this->partial = true;
					break;
				} else{
					$this->partial = false;
				}
				if(!$this->partial){
					if(!in_array($table, $this->extables))
						array_push($this->extables, $table);
				}
			}
		}
		if($this->partial)
			return 1;
		//$res=array();
		//$res=$this->arraydiff($tab,$this->extables);
		unset($xmlExport);
		return 0;
	}

	/**
	 * Function: exportMapper
	 *
	 * Description: This function exports the fields from table
	 */
	function exportInfo($filename, $table, $fields){
		if(!is_array($fields))
			return false;
		// remve $res=array(); from exportTables function
		$out = '<we:info>';
		$this->backup_db->query('SELECT ' . implode(',', $fields) . ' FROM ' . $this->backup_db->escape($table) . ";");
		while($this->backup_db->next_record()) {
			$out.='<we:map table="' . $this->getDefaultTableName($table) . '"';
			foreach($fields as $field){
				$out.=' ' . $field . '="' . $this->backup_db->f($field) . '"';
			}
			$out.='>';
		}
		$out.='</we:info>';
		$out.=we_html_element::htmlComment('webackup') . "\n";
		weFile::save($filename, $out, "ab");
	}

	/**
	 * Function: printDump2BackupDir
	 *
	 * Description: This function saves the dump to the backup directory.
	 */
	function printDump2BackupDir(){
		@set_time_limit(240);
		$backupfilename = $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . $this->filename;
		if($this->compress != "none" && $this->compress != ""){
			$this->dumpfilename = weFile::compress($this->dumpfilename, $this->compress);
			$this->filename = $this->filename . "." . weFile::getZExtension($this->compress);
		}

		if($this->export2server == 1){
			$backupfilename = $this->backup_dir . $this->filename;
			return @copy($this->dumpfilename, $backupfilename);
		}

		return true;
	}

	/**
	 * Function: removeDumpFile
	 *
	 * Description: This function deletes a database dump.
	 */
	function removeDumpFile(){
		if($this->export2send && !$this->export2server){
			we_util_File::insertIntoCleanUp($this->dumpfilename, time());
		} else if(is_file($this->dumpfilename)){
			@unlink($this->dumpfilename);
			$this->dumpfilename = "";
			$this->tempfilename = "";
		}
	}

	/**
	 * Function: restoreFromBackup
	 *
	 * Description: This function restores a backup.
	 */
	function restoreChunk($filename){
		if(!is_readable($filename)){
			$this->setError(sprintf(g_l('backup', "[can_not_open_file]"), $filename));
			return false;
		}

		if($this->mode == "sql")
			return we_backup::restoreFromBackup($filename, $this->backup_extern);
		return $this->recover($filename);
	}

	function getVersion($file){
		if($this->isOldVersion($file))
			$this->mode = "sql";
		else
			$this->mode = "xml";
	}

	function isOldVersion($file){
		$part = weFile::loadPart($file, 0, 512);
		if(stripos($part, "# webEdition version:") !== false && stripos($part, "DROP TABLE") !== false && stripos($part, "CREATE TABLE") !== false)
			return true;
		else
			return false;
	}

	function isCompressed($file){
		$part = weFile::loadPart($file, 0, 512);
		return stripos($part, "<?xml version=") === false;
	}

	function getDownloadFile(){
		return ($this->export2server ? $this->backup_dir . $this->filename : $this->dumpfilename);
	}

	/**
	 * Function: isFixed
	 *
	 * Description: This function checks if a table name has its correct value.
	 */
	function isFixed($tab){
		if(defined("OBJECT_X_TABLE")){
			if(stripos($tab, OBJECT_X_TABLE) !== false){
				if(isset($this->handle_options["object"]) && $this->handle_options["object"])
					return false;
				else
					return true;
			}
		}
		else if(stripos($tab, "tblobject") !== false){
			return true;
		}
		return parent::isFixed($tab) || !$this->isWeTable($tab);
	}

	function getFileList($dir = "", $with_dirs = false, $rem_doc_root = true){
		if($dir == "")
			$dir = $_SERVER['DOCUMENT_ROOT'];

		$d = dir($dir);
		while(false !== ($entry = $d->read())) {
			if($entry != "." && $entry != ".." && $entry != "CVS" && $entry != "webEdition" && $entry != "sql_dumps" && $entry != ".project" && $entry != ".trustudio.dbg.php" && $entry != "LanguageChanges.csv"){
				$file = $dir . "/" . $entry;
				if(!$this->isPathExist(str_replace($_SERVER['DOCUMENT_ROOT'], "", $file))){
					if(is_dir($file)){
						if($with_dirs){
							$this->addToFileList($file, $rem_doc_root);
						}
						$this->getFileList($file, $with_dirs, $rem_doc_root);
					} else{
						$this->addToFileList($file, $rem_doc_root);
					}
				} elseif(is_dir($file)){
					$this->getFileList($file, $with_dirs, $rem_doc_root);
				}
			}
		}
		$d->close();
		$this->file_list_count = count($this->file_list);
	}

	function addToFileList($file, $rem_doc_root = true){
		if($rem_doc_root){
			$this->file_list[] = str_replace($_SERVER['DOCUMENT_ROOT'], "", $file);
		} else{
			$this->file_list[] = $file;
		}
	}

	function getSiteFiles(){
		$this->getFileList($_SERVER['DOCUMENT_ROOT'] . SITE_DIR, true, false);
		$out = array();
		foreach($this->file_list as $file){
			$ct = f('SELECT ContentType FROM ' . FILE_TABLE . ' WHERE Path="' . $this->backup_db->escape(str_replace($_SERVER['DOCUMENT_ROOT'] . rtrim(SITE_DIR, '/'), '', $file)) . '";', 'ContentType', $this->backup_db);
			if($ct){
				if($ct != 'image/*' && $ct != 'application/*' && $ct != 'application/x-shockwave-flash')
					$out[] = $file;
			} else{
				$out[] = $file;
			}
		}
		$this->file_list = $out;
	}

	/**
	 * Function: exportExtern
	 *
	 * Description: This function backup external files.
	 *
	 */
	function exportExtern(){
		$this->current_description = g_l('backup', '[external_backup]');

		if(isset($this->file_list[0])){
			if(is_readable($_SERVER['DOCUMENT_ROOT'] . $this->file_list[0])){

				$this->exportFile($this->file_list[0]);
			}
			array_shift($this->file_list);
		}

		if(!count($this->file_list)){
			$this->backup_phase = 1;
		}
	}

	/**
	 * Function: exportExtern
	 *
	 * Description: This function backup  given file to backup.
	 *
	 */
	function exportFile($file){

		$fh = fopen($this->dumpfilename, 'ab');
		if($fh){

			$bin = weContentProvider::getInstance('weBinary', 0);
			$bin->Path = $file;

			weContentProvider::binary2file($bin, $fh, false);
			fclose($fh);
		}
	}

	function saveState($of = ""){

		// Initialize variable
		$save = '';

		foreach($this->errors as $k => $v){
			$tmp = addslashes($v);
			$save.='$this->errors[' . $k . ']=\'' . $tmp . '\'' . ";\n";
		}
		foreach($this->warnings as $k => $v){
			$tmp = addslashes($v);
			$save.='$this->warnings[' . $k . ']=\'' . $tmp . '\'' . ";\n";
		}
		foreach($this->extables as $k => $v){
			$tmp = addslashes($v);
			$save.='$this->extables[' . $k . ']=\'' . $tmp . '\'' . ";\n";
		}
		$tmp = addslashes($this->dumpfilename);
		$save.='$this->dumpfilename=\'' . $tmp . '\'' . ";\n";

		$tmp = addslashes($this->tempfilename);
		$save.='$this->tempfilename=\'' . $tmp . '\'' . ";\n";

		foreach($this->handle_options as $k => $v){
			$save.='$this->handle_options["' . $k . '"]=\'' . $v . '\'' . ";\n";
		}

		foreach($this->properties as $prop){
			$tmp = addslashes($this->$prop);
			$save.='$this->' . $prop . '=\'' . $tmp . '\'' . ";\n";
		}

		foreach($this->dummy as $k => $v){
			$tmp = addslashes($v);
			$save.='$this->dummy[' . $k . ']=\'' . $tmp . '\'' . ";\n";
		}

		foreach($this->file_list as $k => $v){
			$tmp = addslashes($v);
			$save.='$this->file_list[' . $k . ']=\'' . $tmp . '\'' . ";\n";
		}

		if($of == "")
			$of = weFile::getUniqueId();
		weFile::save($this->backup_dir_tmp . "$of", $save);
		return $of;
	}

	function getExportPercent(){
		$all = 0;
		$db = new DB_WE();
		$db->query("SHOW TABLE STATUS");
		while($db->next_record()) {
			$noprefix = $this->getDefaultTableName($db->f("Name"));
			if(!$this->isFixed($noprefix))
				$all += $db->f("Rows");
		}

		$ex_files = ((int) $this->file_list_count) - ((int) count($this->file_list));
		$all+=(int) $this->file_list_count;
		$done = ((int) $this->row_count) + ((int) $ex_files);
		$percent = (int) (($done / $all) * 100);
		if($percent < 0){
			$percent = 0;
		} else if($percent > 100){
			$percent = 100;
		}
		return $percent;
	}

	function getImportPercent(){
		$file_list_count = (int) ($this->file_list_count - count($this->file_list)) / 100;
		$percent = (int) ((($this->file_counter + $file_list_count) / (($this->file_list_count / 100) + $this->file_end)) * 100);
		if($percent > 100){
			$percent = 100;
		} else if($percent < 0){
			$percent = 0;
		}
		return $percent;
	}

	function setDescriptions(){
		$this->description["import"][strtolower(CONTENT_TABLE)] = g_l('backup', "[import_content]");
		$this->description["import"][strtolower(FILE_TABLE)] = g_l('backup', "[import_files]");
		$this->description["import"][strtolower(DOC_TYPES_TABLE)] = g_l('backup', "[import_doctypes]");
		if(isset($this->handle_options["users"]) && $this->handle_options["users"])
			$this->description["import"][strtolower(USER_TABLE)] = g_l('backup', "[import_user_data]");
		if(defined("CUSTOMER_TABLE") && isset($this->handle_options["customers"]) && $this->handle_options["customers"])
			$this->description["import"][strtolower(CUSTOMER_TABLE)] = g_l('backup', "[import_customers_data]");
		if(defined("SHOP_TABLE") && isset($this->handle_options["shop"]) && $this->handle_options["shop"])
			$this->description["import"][strtolower(SHOP_TABLE)] = g_l('backup', "[import_shop_data]");
		if(defined("ANZEIGE_PREFS_TABLE") && isset($this->handle_options["shop"]) && $this->handle_options["shop"])
			$this->description["import"][strtolower(ANZEIGE_PREFS_TABLE)] = g_l('backup', "[import_prefs]");
		$this->description["import"][strtolower(TEMPLATES_TABLE)] = g_l('backup', "[import_templates]");
		$this->description["import"][strtolower(TEMPORARY_DOC_TABLE)] = g_l('backup', "[import_temporary_data]");
		$this->description["import"][strtolower(BACKUP_TABLE)] = g_l('backup', "[external_backup]");
		$this->description["import"][strtolower(LINK_TABLE)] = g_l('backup', "[import_links]");
		$this->description["import"][strtolower(INDEX_TABLE)] = g_l('backup', "[import_indexes]");

		$this->description["export"][strtolower(CONTENT_TABLE)] = g_l('backup', "[export_content]");
		$this->description["export"][strtolower(FILE_TABLE)] = g_l('backup', "[export_files]");
		$this->description["export"][strtolower(DOC_TYPES_TABLE)] = g_l('backup', "[export_doctypes]");
		if(isset($this->handle_options["users"]) && $this->handle_options["users"])
			$this->description["export"][strtolower(USER_TABLE)] = g_l('backup', "[export_user_data]");
		if(defined("CUSTOMER_TABLE") && isset($this->handle_options["customers"]) && $this->handle_options["customers"])
			$this->description["export"][strtolower(CUSTOMER_TABLE)] = g_l('backup', "[export_customers_data]");
		if(defined("SHOP_TABLE") && isset($this->handle_options["shop"]) && $this->handle_options["shop"])
			$this->description["export"][strtolower(SHOP_TABLE)] = g_l('backup', "[export_shop_data]");
		if(defined("ANZEIGE_PREFS_TABLE") && isset($this->handle_options["shop"]) && $this->handle_options["shop"])
			$this->description["export"][strtolower(ANZEIGE_PREFS_TABLE)] = g_l('backup', "[export_prefs]");
		$this->description["export"][strtolower(TEMPLATES_TABLE)] = g_l('backup', "[export_templates]");
		$this->description["export"][strtolower(TEMPORARY_DOC_TABLE)] = g_l('backup', "[export_temporary_data]");
		$this->description["export"][strtolower(BACKUP_TABLE)] = g_l('backup', "[external_backup]");
		$this->description["export"][strtolower(LINK_TABLE)] = g_l('backup', "[export_links]");
		$this->description["export"][strtolower(INDEX_TABLE)] = g_l('backup', "[export_indexes]");
	}

	function exportGlobalPrefs(){
		$file = '/webEdition/we/include/conf/we_conf_global.inc.php';
		if(is_readable($_SERVER['DOCUMENT_ROOT'] . $file)){
			$this->exportFile($file);
		}
	}

	function writeFooter(){

		if($this->handle_options["settings"])
			$this->exportGlobalPrefs();
		weFile::save($this->dumpfilename, $this->footer, "ab");
	}

	//---------------------------------------------------------------------------------


	function getBackupQuery($table, $keys){
		//$keys=weTableItem::getTableKey($table);
		return "SELECT " . implode(",", $keys) . " FROM " . escape_sql_query($table) . " LIMIT " . intval($this->backup_step) . "," . intval($this->backup_steps);
	}

	function delOldTables(){
		if(!defined("OBJECT_X_TABLE"))
			return;
		if(!isset($this->handle_options["object"]))
			return;
		if(!$this->handle_options["object"])
			return;
		$this->backup_db->query("SHOW TABLE STATUS");
		while($this->backup_db->next_record()) {
			$table = $this->backup_db->f("Name");
			$name = stripTblPrefix($this->backup_db->f("Name"));
			if(substr(strtolower($name), 0, 10) == strtolower(stripTblPrefix(OBJECT_X_TABLE)) && is_numeric(str_replace(strtolower(OBJECT_X_TABLE), '', strtolower($table)))){
				weDBUtil::delTable($table);
			}
		}
	}

	function doUpdate(){
		$updater = new we_updater();
		$updater->doUpdate();
	}

	function clearTemporaryData($docTable){
		$this->backup_db->query('DELETE FROM ' . TEMPORARY_DOC_TABLE . ' WHERE DocTable="' . $this->backup_db->escape(stripTblPrefix($docTable)) . '"');
	}

}
