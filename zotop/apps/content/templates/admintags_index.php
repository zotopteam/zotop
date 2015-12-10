{template 'header.php'}
<div class="side">
	{template 'content/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		
		{if $keywords}
			<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
			<div class="title pull-center">{t('搜索：%s',$keywords)}</div>
		{else}
			<div class="title">{$title}</div>
		{/if}
		

		<form class="searchbar input-group" method="post" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>

	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if empty($data)}
		<div class="nodata">
			<i class="fa fa-frown-o"></i>
			<h1>
				{t('暂时没有任何数据')}
			</h1>
		</div>
		{else}
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
			{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
					<td>{$r['name']}</td>
					<td>{$r['quotes']}</td>
					<td>{$r['hits']}</td>
				</tr>
			{/loop}	
			</tbody>
			</table>
			{form::footer()}
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">
		
		<a class="btn btn-default operate" href="{u('content/admintags/operate/delete')}">{t('删除')}</a>
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