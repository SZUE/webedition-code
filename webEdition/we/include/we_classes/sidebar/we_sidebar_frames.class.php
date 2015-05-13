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
class we_sidebar_frames{
	var $_frameset = '';

	public function __construct(){
		$this->_frameset = WEBEDITION_DIR . 'sideBarFrame.php';
	}

	function getHTML($what){
		switch($what){

			case 'content':
				echo $this->getHTMLContent();
				break;

			default:
				echo $this->getHTMLFrameset();
				break;
		}
	}

	function getHTMLFrameset(){
		echo we_html_element::cssLink(CSS_DIR . 'sidebar.css');
		?>
		</head>
		<body>
			<div id="weSidebarHeader" name="weSidebarHeader">
				<div id="Headline">
					<?php echo g_l('sidebar', '[headline]'); ?>
					<div id="CloseButton">
						<span class="fa-stack close" id="###closeId###" onclick="top.weSidebar.close();">
							<i class="fa fa-circle-o fa-stack-2x"></i>
							<i class="fa fa-close fa-stack-1x "></i>
						</span>
					</div>
				</div>
			</div>
			<div id="weSidebarContentDiv">
				<iframe id="weSidebarContent" src="<?php echo $this->_frameset; ?>?pnt=content" name="weSidebarContent"></iframe>
			</div>
			<div name="weSidebarFooter" id="weSidebarFooter">
			</div>
		</body>

		</html>
		<?php
	}

	function getHTMLContent(){
		$file = we_base_request::_(we_base_request::URL, 'we_cmd', '', 1);
		$params = we_base_request::_(we_base_request::STRING, 'we_cmd', '', 2);
		define('WE_SIDEBAR', true);

		if(stripos($file, "http://") === 0 || stripos($file, "https://") === 0){
			//not implemented
			//header("Location: " . $file);
			exit();
		}

		if(strpos($file, '/') !== 0){
			$file = id_to_path($file, FILE_TABLE, $GLOBALS['DB_WE'], false, false, false, true);
		}

		if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $file) || !is_file($_SERVER['DOCUMENT_ROOT'] . $file)){
			$file = id_to_path(intval(SIDEBAR_DEFAULT_DOCUMENT), FILE_TABLE, $GLOBALS['DB_WE'], false, false, false, true);
			if(!$file || substr($file, -1) === '/' || $file === 'default'){
				$file = WEBEDITION_DIR . 'sidebar/default.php';
			}
		}

		//manipulate GET/REQUEST for document
		$_GET = array();
		parse_str($params, $_GET);
		$_REQUEST = $_GET;
		ob_start();
		include($_SERVER['DOCUMENT_ROOT'] . $file);

		$SrcCode = ob_get_clean();

		echo we_SEEM::parseDocument($SrcCode);

		exit();
	}

}
