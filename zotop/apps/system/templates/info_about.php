{template 'head.php'}
<div class="jumbotron text-center">
	<img src="{A('system.url')}/icons/zotop.png" class="hidden">
	<h1>逐涛网站管理系统</h1>
	<p>{t('Fast & Simple content manage system')}</p>
</div>

<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="nav navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="fa {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div>
	<div class="main-body scrollable">

		<div class="content">


			<div class="content-body">

				<h2>{t('版权声明')}</h2>
				<div class="license scrollable">{file_get_contents(A('system.path').DS.'license.txt')}</div>

				<div class="product">
					<div><b>{t('版权所有 © 2008-2015 zotop team 保留所有权利')}</b></div>
					<div>{t('程序版本')} &nbsp; v{c('zotop.version')}</div>
					<div>{t('开发团队')} &nbsp; zotop team</div>
					<div>{t('官方网站')} &nbsp; <a href="http://www.zotop.com" target="_balnk">zotop.com</a></div>
				</div>

			</div>
		</div>

	</div>
	<div class="main-footer">
		<div class="footer-text pull-right">{zotop::powered()}</div>
		<div class="footer-text">{t('感谢您使用逐涛网站管理系统')}</div>
	</div>
</div>

<!-- 固定main的头部和尾部，并设定fixed，及main的头尾padding，已global-body的滚动条为准，侧边也采用固定方式显示
 -->
<style type="text/css">
	div.license{height:200px;line-height:22px;font-size: 14px;border: solid 1px #ebebeb;padding: 8px;background: #f7f7f7;}	
	div.license h2{font-size: 20px;padding:10px 0px;}
	div.license h3{font-size:20px;padding:10px 0px;}
	div.license p{line-height:24px;font-size: 14px;text-indent: 2em;margin:5px 0;}

	div.product {line-height:26px;margin:20px 0;}
</style>
{template 'foot.php'}