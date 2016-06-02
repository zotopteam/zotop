{template 'member/header.php'}
	
	<style>
		.panel-user .panel-body{padding:20px;}
		.user-info{font-size:12px;line-height:25px;padding:15px;margin-top:-15px;}
		.user-info p{margin:0 0 5px;}
		.user-info p.username{font-size:18px;}
		.user-info a.badge{padding:7px 6px;border-radius:3px;background:#ccc;margin-right:5px;}

		.user-data{text-align:center;border-left:solid 1px #ebebeb;float: left;width:50%;}
		.user-data p{margin:20px 0;}
		.user-data b{color:#ff6600;font-size:30px;margin:0 5px;}
		.user-data i{color:#ff6600;font-style:normal;}

		.user-avatar{position: relative;}
		.edit-avatar{position: absolute;bottom:0;width:100%;background:#000;color:#fff;opacity:0.8;padding:5px;text-align:center;display: none;}
		.edit-avatar:hover{text-decoration:none;color:#f7f7f7;}
		.user-avatar:hover .edit-avatar{display: block;}

		/* 分辨率小于 767px */
		@media (max-width:767px){
			.user-data{margin-top:10px;background:#f7f7f7;padding:10px;}
			.user-amount{border:0;}
		}
	</style>
	
	<div class="panel panel-default panel-user">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6 clearfix">
					<div class="user-avatar pull-left">
						<img src="{m('system.user.avatar', $_USER.id)}" class="avatar img-responsive" />
						<a class="edit-avatar" href="{u('member/mine/avatar')}">{t('修改头像')}</a>
					</div>

					<div class="user-info pull-left">
						<p class="username text-overflow" title="{$_USER.username}">{$_USER.username}</p>
						<p class="sign text-muted text-overflow">你还没有任何</p>
						<p class="model-group text-overflow">{m('member.model.get', $_USER.modelid, 'name')} {m('member.group.get', $_USER.groupid, 'name')}</p>

						{if m('system.user.get', $_USER.id,'mobile')}
						<a class="badge bg-primary" href="{u('member/mine/mobile')}"><i class="fa fa-mobile fa-fw"></i></a>
						{else}
						<a class="badge" href="{u('member/mine/mobile')}" title="{t('尚未绑定手机')}"><i class="fa fa-mobile fa-fw"></i></a>
						{/if}

						{if m('system.user.get', $_USER.id,'email')}
						<a class="badge bg-primary" href="{u('member/mine/email')}"><i class="fa fa-envelope fa-fw"></i></a>
						{else}
						<a class="badge" href="{u('member/mine/email')}" title="{t('尚未绑定邮箱')}"><i class="fa fa-envelope fa-fw"></i></a>
						{/if}						
						
					</div>					
				</div>
				
				<div class="col-sm-6">				
					<div class="user-data user-amount">
						<div>{t('账户余额')}</div>
						<p><b>{m('system.user.get', $_USER.id, 'amount')}</b><i>{t('元')}</i></p>
						<a class="btn btn-default">{t('在线充值')}</a>
					</div>				

					<div class="user-data user-point">
						<div>{t('我的积分')}</div>
						<p><b>{m('system.user.get', $_USER.id, 'point')}</b><i>{t('分')}</i></p>
						<a class="btn btn-default">{t('积分明细')}</a>
					</div>
				</div>
			</div>
		</div>
	</div>
				
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{t('账户信息')}</div>
		</div>
		<div class="panel-body">
			<table class="table">
				<tr>
					<td width="120">{t('用户名')}</td>
					<td><b>{$user.username}</b></td>
				</tr>
				<tr>
					<td width="120">{t('昵称')}</td>
					<td>{$user.nickname}</td>
				</tr>
				<tr>
					<td width="120">{t('邮箱')}</td>
					<td>{$user.email}</td>
				</tr>
				<tr>
					<td width="120">{t('最近登录')}</td>
					<td>{format::date($user.logintime)}</td>
				</tr>
				<tr>
					<td width="120">{t('最近登录IP')}</td>
					<td>{$user.loginip}</td>
				</tr>
				<tr>
					<td width="120">{t('登录次数')}</td>
					<td>{$user.logintimes}</td>
				</tr>
				<tr>
					<td width="120">{t('积分')}</td>
					<td>{$user.point}</td>
				</tr>
				<tr>
					<td width="120">{t('金钱')}</td>
					<td>{$user.amount}</td>
				</tr>
				<tr>
					<td width="120">{t('用户类型')}</td>
					<td>{m('member.model.get', $user.modelid, 'name')}</td>
				</tr>
				<tr>
					<td width="120">{t('用户组')}</td>
					<td>{m('member.group.get', $user.groupid, 'name')}</td>
				</tr>																												
			</table>
		</div>			
	</div>


{template 'member/footer.php'}