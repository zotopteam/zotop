<div class="side-header">
	{A('guestbook.name')}
</div><!-- side-header -->
<div class="side-body scrollable">
	<ul class="sidenavlist">
		<li><a href="{u('guestbook/admin')}"{if substr_count(ZOTOP_URI,'guestbook/admin')} class="current"{/if}><i class="icon icon-index"></i>{t('留言管理')}</a></li>
		<li><a href="{u('guestbook/config/index')}"{if substr_count(ZOTOP_URI,'guestbook/config/index')} class="current"{/if}><i class="icon icon-config"></i>{t('留言设置')}</a></li>
		<li><a href="{u('guestbook/config/mail')}"{if substr_count(ZOTOP_URI,'guestbook/config/mail')} class="current"{/if}><i class="icon icon-mail"></i>{t('邮件通知')}</a></li>
		<li><a href="{u('guestbook')}" target="_blank"><i class="icon icon-open"></i>{t('访问留言本')}</a></li>
	</ul>
</div><!-- side-body -->
