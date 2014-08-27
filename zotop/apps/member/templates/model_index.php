{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div><!-- side -->
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('member/model/add')}">
				<i class="icon icon-add"></i><b>{t('添加模型')}</b>
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
					<td class="center w60">{t('状态')}</td>
					<td class="w400" >{t('名称')}</td>
					<td>{t('说明')}</td>
					<td class="w160">{t('数据表名')}</td>
					<td class="center w100">{t('允许注册')}</td>
					<td class="center w100">{t('会员数目')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
					<td>
						<div class="title textflow">{$r['name']} <span>{$r['id']}</span></div>
						<div class="manage">
							<a href="{u('member/model/edit/'.$r['id'])}">{t('设置')}</a>
							<s></s>
							<a href="{u('member/group/index/'.$r['id'])}">{t('会员组')}</a>
							<s></s>
							<a href="{u('member/field/index/'.$r['id'])}">{t('字段管理')}</a>
							<s></s>
							<a href="{u('member/model/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
						</div>
					</td>
					<td>{$r['description']}</td>
					<td>{$r['tablename']}</td>
					<td class="center">{if $r['settings']['register']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false false"></i>{/if}</td>
					<td class="center">0</td>
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