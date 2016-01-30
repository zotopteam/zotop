{template 'header.php'}
{template 'form/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('form/field/add/'.$formid)}">
				<i class="fa fa-plus"></i><b>{t('添加字段')}</b>
			</a>
			<a href="{U('form/data/index/'.$formid)}" class="btn btn-default"><i class="fa fa-list"></i> <b>{t('数据管理')}</b></a>
			<a href="{U('form/admin/edit/'.$formid)}" class="btn btn-default"><i class="fa fa-cog"></i> <b>{t('表单设置')}</b></a>				
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		{form::header()}
		<table class="table table-nowrap table-hover table-striped list sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="center w50">{t('状态')}</td>
					<td>{t('标签名')}</td>
					<td class="w100">{t('字段名')}</td>
					<td class="w100">{t('控件')}</td>
					<td class="w80 text-center">{t('发布页显示')}</td>
					<td class="w80 text-center">{t('列表页显示')}</td>
					<td class="w80 text-center">{t('详细页显示')}</td>
					<td class="w80 text-center">{t('不能为空')}</td>
					<td class="w80 text-center">{t('值唯一')}</td>
					<td class="w80 text-center">{t('允许搜索')}</td>

					<td class="w80 text-center">{t('排序')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="text-center">{if !$r['disabled']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td>
						<div class="title text-overflow">{$r['label']}</div>
						<div class="manage">
							<a href="{u('form/field/edit/'.$r['id'])}">{t('编辑字段')}</a>

							<s>|</s>
							{if $r['disabled']}
							<a href="{u('form/field/status/'.$r['id'])}" class="js-confirm">{t('启用')}</a>
							{else}
							<a href="{u('form/field/status/'.$r['id'])}" class="js-confirm">{t('禁用')}</a>
							{/if}

							<s>|</s>							
							{if $r['system']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('form/field/delete/'.$r['id'])}" class="js-confirm" data-confirm="<b>{t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！')}</b>">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['name']}</td>
					<td>{$controls[$r['control']]['name']}</td>
					<td class="text-center">{if $r['post']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['list']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['show']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>					
					<td class="text-center">{if $r['notnull']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['unique']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['search']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">
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
		<div class="footer-text">{t('管理表单扩展字段，拖动行可以进行排序')}</div>
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