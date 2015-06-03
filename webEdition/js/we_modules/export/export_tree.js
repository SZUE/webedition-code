/**
 * webEdition SDK
 *
 * webEdition CMS
 * $Rev$
 * $Author$
 * $Date$
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
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

function openClose(id) {
	if (id == "") {
		return;
	}

	var eintragsIndex = indexOfEntry(id);
	var status;

	openstatus = (treeData[eintragsIndex].open == 0 ? 1 : 0);

	treeData[eintragsIndex].open = openstatus;
	if (openstatus && treeData[eintragsIndex].loaded != 1) {
		frames.cmd.location = treeData.frameset + "?pnt=load&tab=" + frames.top.table + "&cmd=load&pid=" + id;
		frames.top.openFolders[frames.top.table] += "," + id;
	} else {
		var arr = frames.top.openFolders[frames.top.table].split(",");
		frames.top.openFolders[frames.top.table] = "";
		for (var t = 0; t < arr.length; t++) {
			if (arr[t] != "" && arr[t] != id) {
				frames.top.openFolders[frames.top.table] += "," + arr[t];
			}
		}
		drawTree();
	}
	if (openstatus == 1) {
		treeData[eintragsIndex].loaded = 1;
	}
}

function checkNode(imgName) {
	var object_name = imgName.substring(4, imgName.length);
	for (i = 1; i <= treeData.len; i++) {
		if (treeData[i].id == object_name) {
			frames.tree.populate(treeData[i].id, treeData.table);
			if (treeData[i].checked == 1) {
				if (document.images) {
					eval("if(" + treeData.treeFrame + ".document.images[imgName]) " + treeData.treeFrame + ".document.images[imgName].src=treeData.tree_image_dir+\"check0.gif\";");
				}
				treeData[i].checked = 0;
				if (frames.top.SelectedItems[frames.top.table].length > 1) {
					found = false;
					frames.top.SelectedItems[frames.top.table].length = frames.top.SelectedItems[frames.top.table].length + 1;
					for (z = 0; z < frames.top.SelectedItems[frames.top.table].length; z++) {
						if (frames.top.SelectedItems[frames.top.table][z] == treeData[i].id)
							found = true;
						if (found) {
							frames.top.SelectedItems[frames.top.table][z] = frames.top.SelectedItems[frames.top.table][z + 1];
						}
					}
					frames.top.SelectedItems[frames.top.table].length = frames.top.SelectedItems[frames.top.table].length - 2;
				} else {
					frames.top.SelectedItems[frames.top.table] = [];
				}

				treeData[i].applylayout();
				break;
			} else {
				if (document.images) {
					eval("if(" + treeData.treeFrame + ".document.images[imgName]) " + treeData.treeFrame + ".document.images[imgName].src=treeData.tree_image_dir+\"check1.gif\";");
				}
				treeData[i].checked = 1;
				frames.top.SelectedItems[frames.top.table].push(treeData[i].id);
				treeData[i].applylayout();
				break;
			}
		}

	}
	if (top.content) {
		if (top.content.hot !== undefined) {
			top.content.hot = 1;
		}
	}
	if (!document.images) {
		drawTree();
	}
}

function info(text) {
}

function addSort(object) {
	this.len++;
	for (var i = this.len; i > 0; i--) {
		if (i > 1 && (this[i - 1].order > object.order)) {
			this[i] = this[i - 1];
		} else {
			for (var j = i; j > 0; j--) {
				if (j > 1 && (this[j - 1].order == object.order) && (this[j - 1].text.toLowerCase() > object.text.toLowerCase() || (this[j - 1].typ > object.typ))) {
					this[j] = this[j - 1];
				} else {
					this[j] = object;
					break;
				}
			}
			break;
		}
	}
}

function populate(id, table) {
}

function setHead(tab) {
	frames.top.table = tab;
	frames.top.document.we_form.table.value = tab;
	setTimeout(frames.top.startTree, 100);
}