{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">

			{form::field(array('type'=>'hidden','name'=>'mail','value'=>c('system.mail')))}

			<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('发送方式'),'mail_mailer',true)}</td>
					<td class="input">
					<?php echo form::field(array(
					   'type'=>'radio',
					   'options'=>array(
							1=>t('通过 PHP 的 mail 函数发送'),
							2=>t('通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)'),
							3=>t('通过 PHP 函数 SMTP 发送(仅 Windows 主机下有效, 不支持 ESMTP 验证)'),
						),
					   'column' => 1,
					   'name'=>'mail_mailer',
					   'value'=>c('system.mail_mailer'),
					   'required'=>'required',
					))?>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('发送人邮箱'),'mail_from',true)}</td>
					<td class="input">
						{form::field(array('type'=>'email','name'=>'mail_from','value'=>c('system.mail_from'),'required'=>'required'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('邮件签名'),'mail_sign',false)}</td>
					<td class="input">
						{form::field(array('type'=>'textarea','name'=>'mail_sign','value'=>c('system.mail_sign')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('邮件头分隔符'),'mail_delimiter',false)}</td>
					<td class="input">
					<?php echo form::field(array(
					   'type'=>'radio',
					   'options'=>array(
							1=>t('使用 CRLF 作为分隔符(通常为 Windows 主机)'),
							2=>t('使用 LF 作为分隔符(通常为 Unix/Linux 主机)'),
							3=>t('使用 CR 作为分隔符(通常为 Mac 主机)'),
						),
					   'column' => 1,
					   'name'=>'mail_delimiter',
					   'value'=>c('system.mail_delimiter'),
					   'required'=>'required',
					))?>
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('SMTP设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('服务器'),'mail_smtp_host',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'mail_smtp_host','value'=>c('system.mail_smtp_host')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('端口'),'mail_smtp_port',false)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'mail_smtp_port','value'=>c('system.mail_smtp_port'),'min'=>0))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('身份验证'),'mail_smtp_auth',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'mail_smtp_auth','value'=>c('system.mail_smtp_auth')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('用户名'),'mail_smtp_username',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'mail_smtp_username','value'=>c('system.mail_smtp_username')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('密码'),'mail_smtp_password',false)}</td>
					<td class="input">
						{form::field(array('type'=>'password','name'=>'mail_smtp_password','value'=>c('system.mail_smtp_password')))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('设置验证')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('验证邮箱'),'',true)}</td>
					<td class="input">
						{form::field(array('type'=>'email','name'=>'sendto','required'=>'required','placeholder'=>t('请输入接收验证码的邮箱地址')))}
						&nbsp;<a class="btn" href="javascript:void(0)" id="testmail">{t('发送验证码')}</a>

						{form::tips('尝试向验证邮箱发送一个验证码，如果邮件发送设置正确，将会收到含有验证码的邮件')}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('验证码'),'',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'mailvalid','required'=>'required','placeholder'=>t('请输入您收到的验证码')))}
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

	$(function(){

		$('#mail_from').change(function(){
			var from = $(this).val();
			var user = from.split('@')[0];
			var host = from.split('@')[1];

			//控件赋值
			$('input[name="mail_smtp_host"]').val('smtp.' + host);
			$('input[name="mail_smtp_port"]').val(25);
			$('input[name="mail_smtp_username"]').val(user);
			$('input[name="mail_smtp_password"]').val('');

		});
	});

	$(function(){
		$('#testmail').on('click',function(){
			var data = $('form.form').serialize();
			$.loading();
			$.post("{u('system/config/mailvalid')}", data, function(msg){
				$.msg(msg);
			},'json');
		});
	});
</script>
{template 'footer.php'}