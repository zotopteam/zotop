<div class="side-header">
	{A('member.name')}
</div><!-- side-header -->
<div class="side-body no-footer scrollable">

<ul class="sidenavlist">

	<li>
		<a href="{u('member/admin')}"{if request::is('member/admin')} class="current"{/if}>
			<i class="icon icon-admin"></i>{t('会员管理')}
		</a>
	</li>

	<li>
		<a href="{u('member/model')}"{if request::is('member/model')} class="current"{/if}>
			<i class="icon icon-model"></i>{t('会员模型')}
		</a>
	</li>

	<li>
		<a href="{u('member/group')}"{if request::is('member/group')} class="current"{/if}>
			<i class="icon icon-category"></i>{t('会员组')}
		</a>
	</li>

	<li>
		<a href="{u('member/field')}"{if request::is('member/field')} class="current"{/if}>
			<i class="icon icon-flag"></i>{t('字段管理')}
		</a>
	</li>

	<li>
		<a href="{u('member/config')}"{if request::is('member/config')} class="current"{/if}>
			<i class="icon icon-config"></i>{t('设置')}
		</a>
	</li>
</ul>

</div><!-- side-body -->