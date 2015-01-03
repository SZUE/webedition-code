/**
 * webEdition CMS
 *
 * webEdition CMS
 * $Rev: 8784 $
 * $Author: mokraemer $
 * $Date: 2014-12-18 13:57:57 +0100 (Do, 18. Dez 2014) $
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

function _getIcon(contentType, extension) {
	if (contentType === contentTypeApp) {
		switch (extension.toLowerCase()) {
			case '.pdf' :
				return 'pdf.gif';
			case '.zip' :
			case '.sit' :
			case '.hqx' :
			case '.bin' :
				return 'zip.gif';
			case '.odt':
			case '.ott':
			case '.dot' :
			case '.doc' :
				return 'word.gif';
			case '.ods':
			case '.ots':
			case '.xlt' :
			case '.xls' :
				return 'excel.gif';
			case '.odp':
			case '.otp':
			case '.ppt' :
				return 'powerpoint.gif';
			case '.odg':
			case '.otg':
				return 'odg.gif';
		}
		return "prog.gif";
	} else {
		tmp = _Contentypes[contentType];
		if (tmp == undefined) {
			return "prog.gif";
		}
		return _Contentypes[contentType];
	}
}

// fits the frame height on resize, add or remove tabs if the tabs wrap
function setFrameSize() {
	tabsHeight = (document.getElementById('tabContainer').clientHeight ? (document.getElementById('tabContainer').clientHeight + heightPlus) : (document.body.clientHeight + heightPlus));
	tabsHeight = tabsHeight < 22 ? 22 : tabsHeight;
	document.getElementById('multiEditorDocumentTabsFrameDiv').style.height = tabsHeight + "px";
	document.getElementById('multiEditorEditorFramesetsDiv').style.top = tabsHeight + "px";
}

/**
 * class declaration
 * the class TabView controls the behaviort of the tabs
 * onload a instance of this class is created
 */
TabView = function (myDoc) {
	this.myDoc = myDoc;
	this.init();
}
/**
 * class TabView methods and properties
 */
TabView.prototype = {
	/**
	 * if a tab for the given frameId exists, it will be selected
	 * if not if will be added
	 */
	openTab: function (frameId, text, title) {
		if (this.myDoc.getElementById("tab_" + frameId) == undefined) {
			this.addTab(frameId, text, title);
		} else {
			this.selectTab(frameId);
		}
	},
	/**
	 * adds an new tab to the tab view
	 */
	addTab: function (frameId, text, title) {
		newtab = this.tabDummy.cloneNode(true);
		newtab.innerHTML = newtab.innerHTML.replace(/###tabTextId###/g, "text_" + frameId);
		newtab.innerHTML = newtab.innerHTML.replace(/###modId###/g, "mod_" + frameId);
		newtab.innerHTML = newtab.innerHTML.replace(/###loadId###/g, "load_" + frameId);
		newtab.innerHTML = newtab.innerHTML.replace(/###closeId###/g, "close_" + frameId);
		newtab.id = "tab_" + frameId;
		newtab.name = "tab";
		newtab.title = title;
		newtab.className = "tabActive";
		this.tabContainer.appendChild(newtab);
		this.setText(frameId, text);
		this.setTitle(frameId, title);
		this.selectTab(frameId);
		setTimeout("setFrameSize()", 100);
	},
	/**
	 * controls the click on the close button
	 */
	onCloseTab: function (val) {
		frameId = (typeof val) == "object" ? val.id.replace(/close_/g, "") : val;
		top.weEditorFrameController.closeDocument(frameId);
	},
	/**
	 * removes a tab from the tab view
	 */
	closeTab: function (frameId) {
		this.tabContainer.removeChild(this.myDoc.getElementById('tab_' + frameId));
		if (this.activeTab == frameId) {
			this.activeTab = null;
		}
		setFrameSize();
		this.contentType[frameId] = "";
	},
	/**
	 * selects a tab (set style for selected tabs)
	 */
	selectTab: function (frameId) {
		this.deselectAll();
		if (this.activeTab != null) {
			this.deselectTab(this.activeTab);
		}
		if (this.myDoc.getElementById('tab_' + frameId) && typeof (this.myDoc.getElementById('tab_' + frameId)) == "object") {
			this.myDoc.getElementById('tab_' + frameId).className = 'tabActive';
		}
		this.activeTab = frameId;
	},
	/**
	 * deselects a tab (set style for deselected tabs)
	 */
	deselectTab: function (frameId) {
		if (this.myDoc.getElementById('tab_' + frameId)) {
			this.myDoc.getElementById('tab_' + frameId).className = "tab";
		}
	},
	/**
	 * deselects all tab (set style for deselected tabs to all tabs)
	 */
	deselectAll: function () {
		tabs = this.myDoc.getElementsByName("tab");
		for (i = 0; tabs.length; i++) {
			tabs[i].className = "tab";
		}
	},
	/**
	 * sets the tab label
	 */
	setText: function (frameId, val) {
		text = this.myDoc.getElementById('text_' + frameId);
		if (text) {
			text.innerHTML = val;
		}
		setTimeout("setFrameSize()", 50);
	},
	/**
	 * sets the tab title
	 */
	setTitle: function (frameId, val) {
		title = this.myDoc.getElementById('tab_' + frameId);
		if (title) {
			title.title = val;
		}
	},
	/**
	 * sets the id to the icon
	 */
	setId: function (frameId, val) {
		this.myDoc.getElementById('load_' + frameId).title = val;
	},
	/**
	 * marks a tab as modified an not safed
	 */
	setModified: function (frameId, modified) {
		this.myDoc.getElementById('mod_' + frameId).src = (modified ?
						"/webEdition/images/multiTabs/modified.gif" :
						"/webEdition/images/pixel.gif");
	},
	/**
	 * displays the loading loading icon
	 */
	setLoading: function (frameId, loading) {
		if (loading) {
			this.myDoc.getElementById('load_' + frameId).style.backgroundImage = "url(/webEdition/images/spinner.gif)";
		} else if (_Contentypes[this.contentType[frameId]]) {
			var _text = this.myDoc.getElementById('text_' + frameId).innerHTML;
			var _ext = _text ? _text.replace(/^.*\./, ".") : "";
			this.myDoc.getElementById('load_' + frameId).style.backgroundImage = "url(/webEdition/images/tree/icons/" + _getIcon(this.contentType[frameId], _ext) + ")";
		} else {
			this.myDoc.getElementById('load_' + frameId).style.backgroundImage = "url(/webEdition/images/pixel.gif)";
		}

	},
	/**
	 * displays the content type icon
	 */
	setContentType: function (frameId, contentType) {
		this.contentType[frameId] = contentType;
		this.setLoading(frameId, false);
	},
	/**
	 * controls the click on a tab
	 */
	selectFrame: function (val) {
		frameId = (typeof val) == "object" ? val.id.replace(/tab_/g, "") : val;
		top.weEditorFrameController.showEditor(frameId);
		//this.selectTab(frameId);
	},
	/**
	 * inits some vars
	 */
	init: function () {
		this.tabs = new Array();
		this.frames = new Array();
		this.activeTab = null;
		this.tabContainer = this.myDoc.getElementById('tabContainer');
		this.tabDummy = this.myDoc.getElementById('tabDummy');
		this.contentType = new Array();
	}
}
/**
 * document init
 */
function init() {
	top.weMultiTabs = new TabView(this.document);
}
