{template 'header.php'}
<div class="side">
	{template 'form/admin_side.php'}
</div><!-- side -->

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			<li class="current"><a href="{U('form/data/index/'.$formid)}"><i class="icon icon-item"></i> {t('数据管理')}</a></li>
			<li><a href="{U('form/field/index/'.$formid)}"><i class="icon icon-database"></i> {t('字段管理')}</a></li>
			<li><a href="{U('form/admin/edit/'.$formid)}"><i class="icon icon-setting"></i> {t('表单设置')}</a></li>			
		</ul>

		<form action="{u('form/data/index/'.$formid)}" class="searchbar" method="post">
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('form/data/add/'.$formid)}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			
			{if $form.settings.list}
			<a href="{U('form/index/list/'.$formid)}" target="_blank" class="btn btn-icon-text">
				<i class="icon icon-view"></i><b>{t('前台列表')}</b>
			</a>
			{/if}

			<a class="btn btn-icon-text" href="{U('form/data/export/'.$formid)}">
				<i class="icon icon-index"></i><b>{t('导出')}</b>
			</a>
		</div>

	</div>
	<div class="main-body scrollable">
		
		{if empty($fields)}
			<div class="nodata">{t('当前表单还没任何字段，请进入 <a href="{1}">字段管理</a> 添加字段',U('form/field/index/'.$formid))}</div>
		{elseif empty($list)}
			<div class="nodata">{t('需要设置至少一个字段作为后台列表字段，请进入 <a href="{1}">字段管理</a> 设置',U('form/field/index/'.$formid))}</div>
		{elseif empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}

			{form::header()}
			<table class="table zebra list">
				<thead>
					<tr>
						<td class="select"><input type="checkbox" class="checkbox select-all"></td>
						<td class="w50 none">{t('编号')}</td>
						{loop $list $name $field}
						<td>{$field['label']}</td>
						{/loop}
						<td class="w200">{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
				{loop $data $r}
					<tr>
						<td class="select"><input type="checkbox" name="id[]" value="{$r['id']}" class="checkbox select"/></td>
						<td class="none">{$r.id}</td>					
						{loop $list $name $field}
												
						<td>{m('form.field.show',$r[$name], $field)}</td>
							
						{/loop}
						<td>
							<div class="manage">
								{if $form['settings']['detail']}
								<a href="{u('form/index/detail/'.$formid.'/'.$r['id'])}" target="_blank"><i class="icon icon-view"></i> {t('访问')}</a>
								{else}
								<a href="{u('form/data/detail/'.$formid.'/'.$r['id'])}" class="dialog-open" data-width="1000px" data-height="450px"><i class="icon icon-view"></i> {t('详细')}</a>
								{/if}
								<s></s>				

								<a href="{u('form/data/edit/'.$formid.'/'.$r['id'])}"><i class="icon icon-edit"></i> {t('编辑')}</a>
								<s></s>

								<a href="{u('form/data/delete/'.$formid.'/'.$r['id'])}" class="dialog-confirm"><i class="icon icon-delete"></i> {t('删除')}</a>
							</div>					
						</td>
					</tr>
				{/loop}
				<tbody>
			</table>

			{form::footer()}

		{/if}
	</div>
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">

		<a class="btn operate" href="{u('form/data/operate/'.$formid.'/delete')}">{t('删除')}</a>		
	</div>
</div>

<style type="text/css">
	table.table td img{max-height: 48px;max-width: 48px;}
</style>
<script type="text/javascript">
$(function(){
	var tablelist = $('table.list').data('tablelist');

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