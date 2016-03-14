{template 'header.php'}
{template 'content/admin_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="goback"><a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a></div>
		<div class="title">{$title}</div>
		<div class="breadcrumb">
			<li><a href="{u('content/category')}">{t('栏目管理')}</a></li>
			{loop $parents $p}
			<li><a href="{u('content/category/index/'.$p['id'])}">{$p['name']}</a></li>
			{/loop}
			<li>{if $data['id']}{t('编辑')}{else}{t('添加')}{/if}</li>
		</div>
		<div class="action">
		</div>
	</div><!-- main-header -->

	{form::header()}
	<div class="main-body scrollable">
		<input type="hidden" name="parentid" value="{$data['parentid']}">

		<div class="container-fluid">

			<div class="form-horizontal">

			<div class="form-title">{t('基本设置')}</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目名称'),'name',true)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'required'=>'required'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目别名'),'alias',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'alias','name'=>'alias','value'=>$data['alias'],'data-source'=>'name'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目图片'),'image',false)}</div>
				<div class="col-sm-8">
					{field type="image" name="image" value="$data.image" dataid="$data.dataid" watermark="0"}
					<div class="help-block">{t('根据栏目设计，可以作为栏目banner或者栏目图标使用')}</div>
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('首页模版'),'settings[template_index]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'template','name'=>'settings[template_index]','value'=>$data['settings']['template_index']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('列表页模版'),'settings[template_list]',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'template','name'=>'settings[template_list]','value'=>$data['settings']['template_list']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('内容模型'),'models',false)}</div>
				<div class="col-sm-8">
					<table class="table table-border table-nowrap table-control">
						<thead>
							<tr>
								<td>{t('选择模型')}</td>
								<td>{t('内容页模版')}</td>
							</tr>
						</thead>
						<tbody>
							{loop $models $i $m}
							{if !$m.disabled}
							<tr>
								<td>
									<label>
										<input type="checkbox" name="settings[models][{$i}][enabled]" value="1" class="vm" {if $m['enabled']}checked="checked"{/if}/>
										<span title="{$m['description']}" data-placement="right">{$m['name']}</span>
									</label>
								</td>
								<td>
									{if $m['template']}
										{form::field(array('type'=>'template','name'=>'settings[models]['.$i.'][template]','value'=>$m['template'],'required'=>'required'))}
									{/if}
								</td>
							</tr>
							{/if}
							{/loop}
						</tbody>
					</table>
				</div>
			</div>

			<div class="form-title">{t('搜索优化')}</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目标题'),'title',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'title','value'=>$data['title']))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目关键词'),'keywords',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'text','name'=>'keywords','value'=>$data['keywords'],'data-source'=>'title,description'))}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('栏目描述'),'description',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description'],'placeholder'=>t('合理填写有助于搜索引擎排名优化')))}
				</div>
			</div>

			<div class="form-title">{t('高级设置')}</div>

			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('是否在导航显示'),'apply-setting-childs',false)}</div>
				<div class="col-sm-8">
					{form::field(array('type'=>'bool','name'=>'settings[isnav]','value'=>$data['settings']['isnav']))}
					<div class="help-block">
						{t('栏目是否在网站导航条显示')}
					</div>
				</div>
			</div>


			{if a('member')}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('会员投稿'),'settings[contribute]',false)}</div>
				<div class="col-sm-8">

					{form::field(array('type'=>'radio','options'=>array(0=>t('禁止'), 1=>t('允许')),'name'=>'settings[contribute]','value'=>(int)$data['settings']['contribute']))}

					<div class="input-group" id="contribute_point">
						<span class="input-group-addon">
							{t('投稿积分')}
						</span>
						{form::field(array('type'=>'number','name'=>'settings[contribute_point]','value'=>(int)$data['settings']['contribute_point']))}
						<span class="input-group-addon">
							{t('正数为增加积分，负数为扣除积分')}
						</span>						
					</div>

					<script type="text/javascript">
						$(function(){

							$contribute = $('input[name="settings[contribute]"]');

							$contribute.filter(':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();

							$contribute.on('click',function(){
								$contribute.filter(':checked').val() == 1 ? $('#contribute_point').show() : $('#contribute_point').hide();
							});
						});
					</script>
				</div>
			</div>
			{/if}

			{if $data['childid']}
			<div class="form-group">
				<div class="col-sm-2 control-label">{form::label(t('设置复制'),'apply-setting-childs',false)}</div>
				<div class="col-sm-8">
					<label class="form-control-static"><input type="checkbox" name="apply-setting-childs" value="1"> {t('复制设置到全部子栏目')}</label>
				</div>
			</div>
			{/if}
		</div> 
		</div> <!-- container-fluid -->
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
			$.loading();
			$(form).find('.submit').prop('disabled',true);
			$.post(action, data, function(msg){

				if( !msg.state ){
					$(form).find('.submit').prop('disabled',false);
				}

				$.msg(msg);

			},'json');
		}});
	});

	$(function(){
		$('[name="name"]').change(function(){
			var name = $(this).val();

			$('[name="title"]').val(name);
			$('[name="keywords"]').val(name);
			$('[name="description"]').val(name);
		});
	})
</script>
{template 'footer.php'}