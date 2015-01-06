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
class we_export_treeMain extends weTree{

	function __construct($frameset = "", $topFrame = "", $treeFrame = "", $cmdFrame = ""){

		parent::__construct($frameset, $topFrame, $treeFrame, $cmdFrame);
	}

	function getJSOpenClose(){
		return '
function openClose(id){
	var sort="";
	if(id==""){
		return;
	}
	var eintragsIndex = indexOfEntry(id);
	var openstatus=(treeData[eintragsIndex].open==0?1:0);

	treeData[eintragsIndex].open=openstatus;

	if(openstatus && treeData[eintragsIndex].loaded!=1){
		if(sort!=""){
			' . $this->cmdFrame . '.location=treeData.frameset+"?pnt=cmd&cmd=mainload&pid="+id+"&sort="+sort;
		}else{
			' . $this->cmdFrame . '.location=treeData.frameset+"?pnt=cmd&cmd=mainload&pid="+id;
		}
	}else{
		drawTree();
	}
	if(openstatus==1){
		treeData[eintragsIndex].loaded=1;
	}
}
 			';
	}

	function getJSUpdateItem(){
		return '
function updateEntry(id,text,pid){
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].id==id) {
			treeData[ai].text=text;
			treeData[ai].parentid=pid;
		}
		ai++;
	}
	drawTree();
}
			';
	}

	function getJSTreeFunctions(){
		$out = weTree::getJSTreeFunctions();

		$out.='
function doClick(id,typ){
	var cmd = "";
	if(top.content.hot == "1") {
		if(confirm("' . g_l('export', '[save_changed_export]') . '")) {
			cmd = "save_export";
			top.content.we_cmd("save_export");
		} else {
			top.content.usetHot();
			cmd = "export_edit";
			var node=' . $this->topFrame . '.get(id);
			' . $this->topFrame . '.editor.edbody.location=treeData.frameset+"?pnt=edbody&cmd="+cmd+"&cmdid="+node.id+"&tabnr="+' . $this->topFrame . '.activ_tab;
		}
	} else {
		cmd = "export_edit";
		var node=' . $this->topFrame . '.get(id);
		' . $this->topFrame . '.editor.edbody.location=treeData.frameset+"?pnt=edbody&cmd="+cmd+"&cmdid="+node.id+"&tabnr="+' . $this->topFrame . '.activ_tab;
	}
}
' . $this->topFrame . '.loaded=1;
			';
		return $out;
	}

	function getJSStartTree(){

		return 'function startTree(){
	' . $this->cmdFrame . '.location=treeData.frameset+"?pnt=cmd&cmd=mainload&pid=0";
	drawTree();
			}';
	}

	function getJSIncludeFunctions(){

		$out = weTree::getJSIncludeFunctions();
		$out.="\n" . $this->getJSStartTree() . "\n";

		return $out;
	}

	function getJSMakeNewEntry(){
		return '
function makeNewEntry(icon,id,pid,txt,open,ct,tab){
	if(treeData[indexOfEntry(pid)]&&treeData[indexOfEntry(pid)].loaded){
		ct=(ct=="folder"? "group":"item");
		var attribs={
		"id":id,
		"icon":icon,
		"text":txt,
		"parentid":pid,
		"open":open,
		"tooltip":id,
		"typ":ct,
		"selected":0,
		"disabled":0
		};

		if(attribs["typ"]=="item"){
			attribs["published"]=0;
		}

		treeData.addSort(new node(attribs));
		drawTree();
	}
}';
	}

}
