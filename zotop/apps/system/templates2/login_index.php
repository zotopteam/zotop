<!DOCTYPE html>
<html>
<head>
	<title>{$title} {t('逐涛网站管理系统')}</title>
	<meta content="none" name="robots" />
	<meta name="renderer" content="webkit" />
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
		<div class="form-status"></div>
		<div class="form-body">

				<div class="input-group">
					<label for="username" class="input-group-addon"><i class="icon icon-user"></i></label>
					{form::field(array('type'=>'text','name'=>'username','value'=>($remember_username ? $remember_username : ''),'placeholder'=>t('用户名'),'required'=>'required'))}
				</div>

				<div class="input-group">
					<label for="password" class="input-group-addon"><i class="icon icon-lock"></i></label>
					{form::field(array('type'=>'password','name'=>'password','placeholder'=>t('密码'),'required'=>'required'))}
				</div>
				
				{if c('system.login_captcha')}
				<div class="input-group">
					<label for="captcha" class="input-group-addon"><i class="icon icon-safe"></i></label>
					{form::field(array('type'=>'captcha','name'=>'captcha','placeholder'=>t('验证码'),'required'=>'required'))}
				</div>
				{/if}

		</div><!-- form-body -->
		<div class="form-footer">
			<span class="field remember">
				<label>
					<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" {if $remember_username}checked="checked"{/if}/>
					{t('记住用户名')}
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
				$(form).find('.submit').disable(true);
				$(form).find('.form-status').html('{t('正在登录中, 请稍后……')}');
				
				$.post($(form).attr('action'), $(form).serialize(), function(msg){
					zotop.debug(msg);
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