{template 'header.php'}
{template 'block/admin_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<ul class="breadcrumb hidden">
			<li><a href="{u('block/admin/index/'.$categoryid)}">{$category.name}</a></li>
			<li>{t('内容维护')}</li>		
		</ul>		
		<div class="action">
			<a class="btn btn-default btn-icon-text" href="{u('block/admin/edit/'.$block['id'])}"><i class="fa fa-cog"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->

	{form}

	<div class="main-body scrollable">

			<div class="container-fluid container-default">

				{form::field(array('type'=>'hidden','name'=>'categoryid','value'=>$block['categoryid'],'required'=>'required'))}
				{form::field(array('type'=>'hidden','name'=>'id','value'=>$block['id'],'required'=>'required'))}
				{form::field(array('type'=>'hidden','name'=>'operate','value'=>'save'))}



				{field type="editor" name="data" value="$block.data" dataid="$block.id" theme="full" tools="true" rows="22" required="required"}

			</div>

			{if $block['description']}
			<div class="container-fluid container-info">
				{$block['description']}
			</div>
			{/if}


	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'button','value'=>t('保存并返回'),'class'=>'submit btn-success','rel'=>'submit'))}

		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}

		<a class="btn btn-default pull-right" href="{u('block/admin/index/'.$categoryid)}"><b>{t('取消')}</b></a>

	</div><!-- main-footer -->
	{/form}
</div><!-- main -->

<script type="text/javascript">

	var $form = $('form.form');

	// 保存
	$(function(){
		$form.on('click','button.submit',function(){

			// 同步参数
			$form.find('input[name=operate]').val($(this).attr('rel'));

			// 提交表单
			$form.submit();
		})
	});

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$(form).find('.submit').prop('disabled',true);
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').prop('disabled',false);
			},'json');
		}});
	});

</script>
{template 'footer.php'}