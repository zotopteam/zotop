{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
        <li>{$title}</li>
    </ul>
</div>

<div class="row row-s-m">
<div class="main">
<div class="main-inner">

	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<tr>
				<td class="label">{t('用户名')}</td>
				<td class="input"><b>{$user.username}</b></td>
			</tr>
			<tr>
				<td class="label">{t('昵称')}</td>
				<td class="input">{$user.nickname}</td>
			</tr>
			<tr>
				<td class="label">{t('邮箱')}</td>
				<td class="input">{$user.email}</td>
			</tr>
			<tr>
				<td class="label">{t('最近登录')}</td>
				<td class="input">{format::date($user.logintime)}</td>
			</tr>
			<tr>
				<td class="label">{t('最近登录IP')}</td>
				<td class="input">{$user.loginip}</td>
			</tr>
			<tr>
				<td class="label">{t('登录次数')}</td>
				<td class="input">{$user.logintimes}</td>
			</tr>
			<tr>
				<td class="label">{t('积分')}</td>
				<td class="input">{$user.point}</td>
			</tr>
			<tr>
				<td class="label">{t('金钱')}</td>
				<td class="input">{$user.amount}</td>
			</tr>
			<tr>
				<td class="label">{t('用户类型')}</td>
				<td class="input">{m('member.model.get', $user.modelid, 'name')}</td>
			</tr>
			<tr>
				<td class="label">{t('用户组')}</td>
				<td class="input">{m('member.group.get', $user.groupid, 'name')}</td>
			</tr>																												
		</table>
	

	</div><!-- main-body -->
	<div class="main-footer">

	</div><!-- main-footer -->

</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	{template 'member/side.php'}
</div> <!-- side -->
</div> <!-- row -->

{template 'footer.php'}