{template 'header.php'}
<div class="side">
{template 'block/admin_side.php'}
</div>
{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('block/admin/index/'.$categoryid)}">{$category.name}</a>
			<s class="arrow">></s>
			{t('内容维护')}
			<s class="arrow">></s>
			{$block['name']}			
		</div>		
		<div class="action">
			<a class="btn btn-icon-text" href="{u('block/admin/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
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


		{form::field(array('type'=>'button','value'=>t('保存并返回'),'class'=>'submit btn-highlight','rel'=>'submit'))}

		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}

		<a class="btn" href="{u('block/admin/index/'.$categoryid)}"><b>{t('取消')}</b></a>	


	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<style type="text/css">
div.editor-area{margin:15px 0;}
div.description{line-height:22px;font-size:14px;clear: both; border: solid 1px #F2E6D1; background: #FCF7E4; color: #B25900; border-radius: 5px;margin: 10px 0; padding: 10px;}
textarea.full{}
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