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
 * @package    webEdition_language
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
/**
 * Language file: metadata.inc.php
 * Provides language strings.
 * Language: Finnish
 */
/* * ***************************************************************************
 * DOCUMENT TAB
 * *************************************************************************** */
$l_metadata = array(
		'filesize' => "Tiedostokoko",
		'supported_types' => "Metatieto formaatit",
		'none' => "ei mitään",
		'filetype' => "Tiedostotyyppi",
		/*		 * ***************************************************************************
		 * METADATA FIELD MAPPING
		 * *************************************************************************** */

		'headline' => "Metatieto kentät",
		'tagname' => "Kentän nimi",
		'type' => "Tyyppi",
		'dummy' => "esimerkki",
		'save' => "Metatietoja tallennetaan, odota pieni hetki...",
		'save_wait' => "Tallennetaan asetuksia",
		'saved' => "Metatietokentät tallennettu.",
		'saved_successfully' => "Metatietokentät tallennettu",
		'properties' => "Ominaisuudet",
		'fields_hint' => "Määrittele lisäkenttiä metatiedolle. Liitetty data saatetaan automaattisesti muuttaa tuonnin yhteydessä. Lisää syöttökenttään yksi tai useampi kenttä jotka tuodaan &quot;import from&quot; formaattiin &quot;[tyyppi]/[kenttänimi]&quot;. Esimerkiksi &quot;exif/copyright,iptc/copyright&quot;. Useita kenttiä voidaan laittaa erottamalla ne pilkulla. Tuonti etsii kaikki määritellyt kentät kaikista kentistä joissa on jotain dataa.",
		'import_from' => "Tuo kohteesta",
		'fields' => "Kentät",
		'add' => "lisää",
		/*		 * ***************************************************************************
		 * UPLOAD
		 * *************************************************************************** */

		'import_metadata_at_upload' => "Tuo metatieto tiedostosta",
		/*		 * ***************************************************************************
		 * ERROR MESSAGES
		 * *************************************************************************** */

		'error_meta_field_empty_msg' => "Kentän nimi rivillä %s1 ei voi olla tyhjä!",
		'meta_field_wrong_chars_messsage' => "Kentän nimi '%s1' on virheellinen! Sallitut kirjaimet ovat (a-z, A-Z, 0-9) ja alaviiva.",
		'meta_field_wrong_name_messsage' => "Kentän nimi '%s1' on virheellinen! Se on sisäisesti webEditionin käytössä! Seuraavat nimet ovat virheellisiä, eikä voida käyttää: %s2",
		'file_size_0' => 'The file size is 0 byte, please upload a document to the server before saving', // TRANSLATE

		/*		 * ***************************************************************************
		 * INFO TAB
		 * *************************************************************************** */

		'info_exif_data' => "Exif data",
		'info_iptc_data' => "IPTC data",
		'no_exif_data' => "Ei Exif dataa saatavilla",
		'no_iptc_data' => "Ei IPTC dataa saatavilla",
		'no_exif_installed' => "PHP Exif -lisäosaa ei asennettu!",
		'no_metadata_supported' => "webEdition ei tue metadata formaatteja tämänkaltaisissa dokumenteissa.",
);