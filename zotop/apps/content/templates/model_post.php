{template 'dialog.header.php'}


{form::header()}

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('模型标识'),'id',true)}</td>
				<td class="input">
					{if $data.id}
					<div class="field-text"><b>{$data['id']}</b></div>
					{else}

					{form::field(array('type'=>'text','name'=>'id','value'=>$data['id'],'maxlength'=>32,'required'=>'required'))}

					{form::tips(t('只允许因为字符和数字，最大长度32位'))}

					{/if}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('模型名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('模型描述'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'required'=>'required'))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('内容页'),'template',false)}</td>
				<td class="input">
					
					{form::field(array('type'=>'bool','name'=>'istemplate','value'=>($data['template'] or empty($data['id']) ? true : false)))}
					
					<i class="icon icon-help" data-placement="left" title="{t('内容页指的是内容的详细页面，有的模型如 “链接” 模型点击后直接打开链接，并不具有内容页面')}"></i>

					<div class="blank"></div>
					<div class="options-template">					
						{form::field(array('type'=>'template','name'=>'template','value'=>$data['template'],'required'=>'required'))}
					</div>

					<script type="text/javascript">
					$(function(){
						$('[name="istemplate"]').val() == 1 ? $('.options-template').show() : $('.options-template').hide();

						$('[name="istemplate"]').on('click',function(){
							$(this).val() == 1  ? $('.options-template').show() : $('.options-template').hide();
						});						
					});
					</script>
				</td>
			</tr>
			</tbody>
		</table>


{form::footer()}


<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			$.loading();
			$(form).find('.submit').disable(true);
			$.post($(form).attr('action'), $(form).serialize(), function(msg){
				if( !msg.state ){
					$(form).find('.submit').disable(false);
				}				
				$.msg(msg);
			},'json');
		}});
	});
</script>

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){
				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}