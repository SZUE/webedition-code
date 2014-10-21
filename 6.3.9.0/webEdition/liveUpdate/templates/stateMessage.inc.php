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
/*
 * This is the template for tab connect. It trys to connect to the server in
 * different ways.
 */

$description = ($this->State === 'true' ?
		g_l('liveUpdate', '[state][descriptionTrue]') :
		g_l('liveUpdate', '[state][descriptionError]'));

$content = '
<div class="defaultfont">
	' . $description . '
	<div class="errorDiv">
		<code>' . $this->Message . '</code>
	</div>
</div>';

print liveUpdateTemplates::getHtml(g_l('liveUpdate', '[state][headline]'), $content);
