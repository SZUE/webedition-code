<?php
/** Generated language file of webEdition CMS */
$l_prefs=array(
	'1_day'=>'1 day',
	'1_hour'=>'1 hour',
	'1_minute'=>'1 minute',
	'1_week'=>'1 week',
	'1_year'=>'1 year',
	'add_dictionary_question'=>'Would you like to upload the dictionary for this language?',
	'all'=>'Wszystkie',
	'ask_at_start'=>'Zapytaj przy starcie, który edytor<br/> ma być używany<br/>',
	'authpass'=>'Hasło',
	'authuser'=>'Nazwa użytkownika',
	'auth'=>'Autentyfikacja HTTP',
	'backup_auto'=>'Auto',
	'backup_fast'=>'Fast',
	'backup_slow'=>'Slow',
	'backwardcompatibility'=>'Backward compatibility',
	'backwardcompatibility_tagloading'=>'Load all `old` we_tag functions',
	
	'base'=>array(
		'css'=>'Domain for CSS files',
		'img'=>'Domain for images',
		'js'=>'Domain for JS files',
	),
	'blocked_until'=>'Blocked until',
	'blockFormmail'=>'Limit formmail requests',
	'blockFor'=>'Block for',
	'cache_information'=>'Set the preset values of the fields "Caching Type" and "Cache lifetime in seconds" for new templates here.<br/><br/>Please note that these setting are only the presets of the fields.',
	
	'cache_lifetimes'=>array(
		'0'=>'deactivated',
		'1800'=>'30 minutes',
		'21600'=>'6 hours',
		'300'=>'5 minutes',
		'3600'=>'1 hour',
		'43200'=>'12 hours',
		'600'=>'10 minutes',
		'60'=>'1 minute',
		'86400'=>'1 day',
	),
	'cache_lifetime'=>'Cache lifetime in seconds',
	'cache_navigation'=>'Default setting',
	'cache_navigation_information'=>'Enter the defaults for the &lt;we:navigation&gt; tag here. This value can be overwritten by the attribute "cachelifetime" of the &lt;we:navigation&gt; tag.',
	'cache_presettings'=>'Presetting',
	'cache_type'=>'Caching Type',
	'cache_type_document'=>'Document cache',
	'cache_type_full'=>'Full cache',
	'cache_type_none'=>'Caching deactivated',
	'cache_type_wetag'=>'we:Tag cache',
	'cannot_delete_default_language'=>'The default language cannot be deleted.',
	'change_only_in_ie'=>'Ponieważ rozszerzenie edytora działa tylko w systemie Windows w przeglądarkach Internet Explorer, Mozilla Firebird oraz Firefox nie można zmienić tych ustawień.',
	'choose_backendcharset'=>'Backend charset',
	'choose_language'=>'Backend language',
	'clear_block_entry_question'=>'Do you really want to unblock the IP %s ?',
	'clear_log_question'=>'Do you really want to clear the log?',
	'cockpit_amount_columns'=>'Columns in the cockpit',
	'confirm_install_plugin'=>'Mozilla ActiveX PlugIn umożliwia zintegrowanie kontrolek ActiveX w przeglądarce Mozilla. Po instalacji należy na nowo uruchomić przeglądarkę.\n\nPamiętaj: ActiveX może stanowić ryzyko dla bezpieczeństwa!\n\nKontynuować instalację?',
	'ContentType'=>'Content Type',
	'countries_country'=>'Country',
	'countries_default'=>'Default value',
	'countries_headline'=>'Country selection',
	'countries_information'=>'Select the countries, which are available in the customer module, shop-module and so on.  The default value (Code `--`) - if filled - will be shown on top of the list, possible values are i.e. `please choose` or `--`.',
	'countries_noshow'=>'no display',
	'countries_show'=>'display',
	'countries_top'=>'top list',
	'db_connect'=>'Rodzaj połączeń <br/>bazą danych',
	'db_set_charset'=>'Connection charset',
	'db_set_charset_information'=>'The connection charset is used for the communication between webEdition and datase server.<br/>If no value is specified, the standard connection charset set in PHP is used.<br/>In the ideal case, the webEdition language (i.e. English_UTF-8), the database collation (i.e. utf8_general_ci), the connection charset (i.e. utf8) and the settings of external tools such as phpMyAdmin (i.e. utf-8) are identical. In this case, one can edit database entries with these external tools without problems.',
	'db_set_charset_warning'=>'The connection charset should be changed only in a fresh installation of webEdition (without data in the database). Otherwise, all non ASCII characters will be interpreted wrong and may be destroyed.',
	'debug_normal'=>'W trybie normalnym',
	'debug_restart'=>'Zmiany wymagają ponownego uruchomienia',
	'debug_seem'=>'W trybie SeeModus',
	'default_cache_lifetime'=>'Default cache lifetime',
	'default_charset'=>'Standard frontend charset',
	'default_php_setting'=>'Standardowe ustawienie dla<br/>atrybutu <em>php</em> w we:tags',
	'deleteEntriesOlder'=>'Delete entries older than',
	'delete_cache_add'=>'after adding a new entry',
	'delete_cache_after'=>'Clear cache after',
	'delete_cache_delete'=>'after deleting an entry',
	'delete_cache_edit'=>'after changing an entry',
	'dimension'=>'Wielkość okna',
	'disable_template_code_check'=>'Deactivate check for invalid<br/>code (php)',
	'disable_template_tag_check'=>'Deactivate check for missing,<br/>closing we:tags',
	'dynamic'=>'Strony dynamiczne',
	'editor_comment_font_color'=>'Comments',
	'editor_completion'=>'Code Completion',
	'editor_docuclick'=>'Docu integration',
	'editor_enable'=>'Enable',
	'editor_fontname'=>'Krój pisma',
	'editor_fontsize'=>'Wielkość',
	'editor_font'=>'Czcionka w edytorze',
	'editor_font_colors'=>'Specify font colors',
	'editor_highlight_colors'=>'Highlighting colors',
	'editor_html_attribute_font_color'=>'HTML attributes',
	'editor_html_tag_font_color'=>'HTML tags',
	'editor_information'=>'Specify font and size which should be used for the editing of templates, CSS- and JavaScript files within webEdition.<br/><br/>These settings are used for the text editor of the abovementioned file types.',
	'editor_javascript2'=>'CodeMirror2',
	'editor_javascript'=>'JavaScript editor (beta)',
	'editor_javascript_information'=>'The JavaScript editor is still in beta stadium. Depending on which of the following options you`ll activate, there might occur errors. Code completion is currently not working in Internet Explorer. For a complete list of known issues please have a look at the <a href="http://qa.webedition.org/tracker/search.php?project_id=107&sticky_issues=on&sortby=last_updated&dir=DESC&hide_status_id=90" target="_blank">webEdition bugtracker</a>.',
	'editor_java'=>'Java editor',
	'editor_linenumbers'=>'Line numbers',
	'editor_mode'=>'Editor',
	'editor_normal_font_color'=>'Default',
	'editor_pi_tag_font_color'=>'PHP code',
	'editor_plaintext'=>'Plain textarea',
	'editor_plugin'=>'Rozszerzenie edytora',
	'editor_tooltips'=>'Tooltips on we:tags',
	'editor_we_attribute_font_color'=>'webEdition attributes',
	'editor_we_tag_font_color'=>'webEdition tags',
	'email'=>'E-Mail',
	'error_deprecated'=>'deprecated Notices',
	'error_displaying'=>'Wyświetlanie błędów',
	'error_display'=>'Wyświetl błąd',
	'error_errors'=>'Błędy',
	'error_log'=>'Rejestruj błędy',
	'error_mail'=>'Wyślij e-mail z informacją o błędzie',
	'error_mail_address'=>'Adresy',
	'error_mail_not_saved'=>'Błedy nie zostaną wysłane na podany przez Ciebie adres, ponieważ adres ten podano błędnie!\n\nZapisano pozostałe ustawienia.',
	'error_notices'=>'Wskazówki',
	'error_notices_warning'=>'We recommend to aktivate the option -Log errors- on all systems; the option -Show errors- should be activated only during development.',
	'error_no_object_found'=>'Errorpage for not existing objects',
	'error_types'=>'Do obsługiwanego błędu',
	'error_use_handler'=>'Włącz obsługe błędów webEdition <br/>',
	'error_warnings'=>'Ostrzeżenia',
	'ever'=>'always',
	'extensions_information'=>'Set the default file extensions for static and dynamic pages here.',
	'force_glossary_action'=>'Force action',
	'force_glossary_check'=>'Force glossary check',
	'forever'=>'Always',
	'formmailConfirm'=>'Formmail confirmation function',
	'formmailSpan'=>'Within the span of time',
	'formmailTrials'=>'Requests allowed',
	'formmailViaWeDoc'=>'Call formmail via webEdition-Dokument.',
	'formmail_information'=>'Wpisz tutaj wszystkie adresy e-mail, do których mogą być wysyłane formularze za pomocą funkcji Formmail (&lt;we:form type="formmail" ..&gt;) .<br/><br/>Jeżeli nie wpisano tu żadnych adresów e-mail, to nie można wysyłać formularzy za pomocą funkcji Formmail!',
	'formmail_log'=>'Formmail log',
	'formmail_recipients'=>'Odbiorca formularza poczty',
	'general_directoryindex_hide'=>'Hide DirectoryIndex- file names',
	'general_directoryindex_hide_description'=>'For the tags <we:a>, <we:href>, <we:link>, <we:linklist>, <we:listview>, <we:url> you can use the attribute `hidedirindex`.',
	'general_objectseourls'=>'Generate object SEO urls',
	'general_objectseourls_description'=>'For the tags <we:link>, <we:linklist>, <we:listview>, <we:object> you can use the attribute `objectseourls`.',
	'general_seoinside'=>'Usage within webEdition',
	'general_seoinside_description'=>'If DirectoryIndex- file names and object SEO urls are used within webEdition, webEdition can not identify internal links and clicks on these links do not open the editor. With the following options, you can decide if they are are used in editmode and in the preview.',
	'glossary_publishing'=>'Check before publishing',
	'height'=>'Wysokość',
	'hidenameattribinweform_default'=>'No output of name=xyz in we:form (XHTML strict)',
	'hidenameattribinweimg_default'=>'No output of name=xyz in we:img,we:link (HTML 5)',
	'hide_expert'=>'Ukryj ustawienia eksperta',
	'hide_predefined'=>'Wyłacz wymiary domyślne',
	'hooks'=>'Hooks',
	'hooks_information'=>'The use of hooks allows for the execution of arbitrary any PHP code during storing, publishing, unpublishing and deleting of any content type in webEdition.<br/>
	Further information can be found in the online documentation.<br/><br/>Allow execution of hooks?',
	'html'=>'Strony HTML',
	'html_extensions'=>'Rozszerzenia HTML',
	'inlineedit_default'=>'Standardowe ustawienie dla<br/>atrybutu <em>inlineedit</em> w<br/>&lt;we:textarea&gt;',
	'install_editor_plugin'=>'Żeby używać rozszerzenia edytora w Twojej przeglądarce, musisz go zainstalować.',
	'install_editor_plugin_text'=>'Rozszerzenie edytora dla webEdition zostanie zainstalowane...',
	'install_plugin'=>'Żeby można było wykorzystać rozszerzenie edytora w twojej przeglądarce, powinienieś zainstalować Mozilla ActiveX PlugIn.',
	'ip_address'=>'IP address',
	'juplod_not_installed'=>'JUpload is not installed!',
	'langlink_abandoned_options'=>'<b>Notice:</b><br>From version 6.27 onwards the following two options are set "true", and can not be changed anymore. Thus setting of language links will allways be done recursively.',
	'langlink_headline'=>'Support for setting links between different languages',
	'langlink_information'=>'With this option, you can set the links to corresponding language versions of documents/objects in the backend and open/create etc. these documents/oobjects.<br/>For the frontend you can display these links in a listview type=languagelink.<br/><br/>For folders, you can define a <b>document</b> in each language, which is used if for a document within the folder no corresponding document in the other language is set.',
	'langlink_support'=>'active',
	'langlink_support_backlinks'=>'Generate back links automatically',
	'langlink_support_backlinks_information'=>'Back links can be generated automatically for documents/objects (not folders). The other document should not be open in an editor tab!',
	'langlink_support_recursive'=>'Generate language links recursive',
	'langlink_support_recursive_information'=>'Setting of langauge links can be done recursively for documents/objects (but not folders). This sets all possible links and tries to establish the language-circle as fast as possible. The other documents should not be open in an editor tab!',
	'language_already_exists'=>'This language already exists',
	'language_country_missing'=>'Please select also a country',
	'language_notice'=>'The backend language/charset change will only take effect everywhere after restarting webEdition.',
	'locale_add'=>'Add language',
	'locale_countries'=>'Country',
	'locale_information'=>'Add all languages for which you would provide a web page.<br/><br/>This preference will be used for the glossary check and the spellchecking.',
	'locale_languages'=>'Language',
	'logFormmailRequests'=>'Log formmail requests',
	
	'login'=>array(
		'deactivateWEstatus'=>'hide the webEdition version status',
		'login'=>'LogIn',
		'windowtypeboth'=>'both, as POPUP and in the same window',
		'windowtypepopup'=>'only as POPUP',
		'windowtypesame'=>'only in the same window',
		'windowtypes'=>'Allow to start webEdition',
	),
	'log_is_empty'=>'The log is empty!',
	'mailer_information'=>'Adjust whether webEditionin should dispatch emails via the integrated PHP function or a seperate SMTP server should be used.<br/><br/>When using a SMTP mail server, the risk that messages are classified by the receiver as a "Spam" is lowered.',
	'mailer_php'=>'Use php mail() function',
	'mailer_smtp'=>'Use SMTP server',
	'mailer_type'=>'Mailer type',
	'maximize'=>'Maksymalizuj',
	
	'message_reporting'=>array(
		'headline'=>'Notifications',
		'information'=>'You can decide on the respective check boxes whether you like to receive a notice for webEdition operations as for example saving, publishing or deleting.',
		'show_errors'=>'Show Errors',
		'show_notices'=>'Show Notices',
		'show_warnings'=>'Show Warnings',
	),
	'module_activation'=>array(
		'headline'=>'Module activation',
		'information'=>'Here you can activate or deactivate your modules if you do not need them.<br/>Deactivated modules improve the overall performance of webEdition. <br/>For some modules, you have to restart webEdition to activate.<br/>The Shop module requires the Customer module, the Workflow module requires the ToDo-Messaging module.',
	),
	'module_object'=>'Moduł DB/Obiekt',
	'more_days'=>'%s days',
	'more_hours'=>'%s hours',
	'more_minutes'=>'%s minutes',
	'more_weeks'=>'%s weeks',
	'more_years'=>'%s years',
	'must_register'=>'Musisz być zarejestrowany',
	'navigation'=>'Navigation',
	'navigation_directoryindex_description'=>'After a change, a rebuild is required (i.e. navigation cache, objects ...)',
	'navigation_directoryindex_hide'=>'in the navigation output',
	'navigation_directoryindex_names'=>'DirectoryIndex file names (comma separated, incl. file extensions, i.e. `index.php,index.html`',
	'navigation_entries_from_document'=>'Create new navigation entries from the document as',
	'navigation_entries_from_document_folder'=>'folder',
	'navigation_entries_from_document_item'=>'item',
	'navigation_objectseourls'=>'in the navigation output',
	'navigation_rules_continue'=>'Continue to evaluate navigation rules after a first match',
	'never'=>'never',
	'no'=>'no',
	'objectlinks_directoryindex_hide'=>'in links to objects',
	'off'=>'off',
	'on'=>'on',
	'pagelogger_dir'=>'Katalog pageLoggera',
	'performance'=>'Here you can set an appropriate performance level. The performance level should be adequate to the server system. If the system has limited resources (memory, timeout etc.) choose a slow level, otherwise choose a fast level.',
	'phpLocalScope'=>'Tag-Parser: <br/>assume PHP local scope==global scope',
	'phpLocalScope_information'=>'`If you just use we:tags in your templates, please select the option "no" (this is the standard value).<br/>
	If you use your own PHP code in templates, make sure, that all PHP variables which are used as attributs to we:tags, are saved in the $GLOBALS array.<br/>
	<br/>To ensure backwards compatibility with old PHP code, which store PHP variables in the local scope (i.e. $X=1;), you can select the option "yes". Be aware, that in this case, you might encounter problems while sending e-mails, i.e. in the Newsletter- and Shop-Module as welöl as with the we:sendMail-tag.<br/>
	In order to switch to the standard "no", please replace in your templates the php code in the following manner: replace $x=1; to $GLOBALS["x"]=1;.<br/><br/>We strongly recommend to switch to the standard setting "no".',
	'predefined'=>'Wymary domyślne',
	'preload'=>'Ładowanie ustawień, zaczekaj chwilę ...',
	'preload_wait'=>'Ładuję ustawienia',
	'proxyaddr'=>'Adres',
	'proxypass'=>'Hasło',
	'proxyport'=>'Port',
	'proxyuser'=>'Nazwa użytkownika',
	'proxy_information'=>'Specify your Proxy settings for your server here, if your server uses a proxy for the connection with the Internet.',
	'question_change_to_seem_start'=>'Chcesz zamienić na wybrany dokument?',
	'removefirstparagraph_default'=>'Default value for the<br/><em>removefirstparagraph</em> attribute in<br/>&lt;we:textarea&gt;',
	'safari_wysiwyg'=>'Użyj edytora Wysiwyg<br/>Safari (wersja beta)',
	'saved'=>'Ustawienia zostały zapamiętane.',
	'saved_successfully'=>'Zapisano ustawienia',
	'save'=>'Zapisano ustawienia, zaczekaj chwilę ...',
	'save_wait'=>'Zapisuję ustawienia',
	'seem'=>'seeMode',
	'seem_deactivate'=>'Wyłącz seeMode',
	'seem_startdocument'=>'Dokument startowy - seeMode',
	'seem_start_type_cockpit'=>'Cockpit',
	'seem_start_type_document'=>'Document',
	'seem_start_type_object'=>'Object',
	'seem_start_type_weapp'=>'WE-App',
	'seoinside_hideineditmode'=>'Do not use in editmode',
	'seoinside_hideinwebedition'=>'Do not use in preview',
	'showinputs_default'=>'Standardowe ustawienie dla <br/>atrybutu <em>showinputs</em> w <br/>&lt;we:img&gt;',
	'show_debug_frame'=>'Wyświetl Debug-Frame',
	'show_expert'=>'Wyświetl ustawienia eksperta',
	'show_predefined'=>'Wyświetl wymiary domyślne',
	'sidebar'=>'Sidebar',
	'sidebar_deactivate'=>'deactivate',
	'sidebar_document'=>'Document',
	'sidebar_show_on_startup'=>'show on startup',
	'sidebar_width'=>'Width in pixel',
	'smtp_auth'=>'Authentication',
	'smtp_encryption'=>'encrypted transport',
	'smtp_encryption_none'=>'no',
	'smtp_encryption_ssl'=>'SSL',
	'smtp_encryption_tls'=>'TLS',
	'smtp_halo'=>'SMTP halo',
	'smtp_password'=>'Password',
	'smtp_port'=>'SMTP port',
	'smtp_server'=>'SMTP server',
	'smtp_timeout'=>'SMTP timeout',
	'smtp_username'=>'User name',
	'specify'=>'Ustaw',
	'start_automatic'=>'Uruchom automatycznie',
	'static'=>'Strony Statyczne',
	'suppress404code'=>'suppress 404 not found',
	
	'tab'=>array(
		'advanced'=>'Zaawansowane',
		'backup'=>'Backup',
		'cache'=>'Cache',
		'cockpit'=>'Cockpit',
		'countries'=>'Countries',
		'defaultAttribs'=>'we:tag defaults',
		'editor'=>'Edytor',
		'email'=>'E-Mail',
		'error_handling'=>'Obsługa błędów',
		'extensions'=>'Rozszerzenia plików',
		'language'=>'Languages',
		'message_reporting'=>'Notifications',
		'modules'=>'Moduły',
		'proxy'=>'Serwer Proxy',
		'recipients'=>'Formmail',
		'seolinks'=>'SEO links',
		'system'=>'System',
		'ui'=>'Interfejs',
		'validation'=>'Walidacja',
		'versions'=>'Versioning',
	),
	'tab_glossary'=>'Glossary',
	'taglinks_directoryindex_hide'=>'preset value for tags',
	'taglinks_objectseourls'=>'preset value for tags',
	'templates'=>'Templates',
	'thumbnail_dir'=>'Thumbnail directory',
	'tree_count'=>'Liczba obiektów do wyświetlenia',
	'tree_count_description'=>'Wartość ta podaje maksymalną liczbę wpisów do wyświetlenia w lewym oknie nawigacji.',
	'tree_title'=>'Tytuł drzewa',
	'unblock'=>'Unblock',
	'urlencode_objectseourls'=>'URLencode the SEO-urls',
	'useauth'=>'Serwer stosuje Autentyfikację HTTP<br/>w katalogu webEdition<br/>',
	'useproxy'=>'Użyj Serwera Proxy do aktualizacji Live-Update<br/>',
	'use_it'=>'Użyj',
	'use_jeditor'=>'Use',
	'use_jupload'=>'Use java upload',
	'versioning'=>'Versioning',
	'versioning_activate_text'=>'Activate versioning for some or all content types.',
	'versioning_anzahl'=>'Number',
	'versioning_anzahl_text'=>'Number of versions which will be created for each document or object.',
	'versioning_create'=>'Create Version',
	'versioning_create_text'=>'Determine which actions provoke new versions. Either if you publish or if you save, unpublish, delete or import files, too.',
	'versioning_templates_text'=>'Define special values for the <b>versioning of templates</b>',
	'versioning_time'=>'Time period',
	'versioning_time_text'=>'If you specify a time period, only versions are saved which are created in this time until today. Older versions will be deleted.',
	'versioning_wizard'=>'Open Versions-Wizard',
	'versioning_wizard_text'=>'Open the Version-Wizard to delete or reset versions.',
	'versions_create_always'=>'always',
	'versions_create_publishing'=>'only when publishing',
	'versions_create_tmpl_always'=>'always',
	'versions_create_tmpl_publishing'=>'only using special button',
	'version_all'=>'all',
	'we_doctype_workspace_behavior'=>'Wybór zachowania typu dokumentu',
	'we_doctype_workspace_behavior_0'=>'Standardowo',
	'we_doctype_workspace_behavior_1'=>'Odwrotnie',
	'we_doctype_workspace_behavior_hint0'=>'Standardowy katalog typu dokumentu musi się znajdować wewnątrz obszaru roboczego użytkownika, aby użytkownik mógł zmieniać typ dokumentu.',
	'we_doctype_workspace_behavior_hint1'=>'Obszar roboczy użytkownika musi się znajdować wewnątrz ustawionego w typie dokumentu katalogu standadardowego, aby użytkownik mógł zminiać typ dokumentu.',
	'we_extensions'=>'Rozszerzenia webEdition',
	'we_max_upload_size'=>'Maksymalna wielkość uploadu w<br/>tekstach wskazówek',
	'we_max_upload_size_hint'=>'(w MB, 0=automatycznie)',
	'we_new_folder_mod'=>'Prawa dostępu do <br/>nowych katalogów',
	'we_new_folder_mod_hint'=>'(Standardowo 755)',
	
	'we_scheduler_trigger'=>array(
		'cron'=>'external cron-job',
		'description'=>'Choose when the scheduler should be triggered.<br/>The options before and after page delivery trigger only on dynamic pages<br/>Before page delivery can cause longer page loading.<br/>Cron-job should be used whereever possible, but requires to call <code>webEdition/triggerWEtasks.php</code>',
		'head'=>'Trigger of the scheduler',
		'postDoc'=>'after delivery of the page',
		'preDoc'=>'before delivery of the page',
	),
	'width'=>'Szerokość',
	'wysiwyglinks_directoryindex_hide'=>'in links from the WYSIWYG editor',
	'wysiwyglinks_objectseourls'=>'in links from the WYSIWYG editor',
	'wysiwyg_type'=>'Select editor for textareas',
	'xhtml_debug_explanation'=>'Wyszukiwanie błędów w XHTML (Debugging) wspierasz tworząc bezbłedne strony WWW. Opcjonalnie można sprawdzić każde wystąpienie znacznika we:Tags pod kątem ważności a w razie potrzeby usunąć bądź wyswietlić błędne atrybuty. Pamiętaj, że proces ten wymaga trochę czasu i może być używany tylko w trakcie tworzenia nowej strony WWW.',
	'xhtml_debug_headline'=>'XHTML-Debugging',
	'xhtml_debug_html'=>'Włącz Debugging XHTML',
	'xhtml_default'=>'Standardowe ustawienie atrybutu <em>xml</em> w we:Tags',
	'xhtml_remove_wrong'=>'Usuń błędne atrybuty',
	'xhtml_show_wrong_error_log_html'=>'W logu błędów (PHP)',
	'xhtml_show_wrong_headline'=>'Powiadomienie przy błędnych atrybutach',
	'xhtml_show_wrong_html'=>'Włącz',
	'xhtml_show_wrong_js_html'=>'Jako komunikat JavaScript',
	'xhtml_show_wrong_text_html'=>'Jako tekst',
	'yes'=>'yes',
);