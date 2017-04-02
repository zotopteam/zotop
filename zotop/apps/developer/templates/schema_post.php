{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}
		<div class="container-fluid">
		<div class="form-horizontal">

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required','pattern'=>'[a-z0-9_]+','remote'=>u('developer/schema/checkfield/'.$tablename.'/'.$data['name'])))}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('类型'),'type',false)}/{form::label(t('长度'),'length',false)}</div>
				<div class="col-sm-4">
					{form::field(array('type'=>'select','name'=>'type','value'=>$data['type'],'options'=>$types,'class'=>'short'))}
				</div>
				<div class="col-sm-4">
					{form::field(array('type'=>'text','name'=>'length','value'=>$data['length'],'class'=>'short','placeholder'=>t('字段长度 或者 字段长度,精度'),'pattern'=>'[0-9,]+'))}
				</div>
			</div>


			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('默认值'),'default',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'default','value'=>$data['default']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('自增'),'autoinc',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'autoinc','value'=>$data['autoinc']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('UNSIGNED'),'unsigned',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'unsigned','value'=>$data['unsigned']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('NOT NULL'),'notnull',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'notnull','value'=>$data['notnull']))}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('注释'),'comment',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'comment','value'=>$data['comment']))}
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('位于'),'position',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','name'=>'position','value'=>$data['position'],'options'=>$position,'class'=>'short'))}
				</div>
			</div>
		</div>
		</div>
	{form::footer()}

</div>

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	// 表单验证
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action,data,function(msg){
				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'dialog.footer.php'}