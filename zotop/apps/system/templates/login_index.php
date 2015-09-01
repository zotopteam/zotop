<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$title} {C('site.title')}</title>
  <meta name="keywords" content="{$keywords} {C('site.keywords')}">
  <meta name="description" content="{$description} {C('site.description')}">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta name="format-detection" content="telephone=no">
  <link href="{A('system.url')}/assets/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{A('system.url')}/assets/apple-touch-icon-180.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{A('system.url')}/assets/apple-touch-icon-144.png">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{A('system.url')}/assets/apple-touch-icon-120.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{A('system.url')}/assets/apple-touch-icon-72.png">  
  <link href="{A('system.url')}/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="{A('system.url')}/assets/css/font-awesome.min.css" rel="stylesheet">
  <link href="{A('system.url')}/assets/css/animate.min.css" rel="stylesheet">
  <link href="{A('system.url')}/assets/css/global.css" rel="stylesheet">
  <script src="{A('system.url')}/assets/js/jquery.min.js"></script>
  <script src="{A('system.url')}/assets/js/jquery.plugins.js"></script>
  <script src="{A('system.url')}/assets/js/bootstrap.min.js"></script>
  <script src="{A('system.url')}/assets/js/global.js"></script>
  <!--[if lt IE 9]>
  <script src="{A('system.url')}/assets/js/html5shiv.min.js"></script>
  <script src="{A('system.url')}/assets/js/respond.min.js"></script>
  <![endif]-->
  {hook 'admin.head'}
</head>
<body class="{ZOTOP_APP}-{ZOTOP_CONTROLLER}-{ZOTOP_ACTION}">
{hook 'admin.header'}

<header class="global-header">
	<nav class="navbar navbar-default navbar-fixed-top">
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{u()}"><i class="fa fa-home"></i> {t('网站首页')}</a></li>
				<li><a href="javascript:void(0);" class="add-favorite"><i class="fa fa-star"></i> {t('加入收藏夹')}</a></li>
				<li><a href="{u('system/login/shortcut')}"><i class="fa fa-heart"></i> {t('设为桌面图标')}</a></li>
			</ul>   
		</div>
	</nav>
</header>

<section class="global-body">

	<div class="container-fluid">
        {form::header()}
			
			<div class="panel panel-login">
				<div class="panel-heading text-center">
					<h1 class="text-overflow">{t('{1}管理',C('site.name'))}</h1>
				</div>
				<div class="panel-body">

					<div class="form-status">{t('请输入您的用户名和密码')}</div>

					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="username" class="input-group-addon"><i class="fa fa-user fa-fw"></i></label>
							{form::field(array('type'=>'text','name'=>'username','value'=>($remember_username ? $remember_username : ''),'placeholder'=>t('用户名'),'required'=>'required'))}
							

							<label for="remember" class="input-group-addon" title="{t('记住用户名')}" data-placement="right">
								<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" tabindex="-1" {if $remember_username}checked="checked"{/if}/>
							</label>										
						</div>
					</div>

					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="password" class="input-group-addon"><i class="fa fa-lock fa-fw"></i></label>
							{form::field(array('type'=>'password','name'=>'password','placeholder'=>t('密码'),'required'=>'required'))}
						</div>
					</div>								

					{if c('system.login_captcha')}
					<div class="form-group">
						<div class="input-group input-group-merge">
							<label for="captcha" class="input-group-addon"><i class="fa fa-key fa-fw"></i></label>
							{form::field(array('type'=>'captcha','name'=>'captcha','placeholder'=>t('验证码'),'required'=>'required'))}
						</div>
					</div>
					{/if}

					<div class="form-group hidden">
						<label for="remember">
							<input type="checkbox" class="checkbox" id="remember" name="remember" value="30" {if $remember_username}checked="checked"{/if}/>
							{t('记住用户名')}
						</label>
					</div>									

					<div class="form-group">
						{form::field(array('type'=>'submit','value'=>t('登 录'),'data-loading-text'=>t('登录中，请稍后……'),'class'=>'btn-block'))}
					</div>
				
				</div>
			</div>				
	
		{form::footer()}
    </div> 


</section>

<footer class="global-footer">
	{t('感谢您使用逐涛内容管理系统')}
	<div class="pull-right hidden-xs">{zotop::powered()}</div>
</footer>


<script type="text/javascript">
	// 禁止被包含
	if(top!= self){top.location = self.location;}

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
				captcha: {required:"{t('请输入验证码')}"}
			},
			showErrors:function(errorMap,errorList){
				if (errorList[0]) $('.form-status').html('<span class="text-error">'+ errorList[0].message +'</span>');
			},
			submitHandler:function(form){

				$('.form-status').html('{t('登录中, 请稍后……')}');
				$('.submit').button('loading');
				
				$.post($(form).attr('action'), $(form).serialize(), function(msg){
					
					$('.form-status').html('<span class="'+msg.state+'">'+ msg.content +'</span>');

					if( msg.url ){
						location.href = msg.url;
						return true;
					}

					$('.submit').button('reset');

					return false;
				},'json');
			}
		});
	});
</script>


{hook 'admin.footer'}

<!--[if lt IE 8]>
<div class="notsupport">
    <h1><?php echo t(':( 非常遗憾')?></h1>
    <h2><?php echo t('ZOTOP暂不支持您的浏览器，请升级到最新的IE8浏览器')?></h2>
    <p><a href="http://windows.microsoft.com/zh-CN/windows/upgrade-your-browser"><?php echo t('立即升级')?></a></p>
</div>
<![endif]-->
</body>
</html>