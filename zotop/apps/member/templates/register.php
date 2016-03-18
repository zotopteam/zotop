{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
        <li>{$title}</li>
    </ul>
</div>

<div class="row">
<div class="main">
<div class="main-inner">

	{if count($models) > 1}
	<ul class="model">
		{loop $models $i $r}
		<li {if $modelid==$i}class="current"{/if}><a href="{U('member/register/'.$i)}">{$r.name}</a></li>
		{/loop}
	</ul>
	{/if}

	{form::header()}

	{form::field(array('type'=>'hidden','name'=>'modelid',value=>$modelid,'required'=>'required'))}

	<div class="form-header">{t('%s注册', $model['name'])}</div>
	<div class="form-body clearfix">
		<div class="field">
					<div class="label">{form::label(t('用户名'),'username',true)}</div>
					<div class="input">
						{form::field(array('type'=>'text','name'=>'username','minlength'=>4,'maxlength'=>20,'required'=>'required','remote'=>u('member/register/check/username')))}

						{form::tips(t('4-20位字符，允许中文、英文、数字和下划线，不能含有特殊字符'))}
					</div>
		</div><!-- field -->

		<div class="field">
					<div class="label">{form::label(t('请输入密码'),'password',true)}</div>
					<div class="input">
						{form::field(array('type'=>'password','name'=>'password','required'=>'required'))}
						{form::tips(t('6-20位字符，可使用英文、数字或者符号组合，不建议使用纯数字、纯字母或者纯符号'))}
					</div>
		</div><!-- field -->

		<div class="field">
					<div class="label">{form::label(t('请确认密码'),'password',true)}</div>
					<div class="input">
						{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','required'=>'required'))}

						{form::tips(t('请再次输入您的密码'))}

					</div>
		</div><!-- field -->

		<div class="field">
					<div class="label">{form::label(t('您的昵称'),'password',true)}</div>
					<div class="input">
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('member/register/check/nickname')))}

					{form::tips(t('为自己起一个独特的昵称'))}
					</div>
		</div><!-- field -->

		<div class="field">
					<div class="label">{form::label(t('您的邮箱'),'password',true)}</div>
					<div class="input">
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('member/register/check/email')))}
					{form::tips(t('您的邮箱地址，我们将给你发送邮件'))}
					</div>
		</div><!-- field -->

		{loop $fields $f}
		<div class="field">
			<div class="label">{form::label($f['label'], $f['for'], $f['required'])}</div>
			<div class="input">
				{form::field($f['field'])}
				{form::tips($f['tips'])}
			</div>
		</div>
		{/loop}

		{if C('member.register_captcha')}
		<div class="field">
			<div class="label">{form::label(t('验证码'),'captcha')}</div>
			<div class="input">
				{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}
			</div>
		</div><!-- field -->
		{/if}

	</div><!-- form-body -->
	<div class="form-footer">
		{form::field(array('type'=>'submit','value'=>t('立即注册')))}

		<div class="result">&nbsp;</div>

	</div>
	{form::footer()}

</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	<div>{t('我已经注册')}</div>

	<div class="login">
		<a class="btn btn-highlight" href="{U('member/login')}">{t('立即登录')}</a>
	</div>
</div> <!-- side -->
</div> <!-- row -->

<style type="text/css">
.row{margin:0 0 10px 0;}
.row .main-inner{margin-right:400px;padding-top:32px;}
.row .side{margin-left:-300px;width:300px;}

ul.model{overflow:hidden;zoom:1;padding-left:150px;z-index:100;height:33px;position:absolute;top:0;}
ul.model li{float:left;text-align:center;background:#f7f7f7;border:1px solid #dddddd;margin-right:8px;font-size:14px;width:100px;height:28px;margin-top:3px;text-align:center;line-height:28px;border-bottom:0 none;}
ul.model li.current{color:#fff;background:#f3f3f3;color:#e4393c;height:32px;line-height:32px;font-weight:bold;margin-top:0;}
ul.model li a{display:block;width:100%;}
ul.model li.current a{color:#ff0000;}

form.form{position:relative;border:solid 1px #d5d5d5;background:#f3f3f3;padding:15px;border-radius:5px;}
div.form-header{font-size:24px;font-weight:bold;padding:0px 0 20px 0;}
div.form-footer{margin:15px 0;padding-left:130px;}
div.form-footer .btn{width:340px;padding:8px;}
div.form-footer .btn button{font-size:16px;}
div.form-footer .result{padding:5px 0;line-height:24px;}

div.field{position:relative;line-height:20px;padding:3px 0px 3px 130px;margin:5px 0;}
div.field div.label{display:inline-block;zoom:1;width:120px;height:20px;padding:0;position:absolute;top:12px;left:10px;}
div.field div.input .text{width:400px;padding:8px 5px;height:20px;line-height:20px;font-size:14px;}
div.field div.input .captcha{width:100px;}

div.login{margin-top:10px;}
</style>

<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').disable(true);
				$.post(action, data, function(msg){
					console.log(msg);
					if( msg.state ){

						$(form).find('.result').html(msg.content);

						if ( msg.url ){
							location.href = msg.url;
						}
						return true;
					}
					$(form).find('.submit').disable(false);
					$(form).find('.result').html(msg.content);
					return false;
				},'json');
			}
		});
	});
</script>
{template 'footer.php'}