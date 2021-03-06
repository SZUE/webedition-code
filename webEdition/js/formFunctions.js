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

// functions for normal input fields

weInput = function () {
};

weInput.setValue = function (elementName, optionValue) {
	if ((elem = document.we_form[elementName])) {
		elem.value = optionValue;
	}
};

weInput.getValue = function (elementName) {
	if ((elem = document.we_form[elementName])) {
		return elem.value;
	}
};

// select
function weSelect() {
}

weSelect.addOption = function (selectName, optionValue, optionText) {
	if ((elem = document.we_form[selectName])) {
		var newOpt = document.createElement("option");
		if (optionValue) {
			newOpt.setAttribute("value", optionValue);
		} else {
			newOpt.setAttribute("value", optionText);
		}

		var textNode = document.createTextNode(optionText);
		newOpt.appendChild(textNode);

		elem.appendChild(newOpt);
	}
};

weSelect.removeOptions = function (selectName) {
	var sel = document.we_form[selectName];
	if (sel) {
		sel.innerHTML = '';
	}
};

weSelect.setOptions = function (selectName, optionsList) {
	// first remove all existing options
	weSelect.removeOptions(selectName);

	var sel = document.we_form[selectName];

	if (sel) {
		// now add all new options
		for (var i = 0; i < optionsList.length; i++) {

			weSelect.addOption(selectName, optionsList[i].value, optionsList[i].text);
			if (i == (optionsList.length - 1)) {
				weSelect.selectOption(selectName, optionsList[i].value);
			}
		}
	}
};

weSelect.updateOption = function (selectName, optionValue, newText, newValue) {
	if ((elem = document.we_form[selectName])) {
		for (i = 0; i < elem.options.length; i++) {
			if (elem.options[i].value == optionValue) {
				if (newValue) {
					elem.options[i].value = newValue;
				}
				elem.options[i].innerHTML = '';
				var textNode = document.createTextNode(newText);
				elem.options[i].appendChild(textNode);
			}
		}
	}
};

weSelect.removeOption = function (selectName, optionValue) {
	if ((elem = document.we_form[selectName])) {
		for (i = 0; i < elem.options.length; i++) {
			if (elem.options[i].value == optionValue) {
				elem.removeChild(elem.options[i]);
			}
		}
	}
};

weSelect.selectOption = function (selectName, optionValue) {
	if ((elem = document.we_form[selectName])) {
		for (i = 0; i < elem.options.length; i++) {
			//first, remove attribute 'selected' from predefined or default selected options; e. g. setOptions()
			if (elem.options[i].hasAttribute("selected")){
				elem.options[i].removeAttribute("selected");
			}
			
			if (elem.options[i].value == optionValue) {
				elem.selectedIndex = i;
				elem.options[i].setAttribute("selected", "selected");
			}
		}
	}
};