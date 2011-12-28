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
class we_linklist{

	private $name = "";
	private $sString = "";
	private $listArray;
	private $db;
	private $rollScript = "";
	private $rollAttribs = array();
	private $cache = array();
	private $hidedirindex = false;
	private $objectseourls = false;
	private $docName;
	private $attribs;
	private $show = -1;
	private $cnt = 0;

	function __construct($sString, $hidedirindex=false, $objectseourls=false, $docName='', $attribs=array()){
		$this->sString = $sString;
		$this->hidedirindex = $hidedirindex;
		$this->objectseourls = $objectseourls;
		$this->docName = $docName;
		$this->attribs = $attribs;
		$this->listArray = $sString ? unserialize($sString) : array();
		if(!is_array($this->listArray)){
			$this->listArray = array();
		}
		$limit = (isset($attribs['limit']) && $attribs['limit'] > 0) ? $attribs['limit'] : 0;
		$editmode = (isset($GLOBALS["we_editmode"]) && $GLOBALS["we_editmode"] && (!isset($GLOBALS["lv"])));
		if(!$editmode){
			$this->show = count($this->listArray);
			if($limit > 0 && $this->show > $limit){
				$this->show = $limit;
			}
		}

		$this->db = new DB_WE();
		reset($this->listArray);
	}

	function setName($name){
		$this->name = $name;
	}

	function getName(){
		return $this->name;
	}

	function getID($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["id"]) ? $cur["id"] : null;
	}

	function getObjID($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["obj_id"]) ? $cur["obj_id"] : "";
	}

	function getLink(){
		//$id = $this->getID($nr);
		switch($this->getType()){
			case 'int':
				return $this->getUrl();
			case 'ext':
				return $this->getHref();
			case 'mail':
				return $this->getHref();
			case 'obj':
				$link = getHrefForObject(
					$this->getObjID(), $GLOBALS["WE_MAIN_DOC"]->ParentID, $GLOBALS["WE_MAIN_DOC"]->Path, $this->db, $this->hidedirindex, $this->objectseourls);
				if(isset($GLOBALS["we_link_not_published"])){
					unset($GLOBALS["we_link_not_published"]);
				}
				return $link;
		}
		return '';
	}

	function getHref($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return $cur["href"];
	}

	function getAttribs($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["attribs"]) ? $cur["attribs"] : "";
	}

	function getTarget($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return $cur['target'];
	}

	function getTitle($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["title"]) ? $cur["title"] : "";
	}

	function getLinktag($link = "", $tagAttr = ""){
		if(!$link)
			$link = $this->getLink();
		$target = $this->getTarget();
		$attribs = $this->getAttribs();
		$anchor = $this->getAnchor();
		$accesskey = $this->getAccesskey();
		$tabindex = $this->getTabindex();
		$lang = $this->getLang();
		$hreflang = $this->getHreflang();
		$rel = $this->getRel();
		$rev = $this->getRev();
		$params = $this->getParams();
		$title = $this->getTitle();
		$text = $this->getText(); // #3636
		$jswinAttribs = $this->getJsWinAttribs();
		$js = "var we_winOpts = '';";

		$hidedirindex = $this->getHidedirindex();
		$objectseourls = $this->getObjectseourls();

		$lattribs = makeArrayFromAttribs($attribs);

		$lattribs['target'] = $target;
		$lattribs['title'] = $title;
		$lattribs['accesskey'] = $accesskey;
		$lattribs['tabindex'] = $tabindex;
		$lattribs['lang'] = $lang;
		$lattribs['hreflang'] = $hreflang;
		$lattribs['rel'] = $rel;
		$lattribs['rev'] = $rev;
		$lattribs = removeEmptyAttribs($lattribs, array());

		$rollOverAttribsArr = $this->rollAttribs;

		if(is_array($tagAttr)){
			foreach($tagAttr as $n => $v){
				$lattribs[$n] = $v;
			}
		}

		// overwrite rolloverattribs
		foreach($rollOverAttribsArr as $n => $v){
			$lattribs[$n] = $v;
		}

		if(isset($jswinAttribs) && is_array($jswinAttribs) && isset($jswinAttribs["jswin"])){ //popUp
			if($jswinAttribs["jscenter"] && $jswinAttribs["jswidth"] && $jswinAttribs["jsheight"]){
				$js .= 'if (window.screen) {var w = ' . $jswinAttribs["jswidth"] . ';var h = ' . $jswinAttribs["jsheight"] . ';var screen_height = screen.availHeight - 70;var screen_width = screen.availWidth-10;var w = Math.min(screen_width,w);var h = Math.min(screen_height,h);var x = (screen_width - w) / 2;var y = (screen_height - h) / 2;we_winOpts = \'left=\'+x+\',top=\'+y;}else{we_winOpts=\'\';};';
			} else
			if($jswinAttribs["jsposx"] != "" || $jswinAttribs["jsposy"] != ""){
				if($jswinAttribs["jsposx"] != ""){
					$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'left=' . $jswinAttribs["jsposx"] . '\';';
				}
				if($jswinAttribs["jsposy"] != ""){
					$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'top=' . $jswinAttribs["jsposy"] . '\';';
				}
			}
			if($jswinAttribs["jswidth"] != ""){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'width=' . $jswinAttribs["jswidth"] . '\';';
			}
			if($jswinAttribs["jsheight"] != ""){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'height=' . $jswinAttribs["jsheight"] . '\';';
			}
			if($jswinAttribs["jsstatus"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'status=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'status=no\';';
			}
			if($jswinAttribs["jsscrollbars"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'scrollbars=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'scrollbars=no\';';
			}
			if($jswinAttribs["jsmenubar"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'menubar=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'menubar=no\';';
			}
			if($jswinAttribs["jsresizable"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'resizable=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'resizable=no\';';
			}
			if($jswinAttribs["jslocation"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'location=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'location=no\';';
			}
			if($jswinAttribs["jstoolbar"]){
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'toolbar=yes\';';
			} else{
				$js .= 'we_winOpts += (we_winOpts ? \',\' : \'\')+\'toolbar=no\';';
			}
			$foo = $js . "var we_win = window.open('','" . "we_ll_" . $nr . "',we_winOpts);";

			$lattribs = removeAttribs($lattribs, array(
				'name', 'target', 'href', 'onClick', 'onclick'
				));

			$lattribs['target'] = 'we_ll_' . $nr;
			$lattribs['onclick'] = $foo;
		} else{ //  no popUp
			$lattribs = removeAttribs($lattribs, array(
				'name', 'href'
				));
		}
		$lattribs['href'] = $link . str_replace('&', '&amp;', $params . $anchor);

		if(isset($lattribs['only'])){
			return $lattribs[$lattribs['only']];
		}

		return $this->rollScript . getHtmlTag('a', $lattribs, '', false, true);
	}

	function getUrl($params = ""){
		$id = $this->getID();
		if($id == '')
			return "http://";
		if(isset($this->cache[$id])){
			$row = $this->cache[$id];
		} else{
			$row = getHash('SELECT IsDynamic,Path FROM ' . FILE_TABLE . ' WHERE ID=' . intval($id), $this->db);
			$this->cache[$id] = $row;
		}
		if(isset($row["Path"]) && $this->hidedirindex){
			$path_parts = pathinfo($row["Path"]);
			if(show_SeoLinks() && defined('NAVIGATION_DIRECTORYINDEX_NAMES') && NAVIGATION_DIRECTORYINDEX_NAMES != '' && in_array($path_parts['basename'], explode(',', NAVIGATION_DIRECTORYINDEX_NAMES))){
				$row["Path"] = ($path_parts['dirname'] != DIRECTORY_SEPARATOR ? $path_parts['dirname'] : '') . DIRECTORY_SEPARATOR;
			}
		}

		return (isset($row["Path"]) ? $row["Path"] : '') . ($params ? ("?" . $params) : "");
	}

	function getImageID($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["img_id"]) ? $cur["img_id"] : "";
	}

	function getImageAttribs($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["img_attribs"]) ? $cur["img_attribs"] : array();
	}

	function getImageAttrib($nr, $key){
		$foo = $this->getImageAttribs($nr);
		return isset($foo[$key]) ? $foo[$key] : "";
	}

	function getJsWinAttrib($nr, $key){
		$foo = $this->getJsWinAttribs();
		return isset($foo[$key]) ? $foo[$key] : "";
	}

	function getJsWinAttribs($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["jswin_attribs"]) ? $cur["jswin_attribs"] : array();
	}

	function getImageSrc($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur["img_src"]) ? $cur["img_src"] : "";
	}

	function getText($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return $cur["text"];
	}

	function getAnchor($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['anchor']) ? $cur['anchor'] : '';
	}

	function getAccesskey($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['accesskey']) ? $cur['accesskey'] : '';
	}

	function getTabindex($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['tabindex']) ? $cur['tabindex'] : '';
	}

	function getLang($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['lang']) ? $cur['lang'] : '';
	}

	function getRel($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['rel']) ? $cur['rel'] : '';
	}

	function getRev($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['rev']) ? $cur['rev'] : '';
	}

	function getHreflang($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['hreflang']) ? $cur['hreflang'] : '';
	}

	function getHidedirindex($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['hidedirindex']) ? $cur['hidedirindex'] : '';
	}

	function getObjectseourls($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['objectseourls']) ? $cur['objectseourls'] : '';
	}

	function getParams($nr=-1){
		$cur = $nr != -1 ? $this->listArray[$nr] : current($this->listArray);
		return isset($cur['params']) ? $cur['params'] : '';
	}

	function getHrefInt($nr=-1){
		$id = $this->getID($nr);
		return $id ? f("SELECT Path FROM " . FILE_TABLE . " WHERE ID=" . intval($id), "Path", $this->db) : "";
	}

	function getHrefObj($nr=-1){
		$id = $this->getObjID($nr);
		return $id ? f("SELECT Path FROM " . OBJECT_FILES_TABLE . " WHERE ID=" . intval($id), "Path", $this->db) : "";
	}

	function getImageSrcInt($nr=-1){
		$id = $this->getImageID($nr);
		return $id ? f("SELECT Path FROM " . FILE_TABLE . " WHERE ID=" . intval($id), "Path", $this->db) : "";
	}

	function getString(){
		if(sizeof($this->listArray) == 0)
			return "";
		return serialize($this->listArray);
	}

	function setID($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["id"] = $val;
		}
	}

	function setObjID($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["obj_id"] = $val;
		}
	}

	function setHref($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["href"] = $val;
		}
	}

	function setAnchor($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["anchor"] = $val;
		}
	}

	function setAccesskey($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["accesskey"] = $val;
		}
	}

	function setTabindex($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["tabindex"] = $val;
		}
	}

	function setLang($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["lang"] = $val;
		}
	}

	function setRel($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["rel"] = $val;
		}
	}

	function setRev($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["rev"] = $val;
		}
	}

	function setHreflang($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["hreflang"] = $val;
		}
	}

	function setParams($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["params"] = $val;
		}
	}

	function setAttribs($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["attribs"] = $val;
		}
	}

	function setTarget($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["target"] = $val;
		}
	}

	function setImageID($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["img_id"] = $val;
		}
	}

	function setTitle($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["title"] = $val;
		}
	}

	function setImageSrc($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["img_src"] = $val;
		}
	}

	function setText($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["text"] = $val;
		}
	}

	function setImageAttribs($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["img_attribs"] = $val;
		}
	}

	function setImageAttrib($nr, $key, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["img_attribs"][$key] = $val;
		}
	}

	function setJsWinAttribs($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["jswin_attribs"] = $val;
		}
	}

	function setJsWinAttrib($nr, $key, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["jswin_attribs"][$key] = $val;
		}
	}

	function next(){
		$ret = ($this->show == -1 || $this->show > (++$this->cnt)) && next($this->listArray);
		$GLOBALS['we_position']['linklist'][$this->name] = array('size' => sizeof($this->listArray), 'position' => $this->cnt);

		$editmode = (isset($GLOBALS["we_editmode"]) && $GLOBALS["we_editmode"] && (!isset($GLOBALS["lv"])));

		if($editmode){
			$disabled = false;
			if($this->show > 0 && $this->length() >= $this->show){
				$disabled = true;
			}
			$plusbut = we_button::create_button(
					"image:btn_add_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('insert_link_at_linklist','" . $this->attribs["name"] . "','" . $this->cnt . "')", true, 100, 22, "", "", $disabled);
			if($ret === false){
				if(isset($GLOBALS["we_list_inserted"]) && isset($GLOBALS["we_list_inserted"]) && ($GLOBALS["we_list_inserted"] == $this->attribs["name"])){
					echo '<script  type="text/javascript">we_cmd(\'edit_linklist\',\'' . $this->attribs["name"] . '\',\'' . ((isset(
						$GLOBALS["we_list_insertedNr"]) && ($GLOBALS["we_list_insertedNr"] != "")) ? $GLOBALS["we_list_insertedNr"] : $this->getMaxListNrID()) . '\');</script>';
				}
				if($this->show == -1 || ($this->show > $this->length())){
					echo "<br/>" . we_button::create_button(
						"image:btn_add_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('add_link_to_linklist','" . $this->attribs["name"] . "')", true, 100, 22, "", "", $disabled);
					echo '<input type="hidden" name="we_' . $this->docName . '_linklist[' . $this->attribs["name"] . ']" value="' . htmlspecialchars(
						$this->getString()) . '" />' . ($this->length() ? '' : $plusbut);
				}
			} else{
				// Create button object
				// Create buttons
				$upbut = we_button::create_button(
						"image:btn_direction_up", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('up_link_at_list','" . $this->attribs["name"] . "','" . $this->cnt . "')", true, -1, -1, "", "", !($this->cnt > 0));
				$downbut = we_button::create_button(
						"image:btn_direction_down", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('down_link_at_list','" . $this->attribs["name"] . "','" . $this->cnt . "')", true, -1, -1, "", "", !($this->cnt < (sizeof($this->listArray) - 1)));
				$editbut = we_button::create_button(
						"image:btn_edit_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('edit_linklist','" . $this->attribs["name"] . "','" . $this->cnt . "')", true);
				$trashbut = we_button::create_button(
						"image:btn_function_trash", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('delete_linklist','" . $this->attribs["name"] . "','" . $this->cnt . "','')", true);
				echo we_button::create_button_table(
					array(
					$plusbut, $upbut, $downbut, $editbut, $trashbut
					), 5);
			}
		}
		if($ret === false){
			//remove var
			unset($GLOBALS['we_position']['linklist'][$this->name]);
		}
		return $ret;
	}

	function getHTML($content){
		$editmode = (isset($GLOBALS["we_editmode"]) && $GLOBALS["we_editmode"] && (!isset($GLOBALS["lv"])));
		$linklistRef = $attribs["name"] . "_TAGS_";
		$limit = (isset($attribs['limit']) && $attribs['limit'] > 0) ? $attribs['limit'] : 0;
		$out = "";

		$j = 0;
		$disabled = false;
		$i = key($this->listArray);
		$val = current($this->listArray);
		foreach($this->listArray as $i => $val){
			$j++;
			if(!$editmode && $j > $show){
				break;
			}

			if(intval($i) || $i == "0"){
				$foo = $content;

				$link = $this->getLink();
				$linkcontent = $this->getLinkContent();
				if($linkcontent){

					$buts = "";

					if($editmode){
						// Create button object
						// Create buttons
						$disabled = false;
						if($limit > 0 && $this->length() >= $limit){
							$disabled = true;
						}
						$plusbut = we_button::create_button(
								"image:btn_add_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('insert_link_at_linklist','" . $attribs["name"] . "','" . $i . "')", true, 100, 22, "", "", $disabled);
						$upbut = we_button::create_button(
								"image:btn_direction_up", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('up_link_at_list','" . $attribs["name"] . "','" . $i . "')", true, -1, -1, "", "", !($i > 0));
						$downbut = we_button::create_button(
								"image:btn_direction_down", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('down_link_at_list','" . $attribs["name"] . "','" . $i . "')", true, -1, -1, "", "", !($i < (sizeof($this->listArray) - 1)));
						$editbut = we_button::create_button(
								"image:btn_edit_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('edit_linklist','" . $attribs["name"] . "','" . $i . "')", true);
						$trashbut = we_button::create_button(
								"image:btn_function_trash", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('delete_linklist','" . $attribs["name"] . "','" . $i . "','')", true);
						$buts = we_button::create_button_table(
								array(
								$plusbut, $upbut, $downbut, $editbut, $trashbut
								), 5);
					}

					if($i == (sizeof($this->listArray) - 1)){
						$foo = eregi_replace('<we_:_postlink>.*</we_:_postlink>', '', $foo);
					} else{
						$foo = str_replace(array('<we_:_postlink>', '</we_:_postlink>'), '', $foo);
					}
					if($i == 0){
						$foo = eregi_replace('<we_:_prelink>.*</we_:_prelink>', '', $foo);
					} else{
						$foo = str_replace(array('<we_:_prelink>', '</we_:_prelink>'), '', $foo);
					}

					//	handle we:ifPosition - if available
					$foo = '<?php $GLOBALS[\'we_position\'][\'linklist\'][\'' . $this->name . '\'] = array(\'size\'=> ' . sizeof(
							$this->listArray) . ',\'position\'=>' . ($i + 1) . '); ?>' . $foo . '<?php unset($GLOBALS[\'we_position\'][\'linklist\'][\'' . $this->name . '\']); ?>';
					//	handle we:ifPosition - if available


					$lnr = $this->listArray[$i]["nr"];

					$foo = eregi_replace('<we_:_field */? *>', $linkcontent, $foo);
					$foo = eregi_replace('<we_:_path */? *>', $link . $this->getParams($i), $foo);
					$foo = str_replace(
						'<we_:_ifSelf>', '<?php if("' . $GLOBALS["WE_MAIN_DOC"]->Path . '" == "' . $this->getLink() . '"){ ?>', $foo);
					$foo = str_replace(
						'<we_:_ifNotSelf>', '<?php if("' . $GLOBALS["WE_MAIN_DOC"]->Path . '" != "' . $this->getLink() . '"){ ?>', $foo);

					if(!isset($this->listArray[$i]["nr"])){
						$nr = $i;
						$this->listArray[$i]["nr"] = $nr;
					} else{
						$nr = $this->listArray[$i]["nr"];
					}

					if(preg_match_all('/<we_:_link([^>\/]*)\/?>/', $foo, $regs, PREG_SET_ORDER)){

						foreach($regs as $reg){

							$attrArr = makeArrayFromAttribs(trim($reg[1]));

							$xml = weTag_getAttribute("xml", $attrArr, (defined('XHTML_DEFAULT') && XHTML_DEFAULT == 1), true);
							$_content = $linkcontent;

							if(isset($attrArr['only'])){
								$foo = str_replace($reg[0], $this->getLinktag($i, $link, $attrArr) . $buts, $foo);
							} else{
								if($link){
									$linktag = $this->getLinktag($i, $link, $attrArr);
									$foo = str_replace($reg[0], $linktag . $_content . '</a>' . $buts, $foo);
								} else{
									$foo = str_replace($reg[0], $_content . $buts, $foo);
								}
							}
						}
					}
					$out .= $foo;
				}
			}
			$this->rollScript = "";
			$this->rollAttribs = array();
		}

		return $out;
	}

	function getType(){
		$cur = current($this->listArray);
		return isset($cur["type"]) ? $cur["type"] : "";
	}

	function getCType(){
		$cur = current($this->listArray);
		return isset($cur["ctype"]) ? $cur["ctype"] : "";
	}

	function setType($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["type"] = $val;
		}
	}

	function setCType($nr, $val){
		if($nr == "0" || $nr){
			$this->listArray[$nr]["ctype"] = $val;
		}
	}

	function addLink(){
		array_push($this->listArray, $this->getRawLink());
	}

	function length(){
		return count($this->listArray);
	}

	function upLink($nr){
		$temp = $this->listArray[$nr - 1];
		$this->listArray[$nr - 1] = $this->listArray[$nr];
		$this->listArray[$nr] = $temp;
	}

	function downLink($nr){
		$temp = $this->listArray[$nr + 1];
		$this->listArray[$nr + 1] = $this->listArray[$nr];
		$this->listArray[$nr] = $temp;
	}

	function insertLink($nr){
		$l = $this->getRawLink();
		for($i = 0; $i < sizeof($this->listArray); $i++){
			$lnr = $this->listArray[$i]["nr"];
			if(!isset($this->listArray[$i]["nr"])){
				$this->listArray[$i]["nr"] = $i;
			}
		}
		for($i = sizeof($this->listArray); $i > $nr; $i--){
			$this->listArray[$i] = $this->listArray[$i - 1];
		}
		$this->listArray[$nr] = $l;
	}

	function removeLink($nr, $names = "", $name = ""){
		$realNr = $this->listArray[$nr]["nr"];
		$namesArray = $names ? explode(",", $names) : array();
		foreach($namesArray as $n){
			unset($GLOBALS['we_doc']->elements[$n . $name . "_TAGS_" . $realNr]);
		}
		array_splice($this->listArray, $nr, 1);
	}

	/* ##### private Functions##### */

	function getMaxListNr(){
		$n = 0;
		foreach($this->listArray as $item){
			$n = max($item["nr"], $n);
		}
		return $n;
	}

	function getMaxListNrID(){
		$n = 0;
		$out = 0;
		for($i = 0; $i < sizeof($this->listArray); $i++){
			if($this->listArray[$i]["nr"] > $n){
				$n = $this->listArray[$i]["nr"];
				$out = $i;
			}
		}
		return $out;
	}

	function getRawLink(){
		$foo = array();
		$foo["href"] = "http://";
		$foo["text"] = g_l('global', "[new_link]");
		$foo["target"] = "";
		$foo["type"] = "ext";
		$foo["ctype"] = "text";
		$foo["nr"] = $this->getMaxListNr() + 1;
		return $foo;
	}

	function getLinkContent(){
		switch($this->getCType()){
			case 'int':
				return $this->makeImgTag();
			case 'ext':
				return $this->makeImgTagFromSrc($this->getImageSrc(), $this->getImageAttribs());
			case 'text':
				return $this->getText();
			default:
				return '';
		}
	}

	function makeImgTag($attribs = ""){
		include_once ($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we_classes/we_imageDocument.inc.php');
		$id = $this->getImageID();
		if(!$attribs)
			$attribs = $this->getImageAttribs();
		$img = new we_imageDocument();
		$img->initByID($id);
		$img->initByAttribs($attribs);
		//	name in linklist is generated from linklistname
		$img->elements['name']['dat'] = $this->name . "_img" . $nr;
		$this->rollScript = $img->getRollOverScript();
		$this->rollAttribs = $img->getRollOverAttribsArr();

		return $img->getHtml(false, false);
	}

	function makeImgTagFromSrc($src, $attribs){

		$attribs = removeEmptyAttribs($attribs, array(
			'alt'
			));
		$attribs['src'] = $src;
		return getHtmlTag('img', $attribs);
	}

	function mta($hash, $key){
		return (isset($hash[$key]) && $hash[$key] != "") ? (' ' . $key . '="' . $hash[$key] . '"') : '';
	}

	function last(){
		$editmode = (isset($GLOBALS["we_editmode"]) && $GLOBALS["we_editmode"] && (!isset($GLOBALS["lv"])));
		if($editmode && count($this->listArray) == 0){
			echo "<br/>" . we_button::create_button(
				"image:btn_add_link", "javascript:setScrollTo();_EditorFrame.setEditorIsHot(1);we_cmd('add_link_to_linklist','" . $this->attribs["name"] . "')", true, 100, 22, "", "", $disabled);
			echo '<input type="hidden" name="we_' . $this->docName . '_linklist[' . $this->attribs["name"] . ']" value="' . htmlspecialchars(
				$this->getString()) . '" />' . ($this->length() ? '' : $plusbut);
		}
	}

}