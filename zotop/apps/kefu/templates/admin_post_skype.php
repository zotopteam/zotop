	<div class="form-group">
		{form::label(t('帐号'),'account',true)}
		{form::field(array('type'=>'text','name'=>'account','value'=>$data['account'],'pattern'=>'^[a-z0-9]{6,32}$','required'=>'required'))}
		{form::tips(t('请输入Skype帐号'))}
	</div>
	<div class="form-group">
		{form::label(t('说明'),'text',true)}
		{form::field(array('type'=>'title','name'=>'text','value'=>$data['text'],'style'=>$data['style'],'required'=>'required','maxlength'=>50))}
		{form::tips(t('显示的标题，如 技术支持 客服一 等'))}
	</div>

