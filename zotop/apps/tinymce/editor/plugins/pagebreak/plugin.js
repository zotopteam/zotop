/**
 * 高级分页
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.PageBreakPlugin', {
		init : function(ed, url) {
			//var pb = '<img src="' + url + '/img/trans.gif" class="mcePageBreak mceItemNoResize" />', cls = 'mcePageBreak', sep = ed.getParam('pagebreak_separator', '<!-- pagebreak -->'), pbRE;
            var pb = '<p class="mcePageBreak">&nbsp;</p>',
				cls = 'mcePageBreak', 
				sep = ed.getParam('pagebreak_separator', '<p class="mcePageBreak">&nbsp;</p>'), 
				pbRE;

			pbRE = new RegExp(sep.replace(/[\?\.\*\[\]\(\)\{\}\+\^\$\:]/g, function(a) {return '\\' + a;}), 'g');

			var toElement = function() {
                var div = document.createElement('div');
                return function(html) {
                    div.innerHTML = html;
                    html = div.firstChild;
                    div.removeChild(html);
                    return html;
                };
            }();

            function lastElementChild(elem) {
                if (elem.lastElementChild) {
                    return elem.lastElementChild;
                }
                var child = elem.lastChild;
                while (child && child.nodeType != 1) {
                    child = child.previousSibling;
                }
                return child;
            }

            function nextElementSibling(elem) {
                if (elem.nextElementSibling) {
                    return elem.nextElementSibling;
                }
                var sibling = elem.nextSibling;
                while (sibling && sibling.nodeType != 1) {
                    sibling = sibling.nextSibling;
                }
                return sibling;
            }

            // Register commands
            ed.addCommand('mcePageBreak', function() {
                ed.execCommand('mceInsertContent', 0, pb);
                if (ed.dom.select('p.' + cls).length == 1) {
                    ed.selection.setCursorLocation(ed.getBody().firstChild, 0);
                    ed.execCommand('mceInsertContent', 0, pb);
                    var firstBreak = ed.dom.select('p.' + cls)[0],
                        nextElement = nextElementSibling(firstBreak);
                    if (nextElement && ed.dom.hasClass(nextElement, cls)) {
                        nextElement.parentNode.insertBefore(toElement('<p>&nbsp;</p>'), nextElement);
                    }
                }
                var body = ed.getBody(), 
                	lastChild = lastElementChild(body);
                if (lastChild && ed.dom.hasClass(lastChild, cls)) {
                    body.appendChild(toElement('<p>&nbsp;</p>'));
                }
			});

			// Register buttons
			ed.addButton('pagebreak', {title : 'pagebreak.desc', cmd : cls});

			ed.onInit.add(function() {
				if (ed.settings.content_css !== false)
					ed.dom.loadCSS(url + "/css/content.css");

				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.node.nodeName == 'P' && ed.dom.hasClass(o.node, cls))
							o.name = 'pagebreak';
					});
				}
			});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('pagebreak', n.nodeName === 'P' && ed.dom.hasClass(n, cls));
			});

			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = o.content.replace(pbRE, pb);
			});
		},

		getInfo : function() {
			return {
				longname : 'PageBreak',
				author : 'Moxiecode Systems AB',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/pagebreak',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('pagebreak', tinymce.plugins.PageBreakPlugin);
})();