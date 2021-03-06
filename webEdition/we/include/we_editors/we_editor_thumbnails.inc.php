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
echo we_html_tools::getHtmlTop() . STYLESHEET;
require_once(WE_INCLUDES_PATH . 'we_editors/we_editor_script.inc.php');
?>

</head>
<body class="weEditorBody" onload="doScrollTo();">
	<form name="we_form"><?php echo we_class::hiddenTrans(); ?>
		<?php
		if(we_base_imageEdit::gd_version() > 0){
			$doc = $we_doc->getDocument();
			$imgType = we_base_imageEdit::detect_image_type('', $doc);

			if(!$doc){
				$parts = array(
					array("headline" => "",
						"html" => we_html_tools::htmlAlertAttentionBox(g_l('thumbnails', '[no_image_uploaded]'), we_html_tools::TYPE_INFO, 700),
				));
			} else if(we_base_imageEdit::is_imagetype_read_supported($imgType)){
				$parts = array();

				// look if the fields origwidth & origheight exixts. If not get and set the values
				if((!$we_doc->issetElement('origwidth')) || (!$we_doc->issetElement('origheight'))){
					$arr = $we_doc->getOrigSize();
					$we_doc->setElement('origwidth', $arr[0], 'attrib');
					$we_doc->setElement('origheight', $arr[1], 'attrib');
					unset($arr);
				}

				$thumbs = $we_doc->getThumbs();
				foreach($thumbs as $thumbid){

					$thumbObj = new we_thumbnail();
					$thumbObj->initByThumbID($thumbid, $we_doc->ID, $we_doc->Filename, $we_doc->Path, $we_doc->Extension, $we_doc->getElement('origwidth'), $we_doc->getElement('origheight'), $doc);

					srand((double) microtime() * 1000000);
					$randval = rand();


					$useOrig = $thumbObj->isOriginal();

					if((!$useOrig) && $we_doc->ID && ($we_doc->DocChanged == false) && file_exists($thumbObj->getOutputPath(true))){
						$src = $thumbObj->getOutputPath(false, true);
					} else {
						$src = WEBEDITION_DIR . 'we_cmd.php?we_cmd[0]=show_binaryDoc&amp;we_cmd[1]=' .
							$we_doc->ContentType . '&amp;we_cmd[2]=' .
							$we_transaction . '&amp;we_cmd[3]=' . ($useOrig ? "" : $thumbid) . '&amp;rand=' . $randval;
					}


					$delbut = we_html_button::create_button(we_html_button::TRASH, "javascript:_EditorFrame.setEditorIsHot(true);we_cmd('del_thumb','" . $thumbid . "');", true, 30);

					$thumbnail = '<table class="default" style="width:570px;"><tr><td style="width:538px;"><img src="' . $src . '" style="width:' . $thumbObj->getOutputWidth() . 'px;height:' . $thumbObj->getOutputHeight() . 'px;" /></td><td>' . $delbut . '</td></tr></table>';

					$parts[] = array(
						'headline' => $thumbObj->getThumbName(),
						'space' => we_html_multiIconBox::SPACE_BIG,
						'noline' => true
					);
					$parts[] = array(
						'html' => $thumbnail,
					);
				}
				$parts[] = array("headline" => "",
					"html" => we_html_tools::htmlAlertAttentionBox(g_l('thumbnails', '[add_descriptiontext]'), we_html_tools::TYPE_INFO, 700) . '<br/><br/>' . we_html_button::create_button('fa:btn_add_thumbnail,fa-plus,fa-lg fa-picture-o', "javascript:_EditorFrame.setEditorIsHot(true);we_cmd('add_thumbnail','" . $we_transaction . "');"),
				);
			} else {
				$parts = array(array("headline" => "",
						"html" => we_html_tools::htmlAlertAttentionBox(g_l('thumbnails', '[format_not_supported]'), we_html_tools::TYPE_INFO, 700),
				));
			}
		} else {
			$parts = array(
				array("headline" => "",
					"html" => we_html_tools::htmlAlertAttentionBox(g_l('thumbnails', '[add_description_nogdlib]'), we_html_tools::TYPE_INFO, 700),
			));
		}
		echo we_html_multiIconBox::getJS() . we_html_multiIconBox::getHTML('', $parts, 20) .
		we_html_element::htmlHidden("we_complete_request", 1);
		?>
	</form>
</body>
</html>