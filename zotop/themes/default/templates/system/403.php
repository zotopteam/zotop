<!DOCTYPE html>
<html>
<head>
	<title>{$title} {t('逐涛网站管理系统')}</title>
	<meta content="none" name="robots" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link href="{__THEME__}/css/bootstrap.min.css" rel="stylesheet">
	<link href="{__THEME__}/css/font-awesome.min.css" rel="stylesheet">
	<link href="{__THEME__}/css/animate.min.css" rel="stylesheet">
	<link href="{__THEME__}/css/bootstrap-override.css" rel="stylesheet">
	<link href="{__THEME__}/css/global.css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
	<link rel="icon" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
	<link rel="bookmark" type="image/x-icon" href="{A('system.url')}/zotop.ico" />
</head>
<body>
	<div class="container-fluid container-primary">
		<div class="jumbotron">
		<h1>{$content}</h1>
		<a class="btn btn-default hidden" href="javascript:history.go(-1)">{t('返回前页')}</a>
		<a class="btn btn-default hidden"  href="javascript:location.reload();">{t('重试')}</a>
		</div>
	</div>
</body>
</html>