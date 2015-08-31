<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo t('ZOTOP安装向导')?> <?php echo $this->steps[$this->action];?></title>
    <meta name="keywords" content="{$keywords} {C('site.keywords')}">
    <meta name="description" content="{$description} {C('site.description')}">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="format-detection" content="telephone=no">
    <link href="./theme/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link href="./theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="./theme/css/font-awesome.min.css" rel="stylesheet">
    <link href="./theme/css/global.css" rel="stylesheet">
    <script src="./theme/js/jquery.min.js"></script>
    <script src="./theme/js/jquery.plugins.js"></script>
    <script src="./theme/js/bootstrap.min.js"></script>
    <script src="./theme/js/global.js"></script>
    <!--[if lt IE 9]>
    <script src="./theme/js/html5shiv.min.js"></script>
    <script src="./theme/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header class="global-header">
  <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-header">
        <a class="navbar-brand navbar-logo" href="javascript:;"><?php echo t('ZOTOP安装向导')?></a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
        
            <?php foreach($this->steps as $action=>$title):?>
                <?php if ( $this->action == $action ) :?>
                <li class="active"><a href="javascript:;"><?php echo $title?></a> </li>
                <?php elseif( in_array($action, $this->completed)  ):?>
                <li class="completed"><a href="javascript:;"><?php echo $title?></a></li>
                <?php else:?>
                <li><a href="javascript:;"><?php echo $title?></a></li>
                <?php endif;?>      
            <?php endforeach;?>

        </ul>   
      </div>
  </nav>
</header>