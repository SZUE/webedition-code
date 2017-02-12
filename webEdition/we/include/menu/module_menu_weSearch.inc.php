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
return [
	'search' => ['text' => g_l('searchtool', '[menu_suche]'),],
	'new' => ['text' => g_l('searchtool', '[menu_new]'),
		'parent' => 'search',
	],
	['text' => g_l('searchtool', '[forDocuments]'),
		'parent' => 'new',
		'cmd' => 'tool_weSearch_new_forDocuments',
		'perm' => 'EDIT_NAVIGATION || ADMINISTRATOR',
		'hide' => !we_base_permission::hasPerm('CAN_SEE_DOCUMENTS')
	],
	['text' => g_l('searchtool', '[forTemplates]'),
		'parent' => 'new',
		'cmd' => 'tool_weSearch_new_forTemplates',
		'perm' => 'EDIT_NAVIGATION || ADMINISTRATOR',
		'hide' => !($_SESSION['weS']['we_mode'] != we_base_constants::MODE_SEE && we_base_permission::hasPerm('CAN_SEE_TEMPLATES'))
	],
	['text' => g_l('searchtool', '[forObjects]'),
		'parent' => 'new',
		'cmd' => 'tool_weSearch_new_forObjects',
		'perm' => 'EDIT_NAVIGATION || ADMINISTRATOR',
		'hide' => (defined('OBJECT_FILES_TABLE') && defined('OBJECT_TABLE') && we_base_permission::hasPerm('CAN_SEE_OBJECTFILES'))
	],
	['text' => g_l('searchtool', '[forMedia]'),
		'parent' => 'new',
		'cmd' => 'tool_weSearch_new_forMedia',
		'perm' => 'EDIT_NAVIGATION || ADMINISTRATOR',
		'enabled' => 1,
		'hide' => !we_base_permission::hasPerm('CAN_SEE_DOCUMENTS')
	],
	['text' => g_l('searchtool', '[menu_advSearch]'),
		'parent' => 'new',
		'cmd' => 'tool_weSearch_new_advSearch',
		'perm' => 'EDIT_NAVIGATION || ADMINISTRATOR',
	],
	['text' => g_l('searchtool', '[menu_save]'),
		'parent' => 'search',
		'cmd' => 'tool_weSearch_save',
	],
	['text' => g_l('searchtool', '[menu_delete]'),
		'parent' => 'search',
		'cmd' => 'tool_weSearch_delete',
	],
	['parent' => 'search'], // separator
	['text' => g_l('searchtool', '[menu_exit]'),
		'parent' => 'search',
		'cmd' => 'tool_weSearch_exit',
	],
	'help' => ['text' => g_l('searchtool', '[menu_help]'),],
	['text' => g_l('searchtool', '[menu_help]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'help_modules',
	],
	['text' => g_l('searchtool', '[menu_info]') . '&hellip;',
		'parent' => 'help',
		'cmd' => 'info_modules',
	],
];
