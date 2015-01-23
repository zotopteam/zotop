{template 'header.php'}
<div class="side">
	{template 'content/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('字段管理')}</div>
		<div class="position">
			<a href="{u('content/model')}">{t('模型管理')}</a>
			<s class="arrow">></s>
			<a href="{u('content/field/index/'.$modelid)}">{t('字段管理')} : {m('content.model.get',$modelid,'name')}</a>
			<s class="arrow">></s>
			{$title}
		</div>		
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<tbody>
			{loop m('content.field.getfields',$modelid) $f}
			<tr>
				<td class="label">{form::label($f['label'],$f['for'],$f['required'])}</td>
				<td class="input">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</td>
			</tr>
			{/loop}
			</tbody>
		</table>		

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('提交')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			return false;
		}});
	});
</script>

{template 'footer.php'}