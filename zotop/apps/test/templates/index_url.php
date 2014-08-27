{template 'header.php'}
<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main no-footer">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
		<dl class="list" style="padding:10px;">
			<dt>
				pathinfo模式： {if c('system.url_pathinfo')}开启{else}未开启{/if}
				&nbsp;&nbsp;
				URL rewrite： {if c('system.url_rewrite')}开启{else}未开启{/if}
				&nbsp;&nbsp;
				URL 伪静态： {if c('system.url_suffix')}开启{else}未开启{/if} {c('system.url_suffix')}
			</dt>
			<dd>
				<b>u()：</b>
				{u()}
			</dd>
			<dd>
				<b>u('system/login')：</b>
				{u('system/login')}
			</dd>
			<dd>
				<b>u('system/login','a=1&c=2#tttt')：</b>
				{u('system/login','a=1&c=2#ttt')}
			</dd>
			<dd>
				<b>u('system/login',array('a'=>1,'b'=>2,'#'=>'ttt'))：</b>
				{u('system/login',array('a'=>1,'b'=>2,'#'=>'ttt'))}
			</dd>
			<dd>
				<b>u('system/login?a=1&c=2#ttt')：</b>
				{u('system/login?a=1&c=2#ttt')}
			</dd>
			<dd>
				<b>u('system/login?a=1&c=2#ttt',array('a'=>22,'b'=>33,'#'=>'444'))：</b>
				{u('system/login?a=1&c=2#ttt',array('a'=>22,'b'=>33,'#'=>'444'))}
			</dd>
			<dd>
				<b>u('system/login',true)：</b>
				{u('system/login','')}
			</dd>
			<dd>
				<b>u('system/login',array(),true) ：</b>
				{u('system/login',array(),true)}
			</dd>
			<dd>
				<b>u('system/login',null,'http://www.zotop.com')：</b>
				{u('system/login',null,'http://www.zotop.com')}
			</dd>
			<dt>
				当前URL信息
			</dt>
			<dd>
				<b>request::url()：</b>	{request::url()}
			</dd>
			<dd>
				<b>request::host()：</b>	{request::host()}
			</dd>
			<dd>
				<b>request::basepath()：</b>	{request::basepath()}
			</dd>
			<dd>
				<b>request::scriptname()：</b>	{request::scriptname()}
			</dd>
			<dd>
				<b>request::referer()：</b>	{request::referer()}
			</dd>
			<dd>
				<b>request::protocol()：</b>	{request::protocol()}
			</dd>
			<dd>
				<b>request::port()：</b>	{request::port()}
			</dd>
			<dd>
				<b>request::basepath()：</b>	{request::basepath()}
			</dd>
			<dd>
				<b>request::ip()：</b>	{request::ip()}
			</dd>
		<dd>

		</dl>
	</div><!-- main-body -->

</div><!-- main -->
{template 'footer.php'}