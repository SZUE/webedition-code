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
$seeMode = !(isset($_SESSION['weS']['we_mode']) && $_SESSION['weS']['we_mode'] == we_base_constants::MODE_NORMAL);
$we_menu = array(
	'file_new' => array(// File > New
		'text' => g_l('javaMenu_global', '[new]'),
	//'parent' => 'file',
	),
	'file_new_wedoc' => array(// File > New > webEdition Document
		'text' => g_l('javaMenu_global', '[webEdition_page]'),
		'parent' => 'file_new',
		'perm' => 'NEW_WEBEDITIONSITE',
	),
	array(// File > New > webEdition Document > empty page
		'text' => g_l('javaMenu_global', '[empty_page]'),
		'parent' => 'file_new_wedoc',
		'cmd' => 'new_webEditionPage',
		'perm' => 'NO_DOCTYPE',
	),
	array(// separator
		'parent' => 'file_new_wedoc',
		'hide' => !$seeMode,
	),
	'file_new_weobj' => array(// File > new > Object
		'text' => g_l('javaMenu_object', '[object]'),
		'parent' => 'file_new',
		'perm' => 'NEW_OBJECTFILE',
		'hide' => !defined('OBJECT_TABLE')
	),
	'file_new_media' => array(
		'text' => g_l('javaMenu_global', '[media]'),
		'parent' => 'file_new',
		'perm' => 'ADMINISTRATOR || NEW_GRAFIK || NEW_FLASH || NEW_QUICKTIME || NEW_SONSTIGE',
	),
	array(// File > Image
		'text' => g_l('javaMenu_global', '[image]'),
		'parent' => 'file_new_media',
		'cmd' => 'new_image',
		'perm' => 'NEW_GRAFIK',
	),
	array(// File > New > Other > Other (Binary)
		'text' => g_l('javaMenu_global', '[pdf]'),
		'parent' => 'file_new_media',
		'cmd' => 'new_binary_document',
		'perm' => 'NEW_SONSTIGE',
	),
	array(// File > New > Other > Flash
		'text' => g_l('javaMenu_global', '[flash_movie]'),
		'parent' => 'file_new_media',
		'cmd' => 'new_flash_movie',
		'perm' => 'NEW_FLASH',
	),
	array(// File > New Other > quicktime
		'text' => g_l('javaMenu_global', '[quicktime_movie]'),
		'parent' => 'file_new_media',
		'cmd' => 'new_quicktime_movie',
		'perm' => 'NEW_QUICKTIME',
	),
	array(// File > New Other > video
		'text' => g_l('contentTypes', '[' . we_base_ContentTypes::VIDEO . ']'),
		'parent' => 'file_new_media',
		'cmd' => 'new_video_movie',
		'perm' => 'NEW_FLASH',
	),
	array(// File > New Other > audio
		'text' => g_l('contentTypes', '[' . we_base_ContentTypes::AUDIO . ']'),
		'parent' => 'file_new_media',
		'cmd' => 'new_audio_audio',
		'perm' => 'NEW_SONSTIGE',
	),
	'file_new_other' => array(// File > New > Other
		'text' => g_l('javaMenu_global', '[other]'),
		'parent' => 'file_new',
		'perm' => 'ADMINISTRATOR || NEW_HTML || NEW_JS || NEW_CSS || NEW_TEXT || NEW_HTACCESS || NEW_SONSTIGE',
	),
	array(// File > New > Other > html
		'text' => g_l('javaMenu_global', '[html_page]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_html_page',
		'perm' => 'NEW_HTML',
	),
	array(// File > New > Other > Javascript
		'text' => g_l('javaMenu_global', '[javascript]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_javascript',
		'perm' => 'NEW_JS',
	),
	array(// File > New > Other > CSS
		'text' => g_l('javaMenu_global', '[css_stylesheet]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_css_stylesheet',
		'perm' => 'NEW_CSS',
	),
	array(// File > New > Other > Text
		'text' => g_l('javaMenu_global', '[text_plain]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_text_plain',
		'perm' => 'NEW_TEXT',
	),
	array(// File > New > Other > XML
		'text' => g_l('javaMenu_global', '[text_xml]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_text_xml',
		'perm' => 'NEW_TEXT',
	),
	array(// File > New > Other > htaccess
		'text' => g_l('javaMenu_global', '[htaccess]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_text_htaccess',
		'perm' => 'NEW_HTACCESS',
	),
	array(// File > New > Other > Other (Binary)
		'text' => g_l('javaMenu_global', '[other_files]'),
		'parent' => 'file_new_other',
		'cmd' => 'new_binary_document',
		'perm' => 'NEW_SONSTIGE',
	),
	array(// separator
		'parent' => 'file_new',
		'hide' => $seeMode
	),
	'file_new_dir' => array(// File > New > Directory
		'text' => g_l('javaMenu_global', '[directory]'),
		'parent' => 'file_new',
		'hide' => $seeMode,
		'perm' => 'NEW_DOC_FOLDER || NEW_TEMP_FOLDER || NEW_OBJECTFILE_FOLDER || NEW_COLLECTION_FOLDER',
	),
	array(// File > New > Directory > Document
		'text' => g_l('javaMenu_global', '[document_directory]'),
		'parent' => 'file_new_dir',
		'cmd' => 'new_document_folder',
		'perm' => 'NEW_DOC_FOLDER',
		'hide' => $seeMode
	),
	array(// File > New > Directory > Template
		'text' => g_l('javaMenu_global', '[template_directory]'),
		'parent' => 'file_new_dir',
		'cmd' => 'new_template_folder',
		'perm' => 'NEW_TEMP_FOLDER',
		'hide' => $seeMode
	),
	array(// File > new > directory > objectfolder
		'text' => g_l('javaMenu_object', '[object_directory]'),
		'parent' => 'file_new_dir',
		'cmd' => 'new_objectfile_nested_folder',
		'perm' => 'NEW_OBJECTFILE_FOLDER',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	array(// File > New > Directory > Collection
		'text' => g_l('javaMenu_global', '[collection_directory]'),
		'parent' => 'file_new_dir',
		'cmd' => 'new_collection_folder',
		'perm' => 'NEW_COLLECTION_FOLDER',
		'hide' => $seeMode || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	array(// separator
		'parent' => 'file_new',
		'perm' => 'NEW_OBJECT || NEW_TEMPLATE',
		'hide' => $seeMode
	),
	array(// File > New > Template
		'text' => g_l('javaMenu_global', '[template]'),
		'parent' => 'file_new',
		'cmd' => 'new_template',
		'perm' => 'NEW_TEMPLATE',
		'hide' => $seeMode
	),
	array(// File > new > Class
		'text' => g_l('javaMenu_object', '[class]'),
		'parent' => 'file_new',
		'cmd' => 'new_object',
		'perm' => 'NEW_OBJECT',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	array(// separator
		'parent' => 'file_new',
		'perm' => 'NEW_COLLECTION',
		'hide' => $seeMode || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	array(// File > COLLECTION
		'text' => g_l('javaMenu_global', '[collection]'),
		'parent' => 'file_new',
		'cmd' => 'new_collection',
		'perm' => 'NEW_COLLECTION',
		'hide' => !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	'file' => array(// File
		'text' => g_l('javaMenu_global', '[file]'),
	),
	/* 	$we_menu[1011100]['parent'] = 'file_new'; // separator
	  // File > New > Wizards
	  'text'=> g_l('javaMenu_global', '[wizards]') . '&hellip;',
	  'parent'=> 'file_new',

	  // File > New > Wizard > First Steps Wizard
	  'text'=> g_l('javaMenu_global', '[first_steps_wizard]'),
	  'parent'=> 1011200,
	  'cmd'=> 'openFirstStepsWizardMasterTemplate',
	  'perm'=> 'ADMINISTRATOR',

	  $we_menu[1020000]['parent'] = 'file'; // separator
	 */
	'file_open' => array(// File > Open
		'text' => g_l('javaMenu_global', '[open]'),
		'parent' => 'file',
	),
	array(// File > Open > Document
		'text' => g_l('javaMenu_global', '[open_document]') . '&hellip;',
		'parent' => 'file_open',
		'cmd' => 'open_document',
		'perm' => 'CAN_SEE_DOCUMENTS',
	),
	array(// File > open > Object
		'text' => g_l('javaMenu_object', '[open_object]') . '&hellip;',
		'parent' => 'file_open',
		'cmd' => 'open_objectFile',
		'perm' => 'CAN_SEE_OBJECTFILES',
		'hide' => !defined('OBJECT_TABLE')
	),
	array(// File > Open > Collection
		'text' => g_l('javaMenu_global', '[collection]') . '&hellip;',
		'parent' => 1030000,
		'cmd' => 'open_collection',
		'perm' => 'CAN_SEE_COLLECTIONS',
		'hide' => !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	array(// separator
		'parent' => 'file_open',
		'perm' => 'CAN_SEE_TEMPLATES || CAN_SEE_OBJECTS',
		'hide' => $seeMode
	),
	array(// File > Open > Template
		'text' => g_l('javaMenu_global', '[open_template]') . '&hellip;',
		'parent' => 'file_open',
		'cmd' => 'open_template',
		'perm' => 'CAN_SEE_TEMPLATES',
		'hide' => $seeMode
	),
	array(// File > Open > Class
		'text' => g_l('javaMenu_object', '[open_class]') . '&hellip;',
		'parent' => 'file_open',
		'cmd' => 'open_object',
		'perm' => 'CAN_SEE_OBJECTS',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	'file_delete' => array(// File > Delete
		'text' => g_l('javaMenu_global', '[delete]') . ($seeMode ? '&hellip;' : ''),
		'parent' => 'file',
		'cmd' => $seeMode ? 'we_selector_delete' : '',
		'perm' => $seeMode ? 'DELETE_DOCUMENT' : 'DELETE_DOCUMENT || DELETE_OBJECTFILE || DELETE_TEMPLATE || DELETE_OBJECT',
	),
	array(// File > Delete > Documents
		'text' => g_l('javaMenu_global', '[documents]'),
		'parent' => 'file_delete',
		'cmd' => 'delete_documents',
		'perm' => 'DELETE_DOCUMENT',
		'hide' => $seeMode,
	),
	array(// File > Delete > Objects
		'text' => g_l('javaMenu_object', '[objects]'),
		'parent' => 'file_delete',
		'cmd' => 'delete_objectfile',
		'perm' => 'DELETE_OBJECTFILE',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	array(// separator
		'parent' => 'file_delete',
		'perm' => 'CAN_SEE_TEMPLATES || CAN_SEE_OBJECTS',
		'hide' => $seeMode
	),
	array(// File > Delete > Templates
		'text' => g_l('javaMenu_global', '[templates]'),
		'parent' => 'file_delete',
		'cmd' => 'delete_templates',
		'perm' => 'DELETE_TEMPLATE',
		'hide' => $seeMode,
	),
	array(// File > Delete > Classes
		'text' => g_l('javaMenu_object', '[classes]'),
		'parent' => 'file_delete',
		'cmd' => 'delete_object',
		'perm' => 'DELETE_OBJECT',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	array(// separator
		'parent' => 'file_delete',
		'perm' => 'DELETE_COLLECTION',
		'hide' => $seeMode
	),
	array(// File > Delete > Collection
		'text' => g_l('global', '[vfile]'),
		'parent' => 'file_delete',
		'cmd' => 'delete_collections',
		'perm' => 'DELETE_COLLECTION',
		'hide' => $seeMode || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION),
	),
	'file_mv' => array(// File > Move
		'text' => g_l('javaMenu_global', '[move]'),
		'parent' => 'file',
		'hide' => $seeMode,
		'perm' => 'MOVE_DOCUMENT || MOVE_OBJECTFILE || MOVE_TEMPLATE',
	),
// File > Move > Documents
	array(
		'text' => g_l('javaMenu_global', '[documents]'),
		'parent' => 'file_mv',
		'cmd' => 'move_documents',
		'perm' => 'MOVE_DOCUMENT',
		'hide' => $seeMode,
	),
	array(// File > move > objects
		'text' => g_l('javaMenu_object', '[objects]'),
		'parent' => 'file_mv',
		'cmd' => 'move_objectfile',
		'perm' => 'MOVE_OBJECTFILE',
		'hide' => !defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL)
	),
	array(// separator
		'parent' => 'file_mv',
		'perm' => 'MOVE_TEMPLATE',
		'hide' => $seeMode
	),
	array(// File > Move > Templates
		'text' => g_l('javaMenu_global', '[templates]'),
		'parent' => 'file_mv',
		'cmd' => 'move_templates',
		'perm' => 'MOVE_TEMPLATE',
		'hide' => $seeMode,
	),
	'file_addcoll' => array(// File > add to collection
		'text' => g_l('javaMenu_global', '[add_to_collection]'),
		'parent' => 'file',
		'hide' => $seeMode,
		'perm' => 'SAVE_COLLECTION',
		'hide' => $seeMode || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	array(// File > add to collection > documents
		'text' => g_l('javaMenu_global', '[documents]'),
		'parent' => 'file_addcoll',
		'cmd' => 'add_documents_to_collection',
		'perm' => 'SAVE_COLLECTION',
		'hide' => $seeMode || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION),
	),
	array(/// File > add to collection > objects
		'text' => g_l('javaMenu_object', '[objects]'),
		'parent' => 'file_addcoll',
		'cmd' => 'add_objectfiles_to_collection',
		'perm' => 'SAVE_COLLECTION',
		'hide' => true, //!defined('OBJECT_TABLE') || ($_SESSION['weS']['we_mode'] != we_base_constants::MODE_NORMAL) || !we_base_moduleInfo::isActive(we_base_moduleInfo::COLLECTION)
	),
	array(// separator
		'parent' => 'file'
	),
	/*array(
		'text' => g_l('javaMenu_glossary', '[glossary_check]'),
		'parent' => 'file',
		'cmd' => 'glossary_check',
		'hide' => !(defined('GLOSSARY_TABLE'))
	),*/
	array(// File > Delete Active Document
		'text' => g_l('javaMenu_global', '[delete_active_document]'),
		'parent' => 'file',
		'cmd' => 'delete_single_document_question',
		'perm' => 'DELETE_DOCUMENT || DELETE_OBJECTFILE || DELETE_TEMPLATE || DELETE_OBJECT',
		'hide' => $seeMode
	),
	/* array(// File > Close
	  'text' => g_l('javaMenu_global', '[close_single_document]'),
	  'parent' => 'file',
	  'cmd' => 'close_document',
	  ), */
	array(// File > Close All
		'text' => g_l('javaMenu_global', '[close_all_documents]'),
		'parent' => 'file',
		'cmd' => 'close_all_documents',
		'hide' => $seeMode
	),
	array(// File > Close All But this
		'text' => g_l('javaMenu_global', '[close_all_but_active_document]'),
		'parent' => 'file',
		'cmd' => 'close_all_but_active_document',
		'hide' => $seeMode
	),
	array(// separator
		'parent' => 'file'
	),
	array(// File > unpublished pages
		'text' => g_l('javaMenu_global', '[unpublished_pages]') . '&hellip;',
		'parent' => 'file',
		'cmd' => 'openUnpublishedPages',
		'perm' => 'CAN_SEE_DOCUMENTS',
	),
	array(// File > unpublished objects
		'text' => g_l('javaMenu_object', '[unpublished_objects]') . '&hellip;',
		'parent' => 'file',
		'cmd' => 'openUnpublishedObjects',
		'perm' => 'CAN_SEE_OBJECTFILES',
		'hide' => !defined('OBJECT_TABLE')
	),
	array(// File > Search
		'text' => g_l('javaMenu_global', '[search]') . '&hellip;',
		'parent' => 'file',
		'cmd' => 'tool_weSearch_edit',
	),
	array(// separator
		'parent' => 'file',
	),
	'file_imex' => array(// File > Import/Export
		'text' => g_l('javaMenu_global', '[import_export]'),
		'parent' => 'file',
		'perm' => 'GENERICXML_EXPORT || CSV_EXPORT || FILE_IMPORT || SITE_IMPORT || GENERICXML_IMPORT || CSV_IMPORT || WXML_IMPORT',
	),
	array(// File > Import/Export > Import
		'text' => g_l('javaMenu_global', '[import]') . '&hellip;',
		'cmd' => 'import',
		'parent' => 'file_imex',
		'perm' => 'FILE_IMPORT || SITE_IMPORT || GENERICXML_IMPORT || CSV_IMPORT || WXML_IMPORT',
	),
	array(// File > Import/Export > Export
		'text' => g_l('javaMenu_global', '[export]') . '&hellip;',
		'cmd' => 'export',
		'parent' => 'file_imex',
		'perm' => 'GENERICXML_EXPORT || CSV_EXPORT',
	),
	'file_backup' => array(// File > Backup
		'text' => g_l('javaMenu_global', '[backup]'),
		'parent' => 'file',
		'hide' => $seeMode,
		'perm' => 'BACKUPLOG || IMPORT || EXPORT || EXPORTNODOWNLOAD',
	),
	array(// File > Backup > make
		'text' => g_l('javaMenu_global', '[make_backup]') . '&hellip;',
		'parent' => $seeMode ? 'file' : 'file_backup',
		'cmd' => 'make_backup',
		'perm' => 'EXPORT || EXPORTNODOWNLOAD',
	),
	array(// File > Backup > recover
		'text' => g_l('javaMenu_global', '[recover_backup]') . '&hellip;',
		'parent' => 'file_backup',
		'cmd' => 'recover_backup',
		'perm' => 'IMPORT',
		'hide' => $seeMode
	),
	array(// File > Backup > view Log
		'text' => g_l('javaMenu_global', '[view_backuplog]') . '&hellip;',
		'parent' => $seeMode ? 'file' : 'file_backup',
		'cmd' => 'view_backuplog',
		'perm' => 'BACKUPLOG',
	),
	array(// File > rebuild
		'text' => g_l('javaMenu_global', '[rebuild]') . '&hellip;',
		'parent' => 'file',
		'cmd' => 'rebuild',
		'perm' => 'REBUILD',
	),
	array(// File > Browse server
		'text' => g_l('javaMenu_global', '[browse_server]') . '&hellip;',
		'parent' => 'file',
		'cmd' => 'browse_server',
		'perm' => 'BROWSE_SERVER',
		'hide' => $seeMode,
	),
	array(// separator
		'parent' => 'file',
		'perm' => 'BROWSE_SERVER',
		'hide' => $seeMode,
	),
	array(// File > Quit
		'text' => g_l('javaMenu_global', '[quit]'),
		'parent' => 'file',
		'cmd' => 'dologout',
	),
	'cockpit' => array(// Cockpit
		'text' => g_l('global', '[cockpit]'),
		'perm' => 'CAN_SEE_QUICKSTART',
	),
// Cockpit > Display
	array(
		'text' => g_l('javaMenu_global', '[display]'),
		'parent' => 'cockpit',
		'cmd' => 'home',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
// Cockpit > new Widget
	'cockpit_new' => array(
		'text' => g_l('javaMenu_global', '[new_widget]'),
		'parent' => 'cockpit',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
// Cockpit > new Widget > shortcuts
	array(
		'text' => g_l('javaMenu_global', '[shortcuts]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_sct',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
// Cockpit > new Widget > RSS
	array(
		'text' => g_l('javaMenu_global', '[rss_reader]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_rss',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(// Cockpit > new Widget > messaging
		'text' => g_l('javaMenu_global', '[todo_messaging]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_msg',
		'perm' => 'CAN_SEE_QUICKSTART',
		'hide' => !defined('MESSAGING_SYSTEM')
	),
	array(// Cockpit > new Widget > Shop
		'text' => g_l('javaMenu_global', '[shop_dashboard]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_shp',
		'perm' => 'CAN_SEE_QUICKSTART || NEW_SHOP_ARTICLE || DELETE_SHOP_ARTICLE || EDIT_SHOP_ORDER || DELETE_SHOP_ORDER || EDIT_SHOP_PREFS',
		'hide' => !defined('SHOP_TABLE')
	),
	array(// Cockpit > new Widget > online users
		'text' => g_l('javaMenu_global', '[users_online]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_usr',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(// Cockpit > new Widget > lastmodified
		'text' => g_l('javaMenu_global', '[last_modified]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_mfd',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(// Cockpit > new Widget > unpublished
		'text' => g_l('javaMenu_global', '[unpublished]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_upb',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(// Cockpit > new Widget > my Documents
		'text' => g_l('javaMenu_global', '[my_documents]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_mdc',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(// Cockpit > new Widget > Notepad
		'text' => g_l('javaMenu_global', '[notepad]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_pad',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	array(
		'text' => g_l('javaMenu_global', '[kv_failedLogins]'),
		'parent' => 'cockpit_new',
		'cmd' => 'new_widget_fdl',
		'perm' => 'EDIT_CUSTOMER || NEW_CUSTOMER',
		'hide' => !defined('CUSTOMER_TABLE') || !permissionhandler::hasPerm('CAN_SEE_QUICKSTART'),
	),
	array(// Cockpit > new Widget > default settings
		'text' => g_l('javaMenu_global', '[default_settings]'),
		'parent' => 'cockpit',
		'cmd' => 'reset_home',
		'perm' => 'CAN_SEE_QUICKSTART',
	),
	'modules' => array(// Modules
		'text' => g_l('javaMenu_global', '[modules]'),
	),
	'extras' => array(// Extras
		'text' => g_l('javaMenu_global', '[extras]'),
	),
	array(// Extras > Dokument-Typen
		'text' => g_l('javaMenu_global', '[document_types]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'doctypes',
		'perm' => 'EDIT_DOCTYPE',
	),
	array(// Extras > Kategorien
		'text' => g_l('javaMenu_global', '[categories]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'editCat',
		'perm' => 'EDIT_KATEGORIE',
	),
	array(// Extras > Thumbnails
		'text' => g_l('javaMenu_global', '[thumbnails]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'editThumbs',
		'perm' => 'EDIT_THUMBS',
	),
	array(// Extras > Metadata fields
		'text' => g_l('javaMenu_global', '[metadata]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'editMetadataFields',
		'perm' => 'ADMINISTRATOR',
		'hide' => $seeMode
	),
	array(// separator
		'parent' => 'extras',
		'perm' => 'EDIT_DOCTYPE || EDIT_KATEGORIE || EDIT_THUMBS',
	),
	array(// Extras > change password
		'text' => g_l('javaMenu_global', '[change_password]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'change_passwd',
		'perm' => 'EDIT_PASSWD',
	),
	array(// separator
		'parent' => 'extras',
		'perm' => 'EDIT_PASSWD',
	),
	array(// Extras > versioning
		'text' => g_l('javaMenu_global', '[versioning]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'versions_wizard',
		'perm' => 'ADMINISTRATOR',
	),
	array(// Extras > versioning-log
		'text' => g_l('javaMenu_global', '[versioning_log]') . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'versioning_log',
		'perm' => 'ADMINISTRATOR',
	),
	array(// separator
		'parent' => 'extras',
		'perm' => 'ADMINISTRATOR',
	),
	array(
		'text' => g_l('javaMenu_global', '[common]') . '&hellip;',
		'parent' => 'settings',
		'cmd' => 'openPreferences',
		'perm' => 'EDIT_SETTINGS',
	),
	array(// separator
		'parent' => 'settings',
		'perm' => 'EDIT_SETTINGS',
	),
	'help' => array(// Help
		'text' => g_l('javaMenu_global', '[help]'),
	),
	'online-help' => array(
		'text' => g_l('javaMenu_global', '[onlinehelp]'),
		'parent' => 'help',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp]') . '&hellip;',
		'parent' => $seeMode ? 'help' : 'online-help',
		'cmd' => 'help',
	),
	array(// separator
		'parent' => 'online-help',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp_documentation]') . '&hellip;',
		'parent' => 'online-help',
		'cmd' => 'help_documentation',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp_tagreference]') . '&hellip;',
		'parent' => 'online-help',
		'cmd' => 'help_tagreference',
		'perm' => 'CAN_SEE_TEMPLATES',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp_forum]') . '&hellip;',
		'parent' => 'online-help',
		'cmd' => 'help_forum',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp_bugtracker]') . '&hellip;',
		'parent' => 'online-help',
		'cmd' => 'help_bugtracker',
		'hide' => $seeMode
	),
	array(// separator
		'parent' => 'online-help',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[onlinehelp_changelog]') . '&hellip;',
		'parent' => 'online-help',
		'cmd' => 'help_changelog',
		'hide' => $seeMode
	),
	array(
		'text' => g_l('javaMenu_global', '[sidebar]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'openSidebar',
		'hide' => !(SIDEBAR_DISABLED == 0)
	),
	array(
		'text' => g_l('javaMenu_global', '[webEdition_online]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'webEdition_online',
	),
	array(// separator
		'parent' => 'help',
		'perm' => 'ADMINISTRATOR',
	),
	array(
		'text' => g_l('javaMenu_global', '[update]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'update',
		'perm' => 'ADMINISTRATOR',
	),
	array(// separator
		'parent' => 'help'
	),
	array(
		'text' => g_l('javaMenu_global', '[sysinfo]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'sysinfo',
		'perm' => 'ADMINISTRATOR',
	),
	array(
		'text' => g_l('javaMenu_global', '[showerrorlog]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'showerrorlog',
		'perm' => 'ADMINISTRATOR',
	),
	array(
		'text' => g_l('javaMenu_global', '[info]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'info',
	)
);

$dtq = we_docTypes::getDoctypeQuery($GLOBALS['DB_WE']);
$GLOBALS['DB_WE']->query('SELECT dt.ID,dt.DocType FROM ' . DOC_TYPES_TABLE . ' dt LEFT JOIN ' . FILE_TABLE . ' dtf ON dt.ParentID=dtf.ID ' . $dtq['join'] . ' WHERE ' . $dtq['where']);

if($GLOBALS['DB_WE']->num_rows() && permissionhandler::hasPerm('NO_DOCTYPE')){
	$we_menu[] = array('parent' => 'file_new_wedoc'); // separator
}
// File > New > webEdition Document > Doctypes*
while($GLOBALS['DB_WE']->next_record()){
	$we_menu[] = array(
		'text' => str_replace(array(',', '"', '\'',), array(' ', ''), $GLOBALS['DB_WE']->f('DocType')),
		'parent' => 'file_new_wedoc',
		'cmd' => array('new_dtPage', $GLOBALS['DB_WE']->f('ID')),
		'perm' => 'NEW_WEBEDITIONSITE',
	);
}


if(defined('OBJECT_TABLE')){
	// object from which class
	$ac = we_users_util::getAllowedClasses($GLOBALS['DB_WE']);
	if($ac){
		$GLOBALS['DB_WE']->query('SELECT ID,Text FROM ' . OBJECT_TABLE . ' ' . ($ac ? ' WHERE ID IN(' . implode(',', $ac) . ') ' : '') . 'ORDER BY Text');

		if($GLOBALS['DB_WE']->num_rows()){
			while($GLOBALS['DB_WE']->next_record()){
				$we_menu[] = array(
					'text' => str_replace(array('"', '\''), '', $GLOBALS['DB_WE']->f('Text')),
					'parent' => 'file_new_weobj',
					'cmd' => array('new_ClObjectFile', $GLOBALS['DB_WE']->f('ID')),
					'perm' => 'NEW_OBJECTFILE',
				);
			}
		} else {
			$we_menu['file_new_weobj']['hide'] = 1;
		}
	}
}

// order all modules
$allModules = we_base_moduleInfo::getAllModules();
we_base_moduleInfo::orderModuleArray($allModules);

//$moduleList = 'schedpro|';
foreach($allModules as $m){
	if(we_base_moduleInfo::showModuleInMenu($m['name'])){
		$we_menu[] = array(
			'text' => $m['text'] . '&hellip;',
			'parent' => 'modules',
			'cmd' => $m['name'] . '_edit_ifthere',
			'perm' => isset($m['perm']) ? $m['perm'] : '',
		);
	}
}
// Extras > Tools > Custom tools
$tools = we_tool_lookup::getAllTools(true, false);

foreach($tools as $tool){
	$we_menu[] = array(
		'text' => ($tool['text'] === 'toolfactory' ? g_l('javaMenu_global', '[toolfactory]') : $tool['text']) . '&hellip;',
		'parent' => 'extras',
		'cmd' => 'tool_' . $tool['name'] . '_edit',
		'perm' => $tool['startpermission'],
	);
}

$activeIntModules = we_base_moduleInfo::getIntegratedModules(true);
we_base_moduleInfo::orderModuleArray($activeIntModules);

//add settings
$we_menu[] = array(// separator
	'parent' => 'extras',
);
$we_menu['settings'] = array(// Extras > Einstellungen
	'text' => g_l('javaMenu_global', '[preferences]'),
	'parent' => 'extras',
);

if($activeIntModules){
	foreach($activeIntModules as $modInfo){
		if($modInfo['hasSettings']){
			$we_menu[] = array(
				'text' => $modInfo['text'] . '&hellip;',
				'parent' => 'settings',
				'cmd' => 'edit_settings_' . $modInfo['name'],
				'perm' => isset($modInfo['perm']) ? $modInfo['perm'] : '',
			);
		}
	}
}
return $we_menu;
