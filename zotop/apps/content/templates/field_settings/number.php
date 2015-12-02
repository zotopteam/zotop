
	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('数值范围'),'length',false)}</div>
		<div class="col-sm-10">
			<div class="input-group">
				<span class="input-group-addon">{t('最小值')}</span>
				{form::field(array('type'=>'number','name'=>'settings[min]','value'=>$data['settings']['min']))}
			</div>
			 -
			<div class="input-group">
				<span class="input-group-addon">{t('最大值')}</span>
				{form::field(array('type'=>'number','name'=>'settings[max]','value'=>$data['settings']['max']))}
			</div>			
			
		</div>
	</div>
