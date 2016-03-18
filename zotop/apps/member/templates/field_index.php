{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div><!-- side -->
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			{loop $models $i $m}
			<li{if $modelid == $i} class="current"{/if}><a href="{u('member/field/index/'.$m['id'])}">{$m['name']}</a></li>
			{/loop}
		</ul>
		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('member/field/add/'.$modelid)}">
				<i class="fa fa-plus"></i><b>{t('添加字段')}</b>
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
					<td class="center w50">{t('状态')}</td>
					<td>{t('标签名')} ( {t('字段名')} )</td>
					<td class="w100">{t('控件')}</td>
					<td class="w100 text-center">{t('注册时显示')}</td>
					<td class="w100 text-center">{t('必填')}</td>
					<td class="w100 text-center">{t('值唯一')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="text-center">{if !$r['disabled']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-false"></i>{/if}</td>
					<td>
						<div class="title text-overflow">{$r['label']} ( {$r['name']} )</div>
						<div class="manage">
							<a href="{u('member/field/edit/'.$r['id'])}">{t('编辑')}</a>
							<s>|</s>
							{if $r['issystem']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('member/field/delete/'.$r['id'])}" class="js-confirm" data-confirm="<b>{t('您确定要删除吗？删除后将删除全部相关数据并且无法恢复！')}</b>">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$controls[$r['control']]['name']}</td>
					<td class="text-center">{if $r['base']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-false"></i>{/if}</td>
					<td class="text-center">{if $r['notnull']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-false"></i>{/if}</td>
					<td class="text-center">{if $r['unique']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-false"></i>{/if}</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		{t('管理会员扩展信息字段，拖动行可以进行排序')}
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