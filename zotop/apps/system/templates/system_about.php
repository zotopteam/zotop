{template 'head.php'}

<div class="jumbotron text-center">
	<img src="{A('system.url')}/icons/zotop.png" class="hidden">
	<h1>逐涛网站管理系统</h1>
	<p>{t('Fast & Simple content manage system')}</p>
</div>

<div class="container-fluid">	
	<h2>{t('版权声明')}</h2>

	<div class="well">{file_get_contents(A('system.path').DS.'license.txt')}</div>

	<div class="address">
		<div><strong>{t('版权所有 © 2008-2015 zotop team 保留所有权利')}</strong></div>
		<div>{t('程序版本')} &nbsp; v{c('zotop.version')}</div>
		<div>{t('开发团队')} &nbsp; zotop team</div>
		<div>{t('官方网站')} &nbsp; <a href="http://www.zotop.com" target="_balnk">zotop.com</a></div>
	</div>
</div>

<div class="copyright clearfix">
	<div class="copyright-thanks">{t('感谢您使用逐涛网站管理系统')}</div>
	<div class="copyright-powered">{zotop::powered()}</div>
</div>

{template 'foot.php'}