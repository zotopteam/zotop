{template 'header.php'}
<div class="side">
	{template 'content/admin_side.php'}
</div><!-- side -->

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('content/field/add/'.$modelid)}">
				<i class="icon icon-add"></i><b>{t('添加字段')}</b>
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
					<td class="center w50">{t('状态')}</td>
					<td>{t('标签名')}</td>
					<td>{t('字段名')}</td>
					<td>{t('控件类型')}</td>
					<td class="w80 center">{t('系统字段')}</td>
					<td class="w80 center">{t('前台投稿')}</td>
					<td class="w80 center">{t('不能为空')}</td>
					<td class="w80 center">{t('值唯一')}</td>
					<td class="w80 center">{t('允许搜索')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="center">{if !$r['disabled']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td>
						<div class="title textflow">{$r['label']}</div>
						<div class="manage">
							<a href="{u('content/field/edit/'.$r['id'])}">{t('编辑字段')}</a>

							<s></s>
							{if $r['disabled']}
							<a href="{u('content/field/status/'.$r['id'])}" class="dialog-confirm">{t('启用')}</a>
							{else}
							<a href="{u('content/field/status/'.$r['id'])}" class="dialog-confirm">{t('禁用')}</a>
							{/if}

							<s></s>							
							{if $r['system']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('content/field/delete/'.$r['id'])}" class="dialog-confirm" data-confirm="<b>{t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！')}</b>">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['name']}</td>
					<td>
						{if $controls[$r['control']]}<span title="{$r['control']}">{$controls[$r['control']]['name']}</span>{else}{$r['control']}{/if}
					</td>
					<td class="center">{if $r['system']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['post']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['notnull']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['unique']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['search']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		{t('模型的字段管理，可以拖动字段行进行排序')}
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