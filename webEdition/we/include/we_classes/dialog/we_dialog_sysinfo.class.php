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
 * @package none
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
abstract class we_dialog_sysinfo{

	private static function getInfoTable($infoArr, $name){

		$table = new we_html_table(['style' => "width: 500px;"], 1, 2);
		$i = 0;

		foreach($infoArr as $k => $v){

			$style = ($i % 2 ? '' : "background: #D4DBFA;");

			$table->addRow(1);
			$table->setRow($i, ['class' => 'defaultfont', 'style' => $style . "height:20px;"]);
			$table->setCol($i, 0, ['style' => "width: 200px; height: 20px; padding-left: 10px;", 'class' => 'bold'], $k);
			$table->setCol($i, 1, ['style' => "width: 250px; height: 20px; padding-left: 10px;"], self::parseValue($k, $v));
			$i++;

			// highlight some values:
			if($name === 'PHP'){
				if($i == 3 && self::ini_get_bool('register_globals')){
					$table->setColAttributes(2, 1, ['style' => "border:1px solid red;"]);
				}
				if($i == 6 && self::ini_get_bool('short_open_tag')){
					$table->setColAttributes(5, 1, ['style' => "border:1px solid red;"]);
				}
				if($i == 9 && self::ini_get_bool('safe_mode')){
					$table->setColAttributes(8, 1, ['style' => "border:1px solid grey;"]);
				}
			}
		}
		return $table->getHtml();
	}

	private static function ini_get_bool($val){
		if($val == 1){
			return true;
		}
		if($val == 0){
			return false;
		}
		switch(strtolower(ini_get($val))){
			case '1':
			case 'on':
			case 'yes':
			case 'true':
				return true;
			default:
				return false;
		}
		return false;
	}

	private static function ini_get_message($val){
		$bool = ini_get($val);
		if($val === 1){
			return 'on';
		}
		if($val === 0){
			return 'off';
		}
		switch(strtolower($bool)){
			case '1':
			case 'on':
			case 'yes':
			case 'true':
				return 'on';
			case '0':
			case 'off':
			case 'no':
			case 'false':
				return 'off';
			case '':
				return g_l('sysinfo', '[not_set]');
			default:
				return $bool;
		}
		return 'off';
	}

	private static function parseValue($name, $value){
		static $types = [];

		$types = $types ?: [
			'upload_max_filesize' => 'bytes',
			'memory_limit' => 'bytes',
			'max_allowed_packet' => 'bytes',
			g_l('sysinfo', '[we_max_upload_size]') => 'bytes'
		];

		if(in_array($name, array_keys($types))){
			if($types[$name] === 'bytes' && $value){

				$value = we_convertIniSizes($value);
				return we_base_file::getHumanFileSize($value, we_base_file::SZ_MB) . ' (' . $value . ' Bytes)';
			}
		}

		return $value;
	}

	private static function getConnectionTypes(){
		$connectionTypes = [];
		if(ini_get('allow_url_fopen') == 1){
			$connectionTypes[] = "fopen";
			$connectionTypeUsed = "fopen";
		}
		if(is_callable('curl_exec')){
			$connectionTypes[] = "curl";
			if(count($connectionTypes) == 1){
				$connectionTypeUsed = "curl";
			}
		}
		foreach($connectionTypes as &$con){
			if($con == $connectionTypeUsed){
				$con = '<u>' . $con . '</u>';
			}
		}
		return $connectionTypes;
	}

	private static function getWarning($message, $value){
		return '<div class="sysinfoMsg" title="' . $message . '">' . $value . '<span class="warn fa-stack fa-lg">
  <i class="fa fa-exclamation-triangle fa-stack-2x" ></i>
  <i style="color:black;" class="fa fa-exclamation fa-stack-1x"></i>
</span></div>';
	}

	private static function getInfo($message, $value){
		return '<div class="sysinfoMsg" title="' . $message . '">' . $value . '<span class="info fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x" ></i>
  <i class="fa fa-info fa-stack-1x fa-inverse"></i>
</span></div>';
	}

	private static function getOK($message = '', $value = ''){
		return '<div class="sysinfoMsg" title="' . $message . '">' . $value . '<span class="ok fa fa-lg fa-check fa-ok"></span></div>';
	}

	public static function getDialog($what){
		if($what == 'phpinfo'){
			phpinfo();
			return;
		}

		$install_dir = '<abbr title="' . $_SERVER['DOCUMENT_ROOT'] . '">' . we_base_util::shortenPath(WEBEDITION_PATH, 35) . '</abbr>';

		$weVersion = WE_VERSION .
			(defined('WE_SVNREV') && WE_SVNREV != '0000' ? ' (SVN-Revision: ' . WE_SVNREV . (defined('WE_VERSION_HOTFIX_NR') && WE_VERSION_HOTFIX_NR ? ' , h' . WE_VERSION_HOTFIX_NR . ',' : '') . ((defined('WE_VERSION_BRANCH') && WE_VERSION_BRANCH != 'trunk') ? ' ' . WE_VERSION_BRANCH : '') . ')' : '') .
			(defined('WE_VERSION_SUPP') && WE_VERSION_SUPP ? ' ' . g_l('global', '[' . WE_VERSION_SUPP . ']') : '') .
			(defined('WE_VERSION_SUPP_VERSION') && WE_VERSION_SUPP_VERSION ? WE_VERSION_SUPP_VERSION : '');

		$gdVersion = (defined('GD_VERSION') ? GD_VERSION : '');

		$phpExtensionsDetectable = true;

		$phpextensions = get_loaded_extensions();
		foreach($phpextensions as &$extens){
			$extens = strtolower($extens);
		}
		$phpextensionsMissing = [];
		$phpextensionsMin = ['ctype', 'date', 'dom', 'filter', 'iconv', 'libxml', 'mysql', 'pcre', 'Reflection', 'session', 'SimpleXML', 'SPL', 'standard', 'tokenizer',
			'xml', 'zlib'];

		if(count($phpextensions) > 3){
			foreach($phpextensionsMin as $exten){
				if(!in_array(strtolower($exten), $phpextensions, true)){
					$phpextensionsMissing[] = $exten;
				}
			}
		} else {
			$phpExtensionsDetectable = false;
		}
		if(extension_loaded('suhosin')){
			if(self::ini_get_bool('suhosin.simulation')){
				$SuhosinText = self::getOK('', g_l('sysinfo', '[suhosin simulation]'));
			} else {
				if(self::ini_get_bool('suhosin.cookie.encrypt')){
					$SuhosinText = self::getWarning(g_l('sysinfo', '[suhosin warning]'), 'on' . ' (suhosin.cookie.encrypt=on)');
				} else {
					$SuhosinText = self::getWarning(g_l('sysinfo', '[suhosin warning]'), 'on');
				}
			}
		} else {
			$SuhosinText = self::getOK('', self::ini_get_message('suhosin'));
		}

		$lockTables = $GLOBALS['DB_WE']->hasLock();
//$allowTempTables = we_search_search::checkRightTempTable();
		$info = [
			'webEdition' => [g_l('sysinfo', '[we_version]') => $weVersion,
				g_l('sysinfo', '[server_name]') => $_SERVER['SERVER_NAME'],
				g_l('sysinfo', '[port]') => $_SERVER['SERVER_PORT'] ?: 80,
				g_l('sysinfo', '[protocol]') => getServerProtocol(),
				g_l('sysinfo', '[installation_folder]') => $install_dir,
				g_l('sysinfo', '[we_max_upload_size]') => getUploadMaxFilesize(),
				g_l('import', '[pfx]') => TBL_PREFIX
			],
			'<a href="javascript:showPhpInfo();">PHP</a>' => [g_l('sysinfo', '[php_version]') => PHP_VERSION,
				'register_globals' => (self::ini_get_bool('register_globals')) ? self::getWarning(g_l('sysinfo', '[register_globals warning]'), ini_get('register_globals')) : self::getOK('', self::ini_get_message('register_globals')),
				'max_execution_time' => ini_get('max_execution_time'),
				'memory_limit' => we_convertIniSizes(ini_get('memory_limit')),
				'short_open_tag' => (self::ini_get_bool('short_open_tag')) ? self::getWarning(g_l('sysinfo', '[short_open_tag warning]'), ini_get('short_open_tag')) : self::ini_get_message('short_open_tag'),
				'allow_url_fopen' => self::ini_get_message('allow_url_fopen'),
				'open_basedir' => self::ini_get_message('open_basedir'),
				'safe_mode' => (self::ini_get_bool('safe_mode')) ? self::getInfo(g_l('sysinfo', '[safe_mode warning]'), ini_get('safe_mode')) : self::getOK('', self::ini_get_message('safe_mode')),
				'safe_mode_exec_dir' => self::ini_get_message('safe_mode_exec_dir'),
				'safe_mode_gid' => self::ini_get_message('safe_mode_gid'),
				'safe_mode_include_dir' => self::ini_get_message('safe_mode_include_dir'),
				'upload_max_filesize' => we_convertIniSizes(ini_get('upload_max_filesize')),
				'post_max_size' => we_convertIniSizes(ini_get('post_max_size')),
				'max_input_vars' => ini_get('max_input_vars') < 2000 ? self::getWarning('<2000', ini_get('max_input_vars')) : self::getOK('>=2000', self::ini_get_message('max_input_vars')),
				'session.auto_start' => (self::ini_get_bool('session.auto_start')) ? self::getWarning(g_l('sysinfo', '[session.auto_start warning]'), ini_get('session.auto_start')) : self::getOK('', self::ini_get_message('session.auto_start')),
				'Suhosin' => $SuhosinText,
				'display_errors' => (self::ini_get_bool('display_errors') ? self::getWarning(g_l('sysinfo', '[display_errors warning]'), 'on') : self::getOK('', self::ini_get_message('off'))),
				'finfo' => (!class_exists('finfo') ? self::getWarning(g_l('sysinfo', '[class_missing]'), '') : self::getOK('', '')),
				g_l('sysinfo', '[umlautdomains]') => (!function_exists('idn_to_ascii') ? self::getWarning(g_l('sysinfo', '[umlautdomains_warning]'), '') : self::getOK('', '')),
			],
			'MySql' => [g_l('sysinfo', '[mysql_version]') => (version_compare("5.0.0", we_database_base::getMysqlVer(false)) > 1) ? self::getWarning(sprintf(g_l('sysinfo', '[dbversion warning]'), we_database_base::getMysqlVer(false)), we_database_base::getMysqlVer(false)) : self::getOK('', we_database_base::getMysqlVer(false)),
				'max_allowed_packet' => $GLOBALS['DB_WE']->getMaxAllowedPacket(),
				'lock tables' => ($lockTables ? self::getOK('', g_l('sysinfo', '[available]')) : self::getWarning('', '-')),
				//'create temporary tables' => ($allowTempTables ? self::getOK('', g_l('sysinfo', '[available]')) : self::getWarning('', '-')),
				'Info' => $GLOBALS['DB_WE']->getInfo(),
			],
			'System' => [g_l('sysinfo', '[connection_types]') => implode(', ', self::getConnectionTypes()),
				g_l('sysinfo', '[mbstring]') => (is_callable('mb_get_info') ? g_l('sysinfo', '[available]') : '-'),
				g_l('sysinfo', '[gdlib]') => ($gdVersion ? g_l('sysinfo', '[version]') . ' ' . $gdVersion : '-'),
				g_l('sysinfo', '[exif]') => (is_callable('exif_imagetype') ? g_l('sysinfo', '[available]') : self::getWarning(g_l('sysinfo', '[exif warning]'), '-')),
				g_l('sysinfo', '[pcre]') => ((defined('PCRE_VERSION')) ? ( (substr(PCRE_VERSION, 0, 1) < 7) ? self::getWarning(g_l('sysinfo', '[pcre warning]'), g_l('sysinfo', '[version]') . ' ' . PCRE_VERSION) : g_l('sysinfo', '[version]') . ' ' . PCRE_VERSION ) : self::getWarning(g_l('sysinfo', '[available]'), g_l('sysinfo', '[pcre_unkown]'))),
				g_l('sysinfo', '[phpext]') => (!empty($phpextensionsMissing) ? self::getWarning(g_l('sysinfo', '[phpext warning2]'), g_l('sysinfo', '[phpext warning]') . implode(', ', $phpextensionsMissing)) : g_l('sysinfo', ($phpExtensionsDetectable ? '[available]' : '[detectable warning]')) ),
				g_l('sysinfo', '[crypt]') => (function_exists('mcrypt_module_open') && (mcrypt_module_open(MCRYPT_TWOFISH, '', MCRYPT_MODE_OFB, '')) ? self::getOK() : self::getWarning(g_l('sysinfo', '[crypt_warning]'), '-'))
			],
			'Deprecated' => ['we:saveRegisteredUser register=' => (defined('CUSTOMER_TABLE') && f('SELECT pref_value FROM ' . SETTINGS_TABLE . ' WHERE tool="webadmin" AND pref_name="default_saveRegisteredUser_register"') === 'true' ? self::getWarning('Deprecated', 'true') : getOk('', defined('CUSTOMER_TABLE') ? 'false' : '?')),
			],
		];

		$buttons = we_html_button::formatButtons(we_html_button::create_button(we_html_button::CLOSE, "javascript:self.close()"));

		$parts = [];
		foreach($info as $k => $v){
			$parts[] = ['headline' => $k,
				'html' => self::getInfoTable($v, strip_tags($k)),
				'space' => we_html_multiIconBox::SPACE_MED2
			];
		}

		$parts[] = ['headline' => '',
			'html' => '<a href="javascript:showPhpInfo();">' . g_l('sysinfo', '[more_info]') . '&hellip;</a>',
			'space' => we_html_multiIconBox::SPACE_SMALL
		];

		echo we_html_tools::getHtmlTop(g_l('sysinfo', '[sysinfo]'), '', '', we_html_element::jsScript(JS_DIR . 'sysinfo.js'), we_html_element::htmlBody([
				'class' => "weDialogBody", 'onload' => "self.focus();"
				], '<div id="info" style="display: block;">' .
				we_html_element::jsScript(JS_DIR . 'multiIconBox.js') .
				we_html_multiIconBox::getHTML('', $parts, 30, $buttons) . '
	</div>
	<div id="more" style="display:none;">' .
				we_html_multiIconBox::getHTML('', [
					['headline' => '',
						'html' => '<iframe id="phpinfo" style="width:1280px;height:530px;">' . g_l('sysinfo', '[more_info]') . ' &hellip;</iframe>',
					],
					['headline' => '',
						'html' => '<a href="javascript:showInfoTable();">' . g_l('sysinfo', '[back]') . '</a>',
						'space' => we_html_multiIconBox::SPACE_SMALL
					],
					], 30, $buttons) . '
	</div>'
		));
	}

}