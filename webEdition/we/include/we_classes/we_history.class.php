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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

/**
 * Class we_history
 *
 * Provides functions determined to handle a list of last modified files required by
 * the 'personalized desktop'.
 */
abstract class we_history{

	const MAX = 5;

	static function userHasPerms($creatorid, $owners, $restricted){
		return (permissionhandler::hasPerm('ADMINISTRATOR') || !$restricted || we_users_util::isOwner($owners) || we_users_util::isOwner($creatorid));
	}

	static function insertIntoHistory(&$object, $action = 'save'){
		$db = new DB_WE();
		$table = $db->escape(stripTblPrefix($object->Table));
		$cnt = f('SELECT COUNT(1) AS cnt FROM ' . HISTORY_TABLE . ' WHERE DID=' . intval($object->ID) . ' AND DocumentTable="' . $table . '"', 'cnt', $db);
		if($cnt > self::MAX){
			$db->query('DELETE FROM ' . HISTORY_TABLE . ' WHERE DID=' . intval($object->ID) . ' AND DocumentTable="' . $table . '" ORDER BY ModDate DESC LIMIT ' . ($cnt - self::MAX));
		}
		$user = (isset($GLOBALS['we']['Scheduler_active']) ? 'Scheduler' : '');
		$db->query('REPLACE INTO ' . HISTORY_TABLE . ' SET ' . we_database_base::arraySetter(array(
				'DID' => intval($object->ID),
				'DocumentTable' => $table,
				'ContentType' => $object->ContentType,
				'Act' => $action,
				'UserName' => (isset($GLOBALS['we']['Scheduler_active']) ? 'Scheduler' : (isset($_SESSION['user']['Username']) ? $_SESSION['user']['Username'] : (isset($_SESSION['webuser']['Username']) ? $_SESSION['webuser']['Username'] : 'Unknown'))),
				'UID' => (isset($GLOBALS['we']['Scheduler_active']) ? 0 : (isset($_SESSION['user']['ID']) ? $_SESSION['user']['ID'] : 0)),
		)));
	}

	/**
	 * Deletes a model from navigation History
	 *
	 * @param array $modelIds
	 * @param string $table
	 */
	static function deleteFromHistory($modelIds, $table){
		$db = new DB_WE();
		$db->query('DELETE FROM ' . HISTORY_TABLE . ' WHERE DID IN (' . implode(', ', $modelIds) . ') AND DocumentTable="' . stripTblPrefix($table) . '"');
	}

}
