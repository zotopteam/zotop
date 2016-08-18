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
    {C('site.meta')}
    <link href="{if C('site.favicon')}{C('site.favicon')}{else}{__THEME__}/favicon.ico{/if}" rel="shortcut icon" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{__THEME__}/apple-touch-icon-180.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{__THEME__}/apple-touch-icon-144.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{__THEME__}/apple-touch-icon-120.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{__THEME__}/apple-touch-icon-72.png">  
    <link href="{__THEME__}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{__THEME__}/css/font-awesome.min.css" rel="stylesheet">
    <link href="{__THEME__}/css/animate.min.css" rel="stylesheet">
    <link href="{__THEME__}/css/global.css" rel="stylesheet">
    <script src="{__THEME__}/js/jquery.min.js"></script>
    <script src="{__THEME__}/js/bootstrap.min.js"></script>
    <script src="{__THEME__}/js/global.js"></script>
    <!--[if lt IE 9]>
    <script src="{__THEME__}/js/html5shiv.min.js"></script>
    <script src="{__THEME__}/js/respond.min.js"></script>
    <![endif]-->
    {hook 'site.head'}
</head>
<body class="message error 404">
	<div class="container">
		<h1>{$content}</h1>
		<p class="action">
			<a class="btn btn-default hidden" href="javascript:history.go(-1)">{t('返回前页')}</a>
			<a class="btn btn-default hidden"  href="javascript:location.reload();">{t('重试')}</a>			
		</p>
	</div>
</body>
</html>