<table class="field">
	<tbody>
	<tr>
		<td class="label">{form::label(t('标题'),'title',true)}</td>
		<td class="input">
			{form::field(array('type'=>'title','name'=>'title','value'=>$data['title'],'required'=>'required'))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('作者'),'author',false)}</td>
		<td class="input">
			{form::field(array('type'=>'author','name'=>'author','value'=>$data['author']))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('来源'),'source',false)}</td>
		<td class="input">
			{form::field(array('type'=>'source','name'=>'source','value'=>$data['source']))}
		</td>
	</tr>
	<tr>
		<td class="label">{form::label(t('内容'),'content',true)}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'=>'editor',
				'name'=>'content',
				'value'=>$data['content'],
				'theme'=>'full',
				'tools'=>true,
				'dataid'=>$data['dataid'],
				'image_resize' => c('content.image_resize'),
				'image_width' => c('content.image_width'),
				'image_height' => c('content.image_height'),
				'image_quality' => c('content.image_quality'),
				'required'=>'required'
			));?>
		</td>
	</tr>
	</tbody>
</table>
<table class="field">
	<tr>
		<td class="label">{form::label(t('缩略图'),'thumb',false)}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'		=> 'image',
				'name'		=> 'thumb',
				'value'		=> $data['thumb'],
				'dataid'	=> $data['dataid'],
				'image_resize' => c('content.thumb_resize'),
				'image_width' => c('content.thumb_width'),
				'image_height' => c('content.thumb_height'),
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
	<tr>
		<td class="label">{form::label(t('URL别名'),'alias',false)}</td>
		<td class="input">
			{form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'title'))}
		</td>
	</tr>
	<tr class="none">
		<td class="label">{form::label(t('独立模板'),'template',false)}</td>
		<td class="input">
			{form::field(array('type'=>'template','name'=>'template','value'=>$data['template']))}
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
