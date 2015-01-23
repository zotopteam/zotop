{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('developer')}">{t('开发助手')}</a>
			<s class="arrow">></s>
			<a href="{u('developer/project')}">{$app['name']}</a>
			<s class="arrow">></s>			
			{$title}
		</div>				
		<div class="action">
				<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/createtable')}" data-width="800" data-height="300">
					<i class="icon icon-add"></i>
					<b>{t('新建数据表')}</b>
				</a>
		</div>
	</div>
	<div class="main-body scrollable">

			{if empty($tables)}
				<div class="nodata">{t('没有找到任何数据表')}</div>
			{else}
			<form>
			<table class="table zebra list">
				<thead>
					<tr>
						<td>{t('名称')}({t('真实名称')})</td>
						<td class="w260">{t('说明')}</td>
						<td class="w60">{t('行数')}</td>
						<td class="w80">{t('大小')}</td>
						<td class="w80">{t('类型')}</td>
						<td class="w120">{t('整理')}</td>
					</tr>
				</thead>
				<tbody>
				{loop $tables $id $table}
					<tr class="{if $i%2==0}even{else}odd{/if}">
						<td>
							<div class="title"><b class="name">{$id}</b><span>( {$table[name]} )</span></div>
							<div class="manage">
								<a href="{u('developer/schema/'.$id)}">{t('表结构')}</a>
								<s>|</s>
								<a class="dialog-open" href="{U('developer/project/edittable/'.$id)}" data-width="800" data-height="300">{t('表设置')}</a>
								<s>|</s>
								<a class="dialog-confirm" href="{u('developer/database/delete/'.$id)}">{t('删除')}</a>
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
		<div class="tips">{t('应用项关联的数据表，如果是从其他位置添加的数据表，请手动修改当前app.php中的tables一项')}</div>
	</div>
</div>

{template 'footer.php'}