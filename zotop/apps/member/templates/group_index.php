{template 'header.php'}

{template 'member/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		
		{if count($models) > 1}
		<div class="btn-group">
			{loop $models $i $m}
			<a href="{u('member/group/index/'.$m['id'])}" class="btn {if $modelid == $i}btn-success{else}btn-default{/if}">
				{$m['name']}
			</a>
			{/loop}			
		</div>
		{/if}

		<div class="action">
			<a class="btn btn-icon-text btn-primary" href="{U('member/group/add/'.$modelid)}">
				<i class="fa fa-plus"></i><b>{t('添加')}</b>
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
					<td class="text-center" width="60">{t('状态')}</td>
					<td >{t('名称')}</td>
					<td>{t('说明')}</td>
					<td width="100">{t('积分下限')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="drag"><input type="hidden" name="id[]" value="{$r['id']}"/></td>
					<td class="text-center">{if !$r['disabled']}<i class="fa fa-check-circle fa-2x text-success"></i>{else}<i class="fa fa-false"></i>{/if}</td>
					<td>
						<div class="title text-overflow">{$r['name']}</div>
						<div class="manage">
							<a href="{u('member/group/edit/'.$r['id'])}">{t('编辑')}</a>
							<s>|</s>
							{if $r['issystem']}
							<a href="javascript:void(0);" class="disabled">{t('删除')}</a>
							{else}
							<a href="{u('member/group/delete/'.$r['id'])}" class="js-confirm">{t('删除')}</a>
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