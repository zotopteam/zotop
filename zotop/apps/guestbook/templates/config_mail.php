{template 'header.php'}

{template 'guestbook/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('邮件通知')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->

	{form action="U('guestbook/config/save')"}

	<div class="main-body scrollable">
		<div class="container-fluid">

			<div class="form-horizontal">
				<div class="form-title">{t('当有新的留言的时候，发送提示邮件')}</div>
				
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('启用'),'addmail',false)}</div>
						<div class="col-sm-8">
							{form::field(array('type'=>'bool','name'=>'addmail','value'=>c('guestbook.addmail')))}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('收件邮箱'),'addmail_sendto',false)}</div>
						<div class="col-sm-8">
							{form::field(array('type'=>'email','name'=>'addmail_sendto','value'=>c('guestbook.addmail_sendto')))}
							{form::tips(t('新留言提示邮件将发送到收件邮箱，未填写收件邮箱将不会发送提示邮件'))}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('邮件标题'),'addmail_title',false)}</div>
						<div class="col-sm-8">
							{form::field(array('type'=>'text','name'=>'addmail_title','value'=>c('guestbook.addmail_title')))}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('邮件内容'),'addmail_sendto',false)}</div>
						<div class="col-sm-8">
							{field type="editor" name="addmail_content" value="c('guestbook.addmail_content')" rows="12" tools="image"}
						</div>
					</div>
				
			</div>
			<div class="form-horizontal">
				<div class="form-title">{t('当留言被回复时候，发送提示邮件到对方留下的信箱')}</div>
				
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('启用'),'replymail',false)}</div>
						<div class="col-sm-8">
							{form::field(array('type'=>'bool','name'=>'replymail','value'=>c('guestbook.replymail')))}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('邮件标题'),'replymail_title',false)}</div>
						<div class="col-sm-8">
							{form::field(array('type'=>'text','name'=>'replymail_title','value'=>c('guestbook.replymail_title')))}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('邮件内容'),'replymail_content',false)}</div>
						<div class="col-sm-8">
							{field type="editor" name="replymail_content" value="c('guestbook.replymail_content')" rows="12" tools="true"}
						</div>
					</div>
				
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->

	{/form}
</div><!-- main -->

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
			},'json');
		}});
	});
</script>
{template 'footer.php'}