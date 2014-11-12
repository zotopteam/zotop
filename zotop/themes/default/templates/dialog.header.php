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
	<script type="text/javascript">
		var $dialog = $.dialog();
			$dialog.statusbar('');
	</script>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/icon/ie7/ie7.css"/>
	<link rel="stylesheet" type="text/css" href="{__THEME__}/css/ie7.css"/>
	<![endif]-->

	{hook 'site.head'}
</head>
<body>
<div class="wrapper">
