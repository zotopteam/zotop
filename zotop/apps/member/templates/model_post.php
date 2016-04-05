{template 'header.php'}

{template 'member/admin_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form class="form-horizontal"}
	<div class="main-body scrollable">

		<div class="container-fluid">
			<div class="form-title">{t('基本信息')}</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</div>
			</div>
			{if empty($data['id'])}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('标识'),'id',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'minlength'=>5,'maxlength'=>32,'required'=>'required'))}
					{form::tips('只能由英文字母、数字和下划线组成')}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('数据表名'),'tablename',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'tablename','value'=>$data['tablename'],'minlength'=>4,'maxlength'=>32,'required'=>'required'))}
					{form::tips('只能由英文字母、数字和下划线组成，不含数据表前缀')}
				</div>
			</div>
			{/if}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('描述'),'description',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('禁用'),'disabled',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
				</div>
			</div>

			<div class="form-title">{t('高级设置')}</div>

			<div class="form-group hidden">
				<div class="col-sm-2 control-label">{form::label(t('允许注册'),'settings[register]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'settings[register]','value'=>$data['settings']['register']))}
				</div>
			</div>

			<div class="form-group hidden">
				<div class="col-sm-2 control-label">{form::label(t('开启验证码'),'settings[register_captcha]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'settings[register_captcha]','value'=>$data['settings']['register_captcha']))}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('默认点数'),'settings[point]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'settings[point]','value'=>(int)$data['settings']['point']))}
					<div class="help-block">{t('用户注册时候默认的金钱点数')}</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('默认金额'),'settings[amount]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'settings[amount]','value'=>(int)$data['settings']['amount']))}
					<div class="help-block">{t('用户注册时候默认的金钱数额')}</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('默认用户组'),'settings[groupid]',true)}</div>
				<div class="col-sm-8">
					{if $data['id']}
					{form::field(array('type'=>'select','options'=>$groups,'name'=>'settings[groupid]','value'=>$data['settings']['groupid'],'required'=>'required'))}
					<div class="help-block">{t('用户注册时候默认的会员组')}</div>
					{else}
					{form::field(array('type'=>'input','name'=>'settings[groupid]','value'=>'','required'=>'required'))}
					<div class="help-block">{t('当前会员模型的默认用户组名称，保存后会自动创建默认会员组')}</div>
					{/if}
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
	{/form}

</div><!-- main -->

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