<!DOCTYPE html>
<html>
<head>
	<title>{$title} {c('site.title')}</title>
	<meta name="keywords" content="{$keywords} {c('site.keywords')}" />
	<meta name="description" content="{$description} {c('site.description')}" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="{__THEME__}/css/global.css"/>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/css/jquery.dialog.css"/>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/icon/style.css"/>
	<script type="text/javascript" src="{__THEME__}/js/jquery.js"></script>
	<script type="text/javascript" src="{__THEME__}/js/jquery.plugins.js"></script>
	<script type="text/javascript" src="{__THEME__}/js/jquery.dialog.js"></script>
	<script type="text/javascript" src="{__THEME__}/js/global.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="{__THEME__}/favicon.ico" />
	<link rel="icon" type="image/x-icon" href="{__THEME__}/favicon.ico" />
	<link rel="bookmark" type="image/x-icon" href="{__THEME__}/favicon.ico" />
	<link rel="apple-touch-icon" href="{__THEME__}/favicon.ico"/>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/icon/ie7/ie7.css"/>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/css/ie7.css"/>
	<![endif]-->

	{hook 'site.head'}
</head>
<body>
<div class="wrapper">
{hook 'site.header'}

<div class="header clearfix">
	<div class="topbar">
		<ul class="fl">
			<li class="welcome">{t('您好，欢迎来到%s',c('site.name'))} </li>
			<li><s></s> <a href="javascript:;" class="addtofav"><i class="icon icon-star2"></i> {t('加入收藏')}</a></li>
			<li class="none"><s></s> <a href=""><i class="icon icon-mobile"></i> {t('手机版')}</a></li>
		</ul>
		{if a('member')}<div class="loginbar ajax-load" data-src="{U('member/login/bar')}"></div>{/if}
	</div>
	<div class="logo" title="{c('site.name')}"><a href="{u()}">{c('site.name')}</a></div>
	<div class="search">
        <form class="search-form clearfix" action="{u('content/search')}" method="get">
            <input class="search-text" type="search" name="keywords" value="{$_GET.keywords}" placeholder="{t('请输入关键词')}" />
            <button type="submit" class="icon icon-search"></button>
        </form>
        <div class="hot-keywords">
        	{block id="3" }
        </div>       
	</div>
</div>

<div class="navbar">{block id="1" name="t('网站主导航')" template="block/navbar.php" commend="1"}</div>

<div class="body">