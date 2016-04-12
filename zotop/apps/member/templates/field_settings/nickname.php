<div class="form-group">
	<div class="col-sm-2 control-label">{form::label(t('占位符'),'settings[placeholder]',false)}</div>
	<div class="col-sm-8">
		{field type="text" name="settings[placeholder]" value="$data.settings.placeholder" placeholder="t('例如这就是占位符')"}
		<div class="help-block">
			{t('定义控件的[placeholder]属性，提供可描述输入字段预期值的提示信息')}
		</div>		
	</div>
</div>