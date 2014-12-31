{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{if $title}{$title}{else}{t('商品管理')}{/if}</div>
		<div class="position">
			<a href="{u('shop/goods')}">{t('商品管理')}</a>
			{loop m('shop.category.getparents',$categoryid) $p}
				<s class="arrow">></s>
				<a href="{u('shop/goods/index/'.$p['id'])}">{$p['name']}</a>
			{/loop}
			<s class="arrow">></s>
			{if $data['id']}{t('编辑')}{else}{t('添加')}{/if}
		</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<input type="hidden" name="id" value="{$data['id']}">
		<input type="hidden" name="status" value="{$data['status']}">

		<table class="field">
			<caption>{t('基本信息')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('商品分类'),'categoryid',true)}</td>
				<td class="input">
					{form::field(array('type'=>'select','options'=>m('shop.category.hashmap'),'name'=>'categoryid','value'=>$data['categoryid']))}					
				</td>
			</tr>			
			<tr>
				<td class="label">{form::label(t('商品类型'),'typeid',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>m('shop.type.hashmap'),'name'=>'typeid','value'=>$data['typeid']))}					
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('商品编号'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'sn','value'=>$data['sn'],'required'=>'required','remote'=>u('shop/goods/check/sn','ignore='.$data['sn'])))}
				</td>
			</tr>							
			<tr>
				<td class="label">{form::label(t('商品名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required','maxlength'=>100))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('商品卖点'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'maxlength'=>255))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('商品关键词'),'keywords',false)}</td>
				<td class="input">
					{form::field(array('type'=>'keywords','name'=>'keywords','value'=>$data['keywords'],'maxlength'=>50))}
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('商品主图'),'thumb',true)}</td>
				<td class="input">
					<?php echo form::field(array(
						'type'			=> 'image',
						'name'			=> 'thumb',
						'value'			=> $data['thumb'],
						'dataid'		=> $data['dataid'],
						'image_resize'	=> 1,
						'image_width'	=> c('shop.thumb_width'),
						'image_height'	=> c('shop.thumb_height'),
						'image_quality' => c('shop.thumb_quality'),
						'watermark'		=> 0,
					));?>					
				</td>
			</tr>						
			{if c('shop.brand')}
			<tr>
				<td class="label">{form::label(t('商品品牌'),'brandid',true)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>m('shop.brand.hashmap'),'name'=>'brandid','value'=>$data['brandid']))}					
				</td>
			</tr>
			{/if}
			</tbody>
		</table>

		<div id="attrs-area">
			
		</div>

		<table class="field">
			<caption>{t('详细内容')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('详细介绍'),'content',true)}</td>
				<td class="input">
					<?php echo form::field(array(
						'type'			=> 'editor',
						'name'			=> 'content',
						'value'			=> $data['content'],
						'dataid'		=> $data['dataid'],
						'tools'			=> true,
						'theme'			=> 'full',
						'image_resize'	=> 1,
						'image_width'	=> c('shop.content_width'),
						'image_height'	=> c('shop.content_height'),
						'image_quality' => c('shop.content_quality'),
						'watermark'		=> 1,
					));?>
				</td>
			</tr>

			<tr>
				<td class="label">{form::label(t('商品图集'),'gallery',true)}</td>
				<td class="input">
					<?php echo form::field(array(
						'type'			=> 'gallery',
						'name'			=> 'gallery',
						'value'			=> is_array($data['gallery']) ? $data['gallery'] : array(),
						'dataid'		=> $data['dataid'],
						'image_resize'	=> 1,
						'image_width'	=> c('shop.gallery_width'),
						'image_height'	=> c('shop.gallery_height'),
						'image_quality' => c('shop.gallery_quality'),
						'watermark'		=> 1,
					));?>
				</td>
			</tr>				
			</tbody>					
		</table>

		<table class="field">
			<tbody>					
				<tr>
					<td class="label">{form::label(t('上架'),'publish',true)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'publish','value'=>$data['publish']))}
					</td>
				</tr>			
			</tbody>
		</table>
	</div><!-- main-body -->
	<div class="main-footer">
		<input type="hidden" name="operate" value="">


		{form::field(array('type'=>'button','value'=>t('保存并返回'),'class'=>'submit btn-highlight','rel'=>'saveback'))}

		{if $data['id']}
		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}
		{else}
		{form::field(array('type'=>'button','value'=>t('保存草稿'),'class'=>'submit btn-primary','rel'=>'draft'))}
		{/if}

		{form::field(array('type'=>'button','value'=>t('返回列表'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	var operate;

	// 保存
	$(function(){
		$('form.form').on('click','button.submit',function(){
			
			// 获取操作
			operate = $(this).attr('rel');

			$('input[name=operate]').val(operate);

			// 提交表单
			$('form.form').submit();
		})
	});

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$(form).find('.submit').disable(true);
			$.post(action, data, function(msg){

				if( !msg.state ||  operate == 'save' || operate == 'draft'){
					$(form).find('.submit').disable(false);
				}

				// 当保存草稿时返回值为内容编号
				if( operate == 'draft' && msg.returnvalue ){					
					$('input[name=id]').val(msg.returnvalue);
				}				

				$.msg(msg);

			},'json');
		}});
	});


	function showattrs(typeid){
		var data = {json_encode($data)};
			data.typeid = typeid || data.typeid;

		$('#attrs-area').html('<i class="icon icon-loading" style="margin-left:150px;"></i>').load("{u('shop/goods/attrs')}",data, function(){
			$(this).find(".checkboxes").checkboxes();
			$(this).find(".radios").radios();
			$(this).find(".single-select").singleselect();			
		});
	}

	$(function(){
		showattrs();

		$('[name=typeid]').on('click',function(){
			showattrs($(this).val());
		});
	});	
</script>
{template 'footer.php'}