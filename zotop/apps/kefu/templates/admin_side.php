<div class="side scrollable">
	<div class="side-header">
		{A('kefu.name')}
	</div><!-- side-header -->
	<div class="side-body">

		<ul class="nav nav-pills nav-stacked nav-side">
			<li>
				<a href="{u('kefu/admin')}"{if substr_count(ZOTOP_URI,'kefu/admin')} class="current"{/if}>
					<i class="fa fa-home"></i><span>{t('客服管理')}</span>
				</a>
			</li>
			<li>
				<a href="{u('kefu/config')}"{if substr_count(ZOTOP_URI,'kefu/config')} class="current"{/if}>
					<i class="fa fa-cog"></i><span>{t('设置')}</span>
				</a>
			</li>
		</ul>

	</div><!-- side-body -->
</div>