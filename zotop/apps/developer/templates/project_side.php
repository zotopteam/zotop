<div class="side-header">
	{$app.name}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

	<ul class="sidenavlist">
		<li>
			<a href="{u('developer')}">
				<i class="icon icon-back"></i>{t('返回首页')}
			</a>
		</li>
		<li class="blank"></li>
		<li>
			<a href="{u('developer/project/index')}" {if substr_count(ZOTOP_URI,'developer/project/index')} class="current"{/if}>
				<i class="icon icon-app"></i>{t('基本信息')}
			</a>
		</li>
		<li>
			<a href="{u('developer/project/config')}" {if substr_count(ZOTOP_URI,'developer/project/config')} class="current"{/if}>
				<i class="icon icon-config"></i>{t('配置项管理')}
			</a>
		</li>
		<li>
			<a href="{u('developer/project/table')}" {if substr_count(ZOTOP_URI,'developer/project/table') or substr_count(ZOTOP_URI,'developer/schema')} class="current"{/if}>
				<i class="icon icon-database"></i>{t('数据表管理')}
			</a>
		</li>											
	</ul>
</div>