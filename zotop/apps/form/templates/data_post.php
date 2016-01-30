{template 'header.php'}

<div class="main">
	<div class="main-header">
		<div class="goback"><a href="{U('form/data/index/'.$formid)}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		{form::field(array('type'=>'hidden','name'=>'formid','value'=>$data['formid'],'required'=>'required'))}

		<div class="container-fluid">
			<div class="form-horizontal">
			{loop $fields $f}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($f['label'], $f['for'], $f['required'])}</div>
				<div class="col-sm-8">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</div>
			</div>
			{/loop}
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