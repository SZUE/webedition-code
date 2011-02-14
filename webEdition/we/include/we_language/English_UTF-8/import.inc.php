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
 * Language file: import.inc.php
 * Provides language strings.
 * Language: English
 */
$l_import = array(
		'title' => 'Import Wizard',
		'wxml_import' => 'webEdition XML import',
		'gxml_import' => 'Generic XML import',
		'csv_import' => 'CSV import',
		'import' => 'Importing',
		'none' => '-- none --',
		'any' => '-- none --',
		'source_file' => 'Source file',
		'import_dir' => 'Target directory',
		'select_source_file' => 'Please choose a source file.',
		'we_title' => 'Title',
		'we_description' => 'Description',
		'we_keywords' => 'Keywords',
		'uts' => 'Unix-Timestamp',
		'unix_timestamp' => 'The unix time stamp is a way to track time as a running total of seconds. This count starts at the Unix Epoch on January 1st, 1970.',
		'gts' => 'GMT Timestamp',
		'gmt_timestamp' => 'General Mean Time ie. Greenwich Mean Time (GMT).',
		'fts' => 'Specified format',
		'format_timestamp' => 'The following characters are recognized in the format parameter string: Y (a full numeric representation of a year, 4 digits), y (a two digit representation of a year), m (numeric representation of a month, with leading zeros), n (numeric representation of a month, without leading zeros), d (day of the month, 2 digits with leading zeros), j (day of the month without leading zeros), H (24-hour format of an hour with leading zeros), G (24-hour format of an hour without leading zeros), i (minutes with leading zeros), s (seconds, with leading zeros)',
		'import_progress' => 'Importing',
		'prepare_progress' => 'Preparing',
		'finish_progress' => 'Finished',
		'finish_import' => 'The Import was successful!',
		'import_file' => 'File import',
		'import_data' => 'Data import',
		'import_templates' => 'Template import',
		'template_import' => 'First Steps Wizard',
		'txt_template_import' => 'Import ready example templates and template sets from the webEdition server',
		'file_import' => 'Import local files',
		'txt_file_import' => 'Import one or more files from the local hard drive.',
		'site_import' => 'Import files from server',
		'site_import_isp' => 'Import graphics from server',
		'txt_site_import_isp' => 'Import graphics form the root-directory of the server. Set filter options to choose which graphics are to be imported.',
		'txt_site_import' => 'Import files from the root-directory of the server. Set filter options to choose if images, HTML pages, Flash, JavaScript, or CSS files, plain-text documents, or other types of files are to be imported.',
		'txt_wxml_import' => 'webEdition XML files contain information about webEdition documents, templates or objects. Choose a directory to which the files are to be imported.',
		'txt_gxml_import' => 'Import "flat" XML files, such as those provided by phpMyAdmin. The dataset fields have to be allocated to the webEdition dataset fields. Use this to import XML files exported from webEdition without the export module.',
		'txt_csv_import' => 'Import CSV files (Comma Separated Values) or modified textformats (e. g. *.txt). The dataset fields are assigned to the webEdition fields.',
		'add_expat_support' => 'In order to implement support for the XML expat parser, you will need to recompile PHP to add support for this library to your PHP build. The expat extension, created by James Clark, can be found at http://www.jclark.com/xml/.',
		'xml_file' => 'XML file',
		'templates' => 'Templates',
		'classes' => 'Classes',
		'predetermined_paths' => 'Path settings',
		'maintain_paths' => 'Maintain paths',
		'import_options' => 'Import options',
		'file_collision' => 'File collision',
		'collision_txt' => 'When you import a file to a folder that contains a file with the same name, a file name collision occurs. You can specify how the import wizard should handle the new and existing files.',
		'replace' => 'Replace',
		'replace_txt' => 'Delete the existing file and replace it with the new file.',
		'rename' => 'Rename',
		'rename_txt' => 'Assign a unique name to the new file. All links will be adjusted to the new filename.',
		'skip' => 'Skip',
		'skip_txt' => 'Skip the current file and leave both copies in their original locations.',
		'extra_data' => 'Extra Data',
		'integrated_data' => 'Import integrated data',
		'integrated_data_txt' => 'Select this option to import integrated data by templates or documents.',
		'max_level' => 'to level',
		'import_doctypes' => 'Import doctypes',
		'import_categories' => 'Import categories',
		'invalid_wxml' => 'The XML document is well-formed but not valid. It does not apply to the webEdition document type definition (DTD).',
		'valid_wxml' => 'The XML document is well-formed and valid.  It applies to the webEdition document type definition (DTD).',
		'specify_docs' => 'Please choose the documents to import.',
		'specify_objs' => 'Please choose the objects to import.',
		'specify_docs_objs' => 'Please choose whether to import documents and objects.',
		'no_object_rights' => 'You do not have authorization to import objects.',
		'display_validation' => 'Display XML validation',
		'xml_validation' => 'XML validation',
		'warning' => 'Warning',
		'attribute' => 'Attribute',
		'invalid_nodes' => 'Invalid XML node at position ',
		'no_attrib_node' => 'No XML element "attrib" at position ',
		'invalid_attributes' => 'Invalid attributes at position ',
		'attrs_incomplete' => 'The list of #required and #fixed attributes is incomplete at position ',
		'wrong_attribute' => 'The attribute name is neither defined as #required nor #implied at position ',
		'documents' => 'Documents',
		'objects' => 'Objects',
		'fileselect_server' => 'Load file from server',
		'fileselect_local' => 'Upload file from local hard disc',
		'filesize_local' => 'Because of restrictions within PHP, the file that you wish to upload cannot exceed %s.',
		'xml_mime_type' => 'The selected file cannot be imported. Mime-type:',
		'invalid_path' => 'The path of the source file is invalid.',
		'ext_xml' => 'Please select a source file with the extension ".xml".',
		'store_docs' => 'Target directory documents',
		'store_tpls' => 'Target directory templates',
		'store_objs' => 'Target directory objects',
		'doctype' => 'Document type',
		'gxml' => 'Generic XML',
		'data_import' => 'Import data',
		'documents' => 'Documents',
		'objects' => 'Objects',
		'type' => 'Type',
		'template' => 'Template',
		'class' => 'Class',
		'categories' => 'Categories',
		'isDynamic' => 'Generate page dynamically',
		'extension' => 'Extension',
		'filetype' => 'Filetype',
		'directory' => 'Directory',
		'select_data_set' => 'Select dataset',
		'select_docType' => 'Please choose a template.',
		'file_exists' => 'The selected source file does not exist. Please check the given file path. Path: ',
		'file_readable' => 'The selected source file is not readable and thereby cannot be imported.',
		'asgn_rcd_flds' => 'Assign data fields',
		'we_flds' => 'webEdition&nbsp;fields',
		'rcd_flds' => 'Dataset&nbsp;fields',
		'name' => 'Name',
		'auto' => 'Automatic',
		'asgnd' => 'Assigned',
		'pfx' => 'Prefix',
		'pfx_doc' => 'Document',
		'pfx_obj' => 'Object',
		'rcd_fld' => 'Dataset field',
		'import_settings' => 'Import settings',
		'xml_valid_1' => 'The XML file is valid and contains',
		'xml_valid_s2' => 'elements. Please select the elements to import.',
		'xml_valid_m2' => 'XML child node in the first level with different names. Please choose the XML node and the number of elements to import.',
		'well_formed' => 'The XML document is well-formed.',
		'not_well_formed' => 'The XML document is not well-formed and cannot be imported.',
		'missing_child_node' => 'The XML document is well-formed, but contains no XML nodes and can therefore not be imported.',
		'select_elements' => 'Please choose the datasets to import.',
		'num_elements' => 'Please choose the number of datasets from 1 to ',
		'xml_invalid' => '',
		'option_select' => 'Selection..',
		'num_data_sets' => 'Datasets:',
		'to' => 'to',
		'assign_record_fields' => 'Assign data fields',
		'we_fields' => 'webEdition fields',
		'record_fields' => 'Dataset fields',
		'record_field' => 'Dataset field ',
		'attributes' => 'Attributes',
		'settings' => 'Settings',
		'field_options' => 'Field options',
		'csv_file' => 'CSV file',
		'csv_settings' => 'CSV settings',
		'xml_settings' => 'XML settings',
		'file_format' => 'File format',
		'field_delimiter' => 'Separator',
		'comma' => ', {comma}',
		'semicolon' => '; {semicolon}',
		'colon' => ': {colon}',
		'tab' => "\\t {tab}",
		'space' => '  {space}',
		'text_delimiter' => 'Text separator',
		'double_quote' => '" {double quote}',
		'single_quote' => '\' {single quote}',
		'contains' => 'First line contains field name',
		'split_xml' => 'Import datasets sequential',
		'wellformed_xml' => 'Validation for well-formed XML',
		'validate_xml' => 'XML validiation',
		'select_csv_file' => 'Please choose a CSV source file.',
		'select_seperator' => 'Please, select a seperator.',
		'format_date' => 'Date format',
		'info_sdate' => 'Select the date format for the webEdition field',
		'info_mdate' => 'Select the date format for the webEdition fields',
		'remark_csv' => 'You are able to import CSV files (Comma Separated Values) or modified text formats (e. g. *.txt). The field delimiter (e. g. , ; tab, space) and text delimiter (= which encapsulates the text inputs) can be preset at the import of these file formats.',
		'remark_xml' => 'To avoid the predefined timeout of a PHP-script, select the option "Import data-sets separately", to import large files.<br>If you are unsure whether the selected file is webEdition XML or not, the file can be tested for validity and syntax.',
		'import_docs' => "Import documents",
		'import_templ' => "Import templates",
		'import_objs' => "Import objects",
		'import_classes' => "Import classes",
		'import_doctypes' => "Import DocTypes",
		'import_cats' => "Import categories",
		'documents_desc' => "Select the directory where the documents will be imported. If the option \"Maintain paths\" is checked, the documents paths will be restored, otherwise the documents paths will be ignored.",
		'templates_desc' => "Select the directory where the templates will be imported. If the option \"Maintain paths\" is checked, the template paths will be restored, otherwise the template paths will be ignored.",
		'handle_document_options' => 'Documents',
		'handle_template_options' => 'Templates',
		'handle_object_options' => 'Objects',
		'handle_class_options' => 'Classes',
		'handle_doctype_options' => "Doctype",
		'handle_category_options' => "Category",
		'log' => 'Details',
		'start_import' => 'Start import',
		'prepare' => 'Prepare...',
		'update_links' => 'Update links...',
		'doctype' => 'Document-Type',
		'category' => 'Category',
		'end_import' => 'Import finshed',
		'handle_owners_option' => 'Owners data',
		'txt_owners' => 'Import linked owmers data.',
		'handle_owners' => 'Restore owners data',
		'notexist_overwrite' => 'If the user do not exist, the option "Overwrite owners data" will be applied',
		'owner_overwrite' => 'Overwrite owners data',
		'name_collision' => 'Name collision',
		'item' => 'Article',
		'backup_file_found' => 'The file looks like webEdition backup file. Please use the \"Backup\" option from the \"File\" menu to import the data.',
		'backup_file_found_question' => 'Would you like now to close the current dialog and to start the backup wizard?',
		'close' => 'Close',
		'handle_file_options' => 'Files',
		'import_files' => 'Import files',
		'weBinary' => 'File',
		'format_unknown' => 'The file format is unknown!',
		'customer_import_file_found' => 'The file looks like import file with customer\'s data. Please use the \"Import/Export\" option from the customer module (PRO) to import the data.',
		'upload_failed' => 'The file can\'t be uploaded. Please verify if the file size is greater then %s',
		'import_navigation' => 'Import navigation',
		'weNavigation' => 'Navigation',
		'navigation_desc' => 'Select the directory where the navigation will be imported.',
		'weNavigationRule' => 'Navigation rule',
		'weThumbnail' => 'Thumbnail',
		'import_thumbnails' => 'Import thumbnails',
		'rebuild' => 'Rebuild',
		'rebuild_txt' => 'Automatic rebuild',
		'finished_success' => 'The import of the data was successful.',
		'encoding_headline' => 'Charset',
		'encoding_noway' => 'A conversion  is only possible between ISO-8859-1 and UTF-8 <br/>and with a set default charset (settings dialog)',
		'encoding_change' => "Change, from '",
		'encoding_XML' => '',
		'encoding_to' => "' (XML file) to '",
		'encoding_default' => "' (standard)",
);