{template 'header.php'}
<div class="side main-side scrollable none">

		<dl class="list">
			<dt>{t('我的')}</dt>
			<dd>
				<table class="table">
					<tr><td colspan="2">
					<a href="{u('system/mine')}">{t('编辑我的资料')}</a>
					&nbsp;&nbsp;
					<a href="{u('system/mine/password')}">{t('修改我的密码')}</a>
					</td></tr>
					<tr><td class="w60">{t('登陆时间')}</td><td>{format::date($user['logintime'])}</td></tr>
					<tr><td class="w60">{t('登陆次数')}</td><td>{$user['logintimes']}</td></tr>
					<tr><td class="w60">{t('登陆IP')}</td><td>{$user['loginip']}</td></tr>
				</table>
			</dd>
			<dt>{t('站点')}</dt>
			<dd>
				<table class="table list">
					<tr><td class="w60">{t('名称')}</td><td><div class="textflow">{c('site.name')}</div></td></tr>
					<tr><td class="w60">{t('网址')}</td><td><div class="textflow">{c('site.url')}</div></td></tr>
					<tr><td class="w60">{t('主题')}</td><td><div class="textflow">{c('site.theme')}</div></td></tr>
				</table>
			</dd>
			<dt>{t('关于')}</dt>
			<dd>
				<table class="table">
					<tr><td class="w60">{t('版本')}</td><td>v {c('zotop.version')}</td></tr>
					<tr><td class="w60">{t('开发')}</td><td>zotop team</td></tr>
					<tr><td class="w60">{t('官网')}</td><td><a href="http://www.zotop.com">zotop.com</a></td></tr>
				</table>
			</dd>
		</dl>

</div>
<div class="main no-header">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<ul class="navlist">
			{loop $start $s}
			<li>
				<a href="{$s['href']}" class="nav clearfix">
					<img src="{$s['icon']}">
					<h2>{$s['text']}</h2>
					<p>{$s['description']}</p>
				</a>

				{if isset($s['msg'])}
				<b class="msg">{$s['msg']}</b>
				{/if}
			</li>
			{/loop}
		</ul>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="fr">{zotop::powered()}</div>
		{t('感谢您使用逐涛网站管理系统')}
	</div>
</div>
{template 'footer.php'}