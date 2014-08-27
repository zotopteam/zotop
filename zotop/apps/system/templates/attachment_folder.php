{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('system/attachment')}">{t('附件管理')}</a>
			<s class="arrow">></s>
			{$title}
		</div>
		<div class="action">
			<a class="btn btn-highlight dialog-prompt" data-value="" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/folderadd')}">
				{t('添加分类')}
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::header()}
		<table class="table zebra list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag"></td>
			<td>{t('分类名称')}</td>
			<td class="w120">{t('管理')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($folders)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $folders $data}
			<tr>
				<td class="drag"><input type="hidden" name="id[]" value="{$data['id']}"></td>
				<td><i class="icon icon-folder"></i> {$data['name']}</td>
				<td class="w120">
					<div class="manage">
						<a class="dialog-prompt" data-value="{$data['name']}" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/folderedit/'.$data['id'])}">
							{t('编辑')}
						</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/attachment/folderdelete/'.$data['id'])}">
							{t('删除')}
						</a>
					</div>
				</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('拖动分类可以直接调整顺序')}</div>
	</div><!-- main-footer -->
</div><!-- main -->
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