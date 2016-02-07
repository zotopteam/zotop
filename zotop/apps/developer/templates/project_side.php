<div class="side">
	<div class="side-header">
		<a href="{u('developer')}" title="{t('返回首页')}" style="margin-left:-10px;margin-right:10px;"><i class="fa fa-angle-left"></i></a>
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
			<li {if request::is('developer/make/controller')} class="active"{/if}>
				<a href="{u('developer/make/controller')}" >
					<i class="fa fa-database"></i>{t('创建控制器')}
				</a>
			</li>
			<li {if request::is('developer/make/model')} class="active"{/if}>
				<a href="{u('developer/make/model')}" >
					<i class="fa fa-database"></i>{t('创建模型')}
				</a>
			</li>
			<li {if request::is('developer/make/template')} class="active"{/if}>
				<a href="{u('developer/make/template')}" >
					<i class="fa fa-database"></i>{t('创建模板')}
				</a>
			</li>																				
		</ul>
	</div>
</div>