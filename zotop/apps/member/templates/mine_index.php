{template 'member/header.php'}

{form class="form-horizontal"}
	{field type="hidden" name="modelid" value="$data.modelid"}
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">{$title}</div>
		</div>
		<div class="panel-body">

			{loop $fields $f}
			<div class="form-group">
				<label for="{$f.for}" class="col-sm-2 control-label {if $f.field.required}required{/if}">{$f.label}</label>
				<div class="col-sm-8">
					{form::field($f.field)}
					{if $f.tips}<div class="help-block">{$f.tips}</div>{/if}
				</div>
			</div>
			{/loop}

		</div><!-- panel-body -->
		<div class="panel-footer">
			{field type="submit" value="t('提交')"}
		</div>
	</div> <!-- panel -->
{/form}

<script type="text/javascript">
	// 登录
	$(function(){
		$('form.form').validate({
			submitHandler:function(form){
				var action = $(form).attr('action');
				var data = $(form).serialize();
				$(form).find('.submit').button('loading');
				$.post(action, data, function(msg){
					$(form).find('.submit').button('reset');
					$.msg(msg);					
				},'json');
			}
		});
	});
</script>

{template 'member/footer.php'}