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

define('WE_DEFAULT_EMAIL', 'mailserver@' . $_SERVER['SERVER_NAME']);
define('WE_DEFAULT_SUBJECT', 'webEdition mailform');

$_blocked = false;


// check to see if we need to lock or block the formmail request

if(FORMMAIL_LOG){
	// insert into log
	$GLOBALS['DB_WE']->query('INSERT INTO ' . FORMMAIL_LOG_TABLE . ' SET ip="' . $GLOBALS['DB_WE']->escape($_SERVER['REMOTE_ADDR']) . '"');
	if(FORMMAIL_EMPTYLOG > -1){
		$GLOBALS['DB_WE']->query('DELETE FROM ' . FORMMAIL_LOG_TABLE . ' WHERE unixTime<(NOW() - INTERVAL ' . intval(FORMMAIL_EMPTYLOG) . ' SECOND)');
	}

	if(FORMMAIL_BLOCK){
		// first delete all entries from blocktable which are older then now - blocktime
		$GLOBALS['DB_WE']->query('DELETE FROM ' . FORMMAIL_BLOCK_TABLE . ' WHERE blockedUntil!=-1 AND blockedUntil<UNIX_TIMESTAMP()');

		// check if ip is allready blocked
		if(f('SELECT 1 FROM ' . FORMMAIL_BLOCK_TABLE . ' WHERE ip="' . $GLOBALS['DB_WE']->escape($_SERVER['REMOTE_ADDR']) . '" LIMIT 1')){
			$_blocked = true;
		} else {
			// ip is not blocked, so see if we need to block it
			if(f('SELECT COUNT(1) FROM ' . FORMMAIL_LOG_TABLE . ' WHERE unixTime>(NOW()- INTERVAL ' . intval(FORMMAIL_SPAN) . ' SECOND) AND ip="' . $GLOBALS['DB_WE']->escape($_SERVER['REMOTE_ADDR']) . '"') > FORMMAIL_TRIALS){
				$_blocked = true;
				// insert in block table
				$GLOBALS['DB_WE']->query('REPLACE INTO ' . FORMMAIL_BLOCK_TABLE . ' SET ' . we_database_base::arraySetter(array(
						'ip' => $_SERVER['REMOTE_ADDR'],
						'blockedUntil' => (FORMMAIL_BLOCKTIME == -1 ? -1 : sql_function('(UNIX_TIMESTAMP()+' . intval(FORMMAIL_BLOCKTIME) . ')'))
				)));
			}
		}
	}
}

$_blocked |= (FORMMAIL_VIAWEDOC && $_SERVER['SCRIPT_NAME'] == WEBEDITION_DIR . basename(__FILE__));

if($_blocked){
	print_error('Email dispatch blocked / Email Versand blockiert!');
}

function contains_bad_str($str_to_test){
	$str_to_test = trim($str_to_test);
	$bad_strings = array(
		'content-type:',
		'mime-version:',
		'Content-Transfer-Encoding:',
		'bcc:',
		'cc:',
		'to:',
	);

	foreach($bad_strings as $bad_string){
		if(preg_match('|^' . preg_quote($bad_string, '|') . '|i', $str_to_test) || preg_match('|[\n\r]' . preg_quote($bad_string, "|") . '|i', $str_to_test)){
			print_error('Email dispatch blocked / Email Versand blockiert!');
		}
	}
	if(stristr($str_to_test, 'multipart/mixed')){
		print_error('Email dispatch blocked / Email Versand blockiert!');
	}
}

function replace_bad_str($str_to_test){
	$bad_strings = array(
		'#(content-type)(:)#i',
		'#(mime-version)(:)#i',
		'#(multipart/mixed)#i',
		'#(Content-Transfer-Encoding)(:)#i',
		'#(bcc)(:)#i',
		'#(cc)(:)#i',
		'#(to)(:)#i',
	);

	return preg_replace($bad_strings, '($1)$2', $str_to_test);
}

function contains_newlines($str_to_test){
	if(preg_match("/(\\n+|\\r+)/", $str_to_test) != 0){
		print_error('newline found in ' . $str_to_test . '. Suspected injection attempt - mail not being sent.');
	}
}

function print_error($errortext){
	$headline = 'Fehler / Error';
	$content = g_l('global', '[formmailerror]') . getHtmlTag('br') . '&#8226; ' . $errortext;

	echo we_html_tools::getHtmlTop() .
	we_html_element::cssLink(WEBEDITION_DIR . 'css/global.php') .
	'</head>' .
	getHtmlTag('body', array('class' => 'weEditorBody'), '', false, true) .
	we_html_tools::htmlDialogLayout(getHtmlTag('div', array('class' => 'defaultgray'), $content), $headline) .
	'</body></html>';

	exit();
}

function check_required($required){
	if($required){
		$we_requiredarray = explode(',', $required);
		foreach($we_requiredarray as $cur){
			if(!we_base_request::_(we_base_request::RAW, $cur)){
				return false;
			}
		}
	}
	return true;
}

function error_page(){
	if(($errorpage = we_base_request::_(we_base_request::URL, 'error_page'))){
		redirect($errorpage);
	} else {
		print_error(g_l('global', '[email_notallfields]'));
	}
}

function ok_page(){
	if(($ok_page = we_base_request::_(we_base_request::URL, 'ok_page'))){
		redirect($ok_page);
	} else {
		echo 'Vielen Dank, Ihre Formulardaten sind bei uns angekommen! / Thank you, we received your form data!';
		exit();
	}
}

function redirect($url, $_emosScontact = ''){
	if($_emosScontact != ''){
		$url = $url . (strpos($url, '?') ? '&' : '?') . 'emosScontact=' . urlencode($_emosScontact);
	}
	header('Location: ' . getServerUrl() . $url);
	exit();
}

function check_recipient($email){
	return (f('SELECT 1 FROM ' . RECIPIENTS_TABLE . ' WHERE Email="' . $GLOBALS['DB_WE']->escape($email) . '" LIMIT 1'));
}

function check_captcha(){
	return ($name = we_base_request::_(we_base_request::STRING, we_base_request::_(we_base_request::STRING, 'captchaname', '__NOT_SET__')) ?
		we_captcha_captcha::check($name) :
		false);
}

$_req = we_base_request::_(we_base_request::RAW, 'required', '');

if(!check_required($_req)){
	error_page();
}

if(isset($_REQUEST['email']) && $_REQUEST['email']){//fixme: note this mail can be in "abc" <cc@de.de> format
	if(!we_check_email($_REQUEST['email'])){
		if(($foo = we_base_request::_(we_base_request::URL, 'mail_error_page'))){
			redirect($foo);
		} else {
			print_error(g_l('global', '[email_invalid]'));
		}
	}
}

$output = array();

$removeArr = array_map('trim', array_filter(explode(',', we_base_request::_(we_base_request::STRINGC, 'we_remove'))));
$we_reserved = array_merge(array('from', 'we_remove', 'captchaname', 'we_mode', 'charset', 'required', 'order', 'ok_page', 'error_page', 'captcha_error_page', 'mail_error_page', 'recipient', 'subject', 'mimetype', 'confirm_mail', 'pre_confirm', 'post_confirm', 'MAX_FILE_SIZE', session_name(), 'cookie', 'recipient_error_page', 'forcefrom'), $removeArr);

$we_txt = '';
$we_html = '<table>';

if(($_order = we_base_request::_(we_base_request::RAW, 'order', ''))){
	$we_orderarray = explode(',', $_order);

	foreach($we_orderarray as $cur){
		if(!in_array($cur, $we_reserved)){
			$output[$cur] = we_base_request::_(we_base_request::RAW, $cur);
		}
	}
} else {
	$we_orderarray = array();
}

if(isset($_POST)){
	foreach($_POST as $n => $v){
		if((!in_array($n, $we_reserved)) && (!in_array($n, $we_orderarray)) && (!is_array($v))){
			if(!(isset($_COOKIE[$n]) && $_COOKIE[$n] == $v)){//for some reason cookies are transfered as POST's, so filter them, if the data matches in case the field names are the same.
				$output[$n] = $v;
			}
		}
	}
}

foreach($output as $n => $v){
	if(is_array($v)){
		foreach($v as $n2 => $v2){
			if(!is_array($v2)){
				$foo = replace_bad_str($v2);
				$n = replace_bad_str($n);
				$n2 = replace_bad_str($n2);
				$we_txt .= $n . '[' . $n2 . ']: ' . $foo . "\n" . ($foo ? '' : "\n");
				$we_html .= '<tr><td align="right"><b>' . $n . '[' . $n2 . ']:</b></td><td>' . $foo . '</td></tr>';
			}
		}
	} else {
		$foo = replace_bad_str($v);
		$n = replace_bad_str($n);
		$we_txt .= $n . ': ' . $foo . "\n" . ($foo ? '' : "\n");
		$we_html .= '<tr><td valign="top" align="right"><b>' . $n . ':</b></td><td>' . ($n === 'email' ? '<a href="mailto:' . $foo . '">' . $foo . '</a>' : nl2br($foo)) . '</td></tr>';
	}
}

$we_html .= '</table>';


$we_html_confirm = '';
$we_txt_confirm = '';

if(isset($_REQUEST['email']) && $_REQUEST['email']){
	if(isset($_REQUEST['confirm_mail']) && $_REQUEST['confirm_mail']){
		$we_html_confirm = $we_html;
		$we_txt_confirm = $we_txt;
		if(isset($_REQUEST['pre_confirm']) && $_REQUEST['pre_confirm']){
			contains_bad_str($_REQUEST['pre_confirm']);
			$we_html_confirm = $_REQUEST['pre_confirm'] . getHtmlTag('br') . $we_html_confirm;
			$we_txt_confirm = $_REQUEST['pre_confirm'] . "\n\n" . $we_txt_confirm;
		}
		if(isset($_REQUEST['post_confirm']) && $_REQUEST['post_confirm']){
			contains_bad_str($_REQUEST['post_confirm']);
			$we_html_confirm = $we_html_confirm . getHtmlTag('br') . $_REQUEST['post_confirm'];
			$we_txt_confirm = $we_txt_confirm . "\n\n" . $_REQUEST['post_confirm'];
		}
	}
}

$email = (isset($_REQUEST['email']) && $_REQUEST['email']) ?
	$_REQUEST['email'] :
	((isset($_REQUEST['from']) && $_REQUEST['from']) ?
		$_REQUEST['from'] :
		WE_DEFAULT_EMAIL);

$subject = we_base_request::_(we_base_request::STRING, 'subject', WE_DEFAULT_SUBJECT);
$charset = str_replace(array("\n", "\r"), '', we_base_request::_(we_base_request::STRING, 'charset', $GLOBALS['WE_BACKENDCHARSET']));
$recipient = (isset($_REQUEST['recipient']) && $_REQUEST['recipient']) ? $_REQUEST['recipient'] : '';
$from = (isset($_REQUEST['from']) && $_REQUEST['from']) ? $_REQUEST['from'] : WE_DEFAULT_EMAIL;

$mimetype = we_base_request::_(we_base_request::STRING, 'mimetype', '');

$wasSent = false;

if($recipient){
	$subject = preg_replace("/(\\n+|\\r+)/", '', $subject);
	$charset = preg_replace("/(\\n+|\\r+)/", '', $charset);
	$fromMail = preg_replace("/(\\n+|\\r+)/", '', (we_base_request::_(we_base_request::BOOL, 'forcefrom') ? $from : $email));
	$email = preg_replace("/(\\n+|\\r+)/", '', $email);
	$from = preg_replace("/(\\n+|\\r+)/", '', $from);

	contains_bad_str($email);
	contains_bad_str($from);
	contains_bad_str($fromMail);
	contains_bad_str($subject);
	contains_bad_str($charset);

	if(!we_check_email($fromMail)){
		print_error(g_l('global', '[email_invalid]'));
	}

	$recipients = makeArrayFromCSV($recipient);
	$senderForename = we_base_request::_(we_base_request::STRING, 'forename', '');
	$senderSurname = we_base_request::_(we_base_request::STRING, 'surname', '');
	$sender = ($senderForename != '' || $senderSurname ? $senderForename . ' ' . $senderSurname . '<' . $fromMail . '>' : $fromMail);

	$phpmail = new we_util_Mailer('', $subject, $sender);
	$phpmail->setCharSet($charset);

	$recipientsList = array();

	foreach($recipients as $recipientID){

		$recipient = preg_replace("/(\\n+|\\r+)/", '', (is_numeric($recipientID) ?
				f('SELECT Email FROM ' . RECIPIENTS_TABLE . ' WHERE ID=' . intval($recipientID), 'Email', $GLOBALS['DB_WE']) :
				// backward compatible
				$recipientID)
		);

		if(!$recipient){
			print_error(g_l('global', '[email_no_recipient]'));
		}
		if(!we_check_email($recipient)){
			print_error(g_l('global', '[email_invalid]'));
		}

		if(we_check_email($recipient) && check_recipient($recipient)){
			$recipientsList[] = $recipient;
		} else {
			print_error(g_l('global', '[email_recipient_invalid]'));
		}
	}

	if($recipientsList){
		foreach($_FILES as $file){
			if(isset($file['tmp_name']) && $file['tmp_name']){
				$tempName = TEMP_PATH . $file['name'];
				move_uploaded_file($file['tmp_name'], $tempName);
				$phpmail->doaddAttachment($tempName);
			}
		}
		$phpmail->addAddressList($recipientsList);
		if($mimetype === 'text/html'){
			$phpmail->addHTMLPart($we_html);
		} else {
			$phpmail->addTextPart($we_txt);
		}
		$phpmail->buildMessage();
		if($phpmail->Send()){
			$wasSent = true;
		}
	}

	if((isset($_REQUEST['confirm_mail']) && $_REQUEST['confirm_mail']) && FORMMAIL_CONFIRM){
		if($wasSent){
			// validation
			if(!we_check_email($email)){
				print_error(g_l('global', '[email_invalid]'));
			}
			$phpmail = new we_util_Mailer($email, $subject, $from);
			$phpmail->setCharSet($charset);
			if($mimetype === 'text/html'){
				$phpmail->addHTMLPart($we_html_confirm);
			} else {
				$phpmail->addTextPart($we_txt_confirm);
			}
			$phpmail->buildMessage();
			$phpmail->Send();
		}
	}
} else {
	print_error(g_l('global', '[email_no_recipient]'));
}

ok_page($subject);
