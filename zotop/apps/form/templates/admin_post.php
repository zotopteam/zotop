{template 'header.php'}
<div class="side">
	{template 'form/admin_side.php'}
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
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required','remote'=>U('form/admin/check/name/'.$data['name'])))}
					{form::tips('当前表单的名称，如“留言板”、“反馈”等')}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('数据表名'),'table',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'table','value'=>$data['table'],'minlength'=>4,'maxlength'=>32,'required'=>'required','remote'=>U('form/admin/checktable/'.$data['table'])))}
					{form::tips('数据库中的数据表名称，只能由小写英文字母、数字和下划线组成，不含数据表前缀，如：“feedback”')}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('描述'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('前台列表页面'),'settings[list]',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'settings[list]','value'=>(int)$data['settings']['list']))}
				</td>
			</tr>
			<tr class="options-list {if $data['settings']['list']==0}none{/if}">
				<td class="label">{form::label(t('前台列表模板'),'settings[listtemplate]',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'settings[listtemplate]','value'=>$data['settings']['listtemplate'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('前台详细页面'),'settings[detail]',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'settings[detail]','value'=>(int)$data['settings']['detail']))}
				</td>
			</tr>
			<tr class="options-detail {if $data['settings']['detail']==0}none{/if}">
				<td class="label">{form::label(t('前台详细模板'),'settings[detailtemplate]',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'settings[detailtemplate]','value'=>$data['settings']['detailtemplate'],'required'=>'required'))}

				</td>
			</tr>								
		</tbody>
	</table>
	<table class="field">
		<tbody>
		<tr>
			<td class="label">{form::label(t('前台发布数据'),'settings[post]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'bool','name'=>'settings[post]','value'=>(int)$data['settings']['post']))}
			</td>
		</tr>

		<tr class="options-post {if $data['settings']['post']==0}none{/if}">
			<td class="label">{form::label(t('前台发布模板'),'settings[posttemplate]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'template','name'=>'settings[posttemplate]','value'=>$data['settings']['posttemplate'],'required'=>'required'))}
			</td>
		</tr>			

		<tr class="options-post {if $data['settings']['post']==0}none{/if}">
			<td class="label">{form::label(t('开启验证码'),'settings[post_captcha]',false)}</td>
			<td class="input">
				{form::field(array('type'=>'bool','name'=>'settings[post_captcha]','value'=>$data['settings']['post_captcha']))}
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

	$(function(){
		$('[name="settings[list]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-list').show();
			}else{
				$('.options-list').hide();
			}
		});

		$('[name="settings[detail]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-detail').show();
			}else{
				$('.options-detail').hide();
			}
		});

		$('[name="settings[post]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-post').show();
			}else{
				$('.options-post').hide();
			}
		});					
	})
</script>

{template 'footer.php'}