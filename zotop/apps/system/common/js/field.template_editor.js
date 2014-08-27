/*
	模板编辑器

	@author   zotop
	@created  2012-11-14
	@version  2.0
	@site     http://zotop.com
*/
(function($) {

	$(function(){
		$('textarea.template_editor').each(function(){
			
			var $this = $(this);
			var id = $this.attr('id');
			var editor  = CodeMirror.fromTextArea(document.getElementById(id),{
				mode: "smartymixed",
				lineNumbers: true
			});
			
			$this.closest('form').submit(function(){
				$this.val(editor.getValue());
			});
			
		});
	})

	// 插入内容到编辑器
	function insert_editor(editor,html){
		editor.deleteH();
		editor.replaceRange(html, editor.getCursor());
		editor.focus();
	}

})(jQuery);