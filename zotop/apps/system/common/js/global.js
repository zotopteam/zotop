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

	$('input[placeholder], textarea[placeholder]').placeholder();

	$(document).tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto';
	},selector:'[title]',container:'body',html:true});

	$('.tooltip-block').tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto';
	},title:function(){
		return '<div class="tooltip-block-content" style="display:block">'+ $(this).find('.tooltip-block-content').html() + '</div>';
	},container:'body',html:true});

	$('input[maxlength],textarea[maxlength]').maxlength({alwaysShow:true,appendToParent:true,threshold:10,separator:'/',warningClass:'true',limitReachedClass:'true',placement:'top-right-inside'});
});



// 常用操作
$(function(){
	$(document).on('click', 'a.dialog-confirm', function(event){
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

		event.stopPropagation();
	});

	$(document).on('click', 'a.dialog-open',function(event){
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

		event.stopPropagation();
	});

	$(document).on('click', 'a.dialog-prompt', function(event){
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

		event.stopPropagation();
	});

	// ajax post 点击链接使用post链接，并返回提示信息
	$(document).on('click', 'a.ajax-post',function(event){
		event.preventDefault();
		
		$.loading();
		$.post($(this).attr('href'),{},function(msg){
			$.msg(msg);
		},'json');

		event.stopPropagation();
	});

});