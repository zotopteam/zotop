
<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('行数'),'rows',false)}</td>
		<td class="input">
			<div class="input-group">
				{form::field(array('type'=>'number','name'=>'rows','value'=>intval($data['rows']),'min'=>0))}
				<span class="input-group-addon">{t('行')}</span>
			</div>
			{form::tips(t('0表示无固定行数'))}
		</td>
	</tr>

	<tr>
		<td class="label">{form::label(t('字段'),'fields',false)}</td>
		<td class="input">
			<table class="controls table border">
			<thead>
			<tr>
				<td class="w50 center">{t('显示')}</td>
				<td class="w100">{t('字段名称')}</td>
				<td class="w100">
					{t('字段标识')} &nbsp;	<i class="icon icon-help" title="{t('可在模板中调用')}"></i>
				</td>
				<td class="w50 center">{t('必填')}</td>
				<td>{t('设置')}</td>
			</tr>
			</thead>
			<tbody>
				{loop m('block.block.fieldlist',$data['fields']) $k $v}
				<tr>
					<td class="center">
						{if $v['name']=='title'}
						<input type="checkbox" class="checkbox disabled" checked disabled>
						<input type="hidden" name="fields[{$k}][show]" class="checkbox" value="1" checked>
						{else}
						<input type="checkbox" name="fields[{$k}][show]" class="checkbox" value="1" {if $v['show']}checked="checked"{/if}>
						{/if}
					</td>
					<td>
						<input type="text" name="fields[{$k}][label]" class="text tiny" value="{$v['label']}">
					</td>
					<td>
						<input type="hidden" name="fields[{$k}][name]" class="text" value="{$v['name']}">
						<input type="text" name="showname" class="text tiny" value="{$v['name']}" disabled>
					</td>
					<td class="center">
						{if $v['name']=='title'}
						<input type="checkbox" class="checkbox disabled" checked disabled>
						<input type="hidden" name="fields[{$k}][required]" class="checkbox" value="required" checked>
						{else}
						<input type="checkbox" name="fields[{$k}][required]" class="checkbox" value="required" {if $v['required']}checked="checked"{/if}>
						{/if}						
					</td>
					<td>
						{if in_array($v['name'], array('title','description'))}
						<div class="input-group">
							<span class="input-group-addon">{t('长度')}</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][minlength]','value'=>$v['minlength']))}
							<span class="input-group-addon">-</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][maxlength]','value'=>$v['maxlength']))}
							<span class="input-group-addon">{t('字')}</span>
						</div>
						{/if}

						{if in_array($v['name'], array('image'))}
							<div class="input-group">
								<span class="input-group-addon">{t('宽高')}</span>
								{form::field(array('type'=>'number','name'=>'fields['.$k.'][image_width]','value'=>$v['image_width']))}
								<span class="input-group-addon">×</span>
								{form::field(array('type'=>'number','name'=>'fields['.$k.'][image_height]','value'=>$v['image_height']))}
								<span class="input-group-addon">px</span>								
							</div>						

							{form::field(array('type'=>'radio','options'=>array(0=>t('原图'),1=>t('缩放'),2=>t('裁剪')),'name'=>'fields['.$k.'][image_resize]','value'=>$v['image_resize']))}

							{form::field(array('type'=>'radio','options'=>array(1=>t('水印'),0=>t('无')),'name'=>'fields['.$k.'][watermark]','value'=>$v['watermark']))}

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
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('推送'),'commend',false)}</td>
		<td class="input">
			{form::field(array('type'=>'radio','options'=>array(0=>t('禁止推送'),1=>t('允许推送')),'name'=>'commend','value'=>$data['commend']))}
		</td>
	</tr>

</tbody>
</table>