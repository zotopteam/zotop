{template 'header.php'}

<div class="section bg-primary bg-image">
	<div class="container">
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
							{field type="text" name="username" value="$remember_username" placeholder="t('用户名/手机号/邮箱')" required="required"}
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
						<a href="{u('member/login/getpassword')}" class="pull-right">{t('忘记密码?')}</a>
						<label for="cookietime">
							<input type="checkbox" class="checkbox" id="cookietime" name="cookietime" value="50400" {if zotop::cookie('cookietime')}checked="checked"{/if}/>
							{t('两周内自动登录')}
						</label>					
					</div>

					<div class="form-group">
						{field type="submit" value="t('登 录')" data-loading-text="t('登录中，请稍后……')" class="btn-block btn-lg"}
					</div>
					

					<div class="form-group hidden">
						第三方登录
					</div>

				</div>

				<div class="panel-footer text-center">
					{t('还没有账号？')} <a href="{U('member/register')}">{t('立即注册')}</a>
				</div>
			</div>	
		{/form}
	</div>
</div>



<style type="text/css">
.panel-login{margin:80px auto;max-width:600px;box-shadow: 0 1px 2px 0 rgba(0,0,0,0.2);border:none;}
.panel-login .panel-heading{padding:10px;border:none;}
.panel-login .panel-body{padding:20px;margin:0 auto;max-width:500px;}
.panel-login .panel-footer{padding:15px;border:none;}
</style>

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