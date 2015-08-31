/*!
 * zotop theme global javascript
 * http://zotop.com/
 */

// placeholder
$(function(){
	$('input,textarea').placeholder();
});

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
			creditcard: $.validator.format("请输入信用卡号"),
			equalTo: $.validator.format("请再次输入相同的值"),
			extension: $.validator.format("后缀名必须是 {0}"),
			pattern: $.validator.format("格式错误"),
			maxlength: $.validator.format("长度最多是 {0}"),
			minlength: $.validator.format("长度最少是 {0}"),
			rangelength: $.validator.format("长度需介于 {0} 和 {1} 之间"),
			range: $.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
			max: $.validator.format("请输入一个最大为 {0} 的值"),
			min: $.validator.format("请输入一个最小为 {0} 的值")
	});

	// 使用bootstrap tooltip 作为错误提示
	$.extend(jQuery.validator.defaults, {
		ignoreTitle: true,
		showErrors: function(errorMap, errorList) {
			
			$.each(this.successList, function(index, value) {
				return $(value).tooltip("hide");
			});

			return $.each(errorList, function(index, value) {
				
				var tooltip = $(value.element).addClass('error').tooltip({
					trigger: "manual",
					html: true,
					title: value.message,
					placement: function(){
						return this.$element.data('placement') ? this.$element.data('placement') : 'top';
					},				
					template: '<div class="tooltip tooltip-error" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
				});

				tooltip.data("bs.tooltip").options.content = value.message;

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
