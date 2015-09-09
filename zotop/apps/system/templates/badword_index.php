{template 'header.php'}

{template 'system/system_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-open" href="{u('system/badword/add')}" data-width="600px" data-height="380px">
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

					<a class="btn btn-primary js-open" href="{u('system/badword/add')}" data-width="600px" data-height="380px">
						<i class="fa fa-plus fa-fw"></i>{t('添加')}
					</a>
				</h1>
			</div>
		{else}
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
			{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td>{$r['word']}</td>
				<td>{if $r['replace']}{$r['replace']}{elseif $r['level']==0}<span class="gray">{str_repeat('*',str::len($r['word']))}</span>{/if}</td>
				<td>{if $r['level'] == 0}{t('一般')}{else}{t('危险')}{/if}</td>
				<td class="manage">
						<a class="js-open" href="{u('system/badword/edit/'.$r['id'])}" data-width="600px" data-height="380px"><i class="fa fa-edit"></i> {t('编辑')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/badword/delete/'.$r['id'])}"><i class="fa fa-trash"></i> {t('删除')}</a>
				</td>
			</tr>
			{/loop}		
		</tbody>
		</table>
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		{pagination::instance($total,$pagesize,$page)}
		<input type="checkbox" class="checkbox select-all">
		<a class="btn btn-default operate" href="{u('system/badword/operate/delete')}">{t('删除')}</a>
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