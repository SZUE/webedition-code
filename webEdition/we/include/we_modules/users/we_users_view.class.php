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
/* the parent class of storagable webEdition classes */


class we_users_view extends we_modules_view{

	function getJSTop(){
		$mod = we_base_request::_(we_base_request::STRING, 'mod', '');
		$modData = we_base_moduleInfo::getModuleData($mod);
		$title = isset($modData['text']) ? 'webEdition ' . g_l('global', '[modules]') . ' - ' . $modData['text'] : '';

		if(isset($_SESSION['user_session_data'])){
			unset($_SESSION['user_session_data']);
		}

		return we_html_element::jsElement('
var loaded=0;
var hot=0;
var frameset="' . $this->frameset . '";
WE().consts.g_l.users.view={
	save_changed_user:"' . g_l('modules_users', '[save_changed_user]') . '",
	give_org_name:"' . g_l('modules_users', '[give_org_name]') . '",
	password_alert:"' . we_message_reporting::prepareMsgForJS(g_l('modules_users', '[password_alert]')) . '"
};
WE().consts.dirs.WE_USERS_MODULE_DIR="' . WE_USERS_MODULE_DIR . '";

parent.document.title = "' . $title . '";
var cgroup=' . ($_SESSION['user']['ID'] ? intval(f('SELECT ParentID FROM ' . USER_TABLE . ' WHERE ID=' . $_SESSION["user"]["ID"])) : 0) . ';
') .
			we_html_element::jsScript(JS_DIR . 'we_modules/users/users_view.js');
	}

	function getJSProperty(){
		return
			weSuggest::getYuiFiles() .
			we_html_element::jsScript(JS_DIR . 'we_modules/users/users_property.js');
	}

	private function new_group(){
		if(!permissionhandler::hasPerm("NEW_GROUP")){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}

		$user_object = new we_users_user();

		if(($cgroup = we_base_request::_(we_base_request::INT, "cgroup"))){
			$user_group = new we_users_user();
			if($user_group->initFromDB($cgroup)){
				$user_object->ParentID = $cgroup;
			}
		}

		$user_object->initType(we_users_user::TYPE_USER_GROUP);

		$_SESSION["user_session_data"] = $user_object;

		echo we_html_element::jsElement('
top.content.editor.edheader.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edheader";
top.content.editor.edbody.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edbody";
top.content.editor.edfooter.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edfooter";');
	}

	private function new_alias(){
		if(!permissionhandler::hasPerm("NEW_USER")){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}

		$user_object = new we_users_user();

		if(($cgroup = we_base_request::_(we_base_request::INT, 'cgroup'))){
			$user_group = new we_users_user();
			if($user_group->initFromDB($cgroup)){
				$user_object->ParentID = $cgroup;
			}
		}

		$user_object->initType(we_users_user::TYPE_ALIAS);

		$_SESSION["user_session_data"] = $user_object;
		echo we_html_element::jsElement('
top.content.editor.edheader.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edheader";
top.content.editor.edbody.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edbody";
top.content.editor.edfooter.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edfooter";');
	}

	private function new_user(){
		if(!permissionhandler::hasPerm("NEW_USER")){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		$user_object = new we_users_user();

		if(($cgroup = we_base_request::_(we_base_request::INT, "cgroup"))){
			$user_group = new we_users_user();
			if($user_group->initFromDB($cgroup)){
				$user_object->ParentID = $cgroup;
			}
		}
		$user_object->initType(we_users_user::TYPE_USER);

		$_SESSION["user_session_data"] = $user_object;
		echo we_html_element::jsElement('
top.content.editor.edheader.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edheader";
top.content.editor.edbody.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edbody&oldtab=0";
top.content.editor.edfooter.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edfooter";');
	}

	private function display_user(){
		if(($uid = we_base_request::_(we_base_request::INT, 'uid'))){
			$user_object = new we_users_user();
			$user_object->initFromDB($uid);
			if(!permissionhandler::hasPerm("ADMINISTRATOR") && $user_object->checkPermission("ADMINISTRATOR")){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				$user_object = new we_users_user();
				return;
			}

			$_SESSION["user_session_data"] = $user_object;

			echo we_html_element::jsElement('top.content.usetHot();' .
				($user_object->Type == 1 ?
					'top.content.cgroup=' . $user_object->ID . ';' :
					'') . '
top.content.editor.edheader.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edheader";
top.content.editor.edbody.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edbody&oldtab=0";
top.content.editor.edfooter.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edfooter";');
		}
	}

	private function save_user(){

		$isAcError = false;
		$weAcQuery = new we_selector_query();
		$ob = we_base_request::_(we_base_request::STRING, 'obj_name');
		$uname = we_base_request::_(we_base_request::STRING, $ob . '_username');
		if($uname && !we_users_user::filenameNotValid($uname)){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('global', '[username_wrong_chars]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(!isset($_SESSION['user_session_data'])){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}

		if(($parent = we_base_request::_(we_base_request::INT, $ob . '_ParentID'))){
			$weAcResult = $weAcQuery->getItemById($parent, USER_TABLE, array('IsFolder'), false);
			if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}
		}
		$i = 0;
		while(($wsp = we_base_request::_(we_base_request::INT, $ob . '_Workspace_' . FILE_TABLE . '_' . $i))){
			$weAcResult = $weAcQuery->getItemById($wsp, FILE_TABLE, array("IsFolder"));
			if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
				$isAcError = true;
				break;
			}
			$i++;
		}
		$i = 0;
		while(($wsp = we_base_request::_(we_base_request::INT, $ob . '_Workspace_' . TEMPLATES_TABLE . '_' . $i))){
			$weAcResult = $weAcQuery->getItemById($wsp, TEMPLATES_TABLE, array("IsFolder"));
			if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
				$isAcError = true;
				break;
			}
			$i++;
		}
		$i = 0;
		while(($wsp = we_base_request::_(we_base_request::INT, $ob . '_Workspace_' . NAVIGATION_TABLE . '_' . $i))){
			$weAcResult = $weAcQuery->getItemById($wsp, NAVIGATION_TABLE, array("IsFolder"));
			if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
				$isAcError = true;
				break;
			}
			$i++;
		}
		if(defined('OBJECT_FILES_TABLE')){
			while(($wsp = we_base_request::_(we_base_request::INT, $ob . '_Workspace_' . OBJECT_FILES_TABLE . '_' . $i))){
				$weAcResult = $weAcQuery->getItemById($wsp, OBJECT_FILES_TABLE, array("IsFolder"));
				if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
					$isAcError = true;
					break;
				}
				$i++;
			}
		}

		if(defined('NEWSLETTER_TABLE')){
			while(($wsp = we_base_request::_(we_base_request::INT, $ob . '_Workspace_' . NEWSLETTER_TABLE . '_' . $i))){
				$weAcResult = $weAcQuery->getItemById($wsp, NEWSLETTER_TABLE, array("IsFolder"));
				if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
					$isAcError = true;
					break;
				}
				$i++;
			}
		}

		if($isAcError){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[workspaceFieldError]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		$user_object = $_SESSION['user_session_data'];
		if(($oldtab = we_base_request::_(we_base_request::INT, 'oldtab')) !== false && ($opb = we_base_request::_(we_base_request::STRING, 'old_perm_branch')) !== false){//FIXME: is latter ever used?
			$user_object->preserveState($oldtab, $opb);
			$_SESSION['user_session_data'] = $user_object;
		}

		if(!permissionhandler::hasPerm('ADMINISTRATOR') && $user_object->checkPermission('ADMINISTRATOR')){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			$user_object = new we_users_user();
			return;
		}
		$oldperm = $user_object->checkPermission('ADMINISTRATOR');
		if(!$user_object){
			return;
		}

		if(!permissionhandler::hasPerm('SAVE_USER') && ($user_object->Type == we_users_user::TYPE_USER || $user_object->Type == we_users_user::TYPE_ALIAS) && $user_object->ID != 0){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(!permissionhandler::hasPerm("NEW_USER") && ($user_object->Type == we_users_user::TYPE_USER || $user_object->Type == we_users_user::TYPE_ALIAS) && $user_object->ID == 0){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(!permissionhandler::hasPerm("SAVE_GROUP") && $user_object->Type == we_users_user::TYPE_USER_GROUP && $user_object->ID != 0){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(!permissionhandler::hasPerm("NEW_GROUP") && $user_object->Type == we_users_user::TYPE_USER_GROUP && $user_object->ID == 0){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(($ot = we_base_request::_(we_base_request::INT, 'oldtab'))){
			$user_object->preserveState($ot, we_base_request::_(we_base_request::STRING, 'old_perm_branch'));
		}

		$id = $user_object->ID;
		if(!$user_object->username && $user_object->Type != we_users_user::TYPE_ALIAS){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[username_empty]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}

		if($user_object->Alias == 0 && $user_object->Type == we_users_user::TYPE_ALIAS){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[username_empty]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		$exist = (f('SELECT 1 FROM ' . USER_TABLE . ' WHERE ID!=' . intval($user_object->ID) . ' AND username="' . $user_object->username . '" LIMIT 1'));
		if($exist && $user_object->Type != we_users_user::TYPE_ALIAS){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(sprintf(g_l('modules_users', '[username_exists]'), $user_object->username), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		if(($oldperm) && (!$user_object->checkPermission("ADMINISTRATOR")) && ($user_object->isLastAdmin())){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[modify_last_admin]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		$foo = ($user_object->ID ?
				getHash('SELECT ParentID FROM ' . USER_TABLE . ' WHERE ID=' . intval($user_object->ID), $user_object->DB_WE) :
				array('ParentID' => 0));

		$ret = $user_object->saveToDB();
		$_SESSION['user_session_data'] = $user_object;

		//	Save seem_startfile to DB when needed.
		if(($sid = we_base_request::_(we_base_request::INT, 'seem_start_file')) !== false){
			$uid = we_base_request::_(we_base_request::INT, 'uid');
			if($sid || (isset($_SESSION['save_user_seem_start_file'][$uid]))){
				$tmp = new DB_WE();

				if($sid !== false){
					//	save seem_start_file from REQUEST
					$seem_start_file = $sid;
					if($user_object->ID == $_SESSION['user']['ID']){ // change preferences if user edits his own startfile
						$_SESSION['prefs']['seem_start_file'] = $seem_start_file;
					}
				} else {
					//	Speichere seem_start_file aus SESSION
					$seem_start_file = $_SESSION['save_user_seem_start_file'][$uid];
				}

				$tmp->query('REPLACE INTO ' . PREFS_TABLE . ' SET userID=' . $uid . ',`key`="seem_start_file",`value`="' . $tmp->escape($seem_start_file) . '"');
				unset($tmp);
				unset($seem_start_file);
				if(isset($_SESSION['save_user_seem_start_file'][$uid])){
					unset($_SESSION['save_user_seem_start_file'][$uid]);
				}
			}
		}

		if($ret == we_users_user::ERR_USER_PATH_NOK){
			echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[user_path_nok]'), we_message_reporting::WE_MESSAGE_ERROR));
			return;
		}
		$tree_code = ($id ?
				'top.content.treeData.updateEntry({id:' . $user_object->ID . ',parentid:' . $user_object->ParentID . ',text:"' . $user_object->Text . '",class:"' . ($user_object->checkPermission('ADMINISTRATOR') ? 'bold ' : '') . ($user_object->LoginDenied ? 'red' : '') . '"});' :
				'top.content.treeData.makeNewEntry({id:' . $user_object->ID . ',parentid:' . $user_object->ParentID . ',text:"' . $user_object->Text . '",open:false,contenttype:"' . (($user_object->Type == we_users_user::TYPE_USER_GROUP) ? "we/userGroup" : (($user_object->Type == we_users_user::TYPE_ALIAS) ? "we/alias" : "we/user")) . '",table:"' . USER_TABLE . '",class:"' . ($user_object->checkPermission('ADMINISTRATOR') ? 'bold ' : '') . ($user_object->LoginDenied ? 'red' : '') . '"});') .
			'top.content.editor.edheader.document.getElementById("titlePath").innerText="' . $user_object->Path . '";';

		switch($user_object->Type){
			case we_users_user::TYPE_ALIAS:
				$savemessage = we_message_reporting::getShowMessageCall(sprintf(g_l('modules_users', '[alias_saved_ok]'), $user_object->Text), we_message_reporting::WE_MESSAGE_NOTICE);
				break;
			case we_users_user::TYPE_USER_GROUP:
				$savemessage = we_message_reporting::getShowMessageCall(sprintf(g_l('modules_users', '[group_saved_ok]'), $user_object->Text), we_message_reporting::WE_MESSAGE_NOTICE);
				break;
			case we_users_user::TYPE_USER:
			default:
				$savemessage = we_message_reporting::getShowMessageCall(sprintf(g_l('modules_users', '[user_saved_ok]'), $user_object->Text), we_message_reporting::WE_MESSAGE_NOTICE);
				break;
		}

		if($user_object->Type == we_users_user::TYPE_USER){
			$tree_code .= 'top.content.cgroup=' . $user_object->ParentID . ';';
		}
		echo we_html_element::jsElement('top.content.usetHot();' . $tree_code . $savemessage . $ret);
	}

	private function delete_user(){

		if(!empty($_SESSION["user_session_data"])){
			$user_object = $_SESSION["user_session_data"];

			if($user_object->ID == $_SESSION["user"]["ID"]){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[delete_user_same]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}

			if(we_users_util::isUserInGroup($_SESSION["user"]["ID"], $user_object->ID)){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[delete_group_user_same]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}

			if(!permissionhandler::hasPerm("ADMINISTRATOR") && $user_object->checkPermission("ADMINISTRATOR")){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				$user_object = new we_users_user();
				return;
			}
			if(!permissionhandler::hasPerm("DELETE_USER") && $user_object->Type == we_users_user::TYPE_USER){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}
			if(!permissionhandler::hasPerm("DELETE_GROUP") && $user_object->Type == we_users_user::TYPE_USER_GROUP){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}

			if(isset($GLOBALS["user"]) && $user_object->Text == $GLOBALS["user"]["Username"]){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[user_same]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}

			if($user_object->checkPermission("ADMINISTRATOR")){
				if($user_object->isLastAdmin()){
					echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_users', '[modify_last_admin]'), we_message_reporting::WE_MESSAGE_ERROR));
					exit();
				}
			}

			switch($user_object->Type){
				case we_users_user::TYPE_USER_GROUP:
					$question = sprintf(g_l('modules_users', '[delete_alert_group]'), $user_object->Text);
					break;
				case we_users_user::TYPE_ALIAS:
					$question = sprintf(g_l('modules_users', '[delete_alert_alias]'), $user_object->Text);
					break;
				case we_users_user::TYPE_USER:
				default:
					$question = sprintf(g_l('modules_users', '[delete_alert_user]'), $user_object->Text);
					break;
			}
			echo we_html_element::jsElement('
		if(confirm("' . $question . '")){
			top.content.cmd.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=cmd&ucmd=do_delete";
		}');
		}
	}

	private function do_delete(){
		if($_SESSION['user_session_data']){
			$user_object = $_SESSION["user_session_data"];
			if(!permissionhandler::hasPerm('DELETE_USER') && $user_object->Type == we_users_user::TYPE_USER){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}
			if(!permissionhandler::hasPerm('DELETE_GROUP') && $user_object->Type == we_users_user::TYPE_USER_GROUP){
				echo we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR));
				return;
			}
			if($user_object->deleteMe()){
				echo we_html_element::jsElement('
		top.content.treeData.deleteEntry(' . $user_object->ID . ');
		top.content.editor.edheader.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edheader&home=1";
		top.content.editor.edbody.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edbody&home=1";
		top.content.editor.edfooter.location=WE().consts.dirs.WEBEDITION_DIR + "we_showMod.php?mod=users&pnt=edfooter&home=1";');
				unset($_SESSION["user_session_data"]);
			}
		}
	}

	private function check_user_display(){
		if(($uid = we_base_request::_(we_base_request::INT, 'uid'))){
			$mpid = f('SELECT ParentID FROM ' . USER_TABLE . ' WHERE ID=' . intval($_SESSION["user"]["ID"]), '', $this->db);
			$pid = f('SELECT ParentID FROM ' . USER_TABLE . ' WHERE ID=' . $uid, '', $this->db);

			$search = true;
			$found = false;
			$first = true;

			while($search){
				if($mpid == $pid){
					$search = false;
					if(!$first){
						$found = true;
					}
				}
				$first = false;
				if($pid == 0){
					$search = false;
				}
				$pid = intval(f('SELECT ParentID FROM ' . USER_TABLE . ' WHERE ID=' . intval($pid), '', $this->db));
			}

			echo we_html_element::jsElement(
				($found || permissionhandler::hasPerm('ADMINISTRATOR') ?
					'top.content.we_cmd(\'display_user\',' . $uid . ')' :
					we_message_reporting::getShowMessageCall(g_l('alert', '[access_denied]'), we_message_reporting::WE_MESSAGE_ERROR)
			));
		}
	}

	function processCommands(){
		switch(we_base_request::_(we_base_request::STRING, "ucmd")){
			case "new_group":
				return $this->new_group();
			case "new_alias":
				return $this->new_alias();
			case "search":
				echo we_html_element::jsElement('top.content.editor.edbody.location="' . WE_USERS_MODULE_DIR . 'edit_users_sresults.php?kwd=' . we_base_request::_(we_base_request::STRING, "kwd") . '";');
				return;
			case "new_user":
				return $this->new_user();
			case "display_user":
				return $this->display_user();
			case "save_user":
				return $this->save_user();
			case "delete_user":
				return $this->delete_user();
			case 'do_delete':
				return $this->do_delete();
			case 'check_user_display':
				return $this->check_user_display();
		}
	}

	function processVariables(){
		if(($page = we_base_request::_(we_base_request::INT, 'page')) !== false){
			$this->page = $page;
		}
	}

	public function getHomeScreen(){
		$content = we_html_element::cssElement('
	i.alias{
	color:grey;
	}
') . we_html_button::create_button('fat:create_user,fa-lg fa-user-plus', "javascript:top.we_cmd('new_user');", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_USER")) .
			we_html_button::create_button('fat:create_group,fa-lg fa-users,fa-plus', "javascript:top.we_cmd('new_group');", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_GROUP")) .
			we_html_button::create_button('fat:create_alias,alias fa-lg fa-user-plus', "javascript:top.we_cmd('new_alias');", true, 0, 0, "", "", !permissionhandler::hasPerm("NEW_ALIAS"));

		return parent::getActualHomeScreen('users', "user.gif", $content);
	}

}
