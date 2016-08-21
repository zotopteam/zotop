

	<div class="form-group">
		<div class="col-sm-1 control-label">{form::label(t('行数'),'rows',false)}</div>
		<div class="col-sm-4">
			<div class="input-group">
				{form::field(array('type'=>'number','name'=>'rows','value'=>intval($data['rows']),'min'=>0))}
				<span class="input-group-addon">{t('行')}</span>
			</div>
			{form::tips(t('0表示无固定行数'))}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-1 control-label">{form::label(t('字段'),'fields',false)}</div>
		<div class="col-sm-11">
			<table class="table table-border table-nowrap table-control fieldlists">
			<thead>
			<tr>
				<td class="text-center">{t('显示')}</td>
				<td class="w100" width="15%">{t('名称')}</td>
				<td class="w100" width="15%">
					{t('标识')} &nbsp;	<i class="icon icon-help" title="{t('可在模板的loop循环中使用')}"></i>
				</td>
				<td class="text-center">{t('必填')}</td>
				<td width="15%">{t('类型')}</td>
				<td>{t('设置')}</td>
			</tr>
			</thead>
			<tbody>
				{loop m('block.block.fieldlist',$data['fields']) $k $v}
				<tr data-key="{$k}" data-index="{$n}">
					<td class="text-center">
						{if in_array($v['name'], array('title'))}
						<input type="checkbox" class="disabled" checked disabled>
						<input type="hidden" name="fields[{$k}][show]" value="1" checked>
						{elseif in_array($v['name'], array('url','image','description','time'))}
						<input type="checkbox" name="fields[{$k}][show]" value="1" {if $v['show']}checked="checked"{/if}>
						{else}
						<input type="hidden" name="fields[{$k}][show]" value="1" checked>
						<a href="javascript:;" onclick="field.delete(this)"><i class="fa fa-times"></i></a>
						{/if}
					</td>
					<td>
						<input type="text" name="fields[{$k}][label]" class="form-control text required" value="{$v['label']}">
					</td>
					<td>
						{if in_array($v['name'], array('title','url','image','description','time'))}
						<input type="hidden" name="fields[{$k}][name]" class="form-control" value="{$v['name']}">
						<input type="text" name="showname" class="form-control text" value="{$v['name']}" disabled>
						{else}
						<input type="text" name="fields[{$k}][name]" class="form-control required" value="{$v['name']}">
						{/if}
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
						{if in_array($v['name'], array('title','url','image','description','time'))}
							{form::field(array('type'=>'hidden','name'=>'fields['.$k.'][type]','value'=>$v['type']))}
							{form::field(array('type'=>'select','options'=>m('block.block.fieldtypes'),'value'=>$v['type'],'disabled'=>'disabled'))}							
						{else}
							{form::field(array('type'=>'select','options'=>m('block.block.fieldtypes'),'name'=>'fields['.$k.'][type]','value'=>$v['type'],'onchange'=>'field.changetype(this)'))}
						{/if}						
					</td>
					<td>
						{if in_array($v['type'], array('title','text','textarea'))}
						<div class="input-group">
							<span class="input-group-addon">{t('长度')}</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][minlength]','value'=>$v['minlength'],'placeholder'=>t('最小')))}
							<span class="input-group-addon">-</span>
							{form::field(array('type'=>'number','name'=>'fields['.$k.'][maxlength]','value'=>$v['maxlength'],'placeholder'=>t('最大')))}
							<span class="input-group-addon">{t('字')}</span>
						</div>
						{/if}

						{if in_array($v['type'], array('image','images'))}
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
					</td>
				</tr>
				{/loop}
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">
						<a class="btn btn-primary btn-sm" href="javascript:;" onclick="field.add(this)"><i class="fa fa-plus fa-fw"></i> <b>{t('添加字段')}</b></a>
					</td>					
				</tr>
			</tfoot>
			</table>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-1 control-label">{form::label(t('推送'),'commend',false)}</div>
		<div class="col-sm-8">
			{form::field(array('type'=>'radio','options'=>array(0=>t('禁止推送'),1=>t('允许推送')),'name'=>'commend','value'=>$data['commend']))}
		</div>
	</div>

	<script>
	var field = {}
		field.add = function() {
			var post = $('form.form').serialize();
			$.post("{U('block/admin/postextend/add')}",post,function(result){
				$('.extend').html(result);
			});			
		}

		field.changetype = function(obj){
			var post = $('form.form').serialize();
			$.post("{U('block/admin/postextend')}",post,function(result){
				$('.extend').html(result);
			});			
		}

		field.delete = function(obj){
			$(obj).parents('tr').remove();
		}
	</script>

