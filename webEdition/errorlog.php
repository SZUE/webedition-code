<?php
/**
 * webEdition CMS
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
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

we_html_tools::protect();

function getInfoTable($_infoArr){
	//recode data - this data might be different than the rest...
	foreach($_infoArr as &$tmp){
		try{
			@$tmp = htmlentities($tmp, ENT_COMPAT, $GLOBALS['WE_BACKENDCHARSET']);
		} catch (Exception $e){
			//try another encoding since last conversion failed.
			@$tmp = htmlentities($tmp, ENT_COMPAT, $GLOBALS['WE_BACKENDCHARSET'] == 'UTF-8' ? 'ISO-8859-15' : 'UTF-8');
		}
	}
	$trans = array('Error type' => 'Type', 'Error message' => 'Text', 'Script name' => 'File', 'Line number' => 'Line', 'Backtrace' => 'Backtrace',
		'Request' => 'Request', 'Server' => 'Server', 'Session' => 'Session', 'Global' => 'Global');
	return '
			<table align="center" bgcolor="#FFFFFF" cellpadding="4" cellspacing="0" style="border: 1px solid #265da6;" width="610">
  <colgroup>
  <col width="10%"/>
  <col width="90%" />
  </colgroup>
  <tr bgcolor="#f7f7f7" valign="top">
  	<td nowrap="nowrap" style="border-bottom: 1px solid #265da6; border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>#' . $_infoArr['ID'] . '</b></font></td>
    <td  style="border-bottom: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">' . $_infoArr['Date'] . '</font></td>
  </tr>' . '
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6; border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Error type:</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><i>' . $_infoArr['Type'] . '</i></font></td>
  </tr>
  <tr valign="top">
    <td  style="border-bottom: 1px solid #265da6; border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Error message:</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><pre>' . $_infoArr['Text'] . '</pre></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6; border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Script name:</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><i>' . $_infoArr['File'] . '</i></font></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6; border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Line number:</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><i>' . $_infoArr['Line'] . '</i></font></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6;border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Backtrace</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><pre>' . $_infoArr['Backtrace'] . '
      </pre></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6;border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Request</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><pre>' . $_infoArr['Request'] . '</pre></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Server</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><pre>' . $_infoArr['Server'] . '</pre></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6;border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Session</b></font></td>
    <td style="border-bottom: 1px solid #265da6;"><pre>' . $_infoArr['Session'] . '
      </pre></td>
  </tr>
  <tr valign="top">
    <td nowrap="nowrap" style="border-bottom: 1px solid #265da6;border-right: 1px solid #265da6;"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Global</b></font></td>
    <td ><pre>' . $_infoArr['Global'] . '</pre></td>
  </tr>

</table>';
}

$buttons = we_button::position_yes_no_cancel(
		we_button::create_button("delete_all", '/webEdition/errorlog.php' . "?delete"), we_button::create_button("refresh", '/webEdition/errorlog.php'), we_button::create_button("close", "javascript:self.close()")
);




$_space_size = 10;
$_parts = array();


$db = new DB_WE();
if(isset($_REQUEST['delete'])){
	$db->query('TRUNCATE TABLE `' . ERROR_LOG_TABLE . '`');
}
$size = f('SELECT COUNT(1) as cnt FROM `' . ERROR_LOG_TABLE . '`', 'cnt', $db);
$count = 1;

$nextprev = "";
if($size > 0){

	$start = (isset($_REQUEST['start']) ? abs($_REQUEST['start']) : 0);
	$start = $start > $size ? $size : $start;

	$back = $start - $count;
	$back = $back < 0 ? 0 : $back;

	$next = $start + $count;
	$next = $next > $size ? $size : $next;

	$div=intval($size/10);
	if($div==0){
		$div=1;
	}
	$nextDiv=$start+$div;
	$prevDiv=$start-$div;

	$ind = 0;
	$nextprev = '<table style="margin-top: 10px;" border="0" cellpadding="0" cellspacing="0"><tr><td>' .
		we_button::create_button("first", '/webEdition/errorlog.php?start=' . ($size-1), true, we_button::WIDTH, we_button::HEIGHT, "", "", ($next >= $size)) .'</td><td>'.
		we_button::getButton("-".$div,'btn', "window.location.href='/webEdition/errorlog.php?start=" . $nextDiv."';", we_button::WIDTH,'',($nextDiv >= $size)) .'</td><td>'.
		we_button::create_button("back", '/webEdition/errorlog.php?start=' . $next, true, we_button::WIDTH, we_button::HEIGHT, "", "", ($next >= $size)) .
		we_html_tools::getPixel(23, 1) . "</td><td align='center' class='defaultfont' width='120'><b>" . ($size - $start) .
		"&nbsp;" . g_l('global', '[from]') . " " . ($size) . "</b></td><td>" . we_html_tools::getPixel(23, 1) .
		we_button::create_button("next", '/webEdition/errorlog.php?start=' . $back, true, we_button::WIDTH, we_button::HEIGHT, "", "", ($start <= 0)) .'</td><td>'.
		we_button::getButton("+".$div,'btn2', "window.location.href='/webEdition/errorlog.php?start=" . $prevDiv."';", we_button::WIDTH, '', ($prevDiv <=0)) .'</td><td>'.
we_button::create_button("last", '/webEdition/errorlog.php?start=0', true, we_button::WIDTH, we_button::HEIGHT, "", "", ($start <= 0)) .
				"</td></tr></table>";

	$_parts[] = array(
		'html' => $nextprev,
		'space' => $_space_size
	);
	$record=getHash('SELECT * FROM `' . ERROR_LOG_TABLE . '` ORDER By ID DESC LIMIT ' . $start . ',1',$db);
		$_parts[] = array(
			'html' => getInfoTable($record),
			'space' => $_space_size
		);
} else{
	$_parts[] = array(
		'html' => 'No entries found',
		'space' => $_space_size
	);
}

we_html_tools::htmlTop('Errorlog', $GLOBALS['WE_BACKENDCHARSET']);
echo we_html_element::jsScript(JS_DIR . 'attachKeyListener.js') .
 we_html_element::jsScript(JS_DIR . 'keyListener.js') .
 we_html_element::jsElement('function closeOnEscape() {
		return true;
	}
') .
 STYLESHEET;
?>
</head>

<body class="weDialogBody" style="overflow:hidden;" onLoad="self.focus();">
	<div id="info" style="display: block;">
		<?php
		print we_multiIconBox::getJS() .
			we_multiIconBox::getHTML('', 700, $_parts, 30, $buttons, -1, '', '', false, "", "", 620, "auto");
		?>
	</div>
</body>
</html>
