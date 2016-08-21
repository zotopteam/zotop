{template 'header.php'}

<div class="main">
	<div class="main-header">
        <div class="goback"><a href="javascript:location.href=document.referrer;"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title}</div>
		<div class="action">
		</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">

		<div class="container-fluid">
			<div class="form-horizontal">

				<div class="form-group">
					<div class="col-sm-1 control-label">{form::label(t('类型'),'type',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'radio','options'=>m('block.block.types'),'name'=>'type','value'=>$data['type']))}
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-1 control-label">{form::label(t('名称'),'name',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
					</div>
				</div>
                <div class="form-group">
                    <div class="col-sm-1 control-label">{form::label(t('描述'),'description',false)}</div>
                    <div class="col-sm-8">
                        {form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
                    </div>
                </div>                			
				<div class="form-group">
					<div class="col-sm-1 control-label">{form::label(t('分类'),'type',true)}</div>
					<div class="col-sm-8">
						{form::field(array('type'=>'select','options'=>arr::hashmap($categories,'id','name'),'name'=>'categoryid','value'=>$data['categoryid']))}
					</div>
				</div>
				
				<div class="extend"></div>

				<div class="form-group">
					<div class="col-sm-1 control-label">{form::label(t('模版'),'template',true)}</div>
					<div class="col-sm-8">

						{form::field(array('type'=>'template','name'=>'template','value'=>$data['template'],'required'=>'required'))}

						{form::tips(t('模板决定区块的显示效果，支持 {literal}{$id}/{$name}/{$data}{/literal} 等标签'))}
					</div>
				</div>

			</div>
		</div>

	</div><!-- main-body -->
	<div class="main-footer">

		{form::field(array('type'=>'hidden','name'=>'operate','value'=>'save'))}

		{if $data['data']}
        
            {field type="button" value="t('保存并下一步')" data-loading-text="t('保存中…')" class="submit btn-success" rel="submit"}

		{/if}

        {field type="button" value="t('保存并下一步')" data-loading-text="t('保存中…')" class="submit btn-primary" rel="save"}

        {field type="cancel" value="t('取消')" class="pull-right"}

	</div><!-- main-footer -->
	{form::footer()}

</div><!-- main -->

<script type="text/javascript">

	var data = {json_encode($data)};	
	var form = $('form.form');

	$(function(){
		form.on('click','button.submit',function(){
			form.find('input[name=operate]').val($(this).attr('rel'));
			form.submit();
		})
	});


	$(function(){
		form.validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').prop('disabled',true);
			$.post(action, data, function(msg){
				$.msg(msg);
				!msg.state && $(form).find('.submit').prop('disabled',false);
			},'json');
		}});
	});

	// init
	function forminit(type){

		var post = $.extend({}, data);
			post.type = type;
			post.template = post.type == data.type ? data.template : 'block/' + post.type + '.php';

		$('[name=template]').val(post.template);

		$('.extend').load("{U('block/admin/postextend')}", post).html('<i class="fa fa-spinner fa-spin"></i>');
	}

	// type init change
	$(function(){
		forminit($('[name=type]:checked').val());

		$('[name=type]').on('click',function(){
			forminit($(this).val());
		});
	});

</script>
{template 'footer.php'}