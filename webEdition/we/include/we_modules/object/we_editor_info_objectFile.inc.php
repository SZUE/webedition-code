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


include_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we_inc_min.inc.php');

if(defined("WORKFLOW_TABLE")){
	include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_modules/workflow/weWorkflowUtility.php");
}

we_html_tools::protect();
we_html_tools::htmlTop();


$parts = array();


$_html = '<div class="weMultiIconBoxHeadline" style="margin-bottom:5px;">ID</div>' .
	'<div style="margin-bottom:10px;">' . ($GLOBALS['we_doc']->ID ?  $GLOBALS['we_doc']->ID : "-") . '</div>';

$_html .= '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('weEditorInfo',"[content_type]").'</div>' .
	'<div style="margin-bottom:10px;">' . g_l('weEditorInfo','['.$GLOBALS['we_doc']->ContentType.']') .'</div>';


array_push($parts, array(	"headline"=>"",
							"html"=>$_html,
							"space"=>140,
							"icon" => "meta.gif"
						)
			);



$_html = '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('weEditorInfo',"[creation_date]").'</div>' .
	'<div style="margin-bottom:10px;">' . date(g_l('weEditorInfo',"[date_format]"), $GLOBALS['we_doc']->CreationDate) .'</div>';




if($GLOBALS['we_doc']->CreatorID){
	$GLOBALS['DB_WE']->query("SELECT First,Second,username FROM " . USER_TABLE . " WHERE ID=".$GLOBALS['we_doc']->CreatorID);
	if ($GLOBALS['DB_WE']->next_record()) {
		$_html .= '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('modules_users',"[created_by]").'</div>' .
			'<div style="margin-bottom:10px;">' . $GLOBALS['DB_WE']->f("First").' '.$GLOBALS['DB_WE']->f("Second").' ('.$GLOBALS['DB_WE']->f("username").')</div>';
	}
}

$_html .= '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('weEditorInfo',"[changed_date]").'</div>' .
	'<div style="margin-bottom:10px;">' . date(g_l('weEditorInfo',"[date_format]"), $GLOBALS['we_doc']->ModDate) .'</div>';


if($GLOBALS['we_doc']->ModifierID){
	$GLOBALS['DB_WE']->query("SELECT First,Second,username FROM " . USER_TABLE . " WHERE ID=".$GLOBALS['we_doc']->ModifierID);
	if ($GLOBALS['DB_WE']->next_record()) {
		$_html .= '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('modules_users',"[changed_by]").'</div>' .
			'<div style="margin-bottom:10px;">' . $GLOBALS['DB_WE']->f("First").' '.$GLOBALS['DB_WE']->f("Second").' ('.$GLOBALS['DB_WE']->f("username").')</div>';

	}
}

$_html .= '<div class="weMultiIconBoxHeadline" style="padding-bottom:5px;">'.g_l('weEditorInfo',"[lastLive]").'</div>' .
		'<div style="margin-bottom:10px;">' . ($we_doc->Published ? date(g_l('weEditorInfo',"[date_format]"),$we_doc->Published) : "-") .'</div>';


array_push($parts, array(	"headline"=>"",
						"html"=>$_html,
						"space"=>140,
						"icon" => "cal.gif"
					)
		);


if(defined("WORKFLOW_TABLE")){
	if(weWorkflowUtility::inWorkflow($GLOBALS['we_doc']->ID,$GLOBALS['we_doc']->Table)){
		$anzeige = weWorkflowUtility::getDocumentStatusInfo($GLOBALS['we_doc']->ID,$GLOBALS['we_doc']->Table);
	}else{
		$anzeige = weWorkflowUtility::getLogButton($GLOBALS['we_doc']->ID,$GLOBALS['we_doc']->Table);
	}
	array_push($parts,array(	"headline"=>g_l('modules_workflow','[workflow]'),
								"html"=>$anzeige,
								"space"=>140,
								"forceRightHeadline"=>1,
								"icon"=>"workflow.gif"
							)
			);
}

 print STYLESHEET;
 echo we_htmlElement::jsScript(JS_DIR.'windows.js');
include_once($_SERVER['DOCUMENT_ROOT']."/webEdition/we/include/we_editors/we_editor_script.inc.php"); ?>
	</head>
	<body class="weEditorBody" onunload="doUnload()">
<?php
print we_multiIconBox::getJS();
print we_multiIconBox::getHTML("","100%",$parts,30,"",-1,"","",false);
?>
	</body>
</html>