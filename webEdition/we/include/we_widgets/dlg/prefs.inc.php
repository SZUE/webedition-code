<?php

// +----------------------------------------------------------------------+
// | webEdition                                                           |
// +----------------------------------------------------------------------+
// | PHP version 4.1.0 or greater                                         |
// +----------------------------------------------------------------------+
// | Copyright (c) 2000 - 2007 living-e AG                                |
// +----------------------------------------------------------------------+
//


$jsPrefs = "
var _sObjId='".$_REQUEST["we_cmd"][0]."';
var _sCls_=opener.gel(_sObjId+'_cls').value;
var _fo,_sInitCls,_oSctCls;
var _iInitCls=0;

function initPrefs(){
	_oSctCls=_fo.elements['sct_cls'];
	var iSctClsLen=_oSctCls.length;
	_sInitCls=_sCls_;
	for(var i=iSctClsLen-1;i>=0;i--){
		if(_oSctCls.options[i].value==_sCls_){
			_oSctCls.options[i].selected=true;
			_iInitCls=i;
		}
	}
}

function savePrefs(){
	opener.setTheme(_sObjId,_oSctCls[_oSctCls.selectedIndex].value);
}

function previewPrefs(){
	opener.setTheme(_sObjId,_oSctCls[_oSctCls.selectedIndex].value);
}

function exitPrefs(){
	var sTheme=_oSctCls[_oSctCls.selectedIndex].value;
	if(_sCls_!=sTheme){
		sTheme=_oSctCls[_iInitCls].value;
		opener.setTheme(_sObjId,sTheme);
	}
}
";

$oSctCls = new we_htmlSelect(array("name"=>"sct_cls","size"=>"1","class"=>"defaultfont","style"=>"width:120px;border:#AAAAAA solid 1px"));
$oSctCls->insertOption(0,"white",$l_cockpit['white']);
$oSctCls->insertOption(1,"lightCyan",$l_cockpit['lightcyan']);
$oSctCls->insertOption(2,"blue",$l_cockpit['blue']);
$oSctCls->insertOption(3,"green",$l_cockpit['green']);
$oSctCls->insertOption(4,"orange",$l_cockpit['orange']);
$oSctCls->insertOption(5,"yellow",$l_cockpit['yellow']);
$oSctCls->insertOption(6,"red",$l_cockpit['red']);

$oSelCls = new we_htmlTable(array("cellpadding"=>"0","cellspacing"=>"0","border"=>"0"),1,2);
$oSelCls->setCol(0,0,array("width"=>130,"class"=>"defaultfont"),$l_cockpit['bgcolor']);
$oSelCls->setCol(0,1,null,$oSctCls->getHTMLCode());

?>