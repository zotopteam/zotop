{template 'header.php'}

{template 'site/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	{form class="form-horizontal"}
	<div class="main-body scrollable">
		
		<div class="container-fluid">

			<div class="form-group">
				<label for="contacts" class="col-sm-2 control-label">{t('联系人')}</label>
				<div class="col-sm-8">
					{field type="text" name="contacts" value="c('site.contacts')"}
				</div>
			</div>
			<div class="form-group">
				<label for="phone" class="col-sm-2 control-label">{t('电话')}</label>
				<div class="col-sm-8">
					{field type="text" name="phone" value="c('site.phone')"}
				</div>
			</div>
			<div class="form-group">
				<label for="fax" class="col-sm-2 control-label">{t('传真')}</label>
				<div class="col-sm-8">
					{field type="text" name="fax" value="c('site.fax')"}
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">{t('Email')}</label>
				<div class="col-sm-8">
					{field type="text" name="email" value="c('site.email')"}
				</div>
			</div>
			<div class="form-group">
				<label for="address" class="col-sm-2 control-label">{t('地址')}</label>
				<div class="col-sm-8">
					{field type="text" name="address" value="c('site.address')"}
				</div>
			</div>				
			<div class="form-group">
				<label for="qq" class="col-sm-2 control-label">{t('QQ')}</label>
				<div class="col-sm-8">
					{field type="text" name="qq" value="c('site.qq')"}
					<div class="help-block">{t('多个qq之间用英文逗号隔开')}</div>
				</div>
			</div>
			<div class="form-group">
				<label for="skype" class="col-sm-2 control-label">{t('Skype')}</label>
				<div class="col-sm-8">
					{field type="text" name="skype" value="c('site.skype')"}
				</div>
			</div>
			<div class="form-group">
				<label for="weixin" class="col-sm-2 control-label">{t('微信')}</label>
				<div class="col-sm-8">
					{field type="text" name="weixin" value="c('site.weixin')"}
				</div>
			</div>
			<div class="form-group">
				<label for="weibo" class="col-sm-2 control-label">{t('微博')}</label>
				<div class="col-sm-8">
					{field type="url" name="weibo" value="c('site.weibo')"}
				</div>
			</div>						
			<div class="form-group">
				<label for="wangwang" class="col-sm-2 control-label">{t('旺旺')}</label>
				<div class="col-sm-8">
					{field type="text" name="wangwang" value="c('site.wangwang')"}
				</div>
			</div>	

		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form}

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

	$(function(){
		$('.themelist li').on('click',function(){
			$(this).addClass('selected').siblings("li").removeClass('selected'); //单选
		})
	})	
</script>
{template 'footer.php'}