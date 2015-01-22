{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('shop/type/add')}">
				<i class="icon icon-add"></i><b>{t('添加类型')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">


		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		{form::header()}
		<table class="table zebra list sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="w60 center">{t('状态')}</td>
					<td class="w300">{t('名称')}</td>
					<td class="w100">{t('编号')}</td>
					<td>{t('描述')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" class="checkbox" name="id[]" value="{$r['id']}"/></td>
					<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							<a href="{u('shop/type/edit/'.$r['id'])}">{t('编辑')}</a>
							<s></s>
							<a href="{u('shop/type/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{$r['id']}</td>
					<td>{$r['description']}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">

	</div>
</div>
<script type="text/javascript">
//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		handle: "td.drag",
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