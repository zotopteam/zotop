{template 'header.php'}
{template 'developer/project_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">

		{form::header()}
		<div class="container-fluid">
		<div class="form-horizontal">
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('源文件'),'source',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'select','options'=>developer::controllers(),'name'=>'source','value'=>''))}
					{form::tips(t('以该源文件为模板创建新文件'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('新控制器名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','placeholder'=>t('只允许小写字母、数字和下划线'),'required'=>'required','pattern'=>'[a-z0-9_]+','data-msg-pattern'=>t('只允许小写字母、数字和下划线'),'remote'=>u('developer/make/check/controller')))}
					<div class="help-block">{t('控制器文件')}: {$path}{DS}<span class="name text-primary">______</span>.php</div>
					<div class="help-block">{t('控制器类名')}: {$app.id}_controller_<span class="name text-primary">______</span></div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('新控制器标题'),'title',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'title','required'=>'required'))}
					<div class="help-block">{t('简短标题，如新闻、留言板等')}</div>
				</div>
			</div>				
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('新控制器描述'),'description',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'description','required'=>'required'))}
					<div class="help-block">{t('该控制器的功能描述或者说明')}</div>
				</div>
			</div>					
			<div class="form-group">
				<div class="col-sm-2 control-label"></div>
				<div class="col-sm-8">
					{form::field(array('type'=>'submit','value'=>t('立即创建')))}
				</div>
			</div>			
		</div>
		</div>
		{form::footer()}

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('在 $1 创建新的控制器文件', $path)}</div>
	</div><!-- main-footer -->
</div><!-- main -->

<script type="text/javascript">

	$(function(){
		$('[name=name]').on('change',function(){
			var value = $(this).val() ? $(this).val() : '______';
			$('span.name').text(value);
		})
	})

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				$.msg(msg);
				$(form).find('.submit').button('reset');
				msg.state && $(form).get(0).reset();				
			},'json');
		}});
	});
</script>

{template 'footer.php'}