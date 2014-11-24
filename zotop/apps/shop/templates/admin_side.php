<div class="side-header">
	{A('shop.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li class="none">
		<a href="{u('shop/admin')}"{if substr_count(ZOTOP_URI,'shop/admin')} class="current"{/if}>
			<i class="icon icon-home"></i>{t('商城首页')}
		</a>
	</li>
	<li>
		<a href="{u('shop/goods')}"{if substr_count(ZOTOP_URI,'shop/goods') and !$categoryid} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('商品管理')}
		</a>
	</li>
	<li>
		<table id="tree" class="table list navtree none" cellspacing="0" cellpadding="0">
			<tbody>
				{loop m('shop.category.active') $c}
				<tr data-tt-id="a{$c['id']}" {if $c['parentid'] !=0 }data-tt-parent-id="a{$c['parentid']}"{/if} {if $categoryid==$c['id']}class="current"{/if}>
					<td class="name">
						<a data-href="{u('shop/goods/index/'.$c['id'])}"><i class="icon {if $c['childid']}icon-folder{else}icon-item{/if}"></i>{$c['name']}</a>
					</td>
				</tr>
				{/loop}
			</tbody>
		</table>
	</li>
	<li>
		<a href="{u('shop/category')}"{if substr_count(ZOTOP_URI,'shop/category')} class="current"{/if}>
			<i class="icon icon-category"></i>{t('商品分类')}
		</a>
	</li>

	<li>
		<a href="{u('shop/type')}"{if substr_count(ZOTOP_URI,'shop/type')} class="current"{/if}>
			<i class="icon icon-flag"></i>{t('商品类型')}
		</a>
	</li>

	{if c('shop.spec')}
	<li>
		<a href="{u('shop/spec')}"{if substr_count(ZOTOP_URI,'shop/spec')} class="current"{/if}>
			<i class="icon icon-flag"></i>{t('商品规格')}
		</a>
	</li>
	{/if}
	
	{if c('shop.brand')}
	<li>
		<a href="{u('shop/brand')}"{if substr_count(ZOTOP_URI,'shop/brand')} class="current"{/if}>
			<i class="icon icon-flag"></i>{t('商品品牌')}
		</a>
	</li>
	{/if}

	<li>
		<a href="{u('shop/config')}"{if substr_count(ZOTOP_URI,'shop/config')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('设置')}
		</a>
	</li>
</ul>

</div><!-- side-body -->


<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/jquery.treetable.css"/>
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.treetable.js"></script>
<script type="text/javascript">
$(function(){
	$("#tree").treetable({
		column : 0,
		indent : 18,
		expandable : true,
		persist: true,
		initialState : 'collapsed', //"expanded" or "collapsed".
		clickableNodeNames : true,
		stringExpand: "{t('展开')}",
		stringCollapse: "{t('关闭')}"
	}).removeClass('none');

	{if $categoryid}
		$("#tree").treetable("reveal", "a{$categoryid}");
	{/if}


	$("#tree").find('a[data-href]').click(function(){
		location.href = $(this).attr('data-href');
	});
})
</script>