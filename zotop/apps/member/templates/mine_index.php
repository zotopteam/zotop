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
		{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('用户名'),'username',true)}</td>
				<td class="input">
				{if $data['username']}
					<div class="field-text"><b>{$data['username']}</b></div>
				{else}
					{form::field(array('type'=>'text','name'=>'username','value'=>$data['username'],'minlength'=>2,'maxlength'=>32,'required'=>'required'))}
					{form::tips(t('用户名长度在2-32位之间，允许英文、数字和下划线，不能含有特殊字符'))}
				{/if}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('昵称'),'nickname',true)}</td>
				<td class="input">
					{form::field(array('type'=>'hidden','name'=>'_nickname','value'=>$data['nickname']))}
					{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('member/mine/check/nickname','ignore='.$data['nickname'])))}
					{form::tips(t('2-20个字符，可由中文、英文、数字、“_”和“-”组成'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('电子邮件'),'email',true)}</td>
				<td class="input">
					<div class="field-text"><b>{$data['email']}</b></div>
					{if $data['emailstatus']}
						{t('邮箱已经通过验证，<a href="%s">修改</a>',U('member/mine/email'))}
					{else}
						<div class="red">{t('邮箱未通过验证，系统已经向该邮箱发送了一封验证邮件，请查收邮件并验证您的邮箱')}</div>
						<div>{t('如果没有收到邮件，您可以 <a href="%s">更换一个邮箱</a> 或者 <a href="%s">重新发送激活邮件</a>', U('member/mine/email'), U('member/mine/validemail'))}</div>
					{/if}
				</td>
			</tr>
			</tbody>
		</table>
		<table class="field">
			<tbody>
			{loop $fields $f}
			<tr>
				<td class="label">{form::label($f['label'], $f['for'], $f['required'])}</td>
				<td class="input">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</td>
			</tr>
			{/loop}
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