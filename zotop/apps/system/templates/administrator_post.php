{template 'header.php'}
<div class="side">
	{template 'system/system_side.php'}
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
				<td class="label">{form::label(t('用户名'),'username',true)}</td>
				<td class="input">
				{if $data['username']}
					<div class="field-text"><b>{$data['username']}</b></div>
				{else}
					{form::field(array('type'=>'text','name'=>'username','value'=>$data['username'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/administrator/check/username','ignore='.$data['username'])))}
					{form::tips(t('用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符'))}
				{/if}
				</td>
			</tr>
			{if empty($data['password'])}
			<tr>
				<td class="label">{form::label(t('用户密码'),'password',true)}</td>
				<td class="input">
					{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('请输入您要设置的新密码'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('密码确认'),'password2',true)}</td>
				<td class="input">
					{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20,'required'=>'required'))}
					{form::tips(t('为确保安全，请再次输入新密码'))}
				</td>
			</tr>
			{else}
			<tr>
				<td class="label">{form::label(t('新密码'),'password',false)}</td>
				<td class="input">
					{form::field(array('type'=>'password','name'=>'password','minlength'=>6,'maxlength'=>20))}
					{form::tips(t('请输入您要设置的新密码，不修改密码请留空'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('密码确认'),'password2',false)}</td>
				<td class="input">
					{form::field(array('type'=>'password','name'=>'password2','equalto'=>'#password','minlength'=>6,'maxlength'=>20))}
					{form::tips(t('为确保安全，请再次输入新密码，不修改密码请留空'))}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="label">{form::label(t('昵称'),'nickname',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/administrator/check/nickname','ignore='.$data['nickname'])))}
					{form::tips(t('前台显示的昵称'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('电子邮件'),'email',true)}</td>
				<td class="input">
					{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('system/administrator/check/email','ignore='.$data['email'])))}
				</td>
			</tr>
			</tbody>
		</table>
		<table class="field">
			<tbody>
			{if $data['id']!=1}
			<tr>
				<td class="label">{form::label(t('角色'),'groupid',false)}</td>
				<td class="input">
				{form::field(array('type'=>'radio','options'=>$roles,'name'=>'groupid','value'=>$data['groupid'],'column'=>1))}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="label">{form::label(t('真实姓名'),'realname',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'realname','value'=>$data['realname'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
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

				if ( !msg.state ){
					$(form).find('.submit').disable(false);
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}