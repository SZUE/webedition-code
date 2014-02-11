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

//FIXME: handle to start different session if name doesn't match!

if(!isset($_SESSION)){
	session_name(SESSION_NAME);
	session_start();
}

//FIXME: still relevant?
//set new sessionID from dw-extension
/* if((isset($_SESSION['user']['ID']) && isset($_REQUEST['weSessionId']) && $_REQUEST['weSessionId'] != '' && isset($_REQUEST['cns']) && $_REQUEST['cns'] == 'dw')){
  $sid = htmlspecialchars(strip_tags($_REQUEST['weSessionId']));
  session_name(SESSION_NAME);
  session_id($sid);
  session_start();
  } */

if(!isset($_SESSION['weS'])){
	$_SESSION['weS'] = array();
	$_SESSION['user'] = array(
		'ID' => '', 'Username' => '', 'workSpace' => '', 'isWeSession' => false
	);
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

$userdata = getHash('SELECT UseSalt, passwd, username, LoginDenied, ID FROM ' . USER_TABLE . ' WHERE IsFolder=0 AND username="' . $DB_WE->escape($_POST['username']) . '"');

// only if username exists !!
if(empty($userdata) || (!we_users_user::comparePasswords($userdata['UseSalt'], $_POST['username'], $userdata['passwd'], $_POST['password']))){
	$_SESSION['user']['Username'] = '';
	we_users_user::removeOldWESession();
	return;
}

if($userdata['LoginDenied']){ // userlogin is denied
	$GLOBALS['userLoginDenied'] = true;
	return;
}

if(($userdata['UseSalt'] != we_users_user::SALT_CRYPT)){ //will cause update on old php-versions every time. since md5 doesn't cost much, ignore this.
	$salted = we_users_user::makeSaltedPassword($userdata['UseSalt'], $_POST['username'], $_POST['password']);
	// UPDATE Password with SALT
	$DB_WE->query('UPDATE ' . USER_TABLE . ' SET passwd="' . $DB_WE->escape($salted) . '",UseSalt=' . intval($userdata['UseSalt']) . ' WHERE IsFolder=0 AND username="' . $DB_WE->escape($_POST["username"]) . '" AND ID=' . $userdata['ID']);
}

if(!(isset($_SESSION['user']) && is_array($_SESSION['user']))){
	$_SESSION['user'] = array();
}

$_SESSION['user']['Username'] = $userdata['username'];
$_SESSION['user']['ID'] = $userdata['ID'];

if($_SESSION['user']['Username'] && $_SESSION['user']['ID']){

	$_SESSION['prefs'] = we_users_user::readPrefs($userdata['ID'], $GLOBALS['DB_WE'], true);

	$_SESSION['perms'] = we_users_user::getAllPermissions($_SESSION['user']['ID']);
	we_users_user::setEffectiveWorkspaces($_SESSION['user']['ID'], $GLOBALS['DB_WE']);
}
$_SESSION['user']['isWeSession'] = true; // for pageLogger, to know that it is really a webEdition session
//FIMXE make this a function to remove uneeded vars from global
unset($userdata);
