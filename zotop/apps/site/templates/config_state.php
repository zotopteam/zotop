{template 'header.php'}

{template 'site/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form class="form-horizontal"}
	<div class="main-body scrollable">
		
		<div class="container-fluid">

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('关闭网站'),'closed',false)}</div>
				<div class="col-sm-10">
					{form::field(array('type'=>'bool','name'=>'closed','value'=>c('site.closed')))}
					{form::tips(t('网站关闭时不影响网站后台访问并且管理员登陆系统之后可以访问网站'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('关闭原因'),'closed_reason',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'closed_reason','value'=>c('site.closed_reason')))}
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>
{template 'footer.php'}