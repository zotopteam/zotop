<div class="side-header">
	{A('form.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li>
		<a href="{u('form/admin')}"{if request::is('form/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('表单管理')}
		</a>
	</li>
	{loop m('form.form.cache') $r}
	<li>
		<a href="{u('form/data/index/'.$r.id)}"{if !request::is('form/admin') and $formid==$r.id} class="current"{/if}>
			<i class="icon icon-item"></i> {$r.name}
		</a>
	</li>
	{/loop}
</ul>

</div><!-- side-body -->