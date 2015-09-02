<!DOCTYPE html>
<html>
<head>
	<title>{$title} {t('逐涛网站管理系统')}</title>
	<meta content="none" name="robots" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/global.css"/>
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/icon/style.css"/>
	<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/jquery.dialog.css"/>
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

<div class="global-header">
	<ul class="global-navbar">
		<li class="logo menu">
			<a class="logo" href="javascript:void(0);">{t('逐涛内容管理系统')}</a>
			<div class="dropmenu">
				<div class="dropmenulist">
					<a href="{u('system/system/reboot')}" class="dialog-confirm"><i class="icon icon-refresh"></i>{t('重启系统')}</a>
					<a href="{u()}" target="_blank"><i class="icon icon-home"></i>{t('访问网站首页')}</a>
					<a href="{u('system/info/server')}"><i class="icon icon-server"></i>{t('服务器信息')}</a>
					<a href="http://www.zotop.com" target="_blank"><i class="icon icon-home"></i>{t('官方网站')}</a>
					<a href="{u('system/info/about')}"><i class="icon icon-info"></i>{t('关于zotop')}</a>
				</div>
			</div>
		</li>
		<li class="normal{if ZOTOP_APP=='system' and ZOTOP_CONTROLLER=='admin'} current{/if}"><a href="{u('system/admin')}">{t('开始')}</a></li>
		{loop zotop::filter('system.globalnavbar',array()) $id $nav}
			{if $nav.menu and is_array($nav.menu)}
			<li class="normal menu{if $nav['current']} current{/if}">
				<a href="{$nav['href']}">{$nav['text']} <i class="icon icon-angle-down"></i></a>
				<div class="dropmenu">
					<div class="dropmenulist">
						{loop $nav.menu $m}
						<a href="{$m.href}">{$m.icon}{$m.text}</a>
						{/loop}
					</div>
				</div>
			</li>
			{else}
			<li class="normal{if $nav['current']} current{/if}"><a href="{$nav['href']}">{$nav['text']}</a></li>
			{/if}
		{/loop}
		<li class="normal{if ZOTOP_APP=='system' and ZOTOP_CONTROLLER!='index'} current{/if}" style="display:none;"><a href="{u('system/system')}">{t('系统')}</a></li>
	</ul>
	<ul class="global-navbar global-userbar">

		{if $_GLOBALMSG = zotop::filter('system.globalmsg',array()) }
		<li class="menu menu-noarrow">
			<a><i class="icon icon-msg a-flash"></i><b class="msg">{count($_GLOBALMSG)}</b></a>
			<div class="dropmenu dropmenu-right">
				<h2>{t('您有 $1 条待处理信息',count($_GLOBALMSG))}</h2>
				<div class="dropmenulist dropmenumsg">
					{loop $_GLOBALMSG $msg}
					<a href="{$msg['href']}"><i class="icon icon-info icon-{$msg['type']} {$msg['type']}"></i>{$msg['text']}</a>
					{/loop}
				</div>
			</div>
		</li>
		{/if}

		<li class="site">
			<a href="{u()}" title="{t('访问 $1 首页',C('site.name'))}" target="_blank"><i class="icon icon-home"></i> {t('网站首页')}</a>
		</li>
		<li><a class="ajax-post" href="{u('system/system/refresh')}" title="{t('一键刷新缓存')}"><i class="icon icon-clear"></i> {t('一键刷新')}</a></li>
		<li class="username menu">
			<a><i class="icon icon-user"></i> {zotop::user('username')} <i class="icon icon-angle-down"></i></a>
			<div class="dropmenu dropmenu-right">
				<div class="dropmenulist">
					<a href="{u('system/mine')}"><i class="icon icon-user"></i>{t('编辑我的资料')}</a>
					<a href="{u('system/mine/password')}"><i class="icon icon-edit"></i>{t('修改我的密码')}</a>
					<a href="{u('system/mine/priv')}"><i class="icon icon-category"></i>{t('查看我的权限')}</a>
					<a href="{u('system/mine/log')}"><i class="icon icon-flag"></i>{t('查看我的日志')}</a>
					<a href="{u('system/login/logout')}" class="dialog-confirm" data-confirm="{t('您确定要退出嘛')}"><i class="icon icon-out"></i>{t('退出')}</a>
				</div>
			</div>
		</li>

	</ul>
</div>
<div class="global-body">