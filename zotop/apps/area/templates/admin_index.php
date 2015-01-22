{template 'header.php'}

<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
		<a href="{u('area/admin/index')}">{t('中国')}</a>
		{loop $parents $p}
		<s class="arrow"></s>
		<a href="{u('area/admin/index/'.$p['id'])}">{$p['name']}</a>
		{/loop}
		</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('area/admin/add/'.$parentid)}" data-width="600" data-height="200">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-icon-text dialog-confirm" href="{U('area/admin/refresh')}">
				<i class="icon icon-refresh"></i><b>{t('刷新缓存')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据表')}</div>
		{else}
		{form::header()}
		<table class="table zebra list sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td>{t('名称')}</td>
					<td>{t('编码')}</td>
					<td>{t('首字母')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							<a href="{u('area/admin/index/'.$r['id'])}">{t('下级区域')}</a>
							<s></s>
							<a href="{u('area/admin/edit/'.$r['id'])}" class="dialog-open" data-width="600" data-height="200">{t('编辑')}</a>
							<s></s>
							<a href="{u('area/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{$r['id']}</td>
					<td>{$r['letter']}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		{t('拖动区域列表排序并自动保存')}
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