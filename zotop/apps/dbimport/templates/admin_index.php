{template 'header.php'}


<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position none">
			<a href="{u('dbimport/admin/index')}">{t('规则管理')}</a>			
		</div>		
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('dbimport/admin/add')}">
				<i class="icon icon-add"></i><b>{t('新建规则')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('暂时还没有任何规则，请新建一个数据导入规则')}</div>
		{else}
		<table class="table zebra list">
			<thead>
				<tr>
					<td class="select none"><input type="checkbox" class="select-all"/></td>
					<td class="w60">{t('编号')}</td>
					<td>{t('名称')}</td>
					<td>{t('数据源')}</td>
					<td>{t('目标数据表')}</td>
					<td>{t('待导入数据条数')}</td>					
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="select none"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"/></td>
					<td>{$r.id}</td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							{if $r.source.count}
							<a href="{u('dbimport/admin/import/'.$r['id'])}" class="dialog-confirm">{t('导入数据')}</a>
							<s></s>
							{/if}
							<a href="{u('dbimport/admin/edit/'.$r['id'])}">{t('编辑规则')}</a>
							<s></s>
							<a href="{u('dbimport/admin/copy/'.$r['id'])}" class="dialog-confirm">{t('复制规则')}</a>
							<s></s>							
							<a href="{u('dbimport/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>
						<div>
						{if $r.source.driver=='mysql'}
							{$r.source.hostname}:{$r.source.hostport} / {$r.source.database} / {$r.source.table} / {$r.source.condition}
						{/if}
						</div>
					</td>
					<td>{$r.table}</td>
					<td>
						{if $r.source.count === false}
							<span class="error">{t('规则异常，请编辑规则')}</span>
						{else}
							{$r.source.count} {t('条')} 
						{/if}
					</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{/if}
	</div>
	<div class="main-footer">

	</div>
</div>
{template 'footer.php'}