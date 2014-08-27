{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{u('member/index')}">{t('会员中心')}</a></li>
        <li>{t('投稿')}</li>
    </ul>
</div>

<div class="row row-s-m">
<div class="main">
<div class="main-inner">

{form::header()}

	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<input type="hidden" name="id" value="{$data['id']}">
		<input type="hidden" name="app" value="{$data['app']}">
		<input type="hidden" name="modelid" value="{$data['modelid']}">
		<input type="hidden" name="categoryid" value="{$data['categoryid']}">
		<input type="hidden" name="status" value="{$data['status']}">

		{template $data['app'].'/contribute_post_'.$data['modelid'].'.php'}

	</div><!-- main-body -->
	<div class="main-footer">

		{form::field(array('type'=>'button','value'=>t('保存并发布'),'class'=>'submit btn-highlight','rel'=>'publish'))}

		{if $data['id']}
		{form::field(array('type'=>'button','value'=>t('保存'),'class'=>'submit btn-primary','rel'=>'save'))}
		{else}
		{form::field(array('type'=>'button','value'=>t('保存草稿'),'class'=>'submit btn-primary','rel'=>'draft'))}
		{/if}

		{form::field(array('type'=>'button','value'=>t('返回列表'), 'onclick'=>'history.go(-1)'))}

	</div><!-- main-footer -->

{form::footer()}

<script type="text/javascript" src="{__THEME__}/js/jquery.validate.min.js"></script>
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

			form.find('.submit').disable(true);

			$.loading();
			$.post(action, data, function(msg){

				if( operate == 'save' || operate == 'draft' ){

					// 当保存草稿时候url返回值为内容编号
					if ( operate == 'draft' && msg.url ) $('input[name=id]').val(msg.url);

					form.find('.submit').disable(false);
					msg.url = null;
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

				$(this).addClass('current').siblings("a").removeClass('current');

				$.each(option, function(i, cls){
					$('tr.options').hide();
					$('tr.'+cls).show();
				});
			}
		})
	})
</script>


</div> <!-- main-inner -->
</div> <!-- main -->
<div class="side">
	{template 'member/side.php'}
</div> <!-- side -->
</div> <!-- row -->

{template 'footer.php'}