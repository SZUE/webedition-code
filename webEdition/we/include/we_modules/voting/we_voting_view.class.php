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
class we_voting_view extends we_modules_view{
	var $voting;
	var $icon_pattern = "";

	function __construct(){
		parent::__construct();
		$this->voting = new we_voting_voting();
	}

	function getCommonHiddens($cmds = []){
		return
			parent::getCommonHiddens($cmds) .
			we_html_element::htmlHiddens(["vernr" => (isset($cmds["vernr"]) ? $cmds["vernr"] : 0),
				"IsFolder" => (isset($this->voting->IsFolder) ? $this->voting->IsFolder : '0')
		]);
	}

	function getJSTop(){
		$mod = we_base_request::_(we_base_request::STRING, 'mod', '');
		$modData = we_base_moduleInfo::getModuleData($mod);
		$title = isset($modData['text']) ? 'webEdition ' . g_l('global', '[modules]') . ' - ' . $modData['text'] : '';

		return
			parent::getJSTop() .
			we_html_element::jsScript(WE_JS_MODULES_DIR . '/voting/voting_top.js', "parent.document.title='" . $title . "'");
	}

	function getJSProperty(array $jsVars = []){
		$this->voting->Owners = (is_array($this->voting->Owners) ? array_filter($this->voting->Owners) : []);
		$owners = ($this->voting->Owners ?
			$this->db->getAllFirstq('SELECT ID,Path,IsFolder FROM ' . USER_TABLE . ' WHERE ID IN (' . implode(',', $this->voting->Owners) . ')', true, MYSQLI_ASSOC) :
			[]);
		return we_html_element::jsScript(WE_JS_MODULES_DIR . '/voting/voting_prop.js', '', ['id' => 'loadVarVoting', 'data-voting' => setDynamicVar([
					'isFolder' => $this->voting->IsFolder,
					'delBut' => we_html_button::create_button(we_html_button::TRASH, 'javascript:top . content . setHot(); #####placeHolder#####'),
					'delBut1' => we_html_button::create_button(we_html_button::TRASH, 'javascript:top.content.setHot();if(answers_edit.itemCount>answers_edit.minCount) #####placeHolder#####; else callAnswerLimit();'),
					'answerCount' => count($this->voting->QASet[0]['answers']),
					'QASet' => $this->voting->QASet,
					'QASetAdditions' => $this->voting->QASetAdditions,
					'showVariant' => we_base_request::_(we_base_request::INT, 'vernr', 0),
					'allow' => [
						'freeText' => $this->View->voting->AllowFreeText,
						'images' => $this->View->voting->AllowImages,
						'media' => $this->View->voting->AllowMedia,
						'successor' => $this->View->voting->AllowSuccessors
					],
					'owners' => $owners,
					'blackList' => is_array($this->voting->BlackList) ? $this->voting->BlackList : []
		])]);
	}

	function processCommands(we_base_jsCmd $jscmd){
		switch(we_base_request::_(we_base_request::STRING, "cmd")){
			case "resetscores":
				foreach($this->voting->arr_Scores as &$val){
					$val = 0;
				}
				break;
			case "new_voting":
			case "new_voting_group":
				if(!we_base_permission::hasPerm("NEW_VOTING")){
					$jscmd->addMsg(g_l('modules_voting', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}
				$this->voting = new we_voting_voting();
				$this->voting->IsFolder = we_base_request::_(we_base_request::STRING, "cmd") === 'new_voting_group' ? 1 : 0;
				$jscmd->addCmd('loadHeaderFooter', $this->voting->Text);
				break;
			case "voting_edit":
				if(!we_base_permission::hasPerm("EDIT_VOTING")){
					$jscmd->addMsg(g_l('modules_voting', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR);
					$_REQUEST['home'] = '1';
					$_REQUEST['pnt'] = 'edbody';
					break;
				}

				$this->voting = new we_voting_voting(we_base_request::_(we_base_request::INT, "cmdid"));

				if(!$this->voting->isAllowedForUser()){
					$jscmd->addMsg(g_l('modules_voting', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR);
					$this->voting = new we_voting_voting();
					$_REQUEST["home"] = true;
					break;
				}
				$jscmd->addCmd('loadHeaderFooter', $this->voting->Text);
				break;
			case "save_voting":
				if(!we_base_permission::hasPerm("NEW_VOTING") && !we_base_permission::hasPerm("EDIT_VOTING")){
					$jscmd->addMsg(g_l('modules_voting', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}

				if(we_voting_voting::filenameNotValid($this->voting->Text)){
					$jscmd->addMsg(g_l('modules_voting', '[wrongtext]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}

				if(!trim($this->voting->Text)){
					$jscmd->addMsg(g_l('modules_voting', '[name_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}

				if($this->voting->Active == 1 && $this->voting->ActiveTime && $this->voting->Valid < time()){
					$jscmd->addMsg(g_l('modules_voting', '[not_active]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}

				$oldpath = $this->voting->Path;
				// set the path and check it
				$this->voting->setPath();

				if($this->voting->pathExists($this->voting->Path)){
					$jscmd->addMsg(g_l('modules_voting', '[name_exists]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}
				if($this->voting->isSelf()){
					$jscmd->addMsg(g_l('modules_voting', '[path_nok]'), we_message_reporting::WE_MESSAGE_ERROR);
					break;
				}

				$error = false;
				$q_empty = true;
				$a_empty = true;
				if(!$this->voting->IsFolder && !empty($this->voting->QASet)){
					foreach($this->voting->QASet as $set){
						$q = trim($set['question']);
						if($q === ''){
							$q_empty = true;
							break;
						}
						$q_empty = false;

						foreach($set['answers'] as $ans){
							$q = trim($ans);
							if($q === ''){
								$a_empty = true;
								break;
							}
							$a_empty = false;
						}
					}

					if($q_empty){
						$error = true;
						$jscmd->addMsg(g_l('modules_voting', '[question_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					} else if($a_empty){
						$error = true;
						$jscmd->addMsg(g_l('modules_voting', '[answer_empty]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					}
				}

				if($this->voting->ParentID > 0){
					$weAcQuery = new we_selector_query();
					$weAcResult = $weAcQuery->getItemById($this->voting->ParentID, VOTING_TABLE, ["IsFolder"]);
					if(!is_array($weAcResult) || $weAcResult[0]['IsFolder'] == 0){
						$jscmd->addMsg(g_l('modules_voting', '[path_nok]'), we_message_reporting::WE_MESSAGE_ERROR);
						break;
					}
				}
				if(!$error){
					$newone = ($this->voting->ID == 0);

					$this->voting->save(we_base_request::_(we_base_request::BOOL, 'scores_changed'));

					if($this->voting->IsFolder && $oldpath != '' && $oldpath != '/' && $oldpath != $this->voting->Path){
						$db_tmp = new DB_WE();
						$this->db->query('SELECT ID FROM ' . VOTING_TABLE . ' WHERE Path LIKE "' . $db_tmp->escape($oldpath) . '%" AND ID!=' . intval($this->voting->ID));
						while($this->db->next_record()){
							$db_tmp->query('UPDATE ' . VOTING_TABLE . ' SET Path="' . $this->voting->evalPath($this->db->f('ID'), $db_tmp) . '" WHERE ID=' . $this->db->f('ID'));
						}
					}

					if($newone){
						$jscmd->addCmd('makeTreeEntry', [
							'id' => $this->voting->ID,
							'parentid' => $this->voting->ParentID,
							'text' => $this->voting->Text,
							'open' => false,
							'contenttype' => ($this->voting->IsFolder ? we_base_ContentTypes::FOLDER : 'we/voting'),
							'table' => VOTING_TABLE,
							'published' => ($this->voting->isActive() ? 1 : 0)
						]);
						$jscmd->addCmd('drawTree');
					} else {
						$jscmd->addCmd('updateTreeEntry', [
							'id' => $this->voting->ID,
							'parentid' => $this->voting->ParentID,
							'text' => $this->voting->Text,
							'published' => ($this->voting->isActive() ? 1 : 0)
						]);
					}
					$jscmd->addCmd('loadHeaderFooter', $this->voting->Text);
					$jscmd->addMsg(g_l('modules_voting', ($this->voting->IsFolder ? '[save_group_ok]' : '[save_ok]')), we_message_reporting::WE_MESSAGE_NOTICE);
				}
				break;
			case 'delete_voting':
				if(!we_base_permission::hasPerm("DELETE_VOTING")){
					$jscmd->addMsg(g_l('modules_voting', '[no_perms]'), we_message_reporting::WE_MESSAGE_ERROR);
					return;
				}
				if($this->voting->delete()){
					$jscmd->addCmd('deleteTreeEntry', $this->voting->ID);
					$jscmd->addMsg(g_l('modules_voting', ($this->Model->IsFolder == 1 ? '[group_deleted]' : '[voting_deleted]')), we_message_reporting::WE_MESSAGE_NOTICE);
					$this->voting = new we_voting_voting();
					$_REQUEST['home'] = 1;
					$_REQUEST['pnt'] = 'edbody';
				} else {
					$jscmd->addMsg(g_l('modules_voting', '[nothing_to_delete]'), we_message_reporting::WE_MESSAGE_ERROR);
				}

				break;
			case "switchPage":
				break;
			case "export_csv":
				$fname = rtrim(we_base_request::_(we_base_request::FILE, 'csv_dir'), '/') . '/voting_' . $this->voting->ID . '_export_' . time() . '.csv';

				$enclose = we_base_request::_(we_base_request::STRING, 'csv_enclose', '"');
				$enclose = $enclose == 0 ? '"' : '\'';
				$delimiter = we_base_request::_(we_base_request::STRING, ';');
				$delimiter = ($delimiter === '\t' ? "\t" : $delimiter);
				switch(we_base_request::_(we_base_request::STRING, 'csv_lineend')){
					default:
					case 'windows':
						$lineend = "\r\n";
						break;
					case 'unix':
						$lineend = "\n";
						break;
					case 'mac':
						$lineend = "\r";
						break;
				}

				$content = [];
				$questName = we_base_request::_(we_base_request::STRING, 'question_name');
				if($questName && ($data = we_base_request::_(we_base_request::STRING, $questName . '_item0'))){
					$content[] = $enclose . addslashes($data) . $enclose . $delimiter;
				}
				$answerName = we_base_request::_(we_base_request::STRING, 'answers_name');
				$cnt = we_base_request::_(we_base_request::INT, 'item_count');
				if($answerName && $cnt){
					for($i = 0; $i < $cnt; $i++){
						if(($data = we_base_request::_(we_base_request::STRING, $answerName . '_item' . $i))){
							$content[] = $enclose . addslashes($data) . $enclose . $delimiter . $this->voting->Scores[$i];
						}
					}
				}
				we_base_file::save($_SERVER['DOCUMENT_ROOT'] . $fname, implode($lineend, $content));
				$_REQUEST["lnk"] = $fname;
				break;
			case 'exportGroup_csv':
				$fname = '/' . ltrim(we_base_request::_(we_base_request::FILE, 'csv_dir') . '/votingGroup_' . $this->voting->ID . '_export_' . time() . '.csv', '/');

				$enclose = we_base_request::_(we_base_request::STRING, 'csv_enclose', '"');
				$enclose = $enclose == 0 ? '"' : $enclose;
				$delimiter = we_base_request::_(we_base_request::STRING, 'csv_delimiter', ';');
				$delimiter = $delimiter === '\t' ? "\t" : $delimiter;
				switch(we_base_request::_(we_base_request::STRING, 'csv_lineend')){
					default:
					case 'windows':
						$lineend = "\r\n";
						break;
					case 'unix':
						$lineend = "\n";
						break;
					case 'mac':
						$lineend = "\r";
						break;
				}

				$allData = $this->voting->loadDB();
				$CSV_Charset = we_base_request::_(we_base_request::STRING, 'the_charset', 'UTF-8');
				$content = [$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[voting-session]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[voting-id]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[time]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[ip]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[user_agent]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[cookie]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[log_fallback]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[status]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[answerID]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[answerText]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[voting-successor]'))) . $enclose . $delimiter .
					$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(g_l('modules_voting', '[voting-additionalfields]'))) . $enclose . $delimiter
				];

				foreach($allData as $key => $data){
					$cookie = g_l('modules_voting', $data['cookie'] ? '[enabled]' : '[disabled]');
					$fallback = g_l('global', $data['fallback'] ? '[yes]' : '[no]');

					if($data['status'] != we_voting_voting::SUCCESS){
						switch($data['status']){
							case we_voting_voting::ERROR :
								$mess = g_l('modules_voting', '[log_error]');
								break;
							case we_voting_voting::ERROR_ACTIVE :
								$mess = g_l('modules_voting', '[log_error_active]');
								break;
							case we_voting_voting::ERROR_REVOTE :
								$mess = g_l('modules_voting', '[log_error_revote]');
								break;
							case we_voting_voting::ERROR_BLACKIP :
								$mess = g_l('modules_voting', '[log_error_blackip]');
								break;
							default:
								$mess = g_l('modules_voting', '[log_error]');
						}
					} else {
						$mess = g_l('modules_voting', '[log_success]');
					}

					$myline = $enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['votingsession'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['voting'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim(date(g_l('weEditorInfo', '[date_format]'), $data['time']))) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['ip'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['agent'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($cookie)) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($fallback)) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($mess)) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['answer'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['answertext'])) . $enclose . $delimiter .
						$enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($data['successor'])) . $enclose . $delimiter;

					if($data['additionalfields'] != ''){
						$addData = we_unserialize($data['additionalfields']);

						if(is_array($addData) && !empty($addData)){
							foreach($addData as $key => $values){
								$myline .= $enclose . iconv(DEFAULT_CHARSET, $CSV_Charset . '//TRANSLIT', trim($values)) . $enclose . $delimiter;
							}
						} else {
							$myline .= $enclose . '-' . $enclose . $delimiter;
						}
					} else {
						$myline .= $enclose . '-' . $enclose . $delimiter;
					}
					$content[] = $myline;
				}

				we_base_file::save($_SERVER['DOCUMENT_ROOT'] . $fname, implode($lineend, $content));
				$_REQUEST['lnk'] = $fname;
				break;

			default:
		}

		$_SESSION['weS']['voting_session'] = $this->voting;
	}

	function processVariables(){

		if(isset($_SESSION['weS']['voting_session'])){
			$this->voting = $_SESSION['weS']['voting_session'];
		}

		if(is_array($this->voting->persistent_slots)){
			foreach($this->voting->persistent_slots as $key => $type){
				if(($v = we_base_request::_($type, $key, '__UNSET__')) !== '__UNSET__'){
					$this->voting->{$key} = $v;
				}
			}
		}

		if(isset($_REQUEST["page"])){
			$this->page = $_REQUEST["page"];
		}

		$qaset = $qaADDset = [];
		$qname = we_base_request::_(we_base_request::STRING, 'question_name');
		$vcount = we_base_request::_(we_base_request::INT, 'variant_count');
		$aname = we_base_request::_(we_base_request::STRING, 'answers_name');
		$icount = we_base_request::_(we_base_request::INT, 'item_count');
		if($qname && $vcount && $aname && $icount){
			for($i = 0; $i < $vcount; $i++){
				if(($quest = we_base_request::_(we_base_request::STRING, $qname . '_variant' . $i . '_' . $qname . '_item0')) !== false){
					$set = ['question' => addslashes($quest),
						'answers' => [],
					];

					$an = $aname . '_variant' . $i . '_' . $aname . '_item';
					$anImage = $an . 'ImageID';
					$anMedia = $an . 'MediaID';
					$anSuccessor = $an . 'SuccessorID';
					$addset = [];
					for($j = 0; $j < $icount; $j++){
						if(($tmp = we_base_request::_(we_base_request::STRING, $an . $j)) !== false){
							$set['answers'][] = addslashes($tmp);
						}
						if(($tmp = we_base_request::_(we_base_request::STRING, $anImage . $j)) !== false){
							$addset['imageID'][] = ($tmp != 'Array' ? addslashes($tmp) : 0);
						}
						if(($tmp = we_base_request::_(we_base_request::STRING, $anMedia . $j)) !== false){
							$addset['mediaID'][] = ($tmp != 'Array' ? addslashes($tmp) : 0);
						}
						if(($tmp = we_base_request::_(we_base_request::STRING, $anSuccessor . $j)) !== false){
							$addset['successorID'][] = ($tmp != 'Array' ? addslashes($tmp) : 0);
						}
					}
					$qaset[] = $set;
					$qaADDset[] = $addset;
				}
			}
		}

		$this->voting->QASet = $qaset;
		$this->voting->QASetAdditions = $qaADDset;

		/* FIXME: this doesn't work! multi_edit assumes the value is the same as the displayed item, so you get an image-tag & textual user here which is really not what we need. To fix this, multi_edit.js must distinguish between "label" & value!
		 * if(($on = we_base_request::_(we_base_request::STRING, 'owners_name')) && ($oc = we_base_request::_(we_base_request::INT, 'owners_count'))){
		  $this->voting->Owners = [];
		  $an = $on . '_variant0_' . $on . '_item';
		  for($i = 0; $i < $oc; $i++){
		  if(($tmp = we_base_request::_(we_base_request::STRING, $an . $i))){
		  $up = str_replace(array(stripslashes($this->item_pattern), stripslashes($this->group_pattern)), '', $tmp);
		  $this->voting->Owners[] = path_to_id($up, USER_TABLE);
		  }
		  }
		  $this->voting->Owners = array_unique($this->voting->Owners);
		  }
		 */

		$ipset = [];
		if(($in = we_base_request::_(we_base_request::STRING, 'iptable_name')) && ($ic = we_base_request::_(we_base_request::INT, 'iptable_count'))){
			$in = $in . '_variant0_' . $in . '_item';
			for($i = 0; $i < $ic; $i++){
				if(($tmp = we_base_request::_(we_base_request::STRING, $in . $i)) !== false){
					$ipset[] = addslashes($tmp);
				}
			}
			$this->voting->BlackList = $ipset;
		}


		if(($day = we_base_request::_(we_base_request::INT, 'PublishDate_day'))){
			$this->voting->PublishDate = mktime(we_base_request::_(we_base_request::INT, 'PublishDate_hour'), we_base_request::_(we_base_request::INT, 'PublishDate_minute'), 0, we_base_request::_(we_base_request::INT, 'PublishDate_month'), $day, we_base_request::_(we_base_request::INT, 'PublishDate_year'));
		}

		if(($day = we_base_request::_(we_base_request::INT, 'Valid_day'))){
			$this->voting->Valid = mktime(we_base_request::_(we_base_request::INT, 'Valid_hour'), we_base_request::_(we_base_request::INT, 'Valid_minute'), 0, we_base_request::_(we_base_request::INT, 'Valid_month'), $day, we_base_request::_(we_base_request::INT, 'Valid_year'));
		}

		if(we_base_request::_(we_base_request::FLOAT, 'scores_0') !== false && ($ic = we_base_request::_(we_base_request::INT, 'item_count')) && we_base_request::_(we_base_request::BOOL, 'scores_changed')){
			$this->voting->Scores = [];
			for($j = 0; $j < $ic; $j++){
				if(($tmp = we_base_request::_(we_base_request::FLOAT, 'scores_' . $j))){
					$this->voting->Scores[] = $tmp;
				}
			}
		}
	}

	public function getHomeScreen(){
		$hiddens = ["cmd" => "home",
			'pnt' => 'edbody'
		];
		$content = we_html_button::create_button('new_voting', "javascript:top.we_cmd('new_voting');", '', 0, 0, "", "", !we_base_permission::hasPerm("NEW_VOTING")) .
			'<br/>' .
			we_html_button::create_button('new_voting_group', "javascript:top.we_cmd('new_voting_group');", '', 0, 0, "", "", !we_base_permission::hasPerm("NEW_VOTING"));

		return parent::getActualHomeScreen("voting", "voting.gif", $content, we_html_element::htmlForm(['name' => 'we_form'], $this->getCommonHiddens($hiddens) . we_html_element::htmlHidden("home", 0)));
	}

}
