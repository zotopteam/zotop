{template 'dialog.header.php'}

{form::header()}
	<table id="tree" class="table list hidden" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="select center">&nbsp;</td>
			<td class="w400">{t('权限名称')}</td>
			<td>{t('权限标识')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $dataset $data}
			<tr data-tt-id="{$data['id']}"{if $data['parentid']} data-tt-parent-id="{$data['parentid']}"{/if}">
				<td class="select center"><input type="checkbox" class="select" name="id[]" value="{$data['id']}" {$data['status']}/></td>
				<td class="name"><i class="icon {if $data['_child']}icon-folder{else}icon-item{/if}"></i>{$data['name']}</td>
				<td>{$data['app']}{if !empty($data['controller'])}/{$data['controller']}{/if}{if !empty($data['action'])}/{$data['action']}{/if}</td>
			</tr>
		{/loop}
		</tbody>
	</table>
{form::footer()}

<link rel="stylesheet" type="text/css" href="{A('system.url')}/common/css/jquery.treetable.css"/>
<script type="text/javascript" src="{A('system.url')}/common/js/jquery.treetable.js"></script>
<script type="text/javascript">
$(function(){
	$("#tree").treetable({
		column : 1,
		indent : 18,
		expandable : true,
		persist: true,
		initialState : 'collapsed', //"expanded" or "collapsed".
		clickableNodeNames : true,
		stringExpand: "{t('展开')}",
		stringCollapse: "{t('关闭')}"
	}).removeClass('hidden');
})

$(function(){

	// 更改全部下级节点目录
	function checkchild(id, checked, rows){
		rows.each(function(){
			if ( $(this).attr('data-tt-parent-id') == id ){

				if (checked){
					$(this).find('input:checkbox').attr('disabled','disabled');
				}else{
					$(this).find('input:checkbox').removeAttr('disabled');
				}

				checkchild($(this).attr('data-tt-id'), checked, rows);
			}

		});
	}

	$("#tree").tablelist({
		oncheck : function(obj,checkboxes,rows){
			var id = $(obj).parents('tr').attr('data-tt-id');
			var checked = obj.checked;

			// 选择子节点
			checkchild(id, checked, rows)
		}
	});
});

$(function(){
	$('form.form').submit(function(){
		if( $('table.list').data('tablelist').checked() == 0 ){
			$.error('{t('请至少选择一项')}');
		}else{
			var href = $('form').attr('action');
			var data = $('form').serialize();;
			$.loading();
			$.post(href,data,function(msg){
				$.msg(msg);
				$dialog.close();
			},'json');
		}

		return false;
	});
})

// 对话框设置
$(function(){
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$dialog.title("{t('%s 的权限设置', $role['name'])}");
});


</script>
{template 'dialog.footer.php'}