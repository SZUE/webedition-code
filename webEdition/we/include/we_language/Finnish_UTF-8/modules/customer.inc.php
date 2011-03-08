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
 * @package    webEdition_language
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
/**
 * Language file: customer.inc.php
 * Provides language strings.
 * Language: English
 */
$l_modules_customer = array(
		'menu_customer' => "Asiakas",
		'menu_new' => "Uusi",
		'menu_save' => "Tallenna",
		'menu_delete' => "Poista",
		'menu_exit' => "Poistu",
		'menu_info' => "Tietoja",
		'menu_help' => "Ohje",
		'menu_admin' => "Hallinta",
		'save_changed_customer' => "Asiakas on muokattu.\\nHaluatko tallentaa muutokset?",
		'customer_saved_ok' => "Asiakas '%s' on tallennettu.",
		'customer_saved_nok' => "Asiakasta '%s' ei voitu tallentaa.",
		'nothing_to_save' => "Ei tallennettavaa",
		'username_exists' => "Käyttäjä '%s' on jo olemassa!",
		'username_empty' => "Käyttäjänimeä ei ole syötetty!",
		'password_empty' => "Salasanaa ei ole syötetty!",
		'customer_deleted' => "Asiakas on poistettu.",
		'nothing_to_delete' => "Ei poistettavaa!",
		'no_space' => "Välilyönnit eivät ole sallittuja.",
		'customer_data' => "Asiakkaan tiedot",
		'first_name' => "Etunimi",
		'second_name' => "Sukunimi",
		'username' => "Käyttäjänimi",
		'password' => "Salasana",
		'login' => "Kirjaudu",
		'login_denied' => "Pääsy kielletty",
		'autologin' => "Auto-Login", // TRANSLATE
		'autologin_request' => "requested", // TRANSLATE

		'permissions' => "Oikeudet",
		'password_alert' => "Salasanan on oltava vähintään 4 merkkiä pitkä.",
		'delete_alert' => "Poista kaikki asiakkaan tiedot.\\n Oletko varma?",
		'created_by' => "Luonut",
		'changed_by' => "Muutettu",
		'no_perms' => "Sinulla ei ole oikeuksia suorittaa tätä toimintoa.",
		'topic' => "Aihe",
		'not_nummer' => "Ensimmäinen merkki ei voi olla numero.",
		'field_not_empty' => "Kenttä on nimettävä.",
		'delete_field' => "Oletko varma että haluat poistaa tämän kentän? Poistoa seuraamuksia ei voi peruuttaa.",
		'display' => "Näytä",
		'insert_field' => "Lisää kenttä",
//---- new things

		'customer' => "Asiakas",
		'common' => "Yleiset",
		'all' => "Kaikki",
		'sort' => "Lajittelu",
		'branch' => "Näkymä",
		'field_name' => "Nimi",
		'field_type' => "Tyyppi",
		'field_default' => "Oletus",
		'add_mail' => "Lisää E-mail",
		'edit_mail' => "Muokkaa E-mail",
		'no_branch' => "Mitää näkymää ei ole valittu!",
		'no_field' => "Mitään kenttää ei ole valittu!",
		'field_saved' => "Kenttä on tallennettu.",
		'field_deleted' => "Kenttä on poistettu %s näkymästä.",
		'del_fild_question' => "Haluatko poistaa kentän?",
		'field_admin' => "Kenttien hallinta",
		'sort_admin' => "Lajittelun hallinta",
		'name' => "Nimi",
		'sort_branch' => "Näkymä",
		'sort_field' => "Kenttä",
		'sort_order' => "Järjestys",
		'sort_saved' => "Järjestys on tallennettu.",
		'sort_name' => "järjestys",
		'sort_function' => "Funktio",
		'no_sort' => "--Ei lajittelua--",
		'branch_select' => "Valitse näkymä",
		'fields' => "Kentät",
		'add_sort_group' => "Lisää uusi ryhmä",
		'search' => "Haku",
		'search_for' => "Hae",
		'simple_search' => "Yksinkertainen haku",
		'advanced_search' => "Kehittynyt haku",
		'search_result' => "Tulos",
		'no_value' => "[-Ei arvoa-]",
		'other' => "Muu",
		'cannot_save_property' => "Kenttä '%s' on suojattu ja sitä ei voida tallentaa!",
		'settings' => "Asetukset",
		'Username' => "Käyttäjätunnus",
		'Password' => "Salasana",
		'Forname' => "Etunimi",
		'Surname' => "Sukunimi",
		'MemeberSince' => "Asiakas alkaen",
		'LastLogin' => "Viimeksi kirjautunut",
		'LastAccess' => "Viimeisin sivupyyntö",
		'default_date_type' => "Päiväyksen oletusmuotoilu",
		'custom_date_format' => "Mukautettu päiväyksen muotoilu",
		'default_sort_view' => "Oletus järjestysnäkymä",
		'unix_ts' => "Unix timestamp",
		'mysql_ts' => "MySQL timestamp",
		'start_year' => "Aloitus vuosi",
		'settings_saved' => "Asetukset on tallennettu.",
		'settings_not_saved' => "Asetuksia ei saatu tallennettua!",
		'data' => "Tiedot",
		'add_field' => "Lisää kenttä",
		'edit_field' => "Muokkaa kenttää",
		'edit_branche' => "Muokkaa näkymää",
		'not_implemented' => "ei toteutettu",
		'branch_no_edit' => "Tämä alue on suojattu ja sitä ei voi muuttaa!",
		'name_exists' => "Annettu nimi on jo olemassa!",
		'import' => "Tuo",
		'export' => "Vie",
		'export_title' => "Vientivelho",
		'import_title' => "Tuontivelho",
		'export_step1' => "Vientimuoto",
		'export_step2' => "Valitse asiakkaat",
		'export_step3' => "Vie tieto",
		'export_step4' => "Vienti päättynyt",
		'import_step1' => "Tuontimuoto",
		'import_step2' => "Tuo tiedot",
		'import_step3' => "Valitse tiedosto",
		'import_step4' => "Määritä tietokentät",
		'import_step5' => "Export finished",
		'file_format' => "Tiedostomuoto",
		'export_to' => "Vie kohteeseen",
		'export_to_server' => "Palvelin",
		'export_to_ftp' => "FTP",
		'export_to_local' => "Paikallinen",
		'ftp_host' => "Isäntänimi",
		'ftp_username' => "Käyttäjätunnus",
		'ftp_password' => "Salasana",
		'filename' => "Tiedostonimi",
		'path' => "Polku",
		'xml_format' => "XML",
		'csv_format' => "CSV",
		'csv_delimiter' => "Erotin",
		'csv_enclose' => "Suljin",
		'csv_escape' => "Koodinvaihtomerkki (escape)",
		'csv_lineend' => "Rivin lopetus",
		'import_charset' => "Import charset", // TRANSLATE
		'csv_null' => "NULL korvaaja",
		'csv_fieldnames' => "Kenttänimet ensimmäisellä rivillä",
		'generic_export' => "Yleinen vienti",
		'gxml_export' => "Yleinen-XML vienti",
		'txt_gxml_export' => "Vienti \"flat\" XML tiedostoon. Tiedoston kentät tullaan määrittämään vastaamaan webEditionin tietokenttiä.",
		'csv_export' => "CSV vienti",
		'txt_csv_export' => "Vienti CSV (Comma Separated Values) tiedostoon tai muuhun valittuun tekstimuotoon (esim. *.txt). Tiedoston kentät tullaan määrittämään vastaamaan webEditionin tietokenttiä.",
		'csv_params' => "CSV tiedoston asetukset",
		'filter_selection' => "Suodatettu valinta",
		'manual_selection' => "Manuaalinen valinta",
		'sortname_empty' => "Lajittelun nimi on tyhjä!",
		'fieldname_exists' => "Annettu kenttänimi on jo käytössä!",
		'treetext_format' => "Puun tekstin muotoilu",
		'we_filename_notValid' => "Käyttäjänimi ei ole sallittu!\\n Sallitut merkit ovat alfa-numeerisia, isot -ja pienet kirjaimet, ala -ja tavuviiva, piste ja välilyönti (a-z, A-Z, 0-9, _, -, ., )",
		'windows' => "Windows muoto",
		'unix' => "UNIX muoto",
		'mac' => "Mac muoto",
		'comma' => ", {pilkku}",
		'semicolon' => "; {puolipiste}",
		'colon' => ": {kaksoispiste}",
		'tab' => "\\t {tabulaattori}",
		'space' => "  {välilyönti}",
		'double_quote' => "\" {lainausmerkki}",
		'single_quote' => "' {heittomerkki}",
		'exporting' => "Viedään...",
		'cdata' => "Koodataan",
		'export_xml_cdata' => "Lisää CDATA -kentät",
		'export_xml_entities' => "Korvaa XML -kokonaisuus",
		'export_finished' => "Vienti päättynyt.",
		'server_finished' => "Vientitiedosto on tallennettu palvelimelle.",
		'download_starting' => "Vientitiedoston lataus on aloitettu.<br><br>Jos siirto ei ala 10 sekunnin kuluessa,<br>",
		'download' => "klikkaa tästä.",
		'download_failed' => "Pyytämääsi tiedostoa ei ole olemassa tai sinulla ei ole oikeutta ladata sitä.",
		'generic_import' => "Yleinen vienti",
		'gxml_import' => "Yleinen XML vienti",
		'txt_gxml_import' => "Tuo \"flat\" XML tiedostoja. Tietueen kenttien täytyy täsmätä asiakashallinnan kenttiin.",
		'csv_import' => "CSV tuonti",
		'txt_csv_import' => "Tuo CSV -tiedostosta (Comma Separated Values) tai muusta tekstityyppiseen tiedostosta (esim. *.txt). Tiedoston kentät muutetaan webEdition -järjestelmän asiakashallinnan kentiksi",
		'source_file' => "Lähdetiedosto",
		'server_import' => "Tuo tiedosto palvelimelta",
		'upload_import' => "Tuo tiedosto paikalliselta kovalevyltä.",
		'file_uploaded' => "Tiedosto on ladattu",
		'num_data_sets' => "Tiedostot:",
		'to' => "Kohde",
		'well_formed' => "XML dokumentti on oikein muodostettu (well-formed).",
		'select_elements' => "Valitse tuotavat tietueet.",
		'xml_valid_1' => "XML tiedosto on validi ja sisältää",
		'xml_valid_m2' => 'XML lapsisolmu ensimmäisellä tasolla eri nimellä. Valitse XML solmu ja tuotavien elementtien määrä.',
		'not_well_formed' => 'XML-dokumentti ei ole oikein muodostettu (well-formed) joten sitä ei voida tuoda.',
		'missing_child_node' => "XML -dokumentti on oikein määritelty, mutta ei sisällä XML -solmuja joten sitä ei voida tuoda.",
		'none' => "-- ei mitään --",
		'any' => "-- ei mitään --",
		'we_flds' => "webEdition&nbsp;kentät",
		'rcd_flds' => "Tietueen&nbsp;kentät",
		'attributes' => "Attribuutti",
		'we_title' => "Otsikko",
		'we_description' => "Kuvaus",
		'we_keywords' => "Avainsanat",
		'pfx' => "Etuliite",
		'pfx_doc' => "Dokumentti",
		'pfx_obj' => "Objekti",
		'rcd_fld' => "Tietueen kenttä",
		'auto' => "Automaattinen",
		'asgnd' => "Määritelty",
		'remark_csv' => 'Voit tuoda CSV (Comma Separated Values) tiedostoja ja mukautettuja tekstitiedostoja (esim. *.txt). Kenttien erottimet (esim. , ; tab, space) ja tekstin rajoitusmerkki (= joka sulkee tekstisyötteet) voivat olla mukana näissä tiedostomuodoissa.',
		'remark_xml' => 'Tuodessasi isoja tiedostoja valitse valitse vaihtoehto "Tuo tietueet erillisinä" välttääksesi ennaltamääritellyn PHP-skriptien aikakatkaisun.<br>Jos olet epävarma siitä onko tiedosto webEdition XML-formaatin mukainen, sen muoto ja syntaksi voidaan testata.',
		'record_field' => "Tietueen kenttä",
		'missing_filesource' => "Lähdetiedosto on tyhjä! Valitse lähdetiedosto.",
		'importing' => "Tuodaan",
		'same_names' => "Samat nimet",
		'same_rename' => "Uudelleennimeä",
		'same_overwrite' => "Ylikirjoita",
		'same_skip' => "Ohita",
		'rename_customer' => "Asiakas '%s' on uudelleennimetty asiakkaaksi '%s'",
		'overwrite_customer' => "Asiakas '%s' on ylikirjoitettu",
		'skip_customer' => "Asiakas '%s' on ohitettu",
		'import_finished_desc' => "%s uutta asiakasta on tuotu!",
		'show_log' => " Varoitusta",
		'import_step5' => "Tuonti päättynyt",
		'view' => "Näkymä",
		'registered_user' => "Rekisteröitynyt käyttäjä",
		'unregistered_user' => "Rekisteröimätön käyttäjä",
		'default_soting_no_del' => "Lajittelua käytetään asetuksissa joten sitä ei voida poistaa!",
		'we_fieldname_notValid' => "Virheellinen kentän nimi!\\nSallitut merkit ovat alfa-numeerisia, isot -ja pienet kirjaimet, ala -ja tavuviiva, piste ja välilyönti (a-z, A-Z, 0-9, _, -, .)",
		'orderTab' => 'Tämän asiakkaan tilaukset',
		'default_order' => 'esiasetettu tilaus',
		'ASC' => 'ascending', // TRANSLATE
		'DESC' => 'descending', // TRANSLATE

		'connected_with_customer' => "Yhdistettu asiakkaaseen",
		'one_customer' => "Asiakas",
		'sort_edit_fields_explain' => "If a field is apparently not moving, it moves along fields in other branches, not visible here", // TRANSLATE
);