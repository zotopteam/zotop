<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{$title} {t('逐涛网站管理系统')}</title>
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
  <link href="{A('system.url')}/assets/css/jquery.dialog.css" rel="stylesheet">
  <link href="{A('system.url')}/assets/css/global.css" rel="stylesheet">
  <script src="{A('system.url')}/assets/js/jquery.min.js"></script>
  <script src="{A('system.url')}/assets/js/jquery.dialog.js"></script>
  <script src="{A('system.url')}/assets/js/bootstrap.min.js"></script>
  <script src="{A('system.url')}/assets/js/plugins.js"></script>
  <script src="{A('system.url')}/assets/js/zotop.js"></script>
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
{if $_USER}

	<nav class="row" role="navigation">
				
		<div class="col-xs-6 col-md-7 col-lg-8">
	      	<ul class="nav global-navbar tabdropable">
	      		<li class="brand dropdown">
	      			<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{t('逐涛内容管理系统')}</a>
					<ul class="dropdown-menu">
						<li><a href="{u('system/system/reboot')}" class="js-confirm"><i class="fa fa-refresh fa-fw"></i> {t('重启系统')}</a></li>
						<li><a href="{u('system/check')}"><i class="fa fa-server fa-fw"></i> {t('系统检测')}</a></li>
						<li class="divider" role="separator"></li>
						<li><a href="http://www.zotop.com" target="_blank"><i class="fa fa-globe fa-fw"></i> {t('官方网站')}</a></li>					
						<li><a href="{u('system/zotop')}"><i class="fa fa-info-circle fa-fw"></i> {t('关于zotop')}</a></li>
					</ul>      			
	      		</li>
	      		<li class="sitename hidden-xs"><a href="{U()}" target="_blank">{C('site.name')}</a></li>
	      		<li class="starting {if ZOTOP_APP=='system' and ZOTOP_CONTROLLER=='admin'} active{/if}"><a href="{u('system/admin')}"><i class="fa fa-dashboard hidden"></i> {t('开始')}</a></li>
				{loop zotop::filter('system.globalnavbar',array()) $id $nav}
				{if $nav.menu}
				<li class="hidden-xs hidden-sm dropdown {if $nav.active}active{/if}">
					<a href="{$nav.href}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$nav.text} <span class="fa fa-angle-down"></span></a>
					<ul class="dropdown-menu">
					{if is_array($nav.menu)}
						{loop $nav.menu $m}
							{if is_array($m)}
							<li class="dropdown-menu-item {$m.class}"><a href="{$m.href}">{$m.icon} {$m.text}</a></li>
							{elseif $m=='divider'}
							<li class="divider" role="separator"></li>
							{else}
								{$m}
							{/if}
						{/loop}
					{else}
						{$nav.menu}
					{/if}
					</ul>
				</li>
				{else}
				<li class="hidden-xs hidden-sm {if $nav.active} active{/if}"><a href="{$nav.href}">{$nav.text}</a></li>
				{/if}
				{/loop}      		
	      	</ul>
		</div>
		<div class="col-xs-6 col-md-5 col-lg-4">
			<ul class="nav global-navbar pull-right">
				{if $_GLOBALMSG = zotop::filter('system.globalmsg',array()) }
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-bell a-flash"></i> <span class="badge badge-xs badge-danger">{count($_GLOBALMSG)}</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li class="dropdown-header">{t('您有 $1 条待处理信息',count($_GLOBALMSG))}</h2>
						{loop $_GLOBALMSG $msg}
						<li class="dropmenu-menu-item"><a href="{$msg.href}"><i class="fa fa-{$msg.type} fa-fw"></i> {$msg.text}</a></li>
						{/loop}
					</ul>
				</li>
				{/if}
				<li class="gotohome"><a href="{u()}" title="{t('访问 $1 首页',C('site.name'))}" target="_blank"><i class="fa fa-home"></i> <span class="hidden-xs hidden-sm">{t('网站首页')}</span></a></li>
				<li class="clearcache"><a class="js-ajax-post" href="{u('system/system/refresh')}" title="{t('一键刷新缓存')}"><i class="fa fa-magic"></i> <span class="hidden-xs hidden-sm">{t('一键刷新')}</span></a></li>
				<li class="dropdown hidden-xs">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-user"></i> <span class="hidden-xs hidden-sm">{zotop::user('username')}</span> <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{u('system/mine')}"><i class="fa fa-user fa-fw"></i> {t('编辑我的资料')}</a></li>
						<li><a href="{u('system/mine/password')}"><i class="fa fa-edit fa-fw"></i> {t('修改我的密码')}</a></li>
						<li><a href="{u('system/mine/priv')}"><i class="fa fa-sitemap fa-fw"></i> {t('查看我的权限')}</a></li>
						<li><a href="{u('system/mine/log')}"><i class="fa fa-flag fa-fw"></i> {t('查看我的日志')}</a></li>
						<li><a href="{u('system/login/logout')}" class="js-confirm" data-confirm="{t('您确定要退出嘛')}"><i class="fa fa-sign-out fa-fw"></i> {t('退出')}</a></li>
					</ul>
				</li>						
			</ul>
		</div>      	
	</nav>
{else}
	<nav class="navbar" role="navigation">
		<div class="navbar-header">

		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="{u()}"><i class="fa fa-home"></i> {t('网站首页')}</a></li>
				<li><a href="javascript:void(0);" class="add-favorite"><i class="fa fa-star"></i> {t('加入收藏夹')}</a></li>
				<li class="hidden"><a href="{u('system/login/shortcut')}"><i class="fa fa-heart"></i> {t('设为桌面图标')}</a></li>
			</ul>
		</div>
	</nav>
{/if}	
</header>

<section class="global-body scrollable">