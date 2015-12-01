{template 'header.php'}
{template 'content/admin_side.php'}

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a href="{u('content/model/add')}" class="btn btn-primary btn-icon-text js-open" data-width="750px" data-height="400px">
				<i class="fa fa-plus"></i><b>{t('新建模型')}</b>
			</a>

			<a href="javascript:;" class="btn btn-default btn-icon-text" id="importmodel">
				<i class="fa fa-upload"></i><b>{t('导入模型')}</b>
			</a>			
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::header()}
		<table class="table table-nowrap list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="text-center" width="40">{t('状态')}</td>
			<td class="w300">{t('名称')}</td>
			<td class="w140">{t('标识')}</td>					
			<td>{t('描述')}</td>
			<td class="w140">{t('类型')}</td>	
			<td class="w80">{t('数据')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td class="center">
					{if $r['disabled']}<i class="fa fa-times-circle fa-2x text-muted"></i>{else}<i class="fa fa-check-circle fa-2x text-success"></i>{/if}
				</td>
				<td>
					<div class="title">{$r['name']} </div>
					<div class="manage">
						<a class="js-confirm" href="{u('content/model/status/'.$r['id'])}">{if $r['disabled']}{t('启用')}{else}{t('禁用')}{/if}</a>
						<s>|</s>
						<a href="{u('content/model/edit/'.$r['id'])}" class="js-open" data-width="750px" data-height="400px">{t('设置')}</a>						
						<s>|</s>
						<a href="{u('content/field/index/'.$r['id'])}">{t('字段管理')}</a>
						<s>|</s>
						<a href="{u('content/model/export/'.$r['id'])}">{t('导出')}</a>												
						<s>|</s>
						<a href="{u('content/model/delete/'.$r['id'])}" class="js-confirm">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['id']}</td>
				<td>{$r['description']}</td>
				<td>					
					{if $r.app='content'}
						{if $r.model=='extend'} {t('扩展模型')} {else} {t('基础模型')} {/if}
					{/if}
				</td>				
				
				<td>{$r['datacount']} {t('条')}</td>
			</tr>
		{/loop}
		</tbody>
		</table>
		{form::footer()}

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
<script type="text/javascript" src="{A('system.url')}/assets/plupload/plupload.full.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/i18n/zh_cn.js"></script>
<script type="text/javascript" src="{A('system.url')}/assets/plupload/jquery.upload.js"></script>
<script type="text/javascript">
	$(function(){
		$("#importmodel").upload({
			url : "{u('content/model/upload')}",
			multi:false,
			fileext: 'model',
			filedescription : '{t('模型文件(.model)')}',
			uploaded : function(up,file,msg){
				$.msg(msg);
			}
		});
	});
</script>
{template 'footer.php'}