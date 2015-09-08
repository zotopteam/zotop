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
    <script type="text/javascript">
    var $dialog = $.dialog();
        $dialog.statusbar('');
    </script>  
    <!--[if lt IE 9]>
    <script src="{A('system.url')}/assets/js/html5shiv.min.js"></script>
    <script src="{A('system.url')}/assets/js/respond.min.js"></script>
    <![endif]-->
    {hook 'admin.head'}
</head>
<body class="{ZOTOP_APP}-{ZOTOP_CONTROLLER}-{ZOTOP_ACTION} body-dialog">
{hook 'admin.header'}

<div class="global-body scrollable">
    





