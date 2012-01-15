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

function we_tag_userInput($attribs, $content){
	$foo = attributFehltError($attribs, "name", "userInput");
	if ($foo)
		return $foo;

	$name = weTag_getAttribute("name", $attribs);
	$type = weTag_getAttribute("type", $attribs);
	$property = weTag_getAttribute("property", $attribs, false, true);
	$format = weTag_getAttribute("format", $attribs);
	$checked = weTag_getAttribute("checked", $attribs, false, true);
	$value = weTag_getAttribute("value", $attribs);
	$editable = weTag_getAttribute("editable", $attribs, true, true);
	$autobrAttr = weTag_getAttribute("autobr", $attribs, true, true);
	$hidden = weTag_getAttribute("hidden", $attribs, false, true);
	$size = weTag_getAttribute("size", $attribs);
	$values = weTag_getAttribute("values", $attribs);
	$xml = weTag_getAttribute("xml", $attribs,(defined('XHTML_DEFAULT') && XHTML_DEFAULT == 1),true);
	$removeFirstParagraph = weTag_getAttribute("removefirstparagraph", $attribs, defined("REMOVEFIRSTPARAGRAPH_DEFAULT") ? REMOVEFIRSTPARAGRAPH_DEFAULT : true, true);

	if ($hidden && ($type != "date")) {
		$type = "hidden";
	}

	$fieldname = $property ? ("we_ui_" . (isset($GLOBALS["WE_FORM"]) ? $GLOBALS["WE_FORM"] : "") . "_" . $name) : ("we_ui_" . (isset(
			$GLOBALS["WE_FORM"]) ? $GLOBALS["WE_FORM"] : "") . "[" . $name . "]");

	$objekt = (isset($GLOBALS["WE_FORM"]) ? (isset($GLOBALS["we_object"][$GLOBALS["WE_FORM"]]) ? $GLOBALS["we_object"][$GLOBALS["WE_FORM"]] : (isset(
			$GLOBALS["we_document"][$GLOBALS["WE_FORM"]]) ? $GLOBALS["we_document"][$GLOBALS["WE_FORM"]] : (isset(
			$GLOBALS['we_doc']) ? $GLOBALS['we_doc'] : false))) :

	"");

	if ($objekt) {
		if ($property) {
			$isset = isset($objekt->{$name});
			$orgVal = $isset ? $objekt->{$name}: $value;
		} else {
			if (!$objekt->ID && $objekt->getElement($name) === "") {
				$isset = false;
			} else {
				$isset = $objekt->issetElement($name);
			}
			$orgVal = $isset ? $objekt->getElement($name) : $value;
		}
		$object_pid = $objekt->ParentID;
		$object_path = $objekt->Path;
		$object_tableID = isset($objekt->TableID) ? $objekt->TableID : "";
	} else {
		$orgVal = $value;
		$object_pid = 0;
		$object_path = "";
		$object_tableID = "";
		$isset = false;
	}

	$content = "";

	$content = we_document::getFieldByVal(
			$orgVal,
			$type,
			$attribs,
			true,
			$object_pid,
			$object_path,
			$GLOBALS['DB_WE'],
			$object_tableID);

	if (!$editable && !$hidden && $type !== "img" && $type !== "binary" && $type !== "flashmovie" && $type !== "quicktime") {
		$_hidden = getHtmlTag(
				'input',
				array(

				'type' => 'hidden', 'name' => $fieldname, 'value' => htmlspecialchars($orgVal), 'xml' => $xml
				));
		return (($type != "hidden") ? $content : "") . $_hidden;
	} else {
		switch ($type) {
			case "img" :

				$_imgDataId = isset($_REQUEST['WE_UI_IMG_DATA_ID_' . $name]) ? $_REQUEST['WE_UI_IMG_DATA_ID_' . $name] : md5(
						uniqid(rand()));


				if ($editable) {

					$foo = attributFehltError($attribs, "parentid", "userInput");
					if ($foo)
						return $foo;

					if (!isset($_SESSION[$_imgDataId])) {
						$_SESSION[$_imgDataId] = array();
					}
					$_SESSION[$_imgDataId]["parentid"] = weTag_getAttribute("parentid", $attribs, "0");
					//$_SESSION[$_imgDataId]["maxfilesize"] = weTag_getAttribute("maxfilesize",$attribs);
					$_SESSION[$_imgDataId]["width"] = weTag_getAttribute("width",$attribs,0);
					$_SESSION[$_imgDataId]["height"] = weTag_getAttribute("height", $attribs, 0);
					$_SESSION[$_imgDataId]["quality"] = weTag_getAttribute("quality", $attribs, "8");
					$_SESSION[$_imgDataId]["keepratio"] = weTag_getAttribute("keepratio", $attribs, true, true);
					$_SESSION[$_imgDataId]["maximize"] = weTag_getAttribute("maximize", $attribs, false, true);
					$_SESSION[$_imgDataId]["id"] = $orgVal ? $orgVal : '';

					$bordercolor = weTag_getAttribute("bordercolor", $attribs, "#006DB8");
					$checkboxstyle = weTag_getAttribute("checkboxstyle", $attribs);
					$inputstyle = weTag_getAttribute("inputstyle", $attribs);
					$checkboxclass = weTag_getAttribute("checkboxclass", $attribs);
					$inputclass = weTag_getAttributeweTag_getAttribute("inputclass", $attribs);
					$checkboxtext = weTag_getAttribute("checkboxtext", $attribs, g_l('parser','[delete]'));

					if ($_SESSION[$_imgDataId]["id"]) {
						$attribs["id"] = $_SESSION[$_imgDataId]["id"];
					}

					if (isset($_SESSION[$_imgDataId]["serverPath"]) && strpos($_SESSION[$_imgDataId]["serverPath"],TMP_DIR )===false ) {
						$src = substr($_SESSION[$_imgDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						$imgTag = '<img src="' . $src . '" alt="" width="' . $_SESSION[$_imgDataId]["imgwidth"] . '" height="' . $_SESSION[$_imgDataId]["imgheight"] . '" />';
					} else {
						unset($attribs["width"]);
						unset($attribs["height"]);
						$imgTag = $GLOBALS['we_doc']->getField($attribs, "img");
					}

					if (isset($_SESSION[$_imgDataId]["doDelete"]) && $_SESSION[$_imgDataId]["doDelete"]) {
						$checked = ' checked';
					} else {
						$checked = '';
					}

					return '<table border="0" cellpadding="2" cellspacing="2" style="border: solid ' . $bordercolor . ' 1px;">
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="center">' . $imgTag . '
								<input type="hidden" name="WE_UI_IMG_DATA_ID_' . $name . '" value="' . $_imgDataId . '" /></td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<input' . ($size ? ' size="' . $size . '"' : '') . ' name="' . $fieldname . '" type="file" accept="' . we_image_edit::IMAGE_CONTENT_TYPES . '"' . ($inputstyle ? (' style="' . $inputstyle . '"') : '') . ($inputclass ? (' class="' . $inputclass . '"') : '') . '/>
							</td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding-right: 5px;">
											<input style="border:0px solid black;" type="checkbox" id="WE_UI_DEL_CHECKBOX_' . $name . '" name="WE_UI_DEL_CHECKBOX_' . $name . '" value="1" ' . $checked . '/>
										</td>
										<td>
											<label for="WE_UI_DEL_CHECKBOX_' . $name . '"' . ($checkboxstyle ? (' style="' . $checkboxstyle . '"') : '') . ($checkboxclass ? (' class="' . $checkboxclass . '"') : '') . '>' . $checkboxtext . '</label>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
				} else {
					$hidden = '<input type="hidden" name="WE_UI_IMG_DATA_ID_' . $name . '" value="' . $_imgDataId . '" />';

					if (isset($_SESSION[$_imgDataId]["serverPath"])) {
						$src = substr($_SESSION[$_imgDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						return '<img src="' . $src . '" alt="" width="' . $_SESSION[$_imgDataId]["imgwidth"] . '" height="' . $_SESSION[$_imgDataId]["imgheight"] . '" />' . $hidden;
					} else
						if (isset($_SESSION[$_imgDataId]["id"]) && $_SESSION[$_imgDataId]["id"]) {

							if (isset($_SESSION[$_imgDataId]["doDelete"]) && $_SESSION[$_imgDataId]["doDelete"]) {
								return $hidden;
							}

							unset($attribs["width"]);
							unset($attribs["height"]);
							$attribs["id"] = $_SESSION[$_imgDataId]["id"];
							return $GLOBALS['we_doc']->getField($attribs, "img") . $hidden;

						} else {
							return '';
						}
				}
			case "flashmovie" :

				$_flashmovieDataId = isset($_REQUEST['WE_UI_FLASHMOVIE_DATA_ID_' . $name]) ? $_REQUEST['WE_UI_FLASHMOVIE_DATA_ID_' . $name] : md5(
						uniqid(rand()));


				if ($editable) {

					$foo = attributFehltError($attribs, "parentid", "userInput");
					if ($foo)
						return $foo;

					if (!isset($_SESSION[$_flashmovieDataId])) {
						$_SESSION[$_flashmovieDataId] = array();
					}
					$_SESSION[$_flashmovieDataId]["parentid"] = weTag_getAttribute("parentid", $attribs, "0");
					//$_SESSION[$_imgDataId]["maxfilesize"] = weTag_getAttribute("maxfilesize",$attribs);
					$_SESSION[$_flashmovieDataId]["width"] = weTag_getAttribute("width", $attribs, 	0);
					$_SESSION[$_flashmovieDataId]["height"] = weTag_getAttribute("height", $attribs, 0);
					$_SESSION[$_flashmovieDataId]["id"] = $orgVal ? $orgVal : '';

					$bordercolor = weTag_getAttribute("bordercolor", $attribs, "#006DB8");
					$checkboxstyle = weTag_getAttribute("checkboxstyle", $attribs);
					$inputstyle = weTag_getAttribute("inputstyle", $attribs);
					$checkboxclass = weTag_getAttribute("checkboxclass", $attribs);
					$inputclass = weTag_getAttribute("inputclass", $attribs);
					$checkboxtext = weTag_getAttribute("checkboxtext", $attribs, g_l('parser','[delete]'));

					if ($_SESSION[$_flashmovieDataId]["id"]) {
						$attribs["id"] = $_SESSION[$_flashmovieDataId]["id"];
					}

					if (isset($_SESSION[$_flashmovieDataId]["serverPath"])) {
						$src = substr($_SESSION[$_flashmovieDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						// $flashmovieTag = 'Dadi'.'<img src="' . $src . '" alt="" width="' . $_SESSION[$_flashmovieDataId]["imgwidth"] . '" height="' . $_SESSION[$_flashmovieDataId]["imgheight"] . '" />';
						$flashmovieTag = '';
					} else {
						unset($attribs["width"]);
						unset($attribs["height"]);

						// Include Flash class
						if (isset($attribs["id"]) && $attribs["id"]){
							$flashmovieTag = $GLOBALS['we_doc']->getField($attribs, "flashmovie");
						} else {
							$flashmovieTag = '<img src="/webEdition/images/icons/no_flashmovie.gif" alt="" width="64" height="64" />';
						}
					}

					if (isset($_SESSION[$_flashmovieDataId]["doDelete"]) && $_SESSION[$_flashmovieDataId]["doDelete"]) {
						$checked = ' checked';
					} else {
						$checked = '';
					}

					return '<table border="0" cellpadding="2" cellspacing="2" style="border: solid ' . $bordercolor . ' 1px;">
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="center">' . $flashmovieTag . '
								<input type="hidden" name="WE_UI_FLASHMOVIE_DATA_ID_' . $name . '" value="' . $_flashmovieDataId . '" /></td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<input' . ($size ? ' size="' . $size . '"' : '') . ' name="' . $fieldname . '" type="file" accept="application/x-shockwave-flash"' . ($inputstyle ? (' style="' . $inputstyle . '"') : '') . ($inputclass ? (' class="' . $inputclass . '"') : '') . '/>
							</td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding-right: 5px;">
											<input style="border:0px solid black;" type="checkbox" id="WE_UI_DEL_CHECKBOX_' . $name . '" name="WE_UI_DEL_CHECKBOX_' . $name . '" value="1" ' . $checked . '/>
										</td>
										<td>
											<label for="WE_UI_DEL_CHECKBOX_' . $name . '"' . ($checkboxstyle ? (' style="' . $checkboxstyle . '"') : '') . ($checkboxclass ? (' class="' . $checkboxclass . '"') : '') . '>' . $checkboxtext . '</label>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
				} else {
					$hidden = '<input type="hidden" name="WE_UI_FLASHMOVIE_DATA_ID_' . $name . '" value="' . $_flashmovieDataId . '" />';

					if (isset($_SESSION[$_flashmovieDataId]["serverPath"])) {
						$src = substr($_SESSION[$_flashmovieDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						//return '<img src="' . $src . '" alt="" width="' . $_SESSION[$_flashmovieDataId]["imgwidth"] . '" height="' . $_SESSION[$_flashmovieDataId]["imgheight"] . '" />' . $hidden;
						return $hidden;
					} else
						if (isset($_SESSION[$_flashmovieDataId]["id"]) && $_SESSION[$_flashmovieDataId]["id"]) {

							if (isset($_SESSION[$_flashmovieDataId]["doDelete"]) && $_SESSION[$_flashmovieDataId]["doDelete"]) {
								return $hidden;
							}

							unset($attribs["width"]);
							unset($attribs["height"]);
							$attribs["id"] = $_SESSION[$_flashmovieDataId]["id"];
							return $GLOBALS['we_doc']->getField($attribs, "flashmovie") . $hidden;

						} else {
							return '';
						}
				}
			case "quicktime" :

				$_quicktimeDataId = isset($_REQUEST['WE_UI_QUICKTIME_DATA_ID_' . $name]) ? $_REQUEST['WE_UI_QUICKTIME_DATA_ID_' . $name] : md5(
						uniqid(rand()));


				if ($editable) {
					$foo = attributFehltError($attribs, "parentid", "userInput");
					if ($foo)
						return $foo;

					if (!isset($_SESSION[$_quicktimeDataId])) {
						$_SESSION[$_quicktimeDataId] = array();
					}
					$_SESSION[$_quicktimeDataId]["parentid"] = weTag_getAttribute("parentid", $attribs, "0");
					//$_SESSION[$_quicktimeDataId]["maxfilesize"] = weTag_getAttribute("maxfilesize",$attribs);
					$_SESSION[$_quicktimeDataId]["width"] = weTag_getAttribute("width", $attribs, 0);
					$_SESSION[$_quicktimeDataId]["height"] = weTag_getAttribute("height", $attribs, 0);
					$_SESSION[$_quicktimeDataId]["id"] = $orgVal ? $orgVal : '';

					$bordercolor = weTag_getAttribute("bordercolor", $attribs, "#006DB8");
					$checkboxstyle = weTag_getAttribute("checkboxstyle", $attribs);
					$inputstyle = weTag_getAttribute("inputstyle", $attribs);
					$checkboxclass = weTag_getAttribute("checkboxclass", $attribs);
					$inputclass = weTag_getAttribute("inputclass", $attribs);
					$checkboxtext = weTag_getAttribute("checkboxtext", $attribs, g_l('parser','[delete]'));

					if ($_SESSION[$_quicktimeDataId]["id"]) {
						$attribs["id"] = $_SESSION[$_quicktimeDataId]["id"];
					}

					if (isset($_SESSION[$_quicktimeDataId]["serverPath"])) {
						$src = substr($_SESSION[$_quicktimeDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						//$quicktimeTag = '<img src="' . $src . '" alt="" width="' . $_SESSION[$_quicktimeDataId]["imgwidth"] . '" height="' . $_SESSION[$_quicktimeDataId]["imgheight"] . '" />';
						$quicktimeTag = '';
					} else {
						unset($attribs["width"]);
						unset($attribs["height"]);
						if (isset($attribs["id"]) && $attribs["id"]){
							$quicktimeTag = $GLOBALS['we_doc']->getField($attribs, "quicktime");
						} else {
							$quicktimeTag = '<img src="/webEdition/images/icons/no_quicktime.gif" alt="" width="64" height="64" />';
						}
					}

					if (isset($_SESSION[$_quicktimeDataId]["doDelete"]) && $_SESSION[$_quicktimeDataId]["doDelete"]) {
						$checked = ' checked';
					} else {
						$checked = '';
					}

					return '<table border="0" cellpadding="2" cellspacing="2" style="border: solid ' . $bordercolor . ' 1px;">
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="center">' . $quicktimeTag . '
								<input type="hidden" name="WE_UI_QUICKTIME_DATA_ID_' . $name . '" value="' . $_quicktimeDataId . '" /></td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<input' . ($size ? ' size="' . $size . '"' : '') . ' name="' . $fieldname . '" type="file" accept="video/quicktime"' . ($inputstyle ? (' style="' . $inputstyle . '"') : '') . ($inputclass ? (' class="' . $inputclass . '"') : '') . '/>
							</td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding-right: 5px;">
											<input style="border:0px solid black;" type="checkbox" id="WE_UI_DEL_CHECKBOX_' . $name . '" name="WE_UI_DEL_CHECKBOX_' . $name . '" value="1" ' . $checked . '/>
										</td>
										<td>
											<label for="WE_UI_DEL_CHECKBOX_' . $name . '"' . ($checkboxstyle ? (' style="' . $checkboxstyle . '"') : '') . ($checkboxclass ? (' class="' . $checkboxclass . '"') : '') . '>' . $checkboxtext . '</label>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
				} else {
					$hidden = '<input type="hidden" name="WE_UI_QUICKTIME_DATA_ID_' . $name . '" value="' . $_quicktimeDataId . '" />';

					if (isset($_SESSION[$_quicktimeDataId]["serverPath"])) {
						$src = substr($_SESSION[$_quicktimeDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						//return '<img src="' . $src . '" alt="" width="' . $_SESSION[$_quicktimeDataId]["imgwidth"] . '" height="' . $_SESSION[$_quicktimeDataId]["imgheight"] . '" />' . $hidden;
						return $hidden;
					} else
						if (isset($_SESSION[$_quicktimeDataId]["id"]) && $_SESSION[$_quicktimeDataId]["id"]) {

							if (isset($_SESSION[$_quicktimeDataId]["doDelete"]) && $_SESSION[$_quicktimeDataId]["doDelete"]) {
								return $hidden;
							}

							unset($attribs["width"]);
							unset($attribs["height"]);
							$attribs["id"] = $_SESSION[$_quicktimeDataId]["id"];
							return $GLOBALS['we_doc']->getField($attribs, "quicktime") . $hidden;

						} else {
							return '';
						}
				}
			case "binary" :

				$_binaryDataId = isset($_REQUEST['WE_UI_BINARY_DATA_ID_' . $name]) ? $_REQUEST['WE_UI_BINARY_DATA_ID_' . $name] : md5(
						uniqid(rand()));

				if ($editable) {

					$foo = attributFehltError($attribs, "parentid", "userInput");
					if ($foo)
						return $foo;

					if (!isset($_SESSION[$_binaryDataId])) {
						$_SESSION[$_binaryDataId] = array();
					}
					$_SESSION[$_binaryDataId]["parentid"] = weTag_getAttribute("parentid", $attribs, "0");
					//$_SESSION[$_binaryDataId]["maxfilesize"] = weTag_getAttribute("maxfilesize",$attribs);

					$_SESSION[$_binaryDataId]["id"] = $orgVal ? $orgVal : '';

					$bordercolor = weTag_getAttribute("bordercolor", $attribs, "#006DB8");
					$checkboxstyle = weTag_getAttribute("checkboxstyle", $attribs);
					$inputstyle = weTag_getAttribute("inputstyle", $attribs);
					$checkboxclass = weTag_getAttribute("checkboxclass", $attribs);
					$inputclass = weTag_getAttribute("inputclass", $attribs);
					$checkboxtext = weTag_getAttribute("checkboxtext", $attribs, g_l('parser','[delete]'));

					if ($_SESSION[$_binaryDataId]["id"]) {
						$attribs["id"] = $_SESSION[$_binaryDataId]["id"];
					}

					if (isset($_SESSION[$_binaryDataId]["serverPath"])) {
						$src = substr($_SESSION[$_binaryDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}

						//$imgTag = '<img src="' . $src . '" alt=""  />';
						$imgTag = 'imgTag';
					} else {

						//$imgTag = $GLOBALS['we_doc']->getField($attribs, "img");
						$binaryTag = $GLOBALS['we_doc']->getField($attribs, "binary");
						$t=explode('_',$binaryTag[0]);
						unset($t[1]);
						unset($t[0]);
						$fn=implode('_',$t);
						$imgTag = '<a href="'.$binaryTag[1].'" target="_blank">'.$fn.'</a>';
					}

					if (isset($_SESSION[$_binaryDataId]["doDelete"]) && $_SESSION[$_binaryDataId]["doDelete"]) {
						$checked = ' checked';
					} else {
						$checked = '';
					}

					return '<table border="0" cellpadding="2" cellspacing="2" style="border: solid ' . $bordercolor . ' 1px;">
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="center">' . $imgTag . '
								<input type="hidden" name="WE_UI_BINARY_DATA_ID_' . $name . '" value="' . $_binaryDataId . '" /></td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<input' . ($size ? ' size="' . $size . '"' : '') . ' name="' . $fieldname . '" type="file" accept="application/*"' . ($inputstyle ? (' style="' . $inputstyle . '"') : '') . ($inputclass ? (' class="' . $inputclass . '"') : '') . '/>
							</td>
						</tr>
						<tr>
							<td class="weEditmodeStyle" colspan="2" align="left">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td style="padding-right: 5px;">
											<input style="border:0px solid black;" type="checkbox" id="WE_UI_DEL_CHECKBOX_' . $name . '" name="WE_UI_DEL_CHECKBOX_' . $name . '" value="1" ' . $checked . '/>
										</td>
										<td>
											<label for="WE_UI_DEL_CHECKBOX_' . $name . '"' . ($checkboxstyle ? (' style="' . $checkboxstyle . '"') : '') . ($checkboxclass ? (' class="' . $checkboxclass . '"') : '') . '>' . $checkboxtext . '</label>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
				} else {
					if (!isset($_SESSION[$_binaryDataId])) {
						$_SESSION[$_binaryDataId] = array();
					}
					$_SESSION[$_binaryDataId]["id"] = $orgVal ? $orgVal : '';
					if ($_SESSION[$_binaryDataId]["id"]) {
						$attribs["id"] = $_SESSION[$_binaryDataId]["id"];
					}
					$hidden = '<input type="hidden" name="WE_UI_BINARY_DATA_ID_' . $name . '" value="' . $_binaryDataId . '" />';

					if (isset($_SESSION[$_binaryDataId]["serverPath"])) {
						$src = substr($_SESSION[$_binaryDataId]["serverPath"], strlen($_SERVER['DOCUMENT_ROOT']));
						if (substr($src, 0, 1) !== "/") {
							$src = "/" . $src;
						}


						return "a".$hidden;
					} else {
						if (isset($_SESSION[$_binaryDataId]["id"]) && $_SESSION[$_binaryDataId]["id"]) {


							if (isset($_SESSION[$_binaryDataId]["doDelete"]) && $_SESSION[$_binaryDataId]["doDelete"]) {
								return "b".$hidden;
							}


							$attribs["id"] = $_SESSION[$_binaryDataId]["id"];
							$binaryTag = $GLOBALS['we_doc']->getField($attribs, "binary");
							$t=explode('_',$binaryTag[0]);
							unset($t[1]);
							unset($t[0]);
							$fn=implode('_',$t);
							$imgTag = '<a href="'.$binaryTag[1].'" target="_blank">'.$fn.'</a>';
							return $imgTag . $hidden;

						} else {
							return '';
						}
					}
					return '';
				}
			case "textarea" :
				$attribs['inlineedit'] = "true"; // bugfix: 7276
				$pure = weTag_getAttribute("pure", $attribs, false, true);
				if ($pure) {
					$atts = removeAttribs(
							$attribs,
							array(

									'wysiwyg',
									'commands',
									'pure',
									'type',
									'value',
									'checked',
									'autobr',
									'name',
									'values',
									'hidden',
									'editable',
									'format',
									'property',
									'size',
									'maxlength',
									'width',
									'height',
									'fontnames',
									'bgcolor'
							));
					return we_getTextareaField($fieldname, $content, $atts);
				} else {
					echo '<script language="JavaScript" type="text/javascript">weFrontpageEdit=true;</script>';
					include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/we/include/we_classes/html/we_forms.inc.php");
					include_once ($_SERVER['DOCUMENT_ROOT'] . "/webEdition/js/we_textarea_include.inc.php");
					$autobr = $autobrAttr ? "on" : "off";
					$showAutobr = isset($attribs["autobr"]);
					$charset = weTag_getAttribute("charset", $attribs, "iso-8859-1");
					return we_forms::weTextarea(
							$fieldname,
							$content,
							$attribs,
							$autobr,
							"autobr",
							$showAutobr,
							$GLOBALS['we_doc']->getHttpPath(),
							false,
							false,
							$xml,
							$removeFirstParagraph,
							$charset,
							false,
							true);
				}
			case "checkbox" :
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'cols',
								'rows',
								'width',
								'height',
								'bgcolor',
								'fontnames'
						));
				if ((!$isset) && $checked) {
					$content = 1;
				}
				return we_getInputCheckboxField($fieldname, $content, $atts);
			case 'date' :
				$currentdate = weTag_getAttribute("currentdate", $attribs, false, true);
				$minyear = weTag_getAttribute("minyear", $attribs);
				$maxyear = weTag_getAttribute("maxyear", $attribs);
				if ($currentdate) {
				if ($orgVal == 0|| $currentdate) {
					$orgVal = time();
				}
				if ($hidden) {
					$attsHidden=array(
						'type' => 'hidden',
						'name' => $fieldname,
						'value' => $orgVal ? $orgVal : time(),
						'xml' => $xml
						);
					return getHtmlTag('input', $attsHidden);
				} else {
					return we_html_tools::getDateInput2(
							"we_ui_" . (isset($GLOBALS["WE_FORM"]) ? $GLOBALS["WE_FORM"] : "") . "[we_date_" . $name . "]",
							($orgVal ? $orgVal : time()),
							false,
							$format,
							'',
							'',
							$xml,
							$minyear,
							$maxyear);
				}
				break;
			case "country":
				$newAtts = removeAttribs($attribs, array('wysiwyg','commands','pure', 'type', 'value', 'checked', 'autobr', 'name', 'values', 'hidden', 'editable', 'format', 'property', 'rows', 'cols','fontnames','bgcolor', 'width', 'height', 'maxlength'));
				$docAttr = weTag_getAttribute("doc", $attribs, "self");

				$doc = we_getDocForTag($docAttr);
				$lang=$doc->Language;
				if ($lang!=''){
					$langcode= substr($lang,0,2);
				} else {
					$langcode = we_core_Local::weLangToLocale($GLOBALS["WE_LANGUAGE"]);
				}

				$zendsupported = Zend_Locale::getTranslationList('territory', $langcode,2);
				if(defined("WE_COUNTRIES_TOP")) {
					$topCountries = explode(',',WE_COUNTRIES_TOP);
				} else {
					$topCountries = explode(',',"DE,AT,CH");
				}
				$topCountries = array_flip($topCountries);
				foreach ($topCountries as $countrykey => &$countryvalue){
					$countryvalue = Zend_Locale::getTranslation($countrykey,'territory',$langcode);
				}
				if(defined("WE_COUNTRIES_SHOWN")){
					$shownCountries = explode(',',WE_COUNTRIES_SHOWN);
				} else {
					$shownCountries = explode(',',"BE,DK,FI,FR,GR,IE,IT,LU,NL,PT,SE,ES,GB,EE,LT,MT,PL,SK,SI,CZ,HU,CY");
				}
				$shownCountries = array_flip($shownCountries);
				foreach ($shownCountries as $countrykey => &$countryvalue){
					$countryvalue = Zend_Locale::getTranslation($countrykey,'territory',$langcode);
				}
				$oldLocale= setlocale(LC_ALL, NULL);
				setlocale(LC_ALL, $lang.'.UTF-8');
				asort($topCountries,SORT_LOCALE_STRING );
				asort($shownCountries,SORT_LOCALE_STRING );
				setlocale(LC_ALL, $oldLocale);

				$options='';
				if(defined('WE_COUNTRIES_DEFAULT') && WE_COUNTRIES_DEFAULT !=''){
					$options.='<option value="--" ' . ($orgVal == '--' ? ' selected="selected">' : '>') .WE_COUNTRIES_DEFAULT . '</option>' . "\n";
				}
				foreach ($topCountries as $countrykey => &$countryvalue){
					$options.='<option value="'.$countrykey.'" '. ($orgVal == $countrykey ? ' selected="selected">': '>').CheckAndConvertISOfrontend($countryvalue).'</option>'."\n";
				}
				if( !empty($topCountries) && !empty($shownCountries) ) {
					$options.='<option value="-" disabled="disabled">----</option>'."\n";
				}
				foreach ($shownCountries as $countrykey2 => &$countryvalue2){
					$options.='<option value="'.$countrykey2.'" '. ($orgVal == $countrykey2 ? ' selected="selected">': '>').CheckAndConvertISOfrontend($countryvalue2).'</option>'."\n";
				}
				$newAtts['size'] = (isset($atts['size']) ? $atts['size'] : 1);
				$newAtts['name'] = $fieldname;
				return getHtmlTag('select', $newAtts, $options, true);
				break;
			case "language":
				$newAtts = removeAttribs($attribs, array('wysiwyg','commands','pure', 'type', 'value', 'checked', 'autobr', 'name', 'values', 'hidden', 'editable', 'format', 'property', 'rows', 'cols','fontnames','bgcolor', 'width', 'height', 'maxlength'));

				$docAttr = weTag_getAttribute("doc", $attribs, "self");
				$doc = we_getDocForTag($docAttr);
				$lang=$doc->Language;
				if ($lang!=''){
					$langcode= substr($lang,0,2);
				} else {
					$langcode = we_core_Local::weLangToLocale($GLOBALS["WE_LANGUAGE"]);
				}
				$frontendL = $GLOBALS["weFrontendLanguages"];
				foreach ($frontendL as $lc => &$lcvalue){
					$lccode = explode('_', $lcvalue);
					$lcvalue= $lccode[0];
				}
				foreach ($frontendL as &$lcvalue){
					$frontendLL[$lcvalue] = Zend_Locale::getTranslation($lcvalue,'language',$langcode);
				}

				$oldLocale= setlocale(LC_ALL, NULL);
				setlocale(LC_ALL, $lang.'.UTF-8');
				asort($frontendLL,SORT_LOCALE_STRING );
				setlocale(LC_ALL, $oldLocale);
				$options='';
				foreach ($frontendLL as $langkey => &$langvalue){
					$options.='<option value="'.$langkey.'" '. ($orgVal == $langkey ? ' selected="selected">': '>').CheckAndConvertISOfrontend($langvalue).'</option>'."\n";
				}
				$newAtts['size'] = (isset($atts['size']) ? $atts['size'] : 1);
				$newAtts['name'] = $fieldname;
				return getHtmlTag('select', $newAtts, $options, true);
				break;
			case "select" :
				$options = '';
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'rows',
								'cols',
								'fontnames',
								'bgcolor',
								'width',
								'height',
								'maxlength'
						));
				if ($values) {

					$values = explode(',', $values);

					foreach ($values as $txt) {

						if ($txt == $orgVal) {
							$attsOption = array(
								'selected' => 'selected'
							);
						} else {
							$attsOption = array();
						}
						$options .= getHtmlTag('option', $attsOption, trim($txt), true) . "\n";
					}
				} else
					if ($objekt && isset($objekt->DefArray["meta_" . $name])) {
						foreach ($objekt->DefArray["meta_" . $name]["meta"] as $key => $val) {

							if ($orgVal == $key) {
								$atts2 = array(
									'value' => $key, 'selected' => 'selected'
								);
							} else {
								$atts2 = array(
									'value' => $key
								);
							}
							$attsOption = array_merge($atts, $atts2);
							$attsOption = removeAttribs($attsOption, array(
								'class'
							));
							$options .= getHtmlTag('option', $attsOption, $val, true) . "\n";
						}
					}
				$atts['size'] = (isset($atts['size']) ? $atts['size'] : 1);
				$atts['name'] = $fieldname;
				return getHtmlTag('select', $atts, $options, true) . "\n";
				break;
			case "radio" :
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'rows',
								'cols',
								'width',
								'height',
								'bgcolor',
								'fontnames'
						));
				if (!$isset) {
					return we_getInputRadioField($fieldname, ($checked ? $value : $value . "dummy"), $value, $atts);
				} else {
					return we_getInputRadioField($fieldname, $content, $orgVal, $atts);
				}
			case "hidden" :
				$attsHidden=array(
					'type' => 'hidden',
					'name' => $fieldname,
					'value' => htmlspecialchars($content),
					'xml' => $xml,
					);
				return getHtmlTag('input', $attsHidden);
			case "choice" :
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'cols',
								'rows',
								'width',
								'height',
								'bgcolor',
								'fontnames',
								'maxlength'
						));
				$mode = weTag_getAttribute("mode", $attribs);
				return we_getInputChoiceField($fieldname, $orgVal, $values, $atts, $mode);
				break;
			case "password" :
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'cols',
								'rows',
								'width',
								'height',
								'bgcolor',
								'fontnames'
						));
				return we_getInputPasswordField($fieldname, $orgVal, $atts);
				break;
			case "textinput" :
			default :
				$atts = removeAttribs(
						$attribs,
						array(

								'wysiwyg',
								'commands',
								'pure',
								'type',
								'value',
								'checked',
								'autobr',
								'name',
								'values',
								'hidden',
								'editable',
								'format',
								'property',
								'cols',
								'rows',
								'width',
								'height',
								'bgcolor',
								'fontnames'
						));
				return we_getInputTextInputField($fieldname, $orgVal, $atts);
		}
	}
}
