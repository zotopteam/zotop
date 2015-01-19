{template 'header.php'}
<div class="side">
{template 'content/admin_side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('content/category')}">{t('根栏目')}</a>
			{loop $parents $p}
				<s class="arrow">></s>
				<a href="{u('content/category/index/'.$p['id'])}">{$p['name']}</a>
			{/loop}
			<s class="arrow">></s>
			{if $data['id']}{t('编辑')}{else}{t('添加')}{/if}
		</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<input type="hidden" name="parentid" value="{$data['parentid']}">

		<table class="field">
			<caption>{t('基本设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('栏目名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('栏目别名'),'alias',false)}</td>
				<td class="input">
					{form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'name'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('栏目图片'),'image',false)}</td>
				<td class="input">
					<?php echo form::field(array(
						'type'			=> 'image',
						'name'			=> 'image',
						'value'			=> $data['image'],
						'dataid'		=> $data['dataid'],
						'image_resize'	=> c('content.category_image_resize'),
						'image_width'	=> c('content.category_image_width'),
						'image_height'	=> c('content.category_image_height'),
						'image_quality'	=> c('content.category_image_quality'),
					))?>
				</td>
			</tr>			
			<tr>
				<td class="label">{form::label(t('首页模版'),'settings[template_index]',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'settings[template_index]','value'=>$data['settings']['template_index'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('列表页模版'),'settings[template_list]',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'settings[template_list]','value'=>$data['settings']['template_list'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('内容模型'),'models',false)}</td>
				<td class="input">
					<table class="controls">
						<thead>
							<tr>
								<td class="w100">{t('模型')}</td>
								<td>{t('内容页模版')}</td>
							</tr>
						</thead>
						<tbody>
							{loop $models $i $m}
							<tr>
								<td>
									<label>
										<input type="checkbox" name="settings[models][{$i}][enabled]" value="1" class="vm" {if $m['enabled']}checked="checked"{/if}/>
										{$m['name']}
									</label>
								</td>
								<td>
									{if $m['template']}
										{form::field(array('type'=>'template','name'=>'settings[models]['.$i.'][template]','value'=>$m['template'],'required'=>'required','style'=>'width:360px;'))}
									{/if}
								<td>
							</tr>
							{/loop}
						</tbody>
					</table>
				</td>
			</tr>
			</tbody>
		</table>
		<table class="field">
			<caption>{t('高级设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('栏目标题'),'title',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'title','value'=>$data['title']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('栏目关键词'),'keywords',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'keywords','value'=>$data['keywords'],'data-source'=>'title,description'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('栏目描述'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'placeholder'=>t('合理填写有助于搜索引擎排名优化')))}
				</td>
			</tr>	
			{if a('member')}
			<tr>
				<td class="label">{form::label(t('会员投稿'),'settings[contribute]',false)}</td>
				<td class="input">

					{form::field(array('type'=>'radio','options'=>array(0=>t('禁止'), 1=>t('允许')),'name'=>'settings[contribute]','value'=>(int)$data['settings']['contribute']))}

					<div class="input-group" id="contribute_point">
						<span class="input-group-addon">
							{t('投稿积分')}
						</span>
						{form::field(array('type'=>'number','name'=>'settings[contribute_point]','value'=>(int)$data['settings']['contribute_point']))}
						<span class="input-group-addon">
							{t('正数为增加积分，负数为扣除积分')}
						</span>						
					</div>

					<script type="text/javascript">
						$(function(){

							$contribute = $('input[name="settings[contribute]"]');

							$contribute.filter(':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();

							$contribute.on('click',function(){
								$contribute.filter(':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();
							});
						});
					</script>
				</td>
			</tr>
			{/if}
			</tbody>
		</table>
		{if $data['childid']}
		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('设置复制'),'apply-setting-childs',false)}</td>
				<td class="input">
					<span class="field"><label><input type="checkbox" name="apply-setting-childs" value="1"> {t('复制设置到全部子栏目')}</label></span>
				</td>
			</tr>
			</tbody>
		</table>
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$(form).find('.submit').disable(true);
			$.post(action, data, function(msg){

				if( !msg.state ){
					$(form).find('.submit').disable(false);
				}

				$.msg(msg);

			},'json');
		}});
	});

	$(function(){
		$('[name="name"]').change(function(){
			var name = $(this).val();

			$('[name="title"]').val(name);
			$('[name="keywords"]').val(name);
			$('[name="description"]').val(name);
		});
	})
</script>
{template 'footer.php'}