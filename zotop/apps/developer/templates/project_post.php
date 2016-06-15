{template 'header.php'}
<div class="main">
	<div class="main-header">
		{if $app.id}
		<div class="goback"><a href="{U('developer/project/index')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		{else}
		<div class="goback"><a href="{U('developer')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		{/if}

		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">

		{form::header()}
		<div class="container-fluid">
		<div class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['id'],'id',true)}</div>
				<div class="col-sm-8">
					{if empty($app['id'])}
					{form::field(array('type'=>'text','name'=>'id','value'=>$app['id'],'required'=>'required'))}
					{form::tips(t('应用的唯一标示符，勿与其它模块重复，只允许英文、数字'))}
					{else}
					{form::field(array('type'=>'hidden','name'=>'id','value'=>$app['id']))}
					{form::field(array('type'=>'text','name'=>'id','value'=>$app['id'],'disabled'=>'disabled'))}
					{form::tips(t('应用的唯一标识符不能修改，如果想修改请重建一个新应用'))}
					{/if}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['dir'],'dir',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'dir','value'=>$app['dir'],'required'=>'required'))}
					{form::tips(t('应用的目录名称，勿与其它模块重复，只允许英文、数字'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['name'],'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$app['name'],'required'=>'required'))}
					{form::tips(t('应用名称，请为您的应用起一个准确的名称'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['type'],'type',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'radio','options'=>array('module'=>t('模块'),'plugin'=>t('插件')),'name'=>'type','value'=>$app['type']))}
					{form::tips(t('具有完整功能的应用为模块，为其它应用服务的为插件'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['tables'],'tables',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'tables','value'=>$app['tables']))}
					{form::tips(t('多个数据表之间用英文逗号隔开，不含前缀'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['dependencies'],'dependencies',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'dependencies','value'=>$app['dependencies']))}
					{form::tips(t('多个依赖应用的标识之间用英文逗号隔开'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['description'],'description',true)}</div>
				<div class="col-sm-8">
				{form::field(array('type'=>'textarea','name'=>'description','value'=>$app['description'],'required'=>'required'))}
				{form::tips(t('请详细填写您的应用的描述或者说明'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($attrs['version'],'version',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'number','name'=>'version','value'=>$app['version']))}
					{form::tips(t('当前版本号，只允许使用数字'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('开发者信息'),'author',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'author','value'=>$app['author'],'placeholder'=>$attrs['author']))}
					<div class="blank"></div>
					{form::field(array('type'=>'email','name'=>'email','value'=>$app['email'],'placeholder'=>$attrs['email']))}
					<div class="blank"></div>
					{form::field(array('type'=>'url','name'=>'homepage','value'=>$app['homepage'],'placeholder'=>$attrs['homepage']))}
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