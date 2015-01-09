{template 'header.php'}

<div class="side side-main">
	{template 'developer/project_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>	
		<div class="action">
				<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/createtable')}" data-width="600" data-height="300">
					<i class="icon icon-add"></i>
					<b>{t('新建数据表')}</b>
				</a>
		</div>
	</div>
	<div class="main-body scrollable">

			{if empty($tables)}
				<div class="nodata">{t('没有找到任何数据表')}</div>
			{else}
			<form>
			<table class="table zebra list">
				<thead>
					<tr>
						<td>{t('名称')}({t('真实名称')})</td>
						<td class="w260">{t('说明')}</td>
						<td class="w60">{t('行数')}</td>
						<td class="w80">{t('大小')}</td>
						<td class="w80">{t('类型')}</td>
						<td class="w120">{t('整理')}</td>
					</tr>
				</thead>
				<tbody>
				{loop $tables $id $table}
					<tr class="{if $i%2==0}even{else}odd{/if}">
						<td>
							<div class="title"><b class="name">{$id}</b><span>( {$table[name]} )</span></div>
							<div class="manage">
								<a href="{u('developer/schema/'.$id)}">{t('表结构')}</a>
								<s>|</s>
								<a class="dialog-open" href="{U('developer/project/edittable/'.$id)}" data-width="600" data-height="300">{t('表设置')}</a>
								<s>|</s>
								<a class="dialog-confirm" href="{u('developer/database/delete/'.$id)}">{t('删除')}</a>
							</div>
						</td>
						<td>
							{$table['comment']}
						</td>
						<td>{$table['rows']}</td>
						<td>{format::size($table['size'])}</td>

						<td>{$table['engine']}</td>
						<td>{$table['collation']}</td>
					</tr>
					{$i++}
				{/loop}
				<tbody>
			</table>
			</form>
			{/if}

	</div>
	<div class="main-footer">
		<div class="tips">{t('管理 apps 目录下在建的应用')}</div>
	</div>
</div>

<style type="text/css">
div.icon {float:left;padding:20px;width:100px;}
div.icon img{width:81px;}
div.info {margin-left:150px;}

dl.list dt a.btn{float:right;}
</style>


<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	var configlist = {};

	// 添加节点
	configlist.addrow = function(key, val){
		var row = '<tr>'+
		'<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;</td>'+
		'<td><input type="text" class="text required" style="width:90%" name="config_key[]" value="'+ ( key || '' ) +'" placeholder="{t('只允许英文，数字和下划线')}" data-placement="top" pattern="^[a-z0-9_]+$"></td>'+
		'<td><input type="text" class="text required" style="width:90%" name="config_val[]" value="'+ ( val || '' )+'"></td>'+
		'<td class="manage"><a href="javascript:;" class="delete" onclick="configlist.delrow(this)" title="{t('删除')}"><i class="icon icon-delete"></i></a></td>'+
		'</tr>';

		$('#configlist tbody').append(row);
	}

	// 删除节点
	configlist.delrow = function(ele) {
		$(ele).closest('tr').remove();
		$('#configform').submit();
	}

// 生成默认数据
$(function(){

	var dataset = {json_encode($config)};

	for (var key in dataset) {
		configlist.addrow(key, dataset[key]);
	}
});

//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		helper: function(e,tr){
			tr.children().each(function(){
				$(this).width($(this).width());
			});
			return tr;
		},
		stop:function(event,ui){
			$('#configform').submit();
		}
	});
});

$(function(){
	$('#configform').on('change','input',function(){
		$(this).submit();
	});

	$('#configform').validate({onfocusout:true,submitHandler:function(form){
		var action = $(form).attr('action');
		var data = $(form).serialize();
		$.post(action, data, function(msg){
			$.msg(msg);
		},'json');
	}});
});
</script>
{template 'footer.php'}