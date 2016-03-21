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
			
			{form::field(array('type'=>'hidden','name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('描述'),'description',false)}</div>
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
			<div class="form-title">{t('会员组设置')}</div>

			{loop member_api::group_settings($data) $f}
			<div class="form-group">
				<label class="col-sm-2 control-label {if $f.field.required}required{/if}" for="{$f.field.name}">{$f.label}</label>
				<div class="{if $f.colclass}{$f.colclass}{else}col-sm-8{/if}">
					{form::field($f.field)}
					{if $f.tips}
					<div class="help-block">{$f.tips}</div>			
					{/if}
				</div>
			</div>
			{/loop}

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