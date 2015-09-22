{template 'header.php'}

{template 'system/system_side.php'}


<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-open" href="{u('system/badword/add')}" data-width="600px" data-height="380px">
				<i class="fa fa-plus fa-fw"></i><b>{t('添加')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	
	<div class="main-body scrollable">

		{if empty($data)}
			<div class="nodata">
				<i class="fa fa-frown-o"></i>
				<h1>
					{t('暂时没有任何数据')}

					<a class="btn btn-primary js-open" href="{u('system/badword/add')}" data-width="600px" data-height="380px">
						<i class="fa fa-plus fa-fw"></i>{t('添加')}
					</a>
				</h1>
			</div>
		{else}
		<table class="table table-hover list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="select"><input type="checkbox" class="select-all" title="{t('全选')} / {t('取消')}"></td>
			<td>{t('敏感词')}</td>
			<td class="hidden-xs">{t('替换词')}</td>
			<td class="hidden-xs">{t('敏感级别')}</td>
			<td class="manage">{t('管理')}</td>
			</tr>
		</thead>
		<tbody>
			{loop $data $r}
			<tr>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}"></td>
				<td>{$r['word']}</td>
				<td class="hidden-xs">{if $r['replace']}{$r['replace']}{elseif $r['level']==0}<span class="gray">{str_repeat('*',str::len($r['word']))}</span>{/if}</td>
				<td class="hidden-xs">{if $r['level'] == 0}{t('一般')}{else}{t('危险')}{/if}</td>
				<td class="manage">
						<a class="js-open" href="{u('system/badword/edit/'.$r['id'])}" data-width="600px" data-height="380px"><i class="fa fa-edit"></i> {t('编辑')}</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/badword/delete/'.$r['id'])}"><i class="fa fa-times"></i> {t('删除')}</a>
				</td>
			</tr>
			{/loop}		
		</tbody>
		</table>
		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<input type="checkbox" class="js-select-all" title="{t('全选')} / {t('取消')}">
		<button type="button" class="btn btn-default js-operate" data-url="{u('system/badword/operate/delete')}">{t('删除')}</button>
		{pagination::instance($total,$pagesize,$page)}
	</div><!-- main-footer -->
	
</div><!-- main -->

<script type="text/javascript">
$(function(){

	var tablelist = $('table.list').data('tablelist');

	if ( tablelist ){

		//底部全选
		$('.js-select-all').on('click',function(e){
			tablelist.selectAll(this.checked);
		});

		//操作
		$(".js-operate").on("click", function(event){
			event.preventDefault();
			if( tablelist.checked() == 0 ){
				$.error('{t('请选择要操作的项')}');
			}else{
				var href = $(this).data('url');
				var text = $(this).text();
				var data = $('form').serializeArray();;
					data.push({name:'operation',value:text});
				$.loading();
				$.post(href,$.param(data),function(msg){
					$.msg(msg);
				},'json');
			}
		});
	}
});
</script>
{template 'footer.php'}