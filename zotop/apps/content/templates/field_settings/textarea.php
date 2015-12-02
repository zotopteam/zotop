
<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('字符长度'),'length',false)}</div>
	<div class="col-sm-10">
		
		<div class="input-group">
				<span class="input-group-addon">{t('最小长度')}</span>

				{form::field(array('type'=>'number','name'=>'settings[minlength]','value'=>intval($data['settings']['minlength']),'min'=>0))}
		</div>
		 -
		<div class="input-group">
			<span class="input-group-addon">{t('最大长度')}</span>

			{form::field(array('type'=>'number','name'=>'settings[maxlength]','value'=>$data['settings']['maxlength']))}
		</div>
	</div>
</div>


<!-- /字段类型相关参数 -->

	<tr class="field-extend field-style">
		<div class="col-sm-2 control-label">{form::label(t('控件样式'),'settings[style]',false)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
			{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
		</div>
	</div>
	<tr class="field-extend field-search">
		<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</div>
	</div>

