{template 'header.php'}
<div class="side">
	{template 'form/admin_side.php'}
</div><!-- side -->

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			<li><a href="{U('form/data/index/'.$formid)}"><i class="icon icon-item"></i> {t('数据管理')}</a></li>
			<li class="current"><a href="{U('form/field/index/'.$formid)}"><i class="icon icon-database"></i> {t('字段管理')}</a></li>
			<li><a href="{U('form/admin/edit/'.$formid)}"><i class="icon icon-setting"></i> {t('表单设置')}</a></li>			
		</ul>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('form/field/add/'.$formid)}">
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
					<td class="w100">{t('字段名')}</td>
					<td class="w100">{t('控件')}</td>
					<td class="w80 center">{t('前台发布')}</td>
					<td class="w80 center">{t('列表显示')}</td>
					<td class="w80 center">{t('前台显示')}</td>
					<td class="w80 center">{t('不能为空')}</td>
					<td class="w80 center">{t('值唯一')}</td>
					<td class="w80 center">{t('允许搜索')}</td>

					<td class="w80 center">{t('排序')}</td>
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
							<a href="{u('form/field/edit/'.$r['id'])}">{t('编辑字段')}</a>

							<s></s>
							{if $r['disabled']}
							<a href="{u('form/field/status/'.$r['id'])}" class="dialog-confirm">{t('启用')}</a>
							{else}
							<a href="{u('form/field/status/'.$r['id'])}" class="dialog-confirm">{t('禁用')}</a>
							{/if}

							<s></s>							
							{if $r['system']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('form/field/delete/'.$r['id'])}" class="dialog-confirm" data-confirm="<b>{t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！')}</b>">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['name']}</td>
					<td>{$controls[$r['control']]['name']}</td>
					<td class="center">{if $r['post']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['list']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['show']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>					
					<td class="center">{if $r['notnull']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['unique']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">{if $r['search']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">
						{if $r['order']=='ASC'}
							<span class="true">{t('升序')}</span>
						{elseif $r['order']=='DESC'}
							<span class="true">{t('降序')}</span>
						{else}
							
						{/if}
						
					</td>

				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		{t('管理表单扩展字段，拖动行可以进行排序')}
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