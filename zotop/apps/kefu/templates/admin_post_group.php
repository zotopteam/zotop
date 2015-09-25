	{form::field(array('type'=>'hidden','name'=>'account','value'=>''))}
	<div class="form-group">
		{form::label(t('组名'),'text',true)}
		{form::field(array('type'=>'title','name'=>'text','value'=>$data['text'],'style'=>$data['style'],'required'=>'required','maxlength'=>50))}
		{form::tips(t('请输入分组名称'))}
	</div>

