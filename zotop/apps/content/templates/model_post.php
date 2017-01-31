{template 'header.php'}
{template 'content/admin_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title} {if $data.label}: {$data.label}{/if}</div>
		<div class="breadcrumb hidden">
			<li><a href="{u('content/model')}">{t('模型管理')}</a></li>
			<li><a href="{u('content/field/index/'.$data.modelid)}">{t('字段管理')} : {m('content.model.get',$data.modelid,'name')}</a></li>
			<li>{$title} {if $data.label}: {$data.label}{/if}</li>
		</div>
	</div><!-- main-header -->
	
	{form}
	<div class="main-body scrollable">
		<div class="container-fluid">	
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('模型名称'),'name',true)}</div>
					<div class="col-sm-8">
						<div class="input-group">
							<span class="input-group-btn">
								{field type="icon" name="icon" value="$data.icon"}
							</span>
							{field type="text" name="name" value="$data.name" required="required"}
						</div>

						{form::tips(t('可读名称，如： 页面、文章、产品、下载'))}
					</div>			
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('模型标识'),'id',true)}</div>
					<div class="col-sm-8">
						{if $data.id}
							{field type="text" name="id" value="$data.id" disabled="disabled"}						
						{else}						
							{field type="text" name="id" value="$data.id" maxlength="32" identifier="true" required="required" remote="U('content/model/check/id')"}
						{/if}
						
						{form::tips(t('模型唯一标识，如： page、article，只允许英文字符和数字，最大长度32位'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('模型描述'),'description',true)}</div>
					<div class="col-sm-8">
						{field type="textarea" name="description" value="$data.description" required="required"}
						{form::tips(t('模型说明，最大255个字符'))}
					</div>				
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('内容页模板'),'template',false)}</div>
					<div class="col-sm-8">
						{field type="template" name="template" value="$data.template"}
						{form::tips(t('内容的详细页面模板，如果没有内容页（如“链接”模型）则无需填写此项'))}
					</div>				
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{field type="submit" value="t('保存')"}
		{field type="cancel" value="t('取消')"}
	</div><!-- main-footer -->

	{/form}

</div><!-- main -->

<script type="text/javascript">

	$(function(){
		
		$.validator.addMethod("identifier", function(value, element) {
			return this.optional(element) || /^[a-z][a-z0-9]{1,31}$/.test(value);
		}, "{t('2-32位长度的英文字符和数字，且不能以数字开头')}");

		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').prop('disabled',true);
			$.post(action, data, function(msg){
				if( !msg.state ){
					$(form).find('.submit').prop('disabled',false);
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'footer.php'}