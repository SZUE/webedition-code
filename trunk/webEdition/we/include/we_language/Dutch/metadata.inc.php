<?php

// +----------------------------------------------------------------------+
// | webEdition                                                           |
// +----------------------------------------------------------------------+
// | PHP version 4.1.0 or greater                                         |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000 - 2007 living-e AG                                |
// +----------------------------------------------------------------------+
//
// $Id: thumbnails.inc.php,v 1.10 2007/06/20 07:22:24 damjan.denic Exp $

/**
 * Language file: metadata.inc.php
 * Provides language strings.
 * Language: Dutch
 */

/*****************************************************************************
 * DOCUMENT TAB
 *****************************************************************************/

$l_metadata["filesize"] = "Bestandsgrootte";
$l_metadata["supported_types"] = "Meta gegevens formaten"; 
$l_metadata["none"] = "geen"; 
$l_metadata["filetype"] = "Bestandstype";

/*****************************************************************************
 * METADATA FIELD MAPPING
 *****************************************************************************/

$l_metadata["headline"] = "Meta gegevens velden";
$l_metadata["tagname"] = "Veld naam"; 
$l_metadata["type"] = "Type";
$l_metadata["dummy"] = "dummy"; 

$l_metadata["save"] = "Bezig met bewaren van meta gegevens velden, een ogenblik geduld ...";
$l_metadata["save_wait"] = "Instellingen bewaren";

$l_metadata["saved"] = "De meta gegevens velden zijn succesvol bewaard.";
$l_metadata["saved_successfully"] = "Meta gegevens velden bewaard";

$l_metadata["properties"] = "Eigenschappen";

$l_metadata["fields_hint"] = "Defineer extra velden voor meta gegevens. Toegevoegde gegevens(Exit, IPTC) aan het originele bestand, kunnen automatisch inbegrepen worden tijdens het importeren. Voeg ��n of meerdere velden toe die ge�mporteerd moeten worden in het invoer veld &quot;importeer vanuit&quot; in het formaat &quot;[type]/[fieldname]&quot;. Bijvoorbeeld: &quot;exif/copyright,iptc/copyright&quot;. Er kunnen meerdere ingevoerd worden, gescheiden door een komma. Tijdens het importeren worden alle gespecificeerde velden doorzocht.";
$l_metadata["import_from"] = "Importeer uit"; 
$l_metadata["fields"] = "Velden";
$l_metadata['add'] = "voeg toe"; 

/*****************************************************************************
 * UPLOAD
 *****************************************************************************/

$l_metadata["import_metadata_at_upload"] = "Importeer metagegevens uit bestand"; 

/*****************************************************************************
 * ERROR MESSAGES
 *****************************************************************************/

$l_metadata['error_meta_field_empty_msg'] = "De veldnaam op regel %s1 mag niet leeg zijn!";
$l_metadata['meta_field_wrong_chars_messsage'] = "De veldnaam '%s1' is niet geldig! Geldige karakters zijn alfa-numeriek, hoofd- en kleine letters (a-z, A-Z, 0-9) en underscore.";
$l_metadata['meta_field_wrong_name_messsage'] = "De veldnaam '%s1' is niet geldig! Deze naam wordt intern gebruikt in webEdition! De volgende namen zijn niet geldig en kunnen niet gebruikt worden: %s2";


/*****************************************************************************
 * INFO TAB
 *****************************************************************************/

$l_metadata['info_exif_data'] = "Exif gegevens";
$l_metadata['info_iptc_data'] = "IPTC gegevens";
$l_metadata['no_exif_data'] = "Geen Exif gegevens beschikbaar"; 
$l_metadata['no_iptc_data'] = "Geen IPTC gegevens available";
$l_metadata['no_exif_installed'] = "De PHP Exif extensie is niet ge�nstalleerd!";
$l_metadata['no_metadata_supported'] = "webEdition ondersteunt geen metagegevens formaten voor dit type document.";

?>