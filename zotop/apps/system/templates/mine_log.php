{template 'header.php'}

<div class="side">
	{template 'system/mine_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table zebra list">
		<thead>
			<tr>
			<td>{t('操作')}</td>
			<td>{t('操作结果')}</td>
			<td class="w120">{t('IP')}</td>
			<td class="w140">{t('时间')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $data $r}
			<tr>
				<td title="{t('操作')}: {$r['app']}/{$r['controller']}/{$r['action']} <br/> {t('链接')}: {$r['url']}<br/> {t('结果')}: {$r['data']}" data-placement="right">
					<div class="textflow">{$r['url']}</div>
				</td>
				<td><div class="textflow"><i class="icon icon-{$r['state']} {$r['state']}"></i> {$r['data']}</div></td>
				<td>{$r['createip']}</td>
				<td>{format::date($r['createtime'])}</td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript">

</script>
{template 'footer.php'}