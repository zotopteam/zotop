<ul class="nav navbar-nav navbar-right">
{if $_USER.username}	
	<li>
		<b>{$_USER.username}</b>
		<a class="loginout" href="{U('member/login/loginout')}">{t('退出')}</a>
	</li>
	<li><a href="{U('member/index')}">{t('会员中心')}</a></li>
{else}
	<li><a class="login" href="{U('member/login')}"><i class="icon icon-user"></i>{t('登录')}</a></li>
	<li><a class="register" href="{U('member/register')}">{t('注册')}</a></li>
{/if}

{loop zotop::filter('member.loginbar',array()) $r}
	<li><s></s><a href="{$r.url}">{$r.text}</a></li>
{/loop}
</ul>