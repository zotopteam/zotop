<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="global-body">

	<div class="masthead text-center">
	    <div class="masthead-body">
	        <div class="container-fluid">
	            <h1><i class="fa fa-check-circle"></i> <?php echo t('安装成功')?></h1>
	           	<h2><?php echo t('创始人用户名');?>：<b><?php echo $admin['username'];?></b> <?php echo t('密码');?> : <b><?php echo $admin['password'];?></b></h2>

	            <h3>
					<?php if( $admin['password'] == 'admin999'  ):?>
					 <div class="text-error"><?php echo t('您使用的是系统默认密码，为确保安全，请登录后及时修改密码');?></div>
					<?php endif;?>

					<div class="text-error"><?php echo t('为了您站点的安全，安装完成后请及时删除网站根目录下的“install”文件夹');?></div>	            	
	            </h3>
	        </div>
	    </div>
	</div>

</div>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
	<a id="prev" class="btn btn-default" href="../index.php"><?php echo t('网站首页')?></a>
	<a id="next" class="btn btn-success pull-right" href="../<? echo basename(ZOTOP_PATH_CMS)?>/index.php"><?php echo t('网站管理后台')?></a>
</footer>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>