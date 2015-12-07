{template 'header.php'}
{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="{u('content/model')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title}：{m('content.model.get',$modelid,'name')}</div>
		<div class="breadcrumb hidden">
			<li><a href="{u('content/model')}">{t('模型管理')}</a></li>
			<li>{$title} : {m('content.model.get',$modelid,'name')}</li>
		</div>
		<div class="action">
			<a class="btn btn-primary btn-icon-text" href="{U('content/field/add/'.$modelid)}">
				<i class="fa fa-plus"></i><b>{t('添加字段')}</b>
			</a>

			<a class="btn btn-default btn-icon-text" href="{U('content/field/view/'.$modelid)}">
				<i class="fa fa-eye"></i><b>{t('预览表单')}</b>
			</a>			
		</div>
	</div>
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}

		{form::header()}
		<table class="table table-nowrap table-striped table-hover list sortable">
			<thead>
				<tr>
					<td class="drag"></td>
					<td class="text-center" width="40">{t('状态')}</td>
					<td>{t('标签名')}</td>
					<td>{t('字段名')}</td>
					<td>{t('控件类型')}</td>
					<td class="text-center">{t('系统字段')}</td>
					<td class="text-center">{t('前台投稿')}</td>
					<td class="text-center">{t('不能为空')}</td>
					<td class="text-center">{t('值唯一')}</td>
					<td class="text-center">{t('允许搜索')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="text-center">
						{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-muted"></i>{else}<i class="fa fa-check-circle fa-2x text-success"></i>{/if}
					</td>
					<td>
						<div class="title text-overflow">{$r['label']}</div>
						<div class="manage">
							<a href="{u('content/field/edit/'.$r['id'])}">{t('修改字段')}</a>
							<s>|</s>
							{if $r['disabled']}
							<a href="{u('content/field/status/'.$r['id'])}" class="js-confirm">{t('启用')}</a>
							{else}
							<a href="{u('content/field/status/'.$r['id'])}" class="js-confirm">{t('禁用')}</a>
							{/if}
							<s>|</s>							
							{if $r['system']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('content/field/delete/'.$r['id'])}" class="js-confirm" data-confirm="<b>{t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！')}</b>">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['name']}</td>
					<td>
						{if $controls[$r['control']]}<span title="{$r['control']}">{$controls[$r['control']]['name']}</span>{else}{$r['control']}{/if}
					</td>
					<td class="text-center">{if $r['system']}<i class="fa fa-check-circle text-success"></i>{else}<i class="fa fa-times-circle text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['post']}<i class="fa fa-check-circle text-success"></i>{else}<i class="fa fa-times-circle text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['notnull']}<i class="fa fa-check-circle text-success"></i>{else}<i class="fa fa-times-circle text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['unique']}<i class="fa fa-check-circle text-success"></i>{else}<i class="fa fa-times-circle text-muted"></i>{/if}</td>
					<td class="text-center">{if $r['search']}<i class="fa fa-check-circle text-success"></i>{else}<i class="fa fa-times-circle text-muted"></i>{/if}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}

		{/if}
	</div>
	<div class="main-footer">
		<div class="footer-text">{t('拖动列表项可以调整顺序')}</div>
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