<?php defined('ZOTOP') or exit('No permission resources.'); ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?> <?php echo t('逐涛网站管理系统');?></title>
<meta content="none" name="robots" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/global.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/icon/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo A('system.url');?>/common/css/jquery.dialog.css"/>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/zotop.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.plugins.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/jquery.dialog.js"></script>
<script type="text/javascript" src="<?php echo A('system.url');?>/common/js/global.js"></script>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />
<link rel="icon" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />
<link rel="bookmark" type="image/x-icon" href="<?php echo A('system.url');?>/zotop.ico" />

<?php zotop::run('admin.head', $this); ?>

</head>
<body>

<?php zotop::run('admin.header', $this); ?>

<div class="global-header">
<ul class="global-navbar">
<li class="menu">
<a class="logo" href="javascript:void(0);"><?php echo t('逐涛内容管理系统');?></a>
<div class="dropmenu">
<div class="dropmenulist">
<a href="<?php echo u('system/system/reboot');?>" class="dialog-confirm"><i class="icon icon-refresh"></i><?php echo t('重启系统');?></a>
<a href="<?php echo u('system/info/server');?>"><i class="icon icon-server"></i><?php echo t('服务器信息');?></a>
<a href="http://www.zotop.com" target="_blank"><i class="icon icon-home"></i><?php echo t('官方网站');?></a>
<a href="<?php echo u('system/info/about');?>"><i class="icon icon-info"></i><?php echo t('关于zotop');?></a>
</div>
</div>
</li>
<li class="highlight">
<a class="site" href="<?php echo u();?>" title="<?php echo t('访问 %s',c('site.name'));?>" target="_blank">
<i class="icon icon-home"></i> <?php echo c('site.name');?>
</a>
</li>
<li class="normal<?php if(ZOTOP_APP=='system' and ZOTOP_CONTROLLER=='index'):?> current<?php endif; ?>"><a href="<?php echo u('system/admin');?>"><?php echo t('开始');?></a></li>
<?php $n=1; foreach($_GLOBALNAVBAR as $id => $nav): ?>
<li class="normal<?php if($nav['current']):?> current<?php endif; ?>"><a href="<?php echo $nav['href'];?>"><?php echo $nav['text'];?></a></li>
<?php $n++;endforeach;unset($n); ?>
<li class="normal<?php if(ZOTOP_APP=='system' and ZOTOP_CONTROLLER!='index'):?> current<?php endif; ?>" style="display:none;"><a href="<?php echo u('system/system');?>"><?php echo t('系统');?></a></li>
</ul>
<ul class="global-navbar global-userbar">
<?php if($_GLOBALMSG):?>
<li class="menu menu-noarrow">
<a><i class="icon icon-msg a-flash"></i><b class="msg"><?php echo count($_GLOBALMSG);?></b></a>
<div class="dropmenu dropmenu-right">
<h2><?php echo t('您有 %s 条待处理信息',count($_GLOBALMSG));?></h2>
<div class="dropmenulist dropmenumsg">
<?php $n=1; foreach($_GLOBALMSG as $msg): ?>
<a href="<?php echo $msg['href'];?>"><i class="icon icon-info icon-<?php echo $msg['type'];?> <?php echo $msg['type'];?>"></i><?php echo $msg['text'];?></a>
<?php $n++;endforeach;unset($n); ?>
</div>
</div>
</li>
<?php endif; ?>
<li><a class="ajax-post" href="<?php echo u('system/system/onekeyclear');?>" title="<?php echo t('一键清理缓存');?>"><i class="icon icon-clear"></i> <?php echo t('一键清理');?></a></li>
<li class="username menu">
<a><i class="icon icon-user"></i> <?php echo zotop::user('username');?><b class="arrow"></b></a>
<div class="dropmenu dropmenu-right">
<div class="dropmenulist">
<a href="<?php echo u('system/mine');?>"><i class="icon icon-user"></i><?php echo t('编辑我的资料');?></a>
<a href="<?php echo u('system/mine/password');?>"><i class="icon icon-edit"></i><?php echo t('修改我的密码');?></a>
<a href="<?php echo u('system/mine/priv');?>"><i class="icon icon-category"></i><?php echo t('查看我的权限');?></a>
<a href="<?php echo u('system/mine/log');?>"><i class="icon icon-flag"></i><?php echo t('查看我的日志');?></a>
<a href="<?php echo u('system/login/logout');?>" class="dialog-confirm" data-confirm="<?php echo t('您确定要退出嘛');?>"><i class="icon icon-out"></i><?php echo t('退出');?></a>
</div>
</div>
</li>
</ul>
</div>
<div class="global-body">