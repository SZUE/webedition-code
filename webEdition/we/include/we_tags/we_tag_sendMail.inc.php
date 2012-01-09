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

function we_tag_sendMail($attribs, $content){
	$foo = attributFehltError($attribs, "recipient", "sendMail");
	if ($foo)
		return $foo;
	$foo = attributFehltError($attribs, "from", "sendMail");
	if ($foo)
		return $foo;

	if (!$GLOBALS['we_doc']->InWebEdition) {

		$GLOBALS['DB_WE'] = !isset($GLOBALS['DB_WE']) ? new DB_WE() : $GLOBALS['DB_WE'];
		$id = weTag_getAttribute("id",$attribs, ( isset($_REQUEST["ID"])? $_REQUEST["ID"] : '' ) );
		$from = weTag_getAttribute("from",$attribs);
		$reply = weTag_getAttribute("reply",$attribs);
		$recipient = weTag_getAttribute("recipient",$attribs);
		$recipientCC = weTag_getAttribute("recipientcc",$attribs);
		if ($recipientCC ==='') {$recipientCC = weTag_getAttribute("recipientCC",$attribs);}
		$recipientBCC = weTag_getAttribute("recipientbcc",$attribs);
		if ($recipientBCC ==='') {$recipientBCC = weTag_getAttribute("recipientBCC",$attribs);}

		$mimetype = weTag_getAttribute("mimetype",$attribs);
		$subject = weTag_getAttribute("subject",$attribs);
		$charset = weTag_getAttribute("charset",$attribs,"UTF-8");
		$includeimages = weTag_getAttribute("includeimages",$attribs,false,true);
		if (isset($attribs['useBaseHref'])) {
			$useBaseHref = weTag_getAttribute("useBaseHref",$attribs,true,true);
		} else {
			$useBaseHref = weTag_getAttribute("usebasehref",$attribs,true,true);
		}
		if (isset($attribs['useFormmailLog'])) {
			$useFormmailLog = weTag_getAttribute("useFormmailLog",$attribs,false,true);
		} else {
			$useFormmailLog = weTag_getAttribute("useformmaillog",$attribs,false,true);
		}
		if (isset($attribs['useFormmailBlock'])) {
			$useFormmailBlock = weTag_getAttribute("useFormmailBlock",$attribs,false,true);
		} else {
			$useFormmailBlock = weTag_getAttribute("useformmailblock",$attribs,false,true);
		}
		if($useFormmailBlock) {$useFormmailLog=true;}
		$_blocked = false;

		if (!empty($id)) {

		    $to = explode(",",$recipient);

		    $we_recipient = array();
			for ($l=0;$l < sizeof($to);$l++) {

		    	if (strpos($to[$l],'@')===false) {
		    		if (isset($_SESSION["webuser"]["registered"]) && $_SESSION["webuser"]["registered"] && isset($_SESSION["webuser"][$to[$l]]) && strpos($_SESSION["webuser"][$to[$l]],'@')!==false) { //wenn man registireten Usern was senden moechte
		    			$we_recipient[] = $_SESSION["webuser"][$to[$l]];
		    		} else if(isset($_REQUEST[$to[$l]]) && strpos($_REQUEST[$to[$l]],'@')!==false) {	//email to friend test
		    			$we_recipient[] = $_REQUEST[$to[$l]];
		    		}
		    	} else {
					$we_recipient[] = $to[$l];
		    	}
			}

			$toCC = explode(",",$recipientCC);
		    $we_recipientCC = array();
			for ($l=0;$l < sizeof($toCC);$l++) {

		    	if (strpos("@",$toCC[$l])===false) {
		    		if (isset($_SESSION["webuser"]["registered"]) && $_SESSION["webuser"]["registered"] && isset($_SESSION["webuser"][$toCC[$l]]) && strpos("@",$_SESSION["webuser"][$toCC[$l]])!==false) { //wenn man registrierten Usern was senden moechte
		    			$we_recipientCC[] = $_SESSION["webuser"][$toCC[$l]];
		    		} else if(isset($_REQUEST[$toCC[$l]]) && strpos("@",$_REQUEST[$toCC[$l]])!==false) {	//email to friend test
		    			$we_recipientCC[] = $_REQUEST[$toCC[$l]];
		    		}
		    	} else {
					$we_recipientCC[] = $toCC[$l];
		    	}
			}
			$toBCC = explode(",",$recipientBCC);
		    $we_recipientBCC = array();
			for ($l=0;$l < sizeof($toBCC);$l++) {

		    	if (strpos($toBCC[$l],'@')===false) {
		    		if (isset($_SESSION["webuser"]["registered"]) && $_SESSION["webuser"]["registered"] && isset($_SESSION["webuser"][$toBCC[$l]]) && strpos($_SESSION["webuser"][$toBCC[$l]],'@')!==false) { //wenn man registrierte Usern was senden moechte
		    			$we_recipientBCC[] = $_SESSION["webuser"][$toBCC[$l]];
		    		} else if(isset($_REQUEST[$toBCC[$l]]) && strpos($_REQUEST[$toBCC[$l]],'@')!==false) {	//email to friend test
		    			$we_recipientBCC[] = $_REQUEST[$toBCC[$l]];
		    		}
		    	} else {
					$we_recipientBCC[] = $toBCC[$l];
		    	}
			}

			if ($useFormmailLog) {
				$_ip = $_SERVER['REMOTE_ADDR'];
				$_now = time();

				// insert into log
				$GLOBALS['DB_WE']->query("INSERT INTO " . FORMMAIL_LOG_TABLE . " (ip, unixTime) VALUES('".$GLOBALS['DB_WE']->escape($_ip)."', UNIX_TIMESTAMP())" );
				if (defined("FORMMAIL_EMPTYLOG") && (FORMMAIL_EMPTYLOG > -1)) {
					$GLOBALS['DB_WE']->query("DELETE FROM " . FORMMAIL_LOG_TABLE . " WHERE unixTime < " . intval($_now - FORMMAIL_EMPTYLOG));
				}

				if ($useFormmailBlock) {

					$_num = 0;
					$_trials = (defined("FORMMAIL_TRIALS") ? FORMMAIL_TRIALS : 3);
					$_blocktime = (defined("FORMMAIL_BLOCKTIME") ? FORMMAIL_BLOCKTIME : 86400);

					// first delete all entries from blocktable which are older then now - blocktime
					$GLOBALS['DB_WE']->query("DELETE FROM " . FORMMAIL_BLOCK_TABLE . " WHERE blockedUntil != -1 AND blockedUntil < UNIX_TIMESTAMP()");

					// check if ip is allready blocked
					if (f("SELECT id FROM " . FORMMAIL_BLOCK_TABLE . " WHERE ip='" . $GLOBALS['DB_WE']->escape($_ip) . "'","id",$GLOBALS['DB_WE'])) {
						$_blocked = true;
					} else {

						// ip is not blocked, so see if we need to block it
						$GLOBALS['DB_WE']->query("SELECT * FROM " . FORMMAIL_LOG_TABLE . " WHERE unixTime > " . intval($_now - FORMMAIL_SPAN) . " AND ip='". $GLOBALS['DB_WE']->escape($_ip) . "'");
						if ($GLOBALS['DB_WE']->next_record()) {
							$_num = $GLOBALS['DB_WE']->num_rows();
							if ($_num > $_trials) {
								$_blocked = true;
								// cleanup
								$GLOBALS['DB_WE']->query("DELETE FROM " . FORMMAIL_BLOCK_TABLE . " WHERE ip='" . $GLOBALS['DB_WE']->escape($_ip) . "'" );
								// insert in block table
								$blockedUntil = ($_blocktime == -1) ? -1 : intval($_now + $_blocktime);
								$GLOBALS['DB_WE']->query("INSERT INTO " . FORMMAIL_BLOCK_TABLE . " (ip, blockedUntil) VALUES('".$GLOBALS['DB_WE']->escape($_ip)."', " . $blockedUntil . ")" );
							}
						}
					}
				}


			}
			if ($_blocked) {
				$headline = "Fehler / Error";
				$content =		g_l('global',"[formmailerror]").getHtmlTag("br")	.	"&#8226; "."Email dispatch blocked / Email Versand blockiert!";
				$css = array('media' => 'screen','rel'	=> 'stylesheet','type'	=> 'text/css','href'	=> WEBEDITION_DIR."css/global.php?WE_LANGUAGE=".$GLOBALS["WE_LANGUAGE"]."&amp;WE_BACKENDCHARSET=".$GLOBALS["WE_BACKENDCHARSET"]);

				print we_html_tools::htmlTop();
				print getHtmlTag("link", $css);
				print "</head>";
				print getHtmlTag("body", array("class"=>"weEditorBody"), "", false, true);
				print we_html_tools::htmlDialogLayout(getHtmlTag("div", array("class" => "defaultgray"), $content),$headline);
				print "</body></html>";

				exit;
			}
			if(!$_blocked) {
				if (!isset($_SESSION)) {
					@session_start();
				}
				$_SESSION['WE_SendMail']=true;
				$codes = we_getDocumentByID($id);
				unset($_SESSION['WE_SendMail']);
			    $phpmail = new we_util_Mailer($we_recipient,$subject,$from,$reply,$includeimages);
				if(isset($includeimages)) {$phpmail->setIsEmbedImages($includeimages);}
				if(!empty($we_recipientCC)){$phpmail->setCC($we_recipientCC);}
				if(!empty($we_recipientBCC)){$phpmail->setBCC($we_recipientBCC);}
				if(isset($useBaseHref)){$phpmail->setIsUseBaseHref($useBaseHref);}
			    $phpmail->setCharSet($charset);
				if ($mimetype != "text/html") {
					$phpmail->addTextPart(strip_tags(str_replace("&nbsp;"," ",str_replace("<br />","\n",str_replace("<br>","\n",$codes)))));
				} else {
					$phpmail->addHTMLPart($codes);
				}
			    $phpmail->buildMessage();
			    $phpmail->Send();
			}
		}
	}
	return;
}
