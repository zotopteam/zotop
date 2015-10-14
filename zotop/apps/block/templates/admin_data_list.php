{template 'header.php'}
<div class="side">
{template 'block/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="breadcrumb">
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
			<table class="table table-hover list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td class="drag">&nbsp;</td>
						<td class="w40 center">{t('行号')}</td>
						<td>{t('标题')}</td>
						<td class="w300">{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
				{loop m('block.datalist.getlist',$block.id) $i $r}
					<tr>
						<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;<input type="hidden" name="id[]" value="{$r.id}"></td>
						<td class="w40 center"></td>
						<td>
							<div class="title overflow">
								{if $r.url}	<a href="{U($r.url)}" target="_blank">{$r.title}</a> {else}	{$r.title} {/if}

								{if $r.image} 
									<i class="fa fa-image green tooltip-block" data-placement="bottom">
										<div class="tooltip-block-content"><img src="{$r.image}" class="preview"></div>
									</i> 
								{/if}								
								{if $r.dataid} <i class="fa fa-share green" title="{t('关联数据')}"></i> {/if}
								{if $r.stick} <i class="fa fa-up yellow" title="{t('已置顶')}"></i> {/if}
							</div> 
						</td>
						<td>
							<div class="manage">
								{if $r.stick}
								<a href="{U('block/datalist/stick/'.$r.id)}" class="ajax-post"><i class="fa fa-down"></i> {t('取消置顶')}</a>
								{else}
								<a href="{U('block/datalist/stick/'.$r.id)}" class="ajax-post"><i class="fa fa-up"></i> {t('置顶')}</a>
								{/if}
								<s>|</s>
								<a href="{U('block/datalist/edit/'.$r.id)}" data-width="800px" data-height="400px" class="js-open"><i class="fa fa-edit"></i> {t('编辑')}</a>
								<s>|</s>
								<a href="{U('block/datalist/delete/'.$r.id)}" class="js-confirm"><i class="fa fa-delete"></i> {t('删除')}</a>
							</div>
						</td>
					</tr>					
				{/loop}				
				</tbody>
			</table>
		{else}
		<div class="nodata">
			<i class="fa fa-frown-o"></i>
			<h1>
				{t('暂时没有任何数据')}
			</h1>
		</div>
		{/if}

		{if $block['description']}
		<div class="container-fluid container-info">			
			{$block['description']}
		</div>
		{/if}
		
		<div class="container-fluid container-default">
			<table class="table table-hover hidden list" id="historylist" cellspacing="0" cellpadding="0">
				<caption>{t('历史记录')}</caption>
				<thead>
					<tr>
						<td>{t('标题')}</td>
						<td class="w300">{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2">
							<a href="javscript:void(0)" class="btn btn-default btn-icon-text" id="historyrefresh"><i class="fa fa-refresh"></i><b>{t('刷新')}</b></a>
							<div id="historypagination"></div>						
						</td>
					</tr>
				</tfoot>
			</table>

			<script id="historytemplate" type="text/x-jsrender">
			  <tr>
			    <td><a href="<[:url]>"><[:title]></a></td>
			    <td>
			    	<div class="manage">
			    		<a href="<[:manage.back]>" class="ajax-post"><i class="fa fa-back"></i> {t('重新推荐')}</a>
						<s>|</s>
						<a href="<[:manage.edit]>" data-width="800px" data-height="400px" class="js-open"><i class="fa fa-edit"></i> {t('编辑')}</a>
						<s>|</s>
						<a href="<[:manage.delete]>" class="js-confirm"><i class="fa fa-delete"></i> {t('删除')}</a>				    		
			    	</div>
			    </td>
			  </tr>
			</script>
		</div>

	</div><!-- main-body -->
	<div class="main-footer">
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->

<script type="text/javascript" src="{a('system.url')}/common/js/jquery.views.min.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.pagination.js"></script>
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

/**
 * 获取历史记录，带分页功能
 * 
 * @param  int pageindex 页面索引，从0开始，如：第一页为 0
 * @param  int pagesize  每页显示条数
 * @return null
 */
function gethistorylist(pageindex){

	//var loading = $.loading();
	var pagesize = 10;

	$.ajax({
		url: "{u('block/datalist/historydata')}",
		data:{page:pageindex + 1, pagesize:pagesize, blockid:{$block.id}},
		dataType:'json',
		success:function(result){
			
			//loading.close();

			if ( result.total == 0 ) return false; 

			$('#historylist').show();	

			$('#historylist tbody').html(function(){
				return $('#historytemplate').render(result.data);
			});

			$('#historypagination').pagination(result.total, {
				current_page: pageindex,
				items_per_page: pagesize,
				num_edge_entries: 1,
				num_display_entries: 7,
				prev_text : "{t('前页')}",
				next_text : "{t('下页')}",
				load_first_page : false,
				show_if_single_page : true,
				link_to : '?pageindex=__id__',
				callback:function(index,jq){
					//gethistorylist(index);
				}
			});
		}
	});
}

$(function(){

	var pageindex = {intval($_GET.pageindex)};

	// 默认加载
	gethistorylist(pageindex);

	// 刷新历史记录
	$('#historyrefresh').on('click',function(){
		gethistorylist(pageindex);
	});
})


</script>

{template 'footer.php'}