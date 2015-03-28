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

/**
 * Class we_widget()
 *
 * Use this class to add a widget to the Cockpit.
 */
abstract class we_base_widget{
	static $js = '';

	/**
	 * To add a widget give a unique id ($iId). Currently supported widget types ($sType) are Shortcuts (sct), RSS Reader (rss),
	 * Last modified (mfd), ToDo/Messaging (msg), Users Online (usr), and Unpublished docs and objs (ubp).
	 *
	 * @param      int $iId
	 * @param      string $sType
	 * @param      object $oContent
	 * @param      array $aLabel
	 * @param      string $sCls
	 * @param      int $iRes
	 * @param      string $sCsv
	 * @param      int $w
	 * @param      int $h
	 * @param      bool $resize
	 * @return     object Returns the we_html_table object
	 */
	static function create($iId, $sType, $oContent, $aLabel = array("", ""), $sCls = "white", $iRes = 0, $sCsv = "", $w = 0, $h = 0, $resize = true){
		$w_i0 = 10;
		$w_i1 = 5;
		$w_icon = (3 * $w_i0) + (2 * $w_i1);
		$h_i0 = 10;
		$show_seizer = false;
		$gap = 10;
		$w+=22;

		$oDrag = new we_html_table(array("id" => $iId . "_h", "style" => "width:100%"), 1, 1);
		$oDrag->setCol(0, 0, array("width" => $w_icon), $show_seizer ? we_html_element::htmlImg(array("src" => IMAGE_DIR . "pd/tb_seizer.gif")) : we_html_tools::getPixel('100%', 16));
		//$oDrag->setCol(0, 1, array("id" => $iId . "_lbl_old", "align" => "center", "class" => "label", "style" => "width:100%;"), "");

		$oIco_prc = new we_html_table(array(), 1, 3);
		$oIco_prc->setCol(0, 0, array("width" => $w_i0, "valign" => "middle", 'style' => 'padding-right:5px;'), we_html_element::htmlA(array("id" => $iId . "_props", "href" => "#", "onclick" => "propsWidget('" . $sType . "','" . $iId . "',gel('" . $iId . "_csv').value);this.blur();"), we_html_element::htmlImg(array("src" => IMAGE_DIR . "pd/tb_props.gif", "width" => $w_i0, "height" => $h_i0, "border" => 0, "title" => g_l('cockpit', '[properties]')))));
		$oIco_prc->setCol(0, 1, array("width" => $w_i0, "valign" => "middle", 'style' => 'padding-right:5px;'), we_html_element::htmlA(array("id" => $iId . "_resize", "href" => "#", "onclick" => "resizeWidget('" . $iId . "');this.blur();"), we_html_element::htmlImg(array("id" => $iId . "_icon_resize", "src" => IMAGE_DIR . "pd/tb_resize.gif", "width" => $w_i0, "height" => $h_i0, "border" => 0, "title" => g_l('cockpit', ($iRes == 0 ? '[increase_size]' : '[reduce_size]'))))));
		$oIco_prc->setCol(0, 2, array("width" => $w_i0, "valign" => "middle"), we_html_element::htmlA(array("id" => $iId . "_remove", "href" => "#", "onclick" => "removeWidget('" . $iId . "');this.blur();"), we_html_element::htmlImg(array("src" => IMAGE_DIR . "pd/tb_close.gif", "width" => $w_i0, "height" => $h_i0, "border" => 0, "title" => g_l('cockpit', '[close]')))));

		$oIco_pc = new we_html_table(array(), 1, 2);
		$oIco_pc->setCol(0, 0, array("width" => $w_i0, "valign" => "middle", 'style' => 'padding-left:15px;padding-right:5px;'), we_html_element::htmlA(array("id" => $iId . "_props", "href" => "#", "onclick" => "propsWidget('" . $sType . "','" . $iId . "',gel('" . $iId . "_csv').value);this.blur();"), we_html_element::htmlImg(array("src" => IMAGE_DIR . "pd/tb_props.gif", "width" => $w_i0, "height" => $h_i0, "border" => 0, "title" => g_l('cockpit', '[properties]')))));
		$oIco_pc->setCol(0, 1, array("width" => $w_i0, "valign" => "middle"), we_html_element::htmlA(array("id" => $iId . "_remove", "href" => "#", "onclick" => "removeWidget('" . $iId . "');this.blur();"), we_html_element::htmlImg(array("src" => IMAGE_DIR . "pd/tb_close.gif", "width" => $w_i0, "height" => $h_i0, "border" => 0, "title" => g_l('cockpit', '[close]')))));

		$ico_obj = ($resize ? 'oIco_prc' : 'oIco_pc');
		$sIco = ($sType != "_reCloneType_") ? $$ico_obj->getHtml() :
			we_html_element::htmlDiv(array("id" => $iId . "_ico_prc", "style" => "display:block;"), $oIco_prc->getHtml()) .
			we_html_element::htmlDiv(array("id" => $iId . "_ico_pc", "style" => "display:none;"), $oIco_pc->getHtml());

		$oTb = new we_html_table(array("id" => $iId . "_tb", 'class' => 'widget_controls'), 1, 2);
		$oTb->setCol(0, 0, array(), $oDrag->getHtml());
		$oTb->setCol(0, 1, array("width" => $w_icon), $sIco);

		if($iId != 'clone'){
			self::$js.="setLabel('" . $iId . "','" . str_replace("'", "\'", $aLabel[0]) . "','" . str_replace("'", "\'", $aLabel[1]) . "');" .
				"initWidget('" . $iId . "');";
		}
		return we_html_element::htmlDiv(
				array("id" => $iId . "_bx", "style" => "width:" . $w . "px;", "class" => 'widget bgc_' . $sCls), $oTb->getHtml() .
				we_html_element::htmlDiv(array("id" => $iId . "_lbl", "class" => "label widgetTitle widgetTitle_" . $sCls,)) .
				we_html_element::htmlDiv(array("id" => $iId . "_wrapper", "class" => "content"), we_html_element::htmlDiv(array("id" => $iId . "_content"), ((isset($oContent)) ? $oContent->getHtml() : "")) .
					we_html_element::htmlHidden($iId . '_prefix', $aLabel[0], $iId . '_prefix') .
					we_html_element::htmlHidden($iId . '_postfix', $aLabel[1], $iId . '_postfix') .
					we_html_element::htmlHidden($iId . '_res', $iRes, $iId . '_res') .
					we_html_element::htmlHidden($iId . '_type', $sType, $iId . '_type') .
					we_html_element::htmlHidden($iId . '_cls', $sCls, $iId . '_cls') .
					we_html_element::htmlHidden($iId . '_csv', $sCsv, $iId . '_csv')
				)
		);
	}

	public static function getJs(){
		return we_html_element::jsElement(self::$js);
	}

}
