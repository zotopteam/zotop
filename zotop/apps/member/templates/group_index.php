{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div><!-- side -->
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="navbar">
			{loop $models $i $m}
			<li{if $modelid == $i} class="current"{/if}><a href="{u('member/group/index/'.$m['id'])}">{$m['name']}</a></li>
			{/loop}
		</ul>
		<div class="action">
			<a class="btn btn-icon-text btn-highlight" href="{U('member/group/add/'.$modelid)}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
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
					<td class="w200" >{t('名称')}</td>
					<td>{t('说明')}</td>
					<td class="w100">{t('积分下限')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="center">{if !$r['disabled']}<i class="icon icon-true true"></i>{else}<i class="icon icon-false"></i>{/if}</td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							<a href="{u('member/group/edit/'.$r['id'])}">{t('编辑')}</a>
							<s></s>
							{if $r['issystem']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('member/group/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r['description']}</td>
					<td>{$r['point']}</td>
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