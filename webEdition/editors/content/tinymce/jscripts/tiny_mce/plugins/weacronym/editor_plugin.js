(function(){tinymce.create('tinymce.plugins.WeacronymPlugin',{init:function(e,f){e.addCommand('mceWeacronym',function(){var a=e.selection;if(a.isCollapsed()&&(!e.dom.getParent(a.getNode(),'ACRONYM')))return;e.windowManager.open({file:f+'/../../../../../wysiwyg/acronymDialog.php?we_dialog_args[editor]=tinyMce',popup_css:false,width:460+parseInt(e.getLang('weacronym.delta_width',0)),height:200+parseInt(e.getLang('weacronym.delta_height',0)),inline:1},{plugin_url:f,some_custom_arg:'custom arg'})});e.addButton('weacronym',{title:tinyMceGL.weacronym.tooltip,cmd:'mceWeacronym',image:f+'/img/acronym.gif'});e.onNodeChange.add(function(a,b,n,c){var d=n.nodeName=='ACRONYM';b.setDisabled('weacronym',c&&!d);b.setActive('weacronym',d)})},createControl:function(n,a){return null},getInfo:function(){return{longname:'Weacronym plugin',author:'Some author',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/weacronym',version:"1.0"}}});tinymce.PluginManager.add('weacronym',tinymce.plugins.WeacronymPlugin)})();
