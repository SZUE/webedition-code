<?php
/** Generated language file of webEdition CMS */
$l_backup=array(
	'backup_deleted'=>'Резервный файл %s удален',
	'backup_form'=>'Резервный файл',
	'backup_log_exp'=>'Лог сохранен в /webEdition/we_backup/data/lastlog.php',
	'banner_info'=>'Статистические данные и данные по баннерам модуля баннера/статистики.',
	'binary_info'=>'Бинарные данные: изображения, PDF и прочие документы.',
	'bzip'=>'bzip',
	'cannot_save_backup'=>'Unable to save backup file.',
	'cannot_save_tmpfile'=>'Unable to create temporary file. Chek if you have write premissions over %s',
	'cannot_send_backup'=>'Unable to execute backup.',
	'cannot_split_file'=>'Невозможно подготовить  файл `%s` к импортированию!',
	'cannot_split_file_ziped'=>'Файл скомпрессирован методом, не поддерживаемым системой.',
	'can_not_open_file'=>'Unable to open file `%s`.',
	'charset_warning'=>'If you encounter problems when restoring a backup, please ensure that the <strong>target system uses the same character set as the source system</strong>. This applies both to the character set of the database (collation) as well as for the character set of the user interface language!',
	'compress'=>'компрессировать',
	'compress_file'=>'Компрессировать файл',
	'convert_charset'=>'Attention! Using this option in an existing site can lead to total loss of all data, please follow the instruction in http://documentation.webedition.org/de/webedition/administration/charset-conversion-of-legacy-sites',
	'convert_charset_data'=>'While importing the backup, convert the site from ISO to UTF-8',
	'core_info'=>'Все документы и шаблоны.',
	'customer_import_file_found'=>'Этот файл должен быть импортирован совместно с данными клиентов. Для импорта файла воспользуйтесь опцией "Импорта/экспорта" в  модуле управления клиентами (ПРО).',
	'customer_info'=>'Данные о клиенте модуля управления клиентами.',
	'decompress'=>'декомпрессировать',
	'defaultcharset_warning'=>'<span style="color:ff0000">Attention! The standard charset is not defined.</span> For some server configurations, this can lead to problems while importing backups.!',
	'delete_entry'=>'Удаление %s',
	'delete_nok'=>'Этот файл не может быть удален!',
	'delete_old_files'=>'Delete old files...',
	'delold_confirm'=>'Вы уверены, что хотите удалить все файлы с сервера?',
	'delold_notice'=>'Рекомендуется предварительно удалить имеющиеся файлы.<br/>Удалить?',
	'del_backup_confirm'=>'Удалить выбранный резервный файл?',
	'download'=>'Please click here.',
	'download_failed'=>'Either the file you requested does not exist or you are not permitted to download it.',
	'download_file'=>'Сохранить файл',
	'download_starting'=>'Download of the backup file has been started.<br/><br/>If the download does not start after 10 seconds,<br/>',
	'error'=>'Error',
	'error_compressing_backup'=>'An error occured while compressing the backup, so the backup could not be finished!',
	'error_delete'=>'Невозможно удалить резервный файл! Попробуйте его удалить с помощью FTP из директирии /webEdition/we_backup.',
	'error_timeout'=>'An timeout occured while creating the backup, so the backup could not be finished!',
	
	'export'=>array(
		'history_data'=>'Save data of the widget last worked on',
		'temporary_data'=>'Save temporary data',
		'weapp'=>'Save the data of the WE-App',
	),
	'export_backup_log'=>'Создать лог',
	'export_banner_data'=>'Save banner data',
	'export_banner_dep'=>'You have selected the option `Save banner data`. The banner data need the documents and because of that, `Save documents and templates` has been automatically selected.',
	'export_binary_data'=>'Сохранить бинарные файлы (изображения, PDF, ...)',
	'export_binary_dep'=>'Вы выбрали опцию: «сохранить бинарные файлы». Для корректного функционирования бинарных файлов требуются соответствующие документы. Опция: «сохранить документы и шаблоны» выбирается автоматически.',
	'export_check_all'=>'Check all',
	'export_configuration_data'=>'Сохранить конфигурацию',
	'export_content'=>'Exporting content',
	'export_core_data'=>'Сохранить документы и шаблоны',
	'export_customer_data'=>'Сохранить данные «Управления клиентами»',
	'export_doctypes'=>'Сохранить типы документов',
	'export_export_data'=>'Сохранить экспортированные данные',
	'export_extern_data'=>'Сохранить внешние файлы/директории webEdition',
	'export_files'=>'Сохранение файлов',
	'export_glossary_data'=>'Save glossary data',
	'export_indexes'=>'Save indexes',
	'export_info'=>'Данные модуля экспорта.',
	'export_links'=>'Save links',
	'export_location'=>'Specify where you want to save the backup file. If it is stored on the server, you find the file in `/webEdition/we_backup/data/`.',
	'export_location_send'=>'On local hard disk',
	'export_location_server'=>'On server',
	'export_newsletter_data'=>'Сохранить данные листа рассылки',
	'export_newsletter_dep'=>'You have selected the option `Save newsletter data`. The Newsletter Module needs the documents and users data and because of that, `Save documents and templates` and `Save customers data` has been automatically selected.',
	'export_object_data'=>'Сохранить объекты и классы',
	'export_options'=>'Выберите файлы, предназначенные для сохранения.',
	'export_prefs'=>'Save preferences',
	'export_schedule_data'=>'Сохранить данные планировщика',
	'export_schedule_dep'=>'You have selected the option `Save schedule data`. The Schedule Module needs the documents and objects and because of that, `Save documents and templates` and `Save objects and classes` has been automatically selected.',
	'export_settings_data'=>'Сохранить настройки',
	'export_shop_data'=>'Сохранить данные «Интернет-магазина»',
	'export_shop_dep'=>'You have selected the option `Save shop data`. The Shop Module needs the customers data and because of that, `Save customers data` has been automatically selected.',
	'export_spellchecker_data'=>'Save spellchecker data',
	'export_step1'=>'Step 1 of 2 - Backup parameters',
	'export_step2'=>'Step 2 of 2 - Backup complete',
	'export_templates'=>'Сохранение шаблонов',
	'export_temporary_data'=>'Save temporary data',
	'export_temporary_dep'=>'Вы выбрали опцию: «сохранить временные файлы». Для корректного функционирования временных файлов требуются соответствующие документы. Опция: «сохранить документы и шаблоны» выбирается автоматически.',
	'export_title'=>'Export',
	'export_todo_data'=>'Save task/messaging data',
	'export_todo_dep'=>'You have selected the option `Save task/messaging data`. The Task/Messaging Module needs the users data and because of that, `Save user data` has been automatically selected.',
	'export_users_data'=>'Сохранить данные «Управления пользователями»',
	'export_user_data'=>'Сохранить данные пользователя',
	'export_versions_binarys_data'=>'Save Version-Binary-Files',
	'export_versions_binarys_dep'=>'You have selected the option `Save Version-Binary-Files`. The Version-Binary-Files need the documents, objects and version data and because of that, `Save documents and templates`, `Save object and classes` and `Save version data` has been automatically selected.',
	'export_versions_data'=>'Save version data',
	'export_versions_dep'=>'You have selected the option `Save version data`. The version data need the documents, objects and version-binary-files and because of that, `Save documents and templates`, `Save object and classes` and `Save Version-Binary-Files` has been automatically selected.',
	'export_voting_data'=>'Восстановить данные голосования',
	'export_workflow_data'=>'Save workflow data',
	'export_workflow_dep'=>'You have selected the option `Save workflow data`. The Workflow Module needs the documents and users data and because of that,  `Save documents and templates` and `Save workflow data` has been automatically selected.',
	'external_backup'=>'Сохранение внешних данных и директорий',
	'extern'=>'Restore webEdition external files and folders',
	'extern_backup_question_exp'=>'You selected the option `Save webEdition external files and folders`. This option could take some time and may lead to some system-specific errors. Do you want to proceed anyway?',
	'extern_backup_question_exp_all'=>'You selected the option `Check all`. That also checks the option `Save webEdition external files and folders`. This option could take some time and may lead to some system-specific errors. <br/><br/>Do you want to let `Save webEdition external files and folders` be checked anyway?',
	'extern_backup_question_imp'=>'You selected the option `Restore webEdition external files and folders`. This option could take some time and may lead to some system-specific errors. Do you want to proceed anyway?',
	'extern_backup_question_imp_all'=>'You selected the option `Check all`. That also checks the option `Restore webEdition external files and folders`. This option could take some time and may lead to some system-specific errors. <br/><br/>Do you want to let `Restore webEdition external files and folders` be checked anyway?',
	'extern_exp'=>'Внимание! Выполнение данной операции займет продолжительное время и может привести к системным ошибкам.',
	'extern_files_question'=>'Save webEdition external files and folders.',
	'extern_files_size'=>'Since the maximum file size is limited to %.1f MB (%s byte) by your database settings, multiple files may be created.',
	'filename'=>'Файл (имя)',
	'filename_compression'=>'Введите имя резервного файла. Вы также можете активировать команду компрессирования файла. Резервный файл компрессируется с помощью gzip с расширением .gz. Эта операция займет некоторое время!',
	'filename_info'=>'Введите имя резервного файла.',
	'files_not_deleted'=>'Один или несколько файлов, предназначенных к удалению, полностью не удалены с сервера! По-видимому, эти файлы с защитой от записи! Их нужно удалить вручную. К ним относятся следующие файлы:',
	'file_missing'=>'Не хватает резервного файла!',
	'file_not_readable'=>'The backup file is not readable. Please check the file permissions.',
	'finished'=>'Finished',
	'finished_fail'=>'The import of backup data has not finished successfully.',
	'finished_success'=>'The import of backup data has finished successfully.',
	'finish'=>'The backup was successfully created.',
	'finish_error'=>'Ошибка: невозможно создать резервный файл',
	'finish_warning'=>'Внимание: сохранение резервного файла завершено, но не все файлы сохранены в полном объеме',
	'format_unknown'=>'Неизвестный формат файла!',
	'ftp_hint'=>'Attention! Use the Binary mode for the download by FTP if the backup file is zip compressed! A download in ASCII 	mode destroys the file, so that it cannot be recovered!',
	'glossary_info'=>'Data from the glossary.',
	'gzip'=>'gzip',
	'history_info'=>'The data of the widget last worked on',
	
	'import'=>array(
		'history_data'=>'Import data for the widget last worked on',
		'temporary_data'=>'Restore temporary data',
		'weapp'=>'Import the data of the WE-App',
	),
	'import_banner_data'=>'Restore banner data',
	'import_banner_dep'=>'You have selected the option `Restore banner data`. The banner data need the documents data and because of that, `Restore documents and templates` has been automatically selected.',
	'import_binary_data'=>'Восстановить бинарные файлы (изображения, PDF, ...)',
	'import_binary_dep'=>'Вы выбрали опцию: «восстановить бинарные файлы». Для корректного функционирования бинарных файлов требуются соответствующие документы. Опция: «восстановить документы и шаблоны» выбирается автоматически.',
	'import_check_all'=>'Check all',
	'import_configuration_data'=>'Воссоздать конфигурацию',
	'import_content'=>'Importing content',
	'import_core_data'=>'Восстановить документы и шаблоны',
	'import_customer_data'=>'Восстановить данные «Управления клиентами»',
	'import_doctypes'=>'Восстановить типы документов',
	'import_export_data'=>'Восстановить экспортируемые данные',
	'import_extern_data'=>'Восстановить внешние файлы/директории webEdition',
	'import_files'=>'Восстановление файлов',
	'import_file_found'=>'Этот файл должен быть импортирован в webEdition. Для импорта файла воспользуйтесь опцией "Импорта/экспорта" в пункте меню "Файл".',
	'import_file_found_question'=>'Закрыть данное диалоговое окно и запустить Мастер импорта/экспорта?',
	'import_from_local'=>'Загрузить данные файла, сохраненного локально',
	'import_from_server'=>'Загрузить данные с сервера',
	'import_glossary_data'=>'Restore glossary data',
	'import_indexes'=>'Restore indexes',
	'import_links'=>'Restore links',
	'import_newsletter_data'=>'Восстановить данные листа рассылки',
	'import_newsletter_dep'=>'You have selected the option `Restore newsletter data`. The Newsletter Module needs the documents and users data and because of that,  `Restore documents and templates` and `Restore customers data` has been automatically selected.',
	'import_object_data'=>'Восстановить объекты и классы',
	'import_options'=>'Выберите файлы, предназначенные для восстановления.',
	'import_prefs'=>'Restore preferences',
	'import_schedule_data'=>'Восстановить данные планировщика',
	'import_schedule_dep'=>'You have selected the option `Restore schedule data`. The Schedule Module needs the documents data and objects and because of that, `Restore documents and templates` and `Restore objects and classes` has been automatically selected.',
	'import_settings_data'=>'Восстановить настройки',
	'import_shop_data'=>'Восстановить данные «Интернет-магазина»',
	'import_shop_dep'=>'You have selected the option `Restore shop data`. The Shop Module needs the customers data and because of that, `Restore customers data` has been automatically selected.',
	'import_spellchecker_data'=>'Restore spellchecker data',
	'import_templates'=>'Восстановление шаблонов',
	'import_temporary_dep'=>'Вы выбрали опцию: «восстановить временные файлы». Для корректного функционирования временных файлов требуются соответствующие документы. Опция: «восстановить документы и шаблоны» выбирается автоматически.',
	'import_todo_data'=>'Restore task/messaging data',
	'import_todo_dep'=>'You have selected the option `Restore task/messaging data`. The Task/Messaging Module needs the users data and because of that, `Restore user data` has been automatically selected.',
	'import_users_data'=>'Восстановить данные «Управления пользователями»',
	'import_user_data'=>'Восстановить данные пользователя',
	'import_versions_binarys_data'=>'Restore Version-Binary-Files',
	'import_versions_binarys_dep'=>'You have selected the option `Restore Version-Binary-Files`. The Version-Binary-Files need the documents data, object data an version data and because of that, `Restore documents and templates`, `Restore objects and classes and `Restore version data` has been automatically selected.',
	'import_versions_data'=>'Restore version data',
	'import_versions_dep'=>'You have selected the option `Restore version data`. The version data need the documents data, object data an version-binary-files and because of that, `Restore documents and templates`, `Restore objects and classes and `Restore Version-Binary-Files` has been automatically selected.',
	'import_voting_data'=>'Сохранить данные голосования',
	'import_workflow_data'=>'Restore workflow data',
	'import_workflow_dep'=>'You have selected the option `Restore workflow data`. The Workflow Module needs the documents and users data and because of that, `Restore documents and templates` and `Restore user data` has been automatically selected.',
	'name_notok'=>'Имя файла недействительно!',
	'newsletter_info'=>'Данные модуля листа рассылки.',
	'none'=>'-',
	'nothing_selected'=>'Ничего не выделено!',
	'nothing_selected_fromlist'=>'Выберите из списка резервный файл!',
	'nothing_to_delete'=>'Нет объекта удаления!',
	'no_resource'=>'Fatal Error: There are not enough resources to finish the backup!',
	'object_info'=>'Объекты и классы модуля базы данных/объекта.',
	'old_backups_warning'=>'Attention! We strongly recommend you to perform an update repeat after restoring a backup from a <strong>webEdition installation older than 6.3.0</strong>!',
	'option'=>'опции резервного копирования',
	'other_files'=>'Другие файлы',
	'preparing_file'=>'Процесс подготовки к восстановлению',
	'protect'=>'Protect backup file',
	'protect_txt'=>'The backup file will be protected from unprivileged download with additional php code. This protection requires additional disk space for import!',
	'query_is_too_big'=>'Резервный файл содержит файл, не подлежащий восстановлению, так как он превышает предел %s bytes!',
	'question_taketime'=>'Export can take some time.',
	'question_wait'=>'Please wait!',
	'rebuild'=>'Automatic rebuild',
	'recover_backup_unsaved_changes'=>'Some open files have unsaved changes. Please check these before you continue.',
	'recover_option'=>'Опции импорта',
	'save_before'=>'During import all existing data will be erased! It is recommended that you save your existing data first.',
	'save_not_checked'=>'Вы не выбрали место сохранения резервного файла!',
	'save_question'=>'Do you want to save your existing data?',
	'schedule_info'=>'Данные планировщика.',
	'select_server_file'=>'Choose the backup file you want to import from this list.',
	'select_upload_file'=>'Upload import from local file',
	'settings'=>'Restore preferences',
	'settings_info'=>'Настройки по применению webEdition.',
	'shop_info'=>'Данные по заказам интернет-магазина.',
	'show_all'=>'Show all files',
	'spellchecker_info'=>'Data for spellchecker: settings, general and personal dictionaries.',
	'step1'=>'Step 1/4 - Save existing data',
	'step2'=>'Step 2/4 - Select import source',
	'step3'=>'Step 3/4 - Import saved data',
	'step4'=>'Step 4/4 - Restore finished',
	'temporary_info'=>'Данные из неопубликованных документов и объектов.',
	'todo_info'=>'Сообщения и задачи модуля задач/сообщений.',
	'tools_export_desc'=>'Here you can save webEdition tools data. Please select the desired tools from the list.',
	'tools_import_desc'=>'Here you can restore webEdition tools data. Please select the desired tools from the list.',
	'too_big_file'=>'File `%s` cannot be written as the size exceeds the maximum file size.',
	'unselect_dep2'=>'Вы отменили выбор `%s`. Выбор следующих опций отменяется автоматически:',
	'unselect_dep3'=>'У Вас есть возможность повторного выбора опций, выбор которых ранее был отменен.',
	'unspecified_error'=>'An unknown error occurred!',
	'upload_failed'=>'Невозможно загрузить файл. Убедитесь в том, что размер файла не превышает %s',
	'user_info'=>'Данные пользователя модуля управления пользователыями.',
	'versions_binarys_info'=>'This option could take some time and memory because the folder /webEdition/we/versions/ could be very large. It is recommended to save this folder manually.',
	'versions_info'=>'Data from Versioning.',
	'view_log'=>'Backup-Log',
	'view_log_not_found'=>'The backup log file was not found!',
	'view_log_no_perm'=>'You do not have the needed permissions to view the backup log file!',
	'voting_info'=>'Данные модуля голосования.',
	'warning'=>'Warning',
	'we_backups'=>'Резервные файлы webEdition',
	'wizard_backup_title'=>'Create Backup Wizard',
	'wizard_recover_title'=>'Restore Backup Wizard',
	'wizard_title'=>'Restore Backup Wizard',
	'wizard_title_export'=>'Backup Export Wizard',
	'workflow_info'=>'Данные модуля электронного документооборота.',
	'working'=>'В работе',
	'zip'=>'zip',
);