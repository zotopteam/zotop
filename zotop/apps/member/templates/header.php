{template 'header.php'}
<style type="text/css">
.user-sidebar{background-color:#fafafa;border:1px solid #f3f3f3;border-radius:4px;}
.user-avatar{padding:15px;}
.user-info{line-height:25px;padding:15px;margin-top:-15px;}
.user-info .username {font-size:16px;}
.user-data{padding:15px 0;text-align:center;margin-top:-15px;margin-bottom:0;}
.user-data li{float: left;width:50%;list-style:none;line-height:25px;}
.user-data li:first-child{border-right:solid 1px #ebebeb;}
.user-data em {font-style:normal;color:#ff6600;display: block;font-size:18px;}
.user-sidebar .list-group{border-shadow:none;}
.user-sidebar .list-group-item{border-width: 1px 0;border-radius: 0;background:transparent;}
.user-sidebar .list-group-item:last-child{border:0;}
</style>

<section class="section section-usercenter">	
	<div class="container">
	
		<div class="row">
			<div class="col-sm-2">
				<div class="user-sidebar">
					<div class="user-avatar">
						<img src="{m('system.user.avatar', $_USER.id, 'big')}" class="avatar img-responsive" />
						<a href="javascript:;"></a>
					</div>

					<div class="user-info">
						<div class="username text-overflow" title="{$_USER.username}">{$_USER.username}</div>
						<div class="model-group text-overflow">{m('member.model.get', $_USER.modelid, 'name')} {m('member.group.get', $_USER.groupid, 'name')}</div>
					</div>

					<ul class="user-data clearfix">
						<li><em>{m('system.user.get', $_USER.id, 'point')} </em> {t('积分')}</li>
						<li><em>{m('system.user.get', $_USER.id, 'amount')} </em> {t('金钱')}</li>						
					</ul>

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
			</div> <!-- col -->
			<div class="col-sm-10">


