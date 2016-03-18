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
				<td class="label">{form::label(t('当前邮箱地址'),'')}</td>
				<td class="input">
					<div class="field-text"><b>{$data['email']}</b></div>
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('新邮箱地址'),'email',true)}</td>
				<td class="input">
					{form::field(array('type'=>'email','name'=>'email','value'=>'','required'=>'required','remote'=>u('member/mine/check/email','ignore='.$data['email'])))}
					{form::tips(t('邮箱地址修改后我们将向您的新邮箱发送一封验证邮件，请点击邮件中的链接重新验证'))}
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