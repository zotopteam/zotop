{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
        <li>{$title}</li>
    </ul>
</div>

<div class="row">


	{form::header()}
	<div class="form-header">{$title}</div>
	<div class="form-status">
	{if $step < 4}
		<div {if $step==1}class="current"{/if}><b>1</b> <span>{t('账号确认')}</span> <s>></s></div>
		<div {if $step==2}class="current"{/if}><b>2</b> <span>{t('安全验证')}</span> <s>></s></div>
		<div {if $step==3}class="current"{/if}><b>3</b> <span>{t('重设密码')}</span></div>
	{/if}
	</div>
	<div class="form-body clearfix">

		{if $step == 1}
		<div class="field">
					<div class="label">{form::label('请填写您的用户名或者邮箱','username')}</div>
					<div class="input">
						{form::field(array('type'=>'text','name'=>'username','required'=>'required','remote'=>U('member/login/checkuser')))}
					</div>
		</div><!-- field -->

		<div class="field">
			<div class="label"></div>
			<div class="input">
				{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}
			</div>
		</div><!-- field -->
		{/if}

		{if $step == 2}
		<div class="field">
					<div class="label">{form::label(t('系统已经向您的邮箱 %s 发送了一封验证邮件，请输入您收到的邮件验证码', $user['email']),'code')}</div>
					<div class="input">
						{form::field(array('type'=>'hidden','name'=>'id', 'value'=>$user['id'],'required'=>'required'))}
						{form::field(array('type'=>'hidden','name'=>'email', 'value'=>$user['email'],'required'=>'required'))}
						{form::field(array('type'=>'text','name'=>'code','placeholder'=>t('邮件验证码'),'required'=>'required','maxlength'=>6,'minlength'=>6))}
						&nbsp;
						<a class="btn" href="javascript:location.reload();">{t('重新发送')}</a>

					</div>
		</div><!-- field -->

		{/if}

		{if $step == 3}

		{form::field(array('type'=>'hidden','name'=>'id','value'=>$_POST['id'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'email','value'=>$_POST['email'],'required'=>'required'))}
		{form::field(array('type'=>'hidden','name'=>'code','value'=>$_POST['code'],'required'=>'required'))}

		<div class="field">
					<div class="label">{form::label(t('请输入您的新密码'),'password', true)}</div>
					<div class="input">
						{form::field(array('type'=>'password','name'=>'password','required'=>'required'))}
					</div>
		</div><!-- field -->
		<div class="field">
					<div class="label">{form::label(t('请再次输入您的新密码'),'password2', true)}</div>
					<div class="input">
						{form::field(array('type'=>'password','name'=>'password2', 'equalto'=>'#password','required'=>'required'))}
					</div>
		</div><!-- field -->
		{/if}

		{if $step ==4 }
		<div class="field">
			<h3>
				<b class="icon icon-true true"></b>
				<b>{t('您已经成功设置密码，请使用新密码登录！')}</b>
				<a href="{u('member/login')}">{t('立即登录')}</a>
			</h3>
		</div><!-- field -->
		{/if}

	</div><!-- form-body -->
	<div class="form-footer">
		{if $step < 3}
		{form::field(array('type'=>'submit','value'=>t('下一步')))}
		{/if}

		{if $step == 3}
		{form::field(array('type'=>'submit','value'=>t('重设密码')))}
		{/if}
	</div>
	{form::footer()}

</div> <!-- row -->

<style type="text/css">

.row{margin:20px 0;}

form.form{border:solid 1px #d5d5d5;background:#f3f3f3;padding:15px;border-radius:5px;}
div.form-header{font-size:24px;font-weight:bold;padding:10px 0 20px 0;}
div.form-status{clear:both;font-size:18px;line-height:48px;}
div.form-status div{float:left;}
div.form-status div s{color:#999;padding:0 8px;}
div.form-status div.current{color:#ff6600;}

div.form-body{clear:both;}
div.form-footer{padding:10px 0;clear:both;}
div.form-footer .btn{width:100px;padding:5px;}
div.form-footer button{font-size:16px;line-height:22px;}
</style>

<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	$(function(){
		$('form.form').validate();
	});
</script>
{template 'footer.php'}