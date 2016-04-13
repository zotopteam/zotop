{template 'header.php'}

<div class="container">
	<div class="panel">
		<div class="row">
			<div class="col-sm-2">
				{template 'member/side.php'}
			</div> <!-- col -->
			<div class="col-sm-10">
				
				<table class="table">
					<caption>{t('账户信息')}</caption>
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
			

			</div> <!-- col -->
		</div> <!-- row -->
	</div>
</div>
{template 'footer.php'}