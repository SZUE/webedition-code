<?php
/**
 * webEdition CMS
 *
 * $Rev: 12131 $
 * $Author: mokraemer $
 * $Date: 2016-05-20 01:10:39 +0200 (Fr, 20. Mai 2016) $
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
echo we_html_tools::getHtmlTop() .
 STYLESHEET;
$we_transaction = we_base_request::_(we_base_request::TRANSACTION, 'we_cmd', we_base_request::_(we_base_request::TRANSACTION, 'we_transaction'), 2);
$remove = we_base_request::_(we_base_request::INT, 'weg');
if(!empty($remove)){
	t_e($remove);
}
?>
</head>
<body class="weEditorBody">
	<form name="we_form" method="post" action="" onsubmit="return false;">
		<?php
		echo we_class::hiddenTrans();
		$tp = new we_tag_tagParser($GLOBALS['we_doc']->getTemplateCode(true));
		$relevantTags = array(
			'normal' => array(),
			'block' => array(),
		);
		//FIXME: we need to get the names of blocks
		$context = '';
		foreach($tp->getTagsWithAttributes(true) as $tag){
			if(!empty($tag['attribs']['name'])){
				$isBlock = !empty($tag['attribs']['weblock']);
				$type = !$isBlock ? 'normal' : 'block';
				$name = $tag['attribs']['name'] . ($isBlock ? implode('', $tag['attribs']['weblock']) : '');
				$nHash = $isBlock ? $name : md5($name);

				if(isset($relevantTags[$type][$nHash])){
					$relevantTags[$type][$nHash]['types'][$tag['name']] = 1;
				} else {
					$relevantTags[$type][$nHash] = array(
						'name' => $tag['attribs']['name'],
						'types' => array($tag['name'] => 1),
					);
				}
			}
		}

		$db = $GLOBALS['DB_WE'];
		$allFields = $db->getAllq('SELECT l.Type,l.Name,IF(c.BDID,c.BDID,c.Dat) AS content FROM ' . LINK_TABLE . ' l JOIN ' . CONTENT_TABLE . ' c ON l.CID=c.ID WHERE DID IN (SELECT ID FROM ' . FILE_TABLE . ' WHERE TemplateID=' . $GLOBALS['we_doc']->ID . ') AND l.Type!="attrib" GROUP BY l.nHash');

		if(!empty($relevantTags['normal']) || !empty($relevantTags['blocks'])){
			$obsolete = $db->getAllq('SELECT l.Type,l.Name,HEX(l.nHash) AS nHash,IF(c.BDID,c.BDID, SUBSTR(c.Dat,1,150)) AS content FROM ' . LINK_TABLE . ' l JOIN ' . CONTENT_TABLE . ' c ON l.CID=c.ID WHERE DID IN (SELECT ID FROM ' . FILE_TABLE . ' WHERE TemplateID=' . $GLOBALS['we_doc']->ID . ') AND l.Type!="attrib" ' . (empty($relevantTags['normal']) ? '' : 'AND l.nHash NOT IN (x\'' . implode('\',x\'', array_keys($relevantTags['normal'])) . '\') ') . (empty($relevantTags['blocks']) ? '' : ' AND SUBSTRING_INDEX(l.Name,"__",1) NOT IN ("' . implode('","', array_keys($relevantTags['blocks'])) . '")') . ' GROUP BY l.nHash ORDER BY l.Name');
			foreach($obsolete as &$ob){
				$bl = explode('blk_', $ob['Name'], 2);
				$cnt = 0;
				$ob['real'] = $bl[0];
				$ob['block'] = isset($bl[1]) ? preg_replace('|(__\d+)+$|', '', str_replace('blk_', ' -> ', $bl[1], $cnt)) : '';
				$ob['blockcnt'] = isset($bl[1]) ? $cnt + 1 : $cnt;
				$ob['content'] = oldHtmlspecialchars($ob['content']);
			}
			usort($obsolete, function($a, $b){
				return $a['block'] == $b['block'] ?
					strcmp($a['real'], $b['real']) :
					strcmp($a['block'], $b['block']);
			});
		} else {
			$obsolete = array();
		}

		$table = new we_html_table(array('class' => 'default middlefont', 'width' => '100%'), count($obsolete) + 1, 5);
		$table->setRowAttributes(0, array('class' => 'boxHeader'));
		$table->setColContent(0, 0, '');
		$table->setColContent(0, 1, 'Block');
		$table->setColContent(0, 2, 'Name');
		$table->setColContent(0, 3, 'Typ');
		$table->setColContent(0, 4, 'Exemplarischer Inhalt');
		foreach($obsolete as $pos => $cur){
			$row = $pos + 1;
			$table->setRowAttributes($row, array('class' => 'htmlDialogBorder4Cell'));
			$table->setColContent($row, 0, '<input type="checkbox" name="weg[' . $cur['nHash'] . ']" value="' . $cur['blockcnt'] . '"/>');
			$table->setColContent($row, 1, $cur['block']);
			$table->setColContent($row, 2, $cur['real']);
			$table->setColContent($row, 3, $cur['Type']);
			$table->setColContent($row, 4, $cur['content']);
		}

		$parts = array(
			array(
				'headline' => g_l('weClass', '[unusedElementsTab]'),
				'html' => we_html_tools::htmlAlertAttentionBox(g_l('weClass', '[unusedElements][description]'), we_html_tools::TYPE_ALERT, 850, false)
			),
			array(
				'html' => $table->getHtml(),
			),
			/* array(
			  'headline' => 'Obsolete Elemente',
			  'html' => '<pre>' . print_r($obsolete, true) . '</pre>',
			  'space' => 140,
			  ),
			  array(
			  'headline' => 'debug',
			  'html' => '<pre>' . print_r($tp->getTagsWithAttributes(true), true) . '</pre>',
			  'space' => 140,
			  ),
			  array(
			  'headline' => 'Gefundene Elemente',
			  'html' => '<pre>' . print_r($relevantTags, true) . '</pre>',
			  'space' => 140,
			  ),
			  /* array(
			  'headline' => 'Elemente in DB',
			  'html' => '<pre>' . print_r($allFields, true) . '</pre>',
			  'space' => 140,
			  ), */
		);


		echo we_html_multiIconBox::getHTML('', $parts, 20, '', -1, '', '', false) .
		we_html_element::htmlHidden("we_complete_request", 1);
		?>
	</form>
</body>
</html>
