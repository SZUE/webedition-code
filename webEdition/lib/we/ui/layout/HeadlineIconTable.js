/**
 * webEdition SDK
 *
 * This source is part of the webEdition SDK. The webEdition SDK is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU Lesser General Public License can be found at
 * http://www.gnu.org/licenses/lgpl-3.0.html.
 * A copy is found in the textfile 
 * webEdition/licenses/webEditionSDK/License.txt
 *
 *
 * @category   we
 * @package    we_ui
 * @subpackage we_ui_layout
 * @copyright  Copyright (c) 2008 living-e AG (http://www.living-e.com)
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */


/**
 * Class for handling we_ui_layout_HeadlineIconTable
 * 
 * @category   we
 * @package    we_ui
 * @subpackage we_ui_layout
 * @copyright  Copyright (c) 2008 living-e AG (http://www.living-e.com)
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
function we_ui_layout_HeadlineIconTable(idOrObject) {
	/**
	 * represents the element in the DOM
	 *
	 * @var object
	 */
	this.element = idOrObject;
	if (typeof(this.element) != "object") {
		this.element = document.getElementById(idOrObject);
	}

	/**
	 * id of the element
	 *
	 * @var object
	 */
	this.id = this.element.id;
	
	/**
	 * id prefix for the main div
	 *
	 * @var string
	 */
	this.divPrefix = this.id + "_div_";

	/**
	 * array with main divs of the table
	 *
	 * @var array
	 */
	this.divs = new Array();
	// setup divs
	var divs = this.element.getElementsByTagName("DIV");
	for(var i = 0; i<divs.length; i++){
		if(divs[i].id.length > this.divPrefix.length && divs[i].id.substring(0,this.divPrefix.length) == this.divPrefix){
			this.divs.push(divs[i]);
		}
	}
	
	/**
	 * returns the number of rows
	 *
	 * @return integer
	 */
	this.count = function() {
		return this.divs.length;
	}
	
	/**
	 * returns the last index of the table
	 *
	 * @return integer
	 */
	this.getLastIndex = function() {
		var div = this.divs[this.divs.length-1];
		return parseInt(div.id.substring(this.divPrefix.length,div.id.length));
	}
	
	/**
	 * deletes a row with the given index
	 *
	 * @param integer index
	 * @return void
	 */
	this.deleteRow = function(index) {
		var div = document.getElementById(this.divPrefix + index);
		var mainTD = document.getElementById(this.id + "_td");
		mainTD.removeChild(div);
	}
	
	/**
	 * appends a row
	 *
	 * @param string divInnerHTML HTML generated by $we_ui_layout_HeadlineIconTablePart->getJSHTML()
	 * @param integer marginLeft
	 * @param boolean insertRuleBefore if a rule should be inserted before
	 * @return void
	 */
	this.appendRow = function(divInnerHTML, marginLeft, insertRuleBefore) {
		var lastNum = this.getLastIndex();
		var i = (lastNum + 1);
		
		divInnerHTML = divInnerHTML.replace(/__INDEX__/g, i);
		
		var mainDiv = document.createElement("DIV");
		
		mainDiv.style.cssText = "margin-left:" + marginLeft + "px";
		mainDiv.id = this.divPrefix + i;
		mainDiv.innerHTML = divInnerHTML;

		var mainTD = document.getElementById(this.id + "_td");
		mainTD.appendChild(mainDiv);
		
		if (navigator.userAgent.indexOf('MSIE') != -1) {
			mainTD.appendChild(document.createElement("BR"));
		}

		var lastDiv = document.createElement("DIV");
		lastDiv.style.cssText = "margin:10px 0;clear:both;";
		mainTD.appendChild(lastDiv);
		if(insertRuleBefore && (lastNum != -1)){
			var rule = document.createElement("DIV");
			rule.className = "we_ui_layout_HeadlineIconTable_Rule";
			var preDIV = document.getElementById(this.divPrefix + i);
			preDIV.parentNode.insertBefore(rule, preDIV);
		}

	}
}

