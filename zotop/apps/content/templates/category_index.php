{template 'header.php'}
<div class="side">
{template 'content/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('content/category')}">{t('根栏目')}</a>
			{loop $parents $p}
				<s class="arrow">></s>
				<a href="{u('content/category/index/'.$p['id'])}">{$p['name']}</a>
			{/loop}
		</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text" href="{u('content/category/add/'.$parentid)}"><i class="icon icon-add"></i><b>{t('添加栏目')}</b></a>
			<a class="btn btn-icon-text dialog-confirm" href="{u('content/category/repair')}"><i class="icon icon-refresh"></i><b>{t('修复栏目')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}
		{form::header()}
		<table class="table list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w40 center">{t('状态')}</td>
			<td>{t('名称')}</td>
			<td class="w80">{t('编号')}</td>
			<td class="w120">{t('别名')}</td>
			<td class="w60">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>

			{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title">
						{$r['name']}

						{if $r['childid']}	<i class="icon icon-folder" title="{t('有子栏目')}"></i> {else}	<i class="icon icon-item" title="{t('无子栏目')}"></i>	{/if}
					</div>

					<div class="manage">
						{if $r['disabled']}
						<a class="dialog-confirm" href="{u('content/category/status/'.$r['id'])}">{t('启用')}</a>
						{else}

						<a href="{$r['url']}" target="_blank">{t('访问')}</a>
						<s></s>
						<a href="{u('content/category/index/'.$r['id'])}">{t('子栏目管理')} [ {if $r['childid']}{count(explode(',',$r['childid']))}{else}0{/if} ]</a>
						<s></s>
						<a href="{u('content/category/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>
						<a class="dialog-open" data-width="400" data-height="300" href="{u('content/category/move/'.$r['id'])}">{t('移动')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('content/category/status/'.$r['id'])}">{t('禁用')}</a>
						<s></s>
						<a class="dialog-confirm" href="{u('content/category/delete/'.$r['id'])}">{t('删除')}</a>
						{/if}
					</div>
				</td>
				<td>{$r['id']}</td>
				<td>{$r['alias']}</td>
				<td>{intval($r['datacount'])}</td>
			</tr>
			{/loop}
		
		</tbody>
		</table>
		{form::footer()}
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">
			<i class="icon icon-info"></i> {t('拖动列表项可以调整顺序')}&nbsp;
			<i class="icon icon-folder"></i> {t('有子栏目')}&nbsp;
			<i class="icon icon-item"></i> {t('无子栏目')}&nbsp;
		</div>
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