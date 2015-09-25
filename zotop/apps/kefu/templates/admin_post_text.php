	{form::field(array('type'=>'hidden','name'=>'account','value'=>''))}
	<div class="form-group">
		{form::label(t('内容'),'text',true)}
		{form::field(array('type'=>'textarea','name'=>'text','value'=>$data['text'],'rows'=>'5','required'=>'required'))}
		{form::tips(t('请输入文字内容'))}
	</div>