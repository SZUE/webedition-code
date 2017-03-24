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
class we_tabs{
	private $container = '';

	public function addTab($text, $icon = '', $isActive = false, $jscmd = '', $attribs = []){
		$class = ($isActive ? 'tabActive' : 'tabNormal');
		$att = 'tilte="' . $text . '" ';
		if(isset($attribs) && is_array($attribs)){
			foreach($attribs as $key => $val){
				$att .= $key . '="' . $val . '" ';
			}
		}
		$this->container .= '<div ' . $att . ' onclick="weTabs.clickHandler(window,this,' . $jscmd . ');" class="' . $class . '"><span class="content">';
		$icons = explode(',', $icon) ?: ['fa-ambulance'];
		foreach($icons as $icon){
			$this->container .= '<i class="icon fa ' . $icon . '"></i>';
		}
		$this->container .= '<span class="text">' . $text . '</span></span></div>';
	}

	function getHTML(){
		return '<div id="tabContainer" name="tabContainer">' . $this->container . '</div>';
	}

}
