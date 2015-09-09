{template 'dialog.header.php'}

	{form::header()}
		<div class="container-fluid">
			<div class="form-group">
				<div class="form-label">{form::label(t('敏感词'),'word',true)}</div>
				<div class="form-input">
					{form::field(array('type'=>'text','name'=>'word','value'=>$data['word'],'class'=>'medium','maxlength'=>30,'required'=>'required','remote'=>u('system/badword/check/word','ignore='.$data['word'])))}
					{form::tips(t('敏感词支持正则模式'))}
				</div>
			</div>
			<div class="form-group">
				<div class="form-label">{form::label(t('替换词'),'replace',false)}</div>
				<div class="form-input">
					{form::field(array('type'=>'text','name'=>'replace','value'=>$data['replace'],'class'=>'medium','maxlength'=>30))}
					{form::tips(t('替换词语为空则默认替换为***'))}
				</div>
			</div>
			<div class="form-group">
				<div class="form-label">{form::label(t('级别'),'level',false)}</div>
				<div class="form-input">
					{form::field(array('type'=>'radio','name'=>'level','value'=>$data['level'],'options'=>array(0=>t('一般，用替换词替换'),1=>t('危险，直接去除')),'column'=>1))}
				</div>
			</div>
		</div>
	{form::footer()}

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