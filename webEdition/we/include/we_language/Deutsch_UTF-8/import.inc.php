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
 * Language file: import.inc.php
 * Provides language strings.
 * Language: Deutsch
 */
$l_import = array(
		'title' => 'Import-Wizard',
		'wxml_import' => 'webEdition XML Import',
		'gxml_import' => 'Generic XML Import',
		'csv_import' => 'CSV Import',
		'import' => 'Importiere',
		'none' => '-- keine --',
		'any' => '-- ohne --',
		'source_file' => 'Quelldatei',
		'import_dir' => 'Zielverzeichnis',
		'select_source_file' => 'Bitte wählen Sie eine Quelldatei.',
		'we_title' => 'Titel',
		'we_description' => 'Beschreibungstext',
		'we_keywords' => 'Schlüsselwörter',
		'uts' => 'Unix-Timestamp',
		'unix_timestamp' => 'Der Unix-Timestamp zählt die Anzahl der Sekunden seit dem Beginn der Unix-Epoche (01.01.1970).',
		'gts' => 'GMT Timestamp',
		'gmt_timestamp' => 'General Mean Time bzw. Greenwich Mean Time (kurz GMT).',
		'fts' => 'Eigenes Format',
		'format_timestamp' => 'Innerhalb der Formatieranweisung sind folgende Symbole zulässig: Y (vierstellige Ausgabe des Jahres: 2004), y (zweistellige Ausgabe des Jahres: 04), m (Monat mit führender Null: 01 bis 12), n (Monat ohne führende Null: 1 bis 12), d (Tag des Monats mit zwei Stellen und führender Null: 01 bis 31), j (Tag des Monats ohne führende Null: 1 bis 31), H (Stunde im 24-Stunden-Format: 00 bis 23), G (Stunde im 24-Stunden-Format ohne führende Null: 0 bis 23), i (Minuten: 00 bis 59), s (Sekunden mit führender Null: 00 bis 59)',
		'import_progress' => 'Importiere',
		'prepare_progress' => 'Vorbereitung',
		'finish_progress' => 'Fertig',
		'finish_import' => 'Der Import wurde erfolgreich beendet!',
		'import_file' => 'Datei-Import',
		'import_data' => 'Daten-Import',
		'import_templates' => 'Vorlagen-Import',
		'template_import' => 'First Steps Wizard',
		'txt_template_import' => 'Importieren Sie fertige Beispiel-Vorlagen und Vorlagensätze vom webEdition Server',
		'file_import' => 'Lokale Dateien importieren',
		'txt_file_import' => 'Eine oder mehrere Dateien von der lokalen Festplatte importieren.',
		'site_import' => 'Dateien vom Server importieren',
		'site_import_isp' => 'Grafiken vom Server importieren',
		'txt_site_import' => 'Dateien eines Server-Verzeichnisses importieren. Wählen Sie durch das Setzen von Filteroptionen, ob Grafiken, HTML-, Flash-, JavaScript-, CSS-, Text-Dateien oder sonstige Dateien importiert werden sollen.',
		'txt_site_import_isp' => 'Grafiken eines Server-Verzeichnisses importieren. Wählen Sie aus, welche Grafiken importiert werden sollen.',
		'txt_wxml_import' => 'webEdition XML-Dateien enthalten Informationen über webEdition-Seiten, Vorlagen oder Objekte. Legen Sie fest in welches Verzeichnis die Dokumente oder Objekte importiert werden sollen.',
		'txt_gxml_import' => 'Import von "flachen" XML-Dateien, wie sie z. B. von  phpMyAdmin erzeugt werden. Die Datensatz-Felder werden den webEdition Feldern zugeordnet. Nutzen Sie diesen Import um XML-Dateien zu importieren die ohne Exportmodul aus webEdition exportiert wurden.',
		'txt_csv_import' => 'Import von CSV-Dateien (Comma Separated Values) oder davon abgewandelter Textformate (z. B. *.txt). Die Datensatz-Felder werden den webEdition Feldern zugeordnet.',
		'add_expat_support' => 'Die XML Import-Schnittstelle erfordert die XML expat Erweiterung von James Clark. Kompilieren Sie PHP mit der expat Erweiterung neu, damit die XML Import Funktionalität unterstützt werden kann.',
		'xml_file' => 'XML-Datei',
		'templates' => 'Vorlagen',
		'classes' => 'Klassen',
		'predetermined_paths' => 'Vorgegebene Pfade',
		'maintain_paths' => 'Pfade beibehalten',
		'import_options' => 'Import Optionen',
		'file_collision' => 'Bei existierender Datei',
		'collision_txt' => 'Beim Import von Dateien in ein Verzeichnis, das eine Datei mit gleichem Namen enthält, kommt es zu Konflikten. Geben Sie an, wie der Import Wizard diese Dateien behandeln soll.',
		'replace' => 'Ersetzen',
		'replace_txt' => 'Bestehende Datei löschen und mit den neuen Einträgen Ihrer vorliegenden Datei ersetzen.',
		'rename' => 'Umbenennen',
		'rename_txt' => 'Dem Dateinamen wird eine eindeutige ID hinzugefügt. Alle Links, die auf diese Datei verweisen, werden entsprechend angepasst.',
		'skip' => 'Überspringen',
		'skip_txt' => 'Beim Überspringen der vorliegenden Datei bleibt die bestehende Datei erhalten.',
		'extra_data' => 'Extra Daten',
		'integrated_data' => 'Eingebundene Daten importieren',
		'integrated_data_txt' => 'Wählen Sie diese Option, wenn die von den Vorlagen inkludierten Daten, bzw. Dokumente importiert werden sollen.',
		'max_level' => 'bis Ebene',
		'import_doctypes' => 'Dokument-Typen importieren',
		'import_categories' => 'Kategorien importieren',
		'invalid_wxml' => 'Es können nur XML-Dateien importiert werden, die der webEdition Dokumenttyp-Definition (DTD) entsprechen.',
		'valid_wxml' => 'Die XML-Datei ist wohlgeformt und gültig, d.h. es entspricht der webEdition Dokumenttyp-Definition (DTD).',
		'specify_docs' => 'Bitte wählen Sie die Dokumente, die Sie importieren möchten.',
		'specify_objs' => 'Bitte wählen Sie die Objekte, die Sie importieren möchten.',
		'specify_docs_objs' => 'Bitte wählen Sie, ob Sie Dokumente und/oder Objekte importieren möchten.',
		'no_object_rights' => 'Sie haben jedoch keine Berichtigung Objekte zu importieren.',
		'display_validation' => 'XML-Validierung anzeigen',
		'xml_validation' => 'XML-Validierung',
		'warning' => 'Warnung',
		'attribute' => 'Attribut',
		'invalid_nodes' => 'ungültiger XML-Knoten an der Position ',
		'no_attrib_node' => 'fehlendes XML-Element "attrib" an der Position ',
		'invalid_attributes' => 'ungültige Attribute an der Position ',
		'attrs_incomplete' => 'die Liste der als #required und #fixed definierten Attribute ist unvollständig an der Position ',
		'wrong_attribute' => 'ein Attributname wurde weder als #required, noch als #implied definiert an der Position ',
		'documents' => 'Dokumente',
		'objects' => 'Objekte',
		'fileselect_server' => 'Quelldatei vom Server laden',
		'fileselect_local' => 'Quelldatei von der lokalen Festplatte hochladen',
		'filesize_local' => 'Die hochzuladende Datei darf auf Grund von PHP Einschränkungen nicht größer als %s sein!',
		'invalid_path' => 'Der Pfad der Quelldatei ist ungültig.',
		'xml_mime_type' => 'Die ausgewählte Datei kann nicht importiert werden. Mime-Typ:',
		'ext_xml' => 'Bitte wählen Sie eine Quelldatei mit der Dateierweiterung ".xml".',
		'store_docs' => 'Zielverzeichnis Dokumente',
		'store_tpls' => 'Zielverzeichnis Seitenvorlagen',
		'store_objs' => 'Zielverzeichnis Objekte',
		'doctype' => 'Dokument Typ',
		'gxml' => 'Generic XML',
		'data_import' => 'Daten importieren',
		'documents' => 'Dokumente',
		'objects' => 'Objekte',
		'type' => 'Typ',
		'template' => 'Vorlage',
		'class' => 'Klasse',
		'categories' => 'Kategorien',
		'isDynamic' => 'Seite dynamisch generieren',
		'extension' => 'Erweiterung',
		'filetype' => 'Dateityp',
		'directory' => 'Verzeichnis',
		'select_data_set' => 'Datensatz auswählen',
		'select_docType' => 'Bitte wählen Sie eine Vorlage aus.',
		'file_exists' => 'Die ausgewählte Quelldatei existiert nicht. Bitte überprüfen Sie die Pfadangabe. Pfad: ',
		'file_readable' => 'Die ausgewählte Quelldatei hat keine Leserechte und kann somit nicht importiert werden.',
		'asgn_rcd_flds' => 'Datenfelder zuordnen',
		'we_flds' => 'webEdition&nbsp;Felder',
		'rcd_flds' => 'Datensatz&nbsp;Felder',
		'name' => 'Name',
		'auto' => 'automatisch',
		'asgnd' => 'zugeordnet',
		'pfx' => 'Präfix',
		'pfx_doc' => 'Dokument',
		'pfx_obj' => 'Objekt',
		'rcd_fld' => 'Datensatz Feld',
		'import_settings' => 'Import-Einstellungen',
		'xml_valid_1' => 'Die XML-Datei ist gültig und enthält',
		'xml_valid_s2' => 'Elemente. Bitte wählen Sie die Elemente aus, die Sie importieren möchten.',
		'xml_valid_m2' => 'XML Kind-Knoten in der ersten Ebene mit unterschiedlichen Namen. Bitte wählen Sie den XML-Knoten und die Anzahl der Elemente, die Sie importieren möchten.',
		'well_formed' => 'Die XML-Datei ist fehlerfrei (wohlgeformt).',
		'not_well_formed' => 'Die XML-Datei ist nicht wohlgeformt und kann nicht importiert werden.',
		'missing_child_node' => 'Die XML-Datei ist wohlgeformt, enthält aber keine XML-Knoten und kann somit nicht importiert werden.',
		'select_elements' => 'Bitte wählen Sie die Datensätze aus, die Sie importieren möchten.',
		'num_elements' => 'Bitte wählen Sie die Anzahl der Datensätze zwischen 1 und ',
		'xml_invalid' => '',
		'option_select' => 'Auswahl..',
		'num_data_sets' => 'Datensätze:',
		'to' => 'bis',
		'assign_record_fields' => 'Datenfelder zuordnen',
		'we_fields' => 'webEdition Felder',
		'record_fields' => 'Datensatz Felder',
		'record_field' => 'Datensatz Feld ',
		'attributes' => 'Attribute',
		'settings' => 'Einstellungen',
		'field_options' => 'Feld-Optionen',
		'csv_file' => 'CSV-Datei',
		'csv_settings' => 'CSV Einstellungen',
		'xml_settings' => 'XML Einstellungen',
		'file_format' => 'Datei-Format',
		'field_delimiter' => 'Trennzeichen',
		'comma' => ', {Komma}',
		'semicolon' => '; {Semikolon}',
		'colon' => ': {Doppelpunkt}',
		'tab' => "\\t {Tab}",
		'space' => '  {Leerzeichen}',
		'text_delimiter' => 'Textbegrenzer',
		'double_quote' => '" {Anführungszeichen}',
		'single_quote' => '\' {einfaches Anführungszeichen}',
		'contains' => 'Erste Zeile enthält Feldnamen',
		'split_xml' => 'Datensätze der Reihe nach importieren',
		'wellformed_xml' => 'Überprüfung auf Wohlgeformtheit',
		'validate_xml' => 'XML-Validierung',
		'select_csv_file' => 'Bitte wählen Sie die CSV-Quelldatei.',
		'select_seperator' => 'Bitte wählen Sie ein Trennzeichen.',
		'format_date' => 'Datumsformat',
		'info_sdate' => 'Wählen Sie das Datumsformat für das webEdition Feld',
		'info_mdate' => 'Wählen Sie das Datumsformat für die webEdition Felder',
		'remark_csv' => 'Sie können CSV-Dateien (Comma Separated Values) oder davon abgewandelte Textformate (z. B. *.txt) importieren. Beim Import dieser Dateiformate kann das Spaltentrennzeichen (z. B. , ; Tab, Leerzeichen) und der Textbegrenzer (= das Zeichen, welches Texteinträge kapselt) eingestellt werden.',
		'remark_xml' => 'Wählen Sie die Option "Datensätze einzeln importieren", damit große Dateien innerhalb der als Timeout definierten Ausführungszeit eines PHP-Scriptes importiert werden können.<br>Wenn Sie nicht sicher sind, ob es sich bei der ausgewählten Datei um webEdition XML handelt, dann können Sie die Datei vor dem Import auf Wohlgeformtheit und Gültigkeit überprüfen.',
		'import_docs' => "Dokumente importieren",
		'import_templ' => "Vorlagen importieren",
		'import_objs' => "Objekte importieren",
		'import_classes' => "Klassen importieren",
		'import_doctypes' => "Dokument-Typen importieren",
		'import_cats' => "Kategorien importieren",
		'documents_desc' => "Geben Sie bitte das Verzeichnis an, in welches die Dokumente importiert werden sollen. Falls die Option \"Pfade beibehalten\" ausgewählt ist, werden die entsprechenden Pfade automatisch wiederhergestellt, anderenfalls werden die Pfade ignoriert.",
		'templates_desc' => "Geben Sie bitte das Verzeichnis an, in welches die Vorlagen importiert werden sollen. Falls die Option \"Pfade beibehalten\" ausgewählt ist, werden die entsprechenden Pfade automatisch wiederhergestellt, anderenfalls werden die Pfade ignoriert.",
		'handle_document_options' => 'Dokumente',
		'handle_template_options' => 'Vorlagen',
		'handle_object_options' => 'Objekte',
		'handle_class_options' => 'Klasse',
		'handle_doctype_options' => "Dokument-Typen",
		'handle_category_options' => "Kategorien",
		'log' => 'Details',
		'start_import' => 'Import startet',
		'prepare' => 'Vorbereitung...',
		'update_links' => 'Links-Aktualisierung...',
		'doctype' => 'Dokument-Typ',
		'category' => 'Kategorie',
		'end_import' => 'Import beendet',
		'handle_owners_option' => 'Besitzerdaten',
		'txt_owners' => 'Die verlinkten Benutzerdaten mit importieren.',
		'handle_owners' => 'Besitzerdaten wiederherstellen',
		'notexist_overwrite' => 'Sollte der Benutzer nicht existieren, dann wird die Option "Besitzerdaten überschreiben" verwendet.',
		'owner_overwrite' => 'Besitzerdaten überschreiben',
		'name_collision' => 'Bei gleichen Namen',
		'item' => 'Artikel',
		'backup_file_found' => 'Hier handelt es sich um eine Backup-Datei. Nutzen Sie bitte die Option \"Backup->Backup wiederherstellen\" aus dem Datei-Menü um die Datei zu importieren.',
		'backup_file_found_question' => 'Möchten Sie gleich das aktuelle Fenster schließen und einen Backup-Wizard für den Import starten?',
		'close' => 'Schließen',
		'handle_file_options' => 'Dateien',
		'import_files' => 'Dateien importieren',
		'weBinary' => 'Datei',
		'format_unknown' => 'Das Format der Datei ist unbekannt!',
		'customer_import_file_found' => 'Hier handelt es sich um eine Import-Datei aus der Kundenverwaltung. Nutzen Sie bitte die Option \"Import/Export\" aus der Kundenverwaltung (PRO) um die Datei zu importieren.',
		'upload_failed' => 'Die Datei kann nicht hochgeladen werden! Prüfen Sie bitte ob die Größe der Datei %s überschreitet',
		'import_navigation' => 'Navigation importieren',
		'weNavigation' => 'Navigation',
		'navigation_desc' => 'Geben Sie bitte das Verzeichnis an, in welches die Navigation importiert werden sollen.',
		'weNavigationRule' => 'Navigation-Regel',
		'weThumbnail' => 'Miniaturansichten',
		'import_thumbnails' => 'Miniaturansichten importieren',
		'rebuild' => 'Rebuild',
		'rebuild_txt' => 'Automatischer Rebuild',
		'finished_success' => 'Der Import der Daten wurde erfolgreich beendet.',
		'encoding_headline' => 'Zeichensatz',
		'encoding_noway' => 'Konvertierung nur möglich zwischen ISO-8859-1 und UTF-8 <br/>und bei gesetztem Standardzeichensatz (Einstellungsdialog)',
		'encoding_change' => "Ändern, von '",
		'encoding_XML' => '',
		'encoding_to' => "' (XML-Datei) zu '",
		'encoding_default' => "' (Standard)",
);