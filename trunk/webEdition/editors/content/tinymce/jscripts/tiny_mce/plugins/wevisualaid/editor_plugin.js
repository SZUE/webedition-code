(function(){tinymce.create('tinymce.plugins.WevisualaidPlugin',{hasWeVisual:false,init:function(d,f){var t=this;function toggleBorders(t,a){var e=t.getBody();var s=t.settings;var b=tinymce.each;var c=tinymce.DOM;b(c.select('a,table,acronym,span,abbr',e),function(e){var v;switch(e.nodeName){case'TABLE':v=c.getAttrib(e,'border');if(!v||v=='0'){if(a.hasVisual){c.addClass(e,s.visual_table_class)}else{c.removeClass(e,s.visual_table_class)}}return;case'A':v=c.getAttrib(e,'name');if(v){if(a.hasVisual){c.addClass(e,'mceItemAnchor')}else{c.removeClass(e,'mceItemAnchor')}}return;case'ACRONYM':if(a.hasVisual){c.addClass(e,'mceItemWeAcronym')}else{c.removeClass(e,'mceItemWeAcronym')}return;case'ABBR':if(a.hasVisual){c.addClass(e,'mceItemWeAbbr')}else{c.removeClass(e,'mceItemWeAbbr')}return;case'SPAN':v=c.getAttrib(e,'lang');if(v){if(a.hasVisual){c.addClass(e,'mceItemWeLang')}else{c.removeClass(e,'mceItemWeLang')}}return}})}d.addCommand('mceWevisualaid',function(){d.hasVisual=!d.hasVisual;toggleBorders(this,d)});d.addButton('wevisualaid',{title:'we.tt_wevisualaid',cmd:'mceWevisualaid'});d.onNodeChange.add(function(a,b,n){b.setActive('wevisualaid',a.hasVisual)})},createControl:function(n,a){return null},getInfo:function(){return{longname:'Wevisualaid plugin',author:'Some author',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/wevisualaid',version:"1.0"}}});tinymce.PluginManager.add('wevisualaid',tinymce.plugins.WevisualaidPlugin)})();