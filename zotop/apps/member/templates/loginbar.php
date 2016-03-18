<style type="text/css">
.loginbar{position:absolute;top:0px;right:0px;font-size:14px;color:#666;}
.loginbar b{font-weight:normal;margin:0 5px;}
</style>
<ul>
{if $_USER.username}	
	<li>
		<b>{$_USER.username}</b>
		<a class="loginout" href="{U('member/login/loginout')}">{t('退出')}</a>
	</li>
	<li>
		<s></s>
		<a href="{U('member/index')}">{t('会员中心')}</a>
	</li>
{else}
	<li>
		<a class="login" href="{U('member/login')}"><i class="icon icon-user"></i>{t('登录')}</a>
	</li>
	<li>
		<s></s>
		<a class="register" href="{U('member/register')}">{t('免费注册')}</a>
	</li>
{/if}

{loop zotop::filter('member.loginbar',array()) $r}
	<li><s></s><a href="{$r.url}">{$r.text}</a></li>
{/loop}

</ul>