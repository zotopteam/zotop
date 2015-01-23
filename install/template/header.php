<!DOCTYPE html> 
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="renderer" content="webkit" />
	<title>zotop <?php echo t('安装向导')?> <?php echo $this->steps[$this->action];?> </title>
	<link href="./theme/global.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="./theme/jquery.js"></script>
	<script type="text/javascript" src="./theme/jquery.plugins.js"></script>
	<link rel="shortcut icon" type="image/x-icon" href="./theme/favicon.ico" /> 
	<link rel="icon" type="image/x-icon" href="./theme/favicon.ico" /> 
	<link rel="bookmark" type="image/x-icon" href="./theme/favicon.ico" />
	<script type="text/javascript">
		//自定义滚动条
		$(function(){
			$('.scrollable').jScrollPane({autoReinitialise:true,autoReinitialiseDelay:100,verticalGutter:3});
		});

		// 居中显示登录窗口
		$(function(){
			$('.wrapper').position({of: $( "body" ),my: 'center center',at: 'center center'}).removeClass('hidden').draggable({handle:'.top',containment: "parent"});
			$(window).bind('resize',function(){
				$('.wrapper').position({of: $( "body" ),my: 'center center',at: 'center center'});
			});
		})		
	</script>
</head>
<body>
<div class="wrapper">
	
<div class="header">
	<div class="logo"></div>
	<div class="top"><?php echo t('安装向导')?></div>
	<div class="steps">
		<ul>
		<?php foreach($this->steps as $action=>$title):?>
			<?php if ( $this->action == $action ) :?>
			<li class="current"><?php echo $title?></li>
			<?php elseif( in_array($action, $this->completed)  ):?>
			<li class="completed"><?php echo $title?></li>
			<?php else:?>
			<li><?php echo $title?></li>
			<?php endif;?>		
		<?php endforeach;?>
		</ul>		
	</div>

</div>
<div class="body">
