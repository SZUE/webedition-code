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
class we_tab{

	var $tab;

	function __construct($href, $text, $status = 'TAB_NORMAL', $jscmd = '', $attribs = array()){
		$class = $status == 'TAB_ACTIVE' ? "tabActive" : "tabNormal";
		$att = "";
		if(isset($attribs) && is_array($attribs)){
			foreach($attribs as $key => $val){
				$att .= $key . '="' . $val . '" ';
			}
		}


		switch(we_base_browserDetect::inst()->getBrowser()){
			case we_base_browserDetect::SAFARI:
				$this->tab = '<div ' . $att . ' onclick="if ( allowed_change_edit_page() ){ setTabClass(this); ' . $jscmd . '}" class="' . $class . '"><nobr><span class="spacer">&nbsp;&nbsp;</span><span class="text">' . $text . '</span>&nbsp;&nbsp;<img src="' . IMAGE_DIR . 'multiTabs/tabBorder.gif" height="21" style="vertical-align:bottom;" /></nobr><span><img src="' . IMAGE_DIR . 'pixel.gif" height="0" /></span></div>';
				break;
			case we_base_browserDetect::IE:
				$this->tab = '<div ' . $att . ' onclick="if ( allowed_change_edit_page() ){ setTabClass(this); ' . $jscmd . '}" class="' . $class . '"><nobr><span class="spacer">&nbsp;&nbsp;</span><span class="text">' . $text . '</span>&nbsp;&nbsp;<img src="' . IMAGE_DIR . 'multiTabs/tabBorder.gif" height="21" style="vertical-align:bottom;" /></nobr></div>';
				break;
			default:
				if(we_base_browserDetect::isMAC()){
					$this->tab = '<div ' . $att . ' onclick="if ( allowed_change_edit_page() ){ setTabClass(this); ' . $jscmd . '}" class="' . $class . '"><nobr><span class="spacer">&nbsp;&nbsp;</span><span class="text">' . $text . '</span>&nbsp;&nbsp;<img src="' . IMAGE_DIR . 'multiTabs/tabBorder.gif" height="21" style="vertical-align:bottom;" /></nobr></div>';
				} elseif(we_base_browserDetect::isUNIX()){
					$this->tab = '<div ' . $att . ' onclick="if ( allowed_change_edit_page() ){ setTabClass(this); ' . $jscmd . '}" class="' . $class . '"><nobr><span class="spacer">&nbsp;&nbsp;</span><span class="text">' . $text . '</span>&nbsp;&nbsp;<img src="' . IMAGE_DIR . 'multiTabs/tabBorder.gif" height="21" style="vertical-align:bottom;" /></nobr></div>';
				} else{
					$this->tab = '<div ' . $att . ' onclick="if ( allowed_change_edit_page() ){ setTabClass(this); ' . $jscmd . '}" class="' . $class . '"><nobr><span class="spacer">&nbsp;&nbsp;</span><span class="text">' . $text . '</span>&nbsp;&nbsp;<img src="' . IMAGE_DIR . 'multiTabs/tabBorder.gif" height="21" style="vertical-align:bottom;" /></nobr></div>';
				}
		}
	}

	function getHTML(){
		return $this->tab;
	}

}
