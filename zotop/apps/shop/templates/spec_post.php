{template 'header.php'}
<div class="side">
{template 'shop/admin_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="field">
			<tbody>
			<tr>
				<td class="label">{form::label(t('名称'),'name',true)}</td>
				<td class="input">
					{form::field(array('type'=>'text','name'=>'name','value'=>$data['name'],'minlength'=>2,'maxlength'=>20,'required'=>'required'))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('描述'),'description',false)}</td>
				<td class="input">
					{form::field(array('type'=>'textarea','name'=>'description','value'=>$data['description']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('规格'),'value',true)}</td>
				<td class="input">
					<textarea id="value" name="value" class="textarea none"></textarea>
					<div class="blank"></div>
					<div id="value-container"></div>
					<script id="value-template" type="text/x-jsrender">
					{loop $types $k $t}
						<label class="f14 pointer" title="{t('规格类型:%s',$t)}"><input type="radio" name="type" value="{$k}" data-link="[:type:]"/> {$t}</label>&nbsp;
					{/loop}
					<div class="blank"></div>
					<div class="blank"></div>
					<table class="table list border sortable zebra auto">
						<thead>
							<tr>
								<td class="drag"></td>
								<td>{t('规格值')}</td>
								<td data-link="class[:~root.type == 'text' ? 'none' : 'w200']">{t('图片')}</td>
								<td class="w100">{t('操作')}</td>
							</tr>
						</thead>
						<tbody>
							<@[for values]>
							<tr>
								<td class="drag"></td>
								<td>
									<input type="text" data-link="[:text:] name[:'text_'+#index] id[:'text_'+#index]" class="text medium required"/>
								</td>
								<td data-link="class[:~root.type == 'text' ? 'none' : 'w200']">
									<div data-link="class[:image?'image-preview':'image-preview image-none']">
										<div class="thumb" style="height:28px;width:28px;"><img data-link="src[:image:]"/></div>
									</div>
									<input type="hidden" data-link="[:image:] name[:'image_'+#index] id[:'image_'+#index]" class="text short"/>
									<a class="btn btn-icon-text upload" href="javascript:;" data-link="id[:'upload_'+#index]"><i class="icon icon-upload"></i><b>{t('上传')}</b></a>
								</td>
								<td>
									<a href="javascript:;" class="up"><i class="icon icon-up"></i></a>&nbsp;
									<a href="javascript:;" class="down"><i class="icon icon-down"></i></a>&nbsp;
									<a href="javascript:;" class="remove"><i class="icon icon-remove"></i></a>
								</td>
							</tr>
							<[/for]>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"><a href="javascript:;" class="add"><i class="icon icon-add"></i> <b>{t('添加一行')}</b></a></td>
							</tr>
						</tfoot>
					</table>
					</script>
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('显示'),'show',false)}</td>
				<td class="input">
					{form::field(array('type'=>'radio','options'=>$shows,'name'=>'show','value'=>$data['show']))}
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>$data['disabled']))}
				</td>
			</tr>
			</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}

		{form::field(array('type'=>'button','value'=>t('取消'), 'onclick'=>'history.go(-1)'))}
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}

<!-- 模板绑定 -->
<script type="text/javascript" src="{a('system.url')}/common/js/jquery.views.min.js"></script>
<script type="text/javascript">
var data = {type:'{$data.type}',values:{json_encode($data['value'])}};

var upload = {
		url : "{u('system/upload/uploadprocess')}",
		multi:false,
		dragdrop:false,
		resize:{width:{c('shop.spec_width')}, height:{c('shop.spec_height')}, quality:100, crop:true},
		maxsize:'20mb',
		fileext: 'jpg,jpeg,gif,png',
		filedescription : '{t('选择文件')}',
		progress : function(up,file){
			up.self.html('{t('上传中……')}' +up.total.percent + '%');
		},
		uploaded : function(up,file,msg){
			if ( msg.state ){
				up.self.prev('input').val(msg.file.url).trigger('change');
			}
			$.msg(msg);			
		},
		complete : function(up,files){
			up.self.html(up.content);
		},
		error : function(error,detail){
			$.error(detail);
		}
};

$(function(){
	$.templates("#value-template").link("#value-container", data).on("click", ".remove", function() {
		$.observable(data.values).remove($.view(this).index);
	}).on("click", ".add", function() {
		$.observable(data.values).insert(data.values.length, {text: ''});
		$('#upload_' + (data.values.length-1) ).upload(upload);
	}).on("click", ".up", function(){
		$.observable(data.values).move($.view(this).index, $.view(this).index-1);
	}).on("click", ".down", function(){
		$.observable(data.values).move($.view(this).index,$.view(this).index+1);
	});
});



//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		handle: "td.drag",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		start:function (event,ui) {
			ui.item.data('index',  ui.item.index());
		},
		stop:function(event,ui){
			var index = ui.item.index();
			$(this).sortable('cancel');
			$.observable(data.values).move(ui.item.data('index'), index);
		}
	});
});

//同步数据
$(function(){
	$('form.form').on('submit',function(){
		$('#value').val(JSON.stringify(data.values));
	})
});


</script>

<!-- 验证提交 -->
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function(){

		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$(form).find('.submit').disable(true);
			$.loading();
			$.post(action, data, function(msg){
				if ( !msg.state ){
					$(form).find('.submit').disable(false);
				}
				$.msg(msg);
			},'json');
		}});
	});
</script>

<!-- 上传 -->
<script type="text/javascript" src="{A('system.url')}/common/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/common/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){

		var uploader = $("a.upload").upload(upload);
	});

	$(function(){
		$( '.image-preview' ).tooltip({placement:'auto bottom',container:'body',html:true,title:function(){
			var img = $(this).find('img').attr('src');
			return img ? '<img src="'+img+'" style="max-width:400px;"/>' : '';
		}});
	});
</script>

{template 'footer.php'}