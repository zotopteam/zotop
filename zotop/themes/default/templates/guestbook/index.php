<?php
/**
 * title:留言模板
 * description:默认留言首页模板
*/
?>
{template 'header.php'}

<div class="box text-center">
	<div class="container-fluid">
	  <h3>{$title}</h3>
	  <p>{$description}</p>
	</div>
</div>

{form::header(u('guestbook/index/add'))}
<div class="box" id="guestbook-add">
<div class="box-head">
	{t('发表留言')}
</div>
<div class="box-body">
	
	<div class="form-group">
		{form::label(t('您的名字'),'name',true)}
		{form::field(array('type'=>'text','name'=>'name','required'=>'required'))}
	</div>
	<div class="form-group">
		{form::label(t('您的邮箱'),'email',true)}
		{form::field(array('type'=>'email','name'=>'email','required'=>'required'))}
	</div>
	<div class="form-group">
		{form::label(t('留言内容'),'content',true)}
		{form::field(array('type'=>'textarea','name'=>'content','required'=>'required','minlength'=>c('guestbook.minlength'),'maxlength'=>c('guestbook.maxlength')))}
	</div>
	{if c('guestbook.captcha')}
	<div class="form-group">
		{form::label(t('验证码'),'captcha',true)}
		{form::field(array('type'=>'captcha','name'=>'captcha','required'=>'required'))}
	</div>
	{/if}
	<div class="form-group">		
			<button class="btn btn-primary" type="submit">提交留言</button>
			<span class="result"></span>
		</div>
	</div>
</div>
</div>
{form::footer()}


<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){

				$(form).find('.submit').disable(true);
				$(form).find('.result').html('<i class="icon icon-loading"></i>');

				$.post($(form).attr('action'), $(form).serialize(), function(msg){

					if( msg.state ){

						$(form).find('.result').addClass('success').html(msg.content);
						$(form)[0].reset();

						return true;
					}

					$(form).find('.submit').disable(false);
					$(form).find('.result').addClass('error').html(msg.content);
					return false;
				},'json');
			}
		});
	});
</script>

{template 'footer.php'}