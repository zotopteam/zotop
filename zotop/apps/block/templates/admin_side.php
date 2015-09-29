<div class="side">
	<div class="side-header">
		{A('block.name')}
	</div><!-- side-header -->
	<div class="side-body scrollable">

		<ul class="nav nav-pills nav-stacked nav-side">
			{loop m('block.category.getall') $c}
			<li{if substr_count(ZOTOP_URI,'block/admin') and $c['id'] == $categoryid} class="active"{/if}>
				<a href="{u('block/admin/index/'.$c['id'])}">
					<i class="fa fa-folder fa-fw"></i>{$c[name]}
				</a>
			</li>
			{/loop}
			<li class="divider" role="separator"></li>
			<li{if substr_count(ZOTOP_URI,'block/category')} class="active"{/if}>
				<a href="{u('block/category')}">
					<i class="fa fa-sitemap fa-fw"></i>{t('分类管理')}
				</a>
			</li>
		</ul>

	</div>
</div>