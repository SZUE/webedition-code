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
 * @package    webEdition_base
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */
class weSideBarFrames{

	var $_frameset = '';

	function __construct(){
		$this->_frameset = WEBEDITION_DIR . 'sideBarFrame.php';
	}

	function getHTML($what){
		switch($what){
			case 'header':
				print $this->getHTMLHeader();
				break;

			case 'content':
				print $this->getHTMLContent();
				break;

			case 'footer':
				print $this->getHTMLFooter();
				break;

			case 'frameset':
			default:
				print $this->getHTMLFrameset();
				break;
		}
	}

	function getHTMLFrameset(){
		return '</head>' .
			we_html_element::htmlBody(array('style' => 'background-color:grey;margin: 0px;position:fixed;top:0px;left:0px;right:0px;bottom:0px;border:0px none;')
				, we_html_element::htmlDiv(array('style' => 'position:absolute;top:0px;bottom:0px;left:0px;right:0px;')
					, we_html_element::htmlIFrame('weSidebarHeader', $this->_frameset . '?pnt=header', 'position:absolute;top:0px;height:22px;left:0px;right:0px;overflow: hidden;') .
					we_html_element::htmlIFrame('weSidebarContent', $this->_frameset . '?pnt=content', 'position:absolute;top:22px;bottom:40px;left:0px;right:0px;overflow: auto;') .
					we_html_element::htmlIFrame('weSidebarFooter', $this->_frameset . '?pnt=footer', 'position:absolute;height:40px;bottom:0px;left:0px;right:0px;overflow: hidden;')
				))
			. '</html>';
	}

	function getHTMLHeader(){
		?>
		<style type="text/css">
			body {
				margin			: 0px;
				padding			: 0px;
				border			: 0px;
				border-top		: 1px solid #000000;
				font-family		: Verdana, Arial, sans-serif;
				font-size		: 10px;
				color			: #000000;
				background-color: silver;
				background-image: url('<?php echo IMAGE_DIR; ?>/backgrounds/multitabBG.gif');
			}
			#Headline {
				padding-left	: 5px;
				line-height		: 20px;
				vertical-align	: middle;
				float			: left;
				width			: 80%;
			}
			#CloseButton {
				padding-top		: 3px;
				padding-right	: 4px;
				float			: right;
			}
		</style>
		</head>
		<body>
			<div id="Headline">
				<?php echo g_l('sidebar', '[headline]'); ?>
			</div>
			<div id="CloseButton">
				<img src="<?php echo IMAGE_DIR; ?>/multiTabs/close.gif" border="0" vspace="0" hspace="0" onclick="top.weSidebar.close();" onmouseover="this.src='<?php echo IMAGE_DIR; ?>/multiTabs/closeOver.gif'" onmouseout="this.src='<?php echo IMAGE_DIR; ?>/multiTabs/close.gif'" />
			</div>

		</body>

		</html>
		<?php
	}

	function getHTMLFooter(){
		?>
		</head>
		<body bgcolor="#f0f0f0"  background="<?php echo IMAGE_DIR; ?>edit/editfooterback.gif" marginwidth="0" marginheight="10" leftmargin="0" topmargin="10">
		</body>

		</html>
		<?php
	}

	function getHTMLContent(){
		$file = isset($_REQUEST['we_cmd'][1]) ? $_REQUEST['we_cmd'][1] : '';
		$params = isset($_REQUEST['we_cmd'][2]) ? $_REQUEST['we_cmd'][2] : '';
		define("WE_SIDEBAR", true);

		if(stripos($file, "http://") === 0 || stripos($file, "https://") === 0){
			//not implemented
			//header("Location: " . $file);
			exit();
		}

		if(strpos($file, '/') !== 0){
			$file = id_to_path($file, FILE_TABLE);
		}

		if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $file) || !is_file($_SERVER['DOCUMENT_ROOT'] . $file)){
			$file = id_to_path(intval(SIDEBAR_DEFAULT_DOCUMENT), FILE_TABLE);
			if($file == '' || substr($file, -1) == '/' || $file == 'default'){
				$file = WEBEDITION_DIR . 'sidebar/default.php';
			}
		}

		//manipulate GET/REQUEST for document
		$_GET = array();
		parse_str($params, $_GET);
		$_REQUEST = $_GET;
		ob_start();
		include($_SERVER['DOCUMENT_ROOT'] . $file);

		$SrcCode = ob_get_contents();
		ob_end_clean();

		echo we_SEEM::parseDocument($SrcCode);

		exit();
	}

}
