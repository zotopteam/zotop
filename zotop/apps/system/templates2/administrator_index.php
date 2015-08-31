{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('管理员管理')}</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text" href="{u('system/administrator/add')}">
				<i class="icon icon-add"></i><b>{t('添加管理员')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="w40 center">{t('状态')}</td>
				<td>{t('用户名')} ({t('真实姓名')})</td>
				<td class="w160">{t('角色')}</td>
				<td class="w200">{t('电子邮件')}</td>
				<td class="w120">{t('最后登陆')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $data $r}
			<tr>
				<td class="center">{if $r['disabled']}<i class="icon icon-false false"></i>{else}<i class="icon icon-true true"></i>{/if}</td>
				<td>
					<div class="title"><b>{$r['username']}</b> <span class="f12">({$r['realname']})</span></div>
					<div class="manage">
						<a href="{u('system/administrator/edit/'.$r['id'])}">{t('编辑')}</a>
						<s></s>
						{if $r['id'] == 1}
						<a class="disabled">{t('禁用')}</a>
						{elseif $r['disabled']}
						<a class="dialog-confirm" href="{U('system/administrator/disabled/'.$r['id'].'/0')}">{t('启用')}</a>
						{else}
						<a class="dialog-confirm" href="{U('system/administrator/disabled/'.$r['id'].'/1')}">{t('禁用')}</a>
						{/if}
						<s></s>
						{if $r['id'] == 1}
						<a class="disabled">{t('删除')}</a>
						{else}
						<a class="dialog-confirm" href="{u('system/administrator/delete/'.$r['id'])}">{t('删除')}</a>
						{/if}
					</div>
				</td>
				<td><span title="{$roles[$r['groupid']]['description']}">{$roles[$r['groupid']]['name']}</span></td>
				<td>{$r['email']}</td>
				<td>{$r['loginip']}{if $r['logintime']}<div class="f12 time">{format::date($r['logintime'])}</div>{/if}</td>
			</tr>
		{/loop}
		</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('管理员是管理网站的用户，您可以设置管理员隶属的角色来设定管理员拥有的权限，如果不再使用可以禁用该管理员的帐户')}</div>
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

{template 'footer.php'}