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

function initDragWidgets() {
	if (!bInitDrag) {
		le_dragInit(oTblWidgets);
		bInitDrag = true;
	}
}

var oEvt = {
	obj: null,
	init: function (oWidgetTbl, oWidgetDiv) {
		oWidgetTbl.onmousedown = oEvt.start;
		oWidgetTbl.obj = oWidgetDiv;
		if (isNaN(parseInt(oWidgetDiv.style.left))) {
			oWidgetDiv.style.left = '0px';
		}
		if (isNaN(parseInt(oWidgetDiv.style.top))) {
			oWidgetDiv.style.top = '0px';
		}
		oWidgetDiv.onDragStart = new Function();
		oWidgetDiv.onDragEnd = new Function();
		oWidgetDiv.onDrag = new Function();
	},
	uninit: function (oWidgetTbl, oWidgetDiv) {
		window.clearInterval(oWidgetDiv.H);
		oWidgetTbl.onmousedown = null;
		oWidgetTbl.obj = null;
		oWidgetDiv.onDragStart = null;
		oWidgetDiv.onDragEnd = null;
		oWidgetDiv.onDrag = null;
	},
	start: function (oMouseEvt) {
		var obj = oEvt.obj = this.obj;
		oMouseEvt = oEvt.getEvt(oMouseEvt);
		if (oMouseEvt.which != 1) {
			return true;
		}
		obj.onDragStart();
		obj.lastMouseX = oMouseEvt.clientX;
		obj.lastMouseY = oMouseEvt.clientY;
		if (oWidget.Safari) {
			obj.lastMouseY -= document.body.scrollTop;
		}
		obj.H = window.setInterval(setPosition, 10, obj, (document.body.scrollHeight > document.documentElement.clientHeight ?
						document.body.scrollHeight : document.documentElement.clientHeight));
		document.onmouseup = oEvt.end;
		document.onmousemove = oEvt.drag;
		return false;
	},
	drag: function (oMouseEvt) {
		oMouseEvt = oEvt.getEvt(oMouseEvt);
		if (oMouseEvt.which === 0) {
			return oEvt.end();
		}
		var oDiv = oEvt.obj;
		var iClientY = oMouseEvt.clientY;
		if (oWidget.Safari) {
			iClientY -= document.body.scrollTop;
		}
		var iClientX = oMouseEvt.clientX;
		if (oDiv.lastMouseX == iClientX && oDiv.lastMouseY == iClientY) {
			return false;
		}
		var iTop = parseInt(oDiv.style.top);
		var iLeft = parseInt(oDiv.style.left);
		var iLastPosX, iLastPosY;
		iLastPosX = iLeft + iClientX - oDiv.lastMouseX;
		iLastPosY = iTop + iClientY - oDiv.lastMouseY;
		oDiv.style.left = iLastPosX + 'px';
		oDiv.style.top = iLastPosY + 'px';
		oDiv.lastMouseX = iClientX;
		oDiv.lastMouseY = iClientY;
		oDiv.onDrag(iLastPosX, iLastPosY);
		return false;
	},
	end: function (oMouseEvt) {
		oMouseEvt = oEvt.getEvt(oMouseEvt);
		document.onmousemove = null;
		document.onmouseup = null;
		window.clearInterval(oEvt.obj.H);
		var oDiv = oEvt.obj.onDragEnd();
		oEvt.obj = null;
		updateJsStyleCls();
		saveSettings();
		return oDiv;
	},
	getEvt: function (oMouseEvt) {
		if (oMouseEvt === undefined) {
			oMouseEvt = window.event;
		}
		if (oMouseEvt.layerX !== undefined) {
			oMouseEvt.layerX = oMouseEvt.offsetX;
		}
		if (oMouseEvt.layerY !== undefined) {
			oMouseEvt.layerY = oMouseEvt.offsetY;
		}
		if (oMouseEvt.which !== undefined) {
			oMouseEvt.which = oMouseEvt.button;
		}
		return oMouseEvt;
	}
};
var le_dragInit = function (oMouseEvt) {
	oWidget.oTbl = oMouseEvt;
	oWidget.oTblRow = oWidget.oTbl.tBodies[0].rows[0];
	oWidget.oCell = oWidget.oTblRow.cells;
	oWidget.c = [];
	var iCountDiv = 0;
	for (var i = 0; i < oWidget.oCell.length; i++) {
		var oCurrCell = oWidget.oCell[i];
		for (var j = 0; j < oCurrCell.childNodes.length; j++) {
			var oChildNode = oCurrCell.childNodes[j];
			if (oChildNode.tagName == 'DIV') {
				oWidget.c[iCountDiv] = new setHandler(oChildNode);
				iCountDiv++;
			}
		}
	}
};

var oWidget = {
	Gecko: navigator.userAgent.indexOf('Gecko') !== -1,
	Safari: navigator.userAgent.indexOf('Safari') !== -1,
	oShieldId: 'divShieldId',
	oModShieldId: 'modDivShieldId',
	oMask: null,
	hide: function () {
		oWidget.oTbl.style.display = 'none';
	},
	show: function () {
		oWidget.oTbl.style.display = '';
	},
	p: function () {
		if (!oWidget.oMask) {
			oWidget.oMask = document.createElement('div');
			oWidget.oMask.className = 'le_widget';
			oWidget.oMask.backgroundColor = '';
			oWidget.oMask.style.border = '2px dotted #aaa';
			oWidget.oMask.innerHTML = '&nbsp;';
		}
		return oWidget.oMask;
	},
	applyEvt: function (obj, evt) {
		return function () {
			return obj[evt].apply(obj, arguments);
		};
	},
	adaptOffset: function (oParent) {
		for (var i = 0; i < oWidget.c.length; i++) {
			var obj = oWidget.c[i];
			obj.node.pagePosLeft = oWidget.setOffsetLeftTop(obj.node, true);
			obj.node.pagePosTop = oWidget.setOffsetLeftTop(obj.node, false);
		}
		var oNextSibling = oParent.node.nextSibling;
		while (oNextSibling) {
			oNextSibling.pagePosTop -= oParent.node.offsetHeight;
			oNextSibling = oNextSibling.nextSibling;
		}
	},
	setOffsetLeftTop: function (obj, bIterate) {
		var count = 0;
		while (obj !== null) {
			count += obj['offset' + (bIterate ? 'Left' : 'Top')];
			obj = obj.offsetParent;
		}
		return count;
	},
	appendMaskClone: function (aTbl) {
		oWidget.removeMasks();
		var oNewDiv = document.createElement('div');
		oNewDiv.id = oWidget.oShieldId;
		oNewDiv.innerHTML = '&nbsp;';
		oNewDiv.style.position = 'absolute';
		oNewDiv.style.width = '100%';
		oNewDiv.style.height = document.body.offsetHeight + 'px';
		oNewDiv.style.left = '0px';
		oNewDiv.style.top = '0px';
		document.body.appendChild(oNewDiv);
	},
	removeMasks: function () {
		var aShields = [oWidget.oModShieldId, oWidget.oShieldId];
		for (var i = 0; i < aShields.length; i++) {
			var oRemove = document.getElementById(aShields[i]);
			if (oRemove) {
				oRemove.parentNode.removeChild(oRemove);
				oRemove = null;
			}
		}
	},
	br: function () {
		var s = '';
		for (var i = 0; i < oWidget.oCell.length; i++) {
			var oCurrCell = oWidget.oCell[i];
			for (var j = 0; j < oCurrCell.childNodes.length - 1; j++) {
				var oChild = oCurrCell.childNodes[j];
				if (oChild.tagName == 'DIV') {
					s += s !== '' ? ':' : '';
					s += oChild.id.substring(2) + '_' + oCurrCell.id.substring(2);
				}
			}
		}
	}
};

function setHandler(oDiv) {
	this._dragStart = onInsertNode;
	this._drag = onDragNode;
	this._dragEnd = getBr;
	this.fDrop = drop;
	this.fUnset = unset;
	this.bSet = false;
	this.node = oDiv;
	this.oDragTb = document.getElementById(oDiv.id + '_h');
	if (this.oDragTb) {
		this.oDragTb.style.cursor = 'move';
		oEvt.init(this.oDragTb, this.node);
		this.node.onDragStart = oWidget.applyEvt(this, '_dragStart');
		this.node.onDrag = oWidget.applyEvt(this, '_drag');
		this.node.onDragEnd = oWidget.applyEvt(this, '_dragEnd');
	}
}

function unset() {
	if (this.oDragTb) {
		if (this.b) {
			this.b.onclick = null;
			this.b.onmouseup = null;
			this.b = null;
		}
		oEvt.uninit(this.oDragTb, this.node);
		this.node.onDragStart = null;
		this.node.onDrag = null;
		this.node.onDragEnd = null;
		this.oDragTb = null;
	}
	this.node = null;
}

function onInsertNode() {
	oWidget.adaptOffset(this);
	this.origNextSibling = this.node.nextSibling;
	var oNodeInsert = oWidget.p();
	var iOffsetH = this.node.offsetHeight;
	if (oWidget.Gecko) {
		iOffsetH -= parseInt(oNodeInsert.style.borderTopWidth) * 2;
	}
	var iOffsetW = (document.getElementById(this.node.id + '_res').value === 0 ? 225 : 452);
	//var iOffsetW=this.node.offsetWidth;
	var iOffsetTrue = oWidget.setOffsetLeftTop(this.node, true);
	var iOffsetFalse = oWidget.setOffsetLeftTop(this.node, false);
	oWidget.hide();
	oNodeInsert.style.height = '1px';
	// set height, if it is narrow
	oNodeInsert.style.width = (iOffsetW <= 300 ? iOffsetW + "px" : "100%");

	this.node.parentNode.insertBefore(oNodeInsert, this.node.nextSibling);
	this.node.style.position = 'absolute';

	this.node.style.zIndex = 100;
	this.node.style.left = iOffsetTrue + 'px';
	this.node.style.top = iOffsetFalse + 'px';

	oWidget.show();
	oWidget.appendMaskClone(this);

	oNodeInsert.style.height = iOffsetH + 'px'; // IMPORTANT set height late otherwise the screen "jumps" 727

	this.bSet = false;
	return false;
}

function onDragNode(iPosX, iPosY) {
	if (!this.bSet) {
		this.node.style.filter = 'alpha(opacity=70)';
		this.node.style.opacity = 0.7;
		this.bSet = true;
	}
	var oBuff = null;
	var iMax = 99999999;
	var obj;
	for (var i = 0; i < oWidget.c.length; i++) {
		obj = oWidget.c[i];
		var iCurrPos = Math.sqrt(Math.pow(iPosX - obj.node.pagePosLeft, 2) + Math.pow(iPosY - obj.node.pagePosTop, 2));
		if (obj == this)
			continue;
		if (isNaN(iCurrPos))
			continue;
		if (iCurrPos < iMax) {
			iMax = iCurrPos;
			oBuff = obj;
		}
	}
	obj = oWidget.p();
	if (oBuff && obj.nextSibling != oBuff.node && oBuff.node.parentNode && oBuff.node.parentNode.nodeType == 1) {
		oBuff.node.parentNode.insertBefore(obj, oBuff.node);
	}
}

function getBr() {
	oWidget.removeMasks();
	if (this.fDrop()) {
		oWidget.br();
	}
	return true;
}

function drop() {
	var bInsertNode = false;
	oWidget.hide();
	this.node.style.position = '';
	this.node.style.left = '0px';
	this.node.style.zIndex = '';
	this.node.style.top = '0px';
	this.node.style.filter = '';
	this.node.style.opacity = '';
	var oNodeDiv = oWidget.p();
	if (oNodeDiv.nextSibling != this.origNextSibling) {
		oNodeDiv.parentNode.insertBefore(this.node, oNodeDiv.nextSibling);
		bInsertNode = true;
	}
	oNodeDiv.parentNode.removeChild(oNodeDiv);
	oWidget.show();
	return bInsertNode;
}

function setPosition(obj, iPx) {
	return function () {
		var iInnerH = (window.innerHeight < document.body.clientHeight) ? window.innerHeight : document.body.clientHeight;
		var iScrollTop = document.body.scrollTop;
		var iMarginTop = 4;
		var iTrans = 0.05 * iInnerH;
		var iNewScrollTop = iScrollTop;
		var iOffsetTop = obj.offsetTop;
		if (obj.lastMouseY <= iTrans) {
			iOffsetTop = Math.max(0, obj.offsetTop - iMarginTop);
			iNewScrollTop = Math.max(0, iScrollTop - iMarginTop);
		} else if (obj.lastMouseY >= iInnerH - iTrans) {
			iOffsetTop = Math.min(iPx - obj.offsetHeight, obj.offsetTop + iMarginTop);
			iNewScrollTop = Math.min(iPx - iInnerH, iScrollTop + iMarginTop);
		}
		var aTbll = iNewScrollTop - iScrollTop;
		if (aTbll !== 0) {
			document.body.scrollTop = iNewScrollTop;
			obj.style.top = iOffsetTop + 'px';
		}
	};
}
