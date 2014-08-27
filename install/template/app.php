<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="main scrollable">
<div class="main-inner">
	
	<h1><?php echo t('核心应用');?></h1>

	<form id="form" class="form" method="post" action="index.php?action=install">
	<table class="table list">
	<?php foreach( $systems as $app ) :?>
	<tr>
		<td class="w20 center">
			<input type="checkbox" checked="checked" disabled="disabled">
			<input type="hidden" name="app[]" value="<?php echo $app['dir']?>">
		</td>
		<td class="w40 icon center">
			<img style="width:48px;height:48px;" src="<?php echo format::url($app['icon'])?>"/>
		</td>
		<td style="vertical-align:top;line-height:22px;">
			<div class="title"><b><?php echo $app['name']?></b> <span> <?php echo $app['id']?> v<?php echo $app['version'] ?></span></div>
			<div class="description"><?php echo $app['description']?></div>
			<div class="info" style="display:none;">
				<?php echo t('开发者') ?> : <b><?php echo $app['author'] ?></b>
				<?php echo t('电子邮件') ?> : <a href="mailto:<?php echo $app['email'] ?>"><?php echo $app['email'] ?></a>
				<?php echo t('网站') ?> : <a href="<?php echo $app['homepage'] ?>" target="_blank"><?php echo $app['homepage'] ?></a>
			</div>
		</td>
	</tr>
	<?php endforeach?>
	</table>

	<h1><?php echo t('可选应用');?></h1>
	
	<table class="table list">
	<?php foreach( $apps as $app ) :?>
	<tr>
		<td class="w20 center">
			<input type="checkbox" name="app[]" value="<?php echo $app['dir']?>" checked="checked">
		</td>
		<td class="w40 icon center">
			<img style="width:48px;height:48px;" src="<?php echo format::url($app['icon'])?>"/>
		</td>
		<td style="vertical-align:top;line-height:22px;">
			<div class="title"><b><?php echo $app['name']?></b> <span><?php echo $app['id']?> v<?php echo $app['version'] ?></span></div>
			<div class="description"><?php echo $app['description']?></div>
			<div class="info" style="display:none;">
				<?php echo t('开发者') ?> : <b><?php echo $app['author'] ?></b>
				<?php echo t('电子邮件') ?> : <a href="mailto:<?php echo $app['email'] ?>"><?php echo $app['email'] ?></a>
				<?php echo t('网站') ?> : <a href="<?php echo $app['homepage'] ?>" target="_blank"><?php echo $app['homepage'] ?></a>
			</div>
		</td>
	</tr>
	<?php endforeach?>
	</table>

</div>
</div>
<div class="buttons">
	<a id="prev" class="button" href="index.php?action=data"><?php echo t('上一步')?></a>
	<a id="next" class="button" href="javascript:void(0);" onclick="submit_app();"><?php echo t('下一步')?></a>
</div>
<script type="text/javascript">
//form submit
function submit_app(){
	$('#form').submit();
}	
</script>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
