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
echo we_html_tools::getHtmlTop(g_l('messageConsole', '[headline]')) .
 STYLESHEET;

$deleteAllButton = we_html_button::create_button(we_html_button::DELETE, "javascript:msgWin.removeMessages();");
$closeButton = we_html_button::create_button(we_html_button::CLOSE, "javascript:window.close();");

$buttons = we_html_button::formatButtons($deleteAllButton . $closeButton);

echo
we_html_element::cssLink(CSS_DIR . 'messageConsole.css') .
 we_html_element::jsScript(JS_DIR . 'messageConsoleWindow.js');
?>
</head>

<body onload="(msgWin=new messageConsoleWindow(window)).init();" onunload="msgWin.remove();" class="weDialogBody messageConsoleWindow">
	<div id="headlineDiv">
		<div class="weDialogHeadline">
			<?php echo g_l('messageConsole', '[headline]') ?>
		</div>
	</div>
	<div id="messageDiv">
		<ul id="jsMessageUl" class="fa-ul"></ul>
	</div>
	<div class="dialogButtonDiv">
			<?php echo $buttons; ?>
	</div>
</body>
</html>