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

/**
 * LiveUpdateTemplates is a helper function taking care of the view of the
 * update process. The functions here are only called from templates!
 *
 */
class liveUpdateTemplates{

	/**
	 * returns standard html container for output
	 *
	 * @param string $headline
	 * @param string $content
	 * @param integer $width
	 * @param integer $height
	 * @return string
	 */
	static function getContainer($headline, $content, $buttons = '', $width = 550, $height = 400){
		$buttonDiv = '';

		$headlineHeight = 30;
		$contentHeight = $height - $headlineHeight;

		$gapHeight = 15;
		$buttonHeight = 20;

		if($buttons){
			$buttonDiv = '<div style="height: ' . $gapHeight . 'px; background: none"></div>
			<div id="buttonDiv" style="height:' . $buttonHeight . 'px;">' .
				$buttons .
				'</div>';

			$contentHeight -= $buttonHeight - $gapHeight;
		}

		return '<div id="contentDiv" class="defaultfont" style="width:' . $width . 'px; height: ' . $height . 'px;">
			<div id="contentHeadlineDiv" style="height: ' . ($headlineHeight) . 'px;">
			<b>' . $headline . '</b><hr />
			</div>
			<div id="contentTextDiv" class="defaultfont" style="height: ' . ($contentHeight) . 'px;">' .
			$content .
			'</div>' .
			$buttonDiv .
			'</div>';
	}

	/**
	 * returns header of template
	 *
	 * @return string
	 */
	static function getHtmlHead(){
		return we_html_tools::htmlMetaCtCharset($GLOBALS['WE_BACKENDCHARSET']) . STYLESHEET .
			LIVEUPDATE_CSS;
	}

	/**
	 * Returns a html page as response
	 *
	 * @param string $headline
	 * @param string $content
	 * @param string $header
	 * @param string $buttons
	 * @param integer $contentWidth
	 * @param integer $contentHeight
	 * @return string
	 */
	static function getHtml($headline, $content, $header = '', $buttons = '', $contentWidth = 550, $contentHeight = 400){
		return we_html_tools::getHtmlTop('', '', '', liveUpdateTemplates::getHtmlHead() .
				$header, '<body>' .
				self::getContainer($headline, $content, $buttons, $contentWidth, $contentHeight) .
				'</body>');
	}

}
