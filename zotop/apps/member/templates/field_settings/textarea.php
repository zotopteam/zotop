<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('占位符'),'settings[placeholder]',false)}</div>
	<div class="col-sm-8">
		{field type="text" name="settings[placeholder]" value="$data.settings.placeholder" placeholder="t('例如这就是占位符')"}
		<div class="help-block">
			{t('定义控件的[placeholder]属性，提供可描述输入字段预期值的提示信息')}
		</div>		
	</div>
</div>

<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('字符长度'),'length',false)}</div>
	<div class="col-sm-5">
		<div class="input-group">
			{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>$data['settings']['minlength'],'min'=>0,'placeholder'=>t('最小长度')))}
			<span class="input-group-addon">-</span>
			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength'],'min'=>1,'required'=>'required','placeholder'=>t('最大长度')))}
		</div>
	</div>		
</div>

<div class="form-group field-extend field-style">
	<div class="col-sm-2 control-label">{form::label(t('可见行数'),'settings[rows]',false)}</div>
	<div class="col-sm-3">
		{form::field(array('type'=>'number','name'=>'settings[rows]','value'=>$data['settings']['rows'],'min'=>1))}
		{form::tips('定义文本区内的可见行数')}
	</div>
</div>

<div class="form-group field-extend field-search">
	<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
	<div class="col-sm-10">
		{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
	</div>
</div>

