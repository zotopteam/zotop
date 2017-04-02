{template 'dialog.header.php'}
<div class="main scrollable">

	{form::header()}

			<div class="form-group">
				{form::label(t('IP 地址'),'ip',true)}
				{form::field(array('type'=>'ip','name'=>'ip','value'=>$data['ip'],'maxlength'=>15,'required'=>'required','remote'=>u('system/ipbanned/check/ip','ignore='.$data['ip'])))}
				{form::tips(t('IP地址格式如：202.102.224.68'))}
			</div>
			<div class="form-group">
				{form::label(t('解封时间'),'description',true)}
				{form::field(array('type'=>'datetime','name'=>'expires','value'=>$data['expires'],'min'=>ZOTOP_TIME, 'inline'=>true, 'required'=>'required'))}
			</div>

	{form::footer()}
</div>

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
					msg.onclose = function(){
						parent.location.reload();
					}
				}

				$.msg(msg);
			},'json');
		}});
	});
</script>

{template 'dialog.footer.php'}