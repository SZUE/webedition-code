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
class we_voting_tree extends weMainTree{

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
			' . $this->cmdFrame . '.location="' . $this->frameset . '?pnt=cmd&pid="+id+(sort!=""?"&sort="+sort:"");
	}else{
		drawTree();
	}
	if(openstatus==1){
		treeData[eintragsIndex].loaded=1;
	}
}';
	}

	function getJSUpdateItem(){
		return '
function updateEntry(id,text,pid,pub){
	var ai = 1;
	while (ai <= treeData.len) {
		if (treeData[ai].id==id) {
			treeData[ai].text=text;
			treeData[ai].parentid=pid;
			treeData[ai].published=pub;
		}
		ai++;
	}
	drawTree();
}';
	}

	function getJSTreeFunctions(){
		return weTree::getJSTreeFunctions() . '
function doClick(id,typ){
	var cmd = "";
	if(top.content.hot == "1") {
		if(confirm("' . g_l('modules_voting', '[save_changed_voting]') . '")) {
			cmd = "save_voting";
			top.content.we_cmd("save_voting");
		} else {
			top.content.usetHot();
			cmd = "voting_edit";
			var node=' . $this->topFrame . '.get(id);
			' . $this->topFrame . '.editor.edbody.location="' . $this->frameset . '?pnt=edbody&cmd="+cmd+"&cmdid="+node.id+"&tabnr="+' . $this->topFrame . '.activ_tab;
		}
	} else {
		cmd = "voting_edit";
		var node=' . $this->topFrame . '.get(id);
		' . $this->topFrame . '.editor.edbody.location="' . $this->frameset . '?pnt=edbody&cmd="+cmd+"&cmdid="+node.id+"&tabnr="+' . $this->topFrame . '.activ_tab;
	}
}
' . $this->topFrame . '.loaded=1;';
	}

	function getJSStartTree(){

		return 'function startTree(){
				' . $this->cmdFrame . '.location="' . $this->frameset . '?pnt=cmd&pid=0";
				drawTree();
			}';
	}

	function getJSIncludeFunctions(){
		return weTree::getJSIncludeFunctions() . $this->getJSStartTree();
	}

	function getJSMakeNewEntry(){
		return '
function makeNewEntry(icon,id,pid,txt,open,ct,tab,pub){
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
		"disabled":0,
		"published":(ct=="item"?pub:1),
		"selected":0
		};

		treeData.addSort(new node(attribs));

		drawTree();
	}
}';
	}

	function getJSInfo(){
		return 'function info(text) {}';
	}

	function getJSShowSegment(){
		return '
function showSegment(){
	parentnode=' . $this->topFrame . '.get(this.parentid);
	parentnode.clear();
	' . $this->cmdFrame . '.location="' . $this->frameset . '?pnt=cmd&pid="+this.parentid+"&offset="+this.offset;
	drawTree();
}';
	}

}
