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
$title = sprintf(g_l('moduleActivation', '[headline]'), $GLOBALS['moduleName']);
echo we_html_tools::getHtmlTop($title, '', '', STYLESHEET);
?>
<body class="weDialogBody" onload="self.focus();" onblur="self.close();"><?php
	echo '
<table width="100%" class="default defaultfont">
<tr><td colspan="2"><strong>' . $title . '</strong></td></tr>
<tr><td style="vertical-align:top"><img src="' . IMAGE_DIR . 'alert.gif' . '" /></td><td class="defaultfont">' . g_l('moduleActivation', '[content]') . '</td></tr>
</table>';
	?>
</body>
</html>