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

require_once($_SERVER['DOCUMENT_ROOT'].'/webEdition/we/include/we.inc.php');

header("Content-Type: text/javascript");

?>
function weSidebar() {}


//
// ----> Functions to load documents in webEdition
//

weSidebar.load = function(url,params) {
	var cmd = Array();
	cmd[0] = 'loadSidebarDocument';
	cmd[1] = url;
	cmd[2] = params;
	top.we_cmd(cmd[0], cmd[1],cmd[2]);

}


//
// ----> Functions to open, close and resize sidebar
//

weSidebar.open = function() {
	var cmd = Array();

	// load document if needed
	if(typeof arguments[0] != "undefined") {
		weSidebar.load(arguments[0]);

	} else if(arguments[0] == "default") {
		weSidebar.load('');

	}

	// get width of sidebar frame
	if(typeof arguments[1] != "undefined") {
		width = parseInt(arguments[1]);

	} else {
		width = <?php echo (!defined("SIDEBAR_DEFAULT_WIDTH") ? 300 : SIDEBAR_DEFAULT_WIDTH); ?>;

	}
	if (isNaN( width ) ||  width < 100 ) {
		width = 100;
	}

	window.setTimeout("weSidebar.resize("+width+");", 200);

}


weSidebar.close = function() {
	top.frames["rframe"].document.getElementById("bm_content_frameDiv").style.right = 0 + "px";
	top.frames["rframe"].document.getElementById("sidebarDiv").style.width = 0 + "px";

}


weSidebar.resize = function(width) {
	top.frames["rframe"].document.getElementById("bm_content_frameDiv").style.right = width + "px";
	top.frames["rframe"].document.getElementById("sidebarDiv").style.width = width + "px";

}


weSidebar.reloadHeader = function() {
	top.frames["rframe"].frames["sidebar"].frames["weSidebarHeader"].location.reload();
	//top.frames["rframe"].frames["sidebar"].frames["weSidebarHeader"].location.replace(top.frames["rframe"].frames["sidebar"].frames["weSidebarHeader"].location);

}


weSidebar.reload = function() {
	top.frames["rframe"].frames["sidebar"].frames["weSidebarContent"].location.reload();
	//top.frames["rframe"].frames["sidebar"].frames["weSidebarContent"].location.replace(top.frames["rframe"].frames["sidebar"].frames["weSidebarContent"].location);

}


weSidebar.reloadFooter = function() {
	top.frames["rframe"].frames["sidebar"].frames["weSidebarFooter"].location.reload();
	//top.frames["rframe"].frames["sidebar"].frames["weSidebarFooter"].location.replace(top.frames["rframe"].frames["sidebar"].frames["weSidebarFooter"].location);

}


//
// ----> Functions to open tabs in webEdition
//

weSidebar.openUrl = function(url) {
	//	build command for this location
	top.we_cmd("open_url_in_editor", url);

}


weSidebar.openDocument = function(obj) {
	obj['table'] = "<?php echo FILE_TABLE; ?>";
	obj['ct'] = (typeof obj['ct'] == "undefined" ? "text/webedition" : obj['ct']);
	weSidebar._open(obj);

}


weSidebar.openDocumentById = function() {
	obj['id'] = (typeof arguments[0] == "undefined" ? 0 : arguments[0]);
	obj['ct'] = (typeof arguments[1] == "undefined" ? "text/webedition" : arguments[1]);
	weSidebar._open(obj);

}


weSidebar.openTemplate = function(obj) {
	obj['table'] = "<?php echo TEMPLATES_TABLE; ?>";
	obj['ct'] = "text/weTmpl";
	weSidebar._open(obj);

}


weSidebar.openTemplateById = function() {
	obj['id'] = (typeof arguments[0] == "undefined" ? 0 : arguments[0]);
	weSidebar._open(obj);

}

<?php
if(defined("OBJECT_FILES_TABLE")) {
?>
weSidebar.openObject = function(obj) {
	obj['table'] = "<?php echo OBJECT_FILES_TABLE; ?>";
	obj['ct'] = "objectFile";
	weSidebar._open(obj);

}


weSidebar.openObjectById = function() {
	obj['id'] = (typeof arguments[0] == "undefined" ? 0 : arguments[0]);
	weSidebar._open(obj);

}


weSidebar.openClass = function(obj) {
	obj['table'] = "<?php echo OBJECT_TABLE; ?>";
	obj['ct'] = "object";
	weSidebar._open(obj);

}


weSidebar.openClassById = function() {
	obj['id'] = (typeof arguments[0] == "undefined" ? 0 : arguments[0]);
	weSidebar._open(obj);

}


<?php
} else {
?>
weSidebar.openObject = function(obj) {

}


weSidebar.openClass = function(obj) {

}


<?php
}
?>
weSidebar.openCockpit = function() {
	obj['ct'] = "cockpit";
	obj['editcmd'] = "open_cockpit";
	weSidebar._open(obj);

}


//
// ----> Function to open navigation tool
//

weSidebar.openNavigation = function() {
	var cmd = Array();
	cmd[0] = 'edit_navigation';
	top.we_cmd(cmd[0]);

}


//
// ----> Function to open doctypes
//

weSidebar.openDoctypes = function() {
	var cmd = Array();
	cmd[0] = 'doctypes';
	top.we_cmd(cmd[0]);

}

//
// ----> Internal function
//

weSidebar._open = function(obj) {
	table = (typeof obj['table'] == "undefined" ? "" : obj['table']);
	id = (typeof obj['id'] == "undefined" ? "" : obj['id']);
	ct = (typeof obj['ct'] == "undefined" ? "" : obj['ct']);
	editcmd = (typeof obj['editcmd'] == "undefined" ? "" : obj['editcmd']);
	dt = (typeof obj['dt'] == "undefined" ? "" : obj['dt']);
	url = (typeof obj['url'] == "undefined" ? "" : obj['url']);
	code = (typeof obj['code'] == "undefined" ? "" : obj['code']);
	mode = (typeof obj['mode'] == "undefined" ? "" : obj['mode']);
	parameters = (typeof obj['parameters'] == "undefined" ? "" : obj['parameters']);

	top.weEditorFrameController.openDocument(table,id,ct,editcmd,dt,url,code,mode,parameters);

}

