{template 'header.php'}

{template 'member/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('member/model/add')}">
				<i class="fa fa-plus"></i><b>{t('添加模型')}</b>
			</a>
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
					<td class="text-center" width="40">{t('状态')}</td>
					<td>{t('名称')}</td>
					<td>{t('说明')}</td>
					<td width="160">{t('数据表名')}</td>
					<td class="text-center" width="100">{t('允许注册')}</td>
					<td class="text-center" width="100">{t('会员数目')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="text-center" width="40">{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-muted"></i>{else}<i class="fa fa-check-circle fa-2x text-success"></i>{/if}</td>
					<td>
						<div class="title text-overflow">{$r['name']} <span>{$r['id']}</span></div>
						<div class="manage">
							<a href="{u('member/model/edit/'.$r['id'])}">{t('设置')}</a>
							<s>|</s>
							<a href="{u('member/group/index/'.$r['id'])}">{t('会员组')}</a>
							<s>|</s>
							<a href="{u('member/field/index/'.$r['id'])}">{t('字段管理')}</a>
							{if $r.id != 'member'}
							<s>|</s>
							<a href="{u('member/model/delete/'.$r['id'])}" class="js-confirm">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['description']}</td>
					<td>{$r['tablename']}</td>
					<td class="text-center">{if $r['settings']['register']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-times-circle fa-2x text-muted"></i>{/if}</td>
					<td class="text-center">0</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		<div class="footer-text">{t('会员模型可以定义多种不同的会员类型，比如个人会员、企业会员等')}</div>
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