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
 * Language file: backup.inc.php
 * Provides language strings.
 * Language: Deutsch
 */
$l_backup = array(
		'save_not_checked' => "Sie haben noch nicht ausgew�hlt, wohin die Backup-Datei gespeichert werden soll!",
		'wizard_title' => "Backup wiederherstellen Wizard",
		'wizard_title_export' => "Backup Wizard",
		'save_before' => "W�hrend des Wiederherstellens der Backup-Datei werden die vorhandenen Daten gel�scht! Es wird daher empfohlen, die vorhandenen Daten vorher zu speichern.",
		'save_question' => "M�chten Sie dies jetzt tun?",
		'step1' => "Schritt 1/4 - Vorhandene Daten speichern",
		'step2' => "Schritt 2/4 - Datenquelle ausw�hlen",
		'step3' => "Schritt 3/4 - Gesicherte Daten wiederherstellen",
		'step4' => "Schritt 4/4 - Wiederherstellung beendet",
		'extern' => "webEdition-externe Dateien/Verzeichnisse wiederherstellen",
		'settings' => "Einstellungen wiederherstellen",
		'rebuild' => "Automatischer Rebuild",
		'select_upload_file' => "Wiederherstellung aus lokaler Datei hochladen",
		'select_server_file' => "W�hlen Sie die gew�nschte Backup-Datei aus.",
		'charset_warning' => "Sollte es Probleme beim Wiederherstellen eines Backups geben, achten Sie bitte darauf, dass <strong>im Zielsystem derselbe Zeichensatz (Charset) wie im Quellsystem verwendet</strong> wird. Dies gilt sowohl f�r den Zeichensatz der Datenbank (collation) als auch f�r den Zeichensatz der verwendeten Oberfl�chensprache!",
		'defaultcharset_warning' => '<span style="color:ff0000">Achtung! Es ist keinen Standard-Zeichensatz definiert.</span> Dies kann bei bestimmten Server-Konfigurationen zu Problemen beim Wiederherstellen des Backups f�hren!',
		'finished_success' => "Der Import der Backup-Daten wurde erfolgreich beendet.",
		'finished_fail' => "Der Import der Backup-Daten wurde nicht erfolgreich beendet.",
		'question_taketime' => "Der Export dauert einige Zeit.",
		'question_wait' => "Bitte haben Sie etwas Geduld!",
		'export_title' => "Backup erstellen",
		'finished' => "Beendet",
		'extern_files_size' => "Dieser Vorgang kann einige Minuten dauern. Es werden unter Umst�nden mehrere Dateien angelegt, da die Datenbankeinstellung auf eine maximale Dateigr��e von %.1f MB (%s Byte) beschr�nkt ist.",
		'extern_files_question' => "webEdition-externe Dateien/Verzeichnisse sichern",
		'export_location' => "Bitte w�hlen Sie aus, wo die Backup-Datei gespeichert werden soll. Wird die Datei auf dem Server gespeichert, finden Sie diese unter '/webEdition/we_backup/data/'.",
		'export_location_server' => "Auf dem Server",
		'export_location_send' => "Auf Ihrer lokalen Festplatte",
		'can_not_open_file' => "Die Datei '%s' kann nicht ge�ffnet werden.",
		'too_big_file' => "Die Datei '%s' kann nicht gespeichert werden, da die maximale Dateigr��e �berschritten wurde.",
		'cannot_save_tmpfile' => " Tempor�re Datei kann nicht angelegt werden! Pr�fen Sie bitte, ob Sie die Rechte haben in %s zu schreiben.",
		'cannot_save_backup' => "Die Backup-Datei kann nicht gespeichert werden.",
		'cannot_send_backup' => " Backup kann nicht ausgef�hrt werden ",
		'finish' => "Die Backup-Datei wurde erstellt.",
		'finish_error' => " Fehler: Backup konnte nicht erfolgreich ausgef�hrt werden",
		'finish_warning' => "Warnung: Backup wurde ausgef�hrt, m�glicherweise wurden nicht alle Dateien vollst�ndig angelegt",
		'export_step1' => "Schritt 1/2 - Backup Parameter",
		'export_step2' => "Schritt 2/2 - Backup beendet",
		'unspecified_error' => "Ein unbekannter Fehler ist aufgetreten",
		'export_users_data' => "Benutzerdaten sichern",
		'import_users_data' => "Benutzerdaten wiederherstellen",
		'import_from_server' => "Daten vom Server laden",
		'import_from_local' => "Daten aus lokal gesicherter Datei laden",
		'backup_form' => "Backup vom ",
		'nothing_selected' => "Es wurde nichts ausgew�hlt!",
		'query_is_too_big' => "Die Backup-Datei enth�lt eine Datei, welche nicht wiederhergestellt werden konnte, da sie gr��er als %s bytes ist!",
		'show_all' => "Zeige alle Dateien",
		'import_customer_data' => "Kundendaten wiederherstellen",
		'import_shop_data' => "Shopdaten wiederherstellen",
		'export_customer_data' => "Kundendaten sichern",
		'export_shop_data' => "Shopdaten sichern",
		'working' => "In Arbeit...",
		'preparing_file' => "Daten f�rs Wiederherstellen vorbereiten...",
		'external_backup' => "Externe Daten sichern...",
		'import_content' => "Inhalt wiederherstellen",
		'import_files' => "Dateien wiederherstellen",
		'import_doctypes' => "Dateien wiederherstellen",
		'import_user_data' => "Benutzerdaten wiederherstellen",
		'import_templates' => "Vorlagen wiederherstellen",
		'export_content' => "Inhalt sichern",
		'export_files' => "Dateien sichern",
		'export_doctypes' => "Dateien sichern",
		'export_user_data' => "Benutzerdaten sichern",
		'export_templates' => "Vorlagen sichern",
		'download_starting' => "Der Download der Backup-Datei wurde gestartet.<br><br>Sollte der Download nach 10 Sekunden nicht starten,<br>",
		'download' => "klicken Sie bitte hier.",
		'download_failed' => "Die angeforderte Datei existiert entweder nicht oder Sie haben keine Berechtigung, sie herunterzuladen.",
		'extern_backup_question_exp' => "Sie haben 'webEdition-externe Dateien/Verzeichnisse sichern' ausgew�hlt. Diese Auswahl kann sehr zeitintensiv sein und zu Systemfehlern f�hren. Trotzdem fortfahren?",
		'extern_backup_question_exp_all' => "Sie haben 'Alle ausw�hlen' ausgew�hlt. Damit wird automatisch 'webEdition-externe Dateien/Verzeichnisse sichern' mit ausgew�hlt. Dieser Vorgang kann sehr zeitintensiv sein und zu Systemfehlern f�hren.\\n'webEdition-externe Dateien/Verzeichnisse sichern' ausgew�hlt lassen?",
		'extern_backup_question_imp' => "Sie haben 'webEdition-externe Dateien/Verzeichnisse wiederherstellen' ausgew�hlt. Diese Auswahl kann sehr zeitintensiv sein und zu Systemfehlern f�hren. Trotzdem fortfahren?",
		'extern_backup_question_imp_all' => "Sie haben 'Alle ausw�hlen' ausgew�hlt. Damit wird automatisch 'webEdition-externe Dateien/Verzeichnisse wiederherstellen' mit ausgew�hlt. Dieser Vorgang kann sehr zeitintensiv sein und zu Systemfehlern f�hren.\\n'webEdition-externe Dateien/Verzeichnisse wiederherstellen' ausgew�hlt lassen?",
		'nothing_selected_fromlist' => "Bitte w�hlen Sie eine Backup-Datei aus der Liste!",
		'export_workflow_data' => "Workflowdaten sichern",
		'export_todo_data' => "Todo/Messaging Daten sichern",
		'import_workflow_data' => "Workflowdaten wiederherstellen",
		'import_todo_data' => "Todo/Messaging Daten wiederherstellen",
		'import_check_all' => "Alle ausw�hlen",
		'export_check_all' => "Alle ausw�hlen",
		'import_shop_dep' => "Sie haben 'Shopdaten wiederherstellen' ausgew�hlt. Der Shop braucht die Kundendaten um richtig zu funktionieren und deswegen wird 'Kundendaten sichern' automatisch markiert.",
		'export_shop_dep' => "Sie haben 'Shopdaten sichern' ausgew�hlt. Das Shop Modul braucht die Kundendaten um richtig zu funktionieren und deswegen wird 'Kundendaten sichern' automatisch markiert.",
		'import_workflow_dep' => "Sie haben 'Workflow wiederherstellen' ausgew�hlt. Das Workflow braucht die Dokumente und Benutzerdaten um richtig zu funktionieren und deswegen wird 'Dokumente und Vorlage wiederherstellen' und 'Benutzerdaten wiederherstellen' automatisch markiert.",
		'export_workflow_dep' => "Sie haben 'Workflow sichern' ausgew�hlt. Das Workflow braucht die Dokumente und Benutzerdaten um richtig zu funktionieren und deswegen wird 'Dokumente und Vorlage sichern' und 'Benutzerdaten sichern' automatisch markiert.",
		'import_todo_dep' => "Sie haben 'Todo/Messaging wiederherstellen' ausgew�hlt. Das Todo/Mess. braucht die Benutzerdaten um richtig zu funktionieren und deswegen wird 'Benutzerdaten wiederherstellen' automatisch markiert.",
		'export_todo_dep' => "Sie haben 'Todo/Messaging sichern' ausgew�hlt. Das Todo/Messaging braucht die Benutzerdaten um richtig zu funktionieren und deswegen wird 'Benutzerdaten sichern' automatisch markiert.",
		'export_newsletter_data' => "Newsletterdaten sichern",
		'import_newsletter_data' => "Newsletterdaten wiederherstellen",
		'export_newsletter_dep' => "Sie haben 'Newsletterdaten sichern' ausgew�hlt. Der Newsletter braucht die Dokumente, Objekte und die Kundendaten um richtig zu funktionieren und deswegen wird 'Dokumente und Vorlage sichern', 'Objekte und Klasse sichern' und 'Kundendaten sichern' automatisch markiert.",
		'import_newsletter_dep' => "Sie haben 'Newsletterdaten wiederherstellen' ausgew�hlt. Der Newsletter braucht die Dokumente, Objekte und die Kundendaten um richtig zu funktionieren und deswegen wird 'Dokumente und Vorlage wiederherstellen', 'Objekte und Klasse wiederherstellen' und 'Kundendaten wiederherstellen' automatisch markiert.",
		'warning' => "Warnung",
		'error' => "Fehler",
		'export_temporary_data' => "Tempor�re Dateien sichern",
		'import_temporary_data' => "Tempor�re Dateien wiederherstellen",
		'export_banner_data' => "Bannerdaten sichern",
		'import_banner_data' => "Bannerdaten wiederherstellen",
		'export_prefs' => "Einstellungen sichern",
		'import_prefs' => "Einstellungen wiederherstellen",
		'export_links' => "Links sichern",
		'import_links' => "Links wiederherstellen",
		'export_indexes' => "Indizes sichern",
		'import_indexes' => "Indizes wiederherstellen",
		'filename' => "Dateiname",
		'compress' => "Komprimieren",
		'decompress' => "Dekomprimieren",
		'option' => "Backup-Optionen",
		'filename_compression' => "Geben Sie hier der Ziel-Backup-Datei einen Namen. Sie k�nnen auch die Dateikompression aktivieren. Die Backup-Datei wird dann mit gzip komprimiert und wird die Dateiendung .gz erhalten. Dieser Vorgang kann einige Minuten dauern!<br>Wenn das Backup nicht erfolgreich ist, �ndern Sie bitte die Einstellungen.",
		'export_core_data' => "Dokumente und Vorlagen sichern",
		'import_core_data' => "Dokumente und Vorlagen wiederherstellen",
		'export_object_data' => "Objekte und Klassen sichern",
		'import_object_data' => "Objekte und Klassen wiederherstellen",
		'export_binary_data' => "Binarydaten (Bilder, PDFs, ...) sichern",
		'import_binary_data' => "Binarydaten (Bilder, PDFs, ...) wiederherstellen",
		'export_schedule_data' => "Scheduledaten sichern",
		'import_schedule_data' => "Scheduledaten wiederherstellen",
		'export_settings_data' => "Einstellungen sichern",
		'import_settings_data' => "Einstellungen wiederherstellen",
		'export_extern_data' => "webEdition-externe Dateien/Verzeichnisse sichern",
		'import_extern_data' => "webEdition-externe Dateien/Verzeichnisse wiederherstellen",
		'export_binary_dep' => "Sie haben 'Binarydaten sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Binarydaten auch die Dokumente. Deswegen wird 'Dokumente und Vorlage sichern' automatisch markiert.",
		'import_binary_dep' => "Sie haben 'Binarydaten wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Binarydaten auch die Dokumente. Deswegen wird 'Dokumente und Vorlage wiederherstellen' automatisch markiert.",
		'export_schedule_dep' => "Sie haben 'Scheduledaten sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Scheduledaten auch die Dokumente und die Objekte. Deswegen wird 'Dokumente und Vorlage sichern' und 'Objekte und Klassen sichern' automatisch markiert.",
		'import_schedule_dep' => "Sie haben 'Scheduledaten wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Scheduledaten auch die Dokumente und die Objekte. Deswegen wird 'Dokumente und Vorlage wiederherstellen' und 'Objekte und Klassen wiederherstellen' automatisch markiert.",
		'export_temporary_dep' => "Sie haben 'Tempor�re Dateien sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Tempor�re Dateien auch die Dokumente. Deswegen wird 'Dokumente und Vorlage sichern' automatisch markiert.",
		'import_temporary_dep' => "Sie haben 'Tempor�re Dateien wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die 'Tempor�re Dateien auch die Dokumente. Deswegen wird 'Dokumente und Vorlage wiederherstellen' automatisch markiert.",
		'compress_file' => "Datei komprimieren",
		'export_options' => "W�hlen Sie die zu sichernden Daten aus.",
		'import_options' => "W�hlen Sie die wiederherzustellenden Daten aus.",
		'extern_exp' => "Achtung! Diese Option ist sehr zeitintensiv und kann zu Systemfehlern f�hren",
		'unselect_dep2' => "Sie haben '%s' abgew�hlt. Folgende Optionen werden automatisch abgew�hlt:",
		'unselect_dep3' => "Sie k�nnen trotzdem die nicht selektierten Optionen ausw�hlen.",
		'gzip' => "gzip",
		'zip' => "zip",
		'bzip' => "bzip",
		'none' => "kein",
		'cannot_split_file' => "Kann die Datei '%s' nicht zur Wiederherstellung vorbereiten!",
		'cannot_split_file_ziped' => "Die Datei wurde mit einer nicht unterst�tzen Komprimierungsmethode komprimiert.",
		'export_banner_dep' => "Sie haben 'Bannerdaten sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Bannerdaten auch die Dokumente. Deswegen wird 'Dokumente und Vorlage sichern' automatisch markiert.",
		'import_banner_dep' => "Sie haben 'Bannerdaten wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Bannerdaten auch die Dokumente. Deswegen wird 'Dokumente und Vorlage wiederherstellen' automatisch markiert.",
		'delold_notice' => "Es wird empfohlen, die vorhandenen Dateien vorher zu l�schen.<br>M�chten Sie dies jetzt tun?",
		'delold_confirm' => "Sind Sie sicher, dass Sie alle Dateien vom Server l�schen m�chten?",
		'delete_entry' => "L�sche %s",
		'delete_nok' => "Die Dateien kann nicht gel�scht werden!",
		'nothing_to_delete' => "Es gibt nichts zu l�schen!",
		'files_not_deleted' => "Eine oder mehrere der zu l�schende Dateien konnten nicht vollst�ndig vom Server gel�scht werden! M�glicherweise sind sie schreibgesch�tzt. L�schen Sie die Dateien manuell. Folgende Dateien sind davon betroffen:",
		'delete_old_files' => "L�sche alte Dateien...",
		'export_configuration_data' => "Konfiguration sichern",
		'import_configuration_data' => "Konfiguration wiederherstellen",
		'export_export_data' => "Exportdaten sichern",
		'import_export_data' => "Exportdaten wiederherstellen",
		'export_versions_data' => "Versionierungsdaten sichern",
		'export_versions_binarys_data' => "Versions-Binary-Dateien sichern",
		'import_versions_data' => "Versionierungsdaten wiederherstellen",
		'import_versions_binarys_data' => "Vorhandene Versions-Binary-Dateien wiederherstellen",
		'export_versions_dep' => "Sie haben 'Versionierungsdaten sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Versionen auch die zugeh�rigen Dokumente, Objekte und Bin�rdateien. Deswegen wird 'Dokumente und Vorlage sichern', 'Objekte und Klassen sichern' und 'Bin�rdateien sichern' automatisch markiert.",
		'import_versions_dep' => "Sie haben 'Versionierungsdaten wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Versionen auch zugeh�rigen Dokumente, Objekte und Bin�rdateien. Deswegen wird 'Dokumente und Vorlage wiederherstellen', 'Objekte und Klassen wiederherstellen' und 'Bin�rdateien wiederherstellen' automatisch markiert.",
		'export_versions_binarys_dep' => "Sie haben 'Versions-Binary-Dateien sichern' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Versionen auch die zugeh�rigen Dokumente, Objekte und Versionierungsdaten. Deswegen wird 'Dokumente und Vorlage sichern', 'Objekte und Klassen sichern' und 'Versionierungsdaten sichern' automatisch markiert.",
		'import_versions_binarys_dep' => "Sie haben 'Versions-Binary-Dateien wiederherstellen' ausgew�hlt. Um richtig zu funktionieren, ben�tigen die Versionen auch zugeh�rigen Dokumente, Objekte und Versionierungsdaten. Deswegen wird 'Dokumente und Vorlage wiederherstellen', 'Objekte und Klassen wiederherstellen' und 'Versionierungsdaten wiederherstellen' automatisch markiert.",
		'del_backup_confirm' => "M�chten Sie ausgew�hlte Backup-Datei l�schen?",
		'name_notok' => "Der Dateiname ist nicht korrekt!",
		'backup_deleted' => "Die Backup-Datei %s wurde gel�scht",
		'error_delete' => "Backup-Datei konnte nicht gel�scht werden. Bitte l�schen Sie die Datei �ber Ihr FTP-Programm aus dem Ordner /webEdition/we_backup",
		'core_info' => 'Alle Vorlagen und Dokumente.',
		'object_info' => 'Objekte und Klassen des DB/Objekt Moduls.',
		'binary_info' => 'Bin�rdateien von Bildern, PDFs und anderen Dokumenten.',
		'user_info' => 'Benutzer und Zugangsdaten der Benutzerverwaltung.',
		'customer_info' => 'Kunden und Zugangsdaten der Kundenverwaltung.',
		'shop_info' => 'Bestellungen des Shop Moduls.',
		'workflow_info' => 'Daten des Workflow Moduls.',
		'todo_info' => 'Mitteilungen und Aufgaben des ToDo/Messaging Moduls.',
		'newsletter_info' => 'Daten des Newsletter Moduls.',
		'banner_info' => 'Banner und Statistiken des Banner Moduls.',
		'schedule_info' => 'Zeitgesteuerte Aktionen des Scheduler Moduls.',
		'settings_info' => 'webEdition Programmeinstellungen.',
		'temporary_info' => 'Noch nicht ver�ffentlichte Dokumente und Objekte bzw. noch nicht ver�ffentlichte �nderungen.',
		'export_info' => 'Daten des Export Moduls.',
		'glossary_info' => 'Daten des Glossars.',
		'versions_info' => 'Daten der Versionierung.',
		'versions_binarys_info' => 'Achtung! Diese Option kann sehr zeit- und speicherintensiv sein da der Ordner /webEdition/we/versions/ unter Umst�nden sehr gro� sein kann. Daher wird empfohlen diesen Ordner manuell zu sichern.',
		'export_voting_data' => "Votingdaten sichern",
		'import_voting_data' => "Votingdaten wiederherstellen",
		'voting_info' => 'Daten aus dem Voting Modul.',
		'we_backups' => 'webEdition Backups',
		'other_files' => 'Sonstige Datei',
		'filename_info' => 'Geben Sie hier der Ziel-Backup-Datei einen Namen.',
		'backup_log_exp' => 'Das Logbuch wird in /webEdition/we_backup/data/lastlog.php erstellt',
		'export_backup_log' => 'Logbuch erstellen',
		'download_file' => 'Datei herunterladen',
		'import_file_found' => 'Hier handelt es sich um eine Import-Datei. Nutzen Sie bitte die Option \"Import/Export\" aus dem Datei-Men� um die Datei zu importieren.',
		'customer_import_file_found' => 'Hier handelt es sich um eine Import-Datei aus der Kundenverwaltung. Nutzen Sie bitte die Option \"Import/Export\" aus der Kundenverwaltung (PRO) um die Datei zu importieren.',
		'import_file_found_question' => 'M�chten Sie gleich das aktuelle Fenster schlie�en und einen Import-Wizard f�r den webEditon XML-Import starten?',
		'format_unknown' => 'Das Format der Datei ist unbekannt!',
		'upload_failed' => 'Die Datei kann nicht hochgeladen werden! Pr�fen Sie bitte ob die Gr��e der Datei %s �berschreitet',
		'file_missing' => 'Die Backup-Datei fehlt!',
		'recover_option' => 'Wiederherstellen-Optionen',
		'no_resource' => 'Kritischer Fehler: Nicht gen�gend freie Ressourcen, um das Backup abzuschlie�en!',
		'error_compressing_backup' => 'Bei der Komprimierung ist ein Fehler aufgetreten, das Backup konnte nicht abgeschlossen werden!',
		'error_timeout' => 'Bei der Erstellung des Backup ist ein timeout aufgetreten, das Backup konnte nicht abgeschlossen werden!',
		'export_spellchecker_data' => "Daten der Rechtschreibpr�fung sichern",
		'import_spellchecker_data' => "Daten der Rechtschreibpr�fung wiederherstellen",
		'spellchecker_info' => 'Daten der Rechtschreibpr�fung: Einstellungen, allgemeine und pers�nliche W�rterb�cher.',
		'export_banner_data' => "Bannerdaten sichern",
		'import_banner_data' => "Bannerdaten wiederherstellen",
		'export_glossary_data' => "Glossardaten sichern",
		'import_glossary_data' => "Glossardaten wiederherstellen",
		'protect' => "Die Backup-Datei sch�tzen",
		'protect_txt' => "Um die Backup-Datei von unrechtm��igem Herunterladen zu sch�tzen, wird zus�tzlicher PHP-Code in die Backup-Datei eingef�gt und die PHP-Datei-Erweiterung verwendet. Diese Schutz ben�tigt beim Import zus�tzlichen Speicherplatz!",
		'recover_backup_unsaved_changes' => "Einige ge�ffnete Dateien haben noch ungespeicherte �nderungen. Bitte �berpr�fen Sie diese, bevor Sie fortfahren.",
		'file_not_readable' => "Die Backup-Datei ist nicht lesbar. Bitte �berpr�fen Sie die Berechtigungen.",
		'tools_import_desc' => "Hier k�nnen Sie die Daten der webEdition-Tools wiederhestellen. W�hlen Sie bitte die gew�nschte Tools aus.",
		'tools_export_desc' => "Hier k�nnen Sie die Daten der webEdition-Tools sichern. W�hlen Sie bitte die gew�nschte Tools aus.",
		'ftp_hint' => "Achtung! Benutzen Sie den Binary-Modus beim Download per FTP, wenn die Backup-Datei mit zip komprimiert ist! Ein Download im ASCII-Modus zerst�rt die Datei, so dass sie nicht wieder hergestellt werden kann!",
		'convert_charset' => "Achtung! Beim Nutzung dieser Option in einer bestehenden Site besteht die Gefahr des totalen Datenverlustes. Bitte beachten Sie die Hinweise unter http://documentation.webedition.org/de/webedition/administration/charset-conversion-of-legacy-sites",
		'convert_charset_data' => "Beim Einspielen des Backups Umstellung der Installation von ISO auf UTF-8",
		'view_log' => "Backup-Log",
		'view_log_not_found' => "Keine Backup-Log-Datei gefunden! ",
		'view_log_no_perm' => "Sie haben nicht die notwendigen Rechte, die Backup-Log-Datei einzusehen! ",
);