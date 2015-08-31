<div class="side-header">
	{t('个人管理')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="sidenavlist">
		<li><a href="{u('system/mine')}"{if ZOTOP_URI == 'system/mine'} class="current"{/if}><i class="icon icon-user"></i>{t('编辑我的资料')}</a></li>
		<li><a href="{u('system/mine/password')}"{if ZOTOP_URI == 'system/mine/password'} class="current"{/if}><i class="icon icon-edit"></i>{t('修改我的密码')}</a></li>
		<li><a href="{u('system/mine/priv')}"{if ZOTOP_URI == 'system/mine/priv'} class="current"{/if}><i class="icon icon-category"></i>{t('查看我的权限')}</a></li>
		<li><a href="{u('system/mine/log')}"{if ZOTOP_URI == 'system/mine/log'} class="current"{/if}><i class="icon icon-flag"></i>{t('查看我的日志')}</a></li>
	</ul>
</div><!-- side-body -->