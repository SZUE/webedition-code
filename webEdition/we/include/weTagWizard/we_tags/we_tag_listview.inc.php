<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_typeAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_multiSelectorAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_textAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_sqlRowAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_selectAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_choiceAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_selectorAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/conf/we_conf_language.inc.php');


$GLOBALS['weTagWizard']['weTagData']['needsEndtag'] = true;

$GLOBALS['weTagWizard']['attribute']['id353_type'] = new weTagData_typeAttribute('353', 'type', array(new weTagDataOption('-', false, '', array('id353_type'), array()), new weTagDataOption('document', false, '', array('id353_type','id354_name','id355_doctype','id356_categories','id357_catOr','id358_rows','id360_order','id361_desc','id362_offset','id832_languages','id373_searchable','id707_workspaceID','id743_cfilter','id746_recursive','id799_customers','id800_contenttypes','id805_id','id811_calendar', 'id827_numericalOrder'), array()), new weTagDataOption('search', false, '', array('id353_type','id354_name','id355_doctype','id356_categories','id357_catOr','id832_languages','id358_rows','id360_order','id361_desc','id363_casesensitive','id364_classid','id707_workspaceID','id743_cfilter','id827_numericalOrder'), array()), new weTagDataOption('category', false, '', array('id353_type','id354_name','id356_categories','id358_rows','id360_order','id361_desc','id362_offset','id370_parentid','id371_parentidname','id644_categoryids'), array()), new weTagDataOption('object', false, '', array('id353_type','id354_name','id356_categories','id357_catOr','id358_rows','id360_order','id361_desc','id362_offset','id364_classid','id365_condition','id366_triggerid','id832_languages','id373_searchable','id707_workspaceID','id743_cfilter','id747_docid','id799_customers','id805_id','id811_calendar','id818_predefinedSQL'), array()), new weTagDataOption('multiobject', false, '', array('id353_type','id354_name','id356_categories','id357_catOr','id358_rows','id360_order','id361_desc','id362_offset','id364_classid','id365_condition','id366_triggerid','id832_languages','id373_searchable','id743_cfilter','id811_calendar'), array()), new weTagDataOption('banner', false, 'banner', array('id353_type','id354_name','id358_rows','id360_order','id754_customer'), array()), new weTagDataOption('shopVariant', false, '', array('id353_type','id354_name','id374_defaultname','id375_documentid','id376_objectid'), array()), new weTagDataOption('customer', false, 'customer', array('id353_type','id354_name','id358_rows','id359_cols','id360_order','id361_desc','id362_offset','id365_condition','id747_docid'), array())), false, '');
$GLOBALS['weTagWizard']['attribute']['id803_MultiSelector'] = new weTagData_multiSelectorAttribute('803','MultiSelector',FILE_TABLE, '', '', false, '');
$GLOBALS['weTagWizard']['attribute']['id354_name'] = new weTagData_textAttribute('354', 'name', false, '');
$GLOBALS['weTagWizard']['attribute']['id355_doctype'] = new weTagData_sqlRowAttribute('355', 'doctype',DOC_TYPES_TABLE, false, 'DocType', '', '', '');
$GLOBALS['weTagWizard']['attribute']['id356_categories'] = new weTagData_multiSelectorAttribute('356','categories',CATEGORY_TABLE, '', 'Path', false, '');
$GLOBALS['weTagWizard']['attribute']['id357_catOr'] = new weTagData_selectAttribute('357', 'catOr', array(new weTagDataOption('true', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id358_rows'] = new weTagData_textAttribute('358', 'rows', false, '');
$GLOBALS['weTagWizard']['attribute']['id359_cols'] = new weTagData_textAttribute('359', 'cols', false, '');
$GLOBALS['weTagWizard']['attribute']['id360_order'] = new weTagData_choiceAttribute('360', 'order', array(new weTagDataOption('random()', false, ''), new weTagDataOption('we_id', false, ''), new weTagDataOption('we_filename', false, ''), new weTagDataOption('we_creationdate', false, ''), new weTagDataOption('we_moddate', false, ''), new weTagDataOption('we_published', false, '')), false,false, '');
$GLOBALS['weTagWizard']['attribute']['id361_desc'] = new weTagData_selectAttribute('361', 'desc', array(new weTagDataOption('true', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id362_offset'] = new weTagData_textAttribute('362', 'offset', false, '');
$GLOBALS['weTagWizard']['attribute']['id363_casesensitive'] = new weTagData_selectAttribute('363', 'casesensitive', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
if(defined("OBJECT_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id364_classid'] = new weTagData_selectorAttribute('364', 'classid',OBJECT_TABLE, 'object', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id365_condition'] = new weTagData_textAttribute('365', 'condition', false, '');
if(defined("FILE_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id366_triggerid'] = new weTagData_selectorAttribute('366', 'triggerid',FILE_TABLE, 'text/webedition', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id367_seeMode'] = new weTagData_selectAttribute('367', 'seeMode', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id707_workspaceID'] = new weTagData_multiSelectorAttribute('707','workspaceID',FILE_TABLE, 'folder', 'ID', false, '');
$GLOBALS['weTagWizard']['attribute']['id644_categoryids'] = new weTagData_multiSelectorAttribute('644','categoryids',CATEGORY_TABLE, '', 'ID', false, '');
if(defined("CATEGORY_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id370_parentid'] = new weTagData_selectorAttribute('370', 'parentid',CATEGORY_TABLE, '', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id371_parentidname'] = new weTagData_textAttribute('371', 'parentidname', false, '');
$GLOBALS['weTagWizard']['attribute']['id800_contenttypes'] = new weTagData_choiceAttribute('800', 'contenttypes', array(new weTagDataOption('text/webedition', false, ''), new weTagDataOption('image/*', false, ''), new weTagDataOption('text/html', false, ''), new weTagDataOption('text/plain', false, ''), new weTagDataOption('text/xml', false, ''), new weTagDataOption('text/js', false, ''), new weTagDataOption('text/css', false, ''), new weTagDataOption('application/*', false, ''), new weTagDataOption('application/x-shockwave-flash', false, ''), new weTagDataOption('video/quicktime', false, '')), false,true, '');
$GLOBALS['weTagWizard']['attribute']['id373_searchable'] = new weTagData_selectAttribute('373', 'searchable', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id374_defaultname'] = new weTagData_textAttribute('374', 'defaultname', false, '');
if(defined("FILE_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id375_documentid'] = new weTagData_selectorAttribute('375', 'documentid',FILE_TABLE, 'text/webedition', false, ''); }
if(defined("OBJECT_FILES_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id376_objectid'] = new weTagData_selectorAttribute('376', 'objectid',OBJECT_FILES_TABLE, 'objectFile', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id377_calendar'] = new weTagData_selectAttribute('377', 'calendar', array(new weTagDataOption('year', false, ''), new weTagDataOption('month', false, ''), new weTagDataOption('month_table', false, ''), new weTagDataOption('day', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id378_datefield'] = new weTagData_textAttribute('378', 'datefield', false, '');
$GLOBALS['weTagWizard']['attribute']['id379_date'] = new weTagData_textAttribute('379', 'date', false, '');
$GLOBALS['weTagWizard']['attribute']['id380_weekstart'] = new weTagData_selectAttribute('380', 'weekstart', array(new weTagDataOption('sunday', false, ''), new weTagDataOption('monday', false, ''), new weTagDataOption('tuesday', false, ''), new weTagDataOption('wednesday', false, ''), new weTagDataOption('thursday', false, ''), new weTagDataOption('friday', false, ''), new weTagDataOption('saturday', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id743_cfilter'] = new weTagData_selectAttribute('743', 'cfilter', array(new weTagDataOption('false', false, ''), new weTagDataOption('true', false, ''), new weTagDataOption('auto', false, '')), false, 'customer');
$GLOBALS['weTagWizard']['attribute']['id746_recursive'] = new weTagData_selectAttribute('746', 'recursive', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id747_docid'] = new weTagData_multiSelectorAttribute('747','docid',FILE_TABLE, 'text/webedition', 'ID', false, '');
$GLOBALS['weTagWizard']['attribute']['id754_customer'] = new weTagData_textAttribute('754', 'customer', false, 'customer');
$GLOBALS['weTagWizard']['attribute']['id799_customers'] = new weTagData_textAttribute('799', 'customers', false, 'customer');
$GLOBALS['weTagWizard']['attribute']['id805_id'] = new weTagData_textAttribute('805', 'id', false, '');
$GLOBALS['weTagWizard']['attribute']['id811_calendar'] = new weTagData_selectAttribute('811', 'calendar', array(new weTagDataOption('year', false, ''), new weTagDataOption('month', false, ''), new weTagDataOption('month_table', false, ''), new weTagDataOption('day', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id818_predefinedSQL'] = new weTagData_textAttribute('818', 'predefinedSQL', false, '');
$GLOBALS['weTagWizard']['attribute']['id827_numericalOrder'] = new weTagData_selectAttribute('827', 'numericalOrder', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$locales = array();
while ($arr = current($GLOBALS["weFrontendLanguages"])) {
	$locales[] = new weTagDataOption(key($GLOBALS["weFrontendLanguages"]), false, '');
    next($GLOBALS["weFrontendLanguages"]);
}
$GLOBALS['weTagWizard']['attribute']['id832_languages'] = new weTagData_choiceAttribute('832', 'languages',$locales, false,true, '');

?>