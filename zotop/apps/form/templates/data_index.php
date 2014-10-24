{template 'header.php'}
<div class="side">
	{template 'form/admin_side.php'}
</div><!-- side -->

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			<li class="current"><a href="{U('form/data/index/'.$formid)}">{t('数据管理')}</a></li>
			<li><a href="{U('form/field/index/'.$formid)}">{t('字段管理')}</a></li>
			<li><a href="{U('form/admin/edit/'.$formid)}">{t('表单设置')}</a></li>			
		</ul>

		<form action="{u('form/data/index/'.$formid)}" class="searchbar" method="post">
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('form/data/add/'.$formid)}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>

	</div>
	<div class="main-body scrollable">
		
		{if empty($fields)}
			<div class="nodata">{t('表单还没任何字段，请进入 <a href="{1}">字段管理</a> 添加字段',U('form/field/index/'.$formid))}</div>
		{elseif empty($list)}
			<div class="nodata">{t('需要设置至少一个字段作为后台列表字段，请进入 <a href="{1}">字段管理</a> 设置',U('form/field/index/'.$formid))}</div>
		{elseif empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}

		{form::header()}
		<table class="table zebra list">
			<thead>
				<tr>
					<td class="w50">{t('编号')}</td>
					{loop $list $name $field}
					<td>{$field['label']}</td>
					{/loop}
					<td class="w200">{t('操作')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td><input type="hidden" name="id[]" value="{$r['id']}"/>{$r.id}</td>					
					{loop $list $name $field}
											
					<td>{m('form.field.show',$r[$name], $field)}</td>
						
					{/loop}
					<td>
						<div class="manage">
							{if $form['settings']['detail']}
							<a href="{u('form/index/detail/'.$formid.'/'.$r['id'])}" target="_blank"><i class="icon icon-view"></i> {t('访问')}</a>
							<s></s>
							{/if}

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
	</div>
</div>
<script type="text/javascript">
//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		helper: function(e,tr){
			tr.children().each(function(){
				$(this).width($(this).width());
			});
			return tr;
		},
		update:function(){
			var action = $(this).parents('form').attr('action');
			var data = $(this).parents('form').serialize();
			$.post(action, data, function(msg){
				$.msg(msg);
			},'json');
		}
	});
});
</script>
{template 'footer.php'}