{template 'header.php'}

<div class="side">
{template 'content/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('内容设置')}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<!--configs: newflag, newflag_expires, autothumb, autosummary, autokeywords, attachment.delete, thumb_resize, thumb_width, thumb_height, thumb_quality, image_resize, image_width, image_height, image_quality, category_image_resize, category_image_width, category_image_height, category_image_quality-->
			<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('newflag'),'newflag',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'newflag','value'=>c('content.newflag')))}
						{form::tips(t('请修改提示信息'))}
					</td>
				</tr>
				</tbody>
			</table>

			{t('开发助手暂时无法自动同步修改配置模板，请自行修改')}
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