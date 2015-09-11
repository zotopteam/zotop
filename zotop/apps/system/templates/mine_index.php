{template 'header.php'}

{template 'system/mine_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">

			<div class="container-fluid">
				<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('用户名'),'username',false)}</div>
					<div class="col-sm-10">
						<div class="form-control-static"><b>{$data['username']}</b></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('真实姓名'),'realname',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'text','name'=>'realname','value'=>$data['realname'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('真实姓名用于管理员之间查看，仅在后台可见'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('我的昵称'),'nickname',true)}</div>
					<div class="col-sm-5">
						{form::field(array('type'=>'text','name'=>'nickname','value'=>$data['nickname'],'minlength'=>2,'maxlength'=>32,'required'=>'required','remote'=>u('system/mine/check/nickname','ignore='.$data['nickname'])))}
						{form::tips(t('前台显示的昵称'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('电子邮件'),'email',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'email','name'=>'email','value'=>$data['email'],'required'=>'required','remote'=>u('system/mine/check/email','ignore='.$data['email'])))}
						{form::tips(t('用于接收系统发送的邮件'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('登陆次数'),'logintimes',false)}</div>
					<div class="col-sm-10">
						<div class="form-control-static">{$data['logintimes']}</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('登陆时间'),'logintime',false)}</div>
					<div class="col-sm-10">
						<div class="form-control-static">{format::date($data['logintime'])}</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('登陆IP'),'loginip',false)}</div>
					<div class="col-sm-10">
						<div class="form-control-static">{$data['loginip']}</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('注册时间'),'regtime',false)}</div>
					<div class="col-sm-10">
						<div class="form-control-static">{format::date($data['regtime'])}</div>
					</div>
				</div>
				</div>
			</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->

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