/* global top, WE */

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

wePropertiesEdit = {
	hasOptions: function (obj) {
		if (obj !== null && obj.options !== null) {
			return true;
		}
		return false;
	},
	moveSelectedOptions: function (from, to, sort, type) {
		sort = sort || true;
		type = type || 'document';

		if (!this.hasOptions(from)) {
			return;
		}
		var index, i, o;
		for (i = 0; i < from.options.length; i++) {
			o = from.options[i];
			if (o.selected) {
				if (!this.hasOptions(to)) {
					index = 0;
				} else {
					index = to.options.length;
				}
				to.options[index] = new Option(o.text, o.value, false, false);
			}
		}
		for (i = (from.options.length - 1); i >= 0; i--) {
			o = from.options[i];
			if (o.selected) {
				from.options[i] = null;
			}
		}
		if (sort) {
			this.sortSelect(from);
			this.sortSelect(to);
		}
		from.selectedIndex = -1;
		to.selectedIndex = -1;
		this.retrieveCsv(type);
	},
	sortSelect: function (obj) {
		var o = [];
		if (!this.hasOptions(obj)) {
			return;
		}
		var i;
		for (i = 0; i < obj.options.length; i++) {
			o[o.length] = new Option(obj.options[i].text, obj.options[i].value, obj.options[i].defaultSelected, obj.options[i].selected);
		}
		if (o.length === 0) {
			return;
		}
		o = o.sort(
						function (a, b) {
							if ((a.text + '') < (b.text + '')) {
								return -1;
							}
							if ((a.text + '') > (b.text + '')) {
								return 1;
							}
							return 0;
						}
		);
		for (i = 0; i < o.length; i++) {
			obj.options[i] = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
		}
	},
	retrieveCsv: function (type) {
		type = type || 'document';
		var mimeListTo = document.getElementById(type === 'document' ? 'mimeListTo' : 'classListTo'),
						mimeStr = '';

		for (var i = 0; i < mimeListTo.options.length; i++) {
			mimeStr += mimeListTo.options[i].value + ',';
		}
		document.getElementById(type === 'document' ? 'we_remCT' : 'we_remClass').value = mimeStr ? ',' + mimeStr : mimeStr;
	}
};

weCollectionEdit = {
	styles: {
		standard: {
			border: '1px solid #888888',
			borderLast: '1px solid #cccccc',
			backgroundColor: '#ffffff'
		},
		okPrev: {
			border: '1px dotted #00ff00',
			borderLast: '1px dotted #00ff00',
			backgroundColor: '#fafffa'
		},
		nokPrev: {
			border: '1px dotted #ff0000',
			borderLast: '1px dotted #ff0000',
			backgroundColor: '#fffafa'
		}
	},
	maxIndex: 0,
	blankItem: {
		list: '',
		grid: ''
	},
	collectionName: '',
	csv: '',
	view: 'grid',
	viewSub: 'broad',
	gridItemDimension: {
		item: 200,
		icon: 32
	},
	itemsPerRow: 4,
	collectionArr: [],
	collectionCsv: '',
	collectionNum: 0,
	sliderDiv: null,
	iconSizes: {},
	ct: {
		grid: null,
		list: null
	},
	we_doc: {
		ID: 0,
		name: '',
		remTable: '',
		remCT: '',
		remClass: '',
		realRemCT: '',
		defaultDir: 0
	},
	dd: {
		fillEmptyRows: true,
		placeholder: null,
		counter: 0,
		isMoveItem: true,
		moveItem: {
			el: null,
			id: 0,
			index: 0,
			next: null,
			pos: 0,
			removed: false
		}
	},
	g_l: {
		info_insertion: ''
	},
	init: function () {
		this.ct.grid = document.getElementById('content_div_grid');
		this.ct.list = document.getElementById('content_div_list');
		this.sliderDiv = document.getElementById('sliderDiv');
		this.numSpan = document.getElementById('numSpan');
		this.itemsPerRow = document.we_form['we_' + this.we_doc.name + '_itemsPerRow'].value;

		/* use this when delivering complete html-items by php
		 for(var i = 0; i < this.ct[this.view].children.length; i++){
		 this.addListenersToItem(this.view, this.ct[this.view].children[i], i+1);
		 }
		 this.reindexAndRetrieveCollection(true);
		 */

		/* use this to render from storage */
		//this.addListenersToContainer();
		this.renderView(true);

	},
	setView: function (view, viewSub) {
		switch(view){
			case 'list':
				this.view = 'list';
				this.viewSub = viewSub === 'minimal' ? 'minimal' : 'broad';
				this.ct.grid.style.display = 'none';
				this.ct.list.style.display = 'block';
				this.sliderDiv.style.display = 'none';
				break;
			case 'grid':
			default:
				this.view = view;
				this.ct.grid.style.display = 'inline-block';
				this.ct.list.style.display = 'none';
				this.sliderDiv.style.display = 'block';
				break;
		}

		document.we_form['we_' + this.we_doc.name + '_view'].value = this.view;
		document.we_form['we_' + this.we_doc.name + '_viewSub'].value = this.viewSub === 'minimal' ? 'minimal' : 'broad';
		this.dd.counter = 0;
		this.renderView(true);
	},
	renderView: function (notSetHot) {
		this.ct[this.view].innerHTML = '';
		this.maxIndex = 0;

		for (var i = 0; i < this.collectionArr.length; i++) {
			var last = (i === (this.collectionArr.length) - 1) && (this.storage['item_' + this.collectionArr[i]].id === -1);
			this.insertItem(null, false, this.storage['item_' + this.collectionArr[i]], this, '', last);
		}
		this.reindexAndRetrieveCollection(notSetHot);
	},
	addListenersToContainer: function () {
		// TODO: drop on container to add at the end of collection
		/*
		 if (this.isDragAndDrop) {
		 var containers = document.getElementsByClassName('collection-content'),
		 container;

		 for (var i = 0; i < containers.length; i++){
		 container = containers[i];
		 container.addEventListener('dragleave', function (e) {
		 //this.leaveDrag('item', view, e, container);
		 container.style.backgroundColor = 'white';
		 }, false);
		 container.addEventListener('drop', function (e) {
		 //this.dropOnItem('item', view, e, container);
		 top.console.log('do drop');
		 }, false);
		 container.addEventListener('dragenter', function (e) {
		 //this.enterDrag('item', view, e, container);
		 container.style.backgroundColor = 'green';
		 }, false);
		 container.addEventListener('dragover', function (e) {
		 container.style.backgroundColor = 'green';
		 e.preventDefault();
		 }, false);
		 }
		 }
		 */
	},
	addListenersToItem: function (view, elem, last) {
		var t = this, item, input, ctrls, space;

		//TODO: grab elems by getElementByClassName instead of counting children...
		if (this.view === 'grid') {
			item = elem.firstChild;
			if(!last){
				item.addEventListener('mouseover', function () {
					t.overMouse('item', view, item);
				}, false);
				item.addEventListener('mouseout', function () {
					t.outMouse('item', view, item);
				}, false);

				ctrls = item.lastChild;
				ctrls.addEventListener('mouseover', function () {
					t.overMouse('btns', view, ctrls);
				}, false);
				ctrls.addEventListener('mouseout', function () {
					t.outMouse('btns', view, ctrls);
				}, false);

				if (this.isDragAndDrop) {
					space = elem.childNodes[1];
					space.addEventListener('drop', function (e) {
						t.dropOnItem('space', view, e, space);
					}, false);
					space.addEventListener('dragover', function (e) {
						t.allowDrop(e);
					}, false);
					space.addEventListener('dragenter', function (e) {
						t.enterDrag('space', view, e, space);
					}, false);
					space.addEventListener('dragleave', function (e) {
						t.leaveDrag('space', view, e, space);
					}, false);
					space.addEventListener('dblclick', function (e) {
						t.dblClick('space', view, e, space);
					}, false);
				}
			}
		} else {
			item = elem;
			input = document.getElementById('yuiAcInputItem_' + item.id.substr(10));
			input.addEventListener('mouseover', function () {
				item.draggable = false;
			});
			input.addEventListener('mouseout', function () {
				item.draggable = true;
			});
		}

		if (this.isDragAndDrop) {
			if(!last){
				item.style.cursor = 'move';
				item.draggable = true;
			}
			item.addEventListener('dragleave', function (e) {
				t.leaveDrag('item', view, e, item);
			}, false);
			item.addEventListener('drop', function (e) {
				t.dropOnItem('item', view, e, item, last);
			}, false);
			item.addEventListener('dragenter', function (e) {
				t.enterDrag('item', view, e, item, last);
			}, false);
			item.addEventListener('dragover', function (e) {
				t.allowDrop(e);
			}, false);
			item.addEventListener('dragstart', function (e) {
				t.startMoveItem(e, view);
			}, false);
			item.addEventListener('dragend', function (e) {
				t.dragEnd(e);
			}, false);
		}
	},
	doClickUp: function (elem) {
		var el = this.getItem(elem);

		if (el.parentNode.firstChild !== el) {
			el.parentNode.insertBefore(el, el.previousSibling);
			this.reindexAndRetrieveCollection();
		}
	},
	doClickDown: function (elem) {
		var el = this.getItem(elem);
		var sib = el.nextSibling;

		if (true || sib) {
			el.parentNode.insertBefore(el.nextSibling, el);
			this.reindexAndRetrieveCollection();
		}
	},
	doClickAdd: function (elem) {
		var el = this.getItem(elem),
						num = 1;//document.getElementById('numselect_' + el.id.substr(10)).value;

		for (var i = 0; i < num; i++) {
			el = this.insertItem(el, false);
		}
		this.reindexAndRetrieveCollection();
	},
	doClickAddItems: function (elem) {
		var el = elem ? this.getItem(elem) : null,
						index = el ? el.id.substr(10) : 0,
						pos = -1;

		if (el) {
			for (var i = 0; i < el.parentNode.childNodes.length; i++) {
				if (el.parentNode.childNodes[i].id == el.id) {
					pos = i;
					break;
				}
			}
		}

		top.we_cmd('addToCollection', 1, WE().consts.tables.TBL_PREFIX + this.we_doc.remTable, this.we_doc.ID, this.we_doc.Path, index, pos);
	},
	doClickDelete: function (elem) {
		var el = this.getItem(elem);

		el.parentNode.removeChild(el);
		this.reindexAndRetrieveCollection();
	},
	doZoomGrid: function (value) {
		var attribDivs = this.ct.grid.getElementsByClassName('toolbarAttribs');
		var iconDivs = this.ct.grid.getElementsByClassName('divInner'), next;

		this.itemsPerRow = 7 - value;
		this.gridItemDimension = this.gridItemDimensions[this.itemsPerRow];
		document.we_form['we_' + this.we_doc.name + '_itemsPerRow'].value = this.itemsPerRow;

		for (var i = 0; i < this.ct.grid.children.length; i++) {
			this.ct.grid.children[i].style.width = this.ct.grid.children[i].style.height = this.gridItemDimension.item + 'px';
			//this.ct['grid'].children[i].style.backgroundSize = Math.max(item.icon.sizeX, item.icon.sizeY) < this.gridItemDimension.item ? 'auto' : 'contain';

			attribDivs[i].style.display = this.itemsPerRow > 5 ? 'none' : 'block';
			if(iconDivs[i].firstChild.tagName === 'BUTTON'){
				iconDivs[i].firstChild.style.fontSize = this.gridItemDimension.btnFontsize + 'px';
				iconDivs[i].firstChild.style.height = this.gridItemDimension.btnHeight + 'px';
			} else {
				iconDivs[i].firstChild.style.fontSize = this.gridItemDimension.icon + 'px';
				if ((next = iconDivs[i].firstChild.nextSibling)) {
					next.style.fontSize = this.gridItemDimension.font + 'px';
				}
			}
		}
	},
	doClickOpenToEdit: function (id) {
		var table = this.we_doc.remTable === 'tblFile' ? WE().consts.tables.FILE_TABLE : WE().consts.tables.OBJECT_FILES_TABLE,
						ct = this.storage['item_' + id].ct;
		WE().layout.weEditorFrameController.openDocument(table, id, ct);
	},
	getPlaceholder: function () {
		if (this.dd.placeholder !== null) {
			return this.dd.placeholder;
		}

		this.dd.placeholder = document.createElement("div");
		this.dd.placeholder.style.backgroundColor = 'white';
		this.dd.placeholder.setAttribute("ondragover", "weCollectionEdit.allowDrop(event)");
		if (this.view === 'grid') {
			this.dd.placeholder.setAttribute("ondrop", "weCollectionEdit.dropOnItem(\'item\',\'grid\',event, this)");
			this.dd.placeholder.style.float = 'left';
			this.dd.placeholder.style.display = 'block';
			this.dd.placeholder.style.height = this.gridItemDimension.item + 'px';
			this.dd.placeholder.style.width = this.gridItemDimension.item + 'px';
			var inner = document.createElement("div");
			inner.style.height = (this.gridItemDimension.item - 14) + 'px';
			inner.style.width = (this.gridItemDimension.item - 18) + 'px';
			inner.style.border = this.styles.standard.border;
			inner.style.borderStyle = 'dotted';
			this.dd.placeholder.appendChild(inner);
		} else {
			this.dd.placeholder.setAttribute("ondrop", "weCollectionEdit.dropOnItem(\'item\',\'grid\',event, this)");
			this.dd.placeholder.style.height = this.viewSub === 'minimal' ? '40px' : '90px';
			this.dd.placeholder.style.margin = '4px 0 0 0';
			this.dd.placeholder.style.border = this.styles.standard.border;
			this.dd.placeholder.style.borderStyle = 'dotted';
		}

		return this.dd.placeholder;
	},
	getItem: function (elem) {
		var itemClass = this.view === 'grid' ? 'gridItem' : 'listItem';

		while (elem.className !== itemClass) {
			elem = elem.parentNode;
			if (elem.className === 'collection-content') {
				return false;
			}
		}

		return elem;
	},
	getItemId: function (elem) {
		var item = this.getItem(elem);

		return item ? item.id.substr(10) : 0;
	},
	insertItem: function (elem, repaint, item, scope, color, last) {
		var t = scope ? scope : this,
			el = elem ? t.getItem(elem) : null,
			mustInsertPathCutLeft = false,
			div, newItem, cmd1, cmd2, cmd3, blank, elPreview, btn;

		color = color ? color : false;
		item = item ? item : this.storage['item_-1'];
		repaint = repaint || false;
		++t.maxIndex;

		if (item.id && !this.storage['item_' + item.id]) {
			this.storage['item_' + item.id] = item;
		}

		div = document.createElement("div");
		document.body.appendChild(div); // we must append temporary div to a visible element to get offsetWidth of some sub elems

		// FIXME: reduce obsolete replacements for listMinimal

		var viewPlusSub = t.view !== 'list' ? 'grid' : (t.viewSub === 'minimal' ? 'listMinimal' : 'list');
		blank = t.blankItem[viewPlusSub].replace(/##INDEX##/g, t.maxIndex).replace(/##ID##/g, item.id).replace(/##PATH##/g, item.path).
			replace(/##CT##/g, item.ct).replace(/##ICONURL##/g, (item.icon ? item.icon.url.replace('%2F', '/') : '')).
			replace(/##ATTRIB_TITLE##/g, item.elements.attrib_title.Dat).replace(/##S_ATTRIB_TITLE##/g, item.elements.attrib_title.state).
			replace(/##ATTRIB_ALT##/g, item.elements.attrib_alt.Dat).replace(/##S_ATTRIB_ALT##/g, item.elements.attrib_alt.state).
			replace(/##META_TITLE##/g, item.elements.meta_title.Dat).replace(/##S_META_TITLE##/g, item.elements.meta_title.state).
			replace(/##META_DESC##/g, item.elements.meta_description.Dat).replace(/##S_META_DESC##/g, item.elements.meta_description.state);

		if (t.view === 'list') {
			blank = blank.replace(/##W_ATTRIB_TITLE##/g, item.elements.attrib_title.write).replace(/##W_ATTRIB_ALT##/g, item.elements.attrib_alt.write).
				replace(/##W_META_TITLE##/g, item.elements.meta_title.write).replace(/##W_META_DESC##/g, item.elements.meta_description.write).
				replace(/##CLASS##/g, (this.viewSub === 'minimal' ? 'minimalListItem' : 'broadListItem'));

			//TODO: list fallback!
			if (item.id === -1) {
				cmd1 = weCmdEnc(weCollectionEdit.selectorCmds[0].replace(/##INDEX##/g, t.maxIndex));
				cmd2 = weCmdEnc(weCollectionEdit.selectorCmds[1].replace(/##INDEX##/g, t.maxIndex));
				cmd3 = weCmdEnc(weCollectionEdit.selectorCmds[2].replace(/##INDEX##/g, t.maxIndex));
			}
			blank = blank.replace(/##CMD1##/g, cmd1).replace(/##CMD2##/g, cmd2).replace(/##CMD3##/g, cmd3);
			div.innerHTML = blank;

			if (item.id === -1) {
				var inners = div.getElementsByClassName('innerDiv');
				for (var i = 0; i < inners.length; i++) {
					inners[i].style.display = 'none';
				}
				div.getElementsByClassName('btn_edit')[0].disabled = 1;
				div.getElementsByClassName('divBtnSelect')[0].style.display = 'block';
			} else {
				div.getElementsByClassName('previewDiv')[0].innerHTML = '';
				if (this.viewSub === 'minimal'){
					div.getElementsByClassName('colContentInput')[0].style.display = 'none';
					div.getElementsByClassName('colContentTextOnly')[0].style.display = 'inline-block';
					div.getElementsByClassName('divBtnEditTextOnly')[0].style.display = 'inline-block';
					mustInsertPathCutLeft = true;
				}
			}

			if ((this.viewSub === 'minimal' || item.ct !== 'image/*') && item.id !== -1) {
				elPreview = div.getElementsByClassName('previewDiv')[0];
				elPreview.innerHTML = WE().util.getTreeIcon(item.ct, false, item.ext);
				elPreview.style.background = 'transparent';
				elPreview.style.display = 'block';
			}
			if(last){
				div.getElementsByClassName('colControls')[0].style.display = 'none';
			}
		} else {
			if (item.id === -1) {
				cmd1 = weCmdEnc(weCollectionEdit.gridBtnCmds[0].replace(/##INDEX##/g, t.maxIndex));
				cmd3 = weCmdEnc(weCollectionEdit.gridBtnCmds[2].replace(/##INDEX##/g, t.maxIndex));
				blank = blank.replace(/##CMD1##/g, cmd1).replace(/##CMD2##/g, '').replace(/##CMD3##/g, cmd3).replace(/##SHOWBTN##/g, 'block');
			} else {
				blank = blank.replace(/##SHOWBTN##/g, 'none');
			}
			div.innerHTML = blank;
			if (item.icon) {
				div.getElementsByClassName('divContent')[0].style.backgroundSize = Math.max(item.icon.sizeX, item.icon.sizeY) < this.gridItemDimension.item ? 'auto' : 'contain';
			}

			if (item.ct !== 'image/*' && item.id !== -1) {
				elPreview = div.getElementsByClassName('divInner')[0];
				elPreview.innerHTML = WE().util.getTreeIcon(item.ct, false, item.ext) + '<div class="divTitle defaultfont" style="font-size:' + this.gridItemDimension.font + 'px;">' + item.name + item.ext + '</div>';
				//<div class="divTitle defaultfont" style="font-size:10px;">Titel: ' + propDesc + '</div>';
				elPreview.getElementsByTagName('SPAN')[0].style.fontSize = this.gridItemDimension.icon + 'px';
				elPreview.style.background = 'transparent';
				elPreview.style.display = 'block';
				elPreview.style.textAlign = 'left';
				elPreview.style.padding = '14% 0 0 10%';
			}

			div.firstChild.style.width = div.firstChild.style.height = t.gridItemDimension.item + 'px';

			div.getElementsByClassName('toolbarAttribs')[0].style.display = this.itemsPerRow > 5 ? 'none' : 'block';
			if (item.id === -1) {
				btn = div.getElementsByClassName('divInner')[0].firstChild;
				if(btn.tagName === 'BUTTON'){
					btn.style.fontSize = this.gridItemDimension.btnFontsize + 'px';
					btn.style.height = this.gridItemDimension.btnHeight + 'px';
				}
				div.getElementsByClassName('toolbarBtns')[0].removeChild(div.getElementsByClassName('toolbarBtns')[0].firstChild);
				div.getElementsByClassName('toolbarAttribs')[0].style.display = 'none';
			}
		}

		document.body.removeChild(div);
		newItem = el ? t.ct[t.view].insertBefore(div.firstChild, el.nextSibling) : t.ct[t.view].appendChild(div.firstChild);

		if(mustInsertPathCutLeft){
			var colContentText = newItem.getElementsByClassName('colContentTextOnly')[0];
			this.addTextCutLeft(colContentText, item.path, colContentText.parentNode.offsetWidth - 10);
		}


		if(last){
			newItem.setAttribute("name", "last");
		}
		this.resetItemColors(newItem);
		t.addListenersToItem(t.view, newItem, last);

		if (repaint) {
			t.reindexAndRetrieveCollection();
		}

		return newItem;
	},
	addItems: function (elem, items, notReplace, notReindex) {
		if (elem === undefined) {
			return false;
		}

		notReindex = notReindex ? true : false;

		var el = this.getItem(elem),
						index = el.id.substr(10),
						rowsFull = false,
						isFirstSet = notReplace !== undefined ? notReplace : false,
						itemsSet = [[], []],
						item, id;

		//set first item on drop row
		if (items.length) {
			/*
			 this.dd.IsDuplicates = document.we_form['check_we_' + this.we_doc.name + '_IsDuplicates'].checked;
			 */
			while (!isFirstSet && items.length) {
				item = items.shift();
				if (this.dd.IsDuplicates === 1 || this.collectionCsv.search(',' + item.id + ',') === -1) {
					var newEl = this.insertItem(el, false, item, this, '#00ee00');
					this.doClickDelete(el);
					el = newEl;
					itemsSet[0].push(item.id);
					isFirstSet = true;
				} else {
					itemsSet[1].push(item.id);
				}
			}
		}

		for (var i = 0; i < items.length; i++) {
			if (this.dd.IsDuplicates || this.collectionCsv.search(',' + items[i].id + ',') === -1) {
				itemsSet[0].push(items[i].id);
				if (this.dd.fillEmptyRows && !rowsFull && el.nextSibling && el.nextSibling.id !== undefined && el.nextSibling.id.substr(0, 10) === this.view + '_item_') {
					index = el.nextSibling.id.substr(10);
					id = this.view === 'grid' ? el.nextSibling.childNodes[2].value : document.getElementById('yuiAcResultItem_' + index).value;
					if (id === -1 || id === 0) {
						//TODO: use insertItem()!
						if (this.view === 'grid') {
							el.nextSibling.childNodes[2].value = items[i].id;
							el.nextSibling.firstChild.style.background = 'url(' + items[i].icon.url('%2F', '/') + ') no-repeat center center';
							el.nextSibling.firstChild.style.backgroundSize = 'contain';
							el.nextSibling.firstChild.title = items[i].path;
						} else {
							document.getElementById('yuiAcInputItem_' + index).value = items[i].path;
							document.getElementById('yuiAcResultItem_' + index).value = items[i].id;
						}
						el = el.nextSibling;
						continue;
					} else {
						rowsFull = true;
					}
				}
				el = this.insertItem(el, false, items[i], null, '#00ee00');
			} else {
				itemsSet[1].push(items[i].id);
			}
		}
		this.reindexAndRetrieveCollection();
		return itemsSet;
	},
	reindexAndRetrieveCollection: function (notSetHot) {
		var ct = this.ct[this.view],
						val, btns_up, btns_down, btns_edit,
						labels = document.getElementsByClassName(this.view + '_label');

		btns_edit = ct.getElementsByClassName('btn_edit');
		if (this.view === 'list') {
			btns_up = ct.getElementsByClassName('btn_up');
			btns_down = ct.getElementsByClassName('btn_down');
		}

		this.collectionCsv = ',';
		this.collectionArr = [];
		this.collectionNum = 0;

		for (var i = 0; i < ct.childNodes.length; i++) {
			switch (this.view) {
				case 'grid':
					ct.childNodes[i].id = 'grid_item_' + (i + 1);
					//labels[i].id = 'label_' + (i+1);
					val = ct.childNodes.length > 1 ? parseInt(document.we_form.collectionItem_we_id[i].value) : parseInt(document.we_form.collectionItem_we_id.value);
					break;
				case 'list':
					val = parseInt(document.getElementById('yuiAcResultItem_' + ct.childNodes[i].id.substr(10)).value);
					if(this.viewSub !== 'minimal'){
						btns_up[i].disabled = i === 0;
						btns_down[i].disabled = (i === (ct.childNodes.length - 1));
					}
					break;
			}
			labels[i].innerHTML = i + 1;


			if (val === 0 || val === -1) {
				this.collectionCsv += -1 + ',';
				this.collectionArr.push(-1);
			} else {
				this.collectionCsv += val + ',';
				this.collectionArr.push(val);
				this.collectionNum++;
			}
		}
		this.numSpan.innerHTML = this.collectionNum;

		if (val !== -1) {
			this.insertItem(ct.lastChild, true, null, this, '', true);//elem, repaint, item, scope, color, last
		}

		if (!this.collectionName) {
			this.collectionName = (WE().consts.tables.TBL_PREFIX + this.we_doc.remTable === WE().consts.tables.FILE_TABLE) ? '_fileCollection' : '_objectCollection';
		}
		document.we_form.elements['we_' + this.we_doc.name + this.collectionName].value = this.collectionCsv;
		if (!notSetHot) {
			WE().layout.weEditorFrameController.getActiveEditorFrame().setEditorIsHot(true);
		}
	},
	hideSpace: function (elem) { // TODO: use classes do define states!
		elem.style.width = '12px';
		elem.style.right = '0px';
		elem.style.border = '1px solid #ffffff';
		elem.style.backgroundColor = '#ffffff';
		elem.style.margin = '0';
		elem.previousSibling.style.right = '14px';
		if (elem.parentNode.nextSibling) {
			elem.parentNode.nextSibling.firstChild.style.left = '0px';
		}
	},
	resetColors: function (scope) {
		var me = scope || this;
		for (var i = 0; i < me.ct[me.view].childNodes.length; i++) {
			me.resetItemColors(me.ct[me.view].childNodes[i], false, me);
		}
	},
	resetItemColors: function (el, color) {
		if (!el) {
			return false;
		}
		color = color || 'standard';
		var set = weCollectionEdit.styles[color];

		switch (this.view) {
			case 'grid':
				el.firstChild.style.border = (el.getAttribute('name') === 'last' ? set.borderLast : set.border);
				el.firstChild.style.backgroundColor = set.backgroundColor;
				break;
			case 'list':
				el.style.border = (el.getAttribute('name') === 'last' ? set.borderLast : set.border);
				el.firstChild.style.backgroundColor = set.backgroundColor;
				break;
		}
	},
	addTextCutLeft: function(elem, text, maxwidth){
		if(!elem){
			return;
		}

		maxwidth = maxwidth || 400;
		text = text ? text : '';
		var i = 2000;
		elem.innerHTML = text;
		while(elem.offsetWidth > maxwidth && i > 0){
			text = text.substr(4);
			elem.innerHTML = '...' + text;
			--i;
		}
		return;
	},
	dblClick: function (type, view, evt, elem) {
		switch (type) {
			case 'space':
				this.doClickAdd(elem);
				break;
			default:
		}
	},
	allowDrop: function (evt) {
		evt.preventDefault();
	},
	enterDrag: function (type, view, evt, elem, last) {
		var el = this.getItem(elem);
		var data = evt.dataTransfer.getData("text") ? evt.dataTransfer.getData("text").split(',') : top.dd.dataTransfer.text.split(',');
		var c, newPos;

		if (this.view === 'grid' && type === 'item') {
			this.outMouse(type, this.view, elem);
		}

		switch (data[0]) {
			case 'moveItem':
				if (!last && type === 'item') {
					c = this.ct[this.view];

					if (!this.dd.moveItem.removed) {
						newPos = [].indexOf.call(c.children, el);
						c.removeChild(this.dd.moveItem.el);
						c.insertBefore(this.getPlaceholder(), c.childNodes[newPos + (newPos >= this.dd.moveItem.pos ? 0 : -1)]);
						this.dd.moveItem.removed = true;
						return false;
					}

					newPos = [].indexOf.call(c.children, el);
					c.removeChild(this.getPlaceholder());
					c.insertBefore(this.getPlaceholder(), c.childNodes[newPos]);
				}
				break;
			case 'dragItem':
			case 'dragFolder':
				if (this.view === 'grid') {
					switch (type) {
						case 'item':
							this.resetColors();
							break;
						case 'space':
							if (elem.parentNode.id.substr(10) % this.itemsPerRow === 0) {
								elem.style.width = '36px';
								elem.previousSibling.style.right = '42px';
								elem.style.margin = '0 0 0 4px';
							} else {
								elem.style.width = '48px';
								elem.style.right = '-22px';
								elem.style.margin = '0 4px 0 4px';
								elem.previousSibling.style.right = '37px';
								if (elem.parentNode.nextSibling) {
									elem.parentNode.nextSibling.firstChild.style.left = '22px';
								}
							}
							break;
					}
					if (data[0] === 'dragFolder' || (!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) !== -1)) {
						this.resetItemColors(el, 'okPrev');
					} else {
						this.resetItemColors(el, 'nokPrev');
					}
				} else {
					this.dd.counter++;
					this.resetColors();
					switch (type) {
						case 'item':
							if (data[0] === 'dragFolder' || (!this.we_doc.remCT || this.we_doc.remCT.search(',' + data[3]) !== -1)) {
								this.resetItemColors(el, 'okPrev');
							} else {
								this.resetItemColors(el, 'nokPrev');
							}
							break;
					}
				}
				break;
			default:
				return;
		}
	},
	leaveDrag: function (type, view, evt, elem) { // TODO: use dd.counter for grid too
		if (this.view === 'list') {
			this.dd.counter--;
			if (this.dd.counter === 0) {
				this.resetItemColors(elem);
			}
		} else {
			var data = evt.dataTransfer.getData("text") ? evt.dataTransfer.getData("text").split(',') : top.dd.dataTransfer.text.split(',');
			switch (data[0]) {
				case 'dragItem':
				case 'dragFolder':
					switch (type) {
						case 'item':
							this.dd.counter--;
							if (this.dd.counter === 0) {
								this.resetItemColors(elem);
							}
							break;
						case 'space':
							this.hideSpace(elem);
							break;
					}
					break;
				default:
					return;
			}
		}
	},
	overMouse: function (type, view, elem) {
		if (view === 'grid') {
			switch (type) {
				case 'item':
					elem.lastChild.style.display = 'block';
					break;
				case 'btns':
					elem.style.opacity = '1';
					break;
			}
		}
	},
	outMouse: function (type, view, elem) {
		if (view === 'grid') {
			switch (type) {
				case 'item':
					elem.lastChild.style.display = 'none';
					break;
				case 'btns':
					elem.style.opacity = '0.8';
					break;
			}
		}
	},
	startMoveItem: function (evt, view) {
		var elem = this.getItem(evt.target),
			position = [].indexOf.call(this.ct[view].children, elem);

		this.view = view;
		this.dd.isMoveItem = true;
		this.dd.moveItem.el = elem;
		this.dd.moveItem.id = elem.id;
		this.dd.moveItem.index = parseInt(elem.id.substr(10));
		this.dd.moveItem.next = elem.nextSibling;
		this.dd.moveItem.pos = position;
		this.dd.moveItem.removed = false;

		top.dd.dataTransfer.text = 'moveItem,' + elem.id;
		evt.dataTransfer.setData('text', 'moveItem,' + elem.id);

		if (this.view === 'grid') {
			this.outMouse('item', this.view, elem.firstChild);
		}
	},
	dropOnItem: function (type, view, evt, elem, last) {
		evt.preventDefault();

		var data = [], el, index;

		if (!evt.dataTransfer.getData("text") && evt.dataTransfer.files.length === 1) {
			data[0] = 'dragItemFromExtern';
		} else {
			data = evt.dataTransfer.getData("text") ? evt.dataTransfer.getData("text").split(',') : top.dd.dataTransfer.text.split(',');
		}

		switch (data[0]) {
			case 'moveItem':
				if(!last){
					this.dd.isMoveItem = false;
					if (this.dd.moveItem.el !== this.getItem(elem)) {
						var indexNextToNewPos = this.getPlaceholder().nextSibling ? this.getPlaceholder().nextSibling.id.substr(10) : 0,
							otherView = view === 'grid' ? 'list' : 'grid';

						this.ct[this.view].replaceChild(this.dd.moveItem.el, this.getPlaceholder());
						this.dd.moveItem.el.firstChild.style.borderColor = 'green';
						this.reindexAndRetrieveCollection();

						setTimeout(function () {
							weCollectionEdit.resetItemColors(weCollectionEdit.dd.moveItem.el);
							weCollectionEdit.resetDdParams();
						}, 200);

						//weCollectionEdit.resetDdParams();
					}
				}
				break;
			case 'dragItem':
			case 'dragFolder':
				el = this.getItem(elem);
				index = el.id.substr(10);

				if (type === 'item') {
					if (this.view === 'list') {
						//el.style.border = '1px solid red';
					}
					//el.firstChild.style.backgroundColor = 'palegreen';
				} else {
					this.hideSpace(elem);
				}

				if (WE().consts.tables.TBL_PREFIX + this.we_doc.remTable == data[1]) {
					if (!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1) {
						this.callForValidItemsAndInsert(index, data[2], false, type !== 'item', el);
						return;
					} else {
						alert("the item you try to drag doesn't match your collection's contenttypes");
						this.resetColors();
					}
				} else {
					alert("the tree you try to drag from doesn't match your collection's table property"); // FIXME: GL()
				}
				setTimeout(weCollectionEdit.resetItemColors, 100, el);
				break;
			case 'dragItemFromExtern':
				var files = evt.dataTransfer.files;
				//weCollectionEdit.we_doc.realRemCT
				if (this.we_doc.realRemCT.search(',' + files[0].type + ',') === -1) {
					alert('wrong type');
					return;
				}

				var parentID = weCollectionEdit.we_doc.defaultDir,
								ct = files[0].type,
								callback;

				el = this.getItem(elem);
				index = el.id.substr(10);
				callback = "WE().layout.weEditorFrameController.getVisibleEditorFrame().weCollectionEdit.callForValidItemsAndInsert(" + index + ", importedDocument.id, 'dummy');self.close();";

				document.presetFileupload = files;
				top.we_cmd("we_fileupload_editor", ct, 1, "", "", callback, parentID, 0, "", true);
				break;
			default:
				return;
		}
	},
	dragEnd: function (evt) {
		if (this.dd.isMoveItem) {
			this.cancelMoveItem();
		}
	},
	cancelMoveItem: function () {
		this.ct[this.view].removeChild(this.getPlaceholder());
		this.dd.moveItem.el.style.borderColor = 'red';
		this.ct[this.view].insertBefore(this.dd.moveItem.el, this.dd.moveItem.next);
		this.reindexAndRetrieveCollection();
		setTimeout(function () {
			weCollectionEdit.resetItemColors(weCollectionEdit.dd.moveItem.el);
			weCollectionEdit.resetDdParams();
			if (this.view === 'list') {

			}
		}, 300);
	},
	resetDdParams: function () {
		this.dd.placeholder = null;
		this.dd.counter = 0;

		this.dd.isMoveItem = false;
		this.dd.moveItem = {
			el: null,
			id: 0,
			index: 0,
			next: null,
			pos: 0,
			removed: false,
		};
	},
	callForValidItemsAndInsert: function (index, csvIDs, message, notReplace) {
		notReplace = notReplace !== undefined ? notReplace : false;
		try {
			if (csvIDs) {
				var postData;
				postData = 'we_cmd[transaction]=' + encodeURIComponent(we_transaction);
				postData += '&we_cmd[id]=' + encodeURIComponent(csvIDs);
				postData += '&we_cmd[collection]=' + encodeURIComponent(this.we_doc.ID);
				postData += '&we_cmd[full]=' + encodeURIComponent(1);
				postData += '&we_cmd[recursive]=' + encodeURIComponent(document.we_form['check_we_' + weCollectionEdit.we_doc.name + '_InsertRecursive'].checked);

				xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function () {
					if (xhr.readyState === 4) {
						if (xhr.status === 200) {
							var respArr = JSON.parse(xhr.responseText);
							if (respArr.length === -1) { // option deactivated: check doublettes for single insert too
								document.getElementById('yuiAcInputItem_' + index).value = respArr[0].path;
								document.getElementById('yuiAcResultItem_' + index).value = respArr[0].id;
								weCollectionEdit.reindexAndRetrieveCollection();
							} else {
								var resp = weCollectionEdit.addItems(document.getElementById(weCollectionEdit.view + '_item_' + index), respArr, notReplace);
								if (message) {
									top.we_showMessage(weCollectionEdit.g_l.info_insertion.replace(/##INS##/, resp[0]).replace(/##REJ##/, resp[1]), 1, window);
								}
							}
							setTimeout(weCollectionEdit.resetColors, 300, weCollectionEdit);
						} else {
							top.console.debug('http request failed');
							return false;
						}
					}
				};
				xhr.open('POST', WE().consts.dirs.WEBEDITION_DIR + 'rpc.php?protocol=json&cmd=GetItemsFromDB&cns=collection', true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xhr.send(postData);
				// set max waiting time
			}
		} catch (e) {
			top.console.debug(e);
		}
	},
	insertImportedDocuments: function (ids) {
		if (ids) {
			this.callForValidItemsAndInsert(this.ct[this.view].lastChild.id.substr(10), ids.join());
		}

	}
};