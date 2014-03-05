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
require_once(WE_MODULES_PATH . 'shop/we_conf_shop.inc.php');

/**
 * This function writes the shop data (order) to the database and send values to saferpay
 *
 * @param          $attribs array
 *
 * @return         void
 */
function we_tag_saferpay($attribs){
	global $DB_WE;
	$name = weTag_getAttribute('name', $attribs);

	if(($foo = attributFehltError($attribs, 'pricename', __FUNCTION__))){
		return $foo;
	}
	if(!$name){
		if(($foo = attributFehltError($attribs, 'shopname', __FUNCTION__))){
			return $foo;
		}
	}

	$shopname = weTag_getAttribute('shopname', $attribs);
	$shopname = $shopname ? $shopname : $name;
	$pricename = weTag_getAttribute('pricename', $attribs);
	$shipping = weTag_getAttribute('shipping', $attribs);
	$shippingIsNet = weTag_getAttribute('shippingisnet', $attribs, false, true);
	$shippingVatRate = weTag_getAttribute('shippingvatrate', $attribs);
	$languagecode = weTag_getAttribute('languagecode', $attribs);

	$onsuccess = weTag_getAttribute('onsuccess', $attribs);
	$onfailure = weTag_getAttribute('onfailure', $attribs);
	$onabortion = weTag_getAttribute('onabortion', $attribs);


	$netprices = weTag_getAttribute('netprices', $attribs, true, true);
	$useVat = weTag_getAttribute('usevat', $attribs, false, true);

	if($useVat){
		if(isset($_SESSION['webuser'])){
			$_customer = $_SESSION['webuser'];
		} else {
			$_customer = false;
		}

		$weShopVatRule = we_shop_vatRule::getShopVatRule();
		$calcVat = $weShopVatRule->executeVatRule($_customer);
	}

	// var_dump($attribs);
	if(isset($GLOBALS[$shopname])){
		$basket = $GLOBALS[$shopname];

		$shoppingItems = $basket->getShoppingItems();
		$cartFields = $basket->getCartFields();

		if(empty($shoppingItems)){
			return;
		}
		/*		 * ***** get the currency ******* */
		$feldnamen = explode("|", f("SELECT strFelder from " . ANZEIGE_PREFS_TABLE . " where strDateiname = 'shop_pref'"));
		if(isset($feldnamen[0])){ // determine the currency
			if($feldnamen[0] == "$" || $feldnamen[0] == "USD"){
				$currency = "USD";
			} elseif($feldnamen[0] == "�" || $feldnamen[0] == "GBP"){
				$currency = "GBP";
			} elseif($feldnamen[0] == "AUD"){
				$currency = "AUD";
			} elseif($feldnamen[0] == "CHF" || $feldnamen[0] == "SFR"){
				$currency = "CHF";
			} elseif($feldnamen[0] == "CAD"){
				$currency = "CAD";
			} else {
				$currency = "EUR";
			}
		} else {
			$currency = "EUR";
		}
		/*		 * ***** get the currency ******* */

		/*		 * **** get the preferences ***** */
		$formField = explode("|", f("SELECT strFelder from " . ANZEIGE_PREFS_TABLE . " where strDateiname = 'payment_details'", 'strFelder', $GLOBALS['DB_WE']));
		if($languagecode == ''){
			if(isset($formField[8])){ // determine the language
				$langID = $formField[8];
			}
		} else {
			$langID = $languagecode;
		}
		if(isset($formField[9])){ // determine the Notify-Email
			$accountID = $formField[9];
		}
		if(isset($formField[10])){ // determine the Notify-Email
			$notifyAddr = $formField[10];
		}
		if(isset($formField[11])){ // determine the  notify-Email
			$allowColl = $formField[11];
		}
		if(isset($formField[12])){ // determine the delivery if yes or no
			$delivery = $formField[12];
		}
		if(isset($formField[13])){ // determine the user notify if yes or no
			$userNotify = $formField[13];
		}
		if(isset($formField[14])){ // determine the providerset
			$providerset = $formField[14];
		}
		if(isset($formField[15])){ // determine the cmd path
			$execPath = $formField[15];
		}
		if(isset($formField[16])){ // determine the conf path
			$confPath = $formField[16];
		}
		if(isset($formField[17])){ // determine the conf path
			$desc = $formField[17];
		}
		/*		 * **** get the preferences ***** */

		/*		 * **** get the further links ***** */
		$successprelink = id_to_path($onsuccess);
		$successlink = getServerUrl() . $successprelink;
		//print $successlink;

		$failureprelink = id_to_path($onfailure);
		$failurelink = getServerUrl() . $failureprelink;
		//print $failurelink;

		$abortionprelink = id_to_path($onabortion);
		$abortionlink = getServerUrl() . $abortionprelink;
		//print $failurelink;
		/*		 * **** get the further links ***** */


		$summit = 0;
		foreach($shoppingItems as $key => $item){

			$itemTitle = (isset($item['serial']['we_' . WE_SHOP_TITLE_FIELD_NAME]) ? $item['serial']['we_' . WE_SHOP_TITLE_FIELD_NAME] : $item['serial'][WE_SHOP_TITLE_FIELD_NAME]);
			$itemPrice = (isset($item['serial']["we_" . $pricename]) ? $item['serial']["we_" . $pricename] : $item['serial'][$pricename]);

			// foreach article we must determine the correct tax-rate
			$vatId = isset($item['serial'][WE_SHOP_VAT_FIELD_NAME]) ? $item['serial'][WE_SHOP_VAT_FIELD_NAME] : 0;
			$shopVat = we_shop_vats::getVatRateForSite($vatId, true, false);
			if($shopVat){ // has selected or standard shop rate
				$$item['serial'][WE_SHOP_VAT_FIELD_NAME] = $shopVat;
			} else { // could not find any shoprates, remove field if necessary
				if(isset($shoppingItem['serial'][WE_SHOP_VAT_FIELD_NAME])){
					unset($shoppingItem['serial'][WE_SHOP_VAT_FIELD_NAME]);
				}
			}


			if($netprices){
				$totalVat = $itemPrice / 100 * $shopVat;
				$totalVats = number_format($totalVat, 2, '.', '');
				// add the polychronic taxes
				// $totalVats;
			}

			// determine the shipping cost by accumulating the total
			$summit += ($itemPrice * $item['quantity'] + $totalVats);
		}


		//get the shipping costs

		$weShippingControl = we_shop_shippingControl::getShippingControl();

		$customer = (we_tag('ifRegisteredUser') ? // check if user is registered
				$_SESSION['webuser'] : false);

		if($shipping == ''){
			$cartField[WE_SHOP_SHIPPING] = array(
				'costs' => $weShippingControl->getShippingCostByOrderValue($summit, $customer),
				'isNet' => $weShippingControl->isNet,
				'vatRate' => $weShippingControl->vatRate
			);
		} else {
			$cartField[WE_SHOP_SHIPPING] = array(
				'costs' => $shipping,
				'isNet' => $shippingIsNet,
				'vatRate' => $shippingVatRate
			);
		}


		$shippingCosts = $cartField[WE_SHOP_SHIPPING]['costs'];
		$isNet = $cartField[WE_SHOP_SHIPPING]['isNet'];
		$vatRate = $cartField[WE_SHOP_SHIPPING]['vatRate'];
		$shippingCostVat = $shippingCosts / 100 * $vatRate;
		$shippingFee = $shippingCosts + $shippingCostVat;

		// sum all costs
		$totalSum = $summit + $shippingFee;
		// to be reserved in minor currency unit e.g. EUR 1.35 must be passed as 135
		$strAmount = str_replace("-", "", number_format($totalSum, 2, '-', ''));

########################### submit starts here #########################

		$attributes = array("-a", "AMOUNT", (int) $strAmount,
			"-a", "CURRENCY", $currency,
			"-a", "DESCRIPTION", $desc,
			"-a", "ALLOWCOLLECT", $allowColl,
			"-a", "DELIVERY", $delivery,
			"-a", "ACCOUNTID", $accountID,
			"-a", "BACKLINK", $abortionlink,
			"-a", "FAILLINK", $failurelink,
			"-a", "SUCCESSLINK", $successlink,
			"-a", "ORDERID", $_SESSION['webuser']['ID'],
			"-a", "PROVIDERSET", $providerset,
			"-a", "LANGID", $langID,
			"-a", "NOTIFYADDRESS", $notifyAddr
		);


		$_SESSION['strAmount'] = $strAmount;
		$strAttributes = join(" ", $attributes);

		/*		 * ** debugging *** */
		//print $strAttributes."\n<br/>";
		// print "<br/>".$execPath;
		// print "<br/>".$confPath;
		// var_dump($attribs);
		// print $langID;
		/*		 * ** debugging *** */

		switch($langID){
			case "de" :
				$processOK = 'Bitte haben Sie einen Moment Geduld.<br>Falls sich kein Fenster &ouml;ffnet klicken Sie bitte <a href="' . $payinit_url . '" onclick="OpenSaferpayTerminal(\'' . $payinit_url . '\', this, \'LINK\');">hier</a>';
				$processError = 'Leider gab es Probleme mit der Abbuchung. Bitte versuchen Sie es sp&auml;ter erneut.';
				break;
			case "en" :
				$processOK = 'This will take some seconds.<br>If no window opens please click <a href="' . $payinit_url . '" onclick="OpenSaferpayTerminal(\'' . $payinit_url . '\', this, \'LINK\');">here</a>';
				$processError = 'A major problem occured. Please try again later.';
				break;
			case "fr" :
				$processOK = 'Soyez patient, cela prendra quelques secondes.<br>Si aucune  fen�tre s affiche, cliquez <a href="' . $payinit_url . '" onclick="OpenSaferpayTerminal(\'' . $payinit_url . '\', this, \'LINK\');">ici</a>';
				$processError = 'Une erreur Une erreur s est produite. S il vous pla�t, essayez de nouveau ult�rieurement..';
				break;
			case "it" :
				$processOK = 'Sia prego paziente.<br>Se nessuna finestra apre, clicca <a href="' . $payinit_url . '" onclick="OpenSaferpayTerminal(\'' . $payinit_url . '\', this, \'LINK\');">prego qui</a>';
				$processError = 'Un errore grave � occorso. Prego prova ancora successivamente..';
				break;
			default:
				$processOK = 'Bitte haben Sie einen Moment Geduld.<br>Falls sich kein Fenster &ouml;ffnet klicken Sie bitte <a href="' . $payinit_url . '" onclick="OpenSaferpayTerminal(\'' . $payinit_url . '\', this, \'LINK\');">hier</a>';
				$processError = 'Leider gab es Probleme mit der Abbuchung. Bitte versuchen Sie es sp&auml;ter erneut.';
		}


		/* command line */
		$command = $execPath . "saferpay -payinit -p $confPath $strAttributes";

		if(!$execPath || !$confPath){
			echo g_l('modules_shop', '[saferpayError]').
				$strAttributes;
			exit;
		} else {

			/* get the payinit URL */
			$fp = popen($command, "r");
			$payinit_url = str_replace(array("\n", "\r"), '', fread($fp, 4096));
		}

		if($payinit_url){
			echo $processOK .
				we_html_element::jsElement('	OpenSaferpayWindowJScript(\'' . $payinit_url . '\');');
		} else {
			echo $processError;
		}

//data in DB
		we_tag('writeShopData', $attribs);
	}
	return;
}
