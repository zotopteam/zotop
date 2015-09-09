<div class="side scrollable">
	<div class="side-header">
		{t('个人管理')}
	</div><!-- side-header -->
	<div class="side-body">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li{if ZOTOP_URI == 'system/mine'} class="active"{/if}>
				<a href="{u('system/mine')}"><i class="fa fa-user"></i><span>{t('编辑我的资料')}</span></a>
			</li>
			<li{if ZOTOP_URI == 'system/mine/password'} class="active"{/if}>
				<a href="{u('system/mine/password')}"><i class="fa fa-edit"></i><span>{t('修改我的密码')}</span></a>
			</li>
			<li{if ZOTOP_URI == 'system/mine/priv'} class="active"{/if}>
				<a href="{u('system/mine/priv')}"><i class="fa fa-sitemap"></i><span>{t('查看我的权限')}</span></a>
			</li>
			<li{if ZOTOP_URI == 'system/mine/log'} class="active"{/if}>
				<a href="{u('system/mine/log')}"><i class="fa fa-flag"></i><span>{t('查看我的日志')}</span></a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>