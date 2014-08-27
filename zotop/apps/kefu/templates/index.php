// kefu config
var config = {json_encode(c('kefu'))};

// kefu html
var html = [
	'<style type="text/css">',
	'	.kefu-box{position:fixed; top:'+ config.offsety +'px; '+ config.position +':'+ config.offsetx +'px; _position:absolute; z-index: 9999}',
	'	.kefu-menu{display:block;width:20px;background:#0072C6;color: #fff;line-height: 22px;padding:10px 5px;text-align: center;font-weight: normal;font-size: 16px;cursor: pointer;border-radius: 5px;}',
	'	.kefu-menu .icon{color:#fff;margin-bottom: 5px;font-size: 18px;}',
	'	.kefu-main{border:solid 1px #dadada;border-radius:5px;overflow: hidden;background:#fff;display:none;padding:1px}',
	'	.kefu-header{padding:5px;background: #0072C6;color:#fff;font-size: 16px;border-radius:3px 3px 0 0;}',
	'	.kefu-header .icon{color:#fff;}',
	'	.kefu-body{padding:5px 8px;color: #333;}',
	'	.kefu-body .item{padding:3px;}',
	'	.kefu-body .item-group{padding:3px 0;font-size:15px;font-weight:bold;color:#333}',
	'	.kefu-body .item a{text-decoration: none;}',
	'	.kefu-body .item .icon{width:20px;height:20px;margin-right:3px;font-size:18px;}',
	'	.kefu-footer{padding:5px;background: #EBEBEB;color: #333;}',
	'	.kefu-box.hover .kefu-menu{display: none;}',
	'	.kefu-box.hover .kefu-main{display: block;}',
	'</style>',
	'<div class="kefu-box">',
	'	<div class="kefu-menu">',
	'		<div class="icon icon-user"></div>'+ config.header +'',
	'	</div>',
	'	<div class="kefu-main">',
	'	<div class="kefu-header"><i class="icon icon-user"></i> '+ config.header +'</div>',
	'	<div class="kefu-body">',
	{loop m('kefu.kefu.cache') $r}
	'	<div class="item item-{$r.type}">{$r.show}</div>',
	{/loop}
	'	</div>',
	'	<div class="kefu-footer">'+ config.footer +'</div>',
	'	</div>',
	'</div>'
].join('');

document.write(html);

// kefu event
$(function(){
	$('.kefu-box').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	});
});