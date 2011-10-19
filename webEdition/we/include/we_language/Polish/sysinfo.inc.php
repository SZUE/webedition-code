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
 * @package    webEdition_language
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
/**
 * Language file: sysinfo.inc.php
 * Provides language strings for system info.
 * Language: Deutsch
 */

$l_sysinfo = array(
		'we_version' => 'webEdition version', // TRANSLATE
		'server_name' => 'Server name', // TRANSLATE
		'port' => 'Port',
		'protocol' => 'Protocol', // TRANSLATE
		'installation_folder' => 'Installation folder', // TRANSLATE
		'we_max_upload_size' => 'Max. upload file size', // TRANSLATE
		'php_version' => 'PHP version', // TRANSLATE
		'mysql_version' => 'MySql version', // TRANSLATE
		'more_info' => 'more info', // TRANSLATE
		'back' => 'back', // TRANSLATE
		'sysinfo' => 'System information', // TRANSLATE
		'zendframework_version' => 'Zend Framework version', // TRANSLATE
		'register_globals warning' => 'WARNING: register_globals can be a serious security risk for your system so we strongly recommend to turn off this feature!', // TRANSLATE
		'short_open_tag warning' => 'WARNUNG: short_open_tag can lead to severe problems with the processing of xml-files, i.e. for backup files. We strongly recommend to turn off this feature!!',
		'safe_mode warning' => 'Please deactivate the PHP Safe Mode if you experience problems during installation or update procedures.',
		'zend_framework warning' => 'You are currently using a different version of the Zend Framework than the recommended version %s.',
		'suhosin warning' => 'Due to the many configuration options of this PHP extension, we cannot guarenty the full functionality of webEdition.',
		'dbversion warning' => 'The database server reports the version %s, webEdition requires at least the  MySQL-Server version 5.0. webEdition may work with the used version, but this can not be guarented for new webEdition versions (i.e. after updates). For webEdition version 7,  MySQL version 5 will definitely be required. In addition: The installed MySQL version is outdated. There are no security updates available for this version, which may put the security of the whole system at risk!',
		'pcre warning' => 'Versions before 7.0 can lead to severe problems', // TRANSLATE
		'pcre_unkown' => 'Not detectable', // TRANSLATE
		'exif warning' => 'EXIF-Metadata for images are not available', // TRANSLATE
		'sdk_db warning' => 'SDK Operations and WE-APPS with database access are not available (required: PDO &amp; PDO_mysql)',//TRANSLATE
		'phpext warning' => 'not available: ', // TRANSLATE
		'phpext warning2' => 'Most likely, webEdition will not work properly!', // TRANSLATE
		'detectable warning' => 'Some of the software requirements can not be checked (Suhosin?). Please check the system requirements at http://www.webedition.org/de/webedition-cms/systemvoraussetzungen.php ', // TRANSLATE
		'connection_types' => 'Update connection types', // TRANSLATE
		'gdlib' => 'GDlib Support', // TRANSLATE
		'mbstring' => 'Multibyte String Functions', // TRANSLATE
		'version' => 'Version', // TRANSLATE
		'available' => 'available', // TRANSLATE
		'exif' => 'EXIF Support', // TRANSLATE
		'pcre' => 'PCRE-Extension', // TRANSLATE
		'sdk_db' => 'SDK/Apps DB support', // TRANSLATE
		'phpext' => 'Required PHP extensions', // TRANSLATE
		'not_set' => 'not set (off)',// TRANSLATE
		'suhosin simulation'=>'Simulation Mode',// TRANSLATE
	);
