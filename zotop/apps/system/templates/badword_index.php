{template 'header.php'}
<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-highlight btn-add dialog-open" href="{u('system/badword/add')}" data-width="600px" data-height="260px">
				{t('添加')}
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="select"><input type="checkbox" class="checkbox select-all"></td>
			<td>{t('敏感词')}</td>
			<td>{t('替换词')}</td>
			<td class="w140">{t('敏感级别')}</td>
			<td class="w140">{t('管理')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td>{$r['word']}</td>
				<td>{if $r['replace']}{$r['replace']}{elseif $r['level']==0}<span class="gray">{str_repeat('*',str::len($r['word']))}</span>{/if}</td>
				<td>{if $r['level'] == 0}{t('一般')}{else}{t('危险')}{/if}</td>
				<td>
					<div class="manage">
						<a class="dialog-open" href="{u('system/badword/edit/'.$r['id'])}" data-width="600px" data-height="260px">{t('编辑')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/badword/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn operate" href="{u('system/badword/operate/delete')}">{t('删除')}</a>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
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