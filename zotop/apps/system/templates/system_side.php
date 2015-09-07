<div class="side-header">
	{t('系统')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="nav nav-pills nav-stacked nav-side">
		<li{if substr_count(ZOTOP_URI,'system/app')} class="active"{/if}><a href="{u('system/app')}"><i class="fa fa-app fa-fw"></i>{t('应用管理')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/config')} class="active"{/if}><a href="{u('system/config')}"><i class="fa fa-con fa-fw"></i>{t('全局设置')}</a></li>		
		<li{if substr_count(ZOTOP_URI,'system/theme')} class="active"{/if}><a href="{u('system/theme')}"><i class="fa fa-magic fa-fw"></i>{t('主题和模板')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/ipbanned')} class="active"{/if}><a href="{u('system/ipbanned')}"><i class="fa fa-disabled fa-fw"></i>{t('IP禁止')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/badword')} class="active"{/if}><a href="{u('system/badword')}"><i class="fa fa-warning fa-fw"></i>{t('敏感词管理')}</a></li>		
		<li{if substr_count(ZOTOP_URI,'system/administrator')} class="active"{/if}><a href="{u('system/administrator')}"><i class="fa fa-users fa-fw"></i>{t('管理员管理')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/role')} class="active"{/if}><a href="{u('system/role')}"><i class="fa fa-user fa-fw"></i>{t('角色管理')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/priv')} class="active"{/if}><a href="{u('system/priv')}"><i class="fa fa-sitemap fa-fw"></i>{t('权限管理')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/log')} class="active"{/if}><a href="{u('system/log')}"><i class="fa fa-flag fa-fw"></i>{t('系统操作日志')}</a></li>
		<li{if substr_count(ZOTOP_URI,'system/system/check')} class="active"{/if}><a href="{u('system/system/check')}"><i class="fa fa-info fa-fw"></i>{t('系统信息')}</a></li>
	</ul>
</div><!-- side-body -->