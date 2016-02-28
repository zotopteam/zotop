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
				<div class="col-sm-2 control-label">{form::label(t('首页关键词'),'keywords',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'index_keywords','value'=>c('site.index_keywords'),'required'=>'required'))}
					{form::tips(t('网站首页使用的关键词'))}
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('网站描述'),'description',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>c('site.description'),'required'=>'required'))}
					{form::tips(t('请填写网站描述信息(Meta Description)'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('Meta标签'),'description',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'meta','value'=>c('site.meta'),'placeholder'=>t('可放置<meta>、<script>、<style>和<link>标签')))}
					{form::tips(t('设置网站head中的额外的Meta/script/style和link标签'))}
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
.themelist li .fa{position:absolute;top:4px;right:4px;display:none;z-index:2;color:#fff;font-size:16px;}
.themelist li input{display:none;}
.themelist li.selected {border:3px solid #66bb00;}
.themelist li.selected:after{width:0;height:0;border-top:40px solid #66bb00;border-left:40px solid transparent;position:absolute;display:block;right:0;content:"";top:0;z-index:1;}
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
				msg.state && location.reload();
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