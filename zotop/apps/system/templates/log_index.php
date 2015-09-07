{template 'head.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form::header()}
	<div class="main-body scrollable">
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>{t('用户名')}</th>
						<th>{t('操作')}</th>
						<th>{t('操作结果')}</th>
						<th>{t('IP')}</th>
						<th>{t('时间')}</th>
					</tr>
				</thead>
				<tbody>
				{loop $data $r}
					<tr>
						<td>{$r['username']}</td>
						<td title="{t('操作')}: {$r['app']}/{$r['controller']}/{$r['action']} <br/> {t('链接')}: {$r['url']}<br/> {t('结果')}: {$r['data']}" data-placement="bottom">
							<div class="text-overflow">{$r['url']}</div>
						</td>
						<td><div class="text-overflow"><i class="icon icon-{$r['state']} {$r['state']}"></i> {$r['data']}</div></td>
						<td>{$r['createip']}</td>
						<td>{format::date($r['createtime'])}</td>
					</tr>
				{/loop}
				</tbody>
			</table>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{pagination::instance($total,$pagesize,$page)}
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->

{template 'foot.php'}