/*
	模版

	@author   zotop
	@created  2012-11-14
	@version  2.0
	@site     http://zotop.com
*/
(function($) {

	$(function(){
		$('input.template').each(function(){
			var $input = $(this);
			var $selector = $('#'+$input.attr('id')+'-selector');
			var $editor = $('#'+$input.attr('id')+'-editor');

			// 编辑按钮
			$input.change(function(){
				var v = $(this).val();
				if(v.length > 0){
					$editor.show();
				}else{
					$editor.hide();
				}
			});

			// 模版选择
			$selector.on('click',function(event){
				event.preventDefault();

				var $dialog = $.dialog({
					url: $(this).attr('href') + '?file=' + $input.val(),
					title: $(this).text(),
					statusbar: '<i class="icon icon-loading"></i>',
					width: $(this).attr('data-width') || 1000,
					height: $(this).attr('data-height') || 500,
					ok : function(template){
						$input.val(template);
					},
					cancel:function(){}
				},true);

				return false;
			});

			// 模版编辑
			$editor.on('click',function(event){
				event.preventDefault();

				var $dialog = $.dialog({
					url: $(this).attr('href') + '?file=' + $input.val(),
					title: $(this).text(),
					statusbar: '<i class="icon icon-loading"></i>',
					width: $(this).attr('data-width') || 1000,
					height: $(this).attr('data-height') || 500,
					ok : function(){},
					okValue : '保存',
					cancel:function(){}
				},true);
				return false;
			});

		});
	})

})(jQuery);