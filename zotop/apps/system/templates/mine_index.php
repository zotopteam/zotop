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
					<td class="label">{form::label(t('真实姓名'),'realname',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'realname','value'=>$data['realname'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('真实姓名用于管理员之间查看，仅在后台可见'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('我的昵称'),'nickname',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/mine/check/nickname','ignore='.$data['nickname'])))}
						{form::tips(t('前台显示的昵称'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('电子邮件'),'email',true)}</td>
					<td class="input">
						{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('system/mine/check/email','ignore='.$data['email'])))}
						{form::tips(t('用于接收系统发送的邮件'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('登陆次数'),'logintimes',false)}</td>
					<td class="input">
					<div class="field-text">{$data['logintimes']}</div>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('登陆时间'),'logintime',false)}</td>
					<td class="input">
					<div class="field-text">{format::date($data['logintime'])}</div>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('登陆IP'),'loginip',false)}</td>
					<td class="input">
					<div class="field-text">{$data['loginip']}</div>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('注册时间'),'regtime',false)}</td>
					<td class="input">
					<div class="field-text">{format::date($data['regtime'])}</div>
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
				$(form).find('.submit').disable(false);
			},'json');
			return false;
		}});
	});
</script>
{template 'footer.php'}