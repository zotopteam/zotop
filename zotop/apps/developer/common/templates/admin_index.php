{template 'header.php'}

{template '[id]/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('[id]/admin/add')}">
				<i class="fa fa-plus"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-icon-text btn-default" href="{U('[id]/admin/test')}">
				<i class="fa fa-info"></i><b>{t('普通按钮')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table list">
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="select-all"/></td>
					<td>{t('名称')}</td>
					<td class="w120">{t('发布者/发布时间')}</td>
				</tr>
			</thead>
			<tbody>
				{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r.id}"/></td>
					<td>
						<div class="title text-overflow">{$r.title}</div>
						<div class="manage">
							<a href="{u('[id]/admin/edit/'.$r.id)}">{t('编辑')}</a>
							<s>|</s>
							<a href="{u('[id]/admin/delete/'.$r.id)}" class="js-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>
						{format::date($r.createtime)}
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