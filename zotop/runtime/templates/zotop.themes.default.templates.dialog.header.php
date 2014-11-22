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
<script type="text/javascript">
var $dialog = $.dialog();
$dialog.statusbar('');
</script>

<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/icon/ie7/ie7.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo __THEME__;?>/css/ie7.css"/>
<![endif]-->

<?php zotop::run('site.head', $this); ?>
</head>
<body>
<div class="wrapper">
