<?php
/**
 * title:留言模板
 * description:默认留言首页模板
*/
?>
{template 'header.php'}

{if $banner = C('guestbook.image')}
<div class="banner banner-image" style="background-image:url({thumb($banner,1920,360)});background-size:cover;"></div>
{else}
<div class="banner banner-full text-center">
	<div class="container">
	  <h1>{$title}</h1>
	  <p>{$description}</p>
	</div>
</div>
{/if}

<section class="section">
	<div class="container">
		{form action="u('guestbook/index/add')" class="form-horizontal"}
		<div class="panel panel-default" id="guestbook-add">
			<div class="panel-heading">
				<div class="panel-title">{t('发表留言')}</div>
			</div>
			<div class="panel-body">
				
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label required">{t('您的名字')}</label>
					<div class="col-sm-8">
						{field type="text" name="name" required="required" minlength="2" title="t('请输入您的姓名')"}
					</div>
				</div>	
				<div class="form-group">
					<label for="email" class="col-sm-2 control-label required">{t('联系方式')}</label>
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-4">
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-envelope fa-fw"></i> {t('邮箱')}</div>
									{field type="email" name="email" required="required"}
								</div>
								<div class="blank visible-xs-block"></div>
							</div>
							<div class="col-sm-4">
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-mobile fa-fw"></i> {t('手机')}</div>
									{field type="text" name="mobile" required="required"}
								</div>
								<div class="blank visible-xs-block"></div>
							</div>
							<div class="col-sm-4">
								<div class="input-group">
									<div class="input-group-addon"><i class="fa fa-qq fa-fw"></i> {t('QQ')}&nbsp;</div>
									{field type="qq" name="qq" required="required"}
								</div>
							</div>														
						</div>
					</div>
				</div>					
				<div class="form-group">
					<label for="content" class="col-sm-2 control-label required">{t('留言内容')}</label>
					<div class="col-sm-8">
						{field type="textarea" name="content" required="required" rows="5" maxlength="c('guestbook.maxlength')" minlength="c('guestbook.minlength')"}
					</div>
				</div>				
				{if c('guestbook.captcha')}
				<div class="form-group">
					<label for="captcha" class="col-sm-2 control-label required">{t('验证码')}</label>
					<div class="col-sm-6">
						{field type="captcha" name="captcha" required="required"}
					</div>
				</div>
				{/if}

				<div class="form-group">
					<div class="col-sm-2 col-sm-offset-2">
						{field type="submit" value="t('提交留言')" class="btn-block"}
					</div>
					<div class="col-sm-6">
						<div class="form-control-static result"></div>
					</div>
				</div>
			</div>
		</div>
		{/form}

	</div>
</section>

<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){

				$(form).find('.submit').button('loading');

				$.post($(form).attr('action'), $(form).serialize(), function(msg){

					$(form).find('.submit').button('reset');
					
					if( msg.state ){

						$(form).find('.result').addClass('text-success').html(msg.content);
						$(form)[0].reset();

						return true;
					}

					$(form).find('.result').addClass('text-error').html(msg.content);
					return false;
				},'json');
			}
		});
	});
</script>

{template 'footer.php'}