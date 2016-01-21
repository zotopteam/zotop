<div class="side-header">
	
	{$account.name}

	<div class="action">		
		
		<a href="{u('wechat/account')}" title="公众号管理"><i class="icon icon-config"></i></a>
	</div>

</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">
	<li>
		<a href="{u('wechat/admin')}"{if request::is('wechat/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('统计信息')}
		</a>
	</li>
	<li>
		<a href="{u('wechat/mass')}"{if request::is('wechat/mass')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('消息群发')}
		</a>
	</li>	
	<li>
		<a href="{u('wechat/autoreply')}"{if request::is('wechat/autoreply')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('自动回复')}
		</a>
	</li>	
	<li>
		<a href="{u('wechat/menu')}"{if request::is('wechat/menu')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('微信菜单')}
		</a>
	</li>
</ul>

</div><!-- side-body -->