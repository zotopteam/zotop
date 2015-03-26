{template 'header.php'}

{form::header()}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

			<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
					<tr>
						<td class="label">{form::label(t('移动网址'),'url',false)}</td>
						<td class="input">
							{form::field(array('type'=>'text','name'=>'url','value'=>c('mobile.url')))}
						</td>
					</tr>				
					<tr>
						<td class="label">{form::label(t('移动主题'),'theme',false)}</td>
						<td class="input">

							<ul class="themelist clearfix">
							{loop $themes $id $theme}
								<li {if c('mobile.theme')== $id}class="selected"{/if} title="{$theme['description']}">
									<label>
									<i class="icon icon-selected true"></i>
									<div class="image"><img src="{$theme['image']}"/></div>
									<div class="title textflow">
										<input type="radio" name="theme" value="{$id}" {if c('mobile.theme')== $id}checked="checked"{/if}/>
										&nbsp;{$theme['name']}
									</div>
									</label>
								</li>
							{/loop}
							</ul>

							{form::tips(t('选择主题后，网站将以该主题和模板显示'))}							
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

<style type="text/css">
.themelist{margin:0 0 -40px -20px;zoom:1;}
.themelist li{position:relative;float:left;width:280px;overflow:hidden;margin: 0 0 20px 20px;background-color: #fff;padding:3px 3px 0 3px;box-shadow: 0 1px 1px #eee;border:3px solid #ebebeb;}
.themelist li:hover{border:3px solid #d5d5d5;}
.themelist li .image{width:100%;height:200px;line-height:200px;overflow:hidden;cursor:pointer;}
.themelist li .image img{width:100%;}
.themelist li .title{padding:5px 0;height:30px;line-height:30px;overflow:hidden;font-size:16px;cursor:pointer;}
.themelist li .icon{position:absolute;bottom:0px;right:5px;font-size:28px;height:36px;width:36px;display:none;z-index:200;}
.themelist li input{display:none;}
.themelist li.selected {border:3px solid #66bb00;}
.themelist li.selected .icon{display:block;}
</style>

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

	$(function(){
		$('.themelist li').on('click',function(){
			$(this).addClass('selected').siblings("li").removeClass('selected'); //单选
		})
	})		
</script>

{template 'footer.php'}