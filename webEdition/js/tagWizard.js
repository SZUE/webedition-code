/* global self, WE, top */

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

var tw = WE().util.getDynamicVar(document, 'loadVarTagWizard', 'data-tw');
WE().util.loadConsts(document, "g_l.tagWizzard");

function weTagWizard(tagName) {

	this.tagName = tagName; // name of the we:tag

	this.allAttributes = [];  // all attributes of the we:tag
	this.reqAttributes = {};  // required attributes of the we:tag
	this.typeAttributeId = '';
	this.typeAttributeAllows = {}; // type depending allowed fields (id)
	this.typeAttributeRequires = {}; // type depending required fields (id)

	this.needsEndTag = false;

	this.missingFields = []; // missing attributes -> these are genereated by method getWeTag

	this.changeType = function (newType) {
		// set the matching required fields.
		// 1st remove all not always needed
		for (i = 0; i < this.allAttributes.length; i++) {

			if (this.reqAttributes[this.allAttributes[i]]) {
				// no need to change these elements
			} else if (this.typeAttributeRequires[newType] && WE().util.in_array(this.allAttributes[i], this.typeAttributeRequires[newType])) {
				this.setLabelRequired(this.allAttributes[i], true);
			} else {
				this.setLabelRequired(this.allAttributes[i], false);
			}
		}

		if (this.typeAttributeAllows[newType]) { // only show selected

			// flag if one is visible
			var hasAttributes = false;

			// show the correct attributes
			for (i = 0; i < this.allAttributes.length; i++) {
				if (WE().util.in_array(this.allAttributes[i], this.typeAttributeAllows[newType])) {

					if (this.allAttributes[i] != this.typeAttributeId) {
						hasAttributes = true;
					}

					this.showAttribute(this.allAttributes[i]);
				} else {
					this.hideAttribute(this.allAttributes[i]);
				}
			}

			if (hasAttributes) {

				this.hideElement('no_attributes_for_type');
			} else {

				this.showElement('no_attributes_for_type');
			}

			this.hideElement('no_type_selected_attributes');

		} else { // show all

			for (i = 0; i < this.allAttributes.length; i++) {
				this.hideAttribute(this.allAttributes[i]);
			}
			this.showElement('no_type_selected_attributes');
			this.hideElement('no_attributes_for_type');
		}
	};

	this.showElement = function (id) {
		elem = document.getElementById(id);
		if (elem) {
			elem.style.display = "";
		}
	};

	this.hideElement = function (id) {
		elem = document.getElementById(id);
		if (elem) {
			elem.style.display = "none";
		}
	};

	this.showAttribute = function (id) {
		elem = document.getElementById("li_" + id);
		if (elem) {
			elem.style.display = "";
		}
	};

	this.hideAttribute = function (id) {

		elem = document.getElementById("li_" + id);
		if (elem) {
			elem.style.display = "none";
		}
	};

	this.getPartFromId = function (elemIdName, getId) {

		if (getId) {
			return elemIdName.substr(0, elemIdName.indexOf("_"));
		} else {
			return elemIdName.substr(elemIdName.indexOf("_") + 1);
		}
	};

	this.setLabelRequired = function (elemIdName, required) {

		element = document.getElementById("label_" + elemIdName);

		elemName = this.getPartFromId(elemIdName);

		if (element) {

			if (required) {
				element.innerHTML = elemName + "*";
			} else {
				element.innerHTML = elemName;
			}

		}
	};

	this.getWeTag = function () { // build the we:tag in this function and return it.

		ret = "<we:" + this.tagName;

		this.missingFields = [];
		var i;
		// differbetween case with/without type-attribute

		if (this.typeAttributeId && typeAttributeAllows[document.getElementById(this.typeAttributeId).value]) {

			var typeValue = document.getElementById(this.typeAttributeId).value;

			for (i = 0; i < this.typeAttributeAllows[typeValue].length; i++) {

				fieldId = this.typeAttributeAllows[typeValue][i];
				fieldName = this.getPartFromId(fieldId);
				fieldValue = document.getElementById(fieldId).value;

				// check if attribute is required attribute of the we:tag
				if (this.reqAttributes[fieldId] && (!fieldValue)) {
					this.missingFields.push(fieldName);
				} else {

					// check if attribute is required by the value of the type-Attribut
					if (this.typeAttributeRequires[typeValue] && (!fieldValue) && WE().util.in_array(fieldId, typeAttributeRequires[typeValue])) {
						this.missingFields.push(fieldName);
					}
				}

				// at last add attribute to the we:tag
				if (fieldValue && !(fieldValue == '-' && this.typeAttributeId == fieldId)) {
					ret += " " + fieldName + "=\"" + fieldValue + "\"";
				}
			}

		} else if (this.typeAttributeId) {
			// type is not selected
			return false;
		} else {
			for (i = 0; i < this.allAttributes.length; i++) {

				fieldId = this.allAttributes[i];
				fieldName = this.getPartFromId(fieldId);
				fieldValue = document.getElementById(fieldId).value;

				//if( this.reqAttributes[fieldId] && (!fieldValue || fieldValue == '-') ) { //#4483
				if (this.reqAttributes[fieldId] && (!fieldValue)) {
					this.missingFields.push(fieldName);
				}

				if (fieldValue) {
					ret += " " + fieldName + "=\"" + fieldValue + "\"";
				}
			}
		}

		if (this.needsEndTag) {
			ret += ">" + document.we_form.elements.weTagData_defaultValue.value + "</we:" + this.tagName + ">";
		} else {
			ret += " />";
		}

		if (this.missingFields.length) {
			return false;
		} else {
			return ret;
		}
	};

	this.editMultiSelector = function (cmdObj) {

		selItems = cmdObj.selectedItems;
		textName = cmdObj.textName;

		val = weTextInput.getValue(cmdObj.textFieldId);

		for (var selId in cmdObj.selectedItems) {

			selItem = cmdObj.selectedItems[selId];
			if (val) {
				val += ",";
			}
			val += selItem[textName];
		}
		weTextInput.setValue(cmdObj.textFieldId, val);
	};
}

function closeOnEscape() {
	return true;
}

function applyOnEnter(evt) {
	_elemName = "target";
	if (evt.srcElement !== undefined) { // IE
		_elemName = "srcElement";
	}

	if (evt[_elemName].tagName != "SELECT") {
		we_cmd("saveTag");
		return true;
	}
}

function we_cmd() {
	var args = WE().util.getWe_cmdArgsArray(Array.prototype.slice.call(arguments));
	var url = WE().util.getWe_cmdArgsUrl(args);

	switch (args[0]) {
		case "switch_type":
			weTagWizard.changeType(args[1]);
			break;
		case "saveTag":
			var strWeTag = weTagWizard.getWeTag();
			if (strWeTag) {
				var contentEditor = WE().layout.weEditorFrameController.getVisibleEditorFrame();
				if (tw.openAtCursor) {
					contentEditor.window.addCursorPosition(strWeTag);
					window.close();
				} else {
					contentEditor.document.we_form.elements.tag_edit_area.value = strWeTag;
					contentEditor.document.we_form.elements.tag_edit_area.select();
					window.close();
				}
			} else {
				if (weTagWizard.missingFields.length) {
					var req = "";
					for (i = 0; i < weTagWizard.missingFields.length; i++) {
						req += "- " + weTagWizard.missingFields[i] + "\n";
					}
					req = WE().consts.g_l.tagWizzard.fill_required_fields + "\n" + req;
					top.we_showMessage(req, WE().consts.message.WE_MESSAGE_WARNING, window);
				} else {
					top.we_showMessage(WE().consts.g_l.tagWizzard.no_type_selected, WE().consts.message.WE_MESSAGE_WARNING, window);
				}
			}
			break;
		case "we_selector_directory":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.windowDirSelect.width, WE().consts.size.windowDirSelect.height, true, true, true, true);
			break;
		case "we_selector_document":
		case "we_selector_image":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.docSelect.width, WE().consts.size.docSelect.height, true, true, true, true);
			break;
		case "we_selector_file":
			new (WE().util.jsWindow)(this, url, "we_fileselector", -1, -1, WE().consts.size.windowSelect.width, WE().consts.size.windowSelect.height, true, true, true, true);
			break;
		case "we_selector_category":
			new (WE().util.jsWindow)(this, url, "we_catselector", -1, -1, WE().consts.size.catSelect.width, WE().consts.size.catSelect.height, true, true, true, true);
			break;
		case "we_users_selector":
			new (WE().util.jsWindow)(this, url, "browse_users", -1, -1, 500, 300, true, false, true);
			break;
		default:
			opener.we_cmd.apply(this, Array.prototype.slice.call(arguments));
	}
}

var weTagWizard = new weTagWizard(tw.tagName);
weTagWizard.allAttributes = tw.attributes;
weTagWizard.reqAttributes = tw.reqAttributes;
weTagWizard.typeAttributeId = tw.typeAttributeId;
weTagWizard.typeAttributeAllows = tw.typeAttributeAllows;
weTagWizard.typeAttributeRequires = tw.typeAttributeRequires;
weTagWizard.needsEndTag = tw.needsEndTag;
