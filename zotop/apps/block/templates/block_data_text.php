{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$block['name']}</div>
		<div class="action">
			<a class="btn btn-icon-text" href="{u('block/block/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<div class="editor-area">

				{form::field(array('type'=>'hidden','name'=>'categoryid','value'=>$block['categoryid'],'required'=>'required'))}
				{form::field(array('type'=>'hidden','name'=>'id','value'=>$block['id'],'required'=>'required'))}
				{form::field(array('type'=>'hidden','name'=>'operate','value'=>'save'))}


				{form::field(array('type'=>'textarea','name'=>'data','value'=>$block['data'],'class'=>'full','required'=>'required'))}

			</div>

			{if $block['description']}
			<div class="description">
				<div class="tips"><i class="icon icon-info alert"></i> {$block['description']}</div>
			</div>
			{/if}

	</div><!-- main-body -->
	<div class="main-footer">

		<a class="btn btn-icon-text fr" href="{u('block/block/list/'.$categoryid)}"><i class="icon icon-back"></i><b>{t('返回')}</b></a>

		{form::field(array('type'=>'button','value'=>t('保存并返回'),'class'=>'submit btn-highlight','rel'=>'submit'))}

		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}


	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<style type="text/css">
div.editor-area{margin:15px;}
div.description{margin:15px;line-height:22px;font-size:14px;}
textarea.full{width:800px;}
</style>
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
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
			$(form).find('.submit').disable(true);
			$.post(action, data, function(msg){
				$.msg(msg);
				if ( !msg.url ){
					$(form).find('.submit').disable(false);
				}
			},'json');
		}});
	});

</script>
{template 'footer.php'}