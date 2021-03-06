<?php
/* webEdition SDK
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
 * @package none
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */

/**
 * Class to display a YUI tree
 *
 * @category   we
 * @package none
 * @subpackage we_ui_controls
 * @license    http://www.gnu.org/licenses/lgpl-3.0.html  LGPL
 */
class we_ui_controls_Tree extends we_ui_abstract_AbstractElement{
	/**
	 * _nodes attribute
	 *
	 * @var array
	 */
	protected $_nodes = array();

	/**
	 * _openNodes attribute
	 *
	 * @var array
	 */
	protected $_openNodes = array();

	/**
	 * _sessionName attribute
	 *
	 * @var array
	 */
	protected $_sessionName = '';

	/**
	 * _table attribute
	 *
	 * @var string
	 */
	protected $_table = '';

	/**
	 * Retrieve open Nodes
	 *
	 * @return array
	 */
	public function getOpenNodes(){
		return $this->_openNodes;
	}

	/**
	 * set open Nodes
	 */
	public function setOpenNodes($_openNodes){
		$this->_openNodes = $_openNodes;
	}

	/**
	 * Retrieve Nodes
	 *
	 * @return array
	 */
	public function getNodes(){
		return $this->_nodes;
	}

	/**
	 * Retrieve Nodes
	 */
	public function setNodes($_nodes){
		$this->_nodes = $_nodes;
	}

	/**
	 * Retrieve Table
	 *
	 * @return string
	 */
	public function getTable(){
		return $this->_table;
	}

	/**
	 * set Table
	 */
	public function setTable($_table){
		$this->_table = $_table;
	}

	/**
	 * Constructor
	 *
	 * Sets object properties if set in $properties array
	 *
	 * @param array $properties associative array containing named object properties
	 * @return void
	 */
	public function __construct($properties = null){
		parent::__construct($properties);

		// add needed CSS files
		$this->addCSSFile(we_ui_layout_Themes::computeCSSURL(__CLASS__));

		// add needed JS Files
		$this->addJSFile(we_ui_controls_Tree::computeJSURL(__CLASS__));

		// add needed JS Files
		$this->addJSFile(LIB_DIR . 'additional/yui/dom-min.js');
		$this->addJSFile(LIB_DIR . '/we/core/JsonRpc.js');
		$this->addJSFile(LIB_DIR . 'additional/yui/treeview/treeview-min.js');
	}

	/**
	 * Retrieve array of nodes from database
	 *
	 * @param string $_table
	 * @param integer $parentID
	 * @param integer $start
	 * @param integer $anzahl
	 * @return array
	 */
	public static function doSelect($_table, $parentID = 0, $start = 0, $anzahl = 0){
		$db = new DB_WE();

		$table = $_table;
		$limit = ($start === 0 && $anzahl === 0) ? '' : (is_numeric($start) && is_numeric($anzahl)) ? 'LIMIT ' . abs($start) . ',' . abs($anzahl) : '';

		$nodes = $db->getAllq('SELECT * FROM ' . $db->escape($table) . ' WHERE ParentID= ' . intval($parentID) . ' ORDER BY IsFolder DESC,(Text REGEXP "^[0-9]") DESC,abs(Text),Text ' . $limit);

		if(!empty($nodes)){
			$addPublished = (!array_key_exists('Published', $nodes[0]));
			$addStatus = (!array_key_exists('Status', $nodes[0]));
			foreach($nodes as &$node){
				if($addPublished){
					$node['Published'] = 1;
				}
				if($addStatus){
					$node['Status'] = '';
				}
				if($node['IsFolder']){
					$node['Published'] = 1;
				}
			}
		}
		//we_util_Strings::p_r($nodes);
		return $nodes;
	}

	/**
	 * Retrieve array of nodes from datasource SESSION
	 *
	 * overwrite if the application datasource is custom
	 *
	 * @return array
	 */
	public static function doCustom(){
		$controller = Zend_Controller_Front::getInstance();
		$appName = $controller->getParam('appName');

		if(isset($_SESSION['apps']['customData'])){
			unset($_SESSION['apps']['customData']);
		}

		$_SESSION['apps']['customData'][] = array(
			'ID' => 'customId1',
			'ParentID' => 0,
			'Text' => 'custom 1',
			'ContentType' => $appName . '/item',
			'IsFolder' => 0,
			'Published' => 1,
			'Status' => ''
			)
		;

		$_SESSION['apps']['customData'][] = array(
			'ID' => 'customId2',
			'ParentID' => 0,
			'Text' => 'custom 2',
			'ContentType' => $appName . '/item',
			'IsFolder' => 0,
			'Published' => 1,
			'Status' => ''
			)
		;

		return $_SESSION['apps']['customData'];
	}

	/**
	 * Retrieve class of tree icon
	 *
	 * @param string $contentType
	 * @param string $extension
	 * @return string
	 */
	public static function getTreeIconClass($contentType, $extension = ''){
		return we_ui_layout_Image::getIconClass($contentType, $extension = '');
	}

	/**
	 * Retrieve string of node object
	 *
	 * @param integer $id
	 * @param string $text
	 * @return string
	 */
	public function getNodeObject($id, $text, $Published = 1, $Status = ''){
		//$doOnClick = "alert(&quot;".$id."&quot;);";
		$doOnClick = "alert(&quot;" . $Published . "&quot;);";
		$outClasses = array();
		if($Published == 0){
			$outClasses[] = 'unpublished';
		}
		if($Status != ''){
			$outClasses[] = $Status;
		}
		if(!empty($outClasses)){
			$ClassesStr = trim(implode(' ', $outClasses, ' '));
		} else {
			$ClassesStr = '';
		}

		$doOnClick = "alert(&quot;Pub:-" . $Published . "- Status:-" . $Status . "- classes:" . $ClassesStr . "-&quot;);";
		if(!empty($ClassesStr)){
			$outClass = 'class=\"' . $ClassesStr . '\"';
		} else {
			$outClass = '';
		}

		return 'var myobj = { ' .
			'label: "<span title=\"' . $id . '\" ' . $outClass . ' id=\"spanText_' . $this->_id . '_' . $id . '\">' . addslashes(oldHtmlspecialchars($text)) . '</span>"' .
			//$out .= ',';
			//$out .= 'href: "javascript:'.$doOnClick.'"';
			',' .
			'id: "' . $id . '"' .
			',' .
			'text: "' . addslashes(oldHtmlspecialchars($text)) . '"' .
			',' .
			'title: "' . $id . '"' .
			'}; ';
	}

	/**
	 * Retrieve string of node object
	 *
	 * @param integer $id
	 * @param string $text
	 * @return string
	 */
	public function getNodeObjectSuggest($id, $text, $Classes = '', $Status = ''){
		//$doOnClick = "alert(&quot;Status:-" . $Status . "- classes:" . $Classes . "-&quot;);";
		$outClass = ($Classes ? 'class=\"' . $Classes . '\"' : '');

		return 'var myobj = {
			label: "<span title=\"' . $id . '\" ' . $outClass . ' id=\"spanText_' . $this->_id . '_' . $id . '\">' . $text . '</span>",
			id: "' . $id . '",
			text: "' . $text . '",
			title: "' . $id . '"
			}; ';
	}

	/**
	 * Retrieve javascript code of nodes
	 *
	 * @return string
	 */
	protected function getNodesJS(){

		$out = 'var root = tree_' . $this->_id . '.getRoot();';
		$nodes = $this->getNodes();
		if(!empty($nodes)){
			foreach($nodes as $k => $v){
				$out .= $this->getNodeObject($v['ID'], $v['Text'], $v['Published'], $v['Status']) .
					'var tmpNode = new YAHOO.widget.TextNode(myobj, root, false);' .
					'tmpNode.labelStyle = "' . $this->getTreeIconClass($v['ContentType']) . '";' .
					($this->getTreeIconClass($v['ContentType']) !== we_base_ContentTypes::FOLDER ? 'tmpNode.isLeaf = true;' : '');

				$session = new we_sdk_namespace($this->_sessionName);
				if(in_array($v['ID'], $session->openNodes) && $v['IsFolder']){
					$out .= 'YAHOO.widget.TreeView.getNode(\'' . $this->_id . '\',tmpNode.index).toggle();' .
						'tmpNode.labelStyle = "' . $this->getTreeIconClass('folderOpen') . '";';
				}
			}
		}

		return $out;
	}

	/**
	 * Retrieve datasource
	 *
	 * @return string
	 */
	protected function getDatasource(){
		$controller = Zend_Controller_Front::getInstance();
		$appPath = $controller->getParam('appPath');
		include($appPath . '/conf/meta.conf.php');
		$db = new DB_WE();
		return (substr($metaInfo['datasource'], 0, 6) === 'table:' && $db->isTabExist($metaInfo['maintable']) ?
				'table' :
				(substr($metaInfo['datasource'], 0, 7) === 'custom:' ?
					'custom' : 'custom'));
	}

	/**
	 * Prepare sessionName and set nodes
	 */
	protected function setUpData(){
		$this->_sessionName = 'openNodes_' . $this->_id;
		if($this->getDatasource() === 'table'){
			$nodes = $this->doSelect($this->getTable());
		} elseif($this->getDatasource() === 'custom'){
			$nodes = $this->doCustom();
		}
		$this->setNodes($nodes);
	}

	/**
	 * Renders and returns HTML of tree
	 *
	 * @return string
	 */
	protected function _renderHTML(){

		$this->setUpData();
		$session = new we_sdk_namespace($this->_sessionName);
		if(!isset($session->openNodes)){
			$session->openNodes = $this->getOpenNodes();
		}

		$js = '
var tree_' . $this->_id . ';
var tree_' . $this->_id . '_activEl = 0;

(function() {

	function tree_' . $this->_id . '_Init() {
		tree_' . $this->_id . ' = new YAHOO.widget.TreeView("' . $this->_id . '");
		tree_' . $this->_id . '.setDynamicLoad(loadNodeData);

		' . $this->getNodesJS() . '

		tree_' . $this->_id . '.subscribe("collapse", function(node) {
			var sUrl = "' . LIB_DIR . 'we/ui/controls/TreeSuggest.php?sessionname=' . $this->_sessionName . '&id=" + node.data.id +  "&close=1";
				var callback = {
						success: function(oResponse) {
							var _node = document.getElementById(node.labelElId);
							if(_node) {
						_node.className = "' . $this->getTreeIconClass('folder') . '";
					}
						},
						failure: function(oResponse) {
						}
				};
				YAHOO.util.Connect.asyncRequest("GET", sUrl, callback);
		});

		tree_' . $this->_id . '.subscribe("expand", function(node) {
			var sUrl = "' . LIB_DIR . 'we/ui/controls/TreeSuggest.php?sessionname=' . $this->_sessionName . '&id=" + node.data.id + "&close=0";
				var callback = {
						success: function(oResponse) {
					var _node = document.getElementById(node.labelElId);
					_node.className = "' . $this->getTreeIconClass('folderOpen') . '";
						},
						failure: function(oResponse) {
						}
				};
				YAHOO.util.Connect.asyncRequest("GET", sUrl, callback);
		});

		tree_' . $this->_id . '.draw();
	}

	function loadNodeData(node, fnLoadComplete)  {

		var nodeId = node.data.id;
		var nodeTable = encodeURI("' . $this->getTable() . '");
			var nodeLabel = encodeURI(node.label);

			//prepare URL for XHR request:
			var sUrl = "' . LIB_DIR . 'we/ui/controls/TreeSuggest.php?treeclass=' . get_class($this) . '&datasource=' . $this->getDatasource() . '&sessionname=' . $this->_sessionName . '&id=" + nodeId + "&table=" + nodeTable;

			//prepare our callback object
			var callback = {

					//if our XHR call is successful, we want to make use
					//of the returned data and create child nodes.
					success: function(oResponse) {
							YAHOO.log("XHR transaction was successful.", "info", "example");
							var oResults = JSON.parse(oResponse.responseText);
							if((oResults.ResultSet.Result) && (oResults.ResultSet.Result.length)) {
									//Result is an array if more than one result, string otherwise
									if(YAHOO.lang.isArray(oResults.ResultSet.Result)) {
											for (var i=0, j=oResults.ResultSet.Result.length; i<j; i++) {
												' . $this->getNodeObjectSuggest('"+oResults.ResultSet.Id[i]+"', '"+oResults.ResultSet.Result[i]+"', '"+oResults.ResultSet.Classes[i]+"', '"+oResults.ResultSet.Status[i]+"') . '
												var tmpNode = new YAHOO.widget.TextNode(myobj, node, oResults.ResultSet.open[i]);
												tmpNode.labelStyle = oResults.ResultSet.LabelStyle[i];
												if(tmpNode.labelStyle!=="folder") {
													tmpNode.isLeaf = true;
												}
												if(oResults.ResultSet.open[i]) {
													tmpNode.labelStyle = "folderOpen";
												}
											}
									} else {
											//there is only one result; comes as string:
						' . $this->getNodeObjectSuggest('"+oResults.ResultSet.Id+"', '"+oResults.ResultSet.Result+"', '"+oResults.ResultSet.Published+"', '"+oResults.ResultSet.Status+"') . '
											var tmpNode = new YAHOO.widget.TextNode(myobj, node, false);
											tmpNode.labelStyle = oResults.ResultSet.LabelStyle;
											if(tmpNode.labelStyle!=="folder") {
												tmpNode.isLeaf = true;
											}
											if(oResults.ResultSet.open) {
												tmpNode.labelStyle = "folderOpen";
											}
									}
							}
							oResponse.argument.fnLoadComplete();
					},

					failure: function(oResponse) {
							YAHOO.log("Failed to process XHR transaction.", "info", "example");
							oResponse.argument.fnLoadComplete();
					},

					argument: {
							"node": node,
							"fnLoadComplete": fnLoadComplete
					},

					timeout: 7000
			};

			YAHOO.util.Connect.asyncRequest("GET", sUrl, callback);
	}

	YAHOO.util.Event.addListener(window, "load", tree_' . $this->_id . '_Init);

})();';

		$page = we_ui_layout_HTMLPage::getInstance();
		$page->addInlineJS($js);

		return '<div class="yui-skin-sam"><div id="' . oldHtmlspecialchars($this->_id) . '"></div></div>';
	}

}
