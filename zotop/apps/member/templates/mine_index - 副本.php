{template 'member/header.php'}



{form class="form-horizontal"}
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{$title}</div>
		</div>
		<div class="panel-body">
			{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('用户名'),'username',true)}</div>
				<div class="col-sm-8">
				{if $data['username']}
					<div class="field-text"><b>{$data['username']}</b></div>
				{else}
					{form::field(array('type'=>'text','name'=>'username','value'=>$data['username'],'minlength'=>2,'maxlength'=>32,'required'=>'required'))}
					{form::tips(t('用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符'))}
				{/if}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('昵称'),'nickname',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'hidden','name'=>'_nickname','value'=>$data['nickname']))}
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('member/mine/check/nickname','ignore='.$data['nickname'])))}
					{form::tips(t('2-20个字符，可由中文、英文、数字、“_”和“-”组成'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('电子邮件'),'email',true)}</div>
				<div class="col-sm-8">
					<div class="field-text"><b>{$data['email']}</b></div>
					{if $data['emailstatus']}
						{t('邮箱已经通过验证，<a href="%s">修改</a>',U('member/mine/email'))}
					{else}
						<div class="red">{t('邮箱未通过验证，系统已经向该邮箱发送了一封验证邮件，请查收邮件并验证您的邮箱')}</div>
						<div>{t('如果没有收到邮件，您可以 <a href="%s">更换一个邮箱</a> 或者 <a href="%s">重新发送激活邮件</a>', U('member/mine/email'), U('member/mine/validemail'))}</div>
					{/if}
				</div>
			</div>
				
			{loop $fields $f}
			<div class="form-group">
				<label for="{$f.for}" class="col-sm-2 control-label {if $f.required}required{/if}">{$f.label}</label>
				<div class="col-sm-8">
					{form::field($f.field)}
					{if $f.tips}<div class="help-block">{$f.tips}</div>{/if}
				</div>
			</div>
			{/loop}

		</div><!-- panel-body -->
		<div class="panel-footer">
			{form::field(array('type'=>'submit','value'=>t('提交')))}
			<span class="result error"></span>
		</div>
	</div> <!-- panel -->

{/form}

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

{template 'member/footer.php'}