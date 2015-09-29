<div class="side scrollable">
	<div class="side-header">
		{A('kefu.name')}
	</div><!-- side-header -->
	<div class="side-body">

		<ul class="nav nav-pills nav-stacked nav-side">
			<li{if substr_count(ZOTOP_URI,'kefu/admin')} class="active"{/if}>
				<a href="{u('kefu/admin')}">
					<i class="fa fa-home"></i><span>{t('客服管理')}</span>
				</a>
			</li>
			<li{if substr_count(ZOTOP_URI,'kefu/config')} class="active"{/if}>
				<a href="{u('kefu/config')}">
					<i class="fa fa-cog"></i><span>{t('设置')}</span>
				</a>
			</li>
		</ul>

	</div><!-- side-body -->
</div>