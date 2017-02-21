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
class we_dialog_hyperlink extends we_dialog_base{
	function __construct($noInternals = true){
		parent::__construct($noInternals);

		$this->changeableArgs = ['type', 'extHref', 'fileID', 'href', 'fileHref', 'fileCT', 'objID', 'objHref', 'mailHref', 'target', 'class',
			'param', 'anchor', 'lang', 'hreflang', 'title', 'accesskey', 'tabindex', 'rel', 'rev'
		];
		$this->dialogTitle = g_l('wysiwyg', '[edit_hyperlink]');
	}

	public static function getDialog($noInternals = true){
		$inst = new we_dialog_hyperlink($noInternals);
		$inst->initByHttp();

		return $inst->getHTML();
	}

	function getDialogButtons(){
		if($this->pageNr == $this->numPages && $this->JsOnly == false){
			$back = $this->getBackBut();
			$ok = we_html_button::create_button(we_html_button::OK, "javascript:weCheckAcFields()");
			$okBut = $back ? $back . $ok : $ok;
		} else if($this->pageNr < $this->numPages){
			$back = $this->getBackBut();
			$next = $this->getNextBut();
			$okBut = $back && $next ?
				($back . $next) :
				($back ?: $next );
		} else {
			$back = $this->getBackBut();
			$ok = $this->getOkBut();
			$okBut = $back && $ok ? $back . $ok : ($back ?: $ok);
		}

		return we_html_button::position_yes_no_cancel($okBut, '', we_html_button::create_button(we_html_button::CANCEL, 'javascript:top.close();'));
	}

	function initByHref($href, $target = '', $class = '', $param = '', $anchor = '', $lang = '', $hreflang = '', $title = '', $accesskey = '', $tabindex = '', $rel = '', $rev = ''){
		if($href){
			$this->args['href'] = $href;
			$href = explode(':', $this->args['href']);
			if(count($href) == 2){
				list($type, $ref) = $href;
				$type .= ':';
			} else {
				$ref = '';
				$type = we_base_link::TYPE_EXT;
			}

			// Object Links and internal links are not possible when outside webEdition
			// for exmaple in the wysiwyg (Mantis Bug #138)
			if(($this->noInternals || (isset($this->args['outsideWE']) && $this->args['outsideWE'] == 1)) &&
				( $type == we_base_link::TYPE_OBJ_PREFIX || $type == we_base_link::TYPE_INT_PREFIX )
			){
				$this->args['href'] = $type = $ref = '';
			}

			$this->args['mailsubject'] = $this->args['mailcc'] = $this->args['mailbcc'] = '';
			$this->args['param'] = str_replace('&amp;', '&', $param);
			switch($type){
				case we_base_link::TYPE_OBJ_PREFIX:
					$this->args['type'] = we_base_link::TYPE_OBJ;
					$this->args['extHref'] = '';
					$this->args['fileID'] = '';
					$this->args['fileHref'] = '';
					$this->args['fileCT'] = '';
					$this->args['mailHref'] = '';
					$this->args['objID'] = trim($ref, '/?#');
					$this->args['objHref'] = f('SELECT Path FROM ' . OBJECT_FILES_TABLE . ' WHERE ID=' . intval($this->args['objID']), '', $this->db);
					break;
				case we_base_link::TYPE_INT_PREFIX:
					$this->args['type'] = we_base_link::TYPE_INT;
					$this->args['extHref'] = '';
					$this->args['fileID'] = trim($ref, '/?#');
					$hash = getHash('SELECT Path,ContentType FROM ' . FILE_TABLE . ' WHERE ID=' . intval($this->args['fileID']), $this->db);
					$this->args['fileHref'] = empty($hash['Path']) ? '' : $hash['Path'];
					$this->args['fileCT'] = empty($hash['ContentType']) ? '' : $hash['ContentType'];
					$this->args['mailHref'] = '';
					$this->args['objID'] = '';
					$this->args['objHref'] = '';
					break;
				case we_base_link::TYPE_MAIL_PREFIX:
					$this->args['type'] = we_base_link::TYPE_MAIL;
					$match = [];
					preg_match('|^([^\?#]+).*$|', $ref, $match);
					$this->args['mailHref'] = trim($match[1], '/');
					$this->args['extHref'] = '';
					$this->args['fileID'] = '';
					$this->args['fileHref'] = '';
					$this->args['fileCT'] = '';
					$this->args['objID'] = '';
					$this->args['objHref'] = '';
					preg_match('|(subject=([^&]*)&?)?(cc=([^&]*)&?)?(bcc=([^&]*)&?)?|', $this->args['param'], $match);
					$this->args['mailsubject'] = isset($match[2]) ? urldecode($match[2]) : '';
					$this->args['mailcc'] = isset($match[4]) ? $match[4] : '';
					$this->args['mailbcc'] = isset($match[6]) ? $match[6] : '';
					break;
				default:
					$this->args['type'] = we_base_link::TYPE_EXT;
					$this->args['extHref'] = preg_replace(['|^' . WEBEDITION_DIR . 'we_cmd.php[^"\'#]+(#.*)$|',
						'|^' . WEBEDITION_DIR . '|',
						'|^([^\?#]+).*$|'
						], ['${1}',
						'',
						'${1}'
						], $this->args["href"]);
					$this->args['fileID'] = '';
					$this->args['fileHref'] = '';
					$this->args['mailHref'] = '';
					$this->args['fileCT'] = '';
					$this->args['objID'] = '';
					$this->args['objHref'] = '';
			}
		}
		$this->args['target'] = $target;
		$this->args['class'] = $class;
		$this->args['anchor'] = $anchor;
		$this->args['lang'] = $lang;
		$this->args['hreflang'] = $hreflang;
		$this->args['title'] = $title;
		$this->args['accesskey'] = $accesskey;
		$this->args['tabindex'] = $tabindex;
		$this->args['rel'] = $rel;
		$this->args['rev'] = $rev;
	}

	function initByFileID($fileID, $target = '', $class = '', $param = '', $anchor = '', $lang = '', $hreflang = '', $title = '', $accesskey = '', $tabindex = '', $rel = '', $rev = ''){
		if($fileID){
			$this->args['href'] = we_base_link::TYPE_INT_PREFIX . $fileID;
			$this->args['type'] = we_base_link::TYPE_INT;
			$this->args['extHref'] = '';
			$this->args['fileID'] = $fileID;
			$hash = getHash('SELECT Path,ContentType FROM ' . FILE_TABLE . ' WHERE ID=' . intval($this->args['fileID']), $this->db);
			$this->args['fileHref'] = $hash['Path'];
			$this->args['fileCT'] = $hash['ContentType'];
			$this->args['objID'] = '';
			$this->args['mailHref'] = '';
			$this->args['objHref'] = '';
		}
		$this->args['mailsubject'] = $this->args['mailcc'] = $this->args['mailbcc'] = '';
		$this->args['target'] = $target;
		$this->args['class'] = $class;
		$this->args['param'] = str_replace('&amp;', '&', $param);
		$this->args['anchor'] = $anchor;
		$this->args['lang'] = $lang;
		$this->args['hreflang'] = $hreflang;
		$this->args['title'] = $title;
		$this->args['accesskey'] = $accesskey;
		$this->args['tabindex'] = $tabindex;
		$this->args['rel'] = $rel;
		$this->args['rev'] = $rev;
	}

	function initByObjectID($objID, $target = '', $class = '', $param = '', $anchor = '', $lang = '', $hreflang = '', $title = '', $accesskey = '', $tabindex = '', $rel = '', $rev = ''){
		if($objID){
			$this->args['href'] = we_base_link::TYPE_OBJ_PREFIX . $objID;
			$this->args['type'] = we_base_link::TYPE_OBJ;
			$this->args['extHref'] = '';
			$this->args['fileID'] = '';
			$this->args['fileHref'] = '';
			$this->args['fileCT'] = '';
			$this->args['mailHref'] = '';
			$this->args['objID'] = $objID;
			$this->args['objHref'] = f('SELECT Path FROM ' . OBJECT_FILES_TABLE . ' WHERE ID=' . intval($this->args['objID']), '', $this->db);
		}
		$this->args['mailsubject'] = $this->args['mailcc'] = $this->args['mailbcc'] = '';
		$this->args['target'] = $target;
		$this->args['class'] = $class;
		$this->args['param'] = str_replace('&amp;', '&', $param);
		$this->args['anchor'] = $anchor;
		$this->args['lang'] = $lang;
		$this->args['hreflang'] = $hreflang;
		$this->args['title'] = $title;
		$this->args['accesskey'] = $accesskey;
		$this->args['tabindex'] = $tabindex;
		$this->args['rel'] = $rel;
		$this->args['rev'] = $rev;
	}

	function initByMailHref($mailHref, $target = '', $class = '', $param = '', $anchor = '', $lang = '', $hreflang = '', $title = '', $accesskey = '', $tabindex = '', $rel = '', $rev = ''){
		if($mailHref){
			$this->args['href'] = we_base_link::TYPE_MAIL_PREFIX . $mailHref;
			$this->args['type'] = we_base_link::TYPE_MAIL;
			$this->args['extHref'] = '';
			$this->args['fileID'] = '';
			$this->args['fileHref'] = '';
			$this->args['fileCT'] = '';
			$this->args['mailHref'] = $mailHref;
			$this->args['objID'] = '';
			$this->args['objHref'] = '';
		}
		$this->args['target'] = $target;
		$this->args['class'] = $class;
		$this->args['param'] = str_replace('&amp;', '&', $param);
		$this->args['anchor'] = $anchor;
		$this->args['lang'] = $lang;
		$this->args['hreflang'] = $hreflang;
		$this->args['title'] = $title;
		$this->args['accesskey'] = $accesskey;
		$this->args['tabindex'] = $tabindex;
		$this->args['rel'] = $rel;
		$this->args['rev'] = $rev;
		$match = [];
		preg_match('|(subject=([^&]*)&?)?(cc=([^&]*)&?)?(bcc=([^&]*)&?)?|', $this->args['param'], $match);
		$this->args['mailsubject'] = isset($match[2]) ? urldecode($match[2]) : '';
		$this->args['mailcc'] = isset($match[4]) ? $match[4] : '';
		$this->args['mailbcc'] = isset($match[6]) ? $match[6] : '';
	}

	function glue_url($parsed){
		if(!is_array($parsed)){
			return false;
		}
		return ($parsed['scheme'] ? $parsed['scheme'] . ':' . ((strtolower($parsed['scheme']) === 'mailto') ? '' : '//') : '') .
			($parsed['user'] ? $parsed['user'] . ($parsed['pass'] ? ':' . $parsed['pass'] : '') . '@' : '') .
			($parsed['host'] ?: '') .
			($parsed['port'] ? ':' . $parsed['port'] : '') .
			($parsed['path'] ?: '') .
			($parsed['query'] ? '?' . $parsed['query'] : '') .
			($parsed['fragment'] ? '#' . $parsed['fragment'] : '');
	}

	function initByHttp(){
		parent::initByHttp();
		$href = $this->getHttpVar(we_base_request::URL, 'href');
		$target = $this->getHttpVar(we_base_request::STRING, 'target');
		$param = $this->getHttpVar(we_base_request::STRING, 'param');
		$anchor = $this->getHttpVar(we_base_request::STRING, 'anchor');
		$lang = $this->getHttpVar(we_base_request::STRING, 'lang');
		$hreflang = $this->getHttpVar(we_base_request::STRING, 'hreflang');
		$title = $this->getHttpVar(we_base_request::STRING, 'title');
		$accesskey = $this->getHttpVar(we_base_request::STRING, 'accesskey');
		$tabindex = $this->getHttpVar(we_base_request::INT, 'tabindex');
		$rel = $this->getHttpVar(we_base_request::STRING, 'rel');
		$rev = $this->getHttpVar(we_base_request::STRING, 'rev');

		if($href && (strpos($href, "?") !== false || strpos($href, "#") !== false)){
			$urlparts = parse_url($href);

			if((!$param) && !empty($urlparts["query"])){
				$param = $urlparts["query"];
			}
			if((!$anchor) && !empty($urlparts["fragment"])){
				$anchor = $urlparts["fragment"];
			}
		}

		$class = $this->getHttpVar(we_base_request::STRING, 'class');
		$type = $this->getHttpVar(we_base_request::STRING, 'type');
		if($href){
			$this->initByHref($href, $target, $class, $param, $anchor, $lang, $hreflang, $title, $accesskey, $tabindex, $rel, $rev);
		} else if($type){
			$fileID = $this->getHttpVar(we_base_request::INT, "fileID", 0);
			$objID = $this->getHttpVar(we_base_request::INT, "objID", 0);
			switch($type){
				case we_base_link::TYPE_EXT:
					$extHref = $this->getHttpVar(we_base_request::URL, 'extHref', '#');
					$this->initByHref($extHref, $target, $class, $param, $anchor, $lang, $hreflang, $title, $accesskey, $tabindex, $rel, $rev);
					break;
				case we_base_link::TYPE_INT:
					$this->initByFileID($fileID, $target, $class, $param, $anchor, $lang, $hreflang, $title, $accesskey, $tabindex, $rel, $rev);
					break;
				case we_base_link::TYPE_OBJ:
					$this->initByObjectID($objID, $target, $class, $param, $anchor, $lang, $hreflang, $title, $accesskey, $tabindex, $rel, $rev);
					break;
				case we_base_link::TYPE_MAIL:
					$mailhref = $this->getHttpVar(we_base_request::STRING, 'mailHref'); //FIXME mail?
					$this->initByMailHref($mailhref, $target, $class, $param, $anchor, $lang, $hreflang, $title, $accesskey, $tabindex, $rel, $rev);
					break;
			}
		} else {
			$this->defaultInit();
		}
	}

	function defaultInit(){
		$this->args = array_merge($this->args, ['href' => we_base_link::TYPE_INT_PREFIX,
			'type' => we_base_link::TYPE_INT,
			'extHref' => '',
			'fileID' => '',
			'fileHref' => '',
			'fileCT' => '',
			'objID' => '',
			'objHref' => '',
			'mailHref' => '',
			'target' => '',
			'class' => '',
			'param' => '',
			'anchor' => '',
			'lang' => '',
			'hreflang' => '',
			'title' => '',
			'accesskey' => '',
			'tabindex' => '',
			'rel' => '',
			'rev' => '',
			'mailsubject' => '',
			'mailcc' => '',
			'mailbcc' => '',
		]);
	}

	function getDialogContentHTML(){
		// Initialize we_button class
		$weSuggest = &weSuggest::getInstance();
		$noInternals = false;

		$extHref = str_replace('%20', ' ', (!$this->args['extHref'] ? '' : ((substr($this->args['extHref'], 0, 1) === '#') ? '' : $this->args['extHref'])));
		if($this->noInternals || (isset($this->args['outsideWE']) && $this->args['outsideWE'] == 1)){
			$noInternals = true;
			$select_type = '<option value="' . we_base_link::TYPE_EXT . '"' . (($this->args["type"] !== we_base_link::TYPE_MAIL) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', '[external_link]') . '</option>
<option value="' . we_base_link::TYPE_MAIL . '"' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? ' selected="selected"' : '') . '>' . g_l('wysiwyg', '[emaillink]') . '</option>';

			$external_link = we_html_tools::htmlTextInput("we_dialog_args[extHref]", 30, $extHref, '', 'placeholder="' . we_base_link::EMPTY_EXT . '"', 'url', 300);
			// E-MAIL LINK
			$email_link = we_html_tools::htmlTextInput("we_dialog_args[mailHref]", 30, $this->args["mailHref"], "", '', "email", 300);
		} else {
			$select_type = '<option value="' . we_base_link::TYPE_EXT . '"' . (($this->args["type"] == we_base_link::TYPE_EXT) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', '[external_link]') . '</option>
<option value="' . we_base_link::TYPE_INT . '"' . (($this->args["type"] == we_base_link::TYPE_INT) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', '[internal_link]') . '</option>
<option value="' . we_base_link::TYPE_MAIL . '"' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? ' selected="selected"' : '') . '>' . g_l('wysiwyg', '[emaillink]') . '</option>' .
				((defined('OBJECT_TABLE') && ($_SESSION['weS']['we_mode'] == we_base_constants::MODE_NORMAL || we_base_permission::hasPerm("CAN_SEE_OBJECTFILES"))) ?
				'<option value="' . we_base_link::TYPE_OBJ . '"' . (($this->args["type"] == we_base_link::TYPE_OBJ) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', '[objectFile]') . '</option>' :
				''
				);

			// EXTERNAL LINK
			$external_select_button = we_base_permission::hasPerm("CAN_SELECT_EXTERNAL_FILES") ? we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('browse_server', 'we_dialog_args[extHref]', '', document.we_form.elements['we_dialog_args[extHref]'].value, '')") : '';
			$openbutton = we_html_button::create_button(we_html_button::EDIT, "javascript:top.openExtSource('extHref');", '', 0, 0, '', '', ($extHref && $extHref !== we_base_link::EMPTY_EXT ? false : true), false, '_ext', false, g_l('wysiwyg', '[openNewWindow]'));

			$external_link = "<div style='margin-top:1px'>" . we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("we_dialog_args[extHref]", 30, $extHref, '', 'onfocus="extHref_doOnFocus(this)" onblur="checkMakeEmptyHrefExt();" onchange="extHref_doOnchange(this)"', "url", 300), "", "left", "defaultfont", $external_select_button . $openbutton, '', '', '', '', 0) . '</div>';

			// INTERNAL LINK
			$weSuggest->setAcId("Path");
			$weSuggest->setContentType([we_base_ContentTypes::FOLDER, we_base_ContentTypes::WEDOCUMENT, we_base_ContentTypes::IMAGE, we_base_ContentTypes::JS, we_base_ContentTypes::CSS,
				we_base_ContentTypes::HTML, we_base_ContentTypes::APPLICATION]);
			$weSuggest->setInput("we_dialog_args[fileHref]", $this->args["fileHref"]);
			$weSuggest->setMaxResults(20);
			$weSuggest->setRequired(true);
			$weSuggest->setResult("we_dialog_args[fileID]", ($this->args["fileID"] == 0 ? "" : $this->args["fileID"]));
			$weSuggest->setSelector(weSuggest::DocSelector);
			$weSuggest->setWidth(300);
			$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_document',document.we_form.elements['we_dialog_args[fileID]'].value, '" . FILE_TABLE . "','we_dialog_args[fileID]','we_dialog_args[fileHref]','selector_callback,btn_edit_int','',0, '', " . (we_base_permission::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . ");"), 10);
			$weSuggest->setOpenButton(we_html_button::create_button(we_html_button::EDIT, "javascript:openToEdit(document.we_form.elements['yuiAcResultPath'].value, document.we_form.elements['yuiAcResultCT'].value);", '', 0, 0, '', '', ($this->args["fileID"] ? false : true), false, '_int'));
			$internal_link = $weSuggest->getHTML() . we_html_element::htmlHidden('yuiAcResultCT', ($this->args['fileCT'] ? $this->args['fileCT'] : we_base_ContentTypes::WEDOCUMENT));

			// E-MAIL LINK
			$email_link = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("we_dialog_args[mailHref]", 30, $this->args["mailHref"], "", '', "email", 300), "", "left", "defaultfont", "", "", "", "", "", 0);

			// OBJECT LINK
			if(defined('OBJECT_TABLE') && ($_SESSION['weS']['we_mode'] == we_base_constants::MODE_NORMAL || we_base_permission::hasPerm("CAN_SEE_OBJECTFILES"))){
				$weSuggest->setAcId("Obj");
				$weSuggest->setContentType("folder," . we_base_ContentTypes::OBJECT_FILE);
				$weSuggest->setInput("we_dialog_args[objHref]", $this->args["objHref"]);
				$weSuggest->setMaxResults(20);
				$weSuggest->setRequired(true);
				$weSuggest->setResult('we_dialog_args[objID]', ($this->args['objID'] == 0 ? '' : $this->args['objID']));
				$weSuggest->setSelector(weSuggest::DocSelector);
				$weSuggest->setTable(OBJECT_FILES_TABLE);
				$weSuggest->setWidth(300);
				$weSuggest->setSelectButton(we_html_button::create_button(we_html_button::SELECT, "javascript:we_cmd('we_selector_document', document.we_form.elements['we_dialog_args[objID]'].value, '" . OBJECT_FILES_TABLE . "', 'we_dialog_args[objID]','we_dialog_args[objHref]', 'selector_callback,btn_edit_obj', '', '', 'objectFile'," . (we_base_permission::hasPerm("CAN_SELECT_OTHER_USERS_OBJECTS") ? 0 : 1) . ");", '', 0, 0, "", "", !we_base_permission::hasPerm("CAN_SEE_OBJECTFILES")), 10);
				$weSuggest->setOpenButton(we_html_button::create_button(we_html_button::EDIT, "javascript:openToEdit(document.we_form.elements['yuiAcResultObj'].value,'" . we_base_ContentTypes::OBJECT_FILE . "','" . OBJECT_FILES_TABLE . "');", '', 0, 0, '', '', ($this->args["objID"] ? false : true), false, '_obj'));
				$object_link = $weSuggest->getHTML();
				/*
				  $object_link = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("we_dialog_args[objHref]",30,$this->args["objHref"],"",' readonly="readonly"',"text",300, 0, "", !we_base_permission::hasPerm("CAN_SEE_OBJECTFILES")) .
				  '<input type="hidden" name="we_dialog_args[objID]" value="'.$this->args["objID"].'" />', "", "left", "defaultfont", $object_select_button, "", "","", "", 0);
				 */
			}
		}

		$anchorSel = '<div id="anchorlistcontainer"></div>';
		$anchorInput = we_html_tools::htmlTextInput("we_dialog_args[anchor]", 30, $this->args["anchor"], "", 'onkeyup="checkMakeEmptyHrefExt()" onblur="checkMakeEmptyHrefExt(); checkAnchor(this)"', "text", 300);

		$anchor = we_html_tools::htmlFormElementTable($anchorInput, "", "left", "defaultfont", $anchorSel, '', "", "", "", 0);

		$param = we_html_tools::htmlTextInput("we_dialog_args[param]", 30, htmlspecialchars(urldecode(utf8_decode($this->args["param"]))), '', 'onkeyup="checkMakeEmptyHrefExt()" onblur="checkMakeEmptyHrefExt();"', 'text', 300);

		// CSS STYLE
		$classSelect = $this->getClassSelect();

		// lang
		$lang = $this->getLangField("lang", g_l('wysiwyg', '[link_lang]'), 145);
		$hreflang = $this->getLangField("hreflang", g_l('wysiwyg', '[href_lang]'), 145);

		$title = we_html_tools::htmlTextInput("we_dialog_args[title]", 30, $this->args["title"], "", "", "text", 300);


		$accesskey = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("we_dialog_args[accesskey]", 30, $this->args["accesskey"], "", "", "text", 145), "accesskey");
		$tabindex = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("we_dialog_args[tabindex]", 30, $this->args["tabindex"], "", ' onkeypress="return WE().util.IsDigit(event);"', "text", 145), "tabindex");


		$rev = we_html_tools::htmlFormElementTable($this->getRevRelSelect("rev"), "rev");
		$rel = we_html_tools::htmlFormElementTable($this->getRevRelSelect("rel"), "rel");

		$show_accessible_class = (we_base_permission::hasPerm("CAN_SEE_ACCESSIBLE_PARAMETERS") ? '' : ' class="weHide"');

		return [['html' =>
			// Create table output
			'<div style="position:relative; top:15px"><table class="default" style="height:65px">
	<tr>
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;height:20px">' . g_l('weClass', '[linkType]') . '</td>
		<td style="vertical-align:top"><select name="we_dialog_args[type]" class="defaultfont" id="weDialogType" style="margin-bottom:5px;width:300px;" onchange="LinkDialog.changeTypeSelect(this);">' . $select_type . '</select></td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_EXT . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_EXT) || ($noInternals && $this->args["type"] !== we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;">' . g_l('linklistEdit', '[external_link]') . '</td><td style="vertical-align:top" >' . $external_link . '</td>
	</tr>' .
			(isset($internal_link) ? '
	<tr class="we_change ' . we_base_link::TYPE_INT . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_INT) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px"> ' . g_l('weClass', '[document]') . '</td>
		<td style="vertical-align:top"> ' . $internal_link . we_html_element::jsElement('document.we_form.onsubmit = function() {return false;}') . '</td>
	</tr>' : '') . '
	<tr class="we_change ' . we_base_link::TYPE_MAIL . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px">' . g_l('wysiwyg', '[emaillink]') . '</td>
		<td style="vertical-align:top">' . $email_link . '</td>
	</tr>' .
			(defined('OBJECT_TABLE') && isset($object_link) ? '
	<tr class="we_change ' . we_base_link::TYPE_OBJ . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_OBJ) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;height:0px;">' . g_l('contentTypes', '[objectFile]') . '</td>
		<td style="vertical-align:top">' . $object_link . '</td>
	</tr>' : '') . '
</table></div>'
			],
				['html' => '<table class="default">
	<tr class="we_change ' . we_base_link::TYPE_INT . ' ' . we_base_link::TYPE_EXT . ' ' . we_base_link::TYPE_OBJ . '" style="display:' . (($this->args["type"] != we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px">' . g_l('wysiwyg', '[anchor]') . '</td>
		<td>' . $anchor . '</td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_MAIL . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px">' . g_l('modules_messaging', '[subject]') . '</td>
		<td>' . we_html_tools::htmlTextInput('we_dialog_args[mail_subject]', 30, $this->args["mailsubject"], "", "", "text", 300) . '</td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_INT . ' ' . we_base_link::TYPE_EXT . ' ' . we_base_link::TYPE_OBJ . '" style="display:' . (($this->args["type"] != we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;padding-top:10px;">' . g_l('linklistEdit', '[link_params]') . '</td>
		<td>' . $param . '</td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_MAIL . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px">CC</td>
		<td>' . we_html_tools::htmlTextInput("we_dialog_args[mail_cc]", 30, $this->args["mailcc"], "", "", "text", 300) . '</td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_INT . ' ' . we_base_link::TYPE_EXT . ' ' . we_base_link::TYPE_OBJ . '" style="display:' . (($this->args["type"] != we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;padding-top:10px;">' . g_l('linklistEdit', '[link_target]') . '</td>
		<td>' . we_html_tools::targetBox('we_dialog_args[target]', 29, 300, 'we_dialog_args[target]', $this->args['target'], '', 10, 100) . '</td>
	</tr>
	<tr class="we_change ' . we_base_link::TYPE_MAIL . '" style="display:' . (($this->args["type"] == we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px">BCC</td>
		<td>' . we_html_tools::htmlTextInput("we_dialog_args[mail_bcc]", 30, $this->args['mailbcc'], '', '', 'text', 300) . '</td>
	</tr>
	<tr>
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;padding-top:10px;">' . g_l('wysiwyg', '[css_style]') . '</td>
		<td>' . $classSelect . '</td>
	</tr>
</table>'],
				['html' => '<table class="default">
	<tr' . $show_accessible_class . '>
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;">' . g_l('wysiwyg', '[language]') . '</td>
		<td><table class="default"><tr><td style="padding-left:2px;">' . $lang . '</td><td>' . $hreflang . '</td></tr></table></td>
	</tr>
	<tr>
		<td class="defaultfont lowContrast" style="vertical-align:top;width:100px;padding-top:10px;">' . g_l('wysiwyg', '[title]') . '</td>
		<td>' . $title . '</td>
	</tr>
	<tr' . $show_accessible_class . '>
		<td class="defaultfont lowContrast" style="vertical-align:top;padding-top:10px;">' . g_l('wysiwyg', '[keyboard]') . '</td>
		<td><table class="default"><tr><td style="padding-left:2px;">' . $accesskey . '</td><td>' . $tabindex . '</td></tr></table></td>
	</tr>
	<tr' . $show_accessible_class . '>
		<td class="defaultfont lowContrast" style="vertical-align:top;padding:10px 0px;">' . g_l('wysiwyg', '[relation]') . '</td>
		<td><table class="default"><tr><td style="padding-left:2px;">' . $rel . '</td><td>' . $rev . '</td></tr></table></td>
	</tr>
</table>'
			]
		];
	}

	function getRevRelSelect($type){
		return '<input type="text" class="wetextinput" name="we_dialog_args[' . $type . ']" value="' . oldHtmlspecialchars($this->args["$type"]) . '" style="width:70px;" /><select class="defaultfont" name="' . $type . '_sel" style="width:75px;" onchange="this.form.elements[\'we_dialog_args[' . $type . ']\'].value=this.options[this.selectedIndex].text;this.selectedIndex=0;">
	<option></option>
	<option>contents</option>
	<option>chapter</option>
	<option>section</option>
	<option>subsection</option>
	<option>index</option>
	<option>glossary</option>
	<option>appendix</option>
	<option>copyright</option>
	<option>next</option>
	<option>prev</option>
	<option>start</option>
	<option>help</option>
	<option>bookmark</option>
	<option>alternate</option>
	<option>nofollow</option>
</select>';
	}

	protected function getJs(){
		return parent::getJs() .
			we_html_element::jsScript(WE_JS_TINYMCE_DIR . 'plugins/welink/js/welink.js') .
			we_html_element::jsScript(JS_DIR . 'dialogs/we_dialog_hyperlink.js', '', [
				'id' => 'loadVarDialog_Hyperlink',
				'data-vars' => setDynamicVar($this->getJSDynamic())
		]);
	}

	function getJSDynamic(){
		return ['editname' => (isset($this->args["editname"]) ? $this->args["editname"] : ''),
			'classNames' => (!empty($this->args["cssClasses"]) ? implode(',', $this->args['cssClasses']) : 'getFromTiny')
		];
	}

	function cmdFunction(array $args){
		if((!isset($args['href'])) || $args['href'] == we_base_link::EMPTY_EXT){
			$args['href'] = '';
		}
		$param = trim($args['param'], '?& ');
		$anchor = trim($args['anchor'], '# ');
		if(!empty($param)){
			$tmp = [];
			parse_str($param, $tmp);
			$param = '?' . http_build_query($tmp, null, '&', PHP_QUERY_RFC3986);
		}
		// TODO: $args['href'] comes from weHyperlinkDialog with params and anchor: strip these elements there, not here!
		$href = (strpos($args['href'], '?') !== false ? substr($args['href'], 0, strpos($args['href'], '?')) :
			(strpos($args['href'], '#') === false ? $args['href'] : substr($args['href'], 0, strpos($args['href'], '#')))) . $param . ($anchor ? '#' . $anchor : '');

		if(strpos($href, we_base_link::TYPE_MAIL_PREFIX) === 0){
			$query = [];
			if(!empty($args['mail_subject'])){
				$query['subject'] = $args['mail_subject'];
			}
			if(!empty($args['mail_cc'])){
				$query['cc'] = $args['mail_cc'];
			}
			if(!empty($args['mail_bcc'])){
				$query['bcc'] = $args['mail_bcc'];
			}

			$href = $args['href'] . (empty($query) ? '' : '?' . http_build_query($query, null, '&', PHP_QUERY_RFC3986) );

			$tmpClass = $args['class'];
			foreach($args as &$val){
				$val = '';
			}
			$args['class'] = $tmpClass;
		}

		$attribs = $args;
		$attribs['href'] = $href;
		$payload = ['attributes' => $attribs];

		return we_html_tools::getHtmlTop('', '', '',
			we_html_element::jsScript(JS_DIR . 'dialogs/we_dialog_cmdFrame.js', "we_cmd('link_writeback')", [
					'id' => 'loadVarDialog_cmdFrame',
					'data-payload' => setDynamicVar($payload)
				]), we_html_element::htmlBody());
	}

}