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
include_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
we_html_tools::protect();
we_html_tools::htmlTop('sideBar');
echo we_html_element::cssLink('/webEdition/css/global.php?WE_LANGUAGE=' . $GLOBALS["WE_LANGUAGE"] . '&amp;WE_BACKENDCHARSET=' . $GLOBALS["WE_BACKENDCHARSET"]);
?>

</head>
<body class="weSidebarBody">

	<table>
		<?php

		function showSidebarText(&$textArray){
			for($i = 0; $i < sizeof($textArray); $i++){
				$text = &$textArray[$i];

				$link = "%s";
				if(isset($text['link']) && $text['link'] != ""){
					if(stripos($text['link'], 'javascript:') === 0){
						$link = "<a href=\"" . $text['link'] . "\">%s</a>";
					} else{
						$link = "<a href=\"" . $text['link'] . "\" target=\"_blank\">%s</a>";
					}
				}

				$icon = "";
				if(isset($text['icon']) && $text['icon'] != ""){
					$icon = sprintf($link, "<img src=\"/webEdition/sidebar/img/" . $text['icon'] . "\" width=\"42\" height=\"42\" border=\"0\" />");
				}

				$headline = "";
				if(isset($text['headline']) && $text['headline'] != ""){
					$headline = sprintf($link, $text['headline']);
				}
				?>
				<tr>
					<td colspan="2"><?php we_html_tools::pPixel(1, 5); ?></td>
				</tr>
				<tr>
					<?php
					if($icon == ""){
						?>
						<td class="defaultfont" valign="top" colspan="2">
							<strong><?php echo $headline; ?></strong><br />
							<?php we_html_tools::pPixel(1, 4); ?>
							<br />
							<?php echo $text['text']; ?>
						</td>
						<?php
					} else{
						?>
						<td class="defaultfont" valign="top" width="52"><?php echo $icon; ?></td>
						<td class="defaultfont" valign="top">
							<strong><?php echo $headline; ?></strong><br />
							<?php we_html_tools::pPixel(1, 4); ?><br />
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

			showSidebarText(g_l('sidebar', '[default]'));

			if(we_hasPerm("ADMINISTRATOR")){
				showSidebarText(g_l('sidebar', '[admin]'));
			}
			?>
	</table>

</body>
</html>