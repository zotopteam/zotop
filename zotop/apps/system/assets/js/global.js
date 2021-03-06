/*!
 * zotop theme global javascript
 * http://zotop.com/
 */

// placeholder && tips
$(function(){
	$('input[placeholder], textarea[placeholder]').placeholder();

	$(document).tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto bottom';
	},selector:'[data-toggle="tooltip"],a[title]',html:true,trigger:'hover'});

	$('.tooltip-block').tooltip({placement:function(){
		return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto bottom';
	},title:function(){
		return '<span class="tooltip-block-content" style="display:block">'+ $(this).find('.tooltip-block-content').html() + '</span>';
	},container:'body',html:true,trigger:'hover'});

	$('.tabdropable').tabdrop();

	$('.scrollable').niceScroll({cursorwidth:"6px",boxzoom:false});

	//maxlength
	$('input[maxlength],textarea[maxlength]').maxlength({alwaysShow:true,appendToParent:true,threshold:10,separator:'/',placement:'bottom-right-inside'});	
});

//dialog
$(function(){
	
	// ajax post 点击链接使用post链接，并返回提示信息
	$(document).on('click', 'a.js-ajax-post',function(event){
		event.preventDefault();

		var fa = $(this).find('.fa');

		if ( fa.length > 0 ){
			fa.addClass('fa-spin fa-spinner');
		}else{
			$.loading();
		}		
		
		$.post($(this).attr('href'),{},function(msg){
			$.msg(msg);

			if ( fa.length > 0 ){
				fa.removeClass('fa-spin fa-spinner');
			}			
		},'json');

		event.stopPropagation();
	});

	$(document).on('click', 'a.js-confirm', function(event){
		event.preventDefault();

		var href    = $(this).attr('href');
		var text    = $(this).attr('title') || $(this).data('original-title') || $(this).text();
		var confirm = $(this).data('confirm') || zotop.t('您确定要<b>%s</b>嘛?', text);
		var $dialog = $.confirm(confirm,function(){
			$dialog.statusbar('<i class="fa fa-spinner fa-spin"></i>');
			$.post(href,{},function(msg){
				$dialog.close().remove();
				$.msg(msg);
			},'json');
			return false;
		}).title(text);

		event.stopPropagation();
	});	

	$(document).on('click', 'a.js-open',function(event){
		event.preventDefault();

		var url     = $(this).attr('href');
		var title   = $(this).attr('title') || $(this).data('original-title') || $(this).text();
		var width   = $(this).data('width') || 'auto';
		var height  = $(this).data('height') || 'auto';		
		var $dialog = $.dialog({
			title:title,
			url:url,
			width:width,
			height:height,
			ok:$.noop,
			cancel:$.noop,
			statusbar: '<i class="fa fa-loading"></i>',
			opener:window
		},true);

		event.stopPropagation();
	});

	$(document).on('click', 'a.js-prompt', function(event){
		event.preventDefault();

		var href    = $(this).attr('href');
		var value   = $(this).data('value');
		var prompt  = $(this).data('prompt');
		var title   = $(this).attr('title') || $(this).data('original-title') || $(this).text();		
		var $dialog = $.prompt(prompt,function(newvalue){

			if( value == newvalue || $.trim(newvalue) == '' ){
				$dialog.shake();
				var input = this._$('content').find('input')[0];
					input.select();
					input.focus();
			}else{				
				$dialog.statusbar('<i class="fa fa-loading"></i>');
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

})

// jQuery Validation
$(function(){

	// jQuery Validation Plugin 中文
	$.extend(jQuery.validator.messages, {
	        required: $.validator.format("必选"),
			remote: $.validator.format("请修正该字段"),
			email: $.validator.format("请输入正确的电子邮件"),
			url: $.validator.format("请输入网址"),
			date: $.validator.format("请输入日期"),
			dateISO: $.validator.format("请输入日期 (ISO)."),
			number: $.validator.format("请输入数字"),
			digits: $.validator.format("只能输入整数"),
			integer : $.validator.format("请输入正整数"),
			creditcard: $.validator.format("请输入信用卡号"),
			equalTo: $.validator.format("请再次输入相同的值"),
			extension: $.validator.format("后缀名必须是 {0}"),
			pattern: $.validator.format("格式错误"),
			maxlength: $.validator.format("长度最多是 {0}"),
			minlength: $.validator.format("长度最少是 {0}"),
			rangelength: $.validator.format("长度需介于 {0} 和 {1} 之间"),
			range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
			max: $.validator.format("请输入一个最大为 {0} 的值"),
			min: $.validator.format("请输入一个最小为 {0} 的值"),
			step: $.validator.format( "必须是 {0} 的倍数")

	});

	// 增加扩展名验证
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, "|") : "png|jpe?g|gif";
		return this.optional(element) || value.match(new RegExp("\\.(" + param + ")$", "i"));
	}, $.validator.messages.extension);

	// 增加整数验证
	$.validator.addMethod("integer", function(value, element) {
		return this.optional(element) || /^-?\d+$/.test(value);
	}, $.validator.messages.integer);

	// 增加正则验证
	$.validator.addMethod("pattern", function(value, element, param) {
		if (this.optional(element)) {
			return true;
		}
		if (typeof param === "string") {
			param = new RegExp("^(?:" + param + ")$");
		}
		return param.test(value);
	}, $.validator.messages.pattern);

	// 覆盖默认的url验证，允许本地链接
	$.validator.addMethod("url", function(value, element) {
		return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
	}, $.validator.messages.url);		

	// 使用bootstrap tooltip 作为错误提示
	$.extend(jQuery.validator.defaults, {
		ignoreTitle: true,
		showErrors: function(errorMap, errorList) {
			
			$.each(this.successList, function(index, value) {
				return $(value).removeClass('error').tooltip("hide");
			});

			return $.each(errorList, function(index, value) {
				
				var tooltip = $(value.element).addClass('error').tooltip({
					trigger: "manual",
					html: true,
					title: value.message,
					placement: function(){
						return this.$element.data('placement') ? 'auto '+this.$element.data('placement') : 'auto bottom';
					},				
					template: '<div class="tooltip tooltip-error" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
				});

				tooltip.data("bs.tooltip").options.title = value.message;

				return $(value.element).tooltip("show");
			});
		}
	});


	$.validator.prototype.__resetForm = $.validator.prototype.resetForm;

	$.extend($.validator.prototype, {
		
		resetForm : function(){

			$.each(this.errorList, function (index, value) {
				$(value.element).tooltip('destroy');
			});

			this.__resetForm();
			return this;
		}
	});

});
