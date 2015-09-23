/*

  图片输入框和文件输入框JS文件

  @author   zotop
  @created  2012-11-14
  @version  2.0
  @site     http://zotop.com

*/
(function($) {

	$(function(){
		$('input.image').each(function(){

			var $input = $(this);


			$input.popover({placement:'bottom',html:true,trigger:'hover',title:false,content:function(){
				
				var value = $input.val();
				
				if(value){
					return $input.hasClass('error') ? false : '<img src="'+ value +'" style="max-width:400px;max-height:200px"/>';
				}

				return false;
			}});

			
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