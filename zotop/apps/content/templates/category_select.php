{template 'dialog.header.php'}

{form}
	<table id="tree" class="table table-hover table-nowrap table-border list hidden">
		<tbody>
			<tr data-tt-id="0">
				<td class="name"><i class="fa fa-folder fa-fw text-primary"></i> {t('根栏目')}</td>
			</tr>
			{loop m('content.category.active') $c}
					<tr data-tt-id="{$c.id}" data-tt-parent-id="{$c.parentid}" {if $id == $c.id}class="selected"{/if}>
						<td class="name"><i class="fa {if $c.childid}fa-folder{else}fa-file{/if} fa-fw text-primary"></i> {$c.name}</td>
					</tr>
			{/loop}
		</tbody>
	</table>
{/form}

<link rel="stylesheet" type="text/css" href="{A('system.url')}/assets/css/jquery.treetable.css"/>
<script type="text/javascript" src="{A('system.url')}/assets/js/jquery.treetable.js"></script>
<script type="text/javascript">
	$(function(){
		$("#tree").treetable({
			column : 0,
			indent : 22,
			expandable : true,
			persist: true,
			initialState : 'collapsed', //"expanded" or "collapsed".
			clickableNodeNames : true,
			stringExpand:null,
			stringCollapse:null
		}).removeClass('hidden');

		$("#tree").treetable("reveal", "{$id}");

		$("#tree").find('tr').click(function(){
			$(this).addClass('selected').siblings("tr").removeClass('selected'); //单选
		});
	})

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

			var id = $('#tree').find('.selected').attr('data-tt-id');

			if ( id ){
				return $dialog.ok(id);
			}

			return false;
	};

	$dialog.title('{$title}');
</script>
{template 'dialog.footer.php'}