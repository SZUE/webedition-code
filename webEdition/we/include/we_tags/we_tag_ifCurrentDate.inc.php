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
 * @return boolean
 * @param array $attribs
 * @param string $content
 * @desc returns true if calendar date is same with current date
 */
function we_tag_ifCurrentDate(){
	if(isset($GLOBALS['lv']->calendar_struct)){
		switch($GLOBALS['lv']->calendar_struct['calendar']){
			case 'day' :
				return (date('d-m-Y H', $GLOBALS['lv']->calendar_struct['date']) == date('d-m-Y H'));
				break;
			case 'month' :
			case 'month_table' :
				return (date('d-m-Y', $GLOBALS['lv']->calendar_struct['date']) == date('d-m-Y'));
				break;
			case 'year' :
				return (date('m-Y', $GLOBALS['lv']->calendar_struct['date']) == date('m-Y'));
				break;
		}
	}
	return false;
}
