#!/usr/bin/php
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
/*
 * The script exports webEdition backup file to the given file
 * webEdition must be installed
 */

require_once("cliConfig.php");

// CONFIGURATION BEGINS ---------------------------------------------------------

/**
 * Path to backup file
 */
$_backup_filename = $_SERVER['DOCUMENT_ROOT'] . '/weBackup_daily.xml';

// export details

/**
 * export webEdition Core Data (Documents, Templates and Navigation)
 */
$_REQUEST = array(
	'handle_core' => true,
	/**
	 * export binary data
	 */
	'handle_binary' => true,
	/**
	 * export version data
	 */
	'handle_versions' => true,
	/**
	 * export version binary files
	 */
	'handle_versions_binarys' => true,
	/**
	 * export user data
	 */
	'handle_user' => true,
	/**
	 * export customer data
	 */
	'handle_customer' => true,
	/**
	 * export shop data
	 */
	'handle_shop' => true,
	/**
	 * export workflow data
	 */
	'handle_workflow' => true,
	/**
	 * export user data
	 */
	'handle_todo' => true,
	/**
	 * export newsletter data
	 */
	'handle_newsletter' => true,
	/**
	 * export temporary data
	 */
	'handle_temporary' => true,
	/**
	 * export banner data
	 */
	'handle_banner' => true,
	/**
	 * export objects and classes
	 */
	'handle_object' => true,
	/**
	 * export scheduler data
	 */
	'handle_schedule' => true,
	/**
	 * export settings and preferences
	 */
	'handle_settings' => true,
	/**
	 * export configuration data
	 */
	'handle_configuration' => true,
	/**
	 * export webEdition export data
	 */
	'handle_export' => true,
	/**
	 * export voting data
	 */
	'handle_voting' => true,
	/**
	 * export extern fi�es
	 */
	'handle_extern' => false,
// be user friendly :-)
	'verbose' => true,
);

// CONFIGURATION ENDS ---------------------------------------------------------
// we want to see errors
ini_set("display_errors", 1);
error_reporting(E_ALL);

//use we-error handler; ignore if logging is disabled!
require_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_error_handler.inc.php');
if(!defined('WE_ERROR_SHOW')){
	define('WE_ERROR_SHOW', 1);
}
if(!defined('WE_ERROR_LOG')){
	define('WE_ERROR_LOG', 1);
}

we_error_handler(false);

// knock out identifiation and permissions
$_SESSION['perms'] = array('ADMINISTRATOR' => true);
$_SESSION['user']['Username'] = 1;


if(!isset($_SERVER['SERVER_NAME'])){
	$_SERVER['SERVER_NAME'] = $SERVER_NAME;
}

// include needed libraries
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');


// Define exit codes for errors
define('NO_ARGS', 10);
define('INVALID_OPTION', 11);

// Reading the incoming arguments - same as $argv
$args = Console_Getopt::readPHPArgv();


$_cliHelp = 'Usage: recoverBackup.php [options] [file]
Options:
  -v, --verbose              Verbosely list files processed

  --help                     Prints out this help

 Options what to recover:
  --all                      Recover everything except external files
  --core                     Documents and templates
  --versions                 Versions
  --versions_binarys         Versions binarys
  --binary                   Binary data
  --user                     User data
  --customer                 Customer data
  --shop                     Shop data
  --workflow                 Workflow data
  --todo                     Task/Messaging data
  --newsletter               Newsletter data
  --temporary                Temporary data (unpublished data)
  --banner                   Banner data
  --object                   Objects and Classes
  --schedule                 Scheduler data
  --settings                 Settings
  --configuration            Configuration Data
  --export                   webEdition-Export Data
  --voting                   Voting data
  --extern                   Extern files (only use this if you have
                             enough memory and cpu power)
';

// Make sure we got them (for non CLI binaries)
if(PEAR::isError($args)){
	fwrite(STDERR, $args->getMessage() . "\n");
	exit(NO_ARGS);
}

// Short options
$short_opts = 'v';

// Long options
$long_opts = array(
	'all',
	'core',
	'versions',
	'versions_binarys',
	'binary',
	'user',
	'customer',
	'shop',
	'workflow',
	'todo',
	'newsletter',
	'temporary',
	'banner',
	'object',
	'schedule',
	'settings',
	'configuration',
	'export',
	'voting',
	'extern',
	'verbose',
	'help'
);

// Convert the arguments to options - check for the first argument
if($_SERVER['argv'] && realpath($_SERVER['argv'][0]) == __FILE__){
	$options = Console_Getopt::getOpt($args, $short_opts, $long_opts);
} else {
	$options = Console_Getopt::getOpt2($args, $short_opts, $long_opts);
}

// Check the options are valid
if(PEAR::isError($options)){
	fwrite(STDERR, $options->getMessage() . "\n");
	fwrite(STDERR, $_cliHelp . "\n");
	exit(INVALID_OPTION);
}
if($args){
	$_REQUEST['verbose'] = false;
	_checkAll(false);
	$_REQUEST['handle_extern'] = false;
}

foreach($options[0] as $opt){
	switch($opt[0]){
		case '--all':
			_checkAll(true);
			break;

		case 'v':
		case '--verbose':
			$_REQUEST['verbose'] = true;
			break;

		case '--help':
			print $_cliHelp;
			exit(0);
			break;

		default:
			$_REQUEST['handle_' . preg_replace('/^--/', '', $opt[0])] = true;
	}
}


// check if no option is checked

$__optionsSelected = false;

foreach($_REQUEST as $_k => $_v){
	if(substr($_k, 0, 7) == "handle_"){
		if($_v){
			$__optionsSelected = true;
			break;
		}
	}
}

// if no option is checked, then check the dafaults
if($__optionsSelected === false){
	$_REQUEST['handle_core'] = true;
}


if(isset($options[1][0])){
	$_backup_filename = $options[1][0];
}



update_time_limit(0);
update_mem_limit(128);

$_REQUEST['backup_select'] = basename($_backup_filename);
if($_backup_filename != $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . $_REQUEST['backup_select']){
	copy($_backup_filename, $_SERVER['DOCUMENT_ROOT'] . BACKUP_DIR . $_REQUEST['backup_select']);
}

if(we_backup_preparer::prepareImport() === true){
	if($_REQUEST['verbose']){
		print "\nImporting from " . $_backup_filename . "...\n";
	}
	while(($_SESSION['weS']['weBackupVars']['offset'] < $_SESSION['weS']['weBackupVars']['offset_end'])){
		if($_REQUEST['verbose']){
			print '-';
		}
		if(!we_backup_import::import($_SESSION['weS']['weBackupVars']['backup_file'], $_SESSION['weS']['weBackupVars']['offset'], $_SESSION['weS']['weBackupVars']['backup_steps'], $_SESSION['weS']['weBackupVars']['options']['compress'] != we_backup_util::NO_COMPRESSION, $_SESSION['weS']['weBackupVars']['encoding'])){
			break;
		}
	}

	if($_REQUEST['verbose']){
		print "\nUpdate...\n";
	}

	we_updater::doUpdate();
} else {
	print we_backup_preparer::getErrorMessage();
}

unset($_SESSION['weS']['weBackupVars']);
//unlink($_SERVER['DOCUMENT_ROOT'].'/webEdition/we_backup/'.$_REQUEST['backup_select']);

if($_REQUEST['verbose']){
	print "\nDone\n";
}

function _checkAll($flag = true){
	$_REQUEST['handle_core'] = $flag;
	$_REQUEST['handle_binary'] = $flag;
	$_REQUEST['handle_versions'] = $flag;
	$_REQUEST['handle_versions_binarys'] = $flag;
	$_REQUEST['handle_user'] = $flag;
	$_REQUEST['handle_customer'] = $flag;
	$_REQUEST['handle_shop'] = $flag;
	$_REQUEST['handle_workflow'] = $flag;
	$_REQUEST['handle_todo'] = $flag;
	$_REQUEST['handle_newsletter'] = $flag;
	$_REQUEST['handle_temporary'] = $flag;
	$_REQUEST['handle_banner'] = $flag;
	$_REQUEST['handle_object'] = $flag;
	$_REQUEST['handle_schedule'] = $flag;
	$_REQUEST['handle_settings'] = $flag;
	$_REQUEST['handle_configuration'] = $flag;
	$_REQUEST['handle_export'] = $flag;
	$_REQUEST['handle_voting'] = $flag;
}
