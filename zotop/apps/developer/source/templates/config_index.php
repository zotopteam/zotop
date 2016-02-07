{template 'header.php'}

{template '[app.id]/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>返回</span></a></div>
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form method="post"}
	<div class="main-body scrollable">
		<div class="container-fluid">
			<div class="form-horizontal">
				
				<!-- 表单主体 begin -->

				<!-- 表单主体 end -->

			</div> <!-- form-horizontal -->
		</div> <!-- container-fluid -->
	</div><!-- main-body -->
	<div class="main-footer">
		{field type="submit" value="t('保存')"}
	</div><!-- main-footer -->
	{/form}
</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data   = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>

{template 'footer.php'}