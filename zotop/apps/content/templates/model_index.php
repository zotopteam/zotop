{template 'header.php'}
<div class="side">
{template 'content/side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a href="{u('content/model/add')}" class="btn btn-icon-text btn-highlight">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::header()}
		<table class="table list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w40 center">{t('状态')}</td>
			<td class="w240">{t('名称')}</td>
			<td class="w140">{t('标识')}</td>
			<td class="w120">{t('应用')}</td>
			<td class="w80">{t('数据')}</td>
			<td>{t('描述')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $models $data}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$data['id']}"></td>
				<td class="center">{if $data['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title">{$data['name']} </div>
					<div class="manage">
						<a class="dialog-confirm" href="{u('content/model/status/'.$data['id'])}">{if $data['disabled']}{t('启用')}{else}{t('禁用')}{/if}</a>
						<s></s>
						<a href="{u('content/model/edit/'.$data['id'])}">{t('设置')}</a>
					</div>
				</td>
				<td>{$data['id']}</td>
				<td>{A($data['app'].'.name')}</td>
				<td>{$data['datacount']}</td>
				<td>{$data['description']}</td>
			</tr>
		{/loop}
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