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
define('NO_SESS', 1);
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
header('Content-type: text/css');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT', true);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT', true);
header('Cache-Control: max-age=86400, must-revalidate', true);
header('Pragma: ', true);
?>

/* css editor body:font-size: this sheet is included first, for font-size to be eventually overwritten by document-css */

body {
font-size: 12px;
<?php
$bgcol = we_base_request::_(we_base_request::STRING, 'tinyMceBackgroundColor');
$bgcol = preg_match('/^[a-f0-9]{6}$/i', $bgcol) ? '#' . $bgcol : $bgcol;
echo $bgcol ? '
background-color: ' . $bgcol . ' !important;
background-image: none !important;
' : '';
?>
}


/* css for plugin wevisialborders */

acronym.mceItemWeAcronym{
border: 1px dotted gray;
}

abbr.mceItemWeAbbr{
border: 1px dotted gray;
}

span.mceItemWeLang{
border: 1px dotted gray;
}


we-gallery{
	display: block;
	font-size: 14px;
}

we-gallery:before {
	font-family: FontAwesome;
	content: "\f187 ";
	color: #164F94;
}

we-gallery:after {
	font-family: Helvetica;
	content: "<?php echo g_l('contentTypes', '[text/weCollection]'); ?>";
	color: #164F94;
	border: 1px dotted gray;
}
