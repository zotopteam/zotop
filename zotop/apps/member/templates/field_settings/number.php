
	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('数值范围'),'length',false)}</div>
		<div class="col-sm-4">
			<div class="input-group">
				{form::field(array('type'=>'number','name'=>'settings[min]','value'=>$data['settings']['min'],'placeholder'=>t('最小值')))}
				<span class="input-group-addon">-</span>
				{form::field(array('type'=>'number','name'=>'settings[max]','value'=>$data['settings']['max'],'placeholder'=>t('最大值')))}
			</div>			
			
		</div>
	</div>
