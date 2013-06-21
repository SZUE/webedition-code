<?php

/**
 * webEdition CMS
 *
 * $Rev: 6180 $
 * $Author: lukasimhof $
 * $Date: 2013-06-07 17:26:34 +0200 (Fr, 07 Jun 2013) $
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
class weBannerFrames extends weModuleFrames{

	var $edit_cmd = "edit_banner";

	protected $useMainTree = false;

	function __construct($frameset){
		parent::__construct($frameset);
		$this->module = "banner";
		$this->treeDefaultWidth = 224;
		$this->View = new weBannerView();
	}

	function getHTML($what = '', $mode = ''){
		switch($what){
			case "edheader":
				print $this->getHTMLEditorHeader($mode);
				break;
			case "edfooter":
				print $this->getHTMLEditorFooter($mode);
				break;
			default:
				parent::getHTML($what);
		}
	}

	function getHTMLFrameset(){
		$extraHead = $this->getJSTreeCode();
		return parent::getHTMLFrameset($extraHead);
	}

	function getHTMLLeftDiv(){//TODO: $loadMainTree entfaellt, sobald trees einheitlich sind
		return parent::getHTMLLeftDiv(false);
	}

	function getHTMLEditor(){

		return parent::getHTMLEditor('&home=1');
	}

	function getJSTreeCode(){//TODO: move (as in all modules...) to some future moduleTree class
		//start of code from ex class weModuleBannerFrames
		print we_html_element::jsScript(JS_DIR . 'images.js') .
		we_html_element::jsScript(JS_DIR . 'windows.js') .
		we_html_element::jsScript(JS_DIR . 'md5.js');
		?>
		<script type="text/javascript"><!--

			var loaded=0;
			var hot=0;
			var hloaded=0;

			function setHot(){
				hot=1;
			}

			function usetHot(){
				hot=0;
			}

			var menuDaten = new container();var count = 0;var folder=0;
			var table="<?php print BANNER_TABLE; ?>";

			function drawEintraege(){
				fr = top.content.tree.document;
				fr.open();
				fr.writeln("<html><head>");
				fr.writeln("<script type=\"text/javascript\">");
				fr.writeln("clickCount=0;");
				fr.writeln("wasdblclick=0;");
				fr.writeln("tout=null");
				fr.writeln("function doClick(id,ct,table){");
				//fr.writeln("if(ct=='folder') top.content.we_cmd('edit_newsletter',id,ct,table); else if(ct=='file') top.content.we_cmd('show_document',id,ct,table);");
				fr.writeln("top.content.we_cmd('<?php print $this->edit_cmd; ?>',id,ct,table);");
				fr.writeln("}");
				fr.writeln("top.content.loaded=1;");
				fr.writeln("</"+"script>");
				fr.writeln('<?php print STYLESHEET_SCRIPT; ?>');
				fr.write("</head>\n");
				fr.write("<body bgcolor=\"#F3F7FF\" link=\"#000000\" alink=\"#000000\" vlink=\"#000000\" leftmargin=5 topmargin=5 marginheight=5 marginwidth=5>\n");
				fr.write("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td class=\"tree\">\n<nobr>\n");
				zeichne(top.content.startloc,"");
				fr.write("</nobr>\n</td></tr></table>\n");
				fr.write("</body>\n</html>");
				fr.close();
			}


			function zeichne(startEntry,zweigEintrag){
				var nf = search(startEntry);
				var ai = 1;
				while (ai <= nf.laenge) {
					fr.write(zweigEintrag);

					if (nf[ai].typ == 'file') {
						if(ai == nf.laenge) fr.write("&nbsp;&nbsp;<img src=<?php print TREE_IMAGE_DIR; ?>kreuzungend.gif width=19 height=18 align=absmiddle border=0>");
						else fr.write("&nbsp;&nbsp;<img src=<?php print TREE_IMAGE_DIR; ?>kreuzung.gif width=19 height=18 align=absmiddle border=0>");
						if(nf[ai].name != -1){
							fr.write("<a name='_"+nf[ai].name+"' href=\"javascript://\" onClick=\"doClick("+nf[ai].name+",'"+nf[ai].contentType+"','"+nf[ai].table+"');return true;\" BORDER=0>");
						}
						fr.write("<IMG SRC=<?php print TREE_IMAGE_DIR; ?>icons/"+nf[ai].icon+" WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 alt=\"<?php #print g_l('tree',"[edit_statustext]");       ?>\">");
						fr.write("</a>");
						fr.write("&nbsp;<a name='_"+nf[ai].name+"' href=\"javascript://\" onClick=\"doClick("+nf[ai].name+",'"+nf[ai].contentType+"','"+nf[ai].table+"');return true;\">"+(parseInt(nf[ai].published) ? "" : "")+ nf[ai].text +(parseInt(nf[ai].published) ? "" : "")+ "</A>&nbsp;&nbsp;<BR>\n");
					}else{
						var newAst = zweigEintrag;

						var zusatz = (ai == nf.laenge) ? "end" : "";

						if (nf[ai].offen == 0){
							fr.write("&nbsp;&nbsp;<A href=\"javascript:top.content.openClose('" + nf[ai].name + "',1)\" BORDER=0><IMG SRC=<?php print TREE_IMAGE_DIR; ?>auf"+zusatz+".gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0 Alt=\"<?php #print g_l('tree',"[open_statustext]")       ?>\"></A>");
							var zusatz2 = "";
						}else{
							fr.write("&nbsp;&nbsp;<A href=\"javascript:top.content.openClose('" + nf[ai].name + "',0)\" BORDER=0><IMG SRC=<?php print TREE_IMAGE_DIR; ?>zu"+zusatz+".gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0 Alt=\"<?php #print g_l('tree',"[close_statustext]")       ?>\"></A>");
							var zusatz2 = "open";
						}
						fr.write("<a name='_"+nf[ai].name+"' href=\"javascript://\" onClick=\"doClick("+nf[ai].name+",'"+nf[ai].contentType+"','"+nf[ai].table+"');return true;\" BORDER=0>");
						fr.write("<IMG SRC=<?php print TREE_IMAGE_DIR; ?>icons/"+nf[ai].icon.replace(/\.gif/,"")+zusatz2+".gif WIDTH=16 HEIGHT=18 align=absmiddle BORDER=0 Alt=\"<?php #print g_l('tree',"[edit_statustext]");       ?>\">");
						fr.write("</a>");
						fr.write("<A name='_"+nf[ai].name+"' HREF=\"javascript://\" onClick=\"doClick("+nf[ai].name+",'"+nf[ai].contentType+"','"+nf[ai].table+"');return true;\">");
						fr.write("&nbsp;<b>" + nf[ai].text + "</b>");
						fr.write("</a>");
						fr.write("&nbsp;&nbsp;<BR>\n");
						if (nf[ai].offen){
							if(ai == nf.laenge) newAst = newAst + "<IMG SRC=<?php print TREE_IMAGE_DIR; ?>leer.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>";
							else newAst = newAst + "<IMG SRC=<?php print TREE_IMAGE_DIR; ?>strich2.gif WIDTH=19 HEIGHT=18 align=absmiddle BORDER=0>";
							zeichne(nf[ai].name,newAst);
						}
					}
					ai++;
				}
			}


			function makeNewEntry(icon,id,pid,txt,offen,ct,tab,pub){
				if(ct=="folder")
					menuDaten.addSort(new dirEntry(icon,id,pid,txt,offen,ct,tab,pub));
				else
					menuDaten.addSort(new urlEntry(icon,id,pid,txt,ct,tab,pub));
				drawEintraege();
			}

			function updateEntry(id,pid,text,pub){
				var ai = 1;
				while (ai <= menuDaten.laenge) {
					if (menuDaten[ai].name==id) {
						menuDaten[ai].vorfahr=pid;
						menuDaten[ai].text=text;
						menuDaten[ai].published=1;
					}
					ai++;
				}
				drawEintraege();
			}

			function deleteEntry(id,type){
				var ai = 1;
				var ind=0;
				while (ai <= menuDaten.laenge) {
					if ((menuDaten[ai].typ==type))
						if (menuDaten[ai].name==id) {
							ind=ai;
							break;
						}
					ai++;
				}
				if(ind!=0){
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

			function openClose(name,status){
				var eintragsIndex = indexOfEntry(name);
				menuDaten[eintragsIndex].offen = status;
				if(status){
					if(!menuDaten[eintragsIndex].loaded){
						drawEintraege();
					}else{
						drawEintraege();
					}
				}else{
					drawEintraege();
				}
			}

			function indexOfEntry(name){var ai = 1;while (ai <= menuDaten.laenge) {if ((menuDaten[ai].typ == 'root') || (menuDaten[ai].typ == 'folder'))if (menuDaten[ai].name == name) return ai;ai++;}return -1;}

			function search(eintrag){var nf = new container();var ai = 1;while (ai <= menuDaten.laenge) {if ((menuDaten[ai].typ == 'folder') || (menuDaten[ai].typ == 'file'))if (menuDaten[ai].vorfahr == eintrag) nf.add(menuDaten[ai]);ai++;}return nf;}

			function container(){this.laenge = 0;this.clear=containerClear;this.add = add;this.addSort = addSort;return this;}

			function add(object){this.laenge++;this[this.laenge] = object;}

			function containerClear(){this.laenge =0;}

			function addSort(object){this.laenge++;for(var i=this.laenge; i>0; i--){if(i > 1 && this[i-1].text.toLowerCase() > object.text.toLowerCase() ){this[i] = this[i-1];}else{this[i] = object;break;}}}

			function rootEntry(name,text,rootstat){this.name = name;this.text = text;this.loaded=true;this.typ = 'root';this.rootstat = rootstat;return this;}

			function dirEntry(icon,name,vorfahr,text,offen,contentType,table,published){this.icon=icon;this.name = name;this.vorfahr = vorfahr;this.text = text;this.typ = 'folder';this.offen = (offen ? 1 : 0);this.contentType = contentType;this.table = table;this.loaded = (offen ? 1 : 0);this.checked = false;this.published = published;return this;}

			function urlEntry(icon,name,vorfahr,text,contentType,table,published){this.icon=icon;this.name = name;this.vorfahr = vorfahr;this.text = text;this.typ = 'file';this.checked = false;this.contentType = contentType;this.table = table;this.published = published;return this;}

			function start(){loadData();drawEintraege();}

			var startloc=0;

			self.focus();
			//-->
		</script>
		<?php
		//end of code from ex class weModuleBannerFrames

		$startloc = 0;

		$out = '
		function loadData(){
			menuDaten.clear();
			startloc=' . $startloc . ';';

		$this->db->query('SELECT ID,ParentID,Path,Text,Icon,IsFolder,ABS(text) as Nr, (text REGEXP "^[0-9]") as isNr FROM ' . BANNER_TABLE . ' ORDER BY isNr DESC,Nr,Text');
		while($this->db->next_record()) {
			$ID = $this->db->f("ID");
			$ParentID = $this->db->f("ParentID");
			$Path = $this->db->f("Path");
			$Text = addslashes($this->db->f("Text"));
			$Icon = $this->db->f("Icon");
			$IsFolder = $this->db->f("IsFolder");

			$out.=($IsFolder ?
					"  menuDaten.add(new dirEntry('" . $Icon . "','" . $ID . "','" . $ParentID . "','" . $Text . "',0,'folder','" . BANNER_TABLE . "',1));" :
					"  menuDaten.add(new urlEntry('" . $Icon . "','" . $ID . "','" . $ParentID . "','" . $Text . "','file','" . BANNER_TABLE . "',1));");
		}

		$out.='}';
		print we_html_element::jsElement($out);
	}

	function getJSCmdCode(){
		print $this->View->getJSTopCode();
	}

	function getHTMLEditorHeader($mode = 0){
		if(isset($_REQUEST["home"])){
			return '<body bgcolor="#F0EFF0"></body></html>';
		}
		$isFolder = 0;
		if(isset($_GET["isFolder"]))
			$isFolder = $_GET["isFolder"];

		$page = 0;
		if(isset($_GET["page"]))
			$page = $_GET["page"];

		$headline1 = ($isFolder == 1) ? g_l('modules_banner', '[group]') : g_l('modules_banner', '[banner]');
		$text = "" . ($isFolder == 1) ? g_l('modules_banner', '[newbannergroup]') : g_l('modules_banner', '[newbanner]');
		if(isset($_GET["txt"]))
			$text = $_GET["txt"];

		$we_tabs = new we_tabs();

		if($isFolder == 0){
			$we_tabs->addTab(new we_tab("#", g_l('tabs', "[module][properties]"), ($page == 0 ? "TAB_ACTIVE" : "TAB_NORMAL"), "setTab(0);"));
			$we_tabs->addTab(new we_tab("#", g_l('tabs', "[module][placement]"), ($page == 1 ? "TAB_ACTIVE" : "TAB_NORMAL"), "setTab(1);"));
			$we_tabs->addTab(new we_tab("#", g_l('tabs', "[module][statistics]"), ($page == 2 ? "TAB_ACTIVE" : "TAB_NORMAL"), "setTab(2);"));
		} else{

			$we_tabs->addTab(new we_tab("#", g_l('tabs', "[module][properties]"), "TAB_ACTIVE", "setTab(0);"));
		}

		$we_tabs->onResize('header');
		$tab_head = $we_tabs->getHeader();
		$tab_body = $we_tabs->getJS();

		$extraHead =
			$tab_head .
			we_html_element::jsElement('
				function setTab(tab){
					switch(tab){
						case ' . weBanner::PAGE_PROPERTY . ':
						case ' . weBanner::PAGE_PLACEMENT . ':
						case ' . weBanner::PAGE_STATISTICS . ':
							top.content.right.editor.edbody.we_cmd("switchPage",tab);
							break;
					}
				}
				top.content.hloaded=1;
			');

		//TODO: we have the following body in several modules!
		$body = we_html_element::htmlBody(array('bgcolor' => 'white', 'background' => IMAGE_DIR . 'backgrounds/header_with_black_line.gif', 'marginwidth' => 0, 'marginheight' => 0, 'leftmargin' => 0, 'topmargin' => 0, 'onload' => 'setFrameSize()', 'onresize' => 'setFrameSize()'),
			we_html_element::htmlDiv(array('id' => 'main'),
				we_html_tools::getPixel(100, 3) .
				we_html_element::htmlDiv(array('style' => 'margin:0px;padding-left:10px;', 'id' => 'headrow'),
					we_html_element::htmlNobr(
						we_html_element::htmlB(str_replace(" ", "&nbsp;", $headline1) . ':&nbsp;') .
						we_html_element::htmlSpan(array('id' => 'h_path', 'class' => 'header_small'),
							'<b id="titlePath">' . str_replace(" ", "&nbsp;", $text) . '</b>'
						)
					)
				) .
				we_html_tools::getPixel(100, 3) .
				$we_tabs->getHTML()
			)
		);

		return $this->getHTMLDocument($body, $extraHead);
	}

	function getHTMLEditorFooter($mode = 0){//TODO: make $extraHeader, $body and use $this->getHTMLDocument($body, $extraHead);
		if(isset($_REQUEST["home"])){
			return '<body bgcolor="#F0EFF0"></body></html>';
		}

		we_html_tools::htmlTop();
		print STYLESHEET;

		$this->View->getJSFooterCode();

		$extraHead = $this->View->getJSFooterCode() . we_html_element::jsElement('
			function sprintf(){
				if (!arguments || arguments.length < 1) return;

				var argum = arguments[0];
				var regex = /([^%]*)%(%|d|s)(.*)/;
				var arr = new Array();
				var iterator = 0;
				var matches = 0;

				while (arr=regex.exec(argum)){
					var left = arr[1];
					var type = arr[2];
					var right = arr[3];

					matches++;
					iterator++;

					var replace = arguments[iterator];

					if (type=="d") replace = parseInt(param) ? parseInt(param) : 0;
					else if (type=="s") replace = arguments[iterator];
					argum = left + replace + right;
				}
				return argum;
			}

			function we_save() {
				var acLoopCount=0;
				var acIsRunning = false;
				if(!!top.content.right.editor.edbody.YAHOO && !!top.content.right.editor.edbody.YAHOO.autocoml){
					while(acLoopCount<20 && top.content.right.editor.edbody.YAHOO.autocoml.isRunnigProcess()){
						acLoopCount++;
						acIsRunning = true;
						setTimeout("we_save()",100);
					}
					if(!acIsRunning) {
						if(top.content.right.editor.edbody.YAHOO.autocoml.isValid()) {
							_we_save();
						} else {
							' . we_message_reporting::getShowMessageCall(g_l('alert', '[save_error_fields_value_not_valid]'), we_message_reporting::WE_MESSAGE_ERROR) . '
						}
					}
				} else {
					_we_save();
				}
			}
		');

		return parent::getHTMLEditorFooter('save_banner', $extraHead);
	}

	function getHTMLCmd(){
		$extraHead = $this->View->getJSCmd();

		$body = we_html_element::htmlBody(array(),
			we_html_element::htmlForm(array(),
				$this->View->htmlHidden("ncmd", "") .
				$this->View->htmlHidden("nopt", "")
			)
		);

		return $this->getHTMLDocument($body, $extraHead);
	}

	function getHTMLDCheck(){

		return $this->getHTMLDocument(we_html_element::htmlBody(array(), $this->View->getHTMLDCheck()), we_html_element::jsElement('self.focus();'));
	}
}