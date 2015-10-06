/**
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
 *
 *  * This source is part of webEdition CMS. webEdition CMS is
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
//FIXME use an Array to store these objects!
jsWindow_count = 0;

function jsWindow(url, ref, x, y, w, h, openAtStartup, scroll, hideMenue, resizable, noPopupErrorMsg, noPopupLocation) {
	var foo_w = w;
	var foo_h = h;

	if (window.screen) {
		var screen_height = ((screen.height - 50) > screen.availHeight) ? screen.height - 50 : screen.availHeight;
		screen_height = screen_height - 40;
		var screen_width = screen.availWidth - 10;
		w = Math.min(screen_width, w);
		h = Math.min(screen_height, h);
		x = (x == -1 ? Math.round((screen_width - w) / 2) : x);
		y = (y == -1 ? Math.round((screen_height - h) / 2) : y);
	}

	this.name = "jsWindow" + (jsWindow_count++);
	this.url = url;
	this.ref = ref;
	this.x = x;
	this.y = y;
	this.w = w;
	this.h = h;
	this.scroll = (foo_w != w || foo_h != h) ? true : scroll;
	this.hideMenue = hideMenue;
	this.resizable = resizable;
	this.wind = null;
	this.obj = this.name + "Object";
	eval(this.obj + "=this;");
	if (openAtStartup) {
		this.open(noPopupErrorMsg, noPopupLocation);
	}
}

jsWindow.prototype.open = function (noPopupErrorMsg, noPopupLocation) {
	var properties = (this.hideMenue ? "menubar=no," : "menubar=yes,") + (this.resizable ? "resizable=yes," : "resizable=no,") + ((this.scroll) ? "scrollbars=yes," : "scrollbars=no,") + "width=" + this.w + ",height=" + this.h + ",left=" + this.x + ",top=" + this.y;
	try {
		this.wind = window.open(this.url, this.ref, properties);
//Bug mit chrome:
//		this.wind.moveTo(this.x,this.y);
		this.wind.focus();

	} catch (e) {
		if (noPopupErrorMsg !== undefined && noPopupErrorMsg.length) {
			if (!this.wind) {
				top.we_showMessage(noPopupErrorMsg, WE_MESSAGE_ERROR, window);
				//  disabled See Bug#1335
				if (noPopupLocation !== undefined) {
					//document.location = noPopupLocation;
				}
			}
		}
	}

}

jsWindow.prototype.close = function () {
	if (!this.wind.closed)
		this.wind.close();
};

function jsWindowClose(name) {
	var i;
	for (i = 0; i <= top.jsWindow_count; i++) {
		if (eval("top.jsWindow" + i + "Object.ref") == name) {
			eval("top.jsWindow" + i + "Object.close()");
		}
	}
}

function jsWindowCloseAll() {
	if (jsWindow_count) {
		for (i = 0; i < jsWindow_count; i++) {
			try {
				eval("jsWindow" + i + "Object.close()");
			} catch (err) {

			}
		}
	}
}

function jsWindowFind(name) {
	var wind = undefined;
	if (jsWindow_count) {
		var fo = false;
		for (k = jsWindow_count - 1; k > -1; k--) {
			eval("if(jsWindow" + k + "Object.ref=='" + name + "'){fo=true;wind=jsWindow" + k + "Object.wind}");
			if (fo) {
				break;
			}
		}
	}
	return wind;
}

function jsWindowFocus(name) {
	var wind = jsWindowFind(name);
	if (wind !== undefined) {
		wind.focus();
		return true;
	}
	return false;
}
