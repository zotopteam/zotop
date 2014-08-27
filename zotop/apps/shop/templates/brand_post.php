{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('标志'),'logo',false)}</td>
				<td class="input">
					{form::field(array('type'=>'image','name'=>'logo','value'=>$data['logo'],'overwrite'=>1,'image_resize'=>2,'image_width' => c('shop.brand_width'),'image_height' => c('shop.brand_height')))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('网址'),'url',false)}</td>
				<td class="input">
					{form::field(array('type'=>'url','name'=>'url','value'=>$data['url']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('描述'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
				</td>
			</tr>
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}


<!-- 验证提交 -->
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){

		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				if ( !msg.state ){
					$(form).find('.submit').disable(false);
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'footer.php'}