{template 'header.php'}
<div class="main{if empty($databases)} no-footer{/if}">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{zotop::url('database/index')}">{t('数据库')}</a>
			<s class="arrow">></s>
			{$database}
		</div>
		<div class="action">
			<a class="btn btn-highlight dialog-prompt" data-value="{$id}" data-prompt="{t('请输入数据表名称')}" href="{zotop::url('database/index/create')}">{t('新建数据表')}</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($tables)}
			<div class="nodata">{t('没有找到任何数据表')}</div>
		{else}
		<form>
		<table class="table list zebra">	
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="checkbox select-all"></td>
					<td class="w260">{t('名称')}</td>
					<td class="none">{t('数据表')}</td>					
					<td>{t('说明')}</td>
					{if $config['driver'] == 'mysql'}
					<td class="w120">{t('行数')}</td>
					<td class="w120">{t('大小')}</td>					
					<td class="w120">{t('类型')}/{t('整理')}</td>
					{/if}
				</tr>			
			</thead>
			<tbody>
			{loop $tables $id $table}
				<tr class="{if $i%2==0}even{else}odd{/if}">
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$id}"></td>
					
					<td>
						<div class="title"><b class="name" title="{$table['name']}">{$id}</b></div>
						<div class="manage">
							<a href="{zotop::url('database/schema/'.$id)}">{t('结构')}</a>
							<a class="dialog-open none" href="{zotop::url('database/schema/show/'.$id)}" data-width="800px" data-height="500px" title="{t('结构数组')}">{t('结构[s]')}</a>
							<a class="dialog-prompt" data-value="{$id}" data-prompt="{t('请输入新名称')}" href="{zotop::url('database/index/rename/'.$id)}">{t('重命名')}</a>
							<a class="dialog-confirm" href="{zotop::url('database/index/delete/'.$id)}">{t('删除')}</a>
						</div>
					</td>					
					<td class="none">{$table[name]}</td>
					<td>
						{if $config['driver'] == 'mysql'}
						{$table['comment']}
						<div class="manage">
							<a class="dialog-prompt" data-value="{$table['comment']}" data-prompt="{t('请输入数据表说明')}" href="{zotop::url('database/index/comment/'.$id)}">{t('编辑')}</a>
						</div>
						{/if}
					</td>
					{if $config['driver'] == 'mysql'}
					<td>{$table['rows']}</td>
					<td>{format::size($table['size'])}</td>					
					<td>
						<div>{$table['engine']}</div>
						<div>{$table['collation']}</div>
					</td>
					{/if}
				</tr>
				{$i++}
			{/loop}
			<tbody>
		</table>
		</form>
		{/if}
	</div>
	<div class="main-footer">
		<input type="checkbox" class="checkbox select-all middle">	
		<a class="btn operate" href="{zotop::url('database/index/operate/repair')}">{t('修复表')}</a>
		<a class="btn operate" href="{zotop::url('database/index/operate/optimize')}">{t('优化表')}</a>
		<a class="btn operate" href="{zotop::url('database/index/operate/check')}">{t('检查表')}</a>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var tablelist = $('table.list').data('tablelist');
	
	//底部全选
	$('input.select-all').click(function(e){
		tablelist.selectAll(this.checked);
	});

	//操作
	$("a.operate").each(function(){
		$(this).on("click", function(event){
			event.preventDefault();
			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
			}else{
				var href = $(this).attr('href');
				var text = $(this).text();
				var data = $('form').serializeArray();;
					data.push({name:'operation',value:text});
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);	
				},'json');
			}
		});	
	});
});
</script>
{template 'footer.php'}