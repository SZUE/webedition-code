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
class we_fileupload_ui_wedoc extends we_fileupload_ui_preview{
	public function __construct($contentType = array(), $extensions = '', $doImport = true){
		parent::__construct($contentType, $extensions);
		$this->formElements = array_merge($this->formElements, array(
			'importMeta' => array('set' => true, 'multiIconBox' => false, 'space' => 0, 'rightHeadline' => true, 'noline' => true),
		));
		$this->dimensions['dragWidth'] = 300;
		$this->moreFieldsToAppend = array_merge($this->moreFieldsToAppend, array(
			array('we_transaction', 'text'),
			array('we_doc_ct', 'text'),
			array('we_doc_ext', 'text')
		));
	}

	public function getHTML($fs = '', $ft = '', $md = '', $thumbnailSmall = '', $thumbnailBig = ''){
		$isIE10 = we_base_browserDetect::isIE() && we_base_browserDetect::getIEVersion() < 11;

		$progress = new we_progressBar(0, true);
		$progress->setStudLen(170);
		$progress->setProgressTextPlace(0);
		$progress->setName('_fileupload');
		$divProgressbar = we_html_element::htmlDiv(array('id' => 'div_fileupload_progressBar', 'style' => 'margin: 13px 0 10px 0;display:none;'), $progress->getHTML());
		$divFileInfo = we_html_element::htmlDiv(array(), $fs . '<br />' . $ft . '<br />' . $md);
		$divButtons = we_html_element::htmlDiv(array('id' => 'div_fileupload_buttons', 'style' => 'width:204px'),
				$this->getDivBtnInputReset($isIE10 ? 84 : 170) .
				$divProgressbar .
				$this->getDivBtnUploadCancel($isIE10 ? 84 : 170)
		);

		return $this->getJs() . $this->getCss() . $this->getHiddens() . '
			<table id="table_form_upload" class="default" width="500">
				<tr style="vertical-align:top;">
					<td class="defaultfont" width="200px">' .
						$divFileInfo . $divButtons . '
					</td>
					<td width="300px">' .
						we_html_element::htmlDiv(array('id' => 'div_fileupload_right', 'style'=>"position:relative;"),
							$this->getHtmlDropZone('preview', $thumbnailSmall) .
							($this->contentType === we_base_ContentTypes::IMAGE ? '<br />' . $this->getFormImportMeta() : '')
						) . '
					</td>
				</tr>
				<tr><td colspan="2" class="defaultfont" style="padding-top:20px;">' . $this->getHtmlAlertBoxes() . '</td></tr>
				<tr>
					<td colspan="2" class="defaultfont" style="padding-top:20px;">' . 
						we_html_tools::htmlAlertAttentionBox(g_l('weClass', (isset($GLOBALS['we_doc']) && $GLOBALS['we_doc']->getFilesize() ? "[upload_will_replace]" : "[upload_single_files]")), we_html_tools::TYPE_ALERT, 508) . '
					</td>
				</tr>
			</table>';
	}
}

