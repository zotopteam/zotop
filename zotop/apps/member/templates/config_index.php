{template 'header.php'}
{form::header(u('member/config/save'))}
<div class="side">
{template 'member/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('会员注册')}</caption>
				<tbody>

				<tr>
					<td class="label">{form::label(t('禁用用户名'),'register_point',false)}</td>
					<td class="input">
						{form::field(array('type'=>'textarea','name'=>'register_banned','value'=>c('member.register_banned')))}
						{form::tips(t('禁止使用的用户名和昵称，每行一个，可以使用通配符*和?'))}
					</td>
				</tr>

				<tr>
					<td class="label">{form::label(t('邮件验证'),'register_validmail',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'register_validmail','value'=>c('member.register_validmail')))}

						{form::tips(t('系统设置中的“邮件发送”发送功能设置正确才能正常使用该功能'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('验证邮件'),'register_validmail_content',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'register_validmail_title','value'=>c('member.register_validmail_title')))}
						<div class="blank"></div>
						{form::field(array('type'=>'editor','name'=>'register_validmail_content','value'=>c('member.register_validmail_content')))}
						<div class="blank"></div>
						<div class="input-group">
							<span class="input-group-addon">{t('有效期')}</span>
							{form::field(array('type'=>'number','name'=>'register_validmail_expire','value'=>c('member.register_validmail_expire'),'min'=>1))}
							<span class="input-group-addon">{t('小时')}</span>
						</div>
					</td>
				</tr>

				</tbody>
			</table>

			<table class="field">
				<caption>{t('登录设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('开启验证码'),'login_captcha',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'login_captcha','value'=>c('member.login_captcha')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('奖励积分'),'login_point',false)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'login_point','value'=>c('member.login_point')))}
						{form::tips(t('每次登录奖励积分'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('找回密码')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('找回密码邮件'),'getpassword_mailcontent',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'getpasswordmail_title','value'=>c('member.getpasswordmail_title')))}
						<div class="blank"></div>
						{form::field(array('type'=>'editor','name'=>'getpasswordmail_content','value'=>c('member.getpasswordmail_content')))}
						<div class="blank"></div>
						<div class="input-group">
							<span class="input-group-addon">{t('有效期')}</span>
							{form::field(array('type'=>'number','name'=>'getpasswordmail_expire','value'=>c('member.getpasswordmail_expire'),'min'=>0.1))}
							<span class="input-group-addon">{t('小时')}</span>
						</div>
					</td>
				</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}