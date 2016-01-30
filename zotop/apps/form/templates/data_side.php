<div class="side">
	<div class="side-header">
		{m('form.form.get',$formid,'name')}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li{if request::is('form/data')} class="active"{/if}>
				<a href="{u('form/data/index/'.$formid)}">
					<i class="fa fa-cubes fa-fw"></i>{t('数据管理')}
				</a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>