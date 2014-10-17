<div class="side-header">
	{A('form.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li>
		<a href="{u('form/admin')}"{if substr_count(ZOTOP_URI,'form/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('表单管理')}
		</a>
	</li>
	<li>
		<a href="{u('form/config')}"{if substr_count(ZOTOP_URI,'form/config')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('设置')}
		</a>
	</li>
</ul>

</div><!-- side-body -->