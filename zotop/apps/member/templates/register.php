{template 'header.php'}

{if count($models) > 1}
<nav class="nav nav-inline">
	<ul>
		{loop $models $i $r}
		<li><a href="{U('member/register/'.$i)}" {if $modelid==$i}class="active"{/if}>{$r.name}</a></li>
		{/loop}
	</ul>
</nav>
{/if}	

<div class="container">

	{form class="form-horizontal"}

		{field type="hidden" name="modelid" value="$modelid" required="required"}

		<div class="panel">
			<div class="panel-heading">
				<h4>{t('%s注册', $model['name'])}</h4>
			</div>
			<div class="panel-body">		

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('用户名'),'username',true)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'text','name'=>'username','minlength'=>4,'maxlength'=>20,'required'=>'required','remote'=>u('member/register/check/username')))}

						{form::tips(t('4-20位字符，允许中文、英文、数字和下划线，不能含有特殊字符'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('请输入密码'),'password',true)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'password','name'=>'password','required'=>'required'))}
						{form::tips(t('6-20位字符，可使用英文、数字或者符号组合，不建议使用纯数字、纯字母或者纯符号'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('请确认密码'),'password',true)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','required'=>'required'))}

						{form::tips(t('请再次输入您的密码'))}

					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('您的昵称'),'password',true)}</div>
					<div class="col-sm-10">
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('member/register/check/nickname')))}

					{form::tips(t('为自己起一个独特的昵称'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('您的邮箱'),'password',true)}</div>
					<div class="col-sm-10">
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('member/register/check/email')))}
					{form::tips(t('您的邮箱地址，我们将给你发送邮件'))}
					</div>
				</div>

				{loop $fields $f}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label($f['label'], $f['for'], $f['required'])}</div>
					<div class="col-sm-10">
						{form::field($f['field'])}
						{form::tips($f['tips'])}
					</div>
				</div>
				{/loop}

				{if C('member.register_captcha')}
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('验证码'),'captcha')}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}
					</div>
				</div>
				{/if}

			</div><!-- pannl-body -->
			<div class="panel-footer">
				{form::field(array('type'=>'submit','value'=>t('立即注册')))}

				<div class="result">&nbsp;</div>

			</div>			
		</div>	
	{form::footer()}

</div> <!-- container -->

<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				
				$(form).find('.submit').button('loading');
				
				$.post(action, data, function(msg){
					console.log(msg);
					if( msg.state ){

						$(form).find('.result').html(msg.content);

						if ( msg.url ){
							location.href = msg.url;
						}
						return true;
					}
					$(form).find('.submit').button('reset');
					$(form).find('.result').html(msg.content);
					return false;
				},'json');
			}
		});
	});
</script>

{template 'footer.php'}