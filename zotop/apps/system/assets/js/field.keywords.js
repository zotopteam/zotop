/*
  关键词提取

  @author   zotop
  @created  2012-11-14
  @version  2.0
  @site     http://zotop.com

*/
(function($) {

	$(function(){
		$('a.getkeywords').each(function(){
			var btn 	= $(this);
			var href 	= btn.attr('href');
			var title 	= $('[name=' + btn.attr('data-source-title') + ']');
			var content = $('[name=' + btn.attr('data-source-content') + ']');
			var to 		= $('[name=' + btn.attr('data-to') + ']');

			btn.on('click',function(e){
				e.preventDefault();
				btn.find('i.icon').addClass('icon-loading');
				$.post(href,{title:title.val(),content:content.val()},function(keywords){
					to.val(keywords);
					btn.find('i.icon').removeClass('icon-loading');
				});
			});
		});
	});

})(jQuery);