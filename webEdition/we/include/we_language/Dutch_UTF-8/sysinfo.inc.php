<?php

/**
 * webEdition CMS
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

$_sysinfo['we_version'] = 'webEdition versie';
$_sysinfo['server_name'] = 'Server naam';
$_sysinfo['port'] ='Port' ;
$_sysinfo['protocol'] = 'Protocol'; // TRANSLATE
$_sysinfo['installation_folder'] = 'Installatie map';
$_sysinfo['we_max_upload_size'] = 'Max. upload bestandsgrootte';
$_sysinfo['php_version'] = 'PHP versie';
$_sysinfo['mysql_version'] = 'MySql versie';
$_sysinfo['more_info'] = 'meer info';
$_sysinfo['back'] = 'terug';
$_sysinfo['sysinfo'] = 'Systeem informatie';
$_sysinfo['zendframework_version'] = 'Zend Framework Version';// TRANSLATE
$_sysinfo["register_globals warning"] = 'WARNING: register_globals can be a serious security risk for your system so we strongly recommend to turn off this feature!';// TRANSLATE
$_sysinfo["short_open_tag warning"] = 'WARNUNG: short_open_tag can lead to severe problems with the processing of xml-files, i.e. for backup files. We strongly recommend to turn off this feature!!';
$_sysinfo["not_active"] = 'not active';
$_sysinfo["not_set"] = 'not set (off)';
$_sysinfo["suhosin simulation"]='Simulation Mode';
$_sysinfo["safe_mode warning"] = 'Please deactivate the PHP Safe Mode if you experience problems during installation or update procedures.';// TRANSLATE
$_sysinfo["zend_framework warning"] = 'You are currently using a different version of the Zend Framework than the recommended version '.WE_ZFVERSION.'.';// TRANSLATE
$_sysinfo["suhosin warning"] = 'Due to the many configuration options of this PHP extension, we cannot guarenty the full functionality of webEdition.';// TRANSLATE
$_sysinfo["dbversion warning"] = 'The database server reports the version %s, webEdition requires at least the  MySQL-Server version 5.0. webEdition may work with the used version, but this can not be guarented for new webEdition versions (i.e. after updates). For webEdition version 7,  MySQL version 5 will definitely be required. In addition: The installed MySQL version is outdated. There are no security updates available for this version, which may put the security of the whole system at risk!';// TRANSLATE
$_sysinfo["pcre warning"] = 'Versions before 7.0 can lead to severe problems';// TRANSLATE
$_sysinfo["pcre_unkown"] = 'Not detectable';// TRANSLATE
$_sysinfo["exif warning"] = 'EXIF-Metadata for images are not available';// TRANSLATE
$_sysinfo['sdk_db warning'] = 'SDK Operations and WE-APPS with database access are not available (required: PDO &amp; PDO_mysql)';// TRANSLATE
$_sysinfo['phpext warning'] = 'not available: ';// TRANSLATE
$_sysinfo['phpext warning2'] = 'Most likely, webEdition will not work properly!';// TRANSLATE
$_sysinfo['detectable warning'] = 'Some of the software requirements can not be checked (Suhosin?). Please check the system requirements athttp://www.webedition.org/de/webedition-cms/systemvoraussetzungen.php ';// TRANSLATE

$_sysinfo['connection_types'] = 'Bijwerken connectie types';
$_sysinfo['gdlib'] = 'GDlib ondersteuning';
$_sysinfo['mbstring'] = 'Multibyte String Functions'; // TRANSLATE
$_sysinfo['version'] = 'Versie';
$_sysinfo['available'] = 'beschikbaar';
$_sysinfo['exif'] = 'EXIF Support'; // TRANSLATE
$_sysinfo['pcre'] = 'PCRE-Extension'; // TRANSLATE
$_sysinfo['sdk_db'] = 'SDK/Apps DB support'; // TRANSLATE
$_sysinfo['phpext'] = 'Required PHP extensions'; // TRANSLATE
?>