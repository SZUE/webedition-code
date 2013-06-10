<?php

/**
 * webEdition CMS
 *
 * $Rev: 6059 $
 * $Author: mokraemer $
 * $Date: 2013-04-20 18:58:44 +0200 (Sa, 20 Apr 2013) $
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
class weMessagingFrames extends weModuleFrames{

	var $db;
	var $View;
	var $frameset;
	protected $weTransaction;
	protected $messaging;

	function __construct($frameset, $transaction = ''){

		parent::__construct(WE_MESSAGING_MODULE_DIR . "edit_messaging_frameset.php");

		//$this->Tree = new weGlossaryTree();
		$this->View = new weMessagingView(WE_MESSAGING_MODULE_DIR . "edit_messaging_frameset.php", "top.content");
		$this->weTransaction = $transaction;
		$this->module = "messaging";
		$this->treeDefaultWidth = 204;
	}

	function getHTML($what){
		switch($what){
			case "left":
				print $this->getHTMLLeft(false);
				break;
			case "msg_fv_headers":
				print $this->getHTMLMsgFvHeaders();
				break;
			default:
				parent::getHTML($what);
		}
	}

	function getJSCmdCode(){

		return $this->View->getJSTop_tmp();
		//. we_html_element::jsElement($this->Tree->getJSMakeNewEntry());
	}
	
	function getJSTreeCode(){ //TODO: move to new class weUsersTree (extends weModulesTree)
		//TODO: title nach View->getJSTop()
		$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
		$title = '';
		foreach($GLOBALS["_we_available_modules"] as $modData){
			if($modData["name"] == $mod){
				$title = "webEdition " . g_l('global', "[modules]") . ' - ' . $modData["text"];
				break;
			}
		}

		$jsOut = '
var loaded = 0;
var hot = 0;
var multi_select = 0;
var startloc=0;
loaded_thr = 2;
load_state = 0;
loaded = false;
deleteMode = false;
entries_selected = new Array();
del_parents = new Array();
open_folder = -1;
viewclass ="message";
mode = "show_folder_content";

parent.document.title = "' . $title . '";
we_transaction = "' . $this->weTransaction . '";

check0_img = new Image();
check1_img = new Image();
check0_img.src = "' . TREE_IMAGE_DIR . 'check0.gif";
check1_img.src = "' . TREE_IMAGE_DIR . 'check1.gif";

// message folders
f1_img = new Image();
f3_img = new Image();
f5_img = new Image();

f1_o_img = new Image();
f3_o_img = new Image();
f5_o_img = new Image();

f1_img.src = "' . ICON_DIR . 'msg_folder.gif";
f3_img.src = "' . ICON_DIR . 'msg_in_folder.gif";
f5_img.src = "' . ICON_DIR . 'msg_sent_folder.gif";

f1_o_img.src = "' . ICON_DIR . 'msg_folder_open.gif";
f3_o_img.src = "' . ICON_DIR . 'msg_in_folder_open.gif";
f5_o_img.src = "' . ICON_DIR . 'msg_sent_folder_open.gif";

// todo folders
tf1_img = new Image();
tf3_img = new Image();
tf13_img = new Image();
tf11_img = new Image();

tf1_o_img = new Image();
tf3_o_img = new Image();
tf13_o_img = new Image();
tf11_o_img = new Image();

tf1_img.src = "' . ICON_DIR . 'todo_folder.gif";
tf3_img.src = "' . ICON_DIR . 'todo_in_folder.gif";
tf13_img.src = "' . ICON_DIR . 'todo_done_folder.gif";
tf11_img.src = "' . ICON_DIR . 'todo_reject_folder.gif";

tf1_o_img.src = "' . ICON_DIR . 'todo_folder_open.gif";
tf3_o_img.src = "' . ICON_DIR . 'todo_in_folder_open.gif";
tf13_o_img.src = "' . ICON_DIR . 'todo_done_folder_open.gif";
tf11_o_img.src = "' . ICON_DIR . 'todo_reject_folder_open.gif";

function check(img) {
	var i;
	var tarr = img.split("_");
	var id = tarr[1];
	for (i = 1; i <= menuDaten.laenge; i++) {
		if (menuDaten[i].name == id) {
			if (menuDaten[i].checked) {
				if (resize.left.document.images) {
					if (resize.left.document.images[img]) {
						resize.left.document.images[img].src = check0_img.src;
					}
				}
				menuDaten[i].checked = false;
				unSelectMessage(img, "elem", "", 1);
				break;
			}
			else {
				if (resize.left.document.images) {
					if (resize.left.document.images[img]) {
						resize.left.document.images[img].src = check1_img.src;
					}
				}
				menuDaten[i].checked = true;
				doSelectMessage(img, "elem", "", 1);
				break;
			}
		}
	}
	if (!resize.left.document.images) {
		drawEintraege();
	}
}

function cb_incstate() {
	load_state++;
	if (!loaded && load_state >= loaded_thr) {
		loaded = true;
		loadData();
		';

		if(isset($_REQUEST['msg_param'])){
			if($_REQUEST['msg_param'] == 'todo'){
				$f = $this->messaging->get_inbox_folder('we_todo');
			} else if($_REQUEST['msg_param'] == 'message'){
				$f = $this->messaging->get_inbox_folder('we_message');
			}
			$jsOut .= '
			r_tree_open(' . $f['ID'] . ');
			';
		}
		if(isset($f)){
			$jsOut .= '
		we_cmd("show_folder_content", ' . $f['ID'] . ');
			';
		} else{
			$jsOut .= '
		drawEintraege();
			';
		}

		$jsOut .= '
	}
}

function r_tree_open(id) {
	ind = indexOfEntry(id);
	if (ind != -1) {
		menuDaten[ind].offen = 1;
		if (menuDaten[ind].vorfahr >= 1) {
			r_tree_open(menuDaten[ind].vorfahr);
		}
	}
}

function update_messaging() {
	if (!deleteMode && (mode == "show_folder_content") && (load_state >= loaded_thr)) {
		if (top.content.resize.right.editor.entries_selected && top.content.resize.right.editor.entries_selected.length > 0) {
			ent_str = "&entrsel=" + top.content.resize.right.editor.entries_selected.join(",");
		}
		else {
			ent_str = "";
		}
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=update_msgs" + ent_str;
	}
}

function update_icon(fid) {
	var s = 0;
	var ai = 1;
	if (fid == open_folder) {
		return 1;
	}
	while (ai <= menuDaten.laenge) {
		if (menuDaten[ai].name == fid) {
			menuDaten[ai].icon = menuDaten[ai].iconbasename + "_open.gif";
			if (++s == 2) {
				break;
			}
		}
		if (menuDaten[ai].name == open_folder) {
			menuDaten[ai].icon = menuDaten[ai].iconbasename + ".gif";
			if (++s == 2) {
				break;
			}
		}
		ai++;
	}
	open_folder = fid;
	drawEintraege();
}

function get_mentry_index(name) {
var ai = 1;
while (ai <= menuDaten.laenge) {
	if (menuDaten[ai].name == name)
		return ai;
	ai++;
}
return -1;
}

function set_frames(vc) {
if (vc == "message") {
	top.content.iconbar.location = "' . WE_MESSAGING_MODULE_DIR . 'messaging_iconbar.php?we_transaction=' . $this->weTransaction . '";
	top.content.resize.right.editor.edheader.location = "' . WE_MESSAGING_MODULE_DIR . 'messaging_search_frame.php?we_transaction=' . $this->weTransaction . '";
	top.content.resize.right.editor.edbody.messaging_fv_headers.location="' . we_class::url($this->frameset) . '&pnt=msg_fv_headers&viewclass=message&we_transaction=' . $this->weTransaction . '";
}
else if (vc == "todo") {
	top.content.iconbar.location = "' . WE_MESSAGING_MODULE_DIR . 'todo_iconbar.php?we_transaction=' . $this->weTransaction . '";
	top.content.resize.right.editor.edheader.location = "' . WE_MESSAGING_MODULE_DIR . 'todo_search_frame.php?we_transaction=' . $this->weTransaction . '";
	top.content.resize.right.editor.edbody.messaging_fv_headers.location="' . we_class::url($this->frameset) . '&pnt=msg_fv_headers&viewclass=todo&we_transaction=' . $this->weTransaction . '>";
}
viewclass= vc;
}

function doUnload() {
if (!!jsWindow_count) {
	for (i = 0; i < jsWindow_count; i++) {
		eval("jsWindow" + i + "Object.close()");
	}
}
}

function we_cmd() {
var args = "";
var url = "' . WEBEDITION_DIR . 'we_cmd.php?we_transaction=' . $this->weTransaction . '&";
for(var i = 0; i < arguments.length; i++) {
	url += "we_cmd["+i+"]="+escape(arguments[i]);
	if(i < (arguments.length - 1)) {
		url += "&";
	}
}

if(hot == "1" && arguments[0] != "messaging_start_view") {
	if(confirm("' . g_l('modules_messaging', "[save_changed_folder]") . '")) {
		top.content.resize.right.editor.document.edit_folder.submit();
	} else {
		top.content.usetHot();
	}
}
switch (arguments[0]) {
	case "messaging_exit":
		if(hot != "1") {
			eval(\'top.opener.top.we_cmd("exit_modules")\');
		}
		break;
	case "show_folder_content":
		ind = get_mentry_index(arguments[1]);
		if (ind > -1) {
			update_icon(arguments[1]);
			if (top.content.viewclass != menuDaten[ind].viewclass) {
				set_frames(menuDaten[ind].viewclass);
			}
			top.content.viewclass = menuDaten[ind].viewclass;
		}
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=show_folder_content&id=" + arguments[1];
		break;
	case "edit_folder":
		update_icon(arguments[1]);
		top.content.cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=edit_folder&mode=edit&fid=" + arguments[1];
		break;
	case "folder_new":
		break;
	case "messaging_new_message":
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=new_message&mode=new";
		break;
	case "messaging_new_todo":
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=new_todo";
		break;
	case "messaging_start_view":
		deleteMode = false;
		mode = "show_folder_content";
		entries_selected = new Array();
		drawEintraege();
		top.content.resize.right.editor.location = "' . WE_MESSAGING_MODULE_DIR . 'messaging_work.php?we_transaction=' . $this->weTransaction . '";
		top.content.usetHot();
		break;
	case "messaging_new_folder":
		mode = "folder_new";
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=edit_folder&mode=new";
		break;
	case "messaging_delete_mode_on":
		deleteMode = true;
		drawEintraege();
		top.content.resize.right.editor.location = "' . WE_MESSAGING_MODULE_DIR . 'messaging_delete_folders.php?we_transaction=' . $this->weTransaction . '";
		break;
	case "messaging_delete_folders":
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=delete_folders&folders=" + entries_selected.join(",");
		break;
	case "messaging_edit_folder":
		mode = "edit_folder";
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=edit_folder&mode=edit&fid=" + open_folder;
		break;
	case "messaging_settings":
		cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=edit_settings&mode=new";
		break;
	case "messaging_copy":
		if (resize && resize.right && resize.right.editor && resize.right.editor.entries_selected && resize.right.editor.entries_selected.length > 0) {
			cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=copy_msg&entrsel=" + resize.right.editor.entries_selected.join(",");
		}
		break;
	case "messaging_cut":
		if (resize && resize.right && resize.right.editor && resize.right.editor.entries_selected && resize.right.editor.entries_selected.length > 0) {
			cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=cut_msg&entrsel=" + resize.right.editor.entries_selected.join(",");
		}
		break;
	case "messaging_paste":
		top.content.cmd.location = "' . $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction . '&mcmd=paste_msg";
		break;
	default:
		for(var i = 0; i < arguments.length; i++) {
			args += "arguments["+i+"]" + ((i < (arguments.length-1)) ? "," : "");
		}
		eval("top.opener.top.we_cmd("+args+")");
	}
}

function setHot() {
	hot=1;
}

function usetHot() {
	hot=0;
}

var menuDaten = new container();
var count = 0;
var folder=0;
var table="' . MESSAGES_TABLE . '";
var mode = "show_folder_content";

function drawEintraege() {
	fr = top.content.resize.left.window.document;
	fr.open();
	fr.writeln("<html><head>");
	fr.writeln("<script type=\"text/javascript\"><!--");

	fr.writeln("clickCount=0;");
	fr.writeln("wasdblclick=0;");
	fr.writeln("tout=null");
	fr.writeln("function doClick(id) {");
	fr.writeln("top.content.we_cmd(top.content.mode,id);");
	fr.writeln("}");

	fr.writeln("top.content.loaded=1;//-->");
	fr.writeln("</" + "script>");
	fr.writeln(\'' . STYLESHEET_SCRIPT . '\');
	fr.writeln("</head>");
	fr.writeln("<body bgcolor=\"#F3F7FF\" link=\"#000000\" alink=\"#000000\" vlink=\"#000000\" leftmargin=5 topmargin=5 marginheight=5 marginwidth=5 >");
	fr.writeln("<table border=0 cellpadding=0 cellspacing=0 width=\"100%\"><tr><td class=\"tree\"><nobr>");

	zeichne(top.content.startloc, "");

	fr.writeln("</nobr></td></tr></table>");
	fr.writeln("</body></html>");
	fr.close();
}

function zeichne(startEntry, zweigEintrag) {
	var nf = search(startEntry);
	var ai = 1;
	while (ai <= nf.laenge) {
		fr.write(zweigEintrag);
		if (nf[ai].typ == "leaf_Folder") {
			if (ai == nf.laenge){
				fr.write("&nbsp;&nbsp;<IMG SRC=' . TREE_IMAGE_DIR . 'kreuzungend.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>");
			} else {
				fr.write("&nbsp;&nbsp;<IMG SRC=' . TREE_IMAGE_DIR . 'kreuzung.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>");
			}
			if (nf[ai].name != -1) {
				fr.write("<a name=\"_"+nf[ai].name+"\" href=\"javascript://\" onClick=\"doClick("+nf[ai].name+");return true;\" BORDER=0>");
			}
			if (deleteMode) {
				if(nf[ai].name != -1) {
					trg = "javascript:top.content.check(\"img_" + nf[ai].name + "\");"
					if(nf[ai].checked) {
						fr.write("<a href=\"" + trg + "\"><img src=\"' . TREE_IMAGE_DIR . 'check1.gif\"WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\"' . g_l('tree', "[select_statustext]") . '\" name=\"img_"+nf[ai].name+"\"></a>");
					}
					else {
						fr.write("<a href=\"" + trg + "\"><img src=\"' . TREE_IMAGE_DIR . 'check0.gif\"WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\"' . g_l('tree', "[select_statustext]") . '\" name=\"img_"+nf[ai].name+"\"></a>");
					}
				}
			} else {
				fr.write("<a name=\"_"+nf[ai].name+"\" href=\"javascript://\" onClick=\"doClick("+nf[ai].name+");return true;\" BORDER=0>");
				fr.write("<IMG SRC=' . ICON_DIR . '"+nf[ai].icon+" WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\"' . g_l('tree', '[edit_statustext]') . '\">");
				fr.write("</a>");
				trg = "doClick("+nf[ai].name+");return true;"
			}
			fr.write("&nbsp;<a name=\"_"+nf[ai].name+"\" href=\"javascript://\" onClick=\"" + trg + "\"><font color=\"black\">"+(parseInt(nf[ai].published) ? " <b>" : "")+ translate(nf[ai].text) +(parseInt(nf[ai].published) ? " </b>" : "")+ "</font></A>&nbsp;&nbsp;<BR>\n");
		} else {
			var newAst = zweigEintrag;
			var zusatz = (ai == nf.laenge) ? "end" : "";
			if (nf[ai].offen == 0) {
				fr.write("&nbsp;&nbsp;<A href=\"javascript:top.content.openClose(\'" + nf[ai].name + "\',1)\" BORDER=0><IMG SRC=' . TREE_IMAGE_DIR . 'auf"+zusatz+".gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0 Alt=\'' . g_l('tree', '[open_statustext]') . '\'></A>");
				var zusatz2 = "";
			} else {
				fr.write("&nbsp;&nbsp;<A href=\"javascript:top.content.openClose(\'" + nf[ai].name + "\',0)\" BORDER=0><IMG SRC=' . TREE_IMAGE_DIR . 'zu"+zusatz+".gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0 Alt=\'' . g_l('tree', '[close_statustext]') . '\'></A>");
				var zusatz2 = "open";
			}
			if(deleteMode) {
				if(nf[ai].name != -1) {
					trg = "javascript:top.content.check(\"img_" + nf[ai].name + "\");";
					if(nf[ai].checked) {
						fr.write("<a href=\"" + trg + "\"><img src=\'' . TREE_IMAGE_DIR . 'check1.gif\' WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\'' . g_l('tree', '[select_statustext]') . '\' name=\'img_"+nf[ai].name+"\'></a>");
					} else {
						fr.write("<a href=\"" + trg + "\"><img src=\'' . TREE_IMAGE_DIR . 'check0.gif\' WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\'' . g_l('tree', '[select_statustext]') . '\' name=\'img_"+nf[ai].name+"\'></a>");
					}
				}
			} else {
				trg = "doClick("+nf[ai].name+");return true;"
			}

			fr.write("<a name=\'_"+nf[ai].name+"\' href=\"javascript://\" onClick=\"" + trg + "\" BORDER=0>");
			fr.write("<IMG SRC=' . ICON_DIR . '" + nf[ai].icon + " WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 Alt=\'' . g_l('tree', '[edit_statustext]') . '\'>");
			fr.write("</a>");

			fr.write("<A name=\"_"+nf[ai].name+"\" HREF=\"javascript://\" onClick=\"" + trg + "\">");
			fr.write("&nbsp;" + translate(nf[ai].text));
			fr.write("</a>");
			fr.write("&nbsp;&nbsp;<BR>\n");
			if (nf[ai].offen) {
				if(ai == nf.laenge) {
					newAst = newAst + "<IMG SRC=' . TREE_IMAGE_DIR . 'leer.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>";
				} else {
					newAst = newAst + "<IMG SRC=' . TREE_IMAGE_DIR . 'strich2.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>";
				}
				zeichne(nf[ai].name,newAst);
			}
		}
		ai++;
	}
}

function translate(inp){
	if(inp.substring(0,12).toLowerCase() == "messages - ("){
		return "' . g_l('modules_messaging', '[Mitteilungen]') . ' - ("+inp.substring(12,inp.length);
	}else if(inp.substring(0,8).toLowerCase() == "task - ("){
		return "' . g_l('modules_messaging', '[ToDo]') . ' - ("+inp.substring(8,inp.length);
	}else if(inp.substring(0,8).toLowerCase() == "todo - ("){
		return "' . g_l('modules_messaging', '[ToDo]') . ' - ("+inp.substring(8,inp.length);
	}else if(inp.substring(0,8).toLowerCase() == "done - ("){
		return "' . g_l('modules_messaging', '[Erledigt]') . ' - ("+inp.substring(8,inp.length);
	}else if(inp.substring(0,12).toLowerCase() == "rejected - ("){
		return "' . g_l('modules_messaging', '[Zurueckgewiesen]') . ' - ("+inp.substring(12,inp.length);
	}else if(inp.substring(0,8).toLowerCase() == "sent - ("){
		return "' . g_l('modules_messaging', '[Gesendet]') . ' - ("+inp.substring(8,inp.length);
	}else{
		return inp;
	}

}

function updateEntry(id,pid,text,pub,redraw) {
	var ai = 1;
	while (ai <= menuDaten.laenge) {
		if ((menuDaten[ai].typ=="parent_Folder") || (menuDaten[ai].typ=="leaf_Folder"))
			if (menuDaten[ai].name==id) {
				if (pid != -1) {
					menuDaten[ai].vorfahr=pid;
				}
			menuDaten[ai].text=text;
			if (pub != -1) {
				menuDaten[ai].published=pub;
			}
			break;
		}
		ai++;
	}
	if (redraw == 1) {
		drawEintraege();
	}
}

function deleteEntry(id) {
	var ai = 1;
	var ind=0;
	while (ai <= menuDaten.laenge) {
		if ((menuDaten[ai].typ=="parent_Folder") || (menuDaten[ai].typ=="leaf_Folder"))
			if (menuDaten[ai].name==id) {
				ind=ai;
				break;
			}
		ai++;
	}
	if(ind!=0) {
		ai = ind;
		while (ai <= menuDaten.laenge-1) {
			menuDaten[ai]=menuDaten[ai+1];
			ai++;
		}
		menuDaten.laenge[menuDaten.laenge]=null;
		menuDaten.laenge--;
		drawEintraege();
	}
}

function openClose(name,status) {
	var eintragsIndex = indexOfEntry(name);
	menuDaten[eintragsIndex].offen = status;
	if(status) {
		if(!menuDaten[eintragsIndex].loaded) {
			drawEintraege();
		}
		else {
			drawEintraege();
		}
	}
	else {
		drawEintraege();
	}
}

function indexOfEntry(name) {
	var ai = 1;
	while (ai <= menuDaten.laenge) {
		if ((menuDaten[ai].typ == "root") || (menuDaten[ai].typ == "parent_Folder"))
			if (menuDaten[ai].name == name)
				return ai;
		ai++;
	}
	return -1;
}

function search(eintrag) {
	var nf = new container();
	var ai = 1;
	while (ai <= menuDaten.laenge) {
		if ((menuDaten[ai].typ == "parent_Folder") || (menuDaten[ai].typ == "leaf_Folder"))
			if (menuDaten[ai].vorfahr == eintrag)
				nf.add(menuDaten[ai]);
		ai++;
	}
	return nf;
}

function container() {
	this.laenge = 0;
	this.clear=containerClear;
	this.add = add;
	this.addSort = addSort;
	return this;
}

function add(object) {
	this.laenge++;
	this[this.laenge] = object;
}

function update_Node(id) {
	var i;
	var off = -1;
	for (i = 1; i < menuDaten.laenge; i++) {
		if (menuDaten[i].name == id) {
			off = i;
			break;
		}
	}
}

function get_index(id) {
	var i;
	for (i = 1; i <= menuDaten.laenge; i++) {
		if (menuDaten[i].name == id) {
			return i;
		}
	}
	return -1;
}

function folder_added(parent_id) {
	var ind = get_index(parent_id);
	if (ind > -1) {
		if (menuDaten[ind].typ == "leaf_Folder") {
			menuDaten[ind].typ = "parent_Folder";
			menuDaten[ind].offen = 0;
			menuDaten[ind].leaf_count = 1;
		}
		else {
			menuDaten[ind].leaf_count++;
		}
	}
}

function folders_removed() {
	var ind;
	var i;
	for (i = 0; i < del_parents.length; i++) {
		if ((ind = get_index(del_parents[i])) < 0) {
			continue;
		}
		menuDaten[ind].leaf_count--;
		if (menuDaten[ind].leaf_count <= 0) {
			menuDaten[ind].typ = "leaf_Folder";
		}
	}
}

function delete_menu_entries(ids) {
	var i, done = 0;
	var t = menuDaten;
	var cont = new container();
	del_parents = new Array();
	for (i = 1; i <= t.laenge; i++) {
		if (array_search(t[i].name, ids) == -1) {
			cont.add(t[i]);
		}
		else {
			del_parents = del_parents.concat(new Array(String(t[i].vorfahr)));
		}
	}
	menuDaten = cont;
}

function containerClear() {
	this.laenge =0;
}

function addSort(object) {
	this.laenge++;
	for(var i=this.laenge; i > 0; i--) {
		if(i > 1 && this[i-1].text.toLowerCase() > object.text.toLowerCase()) {
			this[i] = this[i-1];
		}
		else {
			this[i] = object;
			break;
		}
	}
}

function rootEntry(name,text,rootstat) {
	this.name = name;
	this.text = text;
	this.loaded=true;
	this.typ = "root";
	this.rootstat = rootstat;
	return this;
}

function dirEntry(icon,name,vorfahr,text,offen,contentType,table,leaf_count,iconbasename,viewclass) {
	this.icon=icon;
	this.iconbasename=iconbasename;
	this.name = name;
	this.vorfahr = vorfahr;
	this.text = text;
	this.typ = "parent_Folder";
	this.offen = (offen ? 1 : 0);
	this.contentType = contentType;
	this.leaf_count = leaf_count;
	this.table = table;
	this.loaded = (offen ? 1 : 0);
	this.checked = false;
	this.viewclass = viewclass;
	return this;
}

function urlEntry(icon,name,vorfahr,text,contentType,table,iconbasename,viewclass) {
	this.icon=icon;
	this.iconbasename=iconbasename;
	this.name = name;
	this.vorfahr = vorfahr;
	this.text = text;
	this.typ = "leaf_Folder";
	this.checked = false;
	this.contentType = contentType;
	this.table = table;
	this.viewclass = viewclass;
	return this;
}

function loadData() {
	menuDaten.clear();
		';

		$entries = array();
		$jsOut .= '
	startloc=0;
	menuDaten.add(new self.rootEntry("0","root","root"));
		';

		foreach($this->messaging->available_folders as $folder){
			switch($folder['obj_type']){
				case we_msg_proto::FOLDER_INBOX:
					$iconbasename = $folder['ClassName'] == 'we_todo' ? 'todo_in_folder' : 'msg_in_folder';
					$folder['Name'] = $folder['ClassName'] == 'we_todo' ? g_l('modules_messaging', '[ToDo]') : g_l('modules_messaging', '[Mitteilungen]');
					break;
				case we_msg_proto::FOLDER_SENT:
					$iconbasename = 'msg_sent_folder';
					$folder['Name'] = g_l('modules_messaging', '[Gesendet]');
					break;
				case we_msg_proto::FOLDER_DONE:
					$iconbasename = 'todo_done_folder';
					$folder['Name'] = g_l('modules_messaging', '[Erledigt]');
					break;
				case we_msg_proto::FOLDER_REJECT:
					$iconbasename = 'todo_reject_folder';
					$folder['Name'] = g_l('modules_messaging', '[Zurueckgewiesen]');
					break;
				default:
					$iconbasename = $folder['ClassName'] == 'we_todo' ? 'todo_folder' : 'msg_folder';
					break;
			}
			if(($sf_cnt = $this->messaging->get_subfolder_count($folder['ID'], '')) >= 0){


				$jsOut .= '
	menuDaten.add(new dirEntry("' . $iconbasename . '.gif","' . $folder['ID'] . '","' . $folder['ParentID'] . '","' . $folder['Name'] . ' - (' . $this->messaging->get_message_count($folder['ID'], '') . ')",false,"parent_Folder","' . MESSAGES_TABLE . '", ' . $sf_cnt . ', "' . $iconbasename . '", "' . $folder['view_class'] . '"));
				';
			} else{
				$jsOut .= '
	menuDaten.add(new urlEntry("' . $iconbasename . '.gif","' . $folder['ID'] . '","' . $folder['ParentID'] . '","' . $folder['Name'] . ' - (' . $this->messaging->get_message_count($folder['ID'], '') . ')","leaf_Folder","' . MESSAGES_TABLE . '", "' . $iconbasename . '", "' . $folder['view_class'] . '"));
				';
			}
		}
		$jsOut .= '
}

function msg_start() {
	loadData();
	drawEintraege();
}
		';
		return we_html_element::jsElement($jsOut);
	}

	function getHTMLFrameset(){//TODO: use parent as soon as userTree.class exists
		//TODO: most of these JS will be obsolet when calling parent::getHTMLFRameset
		$extraHead = $this->getJSCmdCode() . 
			self::getJSToggleTreeCode($this->module, $this->treeDefaultWidth) .
			we_html_element::jsScript(JS_DIR . 'we_showMessage.js') .
			we_html_element::jsScript(JS_DIR . 'messaging_std.js') .
			we_html_element::jsScript(JS_DIR . 'messaging_hl.js') . 
			we_main_headermenu::css();
		
		//$cmd_params = '';

		$this->messaging = new we_messaging($_SESSION['weS']['we_data'][$this->weTransaction]);
		$this->messaging->set_login_data($_SESSION["user"]["ID"], $_SESSION["user"]["Username"]);

		if(!$this->messaging->check_folders()){
			include_once(WE_MESSAGING_MODULE_PATH . "messaging_interfaces.inc.php");
			if(!msg_create_folders($_SESSION["user"]["ID"])){
				$extraHead .= we_html_element::jsElement(we_message_reporting::getShowMessageCall(g_l('modules_messaging', '[cant_create_folders]'), we_message_reporting::WE_MESSAGE_ERROR));
			}
		}

		$this->messaging->init($_SESSION['weS']['we_data'][$this->weTransaction]);

		$this->messaging->add_msgobj('we_message', 0);
		$this->messaging->add_msgobj('we_todo', 0);
		$this->messaging->add_msgobj('we_msg_email', 0);

		$this->messaging->saveInSession($_SESSION['weS']['we_data'][$this->weTransaction]);

		//print STYLESHEET;

		$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : '';
		$title = '';
		foreach($GLOBALS["_we_available_modules"] as $modData){
			if($modData["name"] == $mod){
				$title = "webEdition " . g_l('global', "[modules]") . ' - ' . $modData["text"];
				break;
			}
		}
		
		$extraHead .= $this->getJSTreeCode();

		//TODO: change frame names for using parent::getHTMLFrameset
		$body = we_html_element::htmlBody(array('background' => IMAGE_DIR . 'backgrounds/aquaBackground.gif', 'style' => 'background-color:#bfbfbf;background-repeat:repeat;margin:0px;position:fixed;top:0px;left:0px;right:0px;bottom:0px;border:0px none;', "onload" => "start();")
				, we_html_element::htmlDiv(array('style' => 'position:absolute;top:0px;bottom:0px;left:0px;right:0px;')
					, we_html_element::htmlExIFrame('header', parent::getHTMLHeader(WE_INCLUDES_PATH .'java_menu/modules/module_menu_messaging.inc.php', 'messaging'), 'position:absolute;top:0px;height:32px;left:0px;right:0px;') .
					we_html_element::htmlIFrame('iconbar', WE_MESSAGING_MODULE_DIR . 'messaging_iconbar.php?we_transaction=' . $this->weTransaction, 'position:absolute;top:32px;height:40;left:0px;right:0px;overflow: hidden;') .
					we_html_element::htmlIFrame('resize', $this->frameset . '?pnt=resize&we_transaction=' . $this->weTransaction, 'position:absolute;top:72px;bottom:1px;left:0px;right:0px;overflow: hidden;') .
					//we_html_element::htmlIFrame('cmd', WE_MESSAGING_MODULE_DIR . 'messaging_cmd.php', 'position:absolute;bottom:0px;height:1px;left:0px;right:0px;')
					we_html_element::htmlIFrame('cmd', $this->frameset . '?pnt=cmd&we_transaction=' . $this->weTransaction, 'position:absolute;bottom:0px;height:1px;left:0px;right:0px;')
				));
		
		return $this->getHTMLDocument($body, $extraHead);
	}


	function getHTMLResize(){// TODO: change id messaging_right to right, move content of messaging_right.php here and eliminate this
		require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
		if(!preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction'])){
			exit();
		}

		$this->setTreeWidthFromCookie();
		$extraHead = parent::getJSToggleTreeCode($this->module, $this->treeDefaultWidth);

		$_incDecTree = '
			<img id="incBaum" src="' . BUTTONS_DIR . 'icons/function_plus.gif" width="9" height="12" style="position:absolute;bottom:53px;left:5px;border:1px solid grey;padding:0 1px;cursor: pointer; ' . ($this->treeWidth <= 30 ? 'bgcolor:grey;' : '') . '" onClick="top.content.resize.incTree();">
			<img id="decBaum" src="' . BUTTONS_DIR . 'icons/function_minus.gif" width="9" height="12" style="position:absolute;bottom:33px;left:5px;border:1px solid grey;padding:0 1px;cursor: pointer; ' . ($this->treeWidth <= 30 ? 'bgcolor:grey;' : '') . '" onClick="top.content.resize.decTree();">
			<img id="arrowImg" src="' . BUTTONS_DIR . 'icons/direction_' . ($this->treeWidth <= 30 ? 'right' : 'left') . '.gif" width="9" height="12" style="position:absolute;bottom:13px;left:5px;border:1px solid grey;padding:0 1px;cursor: pointer;" onClick="top.content.resize.toggleTree();">
		';

		$body = we_html_element::htmlBody(array('style' => 'background-color:#bfbfbf;'), 
				we_html_element::htmlDiv(array('style' => 'position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px;'),
					we_html_element::htmlDiv(array('id' => 'lframeDiv','style' => 'position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px;width: ' . $this->treeWidth . 'px;'),
						we_html_element::htmlDiv(array('style' => 'position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px; width: ' . weTree::HiddenWidth . 'px; background-image: url(/webEdition/images/v-tabs/background.gif); background-repeat: repeat-y; border-top: 1px solid black;'), $_incDecTree) .
						we_html_element::htmlIFrame('left', $this->frameset . '?pnt=left', 'position: absolute; top: 0px; bottom: 0px; left: ' . weTree::HiddenWidth . 'px; right: 0px;')
					) .
					we_html_element::htmlIFrame('right', $this->frameset . '?pnt=right&we_transaction=' . $_REQUEST['we_transaction'], 'position: absolute; top: 0px; bottom: 0px; left: ' . $this->treeWidth . 'px; right: 0px; width:auto; border-left: 1px solid black; overflow: hidden;')
			));

		return $this->getHTMLDocument($body, $extraHead);
	}


	function getHTMLCmd(){

		return $this->getHTMLDocument(we_html_element::htmlBody(array(), ''), $this->View->processCommands());
	}

	/* use parent
	function getHTMLLeft(){}
	 * 
	 */

	function getHTMLSearch(){
		echo we_html_element::jsScript(JS_DIR . 'images.js') . STYLESHEET;
		?>
		</head>
		<body bgcolor="white" background="<?php echo IMAGE_DIR; ?>edit/editfooterback.gif" marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
			<form name="we_form" onSubmit="top.content.we_cmd('search',document.we_form.keyword.value); return false;">
				<table border="0" cellpadding="0" cellspacing="0" width="3000">
					<tr>
						<td></td>
						<td colspan="2" valign="top"><?php we_html_tools::pPixel(1600, 10); ?></td>
					</tr>
					<tr>
						<td><?php we_html_tools::pPixel(1, 5); ?></td>
						<td><?php
							print we_button::create_button_table(array(we_html_tools::htmlTextInput("keyword", 14, "", "", "", "text", 120), we_button::create_button("image:btn_function_search", "javascript:top.content.we_cmd('search',document.we_form.keyword.value);")), 5);?>
						</td>
					</tr>
				</table>
			</form>
		</body>
		</html>
		<?php
	}

	function getHTMLRight(){
		if(!preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction'])){
			exit();
		}

		$frameset = new we_html_frameset(array("framespacing" => "0", "border" => "0", "frameborder" => "no"));
		$frameset->setAttributes(array("cols" => "*"));
		$frameset->addFrame(array("src" => $this->frameset . "?pnt=editor&we_transaction=" . $_REQUEST['we_transaction'], "name" => "editor", "noresize" => null, "scrolling" => "no"));
		$noframeset = new we_baseElement("noframes");
		$body = $frameset->getHtml() . $noframeset->getHTML();

		return $this->getHTMLDocument($body);
	}

	function getHTMLEditor(){

		$frameset = new we_html_frameset(array("framespacing" => "0", "border" => "0", "frameborder" => "no"));

		$frameset->setAttributes(array("rows" => "35,*"));
		$frameset->addFrame(array('src' => $this->frameset . '?pnt=edheader&we_transaction=' . $_REQUEST['we_transaction'], 'name' => 'edheader', 'noresize' => null, 'scrolling' => 'no'));
		$frameset->addFrame(array('src' => $this->frameset . '?pnt=edbody&we_transaction=' . $_REQUEST['we_transaction'], 'name' => 'edbody', 'scrolling' => 'auto'));
		//$frameset->addFrame(array('src' => $this->frameset . (isset($_REQUEST['sid']) ? '?sid=' . $_REQUEST['sid'] : '?home=1') . '&pnt=edfooter', 'name' => 'edfooter', 'scrolling' => 'no'));

		$body = $frameset->getHtml();

		return $this->getHTMLDocument($body);
	}

	function getHTMLEditorHeader(){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
		we_html_tools::protect();
		we_html_tools::htmlTop();

		print STYLESHEET;
		if(!preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction'])){
			exit();
		}
		echo we_html_element::jsScript(JS_DIR . 'windows.js');
		?>
		<script type="text/javascript"><!--
			function doSearch() {
				top.content.cmd.location = 'edit_messaging_frameset.php?pnt=cmd&mcmd=search_messages&we_transaction=<?php echo $_REQUEST['we_transaction'] ?>&searchterm=' + document.we_messaging_search.messaging_search_keyword.value;
			}

			function launchAdvanced() {
				new jsWindow("<?php print WE_MESSAGING_MODULE_DIR; ?>messaging_search_advanced.php?we_transaction=<?php echo $_REQUEST['we_transaction'] ?>","messaging_search_advanced",-1,-1,300,240,true,false,true,false);
			}

			function clearSearch() {
				document.we_messaging_search.messaging_search_keyword.value = "";
				top.content.cmd.location = '<?php print $this->frameset . '?pnt=cmd'; ?>&mcmd=launch&we_transaction=<?php echo $_REQUEST['we_transaction'] ?>&mode=' + top.content.viewclass;
			}
			//-->
		</script>
		</head>
		<body marginwidth="10" marginheight="7" topmargin="7" leftmargin="7" background="<?php echo IMAGE_DIR; ?>msg_white_bg.gif">
		<nobr>
			<form name="we_messaging_search" action="<?php print WE_MESSAGING_MODULE_DIR; ?>messaging_search_frame.php" onSubmit="return doSearch()">
				<?php echo we_html_tools::hidden('we_transaction', $_REQUEST['we_transaction']) ?>

				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="defaultfont"><?php echo g_l('modules_messaging', '[search_messages]') ?>:</td>
						<td width="10"></td>
						<?php
						echo '<td class="defaultfont">' .
						we_button::create_button_table(array(we_html_tools::htmlTextInput('messaging_search_keyword', 15, isset($_REQUEST['messaging_search_keyword']) ? $_REQUEST['messaging_search_keyword'] : '', 15),
							we_button::create_button("search", "javascript:doSearch();"),
							we_button::create_button("advanced", "javascript:launchAdvanced()", true),
							we_button::create_button("reset_search", "javascript:clearSearch();")), 10)
						. '</td>';
						?>
					</tr>
				</table>
			</form>
		</nobr>
		</body>
		</html>
	<?php
	}

	function getHTMLEditorBody(){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');

		if(!preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction'])){
			exit();
		}

		we_html_tools::protect();

		we_html_tools::htmlTop();
		?>
		<script type="text/javascript"><!--
			do_mark_messages = 0;
			last_entry_selected = -1;
			entries_selected = new Array();
			//-->
		</script>

		</head>
		<frameset rows="35,*" framespacing="0" border="0" frameborder="NO">	
			<frameset rows="26,1,*" framespacing="0" border="0" frameborder="NO">
				<frame src="<?php print we_class::url($this->frameset) . '&pnt=msg_fv_headers'; ?>" name="messaging_fv_headers" scrolling="no" noresize/>
				<frame src="<?php echo HTML_DIR ?>msg_white_fr.html" noresize scrolling="no"/>
				<frame src="<?php print WE_MESSAGING_MODULE_DIR; ?>messaging_mfv.php" name="msg_mfv" scrolling="no"/>
			</frameset>
		</frameset>
		<noframes>
			<body background="<?php print IMAGE_DIR ?>backgrounds/aquaBackground.gif" style="background-color:#bfbfbf; background-repeat:repeat;margin:0px 0px 0px 0px">
			</body>
		</noframes>
		</html>
		<?php
	}
	
	function getHTMLMsgFvHeaders(){
		require_once($_SERVER['DOCUMENT_ROOT'] . '/webEdition/we/include/we.inc.php');
		include_once($_SERVER['DOCUMENT_ROOT'] . WE_MESSAGING_MODULE_DIR . "msg_html_tools.inc.php");
		we_html_tools::protect();
		we_html_tools::htmlTop();

		$_REQUEST['we_transaction'] = isset($_REQUEST['we_transaction']) ? $_REQUEST['we_transaction'] : $we_transaction;
		$_REQUEST['we_transaction'] = (preg_match('|^([a-f0-9]){32}$|i', $_REQUEST['we_transaction']) ? $_REQUEST['we_transaction'] : 0);
		print we_html_element::jsElement('
			function doSort(sortitem) {
				entrstr = "";

				top.content.cmd.location = "' . $this->frameset . '?pnt=cmd&mcmd=show_folder_content&sort=" + sortitem + entrstr + "&we_transaction=' . $_REQUEST['we_transaction'] . '";
			}') .
			STYLESHEET .
			we_html_element::cssElement('.defaultfont a {color:black; text-decoration:none}');
		?>
		</head>
		<body  background="<?php print IMAGE_DIR; ?>backgrounds/header_with_black_line.gif"  marginwidth="7" marginheight="6" topmargin="6" leftmargin="7">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<?php if(!isset($_REQUEST["viewclass"]) || $_REQUEST["viewclass"] != "todo"){ ?>
						<td width="18"><?php we_html_tools::pPixel(18, 1) ?></td>
						<td class="defaultfont" width="200"><a href="javascript:doSort('subject');"><b><?php echo g_l('modules_messaging', '[subject]') ?></b>&nbsp;<?php echo ( (isset($_REQUEST["si"]) && $_REQUEST["si"] == 'subject') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="170"><a href="javascript:doSort('date');"><b><?php echo g_l('modules_messaging', '[date]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'date') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="120"><a href="javascript:doSort('sender');"><b><?php echo g_l('modules_messaging', '[from]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'sender') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="70"><a href="javascript:doSort('isread');"><b><?php echo g_l('modules_messaging', '[is_read]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'isread') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
					<?php } else{ ?>
						<td width="18"><?php we_html_tools::pPixel(18, 1) ?></td>
						<td class="defaultfont" width="200"><a href="javascript:doSort('subject');"><b><?php echo g_l('modules_messaging', '[subject]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'subject') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="170"><a href="javascript:doSort('deadline');"><b><?php echo g_l('modules_messaging', '[deadline]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'deadline') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="120"><a href="javascript:doSort('priority');"><b><?php echo g_l('modules_messaging', '[priority]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'priority') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
						<td class="defaultfont" width="70"><a href="javascript:doSort('status');"><b><?php echo g_l('modules_messaging', '[status]') ?></b>&nbsp;<?php echo ((isset($_REQUEST["si"]) && $_REQUEST["si"] == 'status') ? sort_arrow("arrow_sortorder_" . $_REQUEST['so'], "") : we_html_tools::getPixel(1, 1)) ?></a></td>
					<?php } ?>
				</tr>
			</table>
		</body>
		</html>
		<?php
	}

	function getHTMLEditorFooter(){

	}

}
