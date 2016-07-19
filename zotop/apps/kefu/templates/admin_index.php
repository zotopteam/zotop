{template 'header.php'}

{template 'kefu/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-open" href="{U('kefu/admin/add')}" data-width="800px" data-height="400px">
				<i class="fa fa-plus"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">
				{t('暂时没有任何数据')}

				<a class="btn btn-primary js-open" href="{U('kefu/admin/add')}" data-width="800px" data-height="400px">
					<i class="fa fa-plus fa-fw"></i>{t('添加')}
				</a>
			</div>
		{else}
		{form::header()}
		<table class="table table-hover sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="text-center" width="8%">{t('状态')}</td>
					<td>{t('名称')}</td>
					<td width="10%">{t('类型')}</td>					
					<td width="40%">{t('预览')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
					<td class="text-center">{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-muted"></i>{else}<i class="fa fa-check-circle fa-2x  text-success"></i>{/if}</td>
					<td>
						<div class="title">
							{if $r['account']}
								{$r['account']} <span>{$r['text']}</span>
							{else}
								{format::textarea($r['text'])}
							{/if}						
						</div>
						<div class="manage">
							<a href="{u('kefu/admin/edit/'.$r['id'])}" class="js-open" data-width="800px" data-height="400px"><i class="fa fa-edit"></i> {t('编辑')}</a>
							<s>|</s>
							<a href="{u('kefu/admin/delete/'.$r['id'])}" class="js-confirm"><i class="fa fa-times"></i> {t('删除')}</a>
						</div>
					</td>
					<td>{m('kefu.kefu.types',$r.type)}</td>
					<td>{$r['show']}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		<div class="footer-text">{t('拖动列表可以排序')}</div>
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