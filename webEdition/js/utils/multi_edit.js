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

function multi_edit(parentId, form, itemNum, but, width, editable) {

	this.variantCount = 0;
	this.itemCount = 0;
	this.currentVariant = 0;
	this.button = "";
	this.defWidth = width;
	this.name = "me" + Math.round(Math.random() * 10000);
	this.parent = document.getElementById(parentId);
	this.form = form;
	this.editable = editable;
	this.relatedItems = [];

	this.createItemHidden = function (name) {
		var item = document.createElement("input");
		item.setAttribute("name", name);
		item.setAttribute("id", name);
		item.setAttribute("type", "hidden");

		this.parent.appendChild(item);
	};

	this.updateHidden = function (item, value) {
		this.form.elements[this.name + "_variant" + this.currentVariant + "_" + this.name + "_" + item].value = value;
	};

	this.addVariant = function () {
		for (var i = 0; i < this.itemCount; i++) {
			this.createItemHidden(this.name + "_variant" + this.variantCount + "_" + this.name + "_item" + i);
		}
		this.variantCount++;
	};

	this.deleteVariant = function (variant) {
		if (variant < (this.variantCount - 1)) {
			for (var i = variant + 1; i < this.variantCount; i++) {
				for (var j = 0; j < this.itemCount; j++) {
					this.form.elements[this.name + "_variant" + (i - 1) + "_" + this.name + "_item" + j].value = this.form.elements[this.name + "_variant" + i + "_" + this.name + "_item" + j].value;
				}
			}
		}

		this.variantCount--;
		for (var z = 0; z < this.itemCount; z++) {
			var item = document.getElementById(this.name + "_variant" + this.variantCount + "_" + this.name + "_item" + z);
			this.parent.removeChild(item);
		}
		this.currentVariant = (variant < (this.variantCount - 1) ?
						variant :
						this.variantCount - 1);

		this.showVariant(this.currentVariant);

	};

	this.addItem = function (buttname) {
		if (buttname) {
			this.button = buttname;
		}

		var butt = this.button.replace("#####placeHolder#####", this.name + ".delItem(" + this.itemCount + ")");

		var set = document.createElement("div");
		set.setAttribute("id", this.name + "_item" + this.itemCount);

		set.innerHTML = '<table style="margin-bottom:5px;" class="default"><tr valign="middle"><td style="width:' + this.defWidth + 'px">' +
						(this.editable === true ?
										'<input name="' + this.name + "_item" + this.itemCount + '" id="' + this.name + "_item_input_" + this.itemCount + '" type="text" style="width:' + this.defWidth + 'px" onkeyup="' + this.name + ".updateHidden(\'item" + this.itemCount + "\',this.value)\" class=\"wetextinput\"></td>" :
										'<label id="' + this.name + "_item_label_" + this.itemCount + '" class="defaultfont"></td>'
										) + "<td>&nbsp;</td><td>" + butt + "</td></tr></table>";

		this.parent.appendChild(set);

		set = null;
		for (var j = 0; j < this.variantCount; j++) {
			this.createItemHidden(this.name + "_variant" + j + "_" + this.name + "_item" + this.itemCount);
		}

		this.itemCount++;
	};

	this.delItem = function (child) {
		this.itemCount--;
		for (var i = 0; i < this.variantCount; i++) {
			if (child < this.itemCount) {
				for (var j = child + 1; j < (this.itemCount + 1); j++) {
					this.form.elements[this.name + "_variant" + i + "_" + this.name + "_item" + (j - 1)].value = this.form.elements[this.name + "_variant" + i + "_" + this.name + "_item" + j].value;
				}
			}
			var item = document.getElementById(this.name + "_variant" + i + "_" + this.name + "_item" + this.itemCount);
			this.parent.removeChild(item);
		}

		var item1 = document.getElementById(this.name + "_item" + this.itemCount);
		this.parent.removeChild(item1);
		if (this.relatedItems[child]) {
			this.parent.removeChild(this.relatedItems[child]);
			//remove from list
			this.relatedItems.splice(child, 1);
		}
		this.showVariant(this.currentVariant);
	};

	this.setItem = function (variant, item, value) {
		this.form.elements[this.name + "_variant" + variant + "_" + this.name + "_item" + item].value = value;
	};

	this.setRelatedItems = function (item) {
		this.relatedItems[this.itemCount] = item;
	};

	this.showVariant = function (variant) {
		for (var i = 0; i < this.itemCount; i++) {
			if (this.form.elements[this.name + "_variant" + variant + "_" + this.name + "_item" + i] !== undefined) {
				if (variant != this.currentVariant && this.editable) {
					this.setItem(this.currentVariant, i, this.form.elements[this.name + "_item" + i].value);
				}
				if (this.editable) {
					this.form.elements[this.name + "_item" + i].value = this.form.elements[this.name + "_variant" + variant + "_" + this.name + "_item" + i].value;
				} else {
					var item = document.getElementById(this.name + "_item_label_" + i);
					item.innerHTML = this.form.elements[this.name + "_variant" + variant + "_" + this.name + "_item" + i].value;
				}
			}
		}
		this.currentVariant = variant;
	};

	this.button = but;
	for (i = 0; i < itemNum; i++) {
		this.addItem();
	}
//FIXME: do we need this as a global var?
	window[this.name] = this;
}