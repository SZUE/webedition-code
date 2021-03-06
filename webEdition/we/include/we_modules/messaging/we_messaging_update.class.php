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
/* clipboard object class */
class we_messaging_update extends we_class{
	/* Flag which is set when the file is not new */

	var $userid = -1;
	var $username = '';

	function __construct(){
		parent::__construct();
		$this->Name = 'msg_clipboard_' . md5(uniqid(__FUNCTION__, true));
		$this->persistent_slots = array('ClassName', 'Name', 'ID', 'Folder_ID', 'selected_message', 'selected_set', 'sort_order', 'last_sortfield', 'search_ids', 'available_folders', 'search_folders', 'search_fields');
	}

}
