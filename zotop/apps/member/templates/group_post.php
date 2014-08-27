{template 'header.php'}
<div class="side">
	{template 'member/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<caption>{t('基本信息')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('模型'),'modelid',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','options'=>$models, 'name'=>'modelid','value'=>$data['modelid'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('描述'),'description',true)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>
			</tbody>
		</table>
		<table class="field">
			<caption>{t('会员组设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('积分下限'),'point',false)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'settings[point]','value'=>(int)$data['settings']['point']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('投稿设置'),'contribute',false)}</td>
				<td class="input">
					{form::field(array('type'=>'select','options'=>array(''=>t('禁止'),'pending'=>t('允许-需要审核'),'publish'=>t('允许-直接发布')),'name'=>'settings[contribute]','value'=>$data['settings']['contribute']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('日投稿数'),'point',false)}</td>
				<td class="input">
					{form::field(array('type'=>'number','name'=>'settings[contributes]','value'=>(int)$data['settings']['contributes'],'min'=>0))}
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
</script>

{template 'footer.php'}