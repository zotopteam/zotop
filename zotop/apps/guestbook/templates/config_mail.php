{template 'header.php'}

<div class="side">
	{template 'guestbook/side.php'}
</div>

{form::header(u('guestbook/config/save'))}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('邮件通知')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('当有新的留言的时候，发送提示邮件')}</caption>
				<tbody>
					<tr>
						<td class="label">{form::label(t('启用'),'addmail',false)}</td>
						<td class="input">
							{form::field(array('type'=>'bool','name'=>'addmail','value'=>c('guestbook.addmail')))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('收件邮箱'),'addmail_sendto',false)}</td>
						<td class="input">
							{form::field(array('type'=>'email','name'=>'addmail_sendto','value'=>c('guestbook.addmail_sendto')))}
							{form::tips(t('新留言提示邮件将发送到收件邮箱，未填写收件邮箱将不会发送提示邮件'))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('邮件标题'),'addmail_title',false)}</td>
						<td class="input">
							{form::field(array('type'=>'text','name'=>'addmail_title','value'=>c('guestbook.addmail_title')))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('邮件内容'),'addmail_sendto',false)}</td>
						<td class="input">
							{form::field(array('type'=>'editor','name'=>'addmail_content','value'=>c('guestbook.addmail_content')))}
						</td>
					</tr>
				</tbody>
			</table>
			<table class="field">
				<caption>{t('当留言被回复时候，发送提示邮件到对方留下的信箱')}</caption>
				<tbody>
					<tr>
						<td class="label">{form::label(t('启用'),'replymail',false)}</td>
						<td class="input">
							{form::field(array('type'=>'bool','name'=>'replymail','value'=>c('guestbook.replymail')))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('邮件标题'),'replymail_title',false)}</td>
						<td class="input">
							{form::field(array('type'=>'text','name'=>'replymail_title','value'=>c('guestbook.replymail_title')))}
						</td>
					</tr>
					<tr>
						<td class="label">{form::label(t('邮件内容'),'replymail_content',false)}</td>
						<td class="input">
							{form::field(array('type'=>'editor','name'=>'replymail_content','value'=>c('guestbook.replymail_content')))}
						</td>
					</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').disable(false);
			},'json');
		}});
	});
</script>
{template 'footer.php'}