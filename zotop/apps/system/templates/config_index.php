{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('全局设置')}</div>
		<ul class="nav nav-tabs tabdropable">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}>
				<a href="{$n['href']}">{if $n['icon']}<i class="{$n['icon']}"></i> {/if}<span>{$n['text']}</span></a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	{form::header()}
	<div class="main-body scrollable">
					<table class="field">
				<caption>{t('基本设置')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('网站名称'),'name',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'name','value'=>c('site.name'),'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('当前网站的名称，如<b>逐涛网</b>'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('网站网址'),'url',true)}</td>
					<td class="input">
						{form::field(array('type'=>'url','name'=>'url','value'=>c('site.url'),'required'=>'required'))}
						{form::tips(t('当前网站的网址，格式为：<b>http://www.zotop.com</b>'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('网站主题'),'theme',true)}</td>
					<td class="input">
						<ul class="themelist clearfix">
						{loop $themes $id $theme}
							<li {if c('site.theme')== $id}class="selected"{/if} title="{$theme['description']}">
								<label>
								<i class="icon icon-selected true"></i>
								<div class="image"><img src="{$theme['image']}"/></div>
								<div class="title textflow">
									<input type="radio" name="theme" value="{$id}" {if c('site.theme')== $id}checked="checked"{/if}/>
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



			<table class="field">
				<caption>{t('搜索优化')}</caption>
				<tbody>
					<tr>
					<td class="label">{form::label(t('网站标题'),'title',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'title','value'=>c('site.title'),'required'=>'required'))}
						{form::tips(t('网站标题一般显示在标题栏上，适当填写可以优化搜索'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('网站关键词'),'keywords',true)}</td>
					<td class="input">
						{form::field(array('type'=>'text','name'=>'keywords','value'=>c('site.keywords'),'required'=>'required'))}
						{form::tips(t('请分析并填写网站关键词(Meta Keywords)，多个关键词使用空格隔开'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('网站描述'),'description',true)}</td>
					<td class="input">
						{form::field(array('type'=>'textarea','name'=>'description','value'=>c('site.description'),'required'=>'required'))}
						{form::tips(t('请填写网站描述信息(Meta Description)'))}
					</td>
				</tr>
				</tbody>
			</table>

			<table class="field">
				<caption>{t('网站状态')}</caption>
				<tbody>
				<tr>
					<td class="label">{form::label(t('关闭网站'),'closed',false)}</td>
					<td class="input">
						{form::field(array('type'=>'bool','name'=>'closed','value'=>c('site.closed')))}
						{form::tips(t('网站关闭时不影响网站后台访问并且管理员登陆系统之后可以访问网站'))}
					</td>
				</tr>
				<tr>
					<td class="label">{form::label(t('关闭原因'),'closedreason',false)}</td>
					<td class="input">
						{form::field(array('type'=>'textarea','name'=>'closedreason','value'=>c('site.closedreason')))}
					</td>
				</tr>
				</tbody>
			</table>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<style type="text/css">
.themelist{margin:0 0 -40px -20px;zoom:1;}
.themelist li{position:relative;float:left;width:280px;overflow:hidden;margin: 10px 0 10px 20px;background-color: #fff;padding:3px 3px 0 3px;box-shadow: 0 1px 1px #eee;border:3px solid #ebebeb;}
.themelist li:hover{border:3px solid #d5d5d5;}
.themelist li .image{width:100%;height:200px;line-height:200px;overflow:hidden;cursor:pointer;}
.themelist li .image img{width:100%;}
.themelist li .title{padding:5px 0;height:30px;line-height:30px;overflow:hidden;font-size:16px;cursor:pointer;}
.themelist li .icon{position:absolute;bottom:0px;right:5px;font-size:28px;height:36px;width:36px;display:none;z-index:200;}
.themelist li input{display:none;}
.themelist li.selected {border:3px solid #66bb00;}
.themelist li.selected .icon{display:block;}
</style>

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