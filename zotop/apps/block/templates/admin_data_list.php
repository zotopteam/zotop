{template 'header.php'}
{template 'block/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="breadcrumb hidden">
			<li><a href="{u('block/admin/index/'.$categoryid)}">{$category.name}</a></li>
			<li>{t('内容维护')}</li>		
		</ul>	
		<div class="action">
			<a class="btn btn-primary btn-icon-text js-open" href="{u('block/datalist/add/'.$block['id'])}" data-width="800px" data-height="400px">
				<i class="fa fa-plus"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-default btn-icon-text js-open" href="{u('block/index/preview/'.$block['id'])}" data-width="800px" data-height="400px">
				<i class="fa fa-eye"></i><b>{t('预览')}</b>
			</a>
			<a class="btn btn-default btn-icon-text" href="{u('block/admin/edit/'.$block['id'])}">
				<i class="fa fa-cog"></i><b>{t('设置')}</b>
			</a>
		</div>
	</div><!-- main-header -->

	{form::header(U('block/datalist/order/'.$block.id))}

	<div class="main-body scrollable">

		{if $block.data and is_array($block.data)}		
		<table class="table table-hover table-nowrap sortable" id="datalist" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td class="drag">&nbsp;</td>
					<td class="text-center" width="40">{t('行号')}</td>
					<td>{t('标题')}</td>
					<td class="text-right" width="200">{t('操作')}</td>
				</tr>
			</thead>
			<tbody>
			{loop m('block.datalist.getlist',$block.id) $i $r}
				<tr>
					<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r.id}"></td>
					<td class="text-center"></td>
					<td>
						<div class="title text-overflow">
							{if $r.url}	<a href="{U($r.url)}" target="_blank">{$r.title}</a> {else}	{$r.title} {/if}

							{if $r.image} 
								<i class="fa fa-image text-success tooltip-block" data-placement="bottom">
									<div class="tooltip-block-content"><img src="{$r.image}" class="preview"></div>
								</i> 
							{/if}								
							{if $r.dataid}<i class="fa fa-share text-success" title="{t('关联数据')}"></i> {/if}
							{if $r.stick}<i class="fa fa-arrow-up text-success" title="{t('已置顶')}"></i> {/if}
						</div>
					</td>
					<td class="manage text-right">
							{if $r.stick}
							<a href="{U('block/datalist/stick/'.$r.id)}" class="js-ajax-post"><i class="fa fa-arrow-down"></i> {t('取消置顶')}</a>
							{else}
							<a href="{U('block/datalist/stick/'.$r.id)}" class="js-ajax-post"><i class="fa fa-arrow-up"></i> {t('置顶')}</a>
							{/if}
							<s>|</s>
							
							{if $r.url}
							<a href="{U($r.url)}" target="_blank"><i class="fa fa-eye"></i> {t('查看')}</a>
							<s>|</s>
							{/if}

							<a href="{U('block/datalist/edit/'.$r.id)}" data-width="800px" data-height="400px" class="js-open"><i class="fa fa-edit"></i> {t('编辑')}</a>
							<s>|</s>
							<a href="{U('block/datalist/delete/'.$r.id)}" class="js-confirm"><i class="fa fa-times"></i> {t('删除')}</a>
					</td>
				</tr>					
			{/loop}				
			</tbody>
		</table>
		{else}
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{/if}
		

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text text-overflow">{$block['description']}</div>	
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->

<script type="text/javascript">

// 重排行号
function linenumber(){
    $("table.sortable tbody tr").each(function(d, a) {
        $(a).find("td:eq(1)").text(d + 1);
    });	
}

// 排序
$(function(){
	linenumber();

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
			var action 	= $(this).parents('form').attr('action');
			var data 	= $(this).parents('form').serialize();

			$.post(action, data, function(msg){
				linenumber();
				$.msg(msg);
			},'json');
		}
	});
});

</script>

{template 'footer.php'}