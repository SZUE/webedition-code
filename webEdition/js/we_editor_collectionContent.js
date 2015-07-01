wePropertiesEdit = {
	hasOptions: function(obj){
		if(obj!=null&&obj.options!=null){ return true; }
		return false;
	},

	moveSelectedOptions: function(from,to, sort, type){
		sort = sort || true;
		type = type || 'document';

		if(!this.hasOptions(from)){ return; }
		for(var i=0;i<from.options.length;i++){
			var o=from.options[i];
			if(o.selected){
				if(!this.hasOptions(to)){
					var index=0;
				}else{
					var index=to.options.length;
				}
				to.options[index]=new Option(o.text,o.value,false,false);
			}
		}
		for(var i=(from.options.length-1);i>=0;i--){
			var o=from.options[i];
			if(o.selected){
				from.options[i]=null;
			}
		}
		if(sort){
			this.sortSelect(from);
			this.sortSelect(to);
		}
		from.selectedIndex=-1;
		to.selectedIndex=-1;
		this.retrieveCsv(type);
	},

	sortSelect: function(obj){
		var o=[];
		if(!this.hasOptions(obj)){ return; }
		for(var i=0;i<obj.options.length;i++){
			o[o.length]=new Option(obj.options[i].text,obj.options[i].value,obj.options[i].defaultSelected,obj.options[i].selected);
		}
		if(o.length==0){ return; }
		o=o.sort(
			function(a,b){
				if((a.text+'')<(b.text+'')){ return -1; }
				if((a.text+'')>(b.text+'')){ return 1; }
				return 0;
			}
		);
		for(var i=0;i<o.length;i++){
			obj.options[i]=new Option(o[i].text,o[i].value,o[i].defaultSelected,o[i].selected);
		}
	},

	retrieveCsv: function(type){
		type = type || 'document';
		var mimeListTo = document.getElementById(type === 'document' ? 'mimeListTo' : 'classListTo'),
			mimeStr = '';

		for(var i = 0; i < mimeListTo.options.length; i++){
			mimeStr += mimeListTo.options[i].value + ',';
		}
		document.getElementById(type === 'document' ? 'we_remCT' : 'we_remClass').value = mimeStr ? ',' + mimeStr : mimeStr;
	}
};

weCollectionEdit = {
	maxIndex: 0,
	blankItem: {
		list : '',
		grid : ''
	},
	collectionName: '',
	csv: '',
	view: 'grid',
	gridItemSize: 200,
	collectionArr: [],
	collectionCsv: '',
	sliderDiv: null,
	iconSizes: [],

	ct: {
		grid: null,
		list: null
	},
	

	we_const: {// FIXME: move such "constants" to webEdition.js ("global" namespace)
		TBL_PREFIX: '',
		FILE_TABLE: '',
		OBJECT_FILES_TABLE: ''
	},

	we_doc: {
		ID: 0,
		name: '',
		remTable: '',
		remCT: '',
		remClass: ''
	},

	dd: {
		fillEmptyRows: true,
		placeholder: null,

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
		info_insertion: 'Inserted: ##INS##\nAs duplicates rejected: ##REJ##\n\nOthers items may have been rejecected because of inapropriate class/mime type.'
	},

	init: function(){
		this.ct.grid = document.getElementById('content_table_grid');
		this.ct.list = document.getElementById('content_table_list');
		this.sliderDiv = document.getElementById('sliderDiv');

		for(var i = 0; i < this.ct.grid.children.length; i++){
			this.addListenersToItem('grid', this.ct.grid.children[i], i+1);
		}

		this.collectionName = (this.we_const.TBL_PREFIX + this.we_doc.remTable === this.we_const.FILE_TABLE) ? '_fileCollection' : '_objectCollection';
		this.collectionCsv = document.we_form.elements['we_' + this.we_doc.name + this.collectionName].value;
		//this.setView('grid');
		//this.renderView();
	},

	setView: function(view){
		this.view = view;
		document.we_form['we_' + this.we_doc.name + '_view'].value = this.view;
		this.ct.grid.style.display = this.view === 'grid' ? 'inline-block' : 'none';
		this.ct.list.style.display = this.view === 'list' ? 'block' : 'none';
		this.sliderDiv.style.display = this.view === 'grid' ? 'block' : 'none';
		this.renderView(false);
	},

	renderView: function(fromServer){
		this.ct[this.view].innerHTML = '';
		this.maxIndex = 0;

		for(var i = 0; i < this.collectionArr.length; i++){
			this.insertItem(null, false, this.storage['item_' + this.collectionArr[i]], this);
		}

		if(this.view === 'list'){
			this.repaintAndRetrieveCsv();
		}
	},

	addListenersToItem: function(view, elem, num){
		var t = this, item, ctrls, space;

		if(view === 'grid'){
			item = elem.firstChild;
			item.addEventListener('drop', function(e){t.dropOnItem('item', view, e, item);}, false);
			item.addEventListener('dragenter', function(e){t.enterDrag('item', view, e, item);}, false);
			item.addEventListener('dragover', function(e){t.allowDrop(e);}, false);
			item.addEventListener('dragleave', function(e){t.leaveDrag('item', view, e, item);}, false);
			item.addEventListener('dragstart', function(e){t.startMoveItem(e, view);}, false);
			item.addEventListener('dragend', function(e){t.dragEnd(e);}, false);
			item.addEventListener('mouseover', function(e){t.overMouse('item', view, item);}, false);
			item.addEventListener('mouseout', function(e){t.outMouse('item', view, item);}, false);

			ctrls = item.lastChild;
			ctrls.addEventListener('mouseover', function(e){t.overMouse('btns', view, ctrls);}, false);
			ctrls.addEventListener('mouseout', function(e){t.outMouse('btns', view, ctrls);}, false);

			space = elem.childNodes[1]; //document.getElementById('grid_space_' + num);
			space.addEventListener('drop', function(e){t.dropOnItem('space', view, e, space);}, false);
			space.addEventListener('dragover', function(e){t.allowDrop(e);}, false);
			space.addEventListener('dragenter', function(e){t.enterDrag('space', view, e, space);}, false);
			space.addEventListener('dragleave', function(e){t.leaveDrag('space', view, e, space);}, false);
		}
	},

	doClickUp: function(elem){
		var el = this.getItem(elem);

		if(el.parentNode.firstChild !== el){
			el.parentNode.insertBefore(el, el.previousSibling);
			this.repaintAndRetrieveCsv();
		}
	},

	doClickDown: function(elem){
		var el = this.getItem(elem);
		var sib = el.nextSibling;

		if(true || sib){
			el.parentNode.insertBefore(el.nextSibling, el);
			this.repaintAndRetrieveCsv();
		}
	},

	doClickAdd: function(elem){
		var el = this.getItem(elem),
			num = 1;//document.getElementById('numselect_' + el.id.substr(10)).value;

		for(var i = 0; i < num; i++){
			el = this.insertItem(el, false);
		}
		this.repaintAndRetrieveCsv();
	},

	doClickAddItems: function(elem){
		var el = this.getItem(elem),
			index = el.id.substr(10),
			pos = -1;

		for(var i = 0; i < el.parentNode.childNodes.length; i++){
			if(el.parentNode.childNodes[i].id == el.id){
				pos = i;
				break;
			}
		}

		top.we_cmd('addToCollection', 1, this.we_const.TBL_PREFIX + this.we_doc.remTable, this.we_doc.ID, this.we_doc.Path, index, pos);
	},

	doClickDelete: function(elem){
		var el = this.getItem(elem);

		el.parentNode.removeChild(el);
		this.repaintAndRetrieveCsv();
	},

	doZoomGrid: function(value){
		this.gridItemSize = this.iconSizes[7 - value];
		document.we_form['we_' + this.we_doc.name + '_itemsPerRow'].value = (7 - value);
		for(var i = 0; i < this.ct['grid'].children.length; i++){
			this.ct['grid'].children[i].style.width = this.ct['grid'].children[i].style.height = this.gridItemSize + 'px';
		}
	},

	doClickOpenToEdit: function(id){
		var table = this.we_doc.remTable === 'tblFile' ? this.we_const.FILE_TABLE : this.we_const.OBJECT_FILES_TABLE,
			ct = this.storage['item_' + id].ct;
		top.weEditorFrameController.openDocument(table,id,ct);
	},

	getPlaceholder: function(){
		if(this.dd.placeholder !== null){
			return this.dd.placeholder;
		}

		this.dd.placeholder = document.createElement("div");
		this.dd.placeholder.style.backgroundColor = 'white';
		this.dd.placeholder.setAttribute("ondragover","weCollectionEdit.allowDrop(event)");
		if(this.view === 'grid'){
			this.dd.placeholder.setAttribute("ondrop","weCollectionEdit.dropOnItem(\'item\',\'grid\',event, this)");
			this.dd.placeholder.style.float = 'left';
			this.dd.placeholder.style.display = 'block';
			this.dd.placeholder.style.height = this.gridItemSize + 'px';
			this.dd.placeholder.style.width = this.gridItemSize + 'px';
			var inner = document.createElement("div");
			inner.style.height = (this.gridItemSize - 14) + 'px';
			inner.style.width = (this.gridItemSize - 18) + 'px';
			inner.style.border = '1px dotted #006db8';
			this.dd.placeholder.appendChild(inner);
		} else {
			this.dd.placeholder.setAttribute("ondrop","weCollectionEdit.dropOnItem(\'item\',\'grid\',event, this)");
			this.dd.placeholder.style.border = '1px solid #006db8';
			this.dd.placeholder.style.height = '88px';
			this.dd.placeholder.style.width = '804px';
			this.dd.placeholder.style.marginTop = '4px';
		}

		return this.dd.placeholder;
	},

	getItem: function(elem){
		while(elem.className !== 'drop_reference' && elem.className !== 'content_table'){
			elem = elem.parentNode;
		}

		return elem;
	},

	insertItem: function(elem, repaint, item, scope){
		var t = scope ? scope : this,
			el = elem ? t.getItem(elem) : null,
			div, newElem, cmd1, cmd2,
			id = item && item.id ? item.id : -1,
			path = item && item.path ? item.path : '',
			ct = item && item.ct ? item.ct : 'image/*',
			iconSrc = item && item.icon ? item.icon.url : '',
			alt = item && item.elements.attrib_alt.Dat ? item.elements.attrib_alt.Dat : this.g_l['element_not_set'],
			title = item && item.elements.attrib_title.Dat ? item.elements.attrib_title.Dat : this.g_l['element_not_set'],
			state_alt = item && item.elements.attrib_alt.Dat ? item.elements.attrib_alt.state : 'red',
			state_title = item && item.elements.attrib_title.Dat ? item.elements.attrib_title.state : 'red';

		repaint = repaint || false;
		++t.maxIndex;

		if(id && !this.storage['item_' + id]){
			this.storage['item_' + id] = item;
		}

		div = document.createElement("div");
		div.innerHTML = t.blankItem[t.view].replace(/##INDEX##/g, t.maxIndex).replace(/##ID##/g, id).replace(/##PATH##/g, path).
				replace(/##CT##/g, ct).replace(/##ICONURL##/g, iconSrc.replace('%2F', '/')).replace(/##ATTRIB_ALT##/g, alt).
				replace(/##ATTRIB_TITLE##/g, title).replace(/##S_ATTRIB_ALT##/g, state_alt).replace(/##S_ATTRIB_TITLE##/g, state_title);

		if(t.view === 'list'){
			//TODO: list fallback!
			cmd1 = weCmdEnc(weCollectionEdit.selectorCmds[0].replace(/##INDEX##/g, t.maxIndex));
			cmd2 = weCmdEnc(weCollectionEdit.selectorCmds[1].replace(/##INDEX##/g, t.maxIndex));
		} else {
			// TODO: use replace here too!
			//div.firstChild.firstChild.style.background = 'url(' + item.iconSrc.replace('%2F', '/') + ') no-repeat center center';
			//div.firstChild.firstChild.style.backgroundSize = 'contain';

			div.firstChild.style.width = div.firstChild.style.height = t.gridItemSize + 'px';
			t.addListenersToItem('grid', div.firstChild);
		}
		newElem = el ? t.ct[t.view].insertBefore(div.firstChild, el.nextSibling) : t.ct[t.view].appendChild(div.firstChild);

		

		if(repaint){
			t.repaintAndRetrieveCsv();
		}

		return newElem;
	},

	addItems: function(elem, items, notReplace){
		if(elem === undefined){
			return false;
		}

		var el = this.getItem(elem),
			index = el.id.substr(10),
			rowsFull = false,
			isFirstSet = notReplace !== undefined ? notReplace : false,
			itemsSet = [[],[]],
			item, id;

		//set first item on drop row
		if(items.length){
			/*
			this.dd.IsDuplicates = document.we_form['check_we_' + this.we_doc.name + '_IsDuplicates'].checked;
			*/
			while(!isFirstSet && items.length){
				var item = items.shift();
				if(this.dd.IsDuplicates === 1 || this.collectionCsv.search(',' + item.id + ',') === -1){
					var newEl = this.insertItem(el, true, item, this);
					this.doClickDelete(el);
					el = newEl;
					/*
					if(this.view === 'grid'){
						
						//var next = el.nextSibling;top.console.debug('next: ', next);
						
						var div = document.getElementById('grid_item_' + index);
						div.firstChild.style.background = 'url(' + item.iconSrc.replace('%2F', '/') + ') no-repeat center center';
						div.firstChild.style.backgroundSize = 'contain';
						div.firstChild.title = item.path;
						//TODO: name id-fiels using index from item-id and rename when reordering...
						div.childNodes[2].value = item.id;
						
					} else {
						this.insertItem(el, true, item, this);
						this.doClickDelete(el);
					}
					*/
					itemsSet[0].push(item.id);
					isFirstSet = true;
				} else {
					itemsSet[1].push(item.id);
				}
			}
		}
		

		for(var i = 0; i < items.length; i++){
			if(this.dd.IsDuplicates || this.collectionCsv.search(',' + items[i].id + ',') === -1){
				itemsSet[0].push(items[i].id);
				if(this.dd.fillEmptyRows && !rowsFull && el.nextSibling && typeof el.nextSibling.id !== 'undefined' && el.nextSibling.id.substr(0, 10) === this.view + '_item_'){
					index = el.nextSibling.id.substr(10);
					id = this.view === 'grid' ? el.nextSibling.childNodes[2].value : document.getElementById('yuiAcResultItem_' + index).value;
					if(id === -1 || id === 0){
						//TODO: use insertItem()!!
						if(this.view === 'grid'){
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
				el = this.insertItem(el, false, items[i]);
			} else {
				itemsSet[1].push(items[i].id);
			}
		}
		this.repaintAndRetrieveCsv(this.view);
		return itemsSet;
	},

	repaintAndRetrieveCsv: function(){
		var ct = this.ct[this.view], 
			row, item, index, csv = ',', val, btns, arr = [];

		switch(this.view){
			case 'grid':
				
				var labels = document.getElementsByName('el_label');
				for(var i = 0; i < ct.childNodes.length; i++){
					labels[i].innerHTML = i+1;
					
					/*
					ct.childNodes[i].id = 'grid_item_' + (i+1);
					labels[i].innerHTML = i+1;
					labels[i].id = 'label_' + (i+1);
					*/

					val = ct.childNodes.length > 1 ? parseInt(document.we_form.collectionItem_we_id[i].value) : parseInt(document.we_form.collectionItem_we_id.value);
					csv += (val !== 0 ? val : -1) + ',';
					arr.push((val !== 0 ? val : -1));

				}
				break;
			case 'list':
				for(var i = 0; i < ct.childNodes.length; i++){
					row = ct.childNodes[i];
					//btns = row.getElementsByTagName('BUTTON');
					index = row.id.substr(10);
					val = parseInt(document.getElementById('yuiAcResultItem_' + index).value);
					csv += (val !== 0 ? val : -1) + ',';
					arr.push((val !== 0 ? val : -1));
					document.getElementById('label_' + index).innerHTML = i + 1;
					/*
					btns[2].disabled = (val === - 1);
					btns[4].disabled = (i === 0);
					btns[5].disabled = (i === (ct.childNodes.length - 1));
					btns[6].disabled = (ct.childNodes.length === 1);
					*/
				}
				break;
		}
		if(val !== -1){
			if(this.view === 'grid'){
				this.insertItem(ct.lastChild, true);
			} else {
				this.insertItem(ct.lastChild, true);
			}
		}

		if(!this.collectionName){
			this.collectionName = (this.we_const.TBL_PREFIX + this.we_doc.remTable === this.we_const.FILE_TABLE) ? '_fileCollection' : '_objectCollection';
		}
		document.we_form.elements['we_' + this.we_doc.name + this.collectionName].value = csv;
		this.collectionCsv = csv;
		this.collectionArr = arr;
		top.weEditorFrameController.getActiveEditorFrame().setEditorIsHot(true);
	},

	hideSpace: function(elem){
		elem.style.width = '12px';
		elem.style.right = '0px';
		elem.style.border = '1px solid white';
		elem.style.margin = '0';
		elem.previousSibling.style.right = '14px';
		elem.parentNode.nextSibling.firstChild.style.left = '0px';
	},

	allowDrop: function(evt){
		evt.preventDefault();
	},

	enterDrag: function(type, view, evt, elem){
		var el = this.getItem(elem),//this.getItem(evt.target),
			data = evt.dataTransfer.getData("text").split(',');

		this.view = view;
		if(this.view === 'grid' && type === 'item'){
			this.outMouse(type, this.view, elem);
		}

		switch(data[0]){
			case 'moveItem':
				if(type === 'item'){
					var c = this.ct[this.view],
						newPos;

					if(!this.dd.moveItem.removed){
						newPos = [].indexOf.call(c.children, el);
						c.removeChild(this.dd.moveItem.el);
						c.insertBefore(this.getPlaceholder(), c.childNodes[newPos + (newPos >= this.dd.moveItem.pos ? 0 : -1)]);
						this.dd.moveItem.removed = true;
						return;
					}

					newPos = [].indexOf.call(c.children, el);
					c.removeChild(this.getPlaceholder());
					c.insertBefore(this.getPlaceholder(), c.childNodes[newPos]);
				}
				break;
			case 'dragItem':
			case 'dragFolder':
				if(this.view === 'grid'){
					switch(type){
						case 'item':
							var c = this.ct[this.view], 
									index;
							for(var i = 0; i < c.childNodes.length; i++){
								el.firstChild.style.border = '1px solid #006db8';
							}

							if(!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1){
								el.firstChild.style.border = '1px solid #00cc00';
							} else {
								el.firstChild.style.border = '1px solid red';
							}
							break;
						case 'space':
							if(!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1){
								elem.style.width = '36px';
								elem.style.right = '-16px';
								elem.style.margin = '0 4px 0 4px';
								elem.style.border = '1px dotted #00cc00';
								elem.previousSibling.style.right = '28px';
								elem.parentNode.nextSibling.firstChild.style.left = '14px';
							} else {
								//el.style.border = '1px solid red';
							}
					}
				} else {
					
					switch(type){
						case 'item':
							var t = this.ct[this.view], index;

							for(var i = 0; i < t.childNodes.length; i++){
								t.childNodes[i].style.border = '1px solid #006db8';
							}

							if(!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1){
								el.style.border = '1px solid #00cc00';
							} else {
								el.style.border = '1px solid red';
							}
							break;
						/*
						case 'space':
							if(!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1){
								elem.style.width = '36px';
								elem.style.margin = '0 4px 0 4px';
								elem.style.border = '1px dotted #00cc00';
								elem.previousSibling.style.width = '224px';
								elem.parentNode.nextSibling.firstChild.style.width = '224px';
							} else {
								//el.style.border = '1px solid red';
							}
						*/
					}
					
				}
				break;
			default:
				return;
		}
	},
			
	leaveDrag: function(type, view, evt, elem){
		var data = evt.dataTransfer.getData("text").split(',');

		switch(data[0]){
			case 'dragItem':
			case 'dragFolder':
				switch(type){
					case 'item':
						elem.style.border = '1px solid #006db8';
						break;
					case 'space':
						this.hideSpace(elem);
						break;
				}
				break;
			default:
				return;
		}
	},

	overMouse: function(type, view, elem){
		if(view === 'grid'){
			switch(type){
				case 'item':
					elem.firstChild.style.display = 'block';
					break;
				case 'btns':
					elem.style.opacity = '1';
					break;
			}
		}
	},

	outMouse: function(type, view, elem){
		if(view === 'grid'){
			switch(type){
				case 'item':
					elem.firstChild.style.display = 'none';
					break;
				case 'btns':
					elem.style.opacity = '0.8';
					break;
			}
		}
	},

	startMoveItem: function(evt, view) {
		var el = this.getItem(evt.target),
			index = parseInt(el.id.substr(10)),

		position = [].indexOf.call(this.ct[view].children, el);

		this.view = view;
		this.dd.isMoveItem = true;
		this.dd.moveItem.el = el;
		this.dd.moveItem.id = el.id;
		this.dd.moveItem.index = index;
		this.dd.moveItem.next = el.nextSibling;
		this.dd.moveItem.pos = position;
		this.dd.moveItem.removed = false;

		evt.dataTransfer.setData('text', 'moveItem,' + el.id);

		if(this.view === 'grid'){
			this.outMouse('item', this.view, el.firstChild);
		}
	},

	dropOnItem: function(type, view, evt, elem){
		evt.preventDefault();

		var data = evt.dataTransfer.getData("text").split(','),
			el, index;

		switch(data[0]){
			/*
			case 'moveRow':
				this.dd.isMoveItem = false;
				document.getElementById('content_table').replaceChild(this.dd.moveItem.el, this.getPlaceholder());
				this.repaintAndRetrieveCsv();
				this.dd.moveItem.el.style.borderColor = 'green';
				setTimeout(function(){
					weCollectionEdit.dd.moveItem.el.style.borderColor = '#006db8';
					weCollectionEdit.resetDdParams();
				}, 200);
				break;
			*/
			case 'moveItem':
				this.dd.isMoveItem = false;
				if(this.dd.moveItem.el !== this.getItem(elem)){
					var indexNextToNewPos = this.getPlaceholder().nextSibling ? this.getPlaceholder().nextSibling.id.substr(10) : 0,
						otherView = view === 'grid' ? 'list' : 'grid';

					this.ct[this.view].replaceChild(this.dd.moveItem.el, this.getPlaceholder());
					this.dd.moveItem.el.firstChild.style.borderColor = 'green';

					// move item in second view!
					/*
					if(indexNextToNewPos){
						this.ct[otherView].insertBefore(this.ct[otherView].removeChild(document.getElementById(otherView + '_item_' + this.dd.moveItem.index)),
								document.getElementById(otherView + '_item_' + indexNextToNewPos)
							);
					} else {
						this.ct[otherView].replaceChild(this.ct[otherView].removeChild(document.getElementById(otherView + '_item_' + this.dd.moveItem.index)),
								this.ct[otherView].lastChild
							);
					}
					*/
					this.repaintAndRetrieveCsv(view);

					setTimeout(function(){
						weCollectionEdit.dd.moveItem.el.firstChild.style.borderColor = '#006db8';
						weCollectionEdit.resetDdParams();
					}, 200);
				}
				break;
			case 'dragItem':
			case 'dragFolder':
					el = this.getItem(elem);
					index = el.id.substr(10);
					
					if(type === 'item'){
						if(this.view === 'list'){
							//el.style.border = '1px solid red';
						}
						el.firstChild.style.backgroundColor = 'palegreen';
					} else {
						this.hideSpace(elem);
					}

					if(this.we_const.TBL_PREFIX + this.we_doc.remTable === data[1]){
						if(!this.we_doc.remCT || data[3] === 'folder' || this.we_doc.remCT.search(',' + data[3]) != -1){
							this.callForValidItemsAndInsert(index, data[2], false, type !== 'item', el);
						} else {
							
							//alert("the item you try to drag from doesn't match your collection's content types");
						}
					} else {
						alert("the tree you try to drag from doesn't match your collection's table property");
					}
				break;
			default:
				return;
		}

	},

	dragEnd: function(evt){
		if(this.dd.isMoveItem){
			this.cancelMoveItem();
		}
	},

	cancelMoveItem: function(){
		this.ct[this.view].removeChild(this.getPlaceholder());
		this.dd.moveItem.el.style.borderColor = 'red';
		this.ct[this.view].insertBefore(this.dd.moveItem.el, this.dd.moveItem.next);
		this.repaintAndRetrieveCsv(this.view);
		setTimeout(function(){
			weCollectionEdit.dd.moveItem.el.style.borderColor = '#006db8';
			weCollectionEdit.resetDdParams();
		}, 300);
	},

	resetDdParams: function(){
		this.dd.placeholder = null;

		this.dd.isMoveItem = false;
		this.dd.moveItem = {
			el: null,
			id: 0,
			index: 0,
			next: null,
			pos: 0,
			removed: false
		};
	},

	callForValidItemsAndInsert: function (index, csvIDs, message, notReplace) {
		notReplace = notReplace !== undefined ? notReplace : false;
		try {
			if(csvIDs){
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
							if(respArr.length === -1){ // option deactivated: check doublettes for single insert too
								document.getElementById('yuiAcInputItem_' + index).value = respArr[0].path;
								document.getElementById('yuiAcResultItem_' + index).value = respArr[0].id;
								weCollectionEdit.repaintAndRetrieveCsv(this.view);
							} else {
								var resp = weCollectionEdit.addItems(document.getElementById(weCollectionEdit.view + '_item_' + index), respArr, notReplace);
								if(message){
									top.we_showMessage(weCollectionEdit.g_l.info_insertion.replace(/##INS##/, resp[0]).replace(/##REJ##/, resp[1]), 1, window);
								}
							}
						} else {
							top.console.debug('http request failed');
							return false;
						}
					}
				};
				xhr.open('POST', '/webEdition/rpc/rpc.php?protocol=json&cmd=GetItemsFromDB&cns=collection', true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xhr.send(postData);
				// set max waiting time
			}
		} catch (e) {
			top.console.debug(e);
		}
	},

	insertImportedDocuments : function(ids) {
		top.console.debug(this.we_doc.ID, ids);
	}
};