<div class="side">
	<div class="side-header">
		{A('form.name')}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li{if request::is('form/admin')} class="active"{/if}>
				<a href="{u('form/admin')}">
					<i class="fa fa-cubes fa-fw"></i>{t('表单管理')}
				</a>
			</li>
			{loop m('form.form.cache') $r}
			<li{if request::is('form/field') and $formid==$r.id} class="active"{/if}>
				<a href="{u('form/field/index/'.$r.id)}">
					<i class="fa fa-circle-o fa-fw"></i> {$r.name}
				</a>
			</li>
			{/loop}
		</ul>
	</div><!-- side-body -->
</div>