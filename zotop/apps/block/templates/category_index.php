{template 'header.php'}
<div class="side">
{template 'block/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open"  data-width="600px" data-height="200px" href="{u('block/category/add')}">
				<i class="icon icon-add"></i><b>{t('添加分类')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::header()}
		<table class="table zebra list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w40 center none">{t('编号')}</td>
			<td class="w400">{t('名称')}</td>
			<td>{t('说明')}</td>
			<td class="w60 none">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center none">{$r['id']}</td>
				<td>
					<div class="title">{$r['name']}</div>
					<div class="manage">
						<a href="{u('block/admin/index/'.$r['id'])}">{t('区块管理')}</a>
						<s></s>
						<a class="dialog-open"  data-width="600px" data-height="200px" href="{u('block/category/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('block/category/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['description']}</td>
				<td class="none">{intval($r['posts'])}</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('拖动列表项可以调整顺序')}</div>
	</div><!-- main-footer -->
</div><!-- main -->
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