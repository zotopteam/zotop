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

	{form::header()}
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

			<table class="field">
				<tbody>
				<tr>
					<td class="label">{form::label(t('用户名'),'username',false)}</td>
					<td class="input">
					<div class="field-text"><b>{$data['username']}</b></div>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('原密码'),'password',true)}</td>
					<td class="input">
						{form::field(array('type'=>'password','name'=>'password','value'=>'','minlength'=>6,'maxlength'=>20,'required'=>'required','remote'=>u('member/mine/checkpassword')))}
						{form::tips(t('请输入当前正在使用的密码'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('新密码'),'newpassword',true)}</td>
					<td class="input">
						{form::field(array('type'=>'password','name'=>'newpassword','value'=>$data['newpassword'],'minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('请输入您要设置的新密码'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('新密码确认'),'newpassword2',true)}</td>
					<td class="input">
						{form::field(array('type'=>'password','name'=>'newpassword2','value'=>$data['newpassword2'],'equalto'=>'#newpassword','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('为确保安全，请再次输入新密码'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('验证码'),'captcha',true)}</td>
					<td class="input">
						{form::field(array('type'=>'captcha','name'=>'captcha','value'=>'','required'=>'required'))}
					</td>
					</td>
				</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('提交')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}

		<span class="result error"></span>
	</div><!-- main-footer -->
	{form::footer()}


</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	{template 'member/side.php'}
</div> <!-- side -->
</div> <!-- row -->

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