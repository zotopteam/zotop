<div class="side">
	<div class="side-header">
		{A('guestbook.name')}
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li{if request::is('guestbook/admin')} class="active"{/if}>
				<a href="{u('guestbook/admin')}"><i class="fa fa-home fa-fw"></i><span>{t('留言管理')}</span></a>
			</li>
			<li{if request::is('guestbook/config/index')} class="active"{/if}>
				<a href="{u('guestbook/config/index')}"><i class="fa fa-cog fa-fw"></i><span>{t('留言设置')}</span></a>
			</li>
			<li{if request::is('guestbook/config/mail')} class="active"{/if}>
				<a href="{u('guestbook/config/mail')}"><i class="fa fa-envelope fa-fw"></i><span>{t('邮件通知')}</span></a>
			</li>
			<li>
				<a href="{u('guestbook')}" target="_blank"><i class="fa fa-external-link fa-fw"></i><span>{t('访问留言本')}</span></a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>