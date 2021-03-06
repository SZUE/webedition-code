<?php

abstract class we_base_moduleInfo{
	const BANNER = 'banner';
	const COLLECTION = 'collection';
	const CUSTOMER = 'customer';
	const EDITOR = 'editor';
	const EXPORT = 'export';
	const GLOSSARY = 'glossary';
	const MESSAGING = 'messaging';
	const NAVIGATION = 'navigation';
	const NEWSLETTER = 'newsletter';
	const OBJECT = 'object';
	const SCHEDULER = 'schedule';
	const SHOP = 'shop';
	const SPELLCHECKER = 'spellchecker';
	const USERS = 'users';
	const VOTING = 'voting';
	const WORKFLOW = 'workflow';

	private static $we_available_modules = '';

	private static function init(){
		if(!self::$we_available_modules){
			self::$we_available_modules = include(WE_INCLUDES_PATH . 'we_available_modules.inc.php');
		}
	}

	/**
	 * Orders a hash array of the scheme of we_available_modules
	 *
	 * @param hash $array
	 */
	static function orderModuleArray(&$array){
		uasort($array, function ($a, $b){
			return (strcmp($a['text'], $b['text']));
		});
	}

	/**
	 * returns hash with All modules
	 *
	 * @return hash
	 */
	static function getAllModules(){
		self::init();
		return self::$we_available_modules;
	}

	/**
	 * returns hash of all modules
	 * @return hash
	 */
	static function getIntegratedModules($active){
		self::init();
		$retArr = array();

		foreach(self::$we_available_modules as $key => $modInfo){
			if(self::isActive($key) == $active){
				$retArr[$key] = $modInfo;
			}
		}

		return $retArr;
	}

	/**
	 * returns whether a module is in the menu or not
	 * @param string $modulekey
	 * @return boolean
	 */
	static function showModuleInMenu($modulekey){
		self::init();
		// show a module, if
		// - it is active
		// - if it is in module window

		return (self::$we_available_modules[$modulekey]['inModuleMenu'] && self::isActive($modulekey));
	}

	static function isActive($modul){
		if(!in_array($modul, $GLOBALS['_we_active_integrated_modules'])){
			return false;
		}
		switch($modul){
			case 'users'://removed config
				break;
			default:
				if(file_exists(WE_MODULES_PATH . $modul . '/we_conf_' . $modul . '.inc.php')){
					require_once (WE_MODULES_PATH . $modul . '/we_conf_' . $modul . '.inc.php');
				}
		}
		return true;
	}

	static function getModuleData($module){
		self::init();
		if(isset(self::$we_available_modules[$module])){
			return self::$we_available_modules[$module];
		}
		return false;
	}

}
