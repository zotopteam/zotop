{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::field(array('type'=>'hidden','name'=>'type','value'=>$data['type']))}

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('区块名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('区块标识'),'uid',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'uid','value'=>$data['uid'],'pattern'=>'^[a-zA-Z0-9-_]+$','maxlength'=>32,'required'=>'required','remote'=>u('block/block/check','ignore='.$data['uid'])))}
					{form::tips(t('区块的唯一标识，允许使用英文、数字、短横杠及下划线，最大长度 32'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('区块分类'),'type',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','options'=>arr::hashmap($categories,'id','name'),'name'=>'categoryid','value'=>$data['categoryid']))}
				</td>
			</tr>
			{if $data['type'] == 'auto'}
			<tr>
				<td class="label">{form::label(t('更新频率'),'interval',false)}</td>
				<td class="input">
					<div class="input-group">
						{form::field(array('type'=>'number','name'=>'interval','value'=>intval($data['interval']),'min'=>0))}
						<span class="input-group-addon">{t('秒')}</span>
					</div>
				</td>
			</tr>
			{/if}


			{if in_array($data['type'],array('auto','list'))}
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
			{/if}

			{if $data['type'] == 'list'}
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
			{/if}

			<tr>
				<td class="label">{form::label(t('区块模版'),'template',true)}</td>
				<td class="input">
					{if BLOCK_TEMPLATE_ALONE}
					{form::field(array('type'=>'template_editor','name'=>'template','value'=>$data['template'],'required'=>'required'))}
					{else}
					{form::field(array('type'=>'template','name'=>'template','value'=>$data['template'],'required'=>'required'))}
					{/if}

					{form::tips(t('模板决定区块的显示效果，支持 &#123;$name&#125; &#123;$data&#125; 标签'))}
				</td>
			</tr>


			<tr>
				<td class="label">{form::label(t('区块说明'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'hidden','name'=>'operate','value'=>'save'))}

		{if $data['data']}
		{form::field(array('type'=>'button','value'=>t('保存并返回'),'class'=>'submit btn-highlight','rel'=>'submit'))}
		{/if}

		{form::field(array('type'=>'button','value'=>t('保存并下一步'),'class'=>'submit btn-primary','rel'=>'save'))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}

	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	var $form = $('form.form');

	$(function(){
		$form.on('click','button.submit',function(){
			$form.find('input[name=operate]').val($(this).attr('rel'));
			$form.submit();
		})
	});


	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$(form).find('.submit').disable(true);
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});

	// init
	function forminit(v){
		$('tr.type-options').hide();
		$('tr.type-'+v).show();
	}

	// type init change
	$(function(){
		forminit($('[name=type]:checked').val());

		$('[name=type]').on('change',function(){
			var val= $(this).val();
			forminit(val);
		});
	});


	$(function(){

		// 添加自定义字段
		$('a.add').on('click',function(){
			var table = $(this).parent().prev().prev().find('tbody');
			var $i = table.find('tr').length

			table.append('<tr>'+
				'<td>'+
						'<a href="javascript:void(0)" class="delete"><i class="icon icon-delete"></i></a>'+
						'<input type="hidden" name="fields['+$i+'][show]" class="checkbox" value="1" checked>'+
				'</td>'+
				'<td><input type="text" name="fields['+$i+'][label]" class="text tiny required" placeholder="{t('字段名称')}" data-placement="bottom"></td>'+
				'<td><input type="text" name="fields['+$i+'][name]" class="text tiny required field custom_field" placeholder="{t('字段标识')}" data-placement="bottom" pattern="^([a-zA-Z0-9-]|[_]){1,32}$"></td>'+
				'<td></td>'+
			'</tr>');
		});

		// 禁止输入重复的字段名
		$('table.fields').on('blur','input.custom_field',function(){
			var me = this;
			var field = $(this).val();

			$('input.field').each(function(){
				if ( me != this && field == $(this).val() ){
					$(me).val('').focus();
					$.msg({state:'error',content:'{t('字段已经存在，请输入其它值')}',time:2});
				}
			});

		});

		// 删除自定义字段
		$('table.fields').on('click','a.delete',function(){
			$(this).parent().parent().remove();
		});
	});
</script>
{template 'footer.php'}