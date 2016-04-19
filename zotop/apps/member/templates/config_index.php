{template 'header.php'}
{template 'member/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->

	{form action="u('member/config/save')" class="form-horizontal"}
	<div class="main-body scrollable">
		<div class="container-fluid">
			<div class="form-title">{t('会员注册')}</div>

            <div class="form-group">
                <div class="col-sm-2 control-label">{form::label(t('会员注册'),'register',false)}</div>
                <div class="col-sm-8">
                {field type="radio" options="array(1=>t('允许-无需审核'),2=>t('允许-需要审核'),0=>t('禁止'))" name="register" value="c('member.register')"}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 control-label">{form::label(t('开启验证码'),'register_captcha',false)}</div>
                <div class="col-sm-8">
                    {form::field(array('type'=>'bool','name'=>'register_captcha','value'=>c('member.register_captcha')))}
                </div>
            </div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('禁用用户名'),'register_point',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'register_banned','value'=>c('member.register_banned')))}
					{form::tips(t('禁止使用的用户名和昵称，每行一个，可以使用通配符*和?'))}
				</div>
			</div>

            <div class="form-group">
                <div class="col-sm-2 control-label">{form::label(t('用户注册协议'),'register_protocol',false)}</div>
                <div class="col-sm-8">
                    {form::field(array('type'=>'editor','name'=>'register_protocol','value'=>c('member.register_protocol'),'rows'=>8))}
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 control-label">{form::label(t('关闭注册原因'),'register_closed',false)}</div>
                <div class="col-sm-8">
                    {form::field(array('type'=>'textarea','name'=>'register_closed','value'=>c('member.register_closed'),'rows'=>3))}
                </div>
            </div>                        

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('邮件验证'),'register_validmail',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'register_validmail','value'=>c('member.register_validmail')))}					

					<div class="help-block">
						<span>{t('发送邮件使用系统的邮件发送功能')}</span>
						{if !C('system.mail')}
						<a href="{U('system/config/mail')}" class="text-danger">{t('邮件发送功能尚未正确配置，立即配置')}</a>
						{/if}
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">
                    {form::label(t('验证邮件'),'register_validmail_content',false)}
                    <i class="fa fa-question-circle tooltip-block" data-placement="right">
                        <div class="tooltip-block-content">
                            <h4>{t('标题内容均支持使用如下变量替换')}:</h4>
                            <table class="table table-condensed table-noborder">
                                <tbody>
                                    <tr><td>{username} </td><td>{t('用户名')}</td></tr>
                                    <tr><td>{nickname} </td><td>{t('用户昵称')}</td></tr>
                                    <tr><td>{time} </td><td>{t('发送时间')}</td></tr>
                                    <tr><td>{sitename} </td><td>{t('网站名称 (站点设置中的网站名称)')}</td></tr>
                                    <tr><td>{url} </td><td>{t('验证的url')}</td></tr>
                                    <tr><td>{expire} </td><td>{t('有效期')}</td></tr>
                                    <tr><td>{from} </td><td>{t('发送者的Email')}</td></tr>                  
                                </tbody>
                            </table>                                         
                        </div>
                    </i>
                </div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'register_validmail_title','value'=>c('member.register_validmail_title')))}
					<div class="blank"></div>
					{field type="editor" name="register_validmail_content" value="c('member.register_validmail_content')" rows="13"}
					<div class="blank"></div>
					<div class="input-group">
						<span class="input-group-addon">{t('有效期')}</span>
						{form::field(array('type'=>'number','name'=>'register_validmail_expire','value'=>c('member.register_validmail_expire'),'min'=>1))}
						<span class="input-group-addon">{t('小时')}</span>
					</div>
				</div>
			</div>

			<div class="form-title">{t('登录设置')}</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('开启验证码'),'login_captcha',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'login_captcha','value'=>c('member.login_captcha')))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('奖励积分'),'login_point',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'login_point','value'=>c('member.login_point')))}
					{form::tips(t('每次登录奖励积分'))}
				</div>
			</div>

			<div class="form-title">{t('找回密码')}</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">
                    {form::label(t('找回密码邮件'),'getpassword_mailcontent',false)}
                    <i class="fa fa-question-circle tooltip-block" data-placement="right">
                        <div class="tooltip-block-content">
                            <h4>{t('标题内容均支持使用如下变量替换')}:</h4>
                            <table class="table table-condensed table-noborder">
                                <tbody>
                                    <tr><td>{username} </td><td>{t('用户名')}</td></tr>
                                    <tr><td>{nickname} </td><td>{t('用户昵称')}</td></tr>
                                    <tr><td>{time} </td><td>{t('发送时间')}</td></tr>
                                    <tr><td>{sitename} </td><td>{t('网站名称 (站点设置中的网站名称)')}</td></tr>
                                    <tr><td>{code} </td><td>{t('邮件验证码')}</td></tr>
                                    <tr><td>{expire} </td><td>{t('有效期')}</td></tr>
                                    <tr><td>{from} </td><td>{t('发送者的Email')}</td></tr>                  
                                </tbody>
                            </table>                                         
                        </div>
                    </i>                    
                </div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'getpasswordmail_title','value'=>c('member.getpasswordmail_title')))}
					<div class="blank"></div>
					{field type="editor" name="getpasswordmail_content" value="c('member.getpasswordmail_content')" rows="13"}
					<div class="blank"></div>
					<div class="input-group">
						<span class="input-group-addon">{t('有效期')}</span>
						{form::field(array('type'=>'number','name'=>'getpasswordmail_expire','value'=>c('member.getpasswordmail_expire'),'min'=>0.1))}
						<span class="input-group-addon">{t('小时')}</span>
					</div>

					<div class="help-block">
						<span>{t('发送邮件使用系统的邮件发送功能')}</span>
						{if !C('system.mail')}
						<a href="{U('system/config/mail')}" class="text-danger">{t('邮件发送功能尚未正确配置，立即配置')}</a>
						{/if}
					</div>
				</div>			
			</div>

		</div> <!-- container-fluid -->
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{/form}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>
{template 'footer.php'}