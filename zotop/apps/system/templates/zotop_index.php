{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a href="http://www.zotop.com" class="btn btn-default" target="_blank"><i class="fa fa-globe fa-fw"></i>{t('官方网站')}</a>
		</div>
	</div><!-- main-header -->
	
	<div class="main-body scrollable">

		<div class="container-fluid container-primary">
			<div class="jumbotron text-center">
				<img src="{A('system.url')}/icons/zotop.png" class="hidden">
				<h1>逐涛网站管理系统</h1>
				<p>{t('简洁、易用的内容管理系统')}</p>
			</div>
		</div>

		<div class="container-fluid container-default">

			<div class="panel">
				<div class="panel-heading">
					<h2>{t('版权声明')}</h2>
				</div>	
				<div class="panel-body">
					<div class="well">{file_get_contents(A('system.path').DS.'license.txt')}</div>
				</div>
			</div>

			<div class="blank">	</div>

			<div class="address">
				<div><strong>{t('版权所有 © 2008-2015 zotop team 保留所有权利')}</strong></div>
				<div>{t('程序版本')} &nbsp; v{c('zotop.version')}</div>
				<div>{t('开发团队')} &nbsp; zotop team</div>
				<div>{t('官方网站')} &nbsp; <a href="http://www.zotop.com" target="_balnk">zotop.com</a></div>
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