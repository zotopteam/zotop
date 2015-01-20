{template 'header.php'}
<div class="side">
{template 'content/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a href="{u('content/model/add')}" class="btn btn-icon-text btn-highlight dialog-open" data-width="750px" data-height="450px">
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
			<td class="w300">{t('名称')}</td>
			<td class="w140">{t('标识')}</td>
			<td class="w140">{t('类型')}</td>
			<td class="w80">{t('数据')}</td>
			<td>{t('描述')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title">{$r['name']} </div>
					<div class="manage">
						<a class="dialog-confirm" href="{u('content/model/status/'.$r['id'])}">{if $r['disabled']}{t('启用')}{else}{t('禁用')}{/if}</a>
						<s></s>
						<a href="{u('content/model/edit/'.$r['id'])}" class="dialog-open" data-width="750px" data-height="450px">{t('设置')}</a>						
						<s></s>
						<a href="{u('content/field/index/'.$r['id'])}">{t('字段管理')}</a>
						<s></s>
						<a href="{u('content/model/export/'.$r['id'])}">{t('导出')}</a>												
						<s></s>
						<a href="{u('content/model/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['id']}</td>
				<td>
					{A($r['app'].'.name')}
					
					{if $r.app='content'}
						{if $r.model=='extend'} {t('扩展模型')} {else} {t('基础模型')} {/if}
					{/if}
				</td>
				<td>{$r['datacount']} {t('条')}</td>
				<td>{$r['description']}</td>
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