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
if(isset($_SERVER['SCRIPT_NAME']) && str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']) == str_replace(dirname(__FILE__), '', __FILE__)){
	exit();
}

if(!isset($_SESSION)){
	@session_start();
	//FIXME: remove in 6.4; due to upgrade!
	if(isset($_SESSION['we'])){
		unset($_SESSION['we']);
	}
}
if(!isset($_SESSION['weS'])){
	$_SESSION['weS'] = array();
}

if(!isset($_SESSION['user'])){
	$_SESSION['user'] = array(
		'ID' => '', 'Username' => '', 'workSpace' => '', 'isWeSession' => false
	);
}

$we_transaction = isset($_REQUEST['we_transaction']) ? $_REQUEST['we_transaction'] : md5(uniqID('', true));
$we_transaction = (preg_match('|^([a-f0-9]){32}$|i', $we_transaction) ? $we_transaction : md5(uniqID('', true)));

if(!isset($_SESSION['weS']['we_data'])){
	$_SESSION['weS']['we_data'] = array($we_transaction => '');
}

$_SESSION['weS']['EditPageNr'] = (isset($_SESSION['weS']['EditPageNr']) && (($_SESSION['weS']['EditPageNr'] != '') || ($_SESSION['weS']['EditPageNr'] == 0))) ? $_SESSION['weS']['EditPageNr'] : 1;

if(!(isset($_POST['username']) && isset($_POST['password']))){
	return;
}

$_userdata = getHash('SELECT UseSalt, passwd, username, LoginDenied, ID FROM ' . USER_TABLE . ' WHERE IsFolder=0 AND username="' . $DB_WE->escape($_POST['username']) . '"', $DB_WE);

// only if username exists !!
if(empty($_userdata) || (!we_user::comparePasswords($_userdata['UseSalt'], $_POST['username'], $_userdata['passwd'], $_POST['password']))){
	$_SESSION['user']['Username'] = '';
	we_user::removeOldWESession();
	return;
}

if($_userdata['LoginDenied']){ // userlogin is denied
	$GLOBALS['userLoginDenied'] = true; #
	return;
}

if(($_userdata['UseSalt'] < 2)){ //will cause update on old php-versions every time. since md5 doesn't cost much, ignore this.
	$salted = we_user::makeSaltedPassword($_userdata['UseSalt'], $_POST['username'], $_POST['password']);
	// UPDATE Password with SALT
	$DB_WE->query('UPDATE ' . USER_TABLE . ' SET passwd="' . $DB_WE->escape($salted) . '",UseSalt=' . intval($_userdata['UseSalt']) . ' WHERE IsFolder=0 AND username="' . $DB_WE->escape($_POST["username"]) . '" AND ID=' . $_userdata['ID']);
}

if(!(isset($_SESSION['user']) && is_array($_SESSION['user']))){
	$_SESSION['user'] = array();
}

$_SESSION['user']['Username'] = $_userdata['username'];
$_SESSION['user']['ID'] = $_userdata['ID'];

$workspaces = array(
	FILE_TABLE => array('key' => 'workSpace', 'value' => array(), 'parent' => 0, 'parentKey' => 'ParentWs'),
	TEMPLATES_TABLE => array('key' => 'workSpaceTmp', 'value' => array(), 'parent' => 0, 'parentKey' => 'ParentWst'),
	NAVIGATION_TABLE => array('key' => 'workSpaceNav', 'value' => array(), 'parent' => 0, 'parentKey' => 'ParentWsn'),
);

if(defined('OBJECT_FILES_TABLE')){
	$workspaces[OBJECT_FILES_TABLE] = array('key' => 'workSpaceObj', 'value' => array(), 'parent' => 0, 'parentKey' => 'ParentWso');
}
if(defined('NEWSLETTER_TABLE')){
	$workspaces[NEWSLETTER_TABLE] = array('key' => 'workSpaceNwl', 'value' => array(), 'parent' => 0, 'parentKey' => 'ParentWsnl');
}

$fields = array('ParentID');
foreach($workspaces as $cur){
	$fields[] = $cur['key'];
	$fields[] = $cur['parentKey'];
}
$fields = implode(',', $fields);

$_userGroups = array(); //	Get Groups user belongs to.
$db_tmp = new DB_WE();

$DB_WE->query('SELECT ' . $fields . ' FROM ' . USER_TABLE . ' WHERE ID=' . intval($_SESSION['user']['ID']) . ' OR Alias=' . intval($_SESSION['user']['ID']));
while($DB_WE->next_record()){
	$pid = $DB_WE->f('ParentID');

	foreach($workspaces as &$cur){
		// get workspaces
		$a = explode(',', trim($DB_WE->f($cur['key']), ','));
		foreach($a as $k => $v){
			$cur['value'][] = $v;
		}
		$cur['parent'] = $DB_WE->f($cur['parentKey']);
	}
	unset($cur);
	while($pid){ //	For each group
		$_userGroups[] = $pid;

		$row = getHash('SELECT ' . $fields . ' FROM ' . USER_TABLE . ' WHERE ID=' . intval($pid), $db_tmp);
		if(!empty($row)){
			$pid = $row['ParentID'];
			foreach($workspaces as &$cur){
				if($cur['parent']){
					// get workspaces
					$a = explode(',', trim($row[$cur['key']], ','));
					foreach($a as $k => $v){
						$cur['value'][] = $v;
					}
				}
				$cur['parent'] = $row[$cur['parentKey']];
			}
			unset($cur);
		} else {
			$pid = 0;
		}
	}
}
$_SESSION['user']['groups'] = $_userGroups; //	order: first is folder with user himself (deepest in tree)
$_SESSION['user']['workSpace'] = array();

foreach($workspaces as $key => $cur){
	$_SESSION['user']['workSpace'][$key] = array_unique(array_filter($cur['value']));
}

$_SESSION['prefs'] = we_user::readPrefs($_userdata['ID'], $DB_WE, true);

if(isset($_SESSION['user']['Username']) && isset($_SESSION['user']['ID']) && $_SESSION['user']['Username'] && $_SESSION['user']['ID']){
	$_SESSION['perms'] = we_user::getAllPermissions($_SESSION['user']['ID']);
}
$_SESSION['user']['isWeSession'] = true; // for pageLogger, to know that it is really a webEdition session