{template 'header.php'}
{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>

		{if $parents}
		<div class="breadcrumb hidden-sm">
			<li class="back"><a href="{u('content/category/index/'.$category.parentid)}"><i class="fa fa-angle-left"></i><span>{t('上一级')}</span></a></li>
			<li class="root"><a href="{u('content/category')}">{$title}</a></li>
			{loop $parents $p}
			<li><a href="{u('content/category/index/'.$p['id'])}">{$p['name']}</a></li>
			{/loop}
		</div>
		{/if}

		<div class="action">
			<a class="btn btn-primary btn-icon-text" href="{u('content/category/add/'.$parentid)}"><i class="fa fa-plus"></i><b>{t('添加栏目')}</b></a>
			<a class="btn btn-default btn-icon-text js-confirm" href="{u('content/category/repair')}"><i class="fa fa-wrench"></i><b>{t('修复栏目')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if empty($data)}
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}
		{form::header()}
		<table class="table table-nowrap table-hover list sortable">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="text-center" width="40">{t('状态')}</td>			
			<td>{t('名称')}</td>
			<td class="hidden" width="20"></td>
			<td class="text-center">{t('编号')}</td>
			<td>{t('别名')}</td>
			<td class="text-center">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>

			{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="text-center">
					{if $r['disabled']}
					<i class="fa fa-times-circle fa-2x text-muted"></i>
					{else}
					<i class="fa fa-check-circle fa-2x text-success"></i>
					{/if}
				</td>				
				<td>
					<div class="title">
						<a href="{u('content/category/index/'.$r['id'])}"><b>{$r['name']}</b></a>						
					</div>

					<div class="manage">
						{if $r['disabled']}
						<a class="js-confirm" href="{u('content/category/status/'.$r['id'])}">{t('启用')}</a>
						{else}

						<a href="{$r['url']}" target="_blank">{t('访问')}</a>
						<s>|</s>
						<a href="{u('content/category/index/'.$r['id'])}">{t('子栏目')} [ {if $r['childid']}{count(explode(',',$r['childid']))}{else}0{/if} ]</a>
						<s>|</s>
						<a href="{u('content/category/edit/'.$r['id'])}">{t('编辑')}</a>
						<s>|</s>
						<a class="js-open" data-width="600" data-height="300" href="{u('content/category/move/'.$r['id'])}">{t('移动')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('content/category/status/'.$r['id'])}">{t('禁用')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('content/category/delete/'.$r['id'])}">{t('删除')}</a>
						{/if}
					</div>
				</td>
				<td class="hidden">
					{if $r['childid']}
					<i class="fa fa-folder fa-2x text-primary" title="{t('有子栏目')}"></i>
					{else}
					<i class="fa fa-file fa-2x text-primary" title="{t('无子栏目')}"></i>
					{/if}
				</td>				
				<td class="text-center">{$r['id']}</td>
				<td>{$r['alias']}</td>
				<td class="text-center">{intval($r['datacount'])}</td>
			</tr>
			{/loop}
		
		</tbody>
		</table>
		{form::footer()}
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">
			<i class="fa fa-info-circle"></i> {t('拖动列表项可以调整顺序')}
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