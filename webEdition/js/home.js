/* global WE, top */

/**
 * webEdition CMS
 *
 * webEdition CMS
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

var _propsDlg = [];

var Base64 = {
	// private property
	_keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
	// public method for encoding
	encode: function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
							this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
							this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},
	// public method for decoding
	decode: function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 !== 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 !== 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},
	// private method for UTF-8 encoding
	_utf8_encode: function (string) {
		string = string.replace(/\r\n/g, "\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if ((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			} else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},
	// private method for UTF-8 decoding
	_utf8_decode: function (utftext) {
		var string = "";
		var i = 0;
		var c = c2 = 0;

		while (i < utftext.length) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if ((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i + 1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			} else {
				c2 = utftext.charCodeAt(i + 1);
				c3 = utftext.charCodeAt(i + 2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}

};

function addCss() {
	jsCss = '<style>';
	for (i = 1; i <= 10; i++) {
		jsCss += '.cls_' + i + '_collapse{width:' + oCfg.general_.cls_collapse + 'px;vertical-align:top;}' +
						'.cls_' + i + '_expand{width:' + oCfg.general_.cls_expand + 'px;vertical-align:top;}';
	}
	jsCss += '</style>';
	document.write(jsCss);
}

function getColumnAsoc(id) {
	var oNode = document.getElementById(id);
	var iNodeLen = oNode.childNodes.length;
	var aNodeSet = [];
	var k = 0;
	for (var i = 0; i < iNodeLen; i++) {
		var oChild = oNode.childNodes[i];
		if (oChild.className === 'le_widget') {
			var sAttrId = oChild.getAttribute('id');
			aNodeSet[k] = {
				'type': document.getElementById(sAttrId + '_type').value,
				'cls': document.getElementById(sAttrId + '_cls').value,
				'res': document.getElementById(sAttrId + '_res').value,
				'csv': document.getElementById(sAttrId + '_csv').value,
				'id': sAttrId
			};
			k++;
		}
	}
	return aNodeSet;
}

function getWidgetProps(p) {
	var attr_id, node, child, i, j, oProps = {};
	for (i = 1; i <= _iLayoutCols; i++) {
		node = document.getElementById('c_' + i);
		if (node === null) {
			continue;
		}
		for (j = 0; j < node.childNodes.length; j++) {
			child = node.childNodes[j];
			if (child.className === 'le_widget') {
				attr_id = child.getAttribute('id');
				oProps[attr_id] = document.getElementById(attr_id + '_' + p).value;
			}
		}
	}
	oProps.length = node.childNodes.length;
	return oProps;
}

function modifyLayoutCols(iCols) {
	var i;
	if (iCols > _iLayoutCols) {
		var iAppendCols = iCols - _iLayoutCols;
		var oTbl = document.getElementById('le_tblWidgets');
		var oRow = document.getElementById('rowWidgets');
		for (i = 1; i <= iAppendCols; i++) {
			var oCell = document.createElement('TD');
			oCell.setAttribute('id', 'c_' + (_iLayoutCols + i));
			oCell.setAttribute('class', 'cls_' + (_iLayoutCols + i) + '_collapse');
			var oWildcard = document.createElement('DIV');
			oWildcard.setAttribute('class', 'wildcard');
			oWildcard.setAttribute('style', 'margin-rigth:5px');
			oCell.appendChild(oWildcard);
			oRow.appendChild(oCell);
		}
		_iLayoutCols += iAppendCols;
		le_dragInit(oTbl);
	} else {
		var iRemoveCols = _iLayoutCols - iCols;
		var k = parseInt(iCols) + 1;
		while (k <= _iLayoutCols) {
			var aSoc = getColumnAsoc('c_' + k);
			var aSocLen = aSoc.length;
			for (i = 0; i < aSocLen; i++) {
				createWidget(aSoc[i].type, 0, iCols);
			}
			k++;
		}
		for (i = _iLayoutCols; i > iCols; i--) {
			var asoc = getColumnAsoc('c_' + i);
			for (var j = 0; j < asoc.length; j++) {
				document.getElementById(asoc[j].id).parentNode.removeChild(document.getElementById(asoc[j].id));
			}
			var oRemoveCol = document.getElementById('c_' + i);
			oRemoveCol.parentNode.removeChild(oRemoveCol);
		}
		_iLayoutCols -= iRemoveCols;
		le_dragInit(document.getElementById('le_tblWidgets'));
	}
}

function setPrefs(_pid, sBit, sTitleEnc) {
	var iframeEl = document.getElementById(_pid + "_inline");
	var iframeWin;
	if (iframeEl.contentWindow) {
		iframeWin = iframeEl.contentWindow;
	} else if (iframeEl.contentDocument) { // Dom Level 2
		iframeWin = iframeEl.contentDocument.defaultView;
	} else {  // Safari
		iframeWin = frames.we_wysiwyg_lng_frame;
	}

	iframeWin._sInitProps = sBit;
	iframeWin._ttlB64Esc = sTitleEnc;
}

function findInArray(arrayToSearch, searchValue, optionalMatchFn) {
	var retVal = -1;
	for (var i = 0; i < arrayToSearch.length; i++) {
		if (optionalMatchFn !== null && optionalMatchFn !== undefined) {
			if (optionalMatchFn(arrayToSearch[i], searchValue)) {
				retVal = i;
				break;
			}
		} else {
			if (arrayToSearch[i] === searchValue) {
				retVal = i;
				break;
			}
		}
	}
	return retVal;
}

function saveSettings() {
	var aDat = [];
	for (var i = 0; i < _iLayoutCols; i++) {
		var aSoc = getColumnAsoc('c_' + (i + 1));
		aDat[i] = [];
		for (var iPos in aSoc) {
			aDat[i][iPos] = [];
			var aRef = ['type', 'cls', 'res', 'csv'];
			for (var tp in aSoc[iPos]) {
				var idx = findInArray(aRef, tp);
				if (idx > -1) {
					aDat[i][iPos][idx] = aSoc[iPos][tp];
				}
			}
		}
	}
	rss = [];
	var topRssFeedsLen = _trf.length;
	for (i = 0; i < topRssFeedsLen; i++) {
		rss[i] = [_trf[i][0], _trf[i][1]];
	}
	if (_bDgSave) {
		var sDg = '';
		for (i = 0; i < aDat.length; i++) {
			sDg += i + ":\n";
			for (var j = 0; j < aDat[i].length; j++) {
				sDg += "\t" + aDat[i][j] + "\n";
			}
		}
// interne Meldung - debug
		console.log(sDg);
	}

	fo = self.document.forms.we_form;
	fo.elements['we_cmd[1]'].value = JSON.stringify(aDat);
	fo.elements['we_cmd[2]'].value = JSON.stringify(rss);
	top.YAHOO.util.Connect.setForm(fo);
	var cObj = top.YAHOO.util.Connect.asyncRequest('POST', WE().consts.dirs.WE_INCLUDES_DIR + 'we_widgets/cmd.php', function () {
	});
}

function hasExpandedWidget(node) {
	for (var i = 0; i < node.childNodes.length; i++) {
		var currentChild = node.childNodes[i];
		if (currentChild.className === 'le_widget') {
			if (document.getElementById(currentChild.getAttribute('id') + '_res').value === "1") {
				return true;
			}
		}
	}
	return false;
}

function jsStyleCls(evt, obj, cls1, cls2) {
	switch (evt) {
		case 'swap':
			obj.className = !obj.classList.contains(cls1) ? obj.className.replace(cls2, cls1) : obj.className.replace(cls1, cls2);
			break;
		case 'verify':
			return obj.classList.contains(cls1);
	}
}

function updateJsStyleCls() {
	for (var i = 1; i <= _iLayoutCols; i++) {
		var oCol = document.getElementById('c_' + i);
		if (hasExpandedWidget(oCol)) {
			cls1 = 'cls_' + i + '_expand';
			cls2 = 'cls_' + i + '_collapse';
		} else {
			cls1 = 'cls_' + i + '_collapse';
			cls2 = 'cls_' + i + '_expand';
		}
		if (!oCol.classList.contains(cls1)) {
			if (oCol.classList.contains(cls2)) {
				jsStyleCls('swap', oCol, cls2, cls1);
			} else {
				oCol.classList.add(cls1);
			}
		}
	}
}

function getLabel(id) {
	return document.getElementById(id + '_prefix').value + document.getElementById(id + '_postfix').value;
}

function setLabel(id, prefix, postfix) {
	var el_label = document.getElementById(id + '_lbl');
	var w = parseInt(el_label.style.width);
	var suspensionPts = '';
	if (prefix === undefined || postfix === undefined) {
		label = getLabel(id);
	} else {
		label = prefix + postfix;
		label = label.replace(/\[\[/g, "<");
		label = label.replace(/\]\]/g, ">");
	}
	if (label.indexOf("<span") === -1) {
		while (getDimension(label + suspensionPts, 'label').width + 10 > w) {
			label = label.substring(0, label.length - 1);
			suspensionPts = '&hellip;';
		}
	}
	el_label.innerHTML = label + suspensionPts;
}

function setWidth(id, w) {
	document.getElementById(id).style.width = w + 'px';
}

function setWidgetWidth(id, w) {
	setWidth(id + "_bx", w);
}


function resizeWidget(id) {
	var _type = document.getElementById(id + '_type').value;
	var w = (resizeIdx('get', id) === "0") ? oCfg.general_.w_expand : oCfg.general_.w_collapse;
	resizeIdx('swap', id);
	setWidgetWidth(id, w);
	document.getElementById(id + '_lbl').innerHTML = '';
	setLabel(id);
	updateJsStyleCls();
	initWidget(id); // resize widget, etc.

}

function initWidget(_id) {
	var oNode = document.getElementById(_id + '_type');
	if (oNode && oNode.value === "sct") {
		var _width = "100%";
		if (resizeIdx('get', _id) === "1") {
			_width = "48%";
		}

		var _elem = document.getElementById(_id);
		var _inlineDivs = _elem.getElementsByTagName('div');
		for (i = 0; i < _inlineDivs.length; i++) {
			if (_inlineDivs[i].className === "sct_row") {
				_inlineDivs[i].style.width = _width;
			}
		}
	}
}

function setTheme(wizId, wizTheme) {
	var objs = [document.getElementById(wizId + '_bx')];
	var clsElement = document.getElementById(wizId + '_cls');
	var replaceClsName = clsElement.value;
	var o;
	clsElement.value = wizTheme;
	for (o in objs) {
		if (objs[o].classList.contains("bgc_" + replaceClsName)) {
			objs[o].classList.remove("bgc_" + replaceClsName);
			objs[o].classList.add('bgc_' + wizTheme);
		}
	}
}

function setOpacity(sId, degree) {
	var obj = document.getElementById(sId);
	obj.style.opacity = (degree / 100);
	obj.style.MozOpacity = (degree / 100);
	obj.style.KhtmlOpacity = (degree / 100);
	obj.style.filter = 'alpha(opacity=' + degree + ')';
}

function fadeTrans(wizId, start, end, ms) {
	var v = Math.round(ms / 100);
	var t = 0;
	if (start > end) {
		for (i = start; i >= end; i--) {
			var obj = document.getElementById(wizId);
			setTimeout(setOpacity, (t * v), wizId, i);
			t++;
		}
	} else if (start < end) {
		for (i = start; i <= end; i++) {
			setTimeout(setOpacity, (t * v), wizId, i);
			t++;
		}
	}
}

function toggle(wizId, wizType, prefix, postfix) {
	var defRes = oCfg[wizType + '_props_'].res;
	var defW = (defRes !== undefined) ? oCfg.general_.w_expand : oCfg.general_.w_collapse;
	var asoc = {
		'width': {
			'_inline': defW,
			'_bx': defW + (2 * oCfg.general_.wh_edge)
		}
	};
	var props = {
		'prefix': prefix, 'postfix': postfix, 'type': wizType, 'res': defRes
	};
	for (var att_name in asoc) {
		for (var v in asoc[att_name]) {
			document.getElementById(wizId + v).style[att_name] = asoc[att_name][v] + "px";
		}
	}
	for (var p in props) {
		document.getElementById(wizId + '_' + p).value = props[p];
	}
	if (defRes === "1" && !document.getElementById('c_1').classList.contains('cls_1_expand')) {
		updateJsStyleCls();
	}
}

function pushContent(wizType, wizId, cNode, prefix, postfix, sCsv) {
	var cNodeReceptor = document.getElementById(wizId + '_content');
	var wizTheme = oCfg[wizType + "_props_"].cls;
	cNodeReceptor.innerHTML = cNode;
	document.getElementById(wizId + '_csv').value = sCsv;
	toggle(wizId, wizType, prefix, postfix);
	setLabel(wizId);
	if (wizTheme !== 'white') {
		setTheme(wizId, wizTheme);
	}
	document.getElementById(wizId).style.display = 'block';
	if (oCfg.blend_.fadeIn !== undefined) {
		fadeTrans(wizId, 0, 100, oCfg.blend_.v);
	}
}

function createWidget(typ, row, col) {
// for IE
	if (typ === 'pad') {
		document.getElementById('c_' + col).className = 'cls_' + col + '_expand';
	}
//EOF for IE
	var domNode = document.getElementById('c_' + col);
	var asoc = getColumnAsoc('c_' + col);
	var properties = getWidgetProps('type');
	var idx = properties.length /*+ 1*/;
	while (document.getElementById('m_' + idx) !== null) {
		idx++;
	}
	var new_id = 'm_' + idx;
	var cloneSampleId = 'divClone';
	for (var currentId in properties) {
		if (properties[currentId] == typ) {
			cloneSampleId = currentId;
			break;
		}
	}
	var nodeToClone = document.getElementById(cloneSampleId);
	var regex = cloneSampleId;
	var re = new RegExp(((cloneSampleId === 'divClone') ? new_id + '|clone' : cloneSampleId), 'g');
	var sClonedNode = nodeToClone.innerHTML.replace(re, new_id);
	if (cloneSampleId === 'divClone') {
		sClonedNode = sClonedNode.replace(/_reCloneType_/g, typ);
	}
	var divClone = document.createElement('DIV');
	divClone.setAttribute('id', new_id);
	divClone.setAttribute('class', 'le_widget');
	divClone.className = 'le_widget'; // for IE
	divClone.innerHTML = sClonedNode;
	if (oCfg.blend_.fadeIn !== undefined) {
		divClone.style.display = 'none';
	}
	if (asoc.length && row) {
		domNode.insertBefore(divClone, document.getElementById(asoc[row - 1].id));
	} else { // add to empty col - before wildcard!
		var _td = document.getElementById("c_" + col);
		_td.insertBefore(
						divClone,
						_td.childNodes[0]
						);
	}
	if (findInArray(_noResizeTypes, typ) > -1) {
		var oPrc = document.getElementById(new_id + '_ico_prc');
		var oPc = document.getElementById(new_id + '_ico_pc');
		if (oPrc) {
			oPrc.parentNode.removeChild(oPrc);
		}
		if (oPc) {
			oPc.style.display = 'block';
		}
	}
	if (oCfg.blend_.fadeIn !== undefined) {
		setOpacity(divClone.id, 0);
	}
	if (cloneSampleId !== 'divClone') {
		divClone.style.display = 'block';
		if (oCfg.blend_.fadeIn !== undefined) {
			fadeTrans(new_id, 0, 100, oCfg.blend_.v);
		}
	} else {
		top.we_cmd('edit_home', 'add', typ, new_id);
	}
	tableNode = document.getElementById('le_tblWidgets');
	le_dragInit(tableNode);
	saveSettings();
}

function implode(arr, delimeter, enclosure) {
	if (delimeter === undefined) {
		delimeter = ',';
	}
	if (enclosure === undefined) {
		enclosure = "'";
	}
	var out = '';
	for (var i = 0; i < arr.length; i++) {
		if (i !== 0) {
			out += delimeter;
		}
		out += enclosure + encodeURI(arr[i]) + enclosure;
	}
	return out;
}

function composeUri(args) {
	var uri = WE().consts.dirs.WE_INCLUDES_DIR + 'we_widgets/dlg/' + args[0] + '.php?';
	for (var i = 1; i < args.length; i++) {
		uri += 'we_cmd[' + (i - 1) + ']=' + args[i];
		if (i < (args.length - 1)) {
			uri += '&';
		}
	}
	return uri;
}

/** Enable disable the spinning wheel  **/

/**
 * show the spinning wheel for a widget
 */
function showLoadingSymbol(elementId) {
	if (!document.getElementById("rpcBusyClone_" + elementId)) { // only show ONE loading symbol per widget

		var clone = document.getElementById("rpcBusy").cloneNode(true);
		var wpNode = document.getElementById(elementId + "_wrapper");
		var ctNode = document.getElementById(elementId + "_content");
		ctNode.style.display = "none";
		wpNode.style.textAlign = "center";
		wpNode.style.verticalAlign = "middle";
		wpNode.insertBefore(clone, ctNode);
		clone.id = "rpcBusyClone_" + elementId;
		clone.style.display = "inline";
	}
}


/**
 * hide the spinning wheel for a widget
 */
function hideLoadingSymbol(elementId) {
	if (document.getElementById('rpcBusyClone_' + elementId)) {
		var oWrapper = document.getElementById(elementId + '_wrapper');
		oWrapper.style.textAlign = 'left';
		oWrapper.style.verticalAlign = 'top';
		document.getElementById('rpcBusyClone_' + elementId).parentNode.removeChild(document.getElementById('rpcBusyClone_' + elementId));
	}
}

/** async REQUEST for preview **/

function updateWidgetContent(widgetType, widgetId, contentData, titel) {

	var docIFrm, iFrmScr;
	var oInline = document.getElementById(widgetId + '_inline'); // object-inline

	oInline.style.display = "block";
	if (widgetType === 'pad') {
		if (oInline.contentDocument) {
			docIFrm = oInline.contentDocument;
			iFrmScr = oInline.contentWindow;
		} else if (oInline.contentWindow) {
			docIFrm = oInline.contentWindow.document;
			iFrmScr = oInline.contentWindow;
		} else if (oInline.document) {
			docIFrm = oInline.document;
			iFrmScr = oInline;
		} else {
			return true;
		}
	}

	var oContent = document.getElementById(widgetId + '_content');
	oContent.style.display = 'block';
	hideLoadingSymbol(widgetId);
	var doc = (widgetType === 'pad' ? docIFrm.getElementById(widgetType) : oInline);
	doc.innerHTML = contentData;
	if (widgetType === 'pad') {
		iFrmScr.calendarSetup();
	}
	setLabel(widgetId, titel, '');
	initWidget(widgetId);
}

/**
 * executes a real AJAX command, instead of using an iframe
 * the received ajax-response will use the function "updateWidgetContent" to replace the content of the widget
 * @param param_1 string: individual foreach widget
 * @param initCfg string: configuration (position, etc)
 * @param param_3 string:
 * @param param_4 string:
 * @param titel string: titel of the widget
 * @param widgetId string: id fo widget
 *
 */
function executeAjaxRequest(param_1, initCfg, param_3, param_4, titel, widgetId) {

	// determine type of the widget
	var widgetType = document.getElementById(widgetId + '_type').value;

	showLoadingSymbol(widgetId);
	var _cmdName = null;

	switch (widgetType) {
		case "rss":
			_cmdName = "GetRss";
			break;
			//FIXME: what about all other tools?!
	}
	var url = WE().util.getWe_cmdArgsUrl(Array.prototype.slice.call(arguments), WE().consts.dirs.WEBEDITION_DIR + 'rpc/rpc.php?cmd=' + _cmdName + '&cns=widgets&');

	if (_cmdName) {
		top.YAHOO.util.Connect.asyncRequest('GET', url, {
			success: function (o) {
				if (o.responseText !== undefined && o.responseText !== '') {
					var weResponse = false;
					try {
						eval(o.responseText);
						if (weResponse) {
							updateWidgetContent(weResponse.widgetType, weResponse.widgetId, weResponse.data, weResponse.titel);

						}
					} catch (exc) {
						alert("Could not complete the ajax request");
					}
				}
			},
			failure: function (o) {
				alert("Could not complete the ajax request");

			}
		});
	}
}


/**
 * Old ajax functions using an iframe
 */
function rpc(a, b, c, d, e, wid, path) {
	//FIXME: remove this!
	if (!document.createElement) {
		return true;
	}
	var sType = document.getElementById(wid + '_type').value;
	showLoadingSymbol(wid);

	// temporaryliy add a form submit the form and save all !
	// start bugfix #1145
	var _tmpForm = document.createElement("form");
	document.getElementsByTagName("body")[0].appendChild(_tmpForm);
	switch (sType) {
		case 'rss':
		case 'sct':
			path = 'cmd.php?we_cmd[0]=reload&mod=' + sType;
			break;
		case 'pad':
			path = 'mod/' + sType + '.php';
			break;
		default:
			path = 'dlg/' + path + '.php';
	}
	_tmpForm.id = "_tmpSubmitForm";
	_tmpForm.method = "POST";
	_tmpForm.action = WE().consts.dirs.WE_INCLUDES_DIR + 'we_widgets/' + path;
	_tmpForm.target = "RSIFrame";
	for (var i = 0; i < arguments.length; i++) {
		var _tmpField = document.createElement('input');
		_tmpForm.appendChild(_tmpField);

		_tmpField.name = "we_cmd[]";
		_tmpField.value = unescape(arguments[i]);
		_tmpField.style.display = "none";
	}
	_tmpForm.submit();
	// remove form after submitting everything
	document.getElementsByTagName("body")[0].removeChild(document.getElementById("_tmpSubmitForm"));

	return false;
	// end bugfix #1145
}


function rpcHandleResponse(sType, sObjId, oDoc, sCsvLabel) {
	var docIFrm, iFrmScr, doc;
	var oInline = document.getElementById(sObjId + '_inline');

	oInline.style.display = "block";

	switch (sType) {
		case 'rss':
		case 'pad':
			if (oInline.contentDocument) {
				docIFrm = oInline.contentDocument;
				iFrmScr = oInline.contentWindow;
			} else if (oInline.contentWindow) {
				docIFrm = oInline.contentWindow.document;
				iFrmScr = oInline.contentWindow;
			} else if (oInline.document) {
				docIFrm = oInline.document;
				iFrmScr = oInline;
			} else {
				return true;
			}
	}
	var oContent = document.getElementById(sObjId + '_content');
	oContent.style.display = 'block';

	hideLoadingSymbol(sObjId);
	switch (sType) {
		case 'rss':
		case 'pad':
			doc = docIFrm.getElementById(sType);
			break;
		default:
			doc = oInline;
	}
	doc.innerHTML = oDoc.innerHTML;
	if (sType === 'pad') {
		iFrmScr.calendarSetup();
	}
	setLabel(sObjId, sCsvLabel, '');

	initWidget(sObjId);
}

function propsWidget(wid, ref) {
	var iHeight = oCfg[wid + '_props_'].iDlgHeight;
	var uri = composeUri(arguments);
	_propsDlg[ref] = new (WE().util.jsWindow)(window, uri, ref, -1, -1, oCfg.general_.iDlgWidth, iHeight, true, true, true);
}

function closeAllModalWindows() {
	try {
		for (var dialog in _propsDlg) {
			_propsDlg[dialog].close();
		}
	} catch (e) {

	}
}

function setMsgCount(num) {
	if (document.getElementById('msg_count')) {
		document.getElementById('msg_count').innerHTML = '<b>' + num + '</b>';
	}
}

function setTaskCount(num) {
	if (document.getElementById('task_count')) {
		document.getElementById('task_count').innerHTML = '<b>' + num + '</b>';
	}
}

function setUsersOnline(num) {
	if (document.getElementById('num_users')) {
		document.getElementById('num_users').innerHTML = num;
	}
}

function setUsersListOnline(users) {
	if (document.getElementById('users_online')) {
		document.getElementById('users_online').innerHTML = users;
	}
}
function setMfdData(data) {
	if (document.getElementById('mfd_data')) {
		document.getElementById('mfd_data').innerHTML = data;
		WE().util.setIconOfDocClass(document, "mfdIcon");
	}
}

function getUser() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);
	new (WE().util.jsWindow)(window, url, 'browse_users', -1, -1, 500, 300, true, false, true);
}

function resizeIdx(a, id) {
	var res = document.getElementById(id + '_res').value;
	switch (a) {
		case 'swap':
			document.getElementById(id + '_res').value = (res === "0") ? "1" : "0";
			document.getElementById(id + '_icon_resize').title = (res === "0") ? WE().consts.g_l.cockpit.reduce_size : WE().consts.g_l.cockpit.increase_size;
			break;
		case 'get':
			return res;
	}
}

function removeWidget(wizId) {
	var remove = confirm(WE().consts.g_l.cockpit.pre_remove + getLabel(wizId) + WE().consts.g_l.cockpit.post_remove);
	if (remove === true) {
		document.getElementById(wizId).parentNode.removeChild(document.getElementById(wizId));
		updateJsStyleCls();
	}
	saveSettings();
}

function newMessage(username) {
	if (WE().consts.tables.MESSAGES_TABLE) {
		new (WE().util.jsWindow)(window, WE().consts.dirs.WE_MESSAGING_MODULE_DIR + 'messaging_newmessage.php?we_transaction=' + WE().layout.cockpitFrame.transact + '&mode=u_' + encodeURI(username), 'messaging_new_message', -1, -1, 670, 530, true, false, true, false);
	}
}

function getDimension(theString, styleClassElement) {
	var dim = {};

	if (document.getElementById && document.createElement) {
		var span = document.createElement('span');
		span.id = 'newSpan';
		span.style.position = 'absolute';
		span.style.visibility = 'hidden';
		if (styleClassElement) {
			span.className = styleClassElement;
		}
		span.appendChild(document.createTextNode(theString));
		document.body.appendChild(span);
		dim.height = span.offsetHeight;
		dim.width = span.offsetWidth;
		document.body.removeChild(span);
	} else if (document.all && document.body.insertAdjacentHTML) {
		var html = '<span id="newSpan" style="position: absolute; visibility: hidden;"' +
						(styleClassElement ? ' class="' + styleClassElement + '"' : '') + '>' +
						theString + '<\/span>';
		document.body.insertAdjacentHTML('beforeEnd', html);
		dim.height = document.all.newSpan.offsetHeight;
		dim.width = document.all.newSpan.offsetWidth;
		document.all.newSpan.outerHTML = '';
	} else if (document.layers) {
		var lr = new Layer(window.innerWidth);
		lr.document.open();
		if (styleClassElement) {
			lr.document.write('<span class="' + styleClassElement + '">' + theString + '<\/span>');
		} else {
			lr.document.write(theString);
		}
		lr.document.close();
		dim.height = lr.document.height;
		dim.width = lr.document.width;
	}

	return dim;
}

function transmit(doc, type, id) {
	if (WE().layout.cockpitFrame) {
		WE().layout.cockpitFrame.pushContent(type, id, doc.document.getElementById('content').innerHTML, doc.document.getElementById('prefix').innerHTML, doc.document.getElementById('postfix').innerHTML, doc.document.getElementById('csv').innerHTML);
	}
}

//dont move this as on load event, since adding css will fire load event again.
addCss();