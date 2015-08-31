{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="navbar">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="current"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="icon {$n['icon']}"></i>{/if} {$n['text']}</a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('类型及大小'),'upload_types',true)}</td>
					<td class="input">

						<table class="controls">
							<thead>
								<tr>
									<td><b>{t('类型')}</b></td>
									<td><b>{t('文件格式')}</b></td>
									<td><b>{t('最大值')}</b></td>
								</tr>
							<thead>
							<tbody>
							{loop m('system.attachment')->types() $type $typename}
							<tr>
								<td class="w50">{$typename}</td>
								<td>{form::field(array('type'=>'text','name'=>'attachment_'.$type.'_exts','value'=>c('system.attachment_'.$type.'_exts'),'required'=>'required'))}</td>
								<td>
									<div class="input-group">
										{form::field(array('type'=>'number','name'=>'attachment_'.$type.'_size','value'=>c('system.attachment_'.$type.'_size'), 'min'=>0.1, 'required'=>'required'))}
										<span class="input-group-addon">MB</span>
									</div>

									<label for="attachment_{$type}_size" class="error">{t('必须是数字且不能为空')}</label>
								</td>
							<tr>
							{/loop}
							</tbody>
						</table>
						{form::tips(t('多个文件格式之间请用 英文逗号 隔开，如：jpg,jpeg,png,gif').',&nbsp; '.t('系统允许上传单个文件的最大值: %s',ini_get("upload_max_filesize")))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('存储方式'),'upload_dir',true)}</td>
					<td class="input">
						<?php echo form::field(array(
							'type'=>'radio',
							'options'=>array(
								'[YYYY]'			=> t('按照 %s 目录存储，如%s',t('年'),'2011/2011121310364143635.jpg'),
								'[YYYY]/[MM]'		=> t('按照 %s 目录存储，如%s',t('年/月'),'2011/03/2011121310364143635.jpg'),
								'[YYYY]/[MM]/[DD]'	=> t('按照 %s 目录存储，如%s',t('年/月/日'),'2011/03/31/2011121310364143635.jpg'.'<b>'.t('推荐').'</b>'),
							),
							'name'=>'upload_dir',
							'value'=>c('system.upload_dir'),
							'required'=>'required',
							'column'=>1
						))
						?>
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('图片缩放')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('启用'),'image_resize',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'image_resize','value'=>c('system.image_resize')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('宽高'),'image_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'image_width','value'=>c('system.image_width'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'image_height','value'=>c('system.image_height'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<label for="image_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="image_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('上传图片尺寸大于设置值时自动压缩'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('品质'),'image_quality',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'image_quality','value'=>c('system.image_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('图片水印')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('启用'),'watermark',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'watermark','value'=>c('system.watermark')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('添加条件'),'watermark_width',true)}</td>
					<td class="input">
						<div class="input-group">
							<span class="input-group-addon">{t('宽')}</span>
							{form::field(array('type'=>'number','name'=>'watermark_width','value'=>c('system.watermark_width'),'title'=>t('宽'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<b class="center middle">×</b>
						<div class="input-group">
							<span class="input-group-addon">{t('高')}</span>
							{form::field(array('type'=>'number','name'=>'watermark_height','value'=>c('system.watermark_height'),'title'=>t('高'),'required'=>'required'))}
							<span class="input-group-addon">px</span>
						</div>
						<label for="watermark_width" class="error">{t('必须是数字且不能为空')}</label>
						<label for="watermark_height" class="error">{t('必须是数字且不能为空')}</label>

						{form::tips(t('上传图片尺寸大于设置值时自动加水印'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('水印图片'),'watermark_image',true)}</td>
					<td class="input">
						<?php echo form::field(array(
						   'type'		=> 'hidden',
						   'name'		=> 'watermark_image',
						   'value'		=> c('system.watermark_image'),
						   'required'	=> 'required',
						   'extension'	=> 'png|jpg|jpeg|gif',
						))?>

						<div id="upload-preview" class="clearfix">
							<div id="upload">
								<i class="icon icon-upload"></i>
								<b class="undragdrop">{t('点击上传')}</b>
								<b class="dragdrop none">{t('点击上传或者将图片拖到此区域上传')}</b>
							</div>
							<img src="{format::url(ZOTOP_URL_CMS.'/watermark/'.c('system.watermark_image'))}" id="preview">
						</div>
						{form::tips(t('水印图片一般是小的矩形，建议长度在150px以内、高度在50px以内'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('水印位置'),'watermark_position',false)}</td>
					<td class="input">
						<?php echo form::field(array(
						   'type'=>'radio',
						   'column'=>3,
						   'options'=>array(
								'top left'=>t('顶部居左'),
								'top center'=>t('顶部居中'),
								'top right'=>t('顶部居右'),

								'center left'=>t('中部居左'),
								'center center'=>t('中部居中'),
								'center right'=>t('中部居右'),

								'bottom left'=>t('底部居左'),
								'bottom center'=>t('底部居中'),
								'bottom right'=>t('底部居右'),
							),
						   'name'=>'watermark_position',
						   'value'=>c('system.watermark_position'),
						   'class'=>'watermarkposition',
						))?>
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('水印透明度'),'watermark_opacity',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'watermark_opacity','value'=>c('system.watermark_opacity'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('品质'),'watermark_quality',true)}</td>
					<td class="input">
						{form::field(array('type'=>'number','name'=>'watermark_quality','value'=>c('system.watermark_quality'),'max'=>100,'min'=>0,'required'=>'required'))}
						{form::tips(t('请设置为0-100之间的数字，数字越大图片越清晰'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('测试水印'),'',false)}</td>
					<td class="input">
						<a class="btn" href="javascript:void(0)" id="testwatermark">{t('测试水印')}</a>
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

	$(function(){
		$('#testwatermark').on('click',function(){
			var data = $('form.form').serialize();
			var title = $(this).text();
			$loading = $.loading();
			$.post("{u('system/config/testwatermark')}", data, function(msg){
				$loading.close();
				$.dialog({
					title: title,
					content: '<a href="'+msg.content+'" target="_blank" title="{t('新窗口中打开')}"><img src="'+msg.content+'" width="600"></a>',
					width: 600,
					height: 400,
					padding: 10
				},true);
			},'json');
		});
	});
</script>

<style type="text/css">
#upload-preview{float:left;position:relative;max-width:500px;height:50px;line-height:50px;border:1px solid #EBEBEB;}
#upload-preview img{float:left;max-width:150px;height:50px;}
#upload-preview #upload{width:300px;height:50px;line-height:50px;text-align:center;float:right;cursor:pointer;}
</style>

<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){
		var uploader = $("#upload").upload({
			url : "{u('system/config/uploadwatermark')}",
			//runtimes : 'flash',
			multi:false,
			maxsize:'20mb',
			//resize:{width:150,height:50,quality:100},
			fileext: 'jpg,jpeg,png,gif',
			filedescription : 'image file',
			progress : function(up,file){
				up.self.html('{t('上传中……')}'+up.total.percent + '%');
			},
			uploaded : function(up,file,data){
				$.msg(data);

				$('#preview').attr('src',$('#preview').attr('src')+'?'+Math.random());
			},
			complete : function(up,files){
				up.self.html(up.content);
			},
			error : function(error,detail){
				$.error(detail);
			}
		});
	});
</script>

{template 'footer.php'}