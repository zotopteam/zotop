{template 'header.php'}

<div class="side">
{template 'shop/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('商城设置')}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		
		<table class="field">
			<caption>{t('全局设置')}</caption>
			<tbody>

			<tr>
				<td class="label">{form::label(t('咨询qq'),'goods_sn',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'qq','value'=>c('shop.qq'),'required'=>'required','maxlength'=>20))}
					{form::tips(t('请输入购买咨询客服qq号码（需要开通该qq在线状态）'))}					
				</td>
			</tr>
			</tbody>
		</table>		

		<table class="field">
			<caption>{t('商品设置')}</caption>
			<tbody>

			<tr>
				<td class="label">{form::label(t('编号前缀'),'goods_sn',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'goods_sn','value'=>c('shop.goods_sn'),'required'=>'required','maxlength'=>2))}
				</td>
			</tr>				

			<tr>
				<td class="label">{form::label(t('商品主图'),'thumb_width',true)}</td>
				<td class="input">
					<div class="input-group">
						<span class="input-group-addon">{t('宽')}</span>
						{form::field(array('type'=>'number','name'=>'thumb_width','value'=>c('shop.thumb_width'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
					<b class="center middle">×</b>
					<div class="input-group">
						<span class="input-group-addon">{t('高')}</span>
						{form::field(array('type'=>'number','name'=>'thumb_height','value'=>c('shop.thumb_height'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>

				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('内容图片'),'content_width',true)}</td>
				<td class="input">
					<div class="input-group">
						<span class="input-group-addon">{t('宽')}</span>
						{form::field(array('type'=>'number','name'=>'content_width','value'=>c('shop.content_width'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
					<b class="center middle">×</b>
					<div class="input-group">
						<span class="input-group-addon">{t('高')}</span>
						{form::field(array('type'=>'number','name'=>'content_height','value'=>c('shop.content_height'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('图集图片'),'gallery_width',true)}</td>
				<td class="input">
					<div class="input-group">
						<span class="input-group-addon">{t('宽')}</span>
						{form::field(array('type'=>'number','name'=>'gallery_width','value'=>c('shop.gallery_width'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
					<b class="center middle">×</b>
					<div class="input-group">
						<span class="input-group-addon">{t('高')}</span>
						{form::field(array('type'=>'number','name'=>'gallery_height','value'=>c('shop.gallery_height'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>
				</td>
			</tr>												
			</tbody>
		</table>

		<table class="field">
			<caption>{t('栏目图片')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('栏目图片'),'category_image_resize',false)}</td>
				<td class="input">
					{form::field(array('type'=>'radio',options=>array(1=>t('缩放'),2=>t('裁剪'),0=>t('无')),'name'=>'category_image_resize','value'=>c('shop.category_image_resize')))}

					<div class="input-group">
						<span class="input-group-addon">{t('宽高')}</span>
						{form::field(array('type'=>'number','name'=>'category_image_width','value'=>c('shop.category_image_width'),'required'=>'required'))}
						<span class="input-group-addon"><b class="center middle">×</b></span>
						{form::field(array('type'=>'number','name'=>'category_image_height','value'=>c('shop.category_image_height'),'required'=>'required'))}
						<span class="input-group-addon">px</span>
					</div>

					{form::field(array('type'=>'radio',options=>array(1=>t('水印'),0=>t('无')),'name'=>'content_watermark','value'=>(int)c('shop.content_watermark')))}


					<label for="category_image_width" class="error">{t('必须是数字且不能为空')}</label>
					<label for="category_image_height" class="error">{t('必须是数字且不能为空')}</label>

					{form::tips(t('图片尺寸大于设置值时自动压缩'))}
				</td>
			</tr>
			</tbody>
		</table>			

		<table class="field">
			<caption>{t('规格设置')}</caption>
			<tbody>					
				<tr>
					<td class="label">{form::label(t('开启规格'),'spec',true)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'spec','value'=>(int)c('shop.spec')))}
					</td>
				</tr>					
				<tr>
					<td class="label">{form::label(t('规格图片'),'spec_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'spec_width','value'=>c('shop.spec_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'spec_height','value'=>c('shop.spec_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>

						{form::tips(t('上传图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>			
			</tbody>
		</table>			

		<table class="field">
			<caption>{t('品牌设置')}</caption>
			<tbody>
				<tr>
					<td class="label">{form::label(t('开启品牌'),'brand',true)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'brand','value'=>(int)c('shop.brand')))}
					</td>
				</tr>		
				<tr>
					<td class="label">{form::label(t('品牌logo'),'brand_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'brand_width','value'=>c('shop.brand_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'brand_height','value'=>c('shop.brand_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>


						{form::tips(t('上传图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}