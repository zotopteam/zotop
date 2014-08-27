{template 'header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('developer')}">{t('开发助手')}</a>
			<s class="arrow">></s>
			{$data['name']}
		</div>
		<div class="action">
			<a class="btn btn-icon-text ajax-post hidden" href="{U('developer/project/delete')}">
				<i class="icon icon-delete"></i><b>{t('删除应用')}</b>
			</a>
		</div>
	</div>
	<div class="main-body scrollable">
		<div class="blank"></div>
		<div class="icon">
			<img src="{ZOTOP_URL_APPS}/{$data['dir']}/app.png">
		</div>
		<div class="info">
			<dl class="list">
			<dt>
				{t('基本信息')}
				<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/edit')}" data-width="800" data-height="400">
					<i class="icon icon-edit"></i>
					<b>{t('编辑信息')}</b>
				</a>
			</dt>
			<dd>
			<table class="table list">
			<tr>
			{loop $data $key $val}
				<td class="w200">{$attrs[$key]}({$key})</td>
				<td>{$val}</td>
				{if $n%2==0}</tr><tr>{/if}
			{/loop}
			</tr>
			</table>
			</dd>

			<dt>{t('应用设置')}</dt>
			<dd>
				<form id="configform" method="post" action="{U('developer/project/config')}">
				<table class="table list border zebra sortable" id="datalist">
					<thead>
						<tr>
							<td	class="drag">&nbsp;</td>
							<td class="w300">{t('键名')}</td>
							<td>{t('键值')}</td>
							<td class="manage">{t('操作')}</td>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<td colspan="4"><a href="javascript:;" onclick="datalist.addrow()"><i class="icon icon-add"></i><b>{t('添加一行')}</b></a></td>
						<tr>
					</tfoot>
				</table>
				</form>

			</dd>

			<dt>
				{t('关联数据表')}

				<a class="btn btn-icon-text btn-highlight dialog-open" href="{U('developer/project/createtable')}" data-width="600" data-height="300">
					<i class="icon icon-add"></i>
					<b>{t('新建数据表')}</b>
				</a>
			</dt>
			<dd>
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
			</dd>
			</dl>
		</div>
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
	var datalist = {};

	// 添加节点
	datalist.addrow = function(key, val){
		var key = key || '';
		var val = val || '';
		var row = '<tr>'+
		'<td class="drag" title="{t('拖动排序')}" data-placement="left">&nbsp;</td>'+
		'<td><input type="text" class="text required" style="width:90%" name="config_key[]" value="'+ key +'" placeholder="{t('只允许英文，数字和下划线')}" data-placement="top" pattern="^[a-z0-9_]+$"></td>'+
		'<td><input type="text" class="text required" style="width:90%" name="config_val[]" value="'+ val +'"></td>'+
		'<td class="manage"><a href="javascript:;" class="delete" onclick="datalist.delrow(this)" title="{t('删除')}"><i class="icon icon-delete"></i></a></td>'+
		'</tr>';

		$('#datalist tbody').append(row);
	}

	// 删除节点
	datalist.delrow = function(ele) {
		$(ele).closest('tr').remove();
		$('#configform').submit();
	}

// 生成默认数据
$(function(){

	var dataset = {json_encode($config)};
	if ( dataset.length>0 ){
		for (var key in dataset) {
			datalist.addrow(key, dataset[key]);
		}
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
		$('#configform').submit();
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