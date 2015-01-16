{template 'header.php'}

<div class="side">
	{template 'kefu/admin_side.php'}
</div>


{form::header(u('kefu/config/save'))}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
			<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('头部'),'header',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'header','value'=>c('kefu.header'),'required'=>'required'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('尾部'),'footer',false)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'footer','value'=>c('kefu.footer')))}
					</td>
				</tr>

				<tr>
					<td class="label">{form::label(t('显示位置'),'position',false)}</td>
					<td class="input">
						{form::field(array('type'=>'radio','options'=>array('right'=>t('右侧'),'left'=>t('左侧')),'name'=>'position','value'=>c('kefu.position')))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('顶部距离'),'offsety',false)}</td>
					<td class="input">
						<div class="input-group">
						{form::field(array('type'=>'number','name'=>'offsety','value'=>(int)c('kefu.offsety')))}
						<span class="input-group-addon">px</span>
						</div>
						{form::tips(t('离浏览器顶部距离'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('侧边距离'),'offsetx',false)}</td>
					<td class="input">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'offsetx','value'=>(int)c('kefu.offsetx')))}
							<span class="input-group-addon">px</span>
						</div>
						{form::tips(t('离浏览器左边或右边的距离'))}
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
<script type="text/javascript" src="{a('system.url')}/common/js/jquery.validate.min.js"></script>
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