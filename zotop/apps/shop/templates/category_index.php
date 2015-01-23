{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('shop/category')}">{t('根类别')}</a>
			{loop $parents $p}
				<s class="arrow">></s>
				<a href="{u('shop/category/index/'.$p['id'])}">{$p['name']}</a>
			{/loop}
		</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text" href="{u('shop/category/add/'.$parentid)}"><i class="icon icon-add"></i><b>{t('添加栏目')}</b></a>
			<a class="btn btn-icon-text dialog-confirm" href="{u('shop/category/repair')}"><i class="icon icon-refresh"></i><b>{t('修复栏目')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{form::header()}
		<table class="table list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w40 center">{t('状态')}</td>
			<td>{t('名称')}</td>
			<td class="w100">{t('编号')}</td>
			<td class="w120">{t('别名')}</td>
			<td class="w100">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>
		{if empty($data)}
			<tr class="nodata"><td colspan="4"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
		{else}
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title">
						{$r['name']}
					</div>
					<div class="manage">
						<a href="{$r['url']}" target="_blank">{t('访问')}</a>
						<s></s>
						<a href="{u('shop/category/index/'.$r['id'])}">{t('子类别')} [ {if $r['childid']}{count(explode(',',$r['childid']))}{else}0{/if} ]</a>
						<s></s>
						<a href="{u('shop/category/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>
						<a class="dialog-open" data-width="400" data-height="300" href="{u('shop/category/move/'.$r['id'])}">{t('移动')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('shop/category/status/'.$r['id'])}">{if $r['disabled']}{t('启用')}{else}{t('禁用')}{/if}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('shop/category/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['id']}</td>
				<td>{$r['alias']}</td>
				<td>{intval($r['datacount'])}</td>
			</tr>
		{/loop}
		{/if}
		</tbody>
		</table>
		{form::footer()}
	</div><!-- main-body -->
	<div class="main-footer">

	</div><!-- main-footer -->
</div><!-- main -->
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
				$.msg(msg);location.reload();
			},'json');
		}
	});
});
</script>
{template 'footer.php'}