{template 'header.php'}

{template 'system/system_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('管理员管理')}</div>
		<div class="action">
			<a class="btn btn-primary" href="{u('system/administrator/add')}">
				<i class="fa fa-plus fa-fw"></i><b>{t('添加管理员')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table table-hover table-nowrap">
			<thead>
				<tr>
					<td class="text-center" width="1%">{t('状态')}</td>
					<td>{t('用户名')} ({t('真实姓名')})</td>

					<td class="w160">{t('角色')}</td>
					<td class="hidden-xs">{t('电子邮件')}</td>
					<td class="hidden-xs">{t('最后登陆')}</td>
				</tr>
			</thead>
			<tbody>
			{loop $data $r}
				<tr>
					<td class="text-center va-m">{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-error"></i>{else}<i class="fa fa-check-circle fa-2x text-success"></i>{/if}</td>
					<td>
						<div class="title"><b>{$r['username']}</b> <span class="text-mute">({$r['realname']})</span></div>
						<div class="manage">
							<a href="{u('system/administrator/edit/'.$r['id'])}">{t('编辑')}</a>
							<s>|</s>
							{if $r['id'] == 1}
							<a class="disabled" disabled>{t('禁用')}</a>
							{elseif $r['disabled']}
							<a class="js-confirm" href="{U('system/administrator/disabled/'.$r['id'].'/0')}">{t('启用')}</a>
							{else}
							<a class="js-confirm" href="{U('system/administrator/disabled/'.$r['id'].'/1')}">{t('禁用')}</a>
							{/if}
							<s>|</s>
							{if $r['id'] == 1}
							<a class="disabled">{t('删除')}</a>
							{else}
							<a class="js-confirm" href="{u('system/administrator/delete/'.$r['id'])}">{t('删除')}</a>
							{/if}
						</div>
					</td>
					<td><span title="{$roles[$r['groupid']]['description']}">{$roles[$r['groupid']]['name']}</span></td>
					<td class="hidden-xs">{$r['email']}</td>
					<td class="hidden-xs">
						<div class="js-getip">{$r['loginip']}</div>					
						{if $r['logintime']}<div class="f12 text-muted">{format::date($r['logintime'])}</div>{/if}
					</td>
				</tr>
			{/loop}
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text text-overflow">{t('管理员是管理网站的用户，您可以设置管理员隶属的角色来设定管理员拥有的权限，如果不再使用可以禁用该管理员的帐户')}</div>
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}