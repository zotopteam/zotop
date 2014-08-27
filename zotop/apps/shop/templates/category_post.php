{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('shop/category')}">{t('根类别')}</a>
			{loop $parents $p}
				<s class="arrow">></s>
				<a href="{u('shop/category/index/'.$p['id'])}">{$p['name']}</a>
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
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('别名'),'alias',false)}</td>
				<td class="input">
					{form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'name'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('类型'),'type',false)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>m('shop.type.hashmap'),'name'=>'settings[typeid]','value'=>$data['settings']['typeid']))}					
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
				<td class="label">{form::label(t('详细页模版'),'settings[template_detail]',true)}</td>
				<td class="input">
					{form::field(array('type'=>'template','name'=>'settings[template_detail]','value'=>$data['settings']['template_detail'],'required'=>'required'))}
				</td>
			</tr>						
			</tbody>
		</table>
		<table class="field">
			<caption>{t('高级设置')}</caption>
			<tbody>
			<tr>
				<td class="label">{form::label(t('标题'),'title',false)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'title','value'=>$data['title']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('图片'),'image',false)}</td>
				<td class="input">
					{form::field(array('type'=>'image','name'=>'image','value'=>$data['image'],'dataid'=>$data['dataid']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('简介'),'shop',false)}</td>
				<td class="input">
					{form::field(array('type'=>'editor','name'=>'content','value'=>$data['content'],'tools'=>true,'theme'=>'full','dataid'=>$data['dataid']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('关键词'),'keywords',false)}</td>
				<td class="input">
					{form::field(array('type'=>'keywords','name'=>'keywords','value'=>$data['keywords'],'data-source'=>'shop'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('描述'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'placeholder'=>t('合理填写有助于搜索引擎排名优化')))}
				</td>
			</tr>
				
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
		$('[name=name]').change(function(){
			var name = $(this).val();

			$('[name=title]').val(name);
			$('[name=keywords]').val(name);
			$('[name=description]').val(name);
		});
	})
</script>
{template 'footer.php'}