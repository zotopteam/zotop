<!DOCTYPE html>
<html>
<head>
	<title>{$title} {t('逐涛网站管理系统')}</title>
	<meta content="none" name="robots" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<style type="text/css">
		body {
			height: 100%;
			max-height: 100%;
			border: 0;
			font-size: 12px;
			font-family: 'Microsoft Yahei',Tahoma, Helvetica,STHeiti,Arial;
			-moz-user-select: -moz-none;
		}
		h1{font:normal 24px/48px 'Microsoft Yahei',Tahoma, Helvetica,STHeiti,Arial ;}
	</style>
</head>
<body>
<h1>{t('站点 %s 主题 %s 下缺少首页模板文件index.php',c('site.name'),c('site.theme'))}</h1>
{zotop::powered()}
</body>
</html>

