<?php

/**
 * webEdition CMS
 *
 * $Rev: 3915 $
 * $Author: mokraemer $
 * $Date: 2012-01-30 17:34:27 +0100 (Mo, 30 Jan 2012) $
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

switch($_REQUEST['we_cmd'][0]){
	case 'edit_navigation_ifthere':
	case 'edit_navigation':
		$mod = 'navigation';
		$INCLUDE = 'we_modules/show_frameset.php';
		break;
	/*
	case 'openBannerDirselector':
		$INCLUDE = 'we_modules/banner/we_bannerDirSelectorFrameset.php';
		break;
	case 'openBannerSelector':
		$INCLUDE = 'we_modules/banner/we_bannerSelectorFrameset.php';
		break;
	case 'default_banner':
		$INCLUDE = 'we_modules/banner/we_defaultbanner.php';
		break;
	case 'banner_code':
		$INCLUDE = 'we_modules/banner/we_bannercode.php';
		break;
	 *
	 */
}

