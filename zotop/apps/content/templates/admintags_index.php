{template 'header.php'}
<div class="side">
	{template 'content/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">
		{if $keywords}{t('Tag搜索“%s”',$keywords)}{else}{$title}{/if}
		</div>
		<form method="post" class="searchbar">
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" style="width:200px"/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::header()}
		<table class="table zebra list datalist" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"></td>
				<td>{t('Tag名称')}</td>
				<td class="w120">{t('引用')}</td>
				<td class="w120">{t('访问')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td>{$r['name']}</td>
				<td>{$r['quotes']}</td>
				<td>{$r['hits']}</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">
		<a class="btn operate" href="{u('content/admintags/operate/delete')}">{t('删除')}</a>
	</div><!-- main-footer -->
</div><!-- main -->
<script type="text/javascript">
$(function(){
	var tablelist = $('table.datalist').data('tablelist');

	//底部全选
	$('input.select-all').on('click',function(e){
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
				var data = $('form.form').serializeArray();;
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