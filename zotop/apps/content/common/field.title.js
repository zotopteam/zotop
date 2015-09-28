/**
 * jQuery's colorpicker Plugin
 */
(function($) {
	"use strict";

	var ColorSelector = function(target, options) {
		this.options = options;
		this.$target = $(target);
		this._init();
	};

	ColorSelector.prototype = {

		constructor : ColorSelector,

		_init : function() {

		 	var $dropdownmenu = $("<div>").addClass("colorselector dropdown-menu dropdown-menu-right").insertAfter(this.$target);

			var hc    = ['00','33','66','99','CC','FF'];
			var i     =0;
			var r,g,b,c;
			var color = [];

			color.push('<style>');
			color.push('.colorselector-preview{font-size:12px;height:32px;line-height:20px;padding:5px;margin-bottom:5px;border:solid 1px #ccc;text-align:center;border-radius:3px;}');
			color.push('.colorselector-table{margin:5px 0;width:270px;}');
			color.push('.colorselector-table td{border:solid 1px #f7f7f7;width:15px;height:15px;line-height:1;padding:0;cursor:pointer;}');
			color.push('</style>');
			color.push('<div class="colorselector-preview">&nbsp;</div>');
			color.push('<table class="colorselector-table"><tr>');
			for(r=0;r<6;r++) {
				for(g=0; g<6; g++) {
					for(b=0;b<6;b++) {
						c = hc[r] + hc[g] + hc[b];
						if (i%18==0 && i>0) {
							color.push("</tr><tr>");
						}
						color.push('<td class="color" bgcolor="#'+c+'" title="#'+c+'">&nbsp;</td>');
						i++;
					}
				}
			}
			color.push('</tr></table>');

			if ( this.options.transparent ){
				color.push('<button type="button" class="btn btn-default color" bgcolor="transparent">'+this.options.transparent+'</button>');
			}

			if ( this.options.empty ){
				color.push('<button type="button" class="btn btn-default color pull-right" bgcolor="">'+this.options.empty+'</button>');
			}

			$(color.join('')).appendTo($dropdownmenu);
		 
		 	this.$target.attr("data-toggle", "dropdown").addClass("dropdown-toggle");

			var target   = this.$target;
			var callback = this.options.callback;

			// 选择颜色
		  	$dropdownmenu.on('click', '.color', function(e){
		  		var color = $(this).attr('bgcolor');

		  		if (typeof callback == "function") {
		  			callback(color);
		  		}
		  	});

		  	// 预览颜色
			$dropdownmenu.on('mouseover', '.color', function(){
				var color = $(this).attr('bgcolor');
				$dropdownmenu.find('.colorselector-preview').css('background-color',color).text(color);
			});
		}
	};

	$.fn.colorselector = function(option) {
		var args = Array.apply(null, arguments);
			args.shift();

		return this.each(function() {

			var $this = $(this), data = $this.data("colorselector"), options = $.extend({}, $.fn.colorselector.defaults, $this.data(), typeof option == "object" && option);

			if (!data) {
				$this.data("colorselector", (data = new ColorSelector(this, options)));
			}

			if (typeof option == "string") {
				data[option].apply(data, args);
			}
		});
	};

	$.fn.colorselector.defaults = {
		empty:'清除',
		transparent:'透明',
		callback : function(color) {}
	};

	$.fn.colorselector.Constructor = ColorSelector;

})(jQuery, window, document);



$(function(){
	//template控件处理函数
	$('input.title').each(function(){
		var $input = $(this);
		var $bold = $input.parent().find('a[rel=bold]');
		var $color = $input.parent().find('a[rel=color]');

		var stylefield = $input.attr('stylefield');

		//设置style数据
		function setstyle(){
			var style = '';

			if( $bold.attr('bold') ){
				style = 'font-weight:bold;';
			}

			if ( $color.attr('color') ){
				style += 'color:'+$color.attr('color')+';';
			}

			$input.attr('style',style);

			$('input[name='+stylefield+']').val(style);
		}

		//加粗按钮
		$bold.attr('bold', $input.css('font-weight')).on('click',function(e){
			e.preventDefault();

			if( $(this).attr('bold') == 'bold' ){
				$(this).removeAttr('bold').removeClass('active');

			}else{
				$(this).attr('bold','bold').addClass('active');
			}
			setstyle();
		});

		//色彩按钮
		$color.attr('color',$input.css('color')).colorselector({
			callback:function(v){
				$color.attr('color',v).css('color',v);
;
				setstyle();
			}
		});

	});
});

