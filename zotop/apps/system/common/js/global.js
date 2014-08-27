/*
 * Global
 *
 * @author   zotop team
 * @created  2012-10-25
 * @version  1.0
 * @site     http://zotop.com
 */

// 自定义提示
$(function(){
	// $('form.form').formsavior(zotop.t('离开这个页面, 您所做的更改将丢失'));

	$('input[placeholder], textarea[placeholder]').placeholder();

	$(document).tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto';
	},selector:'[title]',container:'body',html:true});



	$('.tip').tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto';
	},title:function(){
		return '<div class="tip-data">'+ $(this).find('.tip-content').html() + '</div>';
	},container:'body',html:true});

	$('input[maxlength],textarea[maxlength]').maxlength({alwaysShow:true,appendToParent:true,threshold:10,separator:'/',warningClass:'true',limitReachedClass:'true',placement:'top-right-inside'});
});



//对话框
$(function(){
	$('a.dialog-confirm').on('click', function(event){
		event.preventDefault();

		var href = $(this).attr('href');
		var text = $(this).attr('title') || $(this).data('original-title') || $(this).text();
		var confirm = $(this).data('confirm') || zotop.t('您确定要 <b>%s</b> 嘛?', text);
		var $dialog = $.confirm(confirm,function(){
			$dialog.statusbar('<i class="icon icon-loading"></i>');
			$.post(href,{},function(msg){
				$dialog.close().remove();
				$.msg(msg);
			},'json');
			return false;
		}).title(text);
	});

	$('a.dialog-open').on('click', function(event){
		event.preventDefault();

		var url = $(this).attr('href');
		var title = $(this).attr('title') || $(this).data('original-title') || $(this).text();
		var width = $(this).data('width') || 'auto';
		var height = $(this).data('height') || 'auto';

		var $dialog = $.dialog({
			title:title,
			url:url,
			width:width,
			height:height,
			ok:$.noop,
			cancel:$.noop,
			statusbar: '<i class="icon icon-loading"></i>',
			opener:window
		},true);
	});

	$("a.dialog-prompt").on("click", function(event){
		event.preventDefault();

		var href = $(this).attr('href');
		var value = $(this).data('value');
		var prompt = $(this).data('prompt');
		var title = $(this).attr('title') || $(this).data('original-title') || $(this).text();
		
		var $dialog = $.prompt(prompt,function(newvalue){

			if( value == newvalue || $.trim(newvalue) == '' ){
				$dialog.shake();
				var input = this._$('content').find('input')[0];
					input.select();
					input.focus();
			}else{				
				$dialog.statusbar('<i class="icon icon-loading"></i>');
				$.post(href,{newvalue:newvalue},function(msg){
					if( msg.state ){
						$dialog.close().remove();
					}else{
						$dialog.statusbar('');
					}
					$.msg(msg);
				},'json');
			}
			return false;

		}, value).title(title);

	});
});

$(function(){

	// ajax post 点击链接使用post链接，并返回提示信息
	$('a.ajax-post').on('click', function(event){
		event.preventDefault();
		$.loading();
		$.post($(this).attr('href'),{},function(msg){
			$.msg(msg);
		},'json');
	});

});