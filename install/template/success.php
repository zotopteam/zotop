<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="main scrollable">
<div class="main-inner">
	
	<h1 style="color:green;"><?php echo t('恭喜! zotop已经安装成功');?></h1>
	<br/>
	
	<h2><?php echo t('创始人帐户');?></h2>

	<div><?php echo t('用户名');?> : <b><?php echo $admin['username'];?></b></div>
	<div>
		<?php echo t('密&nbsp;&nbsp;&nbsp;码');?> : <b><?php echo $admin['password'];?></b>
		<?php if( $admin['password'] == 'admin999'  ):?>
		 ( <span style="color:red;"><?php echo t('为确保安全，请登录后及时修改密码');?></span> )
		<?php endif;?>
	</div>

	<br/>
	<h2><?php echo t('安全提示');?></h2>
	<div><?php echo t('为了您站点的安全，安装完成后请及时删除网站根目录下的“install”文件夹');?></div>

</div>
</div>

<div class="buttons">
<a id="prev" class="button" href="../index.php"><?php echo t('网站首页')?></a>
<a id="next" class="button" href="../<? echo basename(ZOTOP_PATH_CMS)?>/index.php"><?php echo t('网站管理后台')?></a>
</div>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
