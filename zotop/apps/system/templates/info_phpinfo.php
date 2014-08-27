{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if $action == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div>
	<div class="main-body scrollable">
		<div id="phpinfo">{$phpinfo}</div>
	</div>
	<div class="main-footer">
		<div class="tips">{t('服务器信息')}</div>
	</div>
</div>
<style type="text/css">
	#phpinfo h1,#phpinfo h2 {padding:8px;background:#f7f7f7;font-size:20px;margin-bottom:5px;}
	#phpinfo h1 a,#phpinfo h2 a{font-size:20px;}
	#phpinfo table.table{/*table-layout:fixed;*/}
	#phpinfo table.table th{padding:5px;}
	#phpinfo table.table tr.title{padding:5px;background:#f7f7f7;font-size:16px;}
	#phpinfo table.table td.e{color:#ff6600;white-space:nowrap;}
	#phpinfo table.table td{line-height:24px;}
</style>
{template 'footer.php'}