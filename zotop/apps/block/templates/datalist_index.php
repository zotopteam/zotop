{template 'header.php'}
<div class="side">
{template 'block/side.php'}
</div>
{form::header(u('block/datalist/order/'.$block['id']))}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$block['name']}</div>
		<div class="action">
			<a class="btn btn-highlight btn-icon-text dialog-open" href="{u('block/datalist/add/'.$block['id'])}" data-width="800px" data-height="300px">
				<i class="icon icon-add"></i><b>{t('添加')}</b>
			</a>
			<a class="btn btn-icon-text" href="{u('block/block/edit/'.$block['id'])}"><i class="icon icon-setting"></i><b>{t('设置')}</b></a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<div class="editor-area">
			<table class="table zebra list sortable" id="datalist" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
					<td class="drag">&nbsp;</td>
					<td>{t('标题')}</td>
					<td class="w140">{t('更新者/更新时间')}</td>
					</tr>
				</thead>
				<tbody>
				{if empty($data)}
					<tr class="nodata"><td colspan="3"><div class="nodata">{t('暂时没有任何数据')}</div></td></tr>
				{else}
				{loop $data $k $r}
					<tr>
						<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
						<td>
							<div id="title-{$k}" class="title textflow" title="{$r['title']}"{if $r['style']}style="{$r['style']}"{/if}>
							{$r['title']}
							</div>
							<div id="manage-{$k}" class="manage">
								<a class="dialog-open" href="{u('block/datalist/edit/'.$r['id'])}" data-width="800px" data-height="300px">
									{t('编辑')}
								</a>
								<s></s>
								<a class="dialog-confirm" href="{u('block/datalist/delete/'.$r['id'])}">
									{t('删除')}
								</a>
							</div>
						</td>
						<td>
							<div>{m('system.user.get', $r['userid'], 'username')}</div>
							<div class="f12 time">{format::date($r['updatetime'])}</div>
						</td>
					</tr>
				{/loop}
				{/if}


				</tbody>
			</table>
		</div>

		{if $block['description']}
		<div class="description">
			<div class="tips"><i class="icon icon-info alert"></i> {$block['description']}</div>
		</div>
		{/if}


	</div><!-- main-body -->
	<div class="main-footer">


		<a class="btn btn-icon-text fr" href="{u('block/block/list/'.$categoryid)}"><i class="icon icon-back"></i><b>{t('返回')}</b></a>

		<div class="tips">{t('拖动列表项可以调整顺序')}</div>

	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}


<style type="text/css">
div.description{margin:10px;line-height:22px;font-size:14px;}
</style>

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">
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
			update:function(){
				var action = $(this).parents('form').attr('action');
				var data = $(this).parents('form').serialize();
				$.post(action, data, function(msg){
					$.msg(msg);
				},'json');
			}
		});
	});

</script>
{template 'footer.php'}