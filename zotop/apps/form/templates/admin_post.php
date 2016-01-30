{template 'header.php'}
{template 'form/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="{U('form/admin')}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>

		<div class="title">{$title}</div>

		{if $data.id}
		<div class="btn-group hidden">
			<a href="{U('form/data/index/'.$data.id)}" class="btn btn-default"><i class="fa fa-list"></i> {t('数据管理')}</a>
			<a href="{U('form/field/index/'.$data.id)}" class="btn btn-default"><i class="fa fa-database"></i> {t('字段管理')}</a>
			<a href="{U('form/admin/edit/'.$data.id)}" class="btn btn-primary"><i class="fa fa-cog"></i> {t('表单设置')}</a>			
		</div>
		{/if}
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		<div class="container-fluid">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('表单名称'),'name',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required','remote'=>U('form/admin/check/name/'.$data['name'])))}
						{form::tips('当前表单的名称，如“留言板”、“反馈”等')}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('数据表名'),'table',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'table','value'=>$data['table'],'minlength'=>4,'maxlength'=>32,'required'=>'required','remote'=>U('form/admin/checktable/'.$data['table'])))}
						{form::tips('数据库中的数据表名称，只能由小写英文字母、数字和下划线组成，不含数据表前缀，如：“feedback”')}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('表单说明'),'description',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('前台列表页面'),'settings[list]',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'bool','name'=>'settings[list]','value'=>(int)$data['settings']['list']))}
					</div>
				</div>
				<div class="form-group options-list" {if $data['settings']['list']==0}style="display:none"{/if}>
					<div class="col-sm-2 control-label">{form::label(t('前台列表模板'),'settings[listtemplate]',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'template','name'=>'settings[listtemplate]','value'=>$data['settings']['listtemplate'],'required'=>'required'))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('前台详细页面'),'settings[detail]',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'bool','name'=>'settings[detail]','value'=>(int)$data['settings']['detail']))}
					</div>
				</div>
				<div class="form-group options-detail" {if $data['settings']['detail']==0}style="display:none"{/if}>
					<div class="col-sm-2 control-label">{form::label(t('前台详细模板'),'settings[detailtemplate]',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'template','name'=>'settings[detailtemplate]','value'=>$data['settings']['detailtemplate'],'required'=>'required'))}

					</div>
				</div>								

				<div class="form-group">
					<div class="col-sm-2 control-label">{form::label(t('前台发布数据'),'settings[post]',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'bool','name'=>'settings[post]','value'=>(int)$data['settings']['post']))}
					</div>
				</div>

				<div class="form-group options-post" {if $data['settings']['post']==0}style="display:none"{/if}>
					<div class="col-sm-2 control-label">{form::label(t('前台发布模板'),'settings[posttemplate]',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'template','name'=>'settings[posttemplate]','value'=>$data['settings']['posttemplate'],'required'=>'required'))}
					</div>
				</div>			

				<div class="form-group options-post" {if $data['settings']['post']==0}style="display:none"{/if}>
					<div class="col-sm-2 control-label">{form::label(t('开启验证码'),'settings[post_captcha]',false)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'bool','name'=>'settings[post_captcha]','value'=>$data['settings']['post_captcha']))}
					</div>
				</div>
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->


<script type="text/javascript">
	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data   = $(form).serialize();
			$(form).find('.submit').button('loading');
			$.post(action, data, function(msg){
				if ( !msg.state ){
					$(form).find('.submit').button('reset');
				}
				$.msg(msg);
			},'json');
		}});
	});

	$(function(){
		$('[name="settings[list]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-list').show();
			}else{
				$('.options-list').hide();
			}
		});

		$('[name="settings[detail]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-detail').show();
			}else{
				$('.options-detail').hide();
			}
		});

		$('[name="settings[post]"]').on('click',function(){
			if( $(this).val()==1 ){
				$('.options-post').show();
			}else{
				$('.options-post').hide();
			}
		});					
	})
</script>

{template 'footer.php'}