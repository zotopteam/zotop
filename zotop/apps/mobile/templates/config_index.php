{template 'header.php'}


<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">

		<div class="container-fluid">
			
			<fieldset class="form-horizontal">
				<legend>{t('基本设置')}</legend>
					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('移动网址'),'url',false)}</div>
						<div class="col-sm-6">
							{form::field(array('type'=>'url','name'=>'url','value'=>c('mobile.url')))}
							{form::tips(t('绑定域名后可通过域名访问移动版，如：http://m.zotop.com'))}
						</div>						
					</div>

					<div class="form-group">
						<div class="col-sm-2 control-label">{form::label(t('移动主题'),'theme',true)}</div>					
						<div class="col-sm-6">
							<ul class="themelist clearfix">
							{loop $themes $id $theme}
								<li {if c('site.theme')== $id}class="selected"{/if} title="{$theme['description']}">
									<label>
									<i class="fa fa-check"></i>
									<div class="image"><img src="{$theme['image']}"/></div>
									<div class="title text-overflow">
										<input type="radio" name="theme" value="{$id}" {if c('site.theme')== $id}checked="checked"{/if}/>
										&nbsp;{$theme['name']}
									</div>
									</label>
								</li>
							{/loop}
							</ul>					
							{form::tips(t('移动版使用的主题，选择主题后网站将以该主题显示'))}							
						</div>
					</div>
			</fieldset>
		</div>	
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}
</div><!-- main -->


<style type="text/css">
.themelist{margin:0 0 -30px 0;zoom:1;padding:0;}
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