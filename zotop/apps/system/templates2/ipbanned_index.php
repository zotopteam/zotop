{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight btn-add dialog-open" href="{u('system/ipbanned/add')}" data-width="600px" data-height="350px">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{if empty($data)}
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}	
		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"/></td>
				<td>{t('IP')}</td>
				<td class="w200">{t('解封时间')}</td>
			</tr>
		</thead>
		<tbody>

			{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="ip[]" value="{$r['ip']}"></td>
				<td>
					<div class="title">{$r['ip']}</div>
					<div class="manage">
						<a class="dialog-open" href="{u('system/ipbanned/edit/'.$r['ip'])}" data-width="600px" data-height="350px">{t('编辑')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/ipbanned/delete/'.$r['ip'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{format::date($r['expires'])}</td>

			</tr>
			{/loop}			
		</tbody>
		</table>
		{/if}

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>
		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn operate" href="{u('system/ipbanned/operate/delete')}">{t('删除')}</a>
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