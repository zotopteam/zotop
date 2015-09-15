{template 'header.php'}

<div class="side side-main">
	<div class="side-header">
		{t('附件')}
	</div><!-- side-header -->
	<div class="side-body scrollable">
		<ul class="nav nav-pills nav-stacked nav-side">
			<li>
				<a href="{u('system/attachment/index/list')}">
					<i class="fa fa-folder"></i> <span>{t('全部附件')}</span>
				</a>
			</li>
			{loop m('system.attachment_folder.category') $f}
			<li {if $folderid==$f['id']} class="active"{/if}>
				<a href="{u('system/attachment/index/list/'.$f['id'])}">
					<i class="fa fa-folder"></i> <span>{$f['name']}</span>
				</a>
			</li>
			{/loop}
			<li class="blank"></li>
			<li class="active">
				<a href="{u('system/attachment/folder')}">
					<i class="fa fa-sitemap"></i>  <span>{t('分类管理')}</span>
				</a>
			</li>
		</ul>
	</div><!-- side-body -->
</div>

<div class="main  side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a class="btn btn-primary js-prompt" data-value="" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/folderadd')}">
				<i class="fa fa-plus"></i> <b>{t('添加分类')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		{if empty($folders)}
			<div class="nodata">{t('暂时没有任何数据')}</div>
		{else}

		{form::header()}
		<table class="table table-hover sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag"></td>
			<td>{t('分类名称')}</td>
			<td class="w180">{t('管理')}</td>
			</tr>
		</thead>
		<tbody>

			{loop $folders $data}
			<tr>
				<td class="drag"><input type="hidden" name="id[]" value="{$data['id']}"></td>
				<td>{$data['name']}</td>
				<td class="w120">
					<div class="manage">
						<a class="js-prompt" data-value="{$data['name']}" data-prompt="{t('请输入分类名称')}" href="{u('system/attachment/folderedit/'.$data['id'])}">
							<i class="fa fa-edit fa-fw"></i> {t('编辑')}
						</a>
						<s></s>
						<a class="js-confirm" href="{u('system/attachment/folderdelete/'.$data['id'])}">
							<i class="fa fa-trash fa-fw"></i> {t('删除')}
						</a>
					</div>
				</td>
			</tr>
			{/loop}
		
		</tbody>
		</table>
		{form::footer()}

		{/if}
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('拖动分类可以直接调整顺序')}</div>
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
				msg.onclose = function(){
					location.reload();
				}				
				$.msg(msg);
			},'json');
		}
	});
});
</script>
{template 'footer.php'}