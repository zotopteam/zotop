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

		<table class="table list zebra" cellspacing="0" cellpadding="0">
			<tr>
				<td class="w200">{t('操作系统')}</td><td>{PHP_OS}</td>
			</tr>
			<tr>
				<td class="w200">{t('WEB服务器')}</td>
				<td>{trim(preg_replace(array('#PHP\/[\d\.]+#', '#\([\w]+\)#'), '', $_SERVER['SERVER_SOFTWARE']))}</td>
			</tr>
			<tr>
				<td class="w200">{t('PHP版本')}</td>
				<td>{phpversion()}</td>
			</tr>
			<tr>				
				<td class="w200">{t('服务器时间')}</td>
				<td>{date("Y-m-d G:i T",time())}</td>
			</tr>
			<tr>
				<td class="w200">{t('服务器IP')}</td>
				<td>{$_SERVER['SERVER_ADDR']}</td>
			</tr>
			<tr>				
				<td class="w200">{t('物理路径')}</td>
				<td>{ZOTOP_PATH}</td>
			</tr>
			<tr>				
				<td class="w200">{t('安全模式')}</td>
				<td>{if @ini_get('safe_mode')}<font class="true">{t('开启')}{else}<font class="false">{t('关闭')}{/if}</td>
			</tr>
			<tr>				
				<td class="w200">{t('socket')}</td>
				<td>{if function_exists('fsockopen')} <font class="true">{t('支持')}</font>{else}<font class="false">{t('不支持')}</font> {/if}</td>
			</tr>
			<tr>
				<td class="w200">{t('语言')}</td>
				<td>{$_SERVER[HTTP_ACCEPT_LANGUAGE]}</td>
			</tr>
			<tr>				
				<td class="w200">{t('gzip压缩')}</td>
				<td>{$_SERVER[HTTP_ACCEPT_ENCODING]}</td>
			</tr>
			<tr>
				<td class="w200">{t('URL重写')}</td>
				<td>{if $rewrite}<font class="true">{t('支持')}</font>{else}<font class="false">{t('不支持')}</font>{/if}</td>
			</tr>
			<tr>				
				<td class="w200">{t('上传限制')}</td><td>{ini_get('upload_max_filesize')}</td>

			</tr>
			<tr>
				<td class="w200">{t('POST提交限制')}</td>
				<td>{get_cfg_var("post_max_size")}</td>
			</tr>
			<tr>				
				<td class="w200">{t('脚本运行内存')}</td>
				<td>{if get_cfg_var("memory_limit")}{get_cfg_var("memory_limit")}{else}{t('无')}{/if}</td>
			</tr>
			<tr>
				<td class="w200">{t('脚本超时时间')}</td>
				<td>{get_cfg_var("max_execution_time")}</td>
			</tr>
			<tr>				
				<td class="w200">{t('被屏蔽的函数')}</td>
				<td>{if get_cfg_var("disable_functions")}{get_cfg_var("disable_functions")}{else}{t('无')}{/if}</td>
			</tr>
		</table>

	</div>
	<div class="main-footer">
		<div class="tips">{t('服务器信息')}</div>
	</div>
</div>
{template 'footer.php'}