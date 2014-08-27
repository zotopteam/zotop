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

	{form::header()}
	<div class="form-header">{c('site.name')} {$title}</div>
	<div class="form-status">{t('请输入您的账号和密码登录')}</div>
	<div class="form-body clearfix">
		<div class="field">
					<div class="label">{form::label('<i class="icon icon-user"></i>','username')}</div>
					<div class="input">
						{form::field(array('type'=>'text','name'=>'username','value'=>'','required'=>'required','placeholder'=>t('请输入您的账号')))}
					</div>
		</div><!-- field -->

		<div class="field">
					<div class="label">{form::label(t('<i class="icon icon-lock"></i>'),'password')}</div>
					<div class="input">
						{form::field(array('type'=>'password','name'=>'password','required'=>'required','placeholder'=>t('请输入您的密码')))}
					</div>
		</div><!-- field -->

		{if C('member.login_captcha')}
		<div class="field">
			<div class="label">{form::label(t('<i class="icon icon-safe"></i>'),'captcha')}</div>
			<div class="input">
				{form::field(array('type'=>'captcha','name'=>'captcha','alt'=>t('看不清？'),'required'=>'required'))}
			</div>
		</div><!-- field -->
		{/if}

		<div class="extra">
			<span class="field remember fl">
				<label>
					<input type="checkbox" class="checkbox" id="cookietime" name="cookietime" value="50400" {if zotop::cookie('cookietime')}checked="checked"{/if}/>
					{t('两周内自动登录')}
				</label>
			</span>
			<a href="{u('member/login/getpassword')}" class="fr">{t('忘记密码?')}</a>
		</div>
	</div><!-- form-body -->
	<div class="form-footer">
		{form::field(array('type'=>'submit','value'=>t('立即登录')))}
	</div>
	{form::footer()}

</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">

	{if !empty($models)}

	<div class="register">
	{t('还没有账号？')} <a href="{U('member/register')}">{t('立即注册')}</a>
	</div>

	<ul class="image-text member_model">
	{loop $models $r}
		<li>
			<div class="image">
				<div class="member_model {$r.id}"><s class="icon icon-user"></s></div>
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

.row{margin:20px 0;}
.row .main-inner{margin-right:540px;}
.row .side{margin-left:-540px;width:540px;}

form.form{width:350px;border:solid 1px #d5d5d5;background:#f3f3f3;padding:15px;border-radius:5px;}
div.form-header{font-size:24px;font-weight:bold;padding:10px 0 20px 0;}
div.form-status{padding:5px 0;}
div.form-footer{padding:10px 0;clear:both;}
div.form-footer .btn{width:330px;padding:8px;}
div.form-footer button{font-size:16px;line-height:22px;}

div.field{position:relative;line-height:20px;padding:3px 0px 3px 45px;border:1px solid #DFDFDF;background:#fff;margin:5px 0;}
div.field div.label{display:inline-block;zoom:1;width:25px;height:20px;padding:0;position:absolute;top:12px;left:10px;border-right:1px solid #CCC;}
div.field div.label i.icon{font-size:18px;}
div.field div.input input.text{width:300px;padding:8px 0;height:20px;line-height:20px;border:0 none;font-size:14px;}
div.field div.input input.captcha{width:100px;}


ul.image-text{margin-top:20px;}
ul.image-text li{border:solid 1px #EBEBEB;}
ul.image-text li .image{width:48px;height:48px;font-size:36px;text-align:center;margin-left:5px;margin-right:5px;}
ul.image-text li .title a{font-weight:bold;font-size:15px;}
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