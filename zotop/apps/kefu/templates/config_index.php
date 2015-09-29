{template 'header.php'}

{template 'kefu/admin_side.php'}



<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('设置')}</div>
		<div class="action">

		</div>
	</div><!-- main-header -->

	{form action="u('kefu/config/save')"}
	<div class="main-body scrollable">

		<div class="container-fluid">
			<fieldset class="form-horizontal">

				<div class="form-title">{t('基本设置')}</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('头部文字'),'header',true)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'text','name'=>'header','value'=>c('kefu.header'),'required'=>'required'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('尾部文字'),'footer',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'text','name'=>'footer','value'=>c('kefu.footer')))}
					</div>
				</div>

				<div class="form-title">{t('显示效果')}</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('显示位置'),'position',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'radio','options'=>array('right'=>t('右侧'),'left'=>t('左侧')),'name'=>'position','value'=>c('kefu.position')))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('顶部距离'),'offsety',false)}</div>
					<div class="col-sm-4">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'offsety','value'=>(int)c('kefu.offsety')))}
							<span class="input-group-addon">px</span>
						</div>
						{form::tips(t('离浏览器顶部距离'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('侧边距离'),'offsetx',false)}</div>
					<div class="col-sm-4">
						<div class="input-group">
							{form::field(array('type'=>'number','name'=>'offsetx','value'=>(int)c('kefu.offsetx')))}
							<span class="input-group-addon">px</span>
						</div>
						{form::tips(t('离浏览器左边或右边的距离'))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('面板主色'),'color',true)}</div>
					<div class="col-sm-4">
						{form::field(array('type'=>'color','name'=>'color','value'=>c('kefu.color'),'required'=>'required'))}
						{form::tips(t('在线客服面板主色调'))}
					</div>
				</div>												
			</fieldset>
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
			var data   = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$(form).find('.submit').button('reset');
				$.msg(msg);
			},'json');
		}});
	});
</script>
{template 'footer.php'}