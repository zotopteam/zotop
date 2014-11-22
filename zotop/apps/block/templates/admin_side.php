<div class="side-header">
	{A('block.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

	<ul class="sidenavlist">
		{loop m('block.category.getall') $c}
		<li>
			<a href="{u('block/admin/index/'.$c['id'])}"{if substr_count(ZOTOP_URI,'block/admin') and $c['id'] == $categoryid} class="current"{/if}>
				<i class="icon icon-folder"></i>{$c[name]}
			</a>
		</li>
		{/loop}
		<li class="blank"></li>
		<li>
			<a href="{u('block/category')}"{if substr_count(ZOTOP_URI,'block/category')} class="current"{/if}>
				<i class="icon icon-category"></i>{t('分类管理')}
			</a>
		</li>
	</ul>

</div>