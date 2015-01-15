<!-- /字段类型相关参数 -->
<table class="field">
	<tr class="field-extend field-style none">
		<td class="label">{form::label(t('控件样式'),'settings[style]',false)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'settings[style]','value'=>$data['settings']['style']))}
			{form::tips('定义控件的[style]样式，如：width:200px;height:300px;')}
		</td>
	</tr>
	<tr class="field-extend field-notnull none">
		<td class="label">{form::label(t('不能为空'),'notnull',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'notnull','value'=>(int)$data['notnull']))}
		</td>
	</tr>
	<tr class="field-extend field-unique none">
		<td class="label">{form::label(t('值唯一'),'unique',false)}</td>
		<td class="input">			
			{form::field(array('type'=>'bool','name'=>'unique','value'=>(int)$data['unique']))}			
		</td>
	</tr>
	<tr class="field-extend field-post">
		<td class="label">{form::label(t('前台投稿'),'post',false)} <i class="icon icon-help" title="{t('当表单允许前台发布时是否显示并允许录入数据')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'post','value'=>(int)$data['post']))}
			
		</td>
	</tr>
	<tr class="field-extend field-base">
		<td class="label">{form::label(t('基本信息'),'base',false)} <i class="icon icon-help" title="{t('基本信息将显示在添加编辑页面的主要位置')}"></i></td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'base','value'=>$data['base']))}			
		</td>
	</tr>
	<tr class="field-extend field-search none">
		<td class="label">{form::label(t('允许搜索'),'search',false)}</td>
		<td class="input">
			{form::field(array('type'=>'bool','name'=>'search','value'=>(int)$data['search']))}
		</td>
	</tr>
</table>
