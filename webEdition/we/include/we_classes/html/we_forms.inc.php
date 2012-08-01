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
 * Class we_forms
 *
 * Provides functions for creating html tags used in forms.
 */
include_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_tag.inc.php');

abstract class we_forms{

	/**
	 * @param      $value                                  string
	 * @param      $checked                                bool
	 * @param      $name                                   string
	 * @param      $text                                   string
	 * @param      $uniqid                                 bool                (optional)
	 * @param      $class                                  string              (optional)
	 * @param      $onClick                                string              (optional)
	 * @param      $disabled                               bool                (optional)
	 *
	 * @return     string
	 */
	static function checkbox($value, $checked, $name, $text, $uniqid = false, $class = 'defaultfont', $onClick = '', $disabled = false, $description = '', $type = 0, $width = 0, $html = ''){
		// Check if we have to create a uniqe id
		$_id = ($uniqid ? uniqid($name . '_') : $name); // FIXME: #6590: str_replace('.', '', uniqid("",true))
		// Create HTML tags
		return '
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>
					<td' . ($description ? ' valign="top"' : '') . '>
						<input type="checkbox" name="' . $name . '" id="' . $_id . '" value="' . $value . '" style="cursor: pointer; outline: 0px;" ' . ($checked ? " checked=\"checked\"" : "") . ($onClick ? " onclick=\"$onClick\"" : "") . ($disabled ? " disabled=\"disabled\"" : "") . ' /></td>
					<td>
						' . we_html_tools::getPixel(4, 2) . '</td>
					<td class="' . $class . '" style="white-space:nowrap;"><label id="label_' . $_id . '" for="' . $_id . '" style="' . ($disabled ? 'color: grey; ' : 'cursor: pointer;') . 'outline: 0px;">' . $text . '</label>' . ($description ? "<br>" . we_html_tools::getPixel(1, 3) . "<br>" . we_html_tools::htmlAlertAttentionBox($description, $type, $width) : "") . ($html ? $html : "") . '</td>
				</tr>
			</table>';
	}

	/**
	 * @param      $checked                                bool
	 * @param      $name                                   string
	 * @param      $text                                   string
	 * @param      $uniqid                                 bool                (optional)
	 * @param      $class                                  string              (optional)
	 * @param      $onClick                                string              (optional)
	 * @param      $disabled                               bool                (optional)
	 *
	 * @return     string
	 */
	static function checkboxWithHidden($checked, $name, $text, $uniqid = false, $class = "defaultfont", $onClick = "", $disabled = false, $description = "", $type = 0, $width = 0){
		$onClick = "this.form.elements['$name'].value=this.checked ? 1 : 0;" . $onClick;
		return '<input type="hidden" name="' . $name . '" value="' . ($checked ? 1 : 0) . '" />' . self::checkbox(1, $checked, "_" . $name, $text, $uniqid, $class, $onClick, $disabled, $description, $type, $width);
	}

	/**
	 * @param      $value                                  string
	 * @param      $checked                                bool
	 * @param      $name                                   string
	 * @param      $text                                   string
	 * @param      $uniqid                                 bool                (optional)
	 * @param      $class                                  string              (optional)
	 * @param      $onClick                                string              (optional)
	 * @param      $disabled                               bool                (optional)
	 *
	 * @return     string
	 */
	static function radiobutton($value, $checked, $name, $text, $uniqid = true, $class = "defaultfont", $onClick = "", $disabled = false, $description = "", $type = 0, $width = 0, $onMouseUp = "", $extra_content = ""){
		// Check if we have to create a uniqe id
		$_id = $name . ($uniqid ? '_' . uniqid(rand()) : '');
		// Create HTML tags
		return '
			<table cellpadding="0" border="0" cellspacing="0">
				<tr>
					<td class="weEditmodeStyle"' . ($description ? ' valign="top"' : '') . '>
						<input type="radio" name="' . $name . '" id="' . $_id . '" value="' . $value . '" style="cursor: pointer;outline: 0px;" ' . ($checked ? " checked=\"checked\"" : "") . ($onMouseUp ? " onmouseup=\"$onMouseUp\"" : "") . ($onClick ? " onclick=\"$onClick\"" : "") . ($disabled ? " disabled=\"disabled\"" : "") . ' /></td>
					<td class="weEditmodeStyle">
						' . we_html_tools::getPixel(4, 2) . '</td>
					<td class="weEditmodeStyle ' . $class . '" nowrap="nowrap"><label id="label_' . $_id . '" for="' . $_id . '" style="' . ($disabled ? 'color: grey; ' : 'cursor: pointer;') . 'outline: 0px;" ' . ($onMouseUp ? " onmouseup=\"" . str_replace("this.", "document.getElementById('" . $_id . "').", $onMouseUp) . "\"" : "") . '>' . $text . '</label>' . ($description ? "<br>" . we_html_tools::getPixel(1, 3) . "<br>" . we_html_tools::htmlAlertAttentionBox($description, $type, $width) : "") .
			($extra_content ? (we_html_element::htmlBr() . we_html_tools::getPixel(1, 3) . we_html_element::htmlBr() . $extra_content) : "") . '</td>
				</tr>
			</table>';
	}

	/**
	 * returns the HTML Code for a webEdition Textarea (we:textarea we:sessionfield ...)
	 *
	 * @return string
	 * @param string $name
	 * @param string $value
	 * @param array $attribs
	 * @param string $autobr
	 * @param string $autobrName
	 * @param boolean $showAutobr
	 * @param string $path
	 * @param boolean $hidestylemenu
	 * @param boolean $forceinwebedition
	 * @param boolean $xml
	 * @param boolean $removeFirstParagraph
	 * @param string $charset
	 *
	 */
	static function weTextarea($name, $value, $attribs, $autobr, $autobrName, $showAutobr = true, $path = "", $hidestylemenu = false, $forceinwebedition = false, $xml = false, $removeFirstParagraph = true, $charset = "", $showSpell = true, $isFrontendEdit = false){
		if($charset == ""){
			if(isset($GLOBALS['we_doc']) && $GLOBALS['we_doc']->getElement("Charset")){
				$charset = $GLOBALS['we_doc']->getElement("Charset");
			}
		}

		$out = "";
		$dhtmledit = weTag_getAttribute("dhtmledit", $attribs, false, true); //4614
		$wysiwyg = weTag_getAttribute("wysiwyg", $attribs, false, true);

		$wysiwyg = ($dhtmledit || $wysiwyg) && (we_base_browserDetect::isIE() || we_base_browserDetect::isGecko() || we_base_browserDetect::isOpera() || (defined("SAFARI_WYSIWYG") && we_base_browserDetect::isSafari()));
		$cols = weTag_getAttribute("cols", $attribs);
		$rows = weTag_getAttribute("rows", $attribs);
		$width = weTag_getAttribute("width", $attribs);
		$height = weTag_getAttribute("height", $attribs);
		$commands = preg_replace('/ *, */', ',', weTag_getAttribute('commands', $attribs));
		$bgcolor = weTag_getAttribute("bgcolor", $attribs);
		$wrap = weTag_getAttribute("wrap", $attribs);
		$hideautobr = weTag_getAttribute("hideautobr", $attribs, false, true);
		$class = weTag_getAttribute("class", $attribs);
		$style = weTag_getAttribute("style", $attribs);
		$id = weTag_getAttribute("id", $attribs);
		$inlineedit = weTag_getAttribute("inlineedit", $attribs, defined("INLINEEDIT_DEFAULT") ? INLINEEDIT_DEFAULT : true, true);
		$tabindex = weTag_getAttribute("tabindex", $attribs);

		$buttonpos = weTag_getAttribute("buttonpos", $attribs);

		$cssClasses = weTag_getAttribute("classes", $attribs);

		$buttonTop = false;
		$buttonBottom = false;

		if($buttonpos){
			$foo = makeArrayFromCSV($buttonpos);
			foreach($foo as $p){
				switch($p){
					case "top":
						$buttonTop = true;
						break;
					case "bottom":
						$buttonBottom = true;
						break;
				}
			}
		}
		if($buttonTop == false && $buttonBottom == false){
			$buttonBottom = true;
		}

		if($style){
			$style = preg_replace('/width:[^;"]+[;"]?/i', '', $style);
			$style = preg_replace('/height:[^;"]+[;"]?/i', '', $style);
			$style = trim($style);
		}
		$fontnames = weTag_getAttribute("fontnames", $attribs);
		$showmenues = weTag_getAttribute("showmenus", $attribs, true, true);
		if(isset($attribs["showMenues"])){ // old style compatibility
			if($attribs["showMenues"] == "off" || $attribs["showMenues"] == "false"){
				$showmenues = false;
			}
		} else if(isset($attribs["showmenues"])){ // old style compatibility
			if($attribs["showmenues"] == "off" || $attribs["showmenues"] == "false"){
				$showmenues = false;
			}
		}
		$importrtf = weTag_getAttribute("importrtf", $attribs, false, true);
		if(isset($GLOBALS['we_doc']) && $GLOBALS['we_doc'] != "" && $GLOBALS['we_doc']->ClassName == "we_objectFile"){
			$inwebedition = $forceinwebedition ? $forceinwebedition : (isset($GLOBALS['we_doc']->InWebEdition) && $GLOBALS['we_doc']->InWebEdition);
		} else{
			$inwebedition = $forceinwebedition ? $forceinwebedition : (isset($GLOBALS["WE_MAIN_DOC"]->InWebEdition) && $GLOBALS["WE_MAIN_DOC"]->InWebEdition);
		}

		$value = self::removeBrokenInternalLinksAndImages($value);

		if($wysiwyg){
			$width = $width ? $width : (abs($cols) ? (abs($cols) * 5.5) : "520");
			$height = $height ? $height : (abs($rows) ? (abs($rows) * 8) : "200");
			if(!$showmenues && (strlen($commands) == 0)){
				$commands = implode(",", we_wysiwyg::getAllCmds());
				$commands = str_replace('formatblock,', '', $commands);
				$commands = str_replace('fontname,', '', $commands);
				$commands = str_replace('fontsize,', '', $commands);
				if($hidestylemenu){
					$commands = str_replace('applystyle,', '', $commands);
				}
			}
			if($hidestylemenu && (strlen($commands) == 0)){
				$commands = implode(",", we_wysiwyg::getAllCmds());
				$commands = str_replace('applystyle,', '', $commands);
			}


			$out .= we_wysiwyg::getHeaderHTML();

			$_lang = (isset($GLOBALS['we_doc']) && isset($GLOBALS['we_doc']->Language)) ? $GLOBALS['we_doc']->Language : WE_LANGUAGE;

			if($inlineedit){

				$e = new we_wysiwyg($name, $width, $height, $value, $commands, $bgcolor, "", $class, $fontnames, (!$inwebedition), $xml, $removeFirstParagraph, $inlineedit, "", $charset, $cssClasses, $_lang, '', $showSpell, $isFrontendEdit);
				$out .= $e->getHTML();
			} else{
				$e = new we_wysiwyg($name, $width, $height, "", $commands, $bgcolor, "", $class, $fontnames, (!$inwebedition), $xml, $removeFirstParagraph, $inlineedit, "", $charset, $cssClasses, $_lang, '', $showSpell, $isFrontendEdit);

				$fieldName = preg_replace('#^.+_txt\[(.+)\]$#', '\1', $name);

				// Bugfix => Workarround Bug # 7445
				if(isset($GLOBALS['we_doc']) && $GLOBALS['we_doc']->ClassName != "we_objectFile" && $GLOBALS['we_doc']->ClassName != "we_object"){
					$value = $GLOBALS['we_doc']->getField($attribs);
				} else{
					$value = parseInternalLinks($value, 0);
				}
				// Ende Bugfix

				$value = str_replace("##|r##", "\r", str_replace("##|n##", "\n", $value));
				$out .= ($buttonTop ? '<div class="tbButtonWysiwygBorder" style="width:25px;border-bottom:0px;background-image: url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif);">' . $e->getHTML() . '</div>' : '') . '<div class="tbButtonWysiwygBorder" id="div_wysiwyg_' . $name . '">' . $value . '</div>' . ($buttonBottom ? '<div class="tbButtonWysiwygBorder" style="width:25px;border-top:0px;background-image: url(' . IMAGE_DIR . 'backgrounds/aquaBackground.gif);">' . $e->getHTML() . '</div>' : '');
			}
		} else{
			if($style && substr($style, -1) != ";"){
				$style .= ";";
			}
			if($width){
				$style .= "width:$width;";
			}
			if($height){
				$style .= "height:$height;";
			}

			if($showAutobr || $showSpell){
				$clearval = $value;
				$value = str_replace(array('<?', '<script', '</script', '\\', "\n", "\r", '"'), array('##|lt;?##', '<##scr#ipt##', '</##scr#ipt##', '\\\\', '\n', '\r', '\\"'), $value);
				$out .= we_html_element::jsElement('new we_textarea("' . $name . '","' . $value . '","' . $cols . '","' . $rows . '","' . $width . '","' . $height . '","' . $autobr . '","' . $autobrName . '",' . ($showAutobr ? ($hideautobr ? "false" : "true") : "false") . ',' . ($importrtf ? "true" : "false") . ',"' . $GLOBALS["WE_LANGUAGE"] . '","' . $class . '","' . $style . '","' . $wrap . '","onkeydown","' . ($xml ? "true" : "false") . '","' . $id . '",' . ((defined('SPELLCHECKER') && $showSpell) ? "true" : "false") . ');') .
					'<noscript><textarea name="' . $name . '"' . ($tabindex ? ' tabindex="' . $tabindex . '"' : '') . ($cols ? ' cols="' . $cols . '"' : '') . ($rows ? ' rows="' . $rows . '"' : '') . ($style ? ' style="' . $style . '"' : '') . ($class ? ' class="' . $class . '"' : '') . ($id ? ' id="' . $id . '"' : '') . '>' . htmlspecialchars($clearval) . '</textarea></noscript>';
			} else{
				$out .= '<textarea name="' . $name . '"' . ($tabindex ? ' tabindex="' . $tabindex . '"' : '') . ($cols ? ' cols="' . $cols . '"' : '') . ($rows ? ' rows="' . $rows . '"' : '') . ($style ? ' style="' . $style . '"' : '') . ($class ? ' class="' . $class . '"' : '') . ($id ? ' id="' . $id . '"' : '') . '>' . htmlspecialchars($value) . '</textarea>';
			}
		}
		return $out;
	}

	static function removeBrokenInternalLinksAndImages(&$text){
		$DB_WE = new DB_WE();
		$regs = array();
		if(preg_match_all('/(href|src)="document:(\\d+)([" \?#])/i', $text, $regs, PREG_SET_ORDER)){
			foreach($regs as $reg){
				if(!f('SELECT 1 FROM ' . FILE_TABLE . ' WHERE ID=' . intval($reg[2]), 'Path', $DB_WE)){
					$text = preg_replace('|<a [^>]*href="document:' . $reg[2] . $reg[3] . '"[^>]*>([^<]+)</a>|i', '\1', $text);
					$text = preg_replace('|<a [^>]*href="document:' . $reg[2] . $reg[3] . '"[^>]*>|i', '', $text);
					$text = preg_replace('|<img [^>]*src="document:' . $reg[2] . $reg[3] . '"[^>]*>|i', '', $text);
				}
			}
		}
		if(preg_match_all('/src="thumbnail:(\\d+)[" ]/i', $text, $regs, PREG_SET_ORDER)){
			foreach($regs as $reg){
				$imgID = $thumbID = 0;
				list($imgID, $thumbID) = explode(",", $reg[1]);
				$thumbObj = new we_thumbnail();
				if(!$thumbObj->initByImageIDAndThumbID($imgID, $thumbID)){
					$text = preg_replace('|<img[^>]+src="thumbnail:' . $reg[1] . '[^>]+>|i', '', $text);
				}
			}
		}
		if(defined("OBJECT_TABLE")){
			if(preg_match_all('/href="object:(\\d+)[^" \?#]+\??/i', $text, $regs, PREG_SET_ORDER)){
				foreach($regs as $reg){
					if(!id_to_path($reg[1], OBJECT_FILES_TABLE)){ // if object doesn't exists, remove the link
						$text = preg_replace('|<a [^>]*href="object:' . $reg[1] . '"[^>]*>([^<]+)</a>|i', '\1', $text);
						$text = preg_replace('|<a [^>]*href="object:' . $reg[1] . '"[^>]*>|i', '', $text);
					}
				}
			}
		}

		return $text;
	}

}