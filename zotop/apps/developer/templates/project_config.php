{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">

	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('developer')}">{t('开发助手')}</a>
			<s class="arrow">></s>
			<a href="{u('developer/project')}">{$app['name']}</a>
			<s class="arrow">></s>			
			{$title}
		</div>			
		<div class="action">
			<a href="javascript:;" class="btn btn-icon-text btn-highlight" onclick="configlist.addrow()"><i class="icon icon-add"></i><b>{t('添加一项')}</b></a>
		</div>
	</div>

	<form id="configform" method="post" action="{U('developer/project/config')}">
	<div class="main-body scrollable">
			
			<table class="table list zebra sortable" id="configlist">
				<thead>
					<tr>
						<td	class="drag">&nbsp;</td>
						<td class="w400">{t('键名')}</td>
						<td>{t('键值')}</td>
						<td class="w200">{t('操作')}</td>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
				
	</div>
	<div class="main-footer">
		{form::field(array('type'=>'submit','value'=>t('保存')))}
	</div>
	</form>
</div>



<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	var configlist = {};

	// 添加节点
	configlist.addrow = function(key, val){
		var row = $('<tr>'+
		'<td class="drag">&nbsp;</td>'+
		'<td><input type="text" class="text required" style="width:60%" name="config_key[]" value="'+ ( key || '' ) +'" placeholder="{t('只允许英文，数字和下划线')}"  pattern="^[a-z0-9_]+$"></td>'+
		'<td><input type="text" class="text required" style="width:60%" name="config_val[]" value="'+ ( val || '' )+'"></td>'+
		'<td><a href="javascript:;" class="delete" onclick="configlist.delrow(this)"><i class="icon icon-delete"></i> {t('删除')}</a></td>'+
		'</tr>');

		row.appendTo('#configlist tbody');

		if ( !key && !val ){
			row.find('input:first').focus();
		}				
	}

	// 删除节点
	configlist.delrow = function(ele) {
		$(ele).closest('tr').remove();
	}

// 生成默认数据
$(function(){

	var dataset = {json_encode((object)$config)};
		dataset = $.isEmptyObject(dataset) ?  {'':''} : dataset;

	for (var key in dataset) {
		configlist.addrow(key, dataset[key]);
	}
});

//sortable
$(function(){
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
			//$('#configform').submit();
		}
	});
});

$(function(){
	$('#configform').on('change','input',function(){
		//$(this).submit();
	});

	$('#configform').validate({submitHandler:function(form){
		var action = $(form).attr('action');
		var data = $(form).serialize();
		$.post(action, data, function(msg){
			$.msg(msg);
		},'json');
	}});
});
</script>
{template 'footer.php'}