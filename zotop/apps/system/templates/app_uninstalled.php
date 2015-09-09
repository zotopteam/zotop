{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="nav nav-tabs">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}><a href="{$n['href']}">{$n['text']}</a></li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if empty($apps)}
			<div class="nodata">{t('没有未安装的应用，你可以 在线安装 或者 上传 一个新的应用')}</div>
		{else}
		<table class="table table-hover table-nowrap">
			<tbody>
			{loop $apps $dir $m}
			<tr>
				<td class="w50 va-m center">
					<img src="{ZOTOP_URL_APPS}/{$dir}/app.png" style="width:48px;height:48px;margin-top:4px;">
				</td>
				<td class="w260 va-m">
					<div class="title">{$m['name']} <span class="green f12">{$m['id']}</span></div>
					<div class="manage">
						<a class="js-open" data-width="800" data-height="400" title="{t('安装该应用')}" href="{U('system/app/install',array('dir'=>rawurlencode($dir)))}">{t('安装')}</a>
						<s>|</s>
						<a class="js-confirm"  title="{t('删除该应用')}" href="{U('system/app/delete',array('dir'=>rawurlencode($dir)))}">{t('删除')}</a>
					</div>

				</td>
				<td class="w60 va-m">
					v{$m['version']}
				</td>
				<td class="va-m ">
					<p>{$m['description']}</p>
					<div class="manage">
						{if $m['author']} {t('作者')}: {$m['author']} {/if}
						{if $m['homepage']} <s>|</s> <a target="_blank" href="{$m['homepage']}">{t('网站')}</a> {/if}
					</div>
				</td>
			</tr>
			{/loop}
		</tbody>
		</table>
		{/if}
	</div><!-- main-body -->
	<div class="main-footer textflow">
		<div class="tips">
		{t('应用必须位于 %s 目录下才会显示，安装时点击该应用的<span class="red">安装</span>按钮，根据系统提示完成应用安装', str_replace(ZOTOP_PATH.DS,'',ZOTOP_PATH_APPS))}
		</div>
	</div><!-- main-footer -->
</div>
{template 'footer.php'}