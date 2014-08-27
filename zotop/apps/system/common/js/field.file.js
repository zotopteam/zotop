/*

  图片输入框和文件输入框JS文件

  @author   zotop
  @created  2012-11-14
  @version  2.0
  @site     http://zotop.com

*/
(function($) {

	$(function(){
		$('input.file').each(function(){

			var $input = $(this);

			
			$input.parent().find('a').each(function(){

				$(this).on('click',function(e){
					e.preventDefault();

					var $dialog = $.dialog({
						url : $(this).attr('href') + '&file=' + $input.val(),
						title: $(this).attr('title') || $(this).text(),
						width: $(this).attr('data-width') || 1000,
						height: $(this).attr('data-height') || 460,
						statusbar: '<i class="icon icon-loading"></i>',
						ok : function(insert){
							$input.val(insert[0].url).trigger('change',[insert[0]]);
						},
						cancel : function(){}
					},true);
				})

			});

		});
	})

})(jQuery);