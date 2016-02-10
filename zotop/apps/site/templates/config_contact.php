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

<style type="text/css">
.themelist{margin:0 0 -30px 0;zoom:1;padding:0}
.themelist li{position:relative;float:left;width:280px;overflow:hidden;margin:10px 20px 10px 0;background-color:#fff;padding:4px 4px 0 4px;border:3px solid #ebebeb;border-radius:4px;overflow:hidden;}
.themelist li:hover{border:3px solid #d5d5d5;}
.themelist li .image{width:100%;height:180px;line-height:0;overflow:hidden;cursor:pointer;}
.themelist li .image img{width:100%;}
.themelist li .title{padding:5px 0;height:30px;line-height:30px;overflow:hidden;font-size:16px;font-weight:normal;cursor:pointer;}
.themelist li .fa{position:absolute;top:4px;right:4px;display:none;z-index:20000;color:#fff;font-size:16px;}
.themelist li input{display:none;}
.themelist li.selected {border:3px solid #66bb00;}
.themelist li.selected:after{width:0;height:0;border-top:40px solid #66bb00;border-left:40px solid transparent;position:absolute;display:block;right:0;content:"";top:0;z-index:1001;}
.themelist li.selected .fa{display:block;}
</style>

<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.loading();
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