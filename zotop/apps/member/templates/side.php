<div class="user-info">
	<div class="user-avatar">
			<a href="javascript:;">
				<img src="{m('system.user.avatar', $_USER.id)}"/>
			</a>
	</div>
	<div class="user-main">
		<div class="username textflow" title="{$_USER.username}">{$_USER.username}</div>
		<div class="modelname textflow"><b>{m('member.model.get', $_USER.modelid, 'name')}</b></div>
		<div class="groupname textflow"><b>{m('member.group.get', $_USER.groupid, 'name')}</b></div>
	</div>
</div>

<div class="user-data">
	{t('积分')}<em>{m('system.user.get', $_USER.id, 'point')} </em>
	<s>|</s>
	{t('金钱')}<em>{m('system.user.get', $_USER.id, 'amount')} </em>
</div>

<dl class="list navlist">
	{loop zotop::filter('member.navlist',array()) $r}

	{if $r.menu and is_array($r.menu)}
	<dt>{$r.text}</dt>
	<dd>
		{loop $r.menu $m}
		<div class="item">
			<a href="{$m.href}"><i class="icon {$m.icon}"></i> {$m.text}</a>
			<span class="extra">{$m.extra}</span>
		</div>
		{/loop}
	</dd>
	{/if}

	{/loop}
	<dt class="none">{t('财务')}</dt>
	<dd class="none">
		<div class="item"><a href="{U('member/amount/pay')}">{t('在线充值')}</a></div>
		<div class="item"><a href="{U('member/amount/payment')}">{t('充值记录')}</a></div>
		<div class="item"><a href="{U('member/amount/spend')}">{t('消费记录')}</a></div>
		<div class="item"><a href="{U('member/amount/exchange')}">{t('积分兑换')}</a></div>
	</dd>
	<dt>{t('账户')}</dt>
	<dd>
		<div class="item"><a href="{U('member/mine')}"><i class="icon icon-user"></i> {t('修改我的账户资料')}</a></div>
		<div class="item"><a href="{U('member/mine/email')}"><i class="icon icon-mail"></i> {t('修改我的邮箱')}</a></div>
		<div class="item"><a href="{U('member/mine/password')}"><i class="icon icon-safe"></i> {t('修改我的密码')}</a></div>
		<div class="item"><a href="{U('member/login/loginout')}"><i class="icon icon-out"></i> {t('退出账户')}</a></div>

	</dd>
</dl>

<style type="text/css">
.row-s-m .main-inner{margin:0;margin-left:235px;border:1px solid #ECECEC;padding:20px;}
.row-s-m .side{margin:0;margin-left:-100%;width:220px;border:1px solid #ECECEC;background:#FAFAFA;}

.user-info {background-color:#fff;*zoom:1;padding:20px;}
.user-info .user-avatar {float:left;width:64px;height:64px;}
.user-info .user-avatar img {width:100%;height:100%;}
.user-info .user-main{float:left;height:60px;width:100px;padding-left:15px;line-height:25px;margin-top:-3px;}
.user-info .user-main .username {font-size:16px;}
.user-info .user-main .modelname b{font-weight:normal;}
.user-info .user-main .groupname b{font-weight:normal;}
.user-data{clear:both;display:block;padding:20px;}
.user-data em {font-style:normal;color:#ff6600;margin-left:3px;}
.user-data s{margin:0 5px;color:#ccc;}
</style>
