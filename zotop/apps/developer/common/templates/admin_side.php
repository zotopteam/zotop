<div class="side-header">
	{A('[id].name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li>
		<a href="{u('[id]/admin')}"{if request::is('[id]/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('[name]管理')}
		</a>
	</li>
	<li>
		<a href="{u('[id]/config')}"{if request::is('[id]/config')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('设置')}
		</a>
	</li>
</ul>

</div><!-- side-body -->