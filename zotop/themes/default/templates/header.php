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
  <link href="{__THEME__}/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{__THEME__}/apple-touch-icon-180.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{__THEME__}/apple-touch-icon-144.png">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{__THEME__}/apple-touch-icon-120.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{__THEME__}/apple-touch-icon-72.png">  
  <link href="{__THEME__}/css/bootstrap.min.css" rel="stylesheet">
  <link href="{__THEME__}/css/font-awesome.min.css" rel="stylesheet">
  <link href="{__THEME__}/css/animate.min.css" rel="stylesheet">
  <link href="{__THEME__}/css/bootstrap-override.css" rel="stylesheet">
  <link href="{__THEME__}/css/global.css" rel="stylesheet">
  <script src="{__THEME__}/js/jquery.min.js"></script>
  <script src="{__THEME__}/js/bootstrap.min.js"></script>
  <script src="{__THEME__}/js/global.js"></script>
  <!--[if lt IE 9]>
  <script src="{__THEME__}/js/html5shiv.min.js"></script>
  <script src="{__THEME__}/js/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<header>
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-toggle navbar-button" href="{u('content/search')}"><i class="icon fa fa-search"></i></a>
        <a class="navbar-toggle navbar-button" href="{U('member/login')}"><i class="icon fa fa-user"></i> {t('登录')}</a>
        <a class="navbar-brand navbar-logo" href="{U()}">{C('site.name')}</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="home"><a href="{U()}">{t('首页')}</a></li>          
          {content action="category"}
          {if $r.settings.isnav}
          <li class="item {if $r.id == $category.rootid}active{/if} {if $r.childid}dropdown{/if}">
            <a href="{$r.url}">{$r.name}</a>          
            {if $r.childid}
            <ul class="dropdown-menu">
              {content action="category" cid="$r.id" return="r2"}
              {if $r2.settings.isnav}
              <li><a href="{$r2.url}">{$r2.name}</a></li>
              {/if}
              {/content}
            </ul>              
            {/if}          
          </li>
          {/if}
          {/content}
        </ul>
        <form id="navbar-search-form" class="navbar-form navbar-right navbar-search-form" action="{u('content/search')}" method="get" role="search">
          <div class="form-group">
            <div class="input-group">
              <input type="text" name="keywords" class="form-control" value="{$_GET['keywords']}" placeholder="请输入关键词">
              <div class="input-group-addon"><button type="submit" class="btn-transparent fa fa-search"></button></div>
            </div>
          </div>
        </form>     
      </div>
    </div>
  </nav>
</header>