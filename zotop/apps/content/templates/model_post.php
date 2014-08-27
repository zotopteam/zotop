{template 'header.php'}
<div class="side">
{template 'content/side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">


		<table class="field">
			<caption>{t('基本属性')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('模型'),'id',false)}</td>
				<td class="input">
					<div class="field-text"><b>{$data['id']}</b></div>
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			{if $data['tablename']}
			<tr>
				<td class="label">{form::label(t('内容页模板'),'template',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'template','value'=>$data['template'],'required'=>'required'))}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="label">{form::label(t('描述'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'required'=>'required'))}
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

		{if $data['settingspath']}
			{template $data['settingspath']}
		{/if}


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
{template 'footer.php'}