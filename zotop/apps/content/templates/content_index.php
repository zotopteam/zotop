{template 'header.php'}
<div class="side">
{template 'content/admin_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">

		<div class="title">
		{if $title}
			{$title}
		{else}
			{t('内容管理')}
		{/if}
		</div>

		
		<ul class="navbar">
			{loop m('content.content.status') $s $t}
			<li{if $status == $s} class="current"{/if}>
				<a href="{u('content/content/index/'.$categoryid.'/'.$s)}">{$t}
				{if $statuscount=m('content.content.statuscount', $category['childids'], $s)}<i class="msg">[{$statuscount}]</i>{/if}
				</a>
			</li>
			{/loop}
		</ul>
		

		<form action="{u('content/content/search')}" method="post" class="searchbar">
			{if $keywords}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" style="width:200px;" x-webkit-speech/>
			{else}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" x-webkit-speech/>
			{/if}
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">

			{if $postmodels}
				{if count($postmodels) < 2}
					{loop $postmodels $i $m}
						<a class="btn btn-highlight btn-icon-text" href="{u('content/content/add/'.$categoryid.'/'.$m['id'])}" title="{$m['description']}">
							<i class="icon icon-add"></i><b>{$m['name']}</b>
						</a>
					{/loop}
				{else}
				<div class="menu btn-menu">
					<a class="btn btn-highlight btn-icon-text" href="javascript:void(0);"><i class="icon icon-add"></i> <b>{t('添加')}</b> <i class="icon icon-angle-down"></i></a>
					<div class="dropmenu">
						<div class="dropmenulist">
							{loop $postmodels $i $m}
								<a href="{u('content/content/add/'.$categoryid.'/'.$m['id'])}" data-placement="right" title="{$m['description']}"><i class="icon icon-item icon-{$m['id']}"></i>{$m['name']}</a>
							{/loop}
						</div>
					</div>
				</div>
				{/if}
			{/if}

			{if $categoryid}
			<a class="btn btn-icon" href="{u($category['url'])}" target="_blank" title="{t('访问栏目')}">
				<i class="icon icon-open"></i>
			</a>
			{/if}
		</div>

	</div><!-- main-header -->

	<div class="main-body scrollable">

		{if empty($data)}		
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}

		{form::header()}
		<table class="table list sortable" id="datalist" data-categoryid="{$category.id}">
		<thead>
			<tr>
			{if $categoryid}
			<td class="drag"></td>
			{/if}
			<td class="select"><input type="checkbox" class="checkbox select-all"></td>
			<td class="w40 center">{t('状态')}</td>
			<td>{t('标题')}</td>
			<td class="w80 center">{t('点击')}</td>
			<td class="w80">{t('模型')}</td>
			<td class="w100">{t('栏目')}</td>
			<td class="w120">{t('发布者/发布时间')}</td>
			</tr>
		</thead>
		<tbody>

		{loop $data $r}
			<tr data-id="{$r.id}" data-categoryid="{$r.categoryid}" data-listorder="{$r.listorder}" data-stick="{$r.stick}" >
				{if $categoryid}
				<td class="drag"></td>
				{/if}
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="center"><i class="icon icon-{$r['status']} {$r['status']}" title="{$statuses[$r['status']]}"></i></td>
				<td>
					<div class="title textflow" {if $r['style']}style="{$r['style']}"{/if}>					
					{$r['title']}

					{if $r.image} 
						<i class="icon icon-image text-success tooltip-block" data-placement="bottom">
							<div class="tooltip-block-content"><img src="{$r.image}" class="preview"></div>
						</i> 
					{/if}
					
					{if $r.stick}<i class="icon icon-up yellow" title="{t('置顶')}"></i>{/if}
					</div>
					<div class="manage">
						<a href="{$r['url']}" target="_blank">{t('访问')}</a>
						<s></s>
						<a href="{u('content/content/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>

						{if $r.stick}
						<a href="{u('content/content/stick/'.$r['id'].'/0')}" class="ajax-post">{t('取消置顶')}</a>
						{else}
						<a href="{u('content/content/stick/'.$r['id'].'/1')}" class="ajax-post">{t('置顶')}</a>
						{/if}
						<s></s>

						{loop zotop::filter('content.manage',array(),$r) $m}
						<a href="{$m['href']}" {$m['attr']}>{$m['text']}</a>
						<s></s>
						{/loop}

						<a class="dialog-confirm" href="{u('content/content/delete/'.$r['id'])}">{t('删除')}</a>
					</div>
				</td>
			
				<td class="center">{$r['hits']}</td>
				<td><div class="textflow">{m('content.model.get',$r.modelid,'name')}</div></td>
				<td><div class="textflow">{m('content.category.get',$r.categoryid,'name')}</div></td>
				<td>
					<div class="userinfo" role="{$r.userid}">{m('system.user.get', $r.userid, 'username')}</div>
					<div class="f12 time">{format::date($r['createtime'])}</div>
				</td>
			</tr>
		{/loop}
		
		</tbody>
		</table>
		{form::footer()}

		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		{if $data}
			<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

			<input type="checkbox" class="checkbox select-all middle">

			{loop m('content.content.status') $s $t}
				{if $status != $s}
				<a class="btn operate" href="{u('content/content/operate/'.$s)}" rel="{$s}">{$t}</a>
				{/if}
			{/loop}

			<a class="btn operate" href="{u('content/content/operate/move')}" rel="move">{t('移动')}</a>
			<a class="btn operate" href="{u('content/content/operate/delete')}" rel="delete">{t('删除')}</a>
		{/if}

	</div><!-- main-footer -->

</div><!-- main -->

<script type="text/javascript">

$(function(){

	var tablelist = $('#datalist').data('tablelist');

	//底部全选
	$('input.select-all').on('click',function(e){
		tablelist.selectAll(this.checked);
	});

	//操作
	$("a.operate").each(function(){

		$(this).on("click", function(event){

			event.preventDefault();

			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
				return false;
			}

			var rel = $(this).attr('rel');
			var href = $(this).attr('href');
			var text = $(this).text();
			var data = $('form').serializeArray();

			if ( rel == 'move' ) {

				var $dialog = $.dialog({
					title:text,
					url:"{u('content/category/select/'.$categoryid)}",
					width:400,
					height:300,
					ok:function(categoryid){
						if ( categoryid ){
							data.push({name:'categoryid',value:categoryid});
							$.loading();
							$.post(href,$.param(data),function(msg){
								if( msg.state ){
									$dialog.close();
								}
								$.msg(msg);
							},'json');
						}
						return false;
					},
					cancel:function(){}
				},true);

			} else {
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}

			return true;
		});

	});
});

// 排序
$(function(){

	// 拖动停止更新当前的排序及当前数据之前的数据
	var dragstop = function(evt,ui,tr){
		
		var oldindex = tr.data('originalIndex');
		var newindex = tr.prop('rowIndex');
		
		if(oldindex == newindex){return;}

		var id = tr.data('id');
		var prev = ui.item.siblings('tr').eq(newindex-2); // 排到这一行之后
		var next = ui.item.siblings('tr').eq(newindex-1); // 排到这一行之前

		var neworder = ( newindex==1 || prev.data('listorder') < next.data('listorder') ) ? next.data('listorder') + 1 : prev.data('listorder');
		var newstick = ( newindex < oldindex ) ? next.data('stick') : prev.data('stick');

		//zotop.debug(oldindex+'---'+newindex+'--'+ neworder +'--'+ newstick);

		$.loading();
		$.post('{u('content/content/listorder')}',{categoryid:tr.closest('table').data('categoryid'),id:tr.data('id'),listorder:neworder,stick:newstick},function(data){
			$.msg(data);
		},'json');		
	};	

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
		start:function (event,ui) {
			ui.item.data('originalIndex', ui.item.prop('rowIndex'));
		},		
		stop:function(event,ui){
			dragstop.apply(this, Array.prototype.slice.call(arguments).concat(ui.item));
		}
	});
});
</script>
{template 'footer.php'}