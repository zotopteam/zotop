

	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('行数'),'rows',false)}</div>
		<div class="col-sm-4">
			<div class="input-group">
				{form::field(array('type'=>'number','name'=>'rows','value'=>intval($data['rows']),'min'=>0))}
				<span class="input-group-addon">{t('行')}</span>
			</div>
			{form::tips(t('0表示无固定行数'))}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('字段'),'fields',false)}</div>
		<div class="col-sm-10">
			<table class="table table-border table-nowrap table-control">
			<thead>
			<tr>
				<td class="text-center">{t('显示')}</td>
				<td class="w100" width="15%">{t('字段名称')}</td>
				<td class="w100" width="15%">
					{t('字段标识')} &nbsp;	<i class="icon icon-help" title="{t('可在模板的loop循环中使用')}"></i>
				</td>
				<td class="text-center">{t('必填')}</td>
				<td>{t('设置')}</td>
			</tr>
			</thead>
			<tbody>
				{loop m('block.block.fieldlist',$data['fields']) $k $v}
				<tr>
					<td class="text-center">
						{if in_array($v['name'], array('title'))}
						<input type="checkbox" class="disabled" checked disabled>
						<input type="hidden" name="fields[{$k}][show]" value="1" checked>
						{else}
						<input type="checkbox" name="fields[{$k}][show]" value="1" {if $v['show']}checked="checked"{/if}>
						{/if}
					</td>
					<td>
						<input type="text" name="fields[{$k}][label]" class="form-control text" value="{$v['label']}">
					</td>
					<td>
						<input type="hidden" name="fields[{$k}][name]" class="form-control text" value="{$v['name']}">
						<input type="text" name="showname" class="form-control text" value="{$v['name']}" disabled>
					</td>
					<td class="text-center">
						{if in_array($v['name'], array('title'))}
						<input type="checkbox" class="disabled" checked disabled>
						<input type="hidden" name="fields[{$k}][required]"  value="required" checked>
						{else}
						<input type="checkbox" name="fields[{$k}][required]"  value="required" {if $v['required']}checked="checked"{/if}>
						{/if}						
					</td>
					<td>
						{if in_array($v['name'], array('title','description'))}
						<div class="input-group">
							<span class="input-group-addon">{t('长度')}</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][minlength]','value'=>$v['minlength'],'placeholder'=>t('最小')))}
							<span class="input-group-addon">-</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][maxlength]','value'=>$v['maxlength'],'placeholder'=>t('最大')))}
							<span class="input-group-addon">{t('字')}</span>
						</div>
						{/if}

						{if in_array($v['name'], array('image'))}
							<div class="row">
								<div class="col-lg-6">
									<div class="input-group">
										<span class="input-group-addon">{t('宽高')}</span>
										{form::field(array('type'=>'number','name'=>'fields['.$k.'][image_width]','value'=>$v['image_width'],'placeholder'=>t('宽')))}
										<span class="input-group-addon">×</span>
										{form::field(array('type'=>'number','name'=>'fields['.$k.'][image_height]','value'=>$v['image_height'],'placeholder'=>t('高')))}
										<span class="input-group-addon">px</span>								
									</div>										
								</div>
								<div class="col-lg-3">
									{form::field(array('type'=>'select','options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪')),'name'=>'fields['.$k.'][image_resize]','value'=>$v['image_resize']))}
								</div>
								<div class="col-lg-3">
									{form::field(array('type'=>'select','options'=>array(1=>t('有水印'),0=>t('无水印')),'name'=>'fields['.$k.'][watermark]','value'=>$v['watermark']))}
								</div>
							</div>
						{/if}
						
						{if in_array($v['name'], array('c1','c2','c3','c4','c5'))}
							{form::field(array('type'=>'select','options'=>m('block.block.fieldtypes'),'name'=>'fields['.$k.'][type]','value'=>$v['type']))}
						{else}
							{form::field(array('type'=>'hidden','name'=>'fields['.$k.'][type]','value'=>$v['type']))}
						{/if}
					</td>
				</tr>
				{/loop}
			</tbody>
			</table>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">{form::label(t('推送'),'commend',false)}</div>
		<div class="col-sm-8">
			{form::field(array('type'=>'radio','options'=>array(0=>t('禁止推送'),1=>t('允许推送')),'name'=>'commend','value'=>$data['commend']))}
		</div>
	</div>

