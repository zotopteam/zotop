{template 'header.php'}
<div class="side">
{template 'block/admin_side.php'}
</div>

{form::header(U('block/datalist/order/'.$block.id))}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('block/admin/index/'.$categoryid)}">{$category.name}</a>
			<s class="arrow">></s>
			{t('内容维护')}		
		</div>	
		<div class="action">
			<a class="btn btn-icon-text btn-highlight dialog-open" href="{u('block/datalist/add/'.$block['id'])}" data-width="800px" data-height="400px"><i class="icon icon-add"></i><b>{t('添加')}</b></a>
			<a class="btn btn-icon-text dialog-open" href="{u('block/index/preview/'.$block['id'])}" data-width="800px" data-height="400px"><i class="icon icon-view"></i><b>{t('预览')}</b></a>
			<a class="btn btn-icon-text" href="{u('block/admin/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if $block.data and is_array($block.data)}		
			<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td class="drag">&nbsp;</td>
						<td class="w40 center">{t('行号')}</td>
						<td>{t('标题')}</td>
						<td class="w300">{t('操作')}</td>
						<td class="w140">{t('发布时间')}</td>
					</tr>
				</thead>
				<tbody>
				{loop m('block.datalist.getlist',$block.id) $i $r}
					<tr>
						<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;<input type="hidden" name="id[]" value="{$r.id}"></td>
						<td class="w40 center"></td>
						<td>
							<div class="title overflow">
							{if $r.url}
								<a href="{U($r.url)}" target="_blank">{$r.title}</a>
							{else}
								{$r.title}
							{/if}
							</div> 
						</td>
						<td>
							<div class="manage">
								{if $r.stick}
								<a href="{U('block/datalist/stick/'.$r.id)}" class="ajax-post"><i class="icon icon-down"></i> {t('取消置顶')}</a>
								{else}
								<a href="{U('block/datalist/stick/'.$r.id)}" class="ajax-post"><i class="icon icon-up"></i> {t('设为置顶')}</a>
								{/if}
								<s>|</s>
								<a href="{U('block/datalist/edit/'.$r.id)}" data-width="800px" data-height="400px" class="dialog-open"><i class="icon icon-edit"></i> {t('编辑')}</a>
								<s>|</s>
								<a href="{U('block/datalist/delete/'.$r.id)}" class="dialog-confirm"><i class="icon icon-delete"></i> {t('删除')}</a>
							</div>
						</td>
						<td class="w140">{format::date($r.createtime,'datetime')}</td>
					</tr>					
				{/loop}				
				</tbody>
			</table>
		{else}
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{/if}

		{if $block['description']}
		<div class="description">
			<div class="tips"><i class="icon icon-info alert"></i> {$block['description']} </div>
		</div>
		{/if}

	</div><!-- main-body -->
	<div class="main-footer">

	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<style type="text/css">
div.description{line-height:22px;font-size:14px;clear: both; border: solid 1px #F2E6D1; background: #FCF7E4; color: #B25900; border-radius: 5px;margin: 10px 0; padding: 10px;}
</style>
<script type="text/javascript">

// 重排行号
function linenumber(){
    $("table.sortable tbody tr").each(function(d, a) {
        $(a).find("td:eq(1)").text(d + 1);
    });	
}

//sortable
$(function(){
	linenumber();

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