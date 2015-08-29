<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'header.php';?>
<div class="global-body scrollable">
	
    <div class="jumbotron text-center">
        <div class="container-fluid">
            <h1><?php echo t('选择您需要的应用')?></h1>
            <p><?php echo t('安装完成后，可以在应用管理里面卸载或者添加应用')?></p>
            <p></p>
        </div>
    </div>

	<div class="container-fluid">
	
		<form id="form" class="form" method="post" action="index.php?action=install">

			<div class="page-header">
				<h1><?php echo t('核心应用');?></h1>
			</div>

			<div class="page-body">
				<table class="table list">
					<tbody>
						<?php foreach( $systems as $app ) :?>
						<tr>
							<td width="20" class="text-center va-m">
								<input type="checkbox" checked="checked" disabled="disabled">
								<input type="hidden" name="app[]" value="<?php echo $app['dir']?>">
							</td>
							<td width="40" class="text-center va-m">
								<img style="width:48px;height:48px;" src="<?php echo format::url($app['icon'])?>"/>
							</td>
							<td>
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
					</tbody>
				</table>
			</div>

			<div class="page-header">
				<h1><?php echo t('可选应用');?></h1>
			</div>

			<div class="page-body">
				
				<table class="table list">
					<tbody>
						<?php foreach( $apps as $app ) :?>
						<tr>
							<td width="20" class="text-center va-m">
								<input type="checkbox" name="app[]" value="<?php echo $app['dir']?>">
							</td>
							<td width="40" class="text-center va-m">
								<img style="width:48px;height:48px;" src="<?php echo format::url($app['icon'])?>"/>
							</td>
							<td>
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
					</tbody>
				</table>

			</div>

		</form>
	</div>
</div>

<footer class="global-footer navbar-fixed-bottom clearfix" role="navigation">
	<a id="prev" class="btn btn-default" href="index.php?action=data"><i class="fa fa-angle-left"></i> <?php echo t('上一步')?></a>
	<a id="next" class="btn btn-success pull-right" href="javascript:void(0);" onclick="submit_app();"><?php echo t('下一步')?> <i class="fa fa-angle-right"></a>
</footer>
<script type="text/javascript">
//form submit
function submit_app(){
	$('#form').submit();
}	
</script>
<?php include ZOTOP_PATH_INSTALL.DS.'template'.DS.'footer.php';?>
