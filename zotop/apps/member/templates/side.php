<div class="user-sidebar">
	<div class="user-avatar">
		<img src="{m('system.user.avatar', $_USER.id, 'big')}" class="avatar img-responsive" />
		<a href="javascript:;"></a>
	</div>

	<div class="user-info">
		<div class="username text-overflow" title="{$_USER.username}">{$_USER.username}</div>
		<div class="modelname text-overflow">{m('member.model.get', $_USER.modelid, 'name')}</div>
		<div class="groupname text-overflow">{m('member.group.get', $_USER.groupid, 'name')}</div>
	</div>

	<div class="user-data">
		{t('积分')}:<em>{m('system.user.get', $_USER.id, 'point')} </em>
		<s>|</s>
		{t('金钱')}:<em>{m('system.user.get', $_USER.id, 'amount')} </em>
	</div>

	<dl class="list-group">
		{loop zotop::filter('member.navlist',array()) $r}

		{if $r.menu and is_array($r.menu)}
		<dt class="list-group-item list-group-title">{$r.text}</dt>
		<dd class="list-group-item">
			{loop $r.menu $m}
			<dd class="list-group-item">
				<a href="{$m.href}"><i class="icon {$m.icon}"></i> {$m.text}</a>
				<span class="extra">{$m.extra}</span>
			</dd>
			{/loop}
		</dd>
		{/if}

		{/loop}
		<dt class="list-group-item list-group-title">{t('财务')}</dt>
		<dd class="list-group-item"><a href="{U('member/amount/pay')}">{t('在线充值')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/amount/payment')}">{t('充值记录')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/amount/spend')}">{t('消费记录')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/amount/exchange')}">{t('积分兑换')}</a></dd>
		<dt class="list-group-item list-group-title">{t('账户')}</dt>
		<dd class="list-group-item"><a href="{U('member/mine')}">{t('修改我的账户资料')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/mine/email')}">{t('修改我的邮箱')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/mine/password')}">{t('修改我的密码')}</a></dd>
		<dd class="list-group-item"><a href="{U('member/login/loginout')}">{t('退出账户')}</a></dd>
	</dl>
</div>

<style type="text/css">
.user-sidebar {padding:15px;background-color:#fafafa;border:1px solid #f3f3f3;border-width:0 1px;}
.user-avatar{margin-bottom:15px;}
.user-info{line-height:25px;}
.user-info .username {font-size:16px;}
.user-info .modelname b{font-weight:normal;}
.user-info .groupname b{font-weight:normal;}
.user-data{padding:15px 0;}
.user-data em {font-style:normal;color:#ff6600;margin-left:3px;}
.user-data s{margin:0 5px;color:#ccc;}
.user-sidebar .list-group{border-shadow:none;margin:0 -15px;}
.user-sidebar .list-group-item{border-width: 1px 0;border-radius: 0;background:transparent;}
</style>
