{template 'header.php'}

<div class="side">
{template 'kefu/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('kefu/admin/add')}" data-width="800px" data-height="400px">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		{form::header()}
		<table class="table list sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="w40 center">{t('状态')}</td>
					<td>{t('名称')}</td>
					<td class="w100">{t('类型')}</td>					
					<td class="w300">{t('预览')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
					<td class="w40 center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
					<td>
						<div class="title textflow">
							{if $r['account']}
								{$r['account']}<span>{$r['text']}</span>
							{else}
								{$r['text']}
							{/if}						
						</div>
						<div class="manage">
							<a href="{u('kefu/admin/edit/'.$r['id'])}" class="dialog-open" data-width="800px" data-height="400px">{t('编辑')}</a>
							<s></s>
							<a href="{u('kefu/admin/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{m('kefu.kefu.types',$r.type)}</td>
					<td><div class="title textflow">{$r['show']}</div></td>
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