{template 'member/header.php'}
	
	<style>
		.panel-user .panel-body{padding:20px;}
		.user-info{line-height:25px;padding:15px;margin-top:-15px;}
		.user-info .username {font-size:16px;}
		.user-data{text-align:center;border-left:solid 1px #ebebeb;}
		.user-data p{margin:19px 0;}
		.user-data b{color:#ff6600;font-size:30px;margin:0 5px;}
		.user-data i{color:#ff6600;font-style:normal;}
	</style>
	
	<div class="panel panel-default panel-user">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<div class="user-avatar pull-left">
						<img src="{m('system.user.avatar', $_USER.id)}" class="avatar img-responsive" />
						<a href="javascript:;"></a>
					</div>

					<div class="user-info pull-left">
						<p class="username text-overflow" title="{$_USER.username}">{$_USER.username}</p>
						<p class="model-group text-overflow">{m('member.model.get', $_USER.modelid, 'name')} {m('member.group.get', $_USER.groupid, 'name')}</p>
					</div>					
				</div>

				<div class="col-sm-3 user-data">
					<div>{t('账户余额')}</div>
					<p><b>{m('system.user.get', $_USER.id, 'amount')}</b><i>{t('元')}</i></p>
					<a class="btn btn-default">{t('在线充值')}</a>
				</div>				

				<div class="col-sm-3 user-data">
					<div>{t('我的积分')}</div>
					<p><b>{m('system.user.get', $_USER.id, 'point')}</b><i>{t('分')}</i></p>
					<a class="btn btn-default">{t('积分明细')}</a>
				</div>

			</div>
		</div>
	</div>
				
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{t('账户信息')}</div>
		</div>
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


{template 'member/footer.php'}