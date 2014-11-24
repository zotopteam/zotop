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
				<td class="label">{form::label(t('扩展属性'),'value',true)}</td>
				<td class="input">
					<textarea id="attrs" name="attrs" class="textarea none"></textarea>
					<div class="blank"></div>
					<div id="attrs-container"></div>
					<script id="attrs-template" type="text/x-jsrender">
					<table class="table list border sortable zebra auto">
						<thead>
							<tr>
								<td class="drag"></td>
								<td>{t('属性名称')}</td>
								<td>{t('类型')}</td>
								<td>{t('可选值，多个之间用“,”隔开')}</td>
								<td class="w100 center">{t('作为筛选项')}</td>
								<td class="w100">{t('操作')}</td>
							</tr>
						</thead>
						<tbody>
							<@[for attrs]>
							<tr>
								<td class="drag"></td>
								<td>
									<input type="hidden" data-link="[:id:] name[:'attrid_'+#index] id[:'attrid_'+#index]" class="text short required"/>
									<input type="text" data-link="[:name:] name[:'attrname_'+#index] id[:'attrname_'+#index]" class="text short required"/>
								</td>
								<td>
									<select data-link="[:type:] name[:'attrtype_'+#index] id[:'attrtype_'+#index]" class="select tiny required">
										<option value="radio">{t('单选')}</option>
										<option value="checkbox">{t('多选')}</option>
										<option value="select">{t('下拉')}</option>
										<option value="text">{t('文本')}</option>
									</select>
								</td>
								<td>
									<input type="text" data-link="[:options:] name[:'attroptions_'+#index] id[:'attroptions_'+#index]" class="text medium"/>
								</td>
								<td class="center">
									<label><input type="checkbox" data-link="[:search:] name[:'search'+#index] id[:'search'+#index]" value="1"/></label>
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
								<td colspan="6"><a href="javascript:;" class="add"><i class="icon icon-add"></i> <b>{t('添加一项')}</b></a></td>
							</tr>
						</tfoot>
					</table>
					</script>
				</td>
			</tr>
			<tr>
				<td class="label">{form::label(t('禁用'),'disabled',false)}</td>
				<td class="input">
					{form::field(array('type'=>'bool','name'=>'disabled','value'=>(int)$data['disabled']))}
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
var data = {type:'{$data.type}',attrs:{json_encode($data['attrs'])}};

// 生成属性编号
function makeid(){	
	var max = 0;
	$.each(data.attrs, function(i,attr){
		if ( parseInt(attr.id) > max  ) max = parseInt(attr.id);
	});
	return max + 1;
}


$(function(){
	$.templates("#attrs-template").link("#attrs-container", data).on("click", ".remove", function() {

		$.confirm('{t('删除后无法恢复，确定要删除吗？')}',function(){
			$.observable(data.attrs).remove($.view(this).index);
		});

	}).on("click", ".add", function() {
		$.observable(data.attrs).insert(data.attrs.length, {id: makeid(), name: '',type:'radio',search:false});
	}).on("click", ".up", function(){
		$.observable(data.attrs).move($.view(this).index, $.view(this).index-1);
	}).on("click", ".down", function(){
		$.observable(data.attrs).move($.view(this).index,$.view(this).index+1);
	});
});



//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		start:function (event,ui) {
			ui.item.data('index',  ui.item.index());
		},
		stop:function(event,ui){
			var index = ui.item.index();
			$(this).sortable('cancel');
			$.observable(data.attrs).move(ui.item.data('index'), index);
		}
	});
});

//同步数据
$(function(){
	$('form.form').on('submit',function(){
		$('#attrs').val(JSON.stringify(data.attrs));
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


{template 'footer.php'}