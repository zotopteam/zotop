{template 'header.php'}

{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="{u('content/field/index/'.$modelid)}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>

		<div class="title">{$title} : {m('content.model.get',$modelid,'name')}</div>
		<div class="breadcrumb hidden">
			<li><a href="{u('content/model')}">{t('模型管理')}</a></li>
			<li><a href="{u('content/field/index/'.$modelid)}">{t('字段管理')} : {m('content.model.get',$modelid,'name')}</a></li>
			<li>{$title}</li>
		</div>		
	</div><!-- main-header -->
	
	{form::header()}
	<div class="main-body scrollable">
		
		<div class="container-fluid">
		<div class="form-horizontal">			
			
			{loop m('content.field.getfields',$modelid) $f}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label($f['label'],$f['for'],$f['required'])}</div>
				<div class="col-sm-10">
					{form::field($f['field'])}
					{form::tips($f['tips'])}
				</div>
			</div>
			{/loop}

		</div>
		</div>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('提交')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->

	{form::footer()}
</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			return false;
		}});
	});
</script>

{template 'footer.php'}