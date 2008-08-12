<?php

// +----------------------------------------------------------------------+
// | webEdition                                                           |
// +----------------------------------------------------------------------+
// | PHP version 4.1.0 or greater                                         |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000 - 2007 living-e AG                                |
// +----------------------------------------------------------------------+
//

/**
 * Language file: enc_parser.inc.php
 * Provides language strings.
 * Language: English
 */
$GLOBALS["l_parser"]["delete"] = "Delete"; // TRANSLATE
$GLOBALS["l_parser"]["wrong_type"] = "Arvo \"tyyppi\" on virheellinen!";
$GLOBALS["l_parser"]["error_in_template"] = "Sivupohjavirhe!";
$GLOBALS["l_parser"]["start_endtag_missing"] = "Yksi tai useampi <code>&lt;we:%s&gt;</code> tagi puuttuu!";
$GLOBALS["l_parser"]["tag_not_known"] = "Tagi <code>'&lt;we:%s&gt;'</code> on tuntematon!";
$GLOBALS["l_parser"]["else_start"] = "Tagi <code>&lt;we:else/&gt;</code> on  <code>&lt;we:if...&gt;</code> ilman aloitustagia!";
$GLOBALS["l_parser"]["else_end"] = "Tagi <code>&lt;we:else/&gt;</code> <code>&lt;/we:if...&gt;</code> ilman lopetustagia!";
$GLOBALS["l_parser"]["attrib_missing"] = "M��ritys '%s' tagista <code>&lt;we:%s&gt;</code> puuttuu tai on tyhj�!";
$GLOBALS["l_parser"]["attrib_missing2"] = "M��ritys '%s' tagista <code>&lt;we:%s&gt;</code> puuttuu!";
$GLOBALS["l_parser"]["missing_open_tag"] = "<code>&lt;%s&gt;</code>: Aloitustagi puuttuu.";
$GLOBALS["l_parser"]["missing_close_tag"] = "<code>&lt;%s&gt;</code>: Lopetustagi puuttuu.";
$GLOBALS["l_parser"]["name_empty"] = "Tagin nimi <code>&lt;we:%s&gt;</code> on tyhj�!";
$GLOBALS["l_parser"]["invalid_chars"] =  "The name of the tag <code>&lt;we:%s&gt;</code> virheellisi� kirjaimia. Vain alfa-numeeriset, kirjaimet/numero, '-' ja '_' ovat sallittuja!";
$GLOBALS["l_parser"]["name_to_long"] =  "Tagin nimi <code>&lt;we:%s&gt;</code> liian pitk�! Tagi voi olla enint�� 255 merkki� pitk�!";
$GLOBALS["l_parser"]["name_with_space"] =  "Tagin <code>&lt;we:%s&gt;</code> nimess� ei saa olla v�lily�ntej�!";
$GLOBALS["l_parser"]["client_version"] = "M��ritys 'version' tagissa <code>&lt;we:ifClient&gt;</code> on virheellinen!";
$GLOBALS["l_parser"]["html_tags"] = "Sivupohja voi sis�lt�� vain HTML -tageja <code>&lt;html&gt; &lt;head&gt; &lt;body&gt;</code> tai ei tageja ollenkaan. Muutoin, parseri ei toimi oikein!";
$GLOBALS["l_parser"]["field_not_in_lv"] = "Tagi  <code&gt;</code>&lt;we:field&gt;</code>-on suljettava <code&gt;</code>&lt;we:listview&gt;</code> tai <code&gt;</code>&lt;we:object&gt;</code> aloitus -ja lopetustagilla!";
$GLOBALS["l_parser"]["setVar_lv_not_in_lv"] = "Tagi <code>&lt;we:setVar from=\"listview\" ... &gt;</code> vaatii lopetustagin: <code>&lt;we:listview&gt;</code>!";
$GLOBALS["l_parser"]["checkForm_jsIncludePath_not_found"] = "Tagin <code>&lt;we:checkForm&gt;</code> m��re jsIncludePath annettiin numerona (ID). Mutta j�rjestelm�ss� ei ole dokumenttia kyseisell� ID:ll�!";
$GLOBALS["l_parser"]["checkForm_password"] = "Tagin <code>&lt;we:checkForm&gt;</code> m��re password on oltava kolmeosainen, eroteltuna pilkuilla!";
$GLOBALS["l_parser"]["missing_createShop"] = "Tagia <code>&lt;we:%s&gt;</code> voidaan k�ytt�� vain tagin <code>&lt;we:createShop&gt; j�lkeen</code>.";
$GLOBALS["l_parser"]["multi_object_name_missing_error"] = "Error: The object field &quot;%s, specified in the attribute &quot;name&quot;, does not exist!"; // TRANSLATE
$GLOBALS["l_parser"]["template_recursion_error"] = "Error: Too much recursion!"; // TRANSLATE
?>