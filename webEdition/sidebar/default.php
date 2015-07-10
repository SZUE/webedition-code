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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();
echo we_html_tools::getHtmlTop('sideBar') .
 STYLESHEET;
?>

</head>
<body class="weSidebarBody">

	<table>
		<?php

		function showSidebarText(&$textArray){
			unset($textArray[2]); // #6261: do not show entry [2]
			foreach($textArray as $i => $val){
				if(isset($textArray[$i])){

					$text = &$textArray[$i];

					$link = "%s";
					if(!empty($text['link'])){

						if(stripos($text['link'], 'javascript:') === 0){
							$text['link'] = str_replace("\"", "'", $text['link']); #6625
							$text['link'] = str_replace("`", "'", $text['link']); #6625
							$link = '<a href="' . $text['link'] . '">%s</a>';
						} else {
							$link = '<a href="' . $text['link'] . '" target="_blank">%s</a>';
						}
					}

					$icon = (!empty($text['icon']) ?
							sprintf($link, '<img src="' . IMAGE_DIR . 'sidebar/' . $text['icon'] . '" width="42" height="42" border="0" />') :
							'');

					$headline = "";
					if(!empty($text['headline'])){
						$headline = sprintf($link, $text['headline']);
					}
					?>
					<tr><?php
						if($icon){
							?>
							<td class="defaultfont" valign="top" width="52" style="padding-top:5px;"><?php echo $icon; ?></td>
							<td class="defaultfont" valign="top">
								<strong><?php echo $headline; ?></strong><br />
								<?php echo $text['text']; ?>
							</td>
							<?php
						} else {
							?>
							<td class="defaultfont" valign="top" colspan="2" style="padding-top:5px;">
								<strong><?php echo $headline; ?></strong><br />
								<br />
								<?php echo $text['text']; ?>
							</td>
							<?php
						}
						?>
					</tr>
					<tr>
						<?php
					}
				}
			}

			showSidebarText(g_l('sidebar', '[default]'));

			if(permissionhandler::hasPerm('ADMINISTRATOR')){
				showSidebarText(g_l('sidebar', '[admin]'));
			}
			?>
	</table>

</body>
</html>