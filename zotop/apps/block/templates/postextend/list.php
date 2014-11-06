
<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('显示行数'),'rows',false)}</td>
		<td class="input">
			<div class="input-group">
				{form::field(array('type'=>'number','name'=>'rows','value'=>intval($data['rows']),'min'=>0))}
				<span class="input-group-addon">{t('行')}</span>
			</div>
			{form::tips(t('0表示无固定行数'))}
		</td>
	</tr>

	<tr>
		<td class="label">{form::label(t('字段设置'),'fields',false)}</td>
		<td class="input">
			<table class="controls fields">
			<thead>
			<tr>
				<td class="w50">{t('显示')}</td>
				<td class="w100">{t('字段名称')}</td>
				<td class="w100">
					{t('字段标识')} &nbsp;	<i class="icon icon-help" title="{t('可在模板中调用')}"></i>
				</td>
				<td>{t('设置')}</td>
			</tr>
			</thead>
			<tbody>
				{loop $data['fields'] $k $v}
				<tr>
				{if in_array($v['name'], array('title','url','image','description','createtime'))}
					<td>
						{if $v['name']=='title'}
						<input type="checkbox" class="checkbox" checked disabled>
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
					<td>
						<input type="hidden" name="fields[{$k}][type]" class="text field" value="{$v['type']}">

						{if in_array($v['name'], array('title','description'))}
						<div class="input-group">
							<span class="input-group-addon">{t('长度')}</span>
							<input type="text" name="fields[{$k}][minlength]" class="text number" value="{$v['minlength']}">
							<span class="input-group-addon">~</span>
							<input type="text" name="fields[{$k}][maxlength]" class="text number" value="{$v['maxlength']}">
							<span class="input-group-addon">{t('字')}</span>
						</div>
						{/if}

						{if in_array($v['name'], array('image'))}
						<div class="input-group">
							<span class="input-group-addon">{t('宽高')}</span>
							<input type="text" name="fields[{$k}][image_width]" class="text number" value="{$v['image_width']}">
							<span class="input-group-addon">×</span>
							<input type="text" name="fields[{$k}][image_height]" class="text number" value="{$v['image_height']}">
							<span class="input-group-addon">px</span>								
						</div>

						<span class="inline-radios">
							<label><input type="radio" name="fields[{$k}][image_resize]" value="0" {if $v['image_resize']==0}checked="checked"{/if}>{t('原图')}</label>
							<label><input type="radio" name="fields[{$k}][image_resize]" value="1" {if $v['image_resize']==1}checked="checked"{/if}>{t('缩放')}</label>
							<label><input type="radio" name="fields[{$k}][image_resize]" value="2" {if $v['image_resize']==2}checked="checked"{/if}>{t('裁剪')}</label>
						</span>
						
						<span class="inline-radios">
							<label><input type="radio" name="fields[{$k}][watermark]" value="1" {if $v['watermark']==1}checked="checked"{/if}>{t('水印')}</label>
							<label><input type="radio" name="fields[{$k}][watermark]" value="0" {if $v['watermark']==0}checked="checked"{/if}>{t('无')}</label>
						</span>
						{/if}
					</td>
				{else}
					<td>
						<a href="javascript:void(0)" class="delete"><i class="icon icon-delete"></i></a>
						<input type="hidden" name="fields[{$k}][show]" class="checkbox" value="1" checked>
					</td>
					<td><input type="text" name="fields[{$k}][label]" class="text tiny required" value="{$v['label']}"></td>
					<td><input type="text" name="fields[{$k}][name]" class="text tiny field required custom_field" value="{$v['name']}" pattern="^([a-zA-Z0-9-]|[_]){1,32}$"></td>
					<td></td>
				{/if}
				</tr>
				{/loop}
			</tbody>
			</table>
			<div class="blank"></div>
			<div><a href="javascript:void(0)" class="add"><i class="icon icon-add"></i> <b>{t('添加字段')}</b></a></div>
		</td>
	</tr>
</tbody>
</table>