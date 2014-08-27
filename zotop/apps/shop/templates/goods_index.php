{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>


<div class="main side-main">
	<div class="main-header">
		<div class="title">
			{if $keywords}
				 {t('搜索 "%s"',$keywords)}
			{elseif $category['name']}
				{$category['name']}
			{else}
			 	{t('商品管理')}
			{/if}
		</div>

		{if !$keywords}
		<ul class="navbar">
			{loop m('shop.goods.status') $s $t}
			<li{if $status == $s} class="current"{/if}>
				<a href="{u('shop/goods/index/'.$categoryid.'/'.$s)}">{$t}</a>
				{if $statuscount[$s]}<span class="f12 red">({$statuscount[$s]})</span>{/if}
			</li>
			{/loop}
		</ul>
		{/if}

		<form action="{u('shop/goods/index')}" method="post" class="searchbar">
			{if $keywords}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('关键词/商品编号')}" style="width:200px;" x-webkit-speech/>
			{else}
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('关键词/商品编号')}" x-webkit-speech/>
			{/if}
			<button type="submit"><i class="icon icon-search"></i></button>
		</form>

		<div class="action">
			{if $status!='trash'}
			<a class="btn btn-icon-text btn-highlight" href="{U('shop/goods/add/'.$categoryid)}">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			{/if}
		</div>
	</div>
	<div class="main-body scrollable">


		{if empty($data)}
			<div class="nodata">{t('没有找到任何数据')}</div>
		{else}
		{form::header()}
		<table class="table zebra list" id="datalist">
			<thead>
				<tr>
					<td class="select"><input type="checkbox" class="checkbox select-all"></td>
					<td class="w60 center">{t('权重')}</td>
					<td>{t('商品名称')}</td>
					<td class="w160">{t('商品编号')}</td>
					{if !$categoryid}
					<td class="w140">{t('商品分类')}</td>
					{/if}
					<td class="w140">{t('商品类型')}</td>
					<td class="w120">{t('发布')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
					<td class="center">
						<a class="dialog-prompt" data-value="{$r['weight']}" data-prompt="{t('请输入权重[0-100],权重越大越靠前')}" href="{u('shop/goods/set/'.$r['id'].'/weight')}" title="{t('设置权重')}">
							<span class="{if $r['weight']}red{else}gray{/if}">{$r['weight']}</span>
						</a>
					</td>
					<td>
						<div class="title textflow">{$r['name']}</div>
						<div class="manage">
							
							<a href="{u('shop/detail/'.$r['id'])}" target="_blank">{t('访问')}</a>
							<s></s>

							<a href="{u('shop/goods/edit/'.$r['id'])}">{t('编辑')}</a>
							<s></s>

							{if $r.status == 'publish'}
							<a href="{u('shop/goods/set/'.$r['id'].'/status/disabled')}" class="ajax-post">{t('下架')}</a>
							{else}
							<a href="{u('shop/goods/set/'.$r['id'].'/status/publish')}" class="ajax-post">{t('上架')}</a>
							{/if}
							<s></s>

							{if $r.status == 'trash'}
							<a href="{u('shop/goods/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
							{else}
							<a href="{u('shop/goods/set/'.$r['id'].'/status/trash')}" class="ajax-post">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td>{$r.sn}</td>
					{if !$categoryid}
					<td>{m('shop.category.get',$r.categoryid,'name')}</td>
					{/if}
					<td>{m('shop.type.get',$r.typeid,'name')}</td>
					<td>
						<div>{m('system.user.get', $r.userid, 'username')}</div>
						<div class="f12 time">{format::date($r.createtime)}</div>
					</td>
				</tr>
			{/loop}
			<tbody>
		</table>
		{form::footer()}
		{/if}
	</div>
	<div class="main-footer">
		<div class="pagination">{pagination::instance($total,$pagesize,$page)}</div>

		<input type="checkbox" class="checkbox select-all middle">

		{loop m('shop.goods.status') $s $t}
		{if $status != $s}
		<a class="btn operate" href="{u('shop/goods/operate/'.$s)}" rel="{$s}">{$t}</a>
		{/if}
		{/loop}

		<a class="btn operate" href="{u('shop/goods/operate/weight')}" rel="weight">{t('权重')}</a>

		<a class="btn operate" href="{u('shop/goods/operate/move')}" rel="move">{t('移动')}</a>

		<a class="btn operate" href="{u('shop/goods/operate/delete')}" rel="delete">{t('删除')}</a>
	</div>
</div>

<script type="text/javascript">
$(function(){
	var tablelist = $('#datalist').data('tablelist');

	//底部全选
	$('input.select-all').on('click',function(e){
		tablelist.selectAll(this.checked);
	});

	//操作
	$("a.operate").each(function(){
		$(this).on("click", function(event){ event.preventDefault();

			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
				return false;
			}

			var rel = $(this).attr('rel');
			var href = $(this).attr('href');
			var text = $(this).text();
			var data = $('form').serializeArray();

			if ( rel == 'move' ) {

				var $dialog = $.dialog({title:text, url:"{u('shop/category/select/'.$categoryid)}", width:400, height:300, ok:function(categoryid, categoryname){

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
				},cancel:function(){}},true);

			}else if( rel == 'weight' ){

				var $dialog = $.prompt('{t('请输入权重[0-100],权重越大越靠前')}', function(newvalue){

					data.push({name:'weight',value:newvalue});

					$.loading();
					$.post(href, $.param(data), function(msg){
						if( msg.state ){
							$dialog.close();
						}
						$.msg(msg);
					},'json');

					return false;

				}, '0').title(text);

			}else{
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}

			return true;
		});

	});
});

</script>

{template 'footer.php'}