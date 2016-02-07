{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">

	<div class="main-header">
		<div class="title">{$title}</div>		
		<div class="action">
			<a href="javascript:;" class="btn btn-icon-text btn-primary btn-add">
				<i class="fa fa-plus"></i><b>{t('添加一项')}</b>
			</a>

			<a href="{U('developer/project/config_formcode')}" class="btn btn-default">
				<i class="fa fa-code"></i><b>{t('表单示例代码')}</b>
			</a>
		</div>
	</div>

	{form id="configform" method="post" action="U('developer/project/config')"}
	<div class="main-body scrollable">
			
		<table class="table sortable" id="configlist">
			<thead>
				<tr>
					<td	class="drag">&nbsp;</td>
					<td class="key">{t('键名')}</td>
					<td class="value">{t('默认值')}</td>
					<td class="manage">{t('操作')}</td>
				</tr>
			</thead>
			<tbody>				
			</tbody>
		</table>
				
	</div>
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div>
	{/form}
</div>

<textarea id="template" class="hidden">
	<tr>
		<td class="drag">&nbsp;</td>
		<td><input type="text" class="form-control required" name="config_key[{0}]" value="{0}" placeholder="{t('以字母开头且只允许小写字母，数字和下划线')}" pattern="^[a-z][a-z0-9_]+$" data-msg-pattern="{t('以字母开头且只允许小写字母，数字和下划线')}" required></td>
		<td><input type="text" class="form-control required" name="config_val[{0}]" value="{1}" required></td>
		<td class="manage"><a href="javascript:;" class="delete"><i class="fa fa-times fa-fw"></i>{t('删除')}</a></td>
	</tr>	
</textarea>

<script>
$(function(){
	// 添加节点
	var addrow = function(key, val){

		key = key || '';
		val = val || '';

		var template = $.validator.format($.trim($("#template").val()));
		var row      = $(template(key,val)).appendTo("#configlist tbody");

		if ( !key && !val ){
			row.find('input:first').focus();
		}				
	}

	var dataset = {json_encode((object)$config)};
		dataset = $.isEmptyObject(dataset) ?  {'':''} : dataset;

	$.each(dataset,function(key,val){
		addrow(key, val);
	});

	$('.btn-add').on('click',function(){
		$form.valid() && addrow();
	});		

	var $form = $('#configform');

	$form.on('change','input',function(){
		$(this).submit();
	});

	$form.on('click','a.delete',function(){
		$(this).closest('tr').remove();
		$form.submit();
	})		

	$form.validate({submitHandler:function(form){
		var action = $(form).attr('action');
		var data   = $(form).serialize();
		$(form).find('.submit').button('loading');
		$.post(action, data, function(msg){
			!msg.state && $.msg(msg);
			$(form).find('.submit').button('reset');
		},'json');
	}});

	$("table.sortable").sortable({
		items: "tbody > tr",
		handle: "td.drag",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		helper: function(e,tr){
			tr.children().each(function(){
				$(this).width($(this).width());
			});
			return tr;
		},
		stop:function(event,ui){
			$form.submit();
		}
	});
});
</script>
{template 'footer.php'}