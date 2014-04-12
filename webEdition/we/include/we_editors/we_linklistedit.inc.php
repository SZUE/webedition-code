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
we_html_tools::protect();

//todo: make int/ext/mail/obj consts of some class
function getLangField($name, $value, $title, $width){
	//FIXME: these values should be obtained from global settings
	$input = we_html_tools::htmlTextInput($name, 15, $value, '', '', 'text', $width - 50);
	//FIXME: remove this fixed list by global lang settings
	$select = '<select style="width:50px;" class="defaultfont" name="' . $name . '_select" size="1" onchange="this.form.elements[\'' . $name . '\'].value=this.options[this.selectedIndex].value;this.selectedIndex=-1;">
						<option value=""></option>
						<option value="en">en</option>
						<option value="de">de</option>
						<option value="es">es</option>
						<option value="fi">fi</option>
						<option value="ru">ru</option>
						<option value="fr">fr</option>
						<option value="nl">nl</option>
						<option value="pl">pl</option>
					</select>';
	return we_html_tools::htmlFormElementTable($input, $title, 'left', 'small', $select);
}

function getRevRelSelect($type, $value){
	$input = we_html_tools::htmlTextInput($type, 15, $value, '', '', 'text', 70);
	$select = '<select name="' . $type . '_sel" class="defaultfont" size="1" style="width:70px;" onchange="this.form.elements[\'' . $type . '\'].value=this.options[this.selectedIndex].text;this.selectedIndex=0;">
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
	return we_html_tools::htmlFormElementTable($input, $type, "left", "small", $select);
}

// init document
$we_dt = $_SESSION['weS']['we_data'][$we_transaction];
include(WE_INCLUDES_PATH . 'we_editors/we_init_doc.inc.php');

if(isset($we_doc->elements['Charset']['dat'])){ //	send charset which might be determined in template
	we_html_tools::headerCtCharset('text/html', $we_doc->elements['Charset']['dat']);
}

if(weRequest('bool', 'ok')){
	$alt = weRequest('string', 'alt', '');
	$img_title = weRequest('string', 'img_title', '');
	$text = weRequest('string', 'text', '');
	$attribs = weRequest('raw', 'attribs', '');
	$href = weRequest('url', 'href', '');
	$anchor = trim(weRequest('string', 'anchor', ''));
	$tabindex = weRequest('int', 'tabindex', '');
	$accesskey = weRequest('string', 'accesskey', '');
	$lang = weRequest('string', 'lang', '');
	$rel = weRequest('string', 'rel', '');
	$rev = weRequest('string', 'rev', '');
	$hreflang = weRequest('string', 'hreflang', '');
	$params = $_REQUEST['params'];
	$title = weRequest('string', 'title', '');
	$type = weRequest('string', 'type');

	//	accept anchor with or without '#', when saving the link
	$anchor = (!$anchor || $anchor{0} == '#' ? $anchor : '#' . $anchor);

	if(strlen($params) > 0){ //	accept parameters with or without '?', when saving the link
		//	when type=object we need a '&'
		switch(weRequest('string', 'type')){
			case we_base_link::TYPE_OBJ:
				if(substr($params, 0, 1) != '&'){
					$params = (substr($params, 0, 1) == '?' ? '&' . substr($params, 1) : '&' . $params);
				}
				break;
			default:
				if(substr($params, 0, 1) != '?'){
					$params = (substr($params, 0, 1) == '&' ? '?' . substr($params, 1) : '?' . $params);
				}
		}
	}
	$ln = array(
		'id' => weRequest('int', 'id', 0),
		'obj_id' => defined('OBJECT_TABLE') ? weRequest('int', 'obj_id', 0) : 0,
		'anchor' => $anchor,
		'accesskey' => $accesskey,
		'tabindex' => $tabindex,
		'lang' => $lang,
		'rel' => $rel,
		'rev' => $rev,
		'hreflang' => $hreflang,
		'params' => $params,
		'title' => $title,
		'bcc' => weRequest('email', 'bcc', ''),
		'cc' => weRequest('email', 'cc', ''),
		'subject' => weRequest('string', 'subject', ''),
		'href' => ($type == we_base_link::TYPE_MAIL ? we_base_link::TYPE_MAIL_PREFIX . weRequest('email', 'emaillink', '') : weRequest('url', 'href', '')),
		'attribs' => $attribs,
		'target' => weRequest('string', 'target', ''),
		'jswin' => weRequest('bool', 'jswin'),
		'jscenter' => weRequest('bool', 'jscenter'),
		'jsposx' => weRequest('unit', 'jsposx'),
		'jsposy' => weRequest('unit', 'jsposy'),
		'jswidth' => weRequest('unit', 'jswidth'),
		'jsheight' => weRequest('unit', 'jsheight'),
		'jsstatus' => weRequest('bool', 'jsstatus'),
		'jsscrollbars' => weRequest('bool', 'jsscrollbars'),
		'jsmenubar' => weRequest('bool', 'jsmenubar'),
		'jstoolbar' => weRequest('bool', 'jstoolbar'),
		'jsresizable' => weRequest('bool', 'jsresizable'),
		'jslocation' => weRequest('bool', 'jslocation'),
		'img_id' => weRequest('int', 'img_id', 0),
		'img_src' => weRequest('url', 'img_src', ''),
		'text' => weRequest('string', 'text'),
		'type' => ($type == we_base_link::TYPE_MAIL) ? we_base_link::TYPE_INT : $type,
		'ctype' => weRequest('string', 'ctype'),
		'width' => weRequest('unit', 'width'),
		'height' => weRequest('unit', 'height'),
		'border' => weRequest('int', 'border'),
		'hspace' => weRequest('int', 'hspace'),
		'vspace' => weRequest('int', 'vspace'),
		'align' => weRequest('string', 'align'),
		'alt' => $alt,
		'img_title' => weRequest('string', 'img_title'),
	);

	if(weRequest('bool', 'linklist')){
		$linklist = $_REQUEST['linklist'];
		//  set $nr to global, because it is used everywhere;
		$nr = weRequest('int', 'nr', 0);
		$ll = new we_base_linklist($linklist);
		$ll->setID($nr, $ln['id']);
		$ll->setObjID($nr, $ln['obj_id']);
		$ll->setHref($nr, $ln['href']);
		$ll->setAnchor($nr, $ln['anchor']);
		$ll->setAccesskey($nr, $ln['accesskey']);
		$ll->setTabindex($nr, $ln['tabindex']);
		$ll->setLang($nr, $ln['lang']);
		$ll->setRel($nr, $ln['rel']);
		$ll->setRev($nr, $ln['rev']);
		$ll->setHreflang($nr, $ln['hreflang']);
		$ll->setParams($nr, $ln['params']);
		$ll->setAttribs($nr, $ln['attribs']);
		$ll->setTarget($nr, $ln['target']);
		$ll->setTitle($nr, $ln['title']);
		$ll->setBcc($nr, $ln['bcc']);
		$ll->setCc($nr, $ln['cc']);
		$ll->setSubject($nr, $ln['subject']);
		$ll->setJsWinAttrib($nr, 'jswin', $ln['jswin']);
		$ll->setJsWinAttrib($nr, 'jscenter', $ln['jscenter']);
		$ll->setJsWinAttrib($nr, 'jsposx', $ln['jsposx']);
		$ll->setJsWinAttrib($nr, 'jsposy', $ln['jsposy']);
		$ll->setJsWinAttrib($nr, 'jswidth', $ln['jswidth']);
		$ll->setJsWinAttrib($nr, 'jsheight', $ln['jsheight']);
		$ll->setJsWinAttrib($nr, 'jsstatus', $ln['jsstatus']);
		$ll->setJsWinAttrib($nr, 'jsscrollbars', $ln['jsscrollbars']);
		$ll->setJsWinAttrib($nr, 'jsmenubar', $ln['jsmenubar']);
		$ll->setJsWinAttrib($nr, 'jstoolbar', $ln['jstoolbar']);
		$ll->setJsWinAttrib($nr, 'jsresizable', $ln['jsresizable']);
		$ll->setJsWinAttrib($nr, 'jslocation', $ln['jslocation']);
		$ll->setImageID($nr, $ln['img_id']);
		$ll->setImageSrc($nr, $ln['img_src']);
		$ll->setText($nr, $ln['text']);
		$ll->setType($nr, $ln['type']);
		$ll->setCType($nr, $ln['ctype']);
		$ll->setImageAttrib($nr, 'width', $ln['width']);
		$ll->setImageAttrib($nr, 'height', $ln['height']);
		$ll->setImageAttrib($nr, 'border', $ln['border']);
		$ll->setImageAttrib($nr, 'hspace', $ln['hspace']);
		$ll->setImageAttrib($nr, 'vspace', $ln['vspace']);
		$ll->setImageAttrib($nr, 'align', $ln['align']);
		$ll->setImageAttrib($nr, 'alt', $ln['alt']);

		$linklist = $ll->getString();
	} else {
		$link = serialize($ln);
	}
} else {
	$name = $_REQUEST['we_cmd'][1];
	$nr = weRequest('int','we_cmd','',2);
	if($nr != ''){
		$ll = new we_base_linklist($we_doc->getElement($name));
		$href = $ll->getHref($nr);
		if($href && strpos($href, we_base_link::TYPE_MAIL_PREFIX) === 0){
			$emaillink = substr($href, strlen(we_base_link::TYPE_MAIL_PREFIX));
			$href = '';
			$type = we_base_link::TYPE_MAIL;
		} else {
			$type = $ll->getType($nr);
			$type = $type ? $type : we_base_link::TYPE_INT;
			$emaillink = '';
		}
		$anchor = $ll->getAnchor($nr);
		$accesskey = $ll->getAccesskey($nr);
		$lang = $ll->getLang($nr);
		$rel = $ll->getRel($nr);
		$rev = $ll->getRev($nr);
		$hreflang = $ll->getHrefLang($nr);
		$tabindex = $ll->getTabindex($nr);
		$params = $ll->getParams($nr);
		$title = $ll->getTitle($nr);
		$attribs = $ll->getAttribs($nr);
		$text = $ll->getText($nr);
		$target = $ll->getTarget($nr);
		$jswin = $ll->getJsWinAttrib($nr, 'jswin');
		$jscenter = $ll->getJsWinAttrib($nr, 'jscenter');
		$jsposx = $ll->getJsWinAttrib($nr, 'jsposx');
		$jsposy = $ll->getJsWinAttrib($nr, 'jsposy');
		$jswidth = $ll->getJsWinAttrib($nr, 'jswidth');
		$jsheight = $ll->getJsWinAttrib($nr, 'jsheight');
		$jsstatus = $ll->getJsWinAttrib($nr, 'jsstatus');
		$jsscrollbars = $ll->getJsWinAttrib($nr, 'jsscrollbars');
		$jsmenubar = $ll->getJsWinAttrib($nr, 'jsmenubar');
		$jstoolbar = $ll->getJsWinAttrib($nr, 'jstoolbar');
		$jsresizable = $ll->getJsWinAttrib($nr, 'jsresizable');
		$jslocation = $ll->getJsWinAttrib($nr, 'jslocation');

		//added for #7269
		$bcc = $ll->getBcc($nr);
		$cc = $ll->getCc($nr);
		$subject = $ll->getSubject($nr);

		$id = $ll->getID($nr);
		$obj_id = $ll->getObjID($nr);
		$href_obj = $ll->getHrefObj($nr);
		$img_id = $ll->getImageID($nr);
		$img_src = $ll->getImageSrc($nr);
		$width = $ll->getImageAttrib($nr, 'width');
		$height = $ll->getImageAttrib($nr, 'height');
		$border = $ll->getImageAttrib($nr, 'border');
		$hspace = $ll->getImageAttrib($nr, 'hspace');
		$vspace = $ll->getImageAttrib($nr, 'vspace');
		$align = $ll->getImageAttrib($nr, 'align');
		$alt = $ll->getImageAttrib($nr, 'alt');
		$img_title = $ll->getImageAttrib($nr, 'img_title');
		$href_int = $ll->getHrefInt($nr);
		$src_int = $ll->getImageSrcInt($nr);
		$ctype = $ll->getCType($nr);
	} else {
		$ln = $we_doc->getElement($name) ? unserialize($we_doc->getElement($name)) : array();
		$ln = ($ln ? $ln : array('ctype' => we_base_link::CONTENT_TEXT, 'type' => we_base_link::TYPE_INT, 'href' => we_base_link::EMPTY_EXT, 'text' => g_l('global', '[new_link]')));
		$href = isset($ln['href']) ? $ln['href'] : '';
		if($href && strpos($href, we_base_link::TYPE_MAIL_PREFIX) === 0){
			$emaillink = substr($href, strlen(we_base_link::TYPE_MAIL_PREFIX));
			$href = '';
			$type = we_base_link::TYPE_MAIL;
		} else {
			$type = isset($ln['type']) ? $ln['type'] : we_base_link::TYPE_INT;
			$emaillink = '';
		}
		$attribs = isset($ln['attribs']) ? $ln['attribs'] : '';
		$text = isset($ln['text']) ? $ln['text'] : '';
		$anchor = isset($ln['anchor']) ? $ln['anchor'] : '';
		$accesskey = isset($ln['accesskey']) ? $ln['accesskey'] : '';
		$lang = isset($ln['lang']) ? $ln['lang'] : '';
		$rel = isset($ln['rel']) ? $ln['rel'] : '';
		$rev = isset($ln['rev']) ? $ln['rev'] : '';
		$hreflang = isset($ln['hreflang']) ? $ln['hreflang'] : '';
		$tabindex = isset($ln['tabindex']) ? $ln['tabindex'] : '';
		$params = isset($ln['params']) ? $ln['params'] : '';
		$title = isset($ln['title']) ? $ln['title'] : '';
		$target = isset($ln['target']) ? $ln['target'] : '';

		//added for #7269
		$bcc = isset($ln['bcc']) ? $ln['bcc'] : '';
		$cc = isset($ln['cc']) ? $ln['cc'] : '';
		$subject = isset($ln['subject']) ? $ln['subject'] : '';

		$jswin = isset($ln['jswin']) && $ln['jswin'] ? $ln['jswin'] : '';
		$jscenter = isset($ln['jscenter']) ? $ln['jscenter'] : '';
		$jsposx = isset($ln['jsposx']) ? $ln['jsposx'] : '';
		$jsposy = isset($ln['jsposy']) ? $ln['jsposy'] : '';
		$jswidth = isset($ln['jswidth']) ? $ln['jswidth'] : '';
		$jsheight = isset($ln['jsheight']) ? $ln['jsheight'] : '';
		$jsstatus = isset($ln['jsstatus']) ? $ln['jsstatus'] : '';
		$jsscrollbars = isset($ln['jsscrollbars']) ? $ln['jsscrollbars'] : '';
		$jsmenubar = isset($ln['jsmenubar']) ? $ln['jsmenubar'] : '';
		$jstoolbar = isset($ln['jstoolbar']) ? $ln['jstoolbar'] : '';
		$jsresizable = isset($ln['jsresizable']) ? $ln['jsresizable'] : '';
		$jslocation = isset($ln['jslocation']) ? $ln['jslocation'] : '';

		$id = isset($ln['id']) ? $ln['id'] : '';
		$img_id = isset($ln['img_id']) ? $ln['img_id'] : '';
		$img_src = isset($ln['img_src']) ? $ln['img_src'] : '';
		$width = isset($ln['width']) ? $ln['width'] : '';
		$height = isset($ln['height']) ? $ln['height'] : '';
		$border = isset($ln['border']) ? $ln['border'] : '';
		$hspace = isset($ln['hspace']) ? $ln['hspace'] : '';
		$vspace = isset($ln['vspace']) ? $ln['vspace'] : '';
		$align = isset($ln['align']) ? $ln['align'] : '';
		$alt = isset($ln['alt']) ? $ln['alt'] : '';
		$img_title = isset($ln['img_title']) ? $ln['img_title'] : '';
		$href_int = (isset($id) && $id) ? f('SELECT Path FROM ' . FILE_TABLE . ' WHERE ID=' . intval($id)) : '';
		if(defined('OBJECT_TABLE')){
			$obj_id = isset($ln['obj_id']) ? $ln['obj_id'] : '';
			$href_obj = (isset($obj_id) && $obj_id) ? f('SELECT Path FROM ' . OBJECT_FILES_TABLE . ' WHERE ID=' . intval($obj_id)) : '';
		}
		$src_int = $img_id ? f('SELECT Path FROM ' . FILE_TABLE . ' WHERE ID=' . intval($img_id)) : '';
		$ctype = isset($ln['ctype']) ? $ln['ctype'] : '';
	}
}

echo we_html_tools::getHtmlTop(g_l('linklistEdit', '[edit_link]'), isset($we_doc->elements['Charset']['dat']) ? $we_doc->elements['Charset']['dat'] : '');
$yuiSuggest = & weSuggest::getInstance();
echo $yuiSuggest->getYuiCssFiles() .
 $yuiSuggest->getYuiJsFiles() .
 we_html_element::jsScript(JS_DIR . 'keyListener.js') .
 we_html_element::jsScript(JS_DIR . 'windows.js');
?>
<script type="text/javascript"><!--

	function closeOnEscape() {
		return true;

	}

	function applyOnEnter(evt) {

		_elemName = "target";
		if (typeof (evt["srcElement"]) != "undefined") { // IE
			_elemName = "srcElement";
		}

		if (!(evt[_elemName].tagName == "SELECT" ||
						(evt[_elemName].tagName == "INPUT" && (evt[_elemName].name == "href_int" || evt[_elemName].name == "href_obj" || evt[_elemName].name == "src_int"))
						)) {
			document.forms['we_form'].submit();
			return true;

		}
	}


	function changeTypeSelect(s) {
		for (var i = 0; i < s.options.length; i++) {
			var trObj = document.getElementById(s.options[i].value + "_tr");
			if (i != s.selectedIndex) {
				trObj.style.display = "none";
			} else {
				trObj.style.display = "";
			}
		}
		//added for #7269
		var emailTable = document.getElementById("emailOptions");
		if (emailTable) {
			if (s.value == "<?php echo we_base_link::TYPE_MAIL; ?>") {
				emailTable.style.display = "block";
			} else {
				emailTable.style.display = "none";
			}
		}
	}
	function changeCTypeSelect(s) {
		for (var i = 0; i < s.options.length; i++) {
			var trObj = document.getElementById("c" + s.options[i].value + "_tr");
			var imgPropsObj = document.getElementById("cimgprops_tr");
			if (i != s.selectedIndex) {
				trObj.style.display = "none";
			} else {
				trObj.style.display = "";
			}
		}
		if (s.options[s.selectedIndex].value == "<?php echo we_base_link::CONTENT_TEXT; ?>") {
			imgPropsObj.style.display = "none";
		} else {
			imgPropsObj.style.display = "";
		}
	}

	function IsDigit(e) {
		var key;

		if (e && e.charCode) {
			key = e.charCode;
		} else {
			key = event.keyCode;
		}

		return (((key >= 48) && (key <= 57)) || (key == 0) || (key == 13));
	}

	function openColorChooser(name, value) {
		var win = new jsWindow("colorDialog.php?we_dialog_args[type]=dialog&we_dialog_args[name]=" + escape(name) + "&we_dialog_args[color]=" + escape(value), "colordialog", -1, -1, 400, 380, true, false, true, false);
	}

	function IsDigitPercent(e) {
		var key;

		if (e && e.charCode) {
			key = e.charCode;
		} else {
			key = event.keyCode;
		}

		return (((key >= 48) && (key <= 57)) || (key == 37) || (key == 0) || (key == 13));
	}

	function doUnload() {
		if (jsWindow_count) {
			for (i = 0; i < jsWindow_count; i++) {
				eval("jsWindow" + i + "Object.close()");
			}
		}
	}
<?php
$_REQUEST["we_transaction"] = (preg_match('|^([a-f0-9]){32}$|i', $_REQUEST["we_transaction"]) ? $_REQUEST["we_transaction"] : 0);

if(isset($_REQUEST["ok"]) && $_REQUEST["ok"] && $_REQUEST['we_cmd'][0] == "edit_link_at_class"){
	$_SESSION['weS']['WE_LINK'] = $link;
	//FIXME: we_field XSS
	?>
		opener.setScrollTo();
		opener.we_cmd("change_link_at_class", "<?php print $_REQUEST["we_transaction"]; ?>", "<?php print $_REQUEST["we_field"]; ?>", "<?php print $_REQUEST["name"]; ?>");
		top.close();
	<?php
} else if(isset($_REQUEST["ok"]) && $_REQUEST["ok"] && $_REQUEST['we_cmd'][0] == "edit_link_at_object"){
	$_SESSION['weS']['WE_LINK'] = $link;
	?>
		opener.setScrollTo();
		opener.we_cmd("change_link_at_object", "<?php print $_REQUEST["we_transaction"]; ?>", "link_<?php print $_REQUEST["name"]; ?>");
		top.close();
	<?php
} else if(isset($_REQUEST["ok"]) && $_REQUEST["ok"] && isset($linklist) && $linklist){
	$_SESSION['weS']["WE_LINKLIST"] = $linklist;
	?>
		opener.setScrollTo();
		opener.we_cmd("change_linklist", "<?php print $_REQUEST["name"]; ?>", "");
	<?php
} else if(isset($_REQUEST["ok"]) && $_REQUEST["ok"] && isset($link) && $link){
	$_SESSION['weS']['WE_LINK'] = $link;
	?>
		opener.setScrollTo();
		opener.we_cmd("change_link", "<?php print $_REQUEST["name"]; ?>", "");
	<?php
} else {
	?>
		function we_cmd() {
			var args = "";
			var url = "<?php print WEBEDITION_DIR; ?>we_cmd.php?";

			for (var i = 0; i < arguments.length; i++) {
				url += "we_cmd[" + i + "]=" + escape(arguments[i]);
				if (i < (arguments.length - 1)) {
					url += "&";
				}
			}

			switch (arguments[0]) {
				case "openDocselector":
					new jsWindow(url, "we_fileselector", -1, -1,<?php echo we_selector_file::WINDOW_DOCSELECTOR_WIDTH . ',' . we_selector_file::WINDOW_DOCSELECTOR_HEIGHT; ?>, true, true, true, true);
					break;

				case "browse_server":
					new jsWindow(url, "browse_server", -1, -1, 840, 400, true, false, true);
					break;

				default:
					for (var i = 0; i < arguments.length; i++) {
						args += 'arguments[' + i + ']' + ((i < (arguments.length - 1)) ? ',' : '');
					}
					eval('opener.parent.we_cmd(' + args + ')');
			}
		}

		self.focus();
	<?php
}
?>
//-->
</script>

<?php print STYLESHEET; ?>

</head>

<body class="weDialogBody" style="overflow:hidden;">
	<?php
	if(!isset($_REQUEST["ok"]) || !$_REQUEST["ok"]){

		$_select_type = '<select name="type" size="1" style="margin-bottom:5px;width:300px;" onchange="changeTypeSelect(this);" class="big">
<option value="' . we_base_link::TYPE_EXT . '"' . (($type == we_base_link::TYPE_EXT) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', "[external_link]") . '</option>
<option value="' . we_base_link::TYPE_INT . '"' . (($type == we_base_link::TYPE_INT) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', "[internal_link]") . '</option>
<option value="' . we_base_link::TYPE_MAIL . '"' . (($type == we_base_link::TYPE_MAIL) ? ' selected="selected"' : '') . '>' . g_l('wysiwyg', "[emaillink]") . '</option>
' . (defined("OBJECT_TABLE") ? '
<option value="' . we_base_link::TYPE_OBJ . '"' . (($type == we_base_link::TYPE_OBJ) ? ' selected="selected"' : '') . '>' . g_l('linklistEdit', "[objectFile]") . '</option>' : '') . '
</select>';



		$wecmdenc1 = we_cmd_enc('document.we_form.href.value');
		$but = permissionhandler::hasPerm('CAN_SELECT_EXTERNAL_FILES') ? we_html_button::create_button("select", "javascript:we_cmd('browse_server', '" . $wecmdenc1 . "', '', document.we_form.href.value, '')") : "";
		$butspace = (we_base_browserDetect::isSafari() ? 8 : 10);
		$extLink = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("href", 30, $href, "", '', "text", 300), "", "left", "defaultfont", we_html_tools::getPixel($butspace, 20), $but, "", "", "", 0);
		$emailLink = we_html_tools::htmlTextInput("emaillink", 30, $emaillink, "", '', "text", 300);

		//javascript:we_cmd('openDocselector',document.forms[0].id.value,'" . FILE_TABLE . "','document.forms[\\'we_form\\'].elements[\\'id\\'].value','document.forms[\\'we_form\\'].elements[\\'href_int\\'].value','','".session_id()."',0,'',".(permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1).");")
		$wecmdenc1 = we_cmd_enc("document.forms['we_form'].elements['id'].value");
		$wecmdenc2 = we_cmd_enc("document.forms['we_form'].elements['href_int'].value");

		$but = we_html_button::create_button("select", "javascript:we_cmd('openDocselector',document.forms[0].id.value,'" . FILE_TABLE . "','" . $wecmdenc1 . "','" . $wecmdenc2 . "','','" . session_id() . "',0,''," . (permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . ");");

		$yuiSuggest->setAcId("Doc");
		$yuiSuggest->setContentType('folder,' . we_base_ContentTypes::WEDOCUMENT . ',' . we_base_ContentTypes::HTML);
		$yuiSuggest->setInput("href_int", $href_int);
		$yuiSuggest->setMaxResults(20);
		$yuiSuggest->setMayBeEmpty(true);
		$yuiSuggest->setResult("id", $id);
		$yuiSuggest->setSelector(weSuggest::DocSelector);
		$yuiSuggest->setWidth(300);
		$yuiSuggest->setSelectButton($but, 10);

		$intLink = $yuiSuggest->getHTML();
		if(defined("OBJECT_TABLE")){
			//javascript:we_cmd('openDocselector',document.forms[0].obj_id.value,'" . OBJECT_FILES_TABLE . "','document.forms[\\'we_form\\'].elements[\\'obj_id\\'].value','document.forms[\\'we_form\\'].elements[\\'href_obj\\'].value','','".session_id()."','','objectFile',".(permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_OBJECTS") ? 0 : 1).");"
			$wecmdenc1 = we_cmd_enc("document.forms['we_form'].elements['obj_id'].value");
			$wecmdenc2 = we_cmd_enc("document.forms['we_form'].elements['href_obj'].value");
			$wecmdenc3 = '';
			$but = we_html_button::create_button("select", "javascript:we_cmd('openDocselector',document.forms[0].obj_id.value,'" . OBJECT_FILES_TABLE . "','" . $wecmdenc1 . "','" . $wecmdenc2 . "','','" . session_id() . "','','objectFile'," . (permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_OBJECTS") ? 0 : 1) . ");");

			$yuiSuggest->setAcId("Obj");
			$yuiSuggest->setContentType("folder,objectFile");
			$yuiSuggest->setInput("href_obj", $href_obj);
			$yuiSuggest->setMaxResults(20);
			$yuiSuggest->setMayBeEmpty(true);
			$yuiSuggest->setResult("obj_id", $obj_id);
			$yuiSuggest->setSelector(weSuggest::DocSelector);
			$yuiSuggest->setTable(OBJECT_FILES_TABLE);
			$yuiSuggest->setWidth(300);
			$yuiSuggest->setSelectButton($but, 10);

			$objLink = $yuiSuggest->getHTML();
		}

		//added for #7269
		$bcc = we_html_tools::htmlTextInput('bcc', 30, $bcc, '', '', 'text', 300);
		$cc = we_html_tools::htmlTextInput('cc', 30, $cc, '', '', 'text', 300);
		$subject = we_html_tools::htmlTextInput('subject', 30, $subject, '', '', 'text', 300);

		$anchor = we_html_tools::htmlTextInput('anchor', 30, $anchor, '', '', 'text', 300);
		$accesskey = we_html_tools::htmlTextInput('accesskey', 30, $accesskey, '', '', 'text', 140);
		$tabindex = we_html_tools::htmlTextInput('tabindex', 30, $tabindex, '', '', 'text', 140);
		$lang = getLangField('lang', $lang, g_l('linklistEdit', '[link_language]'), 140);
		$relfield = getRevRelSelect('rel', $rel);
		$revfield = getRevRelSelect('rev', $rev);
		$hreflang = getLangField('hreflang', $hreflang, g_l('linklistEdit', '[href_language]'), 140);
		$params = we_html_tools::htmlTextInput('params', 30, $params, '', '', 'text', 300);
		$title = we_html_tools::htmlTextInput('title', 30, $title, '', '', 'text', 300);
		$ctarget = we_html_tools::targetBox('target', 30, 300, '', $target);
		$cattribs = we_html_tools::htmlTextInput('attribs', 30, $attribs, '', '', 'text', 300);
		$jsWinProps = '
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="small">
							' . g_l('global', '[posx]') . '</td>
						<td></td>
						<td class="small">
							' . g_l('global', "[posy]") . '</td>
						<td></td>
						<td class="small">
							' . g_l('global', "[width]") . '</td>
						<td></td>
						<td class="small">
							' . g_l('global', "[height]") . '</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>' . we_html_tools::htmlTextInput('jsposx', 4, $jsposx, '', '', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput('jsposy', 4, $jsposy, '', "", "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("jswidth", 4, $jswidth, '', ' onchange="if(this.form.jscenter.checked && this.value==\'\'){this.value=100}"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("jsheight", 4, $jsheight, "", ' onchange="if(this.form.jscenter.checked && this.value==\'\'){this.value=100}"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
					</tr>
					<tr>
						<td colspan="9">' . we_html_tools::getPixel(2, 2) . '</td>
					</tr>
					<tr>
						<td>' . we_html_forms::checkbox(1, $jsstatus, "jsstatus", g_l('global', "[status]"), true, "small") . '</td>
						<td></td>
						<td>' . we_html_forms::checkbox(1, $jsscrollbars, "jsscrollbars", g_l('global', "[scrollbars]"), true, "small") . '</td>
						<td></td>
						<td>' . we_html_forms::checkbox(1, $jsmenubar, "jsmenubar", g_l('global', "[menubar]"), true, "small") . '</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>' . we_html_forms::checkbox(1, $jsresizable, "jsresizable", g_l('global', "[resizable]"), true, "small") . '</td>
						<td></td>
						<td>' . we_html_forms::checkbox(1, $jslocation, "jslocation", g_l('global', "[location]"), true, "small") . '</td>
						<td></td>
						<td>' . we_html_forms::checkbox(1, $jstoolbar, "jstoolbar", g_l('global', "[toolbar]"), true, "small") . '</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</table>';
		$foo = '
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>' . we_html_forms::checkbox(1, $jswin, "jswin", g_l('global', "[open]")) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_forms::checkbox(1, $jscenter, "jscenter", g_l('global', "[center]"), true, "defaultfont", "if(this.checked){if(this.form.jswidth.value==''){this.form.jswidth.value='100';};if(this.form.jsheight.value==''){this.form.jsheight.value='100';};}") . '</td>
					</tr>
				</table>';
		$jswinonoff = we_html_tools::htmlFormElementTable($jsWinProps, $foo, "left", "defaultfont", we_html_tools::getPixel(10, 2), "", "", "", "", 0);


		$_content_select = '<select name="ctype" size="1" style="margin-bottom:5px;width:300px;" onchange="changeCTypeSelect(this);" class="big">
<option value="' . we_base_link::CONTENT_TEXT . '"' . (($ctype == we_base_link::CONTENT_TEXT) ? ' selected="selected"' : '') . '>' . oldHtmlspecialchars(g_l('linklistEdit', "[text]")) . '</option>
<option value="' . we_base_link::CONTENT_EXT . '"' . (($ctype == we_base_link::CONTENT_EXT) ? ' selected="selected"' : '') . '>' . oldHtmlspecialchars(g_l('linklistEdit', "[external_image]")) . '</option>
<option value="' . we_base_link::CONTENT_INT . '"' . (($ctype == we_base_link::CONTENT_INT) ? ' selected="selected"' : '') . '>' . oldHtmlspecialchars(g_l('linklistEdit', "[internal_image]")) . '</option>
</select>';


		$ctext = we_html_tools::htmlTextInput("text", 30, $text, "", "", "text", 300);

		//javascript:we_cmd('browse_server', 'document.we_form.img_src.value', '', document.we_form.img_src.value, '')
		$wecmdenc1 = we_cmd_enc("document.we_form.img_src.value");
		$but = permissionhandler::hasPerm("CAN_SELECT_EXTERNAL_FILES") ? we_html_button::create_button("select", "javascript:we_cmd('browse_server', '" . $wecmdenc1 . "', '', document.we_form.img_src.value, '')") : "";
		$extImg = we_html_tools::htmlFormElementTable(we_html_tools::htmlTextInput("img_src", 30, $img_src, "", "", "text", 300), "", "left", "defaultfont", we_html_tools::getPixel(10, 2), $but, "", "", "", 0);

		$wecmdenc1 = we_cmd_enc("document.forms['we_form'].elements['img_id'].value");
		$wecmdenc2 = we_cmd_enc("document.forms['we_form'].elements['src_int'].value");
		$but = we_html_button::create_button("select", "javascript:we_cmd('openDocselector',document.forms[0].img_id.value,'" . FILE_TABLE . "','" . $wecmdenc1 . "','" . $wecmdenc2 . "','','" . session_id() . "','','" . we_base_ContentTypes::IMAGE . "'," . (permissionhandler::hasPerm("CAN_SELECT_OTHER_USERS_FILES") ? 0 : 1) . ");");

		$yuiSuggest->setAcId("Image");
		$yuiSuggest->setContentType("folder," . we_base_ContentTypes::IMAGE);
		$yuiSuggest->setInput("src_int", $src_int);
		$yuiSuggest->setMaxResults(20);
		$yuiSuggest->setMayBeEmpty(true);
		$yuiSuggest->setResult('img_id', $img_id);
		$yuiSuggest->setSelector(weSuggest::DocSelector);
		$yuiSuggest->setWidth(300);
		$yuiSuggest->setSelectButton($but, 10);

		$intImg = $yuiSuggest->getHTML();
		$imgProps = '
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="small">' . g_l('global', "[width]") . '</td>
						<td></td>
						<td class="small">' . g_l('global', "[height]") . '</td>
						<td></td>
						<td class="small">' . g_l('global', "[border]") . '</td>
						<td></td>
						<td class="small">' . g_l('global', "[hspace]") . '</td>
						<td></td>
						<td class="small">' . g_l('global', "[vspace]") . '</td>
						<td></td>
						<td class="small">' . g_l('global', "[align]") . '</td>
					</tr>
					<tr>
						<td>' . we_html_tools::htmlTextInput("width", 4, $width, "", ' onkeypress="return IsDigitPercent(event);"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("height", 4, $height, "", ' onkeypress="return IsDigitPercent(event);"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("border", 4, $border, "", ' onkeypress="return IsDigit(event);"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("hspace", 4, $hspace, "", ' onkeypress="return IsDigit(event);"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>' . we_html_tools::htmlTextInput("vspace", 4, $vspace, "", ' onkeypress="return IsDigit(event);"', "text", 40) . '</td>
						<td>' . we_html_tools::getPixel(10, 2) . '</td>
						<td>
							<select class="defaultfont" name="align" size="1">
							<option value="">Default</option>
							<option value="top"' . (($align == "top") ? "selected" : "") . '>Top</option>
							<option value="middle"' . (($align == "middle") ? "selected" : "") . '>Middle</option>
							<option value="bottom"' . (($align == "bottom") ? "selected" : "") . '>Bottom</option>
							<option value="left"' . (($align == "left") ? "selected" : "") . '>Left</option>
							<option value="right"' . (($align == "right") ? "selected" : "") . '>Right</option>
							<option value="texttop"' . (($align == "texttop") ? "selected" : "") . '>Text Top</option>
							<option value="absmiddle"' . (($align == "absmiddle") ? "selected" : "") . '>Abs Middle</option>
							<option value="baseline"' . (($align == "baseline") ? "selected" : "") . '>Baseline</option>
							<option value="absbottom"' . (($align == "absbottom") ? "selected" : "") . '>Abs Bottom</option>
						</select></td>
					</tr>
					<tr><td colspan="12">' . we_html_tools::getPixel(2, 2) . '</td></tr>
					<tr><td colspan="12" class="small">' . g_l('linklistEdit', "[alt_text]") . '</td></tr>
					<tr><td colspan="12">' . we_html_tools::htmlTextInput("alt", 20, $alt, "", '', "text", 300) . '</td></tr>
          <tr><td colspan="12" class="small">' . g_l('linklistEdit', "[title]") . '</td></tr>
					<tr><td colspan="12">' . we_html_tools::htmlTextInput("img_title", 20, $img_title, "", '', "text", 300) . '</td></tr>
				</table>';
		$buttons = we_html_button::position_yes_no_cancel(we_html_button::create_button("save", "javascript:document.forms['we_form'].submit()"), null, we_html_button::create_button("cancel", "javascript:self.close()"));

		$_parts = array(
			array('headline' => 'URL',
				'html' => '
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>' . $_select_type . '</td>
	</tr>
	<tr id="ext_tr" style="display:' . (($type == we_base_link::TYPE_EXT) ? "table-row" : "none") . ';">
		<td height="35" valign="top"><div style="margin-top:1px;">' . $extLink . '</div></td>
	</tr>
	<tr id="int_tr" style="display:' . (($type == we_base_link::TYPE_INT) ? "table-row" : "none") . ';">
		<td height="35" valign="top">' . $intLink . '</td>
	</tr>
	<tr id="mail_tr" style="display:' . (($type == we_base_link::TYPE_MAIL) ? "table-row" : "none") . ';">
		<td height="35" valign="top"><div style="margin-top:2px;">' . $emailLink . '</div></td>
	</tr>
' . (defined("OBJECT_TABLE") ? '
	<tr id="obj_tr" style="display:' . (($type == we_base_link::TYPE_OBJ) ? "table-row" : "none") . ';">
		<td height="35" valign="top">' . $objLink . '</td>
	</tr>
' : '') . '
</table>',
				'space' => 150,
				'noline' => 1),
			array('headline' => g_l('global', "[content]"),
				'html' => '
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>' . $_content_select . '</td>
					</tr>
					<tr id="ctext_tr" style="display:' . (($ctype == we_base_link::CONTENT_TEXT) ? "table-row" : "none") . ';">
						<td>' . $ctext . '</td>
					</tr>
					<tr id="cext_tr" style="display:' . (($ctype == we_base_link::CONTENT_EXT) ? "table-row" : "none") . ';">
						<td>' . $extImg . '</td>
					</tr>
					<tr id="cint_tr" style="display:' . (($ctype == we_base_link::CONTENT_INT) ? "table-row" : "none") . ';">
						<td>' . $intImg . '</td>
					</tr>
					<tr id="cimgprops_tr" style="display:' . (($ctype == we_base_link::CONTENT_TEXT) ? "none" : "table-row") . ';">
						<td>' . we_html_tools::getPixel(10, 3) . "<br>" . $imgProps . '</td>
					</tr>
				</table><div></div>',
				'space' => 150),
			array('headline' => g_l('linklistEdit', "[link_anchor]"),
				'html' => $anchor,
				'space' => 150,
				'noline' => 1),
			array('headline' => g_l('linklistEdit', "[link_params]"),
				'html' => $params,
				'space' => 150,
				'noline' => 1),
			array('headline' => g_l('linklistEdit', "[link_target]"),
				'html' => $ctarget,
				'space' => 150)
		);


		if(permissionhandler::hasPerm("CAN_SEE_ACCESSIBLE_PARAMETERS")){
			//   start of accessible parameters
			$_parts[] = array('headline' => g_l('linklistEdit', '[language]'),
				'html' => '
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>' . $lang . '</td>
		<td>' . we_html_tools::getPixel(20, 5) . '</td>
		<td>' . $hreflang . '</td>
	</tr>
</table>',
				'space' => 150,
				'noline' => 1);

			$_parts[] = array('headline' => g_l('linklistEdit', '[title]'),
				'html' => $title,
				'space' => 150,
				'noline' => 1);

			$_parts[] = array('headline' => g_l('linklistEdit', '[keyboard]'),
				'html' => '
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="small">' . g_l('linklistEdit', '[accesskey]') . '</td>
		<td>' . we_html_tools::getPixel(20, 5) . '</td>
		<td class="small">' . g_l('linklistEdit', '[tabindex]') . '</td>
	</tr>
	<tr>
		<td>' . $accesskey . '</td>
		<td>' . we_html_tools::getPixel(20, 5) . '</td>
		<td>' . $tabindex . '</td>
	</tr>
</table>',
				'space' => 150,
				'noline' => 1);

			$_parts[] = array('headline' => g_l('wysiwyg', "[relation]"),
				'html' => '<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td>' . $relfield . '</td>
                                <td>' . we_html_tools::getPixel(20, 5) . '</td>
                                <td>' . $revfield . '</td>
                            </tr>
			                 </table>',
				'space' => 150,
				'noline' => 1);

			$_parts[] = array('headline' => g_l('linklistEdit', "[link_attr]"),
				'html' => $cattribs,
				'space' => 150);
		}


		//   Pop-Up
		$_parts[] = array('headline' => g_l('global', "[jswin]"),
			'html' => $jswinonoff,
			'space' => 150);
		?>
		<form name="we_form" action="<?php print WEBEDITION_DIR; ?>we_cmd.php" method="post" onsubmit="return false">
			<input type="hidden" name="we_cmd[0]" value="<?php print $_REQUEST['we_cmd'][0]; ?>" />
			<?php
			if(isset($ll) && $ll){
				?>
				<input type="hidden" name="linklist" value="<?php print oldHtmlspecialchars($ll->getString()); ?>" />
				<?php
			}
			?>
			<input type="hidden" name="name" value="<?php print $name; ?>" />
			<input type="hidden" name="nr" value="<?php echo weRequest('int', "nr", $nr); ?>" />
			<input type="hidden" name="ok" value="1" />
			<input type="hidden" name="we_transaction" value="<?php echo $we_transaction; ?>" />
			<input type="hidden" name="we_field" value="<?php echo weRequest('string','we_cmd','',3); ?>" />
			<?php
			echo we_html_multiIconBox::getHTML('', '100%', $_parts, 30, $buttons, -1, '', '', false, g_l('linklistEdit', '[edit_link]'), '', 671) .
			$yuiSuggest->getYuiCss() .
			$yuiSuggest->getYuiJs();
			?>
		</form>
		<?php
	}
	?>
</body>

</html>
<?php
if(!isset($_REQUEST['ok']) || !$_REQUEST['ok']){
	$we_doc->saveInSession($_SESSION['weS']['we_data'][$we_transaction]);
}