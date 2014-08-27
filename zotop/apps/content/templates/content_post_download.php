<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('标题'),'title',true)}</td>
		<td class="input">
			{form::field(array('type'=>'title','name'=>'title','value'=>$data['title'],'required'=>'required'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('文件地址'),'filepath',true)}</td>
		<td class="input">
			{form::field(array('type'=>'file','name'=>'filepath','id'=>'filepath','value'=>$data['filepath'], 'required'=>'required'))}
			{form::field(array('type'=>'hidden','name'=>'local','id'=>'local','value'=>(int)$data['local']))}

			<script type="text/javascript">
				function formatsize(a){function b(a,b){return Math.round(a*Math.pow(10,b))/Math.pow(10,b)}if(void 0===a||/\D/.test(a))return"";var c=Math.pow(1024,4);return a>c?b(a/c,1)+"TB":a>(c/=1024)?b(a/c,1)+"GB":a>(c/=1024)?b(a/c,1)+"MB":a>1024?Math.round(a/1024)+"KB":a+"B"}

				$(function(){
					$('#filepath').on('change',function(event, file){
						if(file){
							$('#filename').val(file.name);
							$('#filesize').val(formatsize(file.size));
							$('#fileext').val(file.ext);
							$('#local').val(1);							
						}else{
							$('#filename').val('');
							$('#filesize').val('');
							$('#fileext').val('');
							$('#local').val(0);		
						}
					});
				})
			</script>
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('文件信息'),'filename',true)}</td>
		<td class="input">
			<div class="input-group">
				<span class="input-group-addon">{t('文件名称')}</span>
				{form::field(array('type'=>'text','name'=>'filename','id'=>'filename','value'=>$data['filename'],'class'=>'short','placeholder'=>t('如：test.rar'),'required'=>'required'))}			
			</div>

			&nbsp;&nbsp;		
			<div class="input-group">
				<span class="input-group-addon">{t('文件大小')}</span>
				{form::field(array('type'=>'text','name'=>'filesize','id'=>'filesize','value'=>$data['filesize'],'class'=>'tiny','placeholder'=>t('如：128MB'),'required'=>'required'))}			
			</div>

			&nbsp;&nbsp;		
			<div class="input-group">
				<span class="input-group-addon">{t('文件格式')}</span>
				{form::field(array('type'=>'text','name'=>'fileext','id'=>'fileext','value'=>$data['fileext'],'class'=>'tiny','placeholder'=>t('如：doc'),'required'=>'required'))}			
			</div>			

			&nbsp;&nbsp;		
			<div class="input-group">
				<span class="input-group-addon">{t('下载次数')}</span>
				{form::field(array('type'=>'number','name'=>'download','id'=>'download','value'=>(int)$data['download'],'class'=>'tiny','required'=>'required'))}			
			</div>			
		</td>
	</tr>		
	<tr>
		<td class="label">{form::label(t('详细描述'),'content',false)}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'	=> 'editor',
				'name'	=> 'content',
				'value'	=> $data['content'],
				'theme'	=> 'full',
				'tools'	=> true,
				'dataid'=> $data['dataid'],
				'image_resize'	=> c('content.image_resize'),
				'image_width'	=> c('content.image_width'),
				'image_height'	=> c('content.image_height'),
				'image_quality' => c('content.image_quality'),
				'required'	=> 'required'
			));?>
		</td>
	</tr>
	</tbody>
</table>
<table class="field">
	<tr>
		<td class="label">{form::label(t('缩略图'),'image',false)}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'		=> 'image',
				'name'		=> 'thumb',
				'value'		=> $data['thumb'],
				'dataid'	=> $data['dataid'],
				'image_resize'	=> c('content.thumb_resize'),
				'image_width'	=> c('content.thumb_width'),
				'image_height'	=> c('content.thumb_height'),
				'image_quality' => c('content.thumb_quality'),
				'watermark'	=>false,
			));?>
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('关键词'),'keywords',false)}</td>
		<td class="input">
			{form::field(array('type'=>'keywords','name'=>'keywords','value'=>$data['keywords'], 'data-source'=>'content'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('摘要'),'summary',false)}</td>
		<td class="input">
			{form::field(array('type'=>'summary,textarea','name'=>'summary','value'=>$data['summary'],'maxlength'=>500))}
		</td>
	</tr>

	</tbody>
</table>

<table class="field">
	<tbody>
	<tr class="options options-alias">
		<td class="label">{form::label(t('URL别名'),'alias',false)}</td>
		<td class="input">
			{form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'title','remote'=>u('content/content/check/alias','ignore='.$data['alias'])))}
		</td>
	</tr>
	<tr class="options options-template none">
		<td class="label">{form::label(t('独立模板'),'template',false)}</td>
		<td class="input">
			{form::field(array('type'=>'template','name'=>'template','value'=>$data['template']))}
		</td>
	</tr>
	<tr class="options options-time">
		<td class="label">{form::label(t('发布时间'),'createtime',false)}</td>
		<td class="input">
			{form::field(array('type'=>'datetime','name'=>'createtime','value'=>$data['createtime']))}
		</td>
	</tr>
	</tbody>
</table>
