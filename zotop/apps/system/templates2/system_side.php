<div class="side-header">
	{t('系统')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="sidenavlist">
		<li><a href="{u('system/app')}"{if substr_count(ZOTOP_URI,'system/app')} class="current"{/if}><i class="icon icon-app"></i>{t('应用管理')}</a></li>
		<li><a href="{u('system/config')}"{if substr_count(ZOTOP_URI,'system/config')} class="current"{/if}><i class="icon icon-config"></i>{t('全局设置')}</a></li>		
		<li><a href="{u('system/theme')}"{if substr_count(ZOTOP_URI,'system/theme')} class="current"{/if}><i class="icon icon-skin"></i>{t('主题和模板')}</a></li>
		<li><a href="{u('system/ipbanned')}"{if substr_count(ZOTOP_URI,'system/ipbanned')} class="current"{/if}><i class="icon icon-disabled"></i>{t('IP禁止')}</a></li>
		<li><a href="{u('system/badword')}"{if substr_count(ZOTOP_URI,'system/badword')} class="current"{/if}><i class="icon icon-warning"></i>{t('敏感词管理')}</a></li>		
		<li><a href="{u('system/administrator')}"{if substr_count(ZOTOP_URI,'system/administrator')} class="current"{/if}><i class="icon icon-users"></i>{t('管理员管理')}</a></li>
		<li><a href="{u('system/role')}"{if substr_count(ZOTOP_URI,'system/role')} class="current"{/if}><i class="icon icon-user"></i>{t('角色管理')}</a></li>
		<li><a href="{u('system/priv')}"{if substr_count(ZOTOP_URI,'system/priv')} class="current"{/if}><i class="icon icon-priv"></i>{t('权限管理')}</a></li>
		<li><a href="{u('system/log')}"{if substr_count(ZOTOP_URI,'system/log')} class="current"{/if}><i class="icon icon-flag"></i>{t('系统操作日志')}</a></li>
		<li><a href="{u('system/info')}"{if substr_count(ZOTOP_URI,'system/info')} class="current"{/if}><i class="icon icon-info"></i>{t('系统信息')}</a></li>
	</ul>
</div><!-- side-body -->