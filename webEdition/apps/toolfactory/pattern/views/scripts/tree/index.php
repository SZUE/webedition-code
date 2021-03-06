

$controller = Zend_Controller_Front::getInstance();
$appName = $controller->getParam('appName');

$page = we_ui_layout_HTMLPage::getInstance();

$nodes = array();
$table = <?php echo (!empty($TABLECONSTANT) && $TABLEEXISTS) ? $TABLECONSTANT : "''"; ?>;

$TreeDiv = new we_ui_layout_Div();
$TreeDiv->setId('TreeDiv_<?php echo $TOOLNAME; ?>');
$TreeDiv->setStyle('margin:5px 0px 0px 5px;overflow:auto;');


$tree = new <?php echo $TOOLNAME; ?>_ui_controls_Tree();
$tree->setId('tree_<?php echo $TOOLNAME; ?>');
if($table!="") {
	$tree->setTable($table);
}

$InfoField = new we_ui_layout_Div();
$InfoField->setId('infoField');
$InfoField->setClass('editfooter');

$InfoFieldId = new we_ui_layout_Div();
$InfoFieldId->setId('infoFieldId');
$InfoFieldId->setStyle('margin:5px 10px;font-size:11px;');

$js = '
	//var weTree = new we_ui_controls_Tree("' . $tree->getId() . '"); f�r yui 2.5
	var weTree;

//next 2 functions are for IE9
function subscribeLabelClick(){
	tree_' . $tree->getId() . '.subscribe("labelClick", function(node) {
		weTree.unmarkAllNodes();
		weTree.markNode(node.data.id, true);
		tree_' . $tree->getId() . '_activEl = node.data.id;
		weCmdController.fire({cmdName:"app_'.$appName.'_open", id:node.data.id});
		return false;
	});
	weTree = new we_ui_controls_Tree("' . $tree->getId() . '");
}
function delaySubcriptionForIE9(){
	window.setTimeout(subscribeLabelClick, 1000);
}
	YAHOO.util.Event.addListener(window, "load", delaySubcriptionForIE9());

	YAHOO.util.Event.addListener("'.$TreeDiv->getId().'", "mouseover", function(e) {
		var elTarget = YAHOO.util.Event.getTarget(e);
	    var a = "ygtvlabelel";
	    var span = "spanText_' . $tree->getId() . '_";
        if(a == elTarget.id.substring(0, a.length) || span == elTarget.id.substring(0, span.length)) {
        	var node = tree_' . $tree->getId() . '.getNodeByProperty(\'title\',elTarget.title);
            showInfoId(node.data.id);
        } else {
            showInfoId("");
        }
	});



	function showInfoId(text) {
		var field = document.getElementById("'.$InfoFieldId->getId().'");
		if(text!=""){
			field.style.display="block";
			field.innerHTML = "ID:"+text;
		}
		else {
			field.style.display="none";
			field.innerHTML = "";
		}
 	}

 	weGetTop().onload = resizeTreeDiv;
 	weGetTop().onresize = resizeTreeDiv;

 	function resizeTreeDiv(){
 		var h = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight;
		document.getElementById(\''.$TreeDiv->getId().'\').style.height = (h-50)+"px";
	}

	weEventController.register("delete", function(data, sender) {
		if (data.model.ID) {
			weTree.removeNode(data.model.ID);
		}

	});

	weEventController.register("save", function(data, sender) {
		if (data.model.ID) {
			if (data.newBeforeSaving) {
				if (data.model.IsFolder) {
					weTree.addNode(data.model.ID, data.model.Text, "folder", data.model.ParentID, data.model.Published,data.model.Status);
				} else {
					weTree.addNode(data.model.ID, data.model.Text, "' . $tree->getTreeIconClass($appName.'/item') . '", data.model.ParentID, data.model.Published,data.model.Status);
				}
			} else {
				var newParentId = data.model.ParentID;
				var oldParentId = weTree.getParentId(data.model.ID);

				var newLabel = data.model.Text;
				var oldLabel = weTree.getLabel(data.model.ID);

				if (newParentId != oldParentId) {
					weTree.moveNode(data.model.ID, newParentId);
				}

				if (newLabel != oldLabel) {
					weTree.renameNode(data.model.ID, newLabel);
				}
			}
		}
	});
    weEventController.register("markpublished", function(data, sender) {
			weTree.markNodeP(data, 1);
	});
	weEventController.register("markunpublished", function(data, sender) {
		weTree.markNodeP(data, 0);
	});
';

$TreeDiv->addElement($tree);
$page->addElement($TreeDiv);
$page->addInlineJS($js);




$InfoField->addElement($InfoFieldId);
$page->addElement($InfoField);

echo $page->getHTML();