/*
  
  别名
  
  @author   zotop
  @created  2012-11-14
  @version  2.0
  @site     http://zotop.com
  
*/
(function($) {
	
	$(function(){
		$('a.getalias').each(function(){
			var btn    = $(this).addClass('disabled');
			var href   = btn.attr('href');
			var source = $('input[name=' + btn.attr('data-source') + ']');			
			var to     = $('input[name=' + btn.attr('data-to') + ']');

			source.val() ? btn.removeClass('disabled') : btn.addClass('disabled');			
			source.on('change',function(){
				source.val() ? btn.removeClass('disabled') : btn.addClass('disabled');
			});

			btn.on('click',function(e){
				e.preventDefault();	
				btn.addClass('disabled').find('i.fa').addClass('fa-spin');
				$.get(href,{source : source.val(), maxlength:to.attr('maxlength')},function(alias){
					to.val(alias);
					btn.removeClass('disabled').find('i.fa').removeClass('fa-spin');
				});				
			});
		});
	});

})(jQuery);