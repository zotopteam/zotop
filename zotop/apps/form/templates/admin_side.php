<div class="side">
	<div class="side-header">
		{A('form.name')}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li>
				<a href="{u('form/admin')}"{if request::is('form/admin')} class="current"{/if}>
					<i class="fa fa-cubes fa-fw"></i>{t('表单管理')}
				</a>
			</li>
			{loop m('form.form.cache') $r}
			<li>
				<a href="{u('form/data/index/'.$r.id)}"{if !request::is('form/admin') and $formid==$r.id} class="current"{/if}>
					<i class="fa fa-item fa-fw"></i> {$r.name}
				</a>
			</li>
			{/loop}
		</ul>
	</div><!-- side-body -->
</div>