

	<tr class="field-extend field-source">
		<div class="col-sm-2 control-label">{form::label(t('关键词提取'),'settings[data-source]',true)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'text','name'=>'settings[data-source]','value'=>($data['settings']['data-source'] ? $data['settings']['data-source'] : 'title,content'),'required'=>'required'))}
			{form::tips('关键词提取来源字段名，一般从标题和内容(title,content)进行提取')}
		</div>
	</div>
