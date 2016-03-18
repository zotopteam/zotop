{template 'header.php'}

{template 'member/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">

		<div class="container-fluid">
			<div class="form-title">{t('基本信息')}</div>
			<div class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('模型'),'modelid',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','options'=>$models, 'name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('描述'),'description',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</div>
			</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="form-title">{t('会员组设置')}</div>
			<div class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('积分下限'),'point',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'settings[point]','value'=>(int)$data['settings']['point']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('投稿设置'),'contribute',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','options'=>array(''=>t('禁止'),'pending'=>t('允许-需要审核'),'publish'=>t('允许-直接发布')),'name'=>'settings[contribute]','value'=>$data['settings']['contribute']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('日投稿数'),'point',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'settings[contributes]','value'=>(int)$data['settings']['contributes'],'min'=>0))}
				</div>
			</div>
			</div>
		</div>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->


<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.loading();
			$.post(action, data, function(msg){

				if ( !msg.state ){
					$(form).find('.submit').button('reset');
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}