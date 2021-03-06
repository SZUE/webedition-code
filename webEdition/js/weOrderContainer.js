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

function weOrderContainer(id) {
	this.container = id;
	this.elements = [];
	this.position = [];


	this.add = function (doc, id, afterid) {

		var child = null;
		var node = null;
		var div = null;
		var pos = this.position.length;
		var element = [];
		var i;

		element.id = id;
		this.elements[this.elements.length] = element;

		if (afterid !== null) {
			for (i = 0; i < this.position.length; i++) {
				if (this.position[i] == afterid) {
					pos = i + 1;
				}
			}
		}

		// hinten anhängen
		if (pos >= this.position.length) {
			pos = this.position.length;
			this.position.push(element.id);
			// vorne einfügen
		} else if (pos <= 0) {
			pos = 0;
			this.position.reverse();
			this.position.push(element.id);
			this.position.reverse();

			// einfügen
		} else {
			for (i = this.position.length; i > pos; i--) {
				this.position[i] = this.position[(i - 1)];
			}
			this.position[pos] = element.id;

		}

		child = document.getElementById(this.container).childNodes;

		if (doc == document) {
			node = doc.getElementById(id);
			div = node;
		} else {
			if (document.importNode) { // Safari or Mozilla
				node = document.importNode(doc.getElementById(id), true);
			} else { // Internet Explorer
				node = doc.getElementById(id).cloneNode(true);
			}
			div = this.createDIV(node);
		}

		if (this.position.length == 1 || pos >= this.position.length - 1) {
			document.getElementById(this.container).appendChild(div);
		} else {
			document.getElementById(this.container).insertBefore(div, child[pos]);
		}
		this.fixIESelectBug(doc, id);
	};


	this.reload = function (doc, id, selectedId, selectedValue) {

		var found = false;
		var pos = this.position.length;
		var node = null;
		var div;

		for (var i = 0; i < this.position.length; i++) {
			if (this.position[i] == id) {
				pos = i;
				found = true;
			}
		}

		if (found === true) {

			if (doc == document) {
				node = doc.getElementById(id);
				div = node;
			} else {
				if (document.importNode) { // Safari or Mozilla
					node = document.importNode(doc.getElementById(id), true);
				} else { // Internet Explorer
					node = doc.getElementById(id).cloneNode(true);
				}
				div = this.createDIV(node);
			}

			document.getElementById(id).innerHTML = div.innerHTML;

		}

		this.fixIESelectBug(doc, id);

	};


	this.del = function (id) {
		var node = null;
		var i;
		for (i = 0; i < this.elements.length; i++) {
			if (this.elements[i].id == id) {
				this.elements.splice(i, 1);
				i = this.elements.length;
			}
		}

		for (i = 0; i < this.position.length; i++) {
			if (this.position[i] == id) {
				pos = i;
				this.position.splice(i, 1);
				i = this.position.length;
			}
		}

		node = document.getElementById(id);
		document.getElementById(this.container).removeChild(node);

	};


	this.up = function (id) {

		var up = null;
		var down = null;

		for (var i = 1; i < this.position.length; i++) {
			if (this.position[i] == id) {
				up = document.getElementById(this.position[i]);
				down = document.getElementById(this.position[(i - 1)]);
				temp = this.position[(i - 1)];
				this.position[(i - 1)] = this.position[i];
				this.position[i] = temp;
				i = this.position.length;
			}
		}

		if (up !== null && down !== null) {
			document.getElementById(this.container).removeChild(up);
			document.getElementById(this.container).insertBefore(up, down);
		}
	};


	this.down = function (id) {

		var up = null;
		var down = null;

		for (var i = 0; i < this.position.length - 1; i++) {
			if (this.position[i] == id) {
				up = document.getElementById(this.position[i + 1]);
				down = document.getElementById(this.position[i]);
				temp = this.position[(i + 1)];
				this.position[(i + 1)] = this.position[i];
				this.position[i] = temp;
				i = this.position.length;
			}
		}

		if (up !== null && down !== null) {
			document.getElementById(this.container).removeChild(up);
			document.getElementById(this.container).insertBefore(up, down);
		}

	};


	this.createDIV = function (node) {

		var div = document.createElement("div");
		var attr = document.createAttribute("id");

		attr.value = node.getAttribute("id");
		div.innerHTML = node.innerHTML;
		div.setAttributeNode(attr);

		return div;

	};


	// Bug in IE -> loses the selected attribute in option tags
	this.fixIESelectBug = function (doc, id) {
		if (!document.importNode) {
			node = (doc == document ? document : document.getElementById(id));

			for (j = 0; j < doc.getElementsByTagName("select").length; j++) {

				for (i = 0; i < doc.getElementsByTagName("select")[j].options.length; i++) {
					if (doc.getElementsByTagName("select")[j].options[i].selected) {
						node.getElementsByTagName("select")[j].selectedIndex = i;
					}
				}
			}

		}

	};

}