{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-open" href="{u('system/ipbanned/add')}" data-width="600px" data-height="400px">
				<i class="fa fa-plus fa-fw"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->

	{form::header()}

	<div class="main-body scrollable">

		{if empty($data)}
			<div class="nodata">
				<i class="fa fa-frown-o"></i>
				<h1>
					{t('暂时没有任何数据')}

					<a class="btn btn-primary js-open" href="{u('system/ipbanned/add')}" data-width="600px" data-height="400px">
						<i class="fa fa-plus fa-fw"></i>{t('添加')}
					</a>
				</h1>
			</div>
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
						<a class="js-open" href="{u('system/ipbanned/edit/'.$r['ip'])}" data-width="600px" data-height="400px"><i class="fa fa-edit fa-fw"></i> {t('编辑')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/ipbanned/delete/'.$r['ip'])}"><i class="fa fa-times fa-fw"></i>{t('删除')}</a>
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
		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn btn-default operate" href="{u('system/ipbanned/operate/delete')}">{t('删除')}</a>
		{pagination::instance($total,$pagesize,$page)}
	</div><!-- main-footer -->

	{form::footer()}

</div><!-- main -->
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