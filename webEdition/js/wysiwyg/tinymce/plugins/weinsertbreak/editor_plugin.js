(function () {
	tinymce.create('tinymce.plugins.WeinsertbreakPlugin', {
		init: function (c, d) {
			c.addCommand('mceWeinsertbreak', function () {
				c.selection.setContent(c.dom.createHTML('br'));
			});
			c.addButton('weinsertbreak', {
				title: 'we.tt_weinsertbreak',
				cmd: 'mceWeinsertbreak'
			});
			c.onNodeChange.add(function (a, b, n) {});
		},
		createControl: function (n, a) {
			return null;
		},
		getInfo: function () {
			return{
				longname: 'Weinsertbreak plugin',
				author: 'webEdition e.V',
				authorurl: 'http://www.webedition.org',
				infourl: 'http://www.webedition.org'
			};
		}});
	tinymce.PluginManager.add('weinsertbreak', tinymce.plugins.WeinsertbreakPlugin);
})();
