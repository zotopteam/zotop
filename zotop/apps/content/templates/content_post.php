{template 'header.php'}

<div class="main">
	<div class="main-header">
		<div class="goback"><a href="javascript:location.href=document.referrer;"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">
			{if $data['id']}
				{t('编辑%s',m('content.model.get',$data.modelid,'name'))}
			{else}
				{t('添加%s',m('content.model.get',$data.modelid,'name'))}
			{/if}
		</div>
		<div class="breadcrumb hidden">
			<li><a href="{u('content/content')}">{t('内容管理')}</a></li>
			
			{loop m('content.category.getparents',$data.categoryid) $p}
			<li><a href="{u('content/content/index/'.$p['id'].'/publish')}">{$p['name']}</a></li>
			{/loop}			

			{if $data['title']}
			<li>{$data['title']}</li>
			{/if}
		</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">

		<input type="hidden" name="id" value="{$data['id']}">
		<input type="hidden" name="modelid" value="{$data['modelid']}">
		<input type="hidden" name="categoryid" value="{$data['categoryid']}">
		<input type="hidden" name="status" value="{$data['status']}">

		<div class="container-fluid">
			<div class="form-horizontal">
				{loop m('content.field.formatted',$data.modelid,$data) $f}
				<div class="form-group">
					<div class="col-sm-2 col-md-2 col-lg-1 control-label">{form::label($f['label'],$f['for'],$f['required'])}</div>
					<div class="col-sm-10 col-md-10 col-lg-10">
						{form::field($f['field'])}
						{form::tips($f['tips'])}
					</div>
				</div>
				{/loop}
			</div>		
		</div>		

	</div><!-- main-body -->
	<div class="main-footer">

		{form::field(array('type'=>'button','value'=>t('保存并发布'),'class'=>'submit btn-success','rel'=>'publish'))}

		{if $data['id']}
		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}
		{else}
		{form::field(array('type'=>'button','value'=>t('保存草稿'),'class'=>'submit btn-primary','rel'=>'draft'))}
		{/if}

	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->


<script type="text/javascript">

	var form = $('form.form');
	var operate;

	// 保存
	$(function(){
		form.on('click','button.submit',function(){
			// 获取操作
			operate = $(this).attr('rel');

			// 同步参数
			if ( operate == 'publish' || operate == 'draft' ){
				$('input[name=status]').val(operate);
			}
			// 提交表单
			form.submit();
		})
	});



	// 表单验证并提交
	$(function(){
		form.validate({submitHandler:function(){
			var action = form.attr('action');
			var data = form.serialize();

			form.find('.submit').prop('disabled',true);

			$.loading();
			$.post(action, data, function(msg){

				if( operate == 'save' || operate == 'draft' ){

					// 当保存草稿时候url返回值为内容编号
					if ( operate == 'draft' && msg.url ) $('input[name=id]').val(msg.url);

					form.find('.submit').prop('disabled',false);
					msg.url = null;
				}

				if( !msg.state ){
					form.find('.submit').prop('disabled',false);	
				}

				$.msg(msg);

			},'json');
		}});
	});

	$(function(){
		$('span.options a').on('click',function(){

			if ( $(this).hasClass('current') ){

				$(this).removeClass('current');
				$('tr.options').hide();

			}else{
				var option = $(this).attr('class').split(' ');

				$(this).addClass('active').siblings("a").removeClass('active');

				$.each(option, function(i, cls){
					$('.options').hide();
					$('.'+cls).show();
				});
			}
		})
	})
</script>
{template 'footer.php'}