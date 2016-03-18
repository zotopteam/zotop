{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{u('member/index')}">{t('会员中心')}</a></li>
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
		<h2 class="success">{t('验证邮件已经发送到您的邮箱 %s', $data.email)}</h2>

		<div>{t('请登录您的邮箱并查收')}</div>

		<div>{t('如果没有收到邮件，您可以 <a href="%s">更换一个邮箱</a> 或者 <a href="%s">重新发送激活邮件</a>', U('member/mine/email'), U('member/mine/validemail'))}</div>

	</div><!-- main-body -->
	<div class="main-footer">
		<a class="btn btn-highlight" href="{u('member/index')}">{t('已经验证')}</a>
	</div><!-- main-footer -->



</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	{template 'member/side.php'}
</div> <!-- side -->
</div> <!-- row -->

{template 'footer.php'}