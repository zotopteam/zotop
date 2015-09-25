
/**
 * jQuery's colorpicker Plugin
*/
(function($){

	$.fn.colorpicker = function(options) {
		//初始化参数
		options = $.extend({},{
			empty : 'clear',
			transparent : 'transparent',
			selectcolor:function(){},
			setcolor:function(){}
		},options);

		var colorpicker = $('.colorpicker');

		if ( !colorpicker.length ){

			var hc = ["FF","CC","99","66","33","00"];
			var i=0;
			var r,g,b,c;
			var color = [];

			color.push('<div class="colorpicker" style="border:solid 1px #d5d5d5;padding:1px;background:#f7f7f7;border-radius:2px;">');
			color.push('<table cellspacing="0" cellpadding="0" style="margin:4px;width:270px;"><tr>');
			color.push('<tr>');
			color.push('<td colspan="8"><div class="colorpicker-preview" style="height:20px;border:solid 1px #666;line-height:20px;padding:0px 3px;"></div></td>');
			color.push('<td colspan="6" class="color" bgcolor="transparent" align="center" style="padding:1px;"><div class="colorpicker-button transparent" style="font-size:12px;line-height:16px;height:14px;border:1px solid #666;padding:3px 5px;cursor:pointer;">'+options.transparent+'</div></td>');
			color.push('<td colspan="4" class="color" bgcolor="" align="center"  style="padding:1px;"><div class="colorpicker-button nocolor" style="font-size:12px;line-height:16px;height:14px;border:1px solid #666;padding:3px 5px;cursor:pointer;">'+options.empty+'</div></td>');
			color.push('</tr>');
			color.push('<tr><td height="5" colspan="18" class="colorpicker-split" style="padding:0px;height:3px;"></td></tr>');
			for(r=0;r<6;r++) {
				for(g=0; g<6; g++) {
					for(b=0;b<6;b++) {
						c = hc[r] + hc[g] + hc[b];
						if (i%18==0 && i>0) {
							color.push("</tr><tr>");
						}
						color.push('<td class="color" bgcolor="#'+c+'" style="border:solid 1px #f7f7f7;width:12px;height:12px;" title="#'+c+'"></td>');
						i++;
					}
				}
			}
			color.push('</tr></table>');
			color.push('</div>');

			colorpicker = $(color.join('')).appendTo(document.body).hide();

			//关闭面板
			$(document).on('mousedown',function(e){
				if ( colorpicker.hasClass('active') ) colorpicker.removeClass('active').hide();
			});
		}

		return this.each(function(){
			var btn = $(this);
			btn.on('mouseover',function(){
				colorpicker.css({
					'position' : 'absolute',
					'z-index' : 99999
				}).position({
					of : btn,
					my : 'left top+3',
					at : 'left bottom',
					collision: 'fit none'
				}).on('mouseover','.color',function(){
					var color = $(this).attr('bgcolor');
					colorpicker.find('.colorpicker-preview').css('background-color',color).text(color);
					options.selectcolor(color);
				}).on('mousedown','.color',function(e){
					e.preventDefault();
					var color = $(this).attr('bgcolor');
					btn.attr('color',color);
					options.setcolor(color);
					colorpicker.removeClass('active').hide();
				}).addClass('active').show();
			});
		});
	}
})(jQuery);

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
		$color.attr('color',$input.css('color')).colorpicker({
			setcolor:function(v){setstyle();}
		});

	});
});