{template 'header.php'}

<div class="side">
	{template 'system/mine_side.php'}
</div>


{form::header()}
<div class="main side-main">
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
						{form::field(array('type'=>'password','name'=>'password','value'=>'','minlength'=>6,'maxlength'=>20,'required'=>'required','remote'=>u('system/mine/checkpassword')))}
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
				if ( msg.state ) $(form).get(0).reset();
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}