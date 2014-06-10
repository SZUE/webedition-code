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
function we_tag_ifFemale(){
	if(isset($GLOBALS['we_editmode']) && $GLOBALS['we_editmode']){
		return true;
	}
	if(isset($GLOBALS['WE_SALUTATION']) && $GLOBALS['WE_SALUTATION']){
		$femaleSalutation = f('SELECT pref_value FROM ' . NEWSLETTER_PREFS_TABLE . ' WHERE pref_name="' . we_newsletter_base::FEMALE_SALUTATION_FIELD . '"', 'pref_value', $GLOBALS['DB_WE']);
		if($femaleSalutation == ''){
			$femaleSalutation = g_l('modules_newsletter', '[default][female]');
		}
		return ($GLOBALS['WE_SALUTATION'] == $femaleSalutation);
	}
	return false;
}
