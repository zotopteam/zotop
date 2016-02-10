{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{t('系统设置')}</div>
		<ul class="nav nav-tabs">
			{loop $navbar $k $n}
			<li{if ZOTOP_ACTION == $k} class="active"{/if}>
				<a href="{$n.href}"><i class="{$n.icon} fa-fw"></i><span class="hidden-xs hidden-sm">{$n.text}</span></a>
			</li>
			{/loop}
		</ul>
	</div><!-- main-header -->
	{form::header()}
	<div class="main-body scrollable">
		
		<div class="container-fluid">

			<div class="form-horizontal">

				<div class="form-title">{t('基本设置')}</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站名称'),'name',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'name','value'=>c('site.name'),'maxlength'=>20,'required'=>'required'))}
						{form::tips(t('当前网站的名称，如<b>逐涛网</b>'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站网址'),'url',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'url','name'=>'url','value'=>c('site.url'),'required'=>'required'))}
						{form::tips(t('当前网站的网址，格式为：<b>http://www.zotop.com</b>'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站主题'),'theme',true)}</div>
					<div class="col-sm-10">
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
						{form::tips(t('选择主题后，网站将以该主题和模板显示'))}
					</div>
				</div>
			</div>


			<div class="form-horizontal">
			
				<div class="form-title">{t('搜索优化')}</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站标题'),'title',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'title','value'=>c('site.title'),'required'=>'required'))}
						{form::tips(t('网站标题一般显示在标题栏上，适当填写可以优化搜索'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站关键词'),'keywords',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'keywords','value'=>c('site.keywords'),'required'=>'required'))}
						{form::tips(t('请分析并填写网站关键词(Meta Keywords)，多个关键词使用空格隔开'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('网站描述'),'description',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'textarea','name'=>'description','value'=>c('site.description'),'required'=>'required'))}
						{form::tips(t('请填写网站描述信息(Meta Description)'))}
					</div>
				</div>
			</div>

			<div class="form-horizontal">
				<div class="form-title">{t('网站状态')}</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('关闭网站'),'closed',false)}</div>
					<div class="col-sm-10">
						{form::field(array('type'=>'bool','name'=>'closed','value'=>c('site.closed')))}
						{form::tips(t('网站关闭时不影响网站后台访问并且管理员登陆系统之后可以访问网站'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('关闭原因'),'closedreason',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'textarea','name'=>'closedreason','value'=>c('site.closedreason')))}
					</div>
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div><!-- main-footer -->
	{form::footer()}

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