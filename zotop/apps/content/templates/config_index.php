{template 'header.php'}

<div class="side">
{template 'content/admin_side.php'}
</div>
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('内容设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->

	{form::header(u('content/config/save'))}
	<div class="main-body scrollable">

		<div class="container-fluid">
			<div class="form-horizontal">

				<div class="form-title">{t('最新标识')}</div>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('最新标识'),'newflag',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'radio','options'=>array(''=>t('禁用'),'createtime'=>t('以发布时间判定'),'updatetime'=>t('以更新时间判定')),'name'=>'newflag','value'=>c('content.newflag')))}
						{form::tips(t('是否在内容标题后的显示最新标识及判定依据，如: <span class="red">新</span>'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('标识期限'),'newflag_expires',false)}</div>
					<div class="col-sm-8">
						<div class="input-group col-sm-2">
							{form::field(array('type'=>'number','name'=>'newflag_expires','value'=>c('content.newflag_expires')))}
							<span class="input-group-addon">{t('小时')}</span>
						</div>
						{form::tips(t('超出标识期限则不显示'))}
					</div>
				</div>
				
			

			
				<div class="form-title">{t('自动提取')}</div>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('提取摘要'),'auto_summary',false)}</div>
					<div class="col-sm-8">
						<div class="input-group col-sm-2">
							{form::field(array('type'=>'number','name'=>'auto_summary','value'=>c('content.auto_summary')))}
							<span class="input-group-addon">{t('字符')}</span>
						</div>
						{form::tips(t('当摘要为空时从内容提取指定长度文本作为摘要，0：关闭提取'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('提取缩略图'),'auto_image',false)}</div>
					<div class="col-sm-8">
						<div class="input-group col-sm-2">
							<span class="input-group-addon">{t('第')}</span>
							{form::field(array('type'=>'number','name'=>'auto_image','value'=>c('content.auto_image')))}
							<span class="input-group-addon">{t('张')}</span>
						</div>
						{form::tips(t('当缩略图为空时从内容提取指定图片文本作为缩略图，0：关闭提取'))}
					</div>
				</div>
				<div class="form-group hidden">
					<div class="col-sm-2 control-label">{form::label(t('提取关键词'),'auto_keywords',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'bool','name'=>'auto_keywords','value'=>c('content.auto_keywords')))}
						{form::tips(t('当关键词为空时从内容提取关键词'))}
					</div>
				</div>
				
			

			
				<div class="form-title">{t('栏目图片')}</div>
				
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('缩放'),'category_image_resize',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'radio',options=>array(0=>t('关闭'),1=>t('缩放'),2=>t('裁剪')),'name'=>'category_image_resize','value'=>c('content.category_image_resize')))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('宽高'),'category_image_width',true)}</div>
					<div class="col-sm-8">
						<div class="input-group col-sm-4">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'category_image_width','value'=>c('content.category_image_width'),'required'=>'required'))}
							<span class="input-group-addon">×</span>
							{form::field(array('type'=>'number','name'=>'category_image_height','value'=>c('content.category_image_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						<label for="category_image_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="category_image_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('图片尺寸大于设置值时自动压缩'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('品质'),'category_image_quality',true)}</div>
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-2">
							{form::field(array('type'=>'number','name'=>'category_image_quality','value'=>c('content.category_image_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
							</div>
						</div>
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</div>
				</div>
				
			

			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').prop('disabled',true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').prop('disabled',false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}