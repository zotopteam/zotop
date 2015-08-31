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

		 <table class="table list" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td class="col2"><?php echo t('名称')?></td>
					<td class="col1"><?php echo t('目录')?></td>
					<td class="col2"><?php echo t('所需状态')?></td>
					<td class="col2"><?php echo t('当前状态')?></td>
				</tr>
			</thead>
			<tbody>
				{loop $list $f $l}
				<tr>
					<td>{$l['name']}</td>
					<td>{$l['position']}</td>
					<td>{if $l['writable']} {t('必须可写(0777)')} {else} {t('建议只读')} {/if}</td>
					<td>{if $l['is_writable']}<font class="true">{t('可写')}</font>{else}<font color="false">{t('不可写')}</font>{/if}</td>
				</tr>
				{/loop}
			</tbody>
		</table>

	</div>
	<div class="main-footer">
		<div class="tips">{t('777属性通过：文件属性正常，没有设置777属性，需要管理员使用FTP工具手动设置777属性，否则程序可能无法正常运行')}</div>
	</div>
</div>
{template 'footer.php'}