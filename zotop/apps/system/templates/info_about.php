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
		<div id="about">
			<img src="{A('system.url')}/icons/zotop.png">
			<div id="about-content">
				<h2>逐涛网站管理系统</h2>
				<div>{t('Fast & Simple content manage system')}</div>
			</div>
		</div>

		<div id="license">
			<div id="license-title">{t('版权声明')}</div>
			<div id="license-content" class="scrollable">{file_get_contents(A('system.path').DS.'license.txt')}</div>
		</div>

		<div id="product">
			<div><b>{t('版权所有 © 2008-2014 zotop team 保留所有权利')}</b></div>
			<div>{t('程序版本')} &nbsp; v{c('zotop.version')}</div>
			<div>{t('开发团队')} &nbsp; zotop team</div>
			<div>{t('官方网站')} &nbsp; <a href="http://www.zotop.com" target="_balnk">zotop.com</a></div>
		</div>

	</div>
	<div class="main-footer">
		<div class="fr">{zotop::powered()}</div>
		{t('感谢您使用逐涛网站管理系统')}
	</div>
</div>

<style type="text/css">
	#about{position:relative;padding:15px;background:#e5f3fb;}
	#about img{width:42px;height:42px;}
	#about-content{position:absolute;left:72px;top:11px;}
	#about-content h2{font-size:20px;margin-bottom:2px;}
	#about-content div{font-size:80%;}

	#license {margin:20px;}
	#license-title{font-size:20px;margin-bottom:8px;}
	#license-content{height:240px;line-height:22px;font-size: 14px;border: solid 1px #ebebeb;padding: 8px;background: #f7f7f7;}
	
	#license-content h2{font-size: 20px;padding:10px 0px;}
	#license-content h3{font-size:20px;padding:10px 0px;}
	#license-content p{line-height:24px;font-size: 14px;text-indent: 2em;margin:5px 0;}

	#product {line-height:26px;margin:20px;}
</style>
{template 'footer.php'}