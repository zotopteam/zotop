<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?> <?php echo c('site.title');?></title>
<meta name="keywords" content="<?php echo $keywords;?> <?php echo c('site.keywords');?>" />
<meta name="description" content="<?php echo $description;?> <?php echo c('site.description');?>" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/css/global.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/css/jquery.dialog.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/icon/style.css"/>
<script type="text/javascript" src="<?php echo __THEME__;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo __THEME__;?>/js/jquery.plugins.js"></script>
<script type="text/javascript" src="<?php echo __THEME__;?>/js/jquery.dialog.js"></script>
<script type="text/javascript" src="<?php echo __THEME__;?>/js/global.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo __THEME__;?>/favicon.ico" />
<link rel="icon" type="image/x-icon" href="<?php echo __THEME__;?>/favicon.ico" />
<link rel="bookmark" type="image/x-icon" href="<?php echo __THEME__;?>/favicon.ico" />
<link rel="apple-touch-icon" href="<?php echo __THEME__;?>/favicon.ico"/>

<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/icon/ie7/ie7.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/css/ie7.css"/>
<![endif]-->

<?php zotop::run('site.head', $this); ?>
</head>
<body>
<div class="wrapper">
<?php zotop::run('site.header', $this); ?>

<div class="header clearfix">
<div class="topbar">
<ul class="fl">
<li class="welcome"><?php echo t('您好，欢迎来到%s',c('site.name'));?> </li>
<li><s></s> <a href="javascript:;" class="addtofav"><i class="icon icon-star2"></i> <?php echo t('加入收藏');?></a></li>
<li class="none"><s></s> <a href=""><i class="icon icon-mobile"></i> <?php echo t('手机版');?></a></li>
</ul>

<?php if(a('member')):?><div class="loginbar ajax-load" data-src="<?php echo U('member/login/bar');?>"></div><?php endif; ?>

</div>
<div class="logo" title="<?php echo c('site.name');?>"><a href="<?php echo u();?>"><?php echo c('site.name');?></a></div>
<div class="search">
        <form class="search-form clearfix" action="<?php echo u('content/search');?>" method="get">
            <input class="search-text" type="search" name="keywords" value="<?php echo $_GET['keywords'];?>" placeholder="<?php echo t('请输入关键词');?>" />
            <button type="submit" class="icon icon-search"></button>
        </form>
        <div class="hot-keywords">
        	<div class="box">
<div class="box-head">
<h1 class="box-title">自动创建</h1>
</div>
<div class="box-body">
<ul class="list">
</ul>
</div>
</div><!-- box -->
        </div>       
</div>
<div class="button">
<a  href="http://wpa.qq.com/msgrd?v=3&uin=1436958436&site=<?php echo c('site.name');?>&menu=yes" target="_blank" class="btn btn-icon-text btn-large btn-highlight">
<i class="icon icon-good"></i> <b><?php echo t('免费方案设计');?></b>
</a>
</div>
</div>

<div class="navbar">
<ul>
    <li><a href="" >首页</a></li>
    <li><a href="" >新闻</a></li>
  </ul>
</div>

<div class="body">