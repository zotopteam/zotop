{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form class="form-horizontal"}
	<div class="main-body scrollable">
		{field type="hidden" name="referer" value="request::referer()"}
		{field type="hidden" name="modelid" value="$data['modelid']"}

		<div class="container-fluid">
			
			{loop $fields $f}
			<div class="form-group">
				<label for="{$f.for}" class="col-sm-2 control-label {if $f.field.required}required{/if}">
					{$f.label}
				</label>
				<div class="col-sm-8">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</div>
			</div>
			{/loop}

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