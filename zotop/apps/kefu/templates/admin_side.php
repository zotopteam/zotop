<div class="side-header">
	{A('kefu.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li>
		<a href="{u('kefu/admin')}"{if substr_count(ZOTOP_URI,'kefu/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('客服管理')}
		</a>
	</li>
	<li>
		<a href="{u('kefu/config')}"{if substr_count(ZOTOP_URI,'kefu/config')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('设置')}
		</a>
	</li>
</ul>

</div><!-- side-body -->