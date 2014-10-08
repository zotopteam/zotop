<!DOCTYPE html>
<html>
<head>
	<title>{$title} {t('逐涛网站管理系统')}</title>
	<meta content="none" name="robots" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/global.css"/>
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/icon/style.css"/>
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/jquery.dialog.css"/>
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/login.css"/>
	<script type="text/javascript" src="{A('system.url')}/common/js/jquery.js"></script>
	<script type="text/javascript" src="{A('system.url')}/common/js/zotop.js"></script>
	<script type="text/javascript" src="{A('system.url')}/common/js/jquery.plugins.js"></script>
	<script type="text/javascript" src="{A('system.url')}/common/js/jquery.dialog.js"></script>
	<script type="text/javascript" src="{A('system.url')}/common/js/global.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
	<link rel="icon" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
	<link rel="bookmark" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
	{hook 'admin.head'}
</head>
<body>

{hook 'admin.header'}

<div class="topbar">
	<a href="{u()}"><i class="icon icon-home"></i> {t('网站首页')}</a>
	<b>│</b>
	<a href="javascript:void(0);" class="add-favorite"><i class="icon icon-star2"></i> {t('加入收藏夹')}</a>
	<b>│</b>
	<a href="{u('system/login/shortcut')}"><i class="icon icon-heart"></i> {t('设为桌面图标')}</a>
</div>

<div class="box hidden" id="loginbox">
	<div class="box-body">
		{form::header()}
		<div class="form-header"></div>
		<div class="form-status">{t('请输入您的账号和密码登录')}</div>
		<div class="form-body">
			<table class="field">
				<tbody>
					<tr>
						<td class="label">{form::label(t('用户名'),'username')}</td>
						<td class="input">
							{form::field(array('type'=>'text','name'=>'username','value'=>($remember_username ? $remember_username : ''),'required'=>'required'))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('密&nbsp;&nbsp;&nbsp;码'),'password')}</td>
						<td class="input">
							{form::field(array('type'=>'password','name'=>'password','required'=>'required'))}
						</td>
					</tr>
					{if c('system.login_captcha')}
					<tr>
						<td class="label">{form::label(t('验证码'),'captcha')}</td>
						<td class="input">
							{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}
						</td>
					</tr>
					{/if}
				</tbody>
			</table>
		</div><!-- form-body -->
		<div class="form-footer">
			<span class="field remember">
				<label>
					<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" {if $remember_username}checked="checked"{/if}/>
					{t('记住账号')}
				</label>
			</span>
			{form::field(array('type'=>'submit','value'=>t('登 录')))}
		</div>
		{form::footer()}
	</div><!-- box-body -->
</div><!-- box -->
<div class="bottombar">
	{t('感谢您使用逐涛内容管理系统')}
	<div class="fr">{zotop::powered()}</div>
</div>
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 禁止被包含
	if(top!= self){top.location = self.location;}

	// 居中显示登录窗口
	$(function(){
		$('#loginbox').position({of: $( "body" ),my: 'center center',at: 'center center'}).removeClass('hidden').draggable({handle:'.form-header',containment: "parent"});
		$(window).bind('resize',function(){
			$('#loginbox').position({of: $( "body" ),my: 'center center',at: 'center center'});
		});
	})

	//加入收藏夹
	$(function(){
		$("a.add-favorite").on('click',function(){
			var title = window.document.title;
			var url = window.location.href;
			try{
				if ( window.sidebar && 'object' == typeof( window.sidebar ) && 'function' == typeof( window.sidebar.addPanel ) ){
					window.sidebar.addPanel(title, url , '');
				}else if ( document.all && 'object' == typeof( window.external ) ){
					window.external.addFavorite(url , title);
				}else {
					$.error('{t('您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏')}',5);
				}
			}catch(e){
				$.error('{t('您使用的浏览器不支持此功能，请按“Ctrl + D”键手工加入收藏')}',5);
			}
			return false;
		});
	})

	// 登录
	$(function(){
		$('form.form').validate({
			rules: {
				username: 'required',
				password: 'required'
			},
			messages: {
				username: "{t('请输入您的用户名')}",
				password: "{t('请输入您的密码')}",
				captcha: {required : "{t('请输入验证码')}"}
			},
			showErrors:function(errorMap,errorList){
				if (errorList[0]) $('.form-status').html('<span class="error">'+ errorList[0].message +'</span>');
			},
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').disable(true);
				$(form).find('.form-status').html('{t('正在登录中, 请稍后……')}');
				$.post(action, data, function(msg){
					if( msg ) $(form).find('.form-status').html('<span class="'+msg.state+'">'+ msg.content +'</span>');
					if( msg.url ){
						location.href = msg.url;
						return true;
					}
					$(form).find('.submit').disable(false);
					return false;
				},'json');
			}
		});
	});
</script>
<!--[if lte IE 7]>
<div class="notsupport">
	<h1>{t('您的浏览器版本过低，ZOTOP暂不支持您的浏览器，请升级到IE8或者更高版本浏览器')} <a href="http://windows.microsoft.com/zh-CN/windows/upgrade-your-browser">{t('立即升级')}</a></h1>
</div>
<![endif]-->

{hook 'admin.footer'}

</body>
</html>