<div class="side">
	<div class="side-header">
		<a href="{u('developer')}" title="{t('返回首页')}"><i class="fa fa-angle-left"></i></a>
		{$app.name}
	</div><!-- side-header -->
	<div class="side-body no-footer scrollable">

		<ul class="nav nav-pills nav-stacked nav-side">
			<li {if request::is('developer/project/index')} class="active"{/if}>
				<a href="{u('developer/project/index')}">
					<i class="fa fa-info-circle"></i>{t('基本信息')}
				</a>
			</li>
			<li {if request::is('developer/project/config')} class="active"{/if}>
				<a href="{u('developer/project/config')}">
					<i class="fa fa-cog"></i>{t('配置项管理')}
				</a>
			</li>
			<li {if request::is('developer/project/table','developer/schema')} class="active"{/if}>
				<a href="{u('developer/project/table')}" >
					<i class="fa fa-database"></i>{t('数据表管理')}
				</a>
			</li>											
		</ul>
	</div>
</div>