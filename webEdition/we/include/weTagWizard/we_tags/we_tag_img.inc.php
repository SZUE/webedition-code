<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_textAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_choiceAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_selectorAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_selectAttribute.class.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/weTagWizard/classes/weTagData_sqlRowAttribute.class.php');

$GLOBALS['weTagWizard']['weTagData']['needsEndtag'] = false;

$GLOBALS['weTagWizard']['attribute']['id299_name'] = new weTagData_textAttribute('299', 'name', true, '');
$GLOBALS['weTagWizard']['attribute']['id728_only'] = new weTagData_choiceAttribute('728', 'only', array(new weTagDataOption('width', false, ''), new weTagDataOption('height', false, ''), new weTagDataOption('alt', false, ''), new weTagDataOption('src', false, '')), false,true, '');
if(defined("FILE_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id300_id'] = new weTagData_selectorAttribute('300', 'id',FILE_TABLE, 'image/*', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id301_width'] = new weTagData_textAttribute('301', 'width', false, '');
$GLOBALS['weTagWizard']['attribute']['id302_height'] = new weTagData_textAttribute('302', 'height', false, '');
$GLOBALS['weTagWizard']['attribute']['id303_border'] = new weTagData_textAttribute('303', 'border', false, '');
$GLOBALS['weTagWizard']['attribute']['id304_hspace'] = new weTagData_textAttribute('304', 'hspace', false, '');
$GLOBALS['weTagWizard']['attribute']['id305_vspace'] = new weTagData_textAttribute('305', 'vspace', false, '');
$GLOBALS['weTagWizard']['attribute']['id306_alt'] = new weTagData_textAttribute('306', 'alt', false, '');
$GLOBALS['weTagWizard']['attribute']['id307_title'] = new weTagData_textAttribute('307', 'title', false, '');
if(defined("FILE_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id714_startid'] = new weTagData_selectorAttribute('714', 'startid',FILE_TABLE, 'folder', false, ''); }
if(defined("FILE_TABLE")) { $GLOBALS['weTagWizard']['attribute']['id715_parentid'] = new weTagData_selectorAttribute('715', 'parentid',FILE_TABLE, 'folder', false, ''); }
$GLOBALS['weTagWizard']['attribute']['id308_align'] = new weTagData_selectAttribute('308', 'align', array(new weTagDataOption('left', false, ''), new weTagDataOption('right', false, ''), new weTagDataOption('top', false, ''), new weTagDataOption('bottom', false, ''), new weTagDataOption('absmiddle', false, ''), new weTagDataOption('middle', false, ''), new weTagDataOption('texttop', false, ''), new weTagDataOption('baseline', false, ''), new weTagDataOption('absbottom', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id309_thumbnail'] = new weTagData_sqlRowAttribute('309', 'thumbnail',THUMBNAILS_TABLE, false, 'Name', '', '', '');
$GLOBALS['weTagWizard']['attribute']['id311_showcontrol'] = new weTagData_selectAttribute('311', 'showcontrol', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id312_showimage'] = new weTagData_selectAttribute('312', 'showimage', array(new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id313_showinputs'] = new weTagData_selectAttribute('313', 'showinputs', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id890_showthumbcontrol'] = new weTagData_selectAttribute('890', 'showthumbcontrol', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id628_xml'] = new weTagData_selectAttribute('628', 'xml', array(new weTagDataOption('true', false, ''), new weTagDataOption('false', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id847_sizingrel'] = new weTagData_textAttribute('847', 'sizingrel', false, '');
$GLOBALS['weTagWizard']['attribute']['id860_sizingstyle'] = new weTagData_selectAttribute('860', 'sizingstyle', array(new weTagDataOption('none', false, ''), new weTagDataOption('em', false, ''), new weTagDataOption('ex', false, ''), new weTagDataOption('%', false, ''), new weTagDataOption('px', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id861_sizingbase'] = new weTagData_textAttribute('861', 'sizingbase', false, '');
$GLOBALS['weTagWizard']['attribute']['id478_to'] = new weTagData_selectAttribute('478', 'to', array(new weTagDataOption('screen', false, ''),new weTagDataOption('request', false, ''), new weTagDataOption('post', false, ''), new weTagDataOption('get', false, ''), new weTagDataOption('global', false, ''), new weTagDataOption('session', false, ''), new weTagDataOption('top', false, ''), new weTagDataOption('self', false, ''), new weTagDataOption('sessionfield', false, '')), false, '');
$GLOBALS['weTagWizard']['attribute']['id479_nameto'] = new weTagData_textAttribute('479', 'nameto', false, '');
$GLOBALS['weTagWizard']['attribute']['id734_cachelifetime'] = new weTagData_textAttribute('734', 'cachelifetime', false, '');
