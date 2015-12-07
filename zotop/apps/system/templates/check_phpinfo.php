{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i> <span>{t('返回')}</span></a></div>	
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->
	
	<div class="main-body scrollable">

		<div class="container-fluid container-default">
			<div class="panel">
				<div class="panel-body">
					<div class="table-responsive">
					{$phpinfo}
					</div>
				</div>			
			</div>
		</div>


	</div><!-- main-body -->
	<div class="main-footer">
		<div class="copyright clearfix">
			<div class="copyright-thanks">{t('感谢您使用逐涛网站管理系统')}</div>
			<div class="copyright-powered">{zotop::powered()}</div>
		</div>
	</div><!-- main-footer -->
</div><!-- main -->		

{template 'footer.php'}