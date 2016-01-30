<!-- /字段类型相关参数 -->

	<tr class="field-extend field-notnull">
		<div class="col-sm-2 control-label">{form::label(t('不能为空'),'notnull',false)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}
		</div>
	</div>
	<tr class="field-extend field-unique">
		<div class="col-sm-2 control-label">{form::label(t('值唯一'),'unique',false)}</div>
		<div class="col-sm-10">			
			{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}			
		</div>
	</div>
	<tr class="field-extend field-post">
		<div class="col-sm-2 control-label">{form::label(t('前台投稿'),'post',false)} <i class="icon icon-help" title="{t('当表单允许前台发布时是否显示并允许录入数据')}"></i></div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'post','value'=>$data['post']))}
			
		</div>
	</div>
	<tr class="field-extend field-base">
		<div class="col-sm-2 control-label">{form::label(t('基本信息'),'base',false)} <i class="icon icon-help" title="{t('基本信息将显示在添加编辑页面的主要位置')}"></i></div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'base','value'=>$data['base']))}			
		</div>
	</div>
	<tr class="field-extend field-search">
		<div class="col-sm-2 control-label">{form::label(t('允许搜索'),'search',false)}</div>
		<div class="col-sm-10">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</div>
	</div>

