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

		<div class="panel panel-register">
			<div class="panel-heading">
				<h3 class="text-center">{t('%s注册', $model['name'])}</h3>
			</div>
			<div class="panel-body">	

				{loop $fields $f}
				<div class="form-group">
					<label for="{$f.for}" class="col-sm-2 control-label {if $f.field.required}required{/if}">{$f.label}</label>
					<div class="col-sm-10">
						{form::field($f['field'])}
						{if $f.tips}<div class="help-block">{$f.tips}</div>{/if}
					</div>
				</div>
				{/loop}

				{if C('member.register_captcha')}
				<div class="form-group">
					<label for="captcha" class="col-sm-2 control-label required">{t('验证码')}</label>
					<div class="col-sm-10">
						{field type="captcha" name="captcha" required="required"}
					</div>
				</div>
				{/if}

				<div class="form-group">
					<div class="col-sm-2 control-label"></div>
					<div class="col-sm-10">
						<div class="text-muted">{t('点击“立即注册”，即表示您同意并愿意遵守我们的<a href="javascript:;">用户协议</a>')}</div>
					</div>
				</div>				

				<div class="form-group">
					<div class="result">&nbsp;</div>
					{field type="submit" value="t('立即注册')" data-loading-text="t('注册中，请稍后……')" class="btn-block btn-lg"}								
				</div>

			</div><!-- pannl-body -->
			<div class="panel-footer text-center">
				{t('已有账号？')} <a href="{U('member/login')}">{t('立即登录')}</a>
			</div>			
		</div>	
	{form::footer()}

</div> <!-- container -->

<style>
	.panel-register{}
	.panel-register .panel-body{max-width:800px;margin:auto;}
	.panel-register .form-group:last-child{border:0;}
</style>

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