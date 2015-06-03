{template 'header.php'}
<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div>
	<div class="main-body scrollable">

		<div class="content">
			<div class="content-header">
				<img src="{A('system.url')}/icons/zotop.png">
				<div class="text">
					<h2>逐涛网站管理系统</h2>
					<div>{t('Fast & Simple content manage system')}</div>
				</div>
			</div>

			<div class="content-body">

				<div class="content-title">{t('版权声明')}</div>
				<div class="license scrollable">{file_get_contents(A('system.path').DS.'license.txt')}</div>

				<div class="product">
					<div><b>{t('版权所有 © 2008-2014 zotop team 保留所有权利')}</b></div>
					<div>{t('程序版本')} &nbsp; v{c('zotop.version')}</div>
					<div>{t('开发团队')} &nbsp; zotop team</div>
					<div>{t('官方网站')} &nbsp; <a href="http://www.zotop.com" target="_balnk">zotop.com</a></div>
				</div>

			</div>
		</div>

	</div>
	<div class="main-footer">
		<div class="fr">{zotop::powered()}</div>
		{t('感谢您使用逐涛网站管理系统')}
	</div>
</div>

<style type="text/css">
	div.content-header img{width:42px;height:42px;}
	div.content-header .text{position:absolute;left:72px;top:15px;}
	div.content-header h2{font-size:20px;margin-bottom:2px;}
	div.content-header div{font-size:80%;}

	div.license{height:200px;line-height:22px;font-size: 14px;border: solid 1px #ebebeb;padding: 8px;background: #f7f7f7;}	
	div.license h2{font-size: 20px;padding:10px 0px;}
	div.license h3{font-size:20px;padding:10px 0px;}
	div.license p{line-height:24px;font-size: 14px;text-indent: 2em;margin:5px 0;}

	div.product {line-height:26px;margin:20px 0;}
</style>
{template 'footer.php'}