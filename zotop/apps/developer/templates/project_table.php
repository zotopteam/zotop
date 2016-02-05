{template 'header.php'}

{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>		
		<div class="action">
				<a class="btn btn-icon-text btn-primary js-open" href="{U('developer/project/create_table')}" data-width="800" data-height="300">
					<i class="fa fa-plus"></i>
					<b>{t('新建数据表')}</b>
				</a>
		</div>
	</div>
	<div class="main-body scrollable">

			{if empty($tables)}
				<div class="nodata">{t('没有找到任何数据表')}</div>
			{else}
			<form>
			<table class="table list">
				<thead>
					<tr>
						<td>{t('数据表名称')}</td>
						<td class="w260">{t('说明')}</td>
						<td class="w60">{t('行数')}</td>
						<td class="w80">{t('大小')}</td>
						<td class="w80">{t('类型')}</td>
						<td class="w120">{t('整理')}</td>
					</tr>
				</thead>
				<tbody>
				{loop $tables $id $table}
					<tr>
						<td>
							<div class="title">
								<b class="name">{$id}</b>
								<span class="text-muted">{$table[name]}</span>
							</div>
							<div class="manage">
								<a href="{u('developer/schema/'.$id)}"> <i class="fa fa-sitemap"></i> {t('表结构')}</a>
								<s>|</s>
								<a class="js-open" href="{U('developer/project/edit_table/'.$id)}" data-width="800" data-height="300">
									<i class="fa fa-cog"></i> {t('表设置')}
								</a>
								<s>|</s>
								<a class="js-confirm" href="{u('developer/project/delete_table/'.$id)}"><i class="fa fa-times"></i> {t('删除')}</a>
							</div>
						</td>
						<td>
							{$table['comment']}
						</td>
						<td>{$table['rows']}</td>
						<td>{format::size($table['size'])}</td>
						<td>{$table['engine']}</td>
						<td>{$table['collation']}</td>
					</tr>
					{$i++}
				{/loop}
				<tbody>
			</table>
			</form>
			{/if}

	</div>
	<div class="main-footer">
		<div class="footer-text">{t('应用项关联的数据表，如果是从其他位置添加的数据表，请手动修改当前app.php中的tables一项')}</div>
	</div>
</div>

{template 'footer.php'}