{template 'header.php'}
{template 'block/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-open"  data-width="600" data-height="300" href="{u('block/category/add')}">
				<i class="fa fa-plus fa-fw"></i> <b>{t('添加分类')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::header()}
		<table class="table table-hover table-nowrap sortable">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w40 center hidden">{t('编号')}</td>
			<td class="w400">{t('名称')}</td>
			<td>{t('说明')}</td>
			<td class="w60 hidden">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center hidden">{$r['id']}</td>
				<td>
					<div class="title">{$r['name']}</div>
					<div class="manage">
						<a href="{u('block/admin/index/'.$r['id'])}"><i class="fa fa-folder"></i> {t('区块管理')}</a>
						<s>|</s>
						<a class="js-open"  data-width="600" data-height="300" href="{u('block/category/edit/'.$r['id'])}"><i class="fa fa-edit"></i> {t('编辑')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('block/category/delete/'.$r['id'])}"><i class="fa fa-times"></i> {t('删除')}</a>
					</div>
				</td>
				<td>{$r['description']}</td>
				<td class="hidden">{intval($r['posts'])}</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('拖动列表项可以调整顺序')}</div>
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