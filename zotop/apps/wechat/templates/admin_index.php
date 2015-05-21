{template 'header.php'}

<div class="side">
{template 'wechat/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('wechat/admin/add')}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-icon-text" href="{U('wechat/admin/test')}">
				<i class="icon icon-database"></i><b>{t('普通按钮')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		<table class="table zebra list">
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
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"/></td>
					<td>
						<div class="title textflow">{$r['title']}</div>
						<div class="manage">
							<a href="{u('wechat/admin/edit/'.$r['id'])}">{t('编辑')}</a>
							<s></s>
							<a href="{u('wechat/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td></td>
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