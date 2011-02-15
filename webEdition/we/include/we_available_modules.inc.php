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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

$_we_available_modules = array();


$_we_available_modules["users"] = array(

		"name" => "users",
		"perm" => "NEW_USER || NEW_GROUP || SAVE_USER || SAVE_GROUP || DELETE_USER || DELETE_GROUP || ADMINISTRATOR",
		"text" => g_l('javaMenu_moduleInformation','['."users".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."users".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."users".'][not_installed]'),
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => true,
		"hasSettings" => false,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["customer"] = array(

		"name" => "customer",
		"perm" => "SHOW_CUSTOMER_ADMIN || DELETE_CUSTOMER || EDIT_CUSTOMER || NEW_CUSTOMER || ADMINISTRATOR",
		"text" => g_l('javaMenu_moduleInformation','['."customer".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."customer".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."customer".'][not_installed]'),
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>"shop"
);

$_we_available_modules["shop"] = array(

		"name" => "shop",
		"text" => g_l('javaMenu_moduleInformation','['."shop".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."shop".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."shop".'][not_installed]'),
		"perm" => "NEW_SHOP_ARTICLE || DELETE_SHOP_ARTICLE || EDIT_SHOP_ORDER || DELETE_SHOP_ORDER || EDIT_SHOP_PREFS || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"hasSettings" => true,
		"dependson" => "customer",
		"childmodule" =>""
);

$_we_available_modules["schedule"] = array(

		"name" => "schedule",
		"text" => g_l('javaMenu_moduleInformation','['."schedule".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."schedule".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."schedule".'][not_installed]'),
		"inModuleMenu" => false,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => false,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["editor"] = array(

		"name" => "editor",
		"text" => g_l('javaMenu_moduleInformation','['."editor".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."editor".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."editor".'][not_installed]'),
		"perm" => "NEW_SHOP_ARTICLE || DELETE_SHOP_ARTICLE || EDIT_SHOP_ORDER || DELETE_SHOP_ORDER || EDIT_SHOP_PREFS || ADMINISTRATOR",
		"inModuleMenu" => false,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["object"] = array(

		"name" => "object",
		"text" => g_l('javaMenu_moduleInformation','['."object".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."object".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."object".'][not_installed]'),
		"inModuleMenu" => false,
		"integrated" => true,
		"hasSettings" => false,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["messaging"] = array(

		"name" => "messaging",
		"text" => g_l('javaMenu_moduleInformation','['."messaging".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."messaging".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."messaging".'][not_installed]'),
		"inModuleMenu" => true,
		"integrated" => true,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>"workflow"
);

$_we_available_modules["workflow"] = array(

		"name" => "workflow",
		"text" => g_l('javaMenu_moduleInformation','['."workflow".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."workflow".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."workflow".'][not_installed]'),
		"perm" => "NEW_WORKFLOW || DELETE_WORKFLOW || EDIT_WORKFLOW || EMPTY_LOG || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"hasSettings" => false,
		"dependson" => "messaging",
		"childmodule" =>""
);

$_we_available_modules["newsletter"] = array(

		"name" => "newsletter",
		"text" => g_l('javaMenu_moduleInformation','['."newsletter".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."newsletter".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."newsletter".'][not_installed]'),
		"perm" => "NEW_NEWSLETTER || DELETE_NEWSLETTER || EDIT_NEWSLETTER || SEND_NEWSLETTER || SEND_TEST_EMAIL || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["banner"] = array(

		"name" => "banner",
		"text" => g_l('javaMenu_moduleInformation','['."banner".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."banner".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."banner".'][not_installed]'),
		"perm" => "NEW_BANNER || DELETE_BANNER || EDIT_BANNER || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["export"] = array(

		"name" => "export",
		"text" => g_l('javaMenu_moduleInformation','['."export".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."export".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."export".'][not_installed]'),
		"perm" => "NEW_EXPORT || DELETE_EXPORT || EDIT_EXPORT || MAKE_EXPORT || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => true,
		"hasSettings" => false,
		"inModuleWindow" => true,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["voting"] = array(

		"name" => "voting",
		"text" => g_l('javaMenu_moduleInformation','['."voting".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."voting".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."voting".'][not_installed]'),
		"perm" => "NEW_VOTING || DELETE_VOTING || EDIT_VOTING || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => false,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["spellchecker"] = array(

		"name" => "spellchecker",
		"text" => g_l('javaMenu_moduleInformation','['."spellchecker".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."spellchecker".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."spellchecker".'][not_installed]'),
		"perm" => "SPELLCHECKER_ADMIN || ADMINISTRATOR",
		"inModuleMenu" => false,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>""
);

$_we_available_modules["glossary"] = array(

		"name" => "glossary",
		"text" => g_l('javaMenu_moduleInformation','['."glossary".'][text]'),
		"text_short" => g_l('javaMenu_moduleInformation','['."glossary".'][text_short]'),
		"notInstalled" => g_l('javaMenu_moduleInformation','['."glossary".'][not_installed]'),
		"perm" => "NEW_GLOSSARY || DELETE_GLOSSARY || EDIT_GLOSSARY || ADMINISTRATOR",
		"inModuleMenu" => true,
		"integrated" => true,
		"alwaysActive" => false,
		"hasSettings" => true,
		"dependson" => "",
		"childmodule" =>""
);
