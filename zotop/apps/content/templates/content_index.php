{template 'header.php'}
{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		
		{if $keywords}
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title pull-center">{$title}</div>
		{else}		
		<div class="title">{if $title} {$title} {else} {m('content.content.status',$status)} {/if}</div>
		{/if}

		<form action="{u('content/content/search')}" class="searchbar input-group" method="get" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>

		<div class="action">
			{if $status=='publish'}
				{if count($models) < 2}
					{loop $models $i $m}
						<a class="btn btn-primary btn-icon-text" href="{u('content/content/add/'.$categoryid.'/'.$m['id'])}" title="{$m['description']}">
							<i class="fa fa-plus"></i><b>{$m['name']}</b>
						</a>
					{/loop}
				{else}
				<div class="btn-group">
					<a class="btn btn-primary btn-icon-text dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-plus"></i> <b>{t('添加')}</b> <i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						{loop $models $i $m}
							<li>
								<a href="{u('content/content/add/'.$categoryid.'/'.$m['id'])}" data-placement="right" title="{$m['description']}">
									<i class="fa {$m.icon} fa-fw"></i>{$m['name']}
								</a>
							</li>
						{/loop}
					</ul>
				</div>
				{/if}
			{/if}			

			{if $categoryid}
			<a class="btn btn-default btn-icon-text" href="{u($category['url'])}" target="_blank" title="{t('访问栏目')}"><i class="fa fa-eye"></i><b>{t('访问')}</b></a>
			{/if}

		</div>

	</div><!-- main-header -->

	<div class="main-body scrollable">

		{if empty($data)}		
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}

		{form::header()}
		<table class="table table-hover table-nowrap list sortable" id="datalist" data-categoryid="{$category.id}">
		<thead>
			<tr>
			<td class="drag"></td>
			<td class="select"><input type="checkbox" class="checkbox select-all"></td>
			<td class="text-center" width="40">{t('状态')}</td>
			<td>{t('标题')}</td>
			<td class="text-center hidden-xs hidden-sm" width="80">{t('点击')}</td>
			<td class="text-center hidden-xs hidden-sm">{t('模型')}</td>
			<td class="hidden-xs hidden-sm">{t('发布者/发布时间')}</td>
			</tr>
		</thead>
		<tbody>

		{loop $data $r}
			<tr data-id="{$r.id}" data-categoryid="{$r.categoryid}" data-listorder="{$r.listorder}" data-stick="{$r.stick}" >
				<td class="drag"></td>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td class="text-center"><i class="fa fa-{$r['status']} fa-2x  text-{$r['status']}" title="{$statuses[$r['status']]}"></i></td>
				<td >
					<p class="title" {if $r['style']}style="{$r['style']}"{/if}>					
						{$r['title']}

						{if $r.image} 
						<i class="fa fa-image text-success tooltip-block" data-placement="bottom">
							<span class="tooltip-block-content"><img src="{$r.image}" class="preview"></span>
						</i> 
						{/if}
					
						{if $r.stick}
						<i class="fa fa-arrow-up text-success" data-toggle="tooltip" title="{t('置顶')}"></i>
						{/if}

						{if $f = C('content.newflag') }
							{if (ZOTOP_TIME - $r[$f]) <= C('content.newflag_expires') * 3600}<i class="new">{t('新')}</i>{/if}
						{/if}
					</p>
					<div class="manage">
						{loop content_hook::manage_menu($r) $m}
						{if $loop.index}<s>|</s>{/if}
						<a {html::attributes($m.attrs)}><i class="{$m.icon} fa-fw"></i><span>{$m.text}</span></a>
						{/loop}
					</div>
				</td>
			
				<td class="text-center hidden-xs hidden-sm">{$r['hits']}</td>
				<td class="text-center hidden-xs hidden-sm"><div class="textflow">{m('content.model.get',$r.modelid,'name')}</div></td>
				<td class="hidden-xs hidden-sm">
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
			{pagination::instance($total,$pagesize,$page)}

			<input type="checkbox" class="checkbox select-all middle">

			<div class="btn-group">
			{loop m('content.content.status') $s $t}
				{if $status != $s}<a class="btn btn-default operate" href="{u('content/content/operate/'.$s)}" rel="{$s}">{$t}</a>{/if}
			{/loop}				
			</div>
			
			<a class="btn btn-default operate" href="{u('content/content/operate/move')}" rel="move">{t('移动')}</a>
			<a class="btn btn-default operate" href="{u('content/content/operate/delete')}" rel="delete">{t('删除')}</a>
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
					width:600,
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