{template 'header.php'}

{template 'block/admin_side.php'}

<div class="main side-main">

	<div class="main-header">		
		{if $keywords}
		<a class="goback" href="{u('block/admin/index/'.$categoryid)}"><i class="fa fa-angle-left"></i><span>{t('返回')}</span></a>
		<div class="title pull-center">{t('搜索 “%s”',$keywords)}</div>
		{else}
		<div class="title">{$category['name']} </div>
		{/if}

		<form action="{u('block/admin')}" class="searchbar input-group" method="post" role="search">				
			<input type="text" name="keywords" value="{$keywords}" placeholder="{t('请输入关键词')}" class="form-control" x-webkit-speech/>
			<span class="input-group-btn">
				<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
			</span>
		</form>		
		
		<div class="action">			
			<a class="btn btn-primary" href="{u('block/admin/add/'.$category['id'])}"><i class="fa fa-plus"></i><b>{t('新建区块')}</b></a>
		</div>
	</div><!-- main-header -->

	<div class="main-body scrollable">

		{if empty($data)}
		<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}
		
		{form::header()}
			<table class="table table-nowrap sortable" id="datalist" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
				<td class="drag">&nbsp;</td>
				<td class="text-center" width="20">{t('编号')}</td>
				<td>{t('名称')}</td>
				<td>{t('类型')}</td>
				<td>{t('调用代码')}</td>			
				<td width="160">{t('更新时间')}</td>
				</tr>
			</thead>
			<tbody>

			{loop $data $r}
				<tr>
					<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
					<td class="text-center">
					{$r.id}
					</td>
					<td>
						<div class="title text-overflow" {if $r.style}style="{$r.style}"{/if}>
						{if $r.name} {$r.name} {else} <i class="gray">{t('自动创建')}</i> {/if}
						</div>
						<div class="manage">
							<a href="{u('block/admin/data/'.$r['id'])}"><i class="fa fa-database"></i> {t('内容维护')}</a>
							<s>|</s>
							<a class="ajax-post hidden" href="{u('block/admin/publish/'.$r['id'])}">{t('发布')}</a>
							<s class="hidden">|</s>
							<a href="{u('block/admin/edit/'.$r['id'])}"><i class="fa fa-cog"></i> {t('设置')}</a>
							<s>|</s>
							<a class="dialog-confirm" href="{u('block/admin/delete/'.$r['id'])}"><i class="fa fa-times"></i> {t('删除')}</a>
						</div>
					</td>
					<td><div class="textflow">{m('block.block.types',$r.type)}</div></td>
					<td>{$r.tag}</td>
					<td>
						<div>{m('system.user.get', $r['userid'], 'username')}</div>
						<div class="f12 time">{format::date($r['updatetime'])}</div>
					</td>
				</tr>
			{/loop}			
			</tbody>
			</table>
		{form::footer()}
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('拖动列表项可以调整顺序')}</div>
	</div><!-- main-footer -->

</div><!-- main -->
<script type="text/javascript">
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