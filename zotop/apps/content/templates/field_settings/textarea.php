
<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('字符长度'),'length',false)}</div>
	<div class="col-sm-5">
		<div class="input-group">
			{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>$data['settings']['minlength'],'min'=>0,'placeholder'=>t('最小长度')))}
			<span class="input-group-addon">-</span>
			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'max'=>255,'min'=>1,'required'=>'required','placeholder'=>t('最大长度')))}
		</div>
	</div>		
</div>


<!-- /字段类型相关参数 -->

<div class="form-group field-extend field-style">
	<div class="col-sm-2 control-label">{form::label(t('控件样式'),'settings[style]',false)}</div>
	<div class="col-sm-8">
		{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
		{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
	</div>
</div>

<div class="form-group field-extend field-search">
	<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
	<div class="col-sm-10">
		{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
	</div>
</div>

