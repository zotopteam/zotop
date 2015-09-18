{template 'header.php'}

{template 'system/mine_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table table-hover table-nowrap">
			<thead>
				<tr>
				<th>{t('操作')}</th>
				<th>{t('操作结果')}</th>
				<th class="w120">{t('IP')}</th>
				<th class="w140">{t('时间')}</th>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td title="{t('操作')}: {$r['app']}/{$r['controller']}/{$r['action']} <br/> {t('链接')}: {$r['url']}<br/> {t('结果')}: {$r['data']}" data-placement="right">
						<div class="text-overflow">{$r['url']}</div>
					</td>
					<td>
						<div class="text-overflow">
							{if $r.state=='success'}<i class="fa fa-check-circle fa-fw text-success"></i>{else}<i class="fa fa-times-circle fa-fw text-error"></i>{/if}
							{$r['data']}
						</div>
					</td>
					<td>{$r['createip']}</td>
					<td>{format::date($r['createtime'])}</td>
				</tr>
			{/loop}
			</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{pagination::instance($total,$pagesize,$page)}
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}