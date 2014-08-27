<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('标题'),'title',true)}</td>
		<td class="input">
			{form::field(array('type'=>'title','name'=>'title','value'=>$data['title'],'required'=>'required'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('链接'),'url',true)}</td>
		<td class="input">
			{form::field(array('type'=>'text','name'=>'url','value'=>$data['url'],'required'=>'required'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('缩略图'),'image',false)}</td>
		<td class="input">
		<?php echo form::field(array(
			'type'			=> 'image',
			'name'			=> 'thumb',
			'value'			=> $data['thumb'],
			'dataid'		=> $data['dataid'],
			'image_resize'	=> c('content.thumb_resize'),
			'image_width'	=> c('content.thumb_width'),
			'image_height'	=> c('content.thumb_height'),
			'image_quality' => c('content.thumb_quality'),
			'watermark'	=>false,
		))?>
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
	<tr>
		<td class="label">{form::label(t('发布时间'),'createtime',false)}</td>
		<td class="input">
			{form::field(array('type'=>'datetime','name'=>'createtime','value'=>$data['createtime']))}
		</td>
	</tr>
	</tbody>
</table>
