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
class we_messaging_headerMsg{
	private static $messaging = 0;

	private static function start(){
		if(is_object(self::$messaging)){
			return;
		}
		self::$messaging = new we_messaging_messaging($_SESSION['weS']['we_data']["we_transaction"]);
		self::$messaging->set_login_data($_SESSION["user"]["ID"], $_SESSION["user"]["Username"]);
		self::$messaging->add_msgobj('we_message', 1);
		self::$messaging->add_msgobj('we_todo', 1);
	}

	static function pJS(){
		self::start();
		echo we_html_element::jsScript(JS_DIR . 'header_msg.js');
		?>
		<script type="text/javascript"><!--

		<?php
		if(defined('MESSAGING_SYSTEM')){
			$newmsg_count = self::$messaging->used_msgobjs['we_message']->get_newmsg_count();
			$newtodo_count = self::$messaging->used_msgobjs['we_todo']->get_newmsg_count();
			echo 'header_msg_update(' . $newmsg_count . ', ' . $newtodo_count . ');';
			} ?>
		//-->
		</script>
		<?php
	}

	static function pbody(){
		self::start();
		//start with 0 to get popup with new count
		$msg_cmd = "we_cmd('messaging_start', " . we_messaging_frames::TYPE_MESSAGE . ");";
		$todo_cmd = "we_cmd('messaging_start', " . we_messaging_frames::TYPE_TODO . ");";
		?>
		<table>
			<?php echo '
<tr>
	<td id="msgCount" align="right" class="middlefont"><div onclick="' . $msg_cmd . '">0</div></td>
	<td>' . we_html_tools::getPixel(5, 1) . '</td>
	<td valign="bottom"><img src="' . IMAGE_DIR . 'modules/messaging/launch_messages.gif" style="width:16px;height:12px;" alt="" onclick="' . $msg_cmd . '"/></td>
</tr>
<tr>
	<td id="todoCount" align="right" class="middlefont"><div onclick="' . $todo_cmd . '">0</div></td>
	<td>' . we_html_tools::getPixel(5, 1) . '</td>
	<td valign="bottom"><img src="' . IMAGE_DIR . 'modules/messaging/launch_tasks.gif" style="width:16px;height:12px;" alt="" onclick="' . $todo_cmd . '"/></td>
</tr>'
			?>
		</table>
		<?php
	}

}
