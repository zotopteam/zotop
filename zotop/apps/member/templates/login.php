{template 'header.php'}

<div class="container">

	<div class="row">
		<div class="col-sm-6 col-md-offset-3">
			{form class="login"}
				<div class="panel panel-login">
					<div class="panel-heading text-center">
						<h2 class="text-overflow">{$title}</h2>
					</div>
					<div class="panel-body">

						<div class="form-status">{t('请输入您的账户和密码')}</div>

						<div class="form-group">
							<div class="input-group input-group-merge">
								<label for="username" class="input-group-addon"><i class="fa fa-user fa-fw text-primary"></i></label>
								{field type="text" name="username" value="$remember_username" placeholder="t('用户名/手机号/密码')" required="required"}
							</div>
						</div>

						<div class="form-group">
							<div class="input-group input-group-merge">
								<label for="password" class="input-group-addon"><i class="fa fa-lock fa-fw text-primary"></i></label>
								{field type="password" name="password" placeholder="t('密码')" required="required"}
							</div>
						</div>

						{if c('member.login_captcha')}
						<div class="form-group">
							<div class="input-group input-group-merge">
								<label for="captcha" class="input-group-addon"><i class="fa fa-key fa-fw text-primary"></i></label>
								{field type="captcha" name="captcha" placeholder="t('验证码')" required="required"}
							</div>
						</div>
						{/if}
						
						<div class="form-group">
							<span>
								<input type="checkbox" class="checkbox" name="cookietime" value="50400" {if zotop::cookie('cookietime')}checked="checked"{/if}/>
								<label for="cookietime">{t('两周内自动登录')}</label>
							</span>							
							<a href="{u('member/login/getpassword')}" class="pull-right">{t('忘记密码?')}</a>
						</div>

						<div class="form-group">
							{field type="submit" value="t('登 录')" data-loading-text="t('登录中，请稍后……')" class="btn-block"}
						</div>

					</div>
				</div>	
			{/form}
		</div>
	</div>
</div>

<div class="row hidden">

<div class="side">

	{if !empty($models)}

	<div class="register">
	{t('还没有账号？')} <a href="{U('member/register')}">{t('立即注册')}</a>
	</div>

	<ul class="image-text member_model">
	{loop $models $r}
		<li>
			<div class="image">
				<div class="member_model {$r.id}"><s class="fa fa-user"></s></div>
			</div>
			<div class="text">
				<div class="title"><a href="{U('member/register/'.$r.id)}">{$r.name}</a></div>
				<div class="summary">{$r.description}</div>
			</div>
		</li>
	{/loop}
	</ul>
	{/if}
</div> <!-- side -->
</div> <!-- row -->



<style type="text/css">
.panel-login{margin:80px auto;}
</style>

<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			rules: {
				username: 'required',
				password: 'required'
			},
			messages: {
				username: "{t('请输入您的用户名')}",
				password: "{t('请输入您的密码')}",
				captcha: {required : "{t('请输入验证码')}"}
			},
			showErrors:function(errorMap,errorList){
				if (errorList[0]) $('.form-status').html('<span class="error">'+ errorList[0].message +'</span>');
			},
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').disable(true);
				$(form).find('.form-status').html('{t('正在登录中, 请稍后……')}');
				$.post(action, data, function(msg){
					if( msg ) $(form).find('.form-status').html('<span class="'+msg.state+'">'+ msg.content +'</span>');
					if( msg.url ){
						location.href = msg.url;
						return true;
					}
					$(form).find('.submit').disable(false);
					return false;
				},'json');
			}
		});
	});
</script>
{template 'footer.php'}