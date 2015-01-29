{template 'dialog.header.php'}

<div class="main no-footer">
	<div class="main-header">
		<div class="position">
			<a href="{u('system/theme/template/select')}"><i class="icon icon-home"></i> {t('模版目录')}</a>
			{loop $position $p}
			<s class="arrow">></s> <a href="{u('system/theme/template/select?dir='.$p[1])}"><i class="icon icon-folder"></i> {$p[0]}</a>
			{/loop}
		</div>
		<div class="action">
			<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="{u('system/theme/template_newfolder?dir='.$dir)}">
				<i class="icon icon-folder"></i><b>{t('新建目录')}</b>
			</a>
			<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="{u('system/theme/template_newfile?dir='.$dir)}">
				<i class="icon icon-file"></i><b>{t('新建模板')}</b>
			</a>
			<a class="btn btn-icon-text" href="javascript:location.reload();">
				<i class="icon icon-refresh"></i><b>{t('刷新')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="w30 center"></td>
				<td>{t('名称')}</td>
				<td class="w120"></td>
				<td class="w140">{t('修改时间')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $folders $f}
			<tr>
				<td class="center"><b class="icon icon-folder f32"></b></td>
				<td>
					<div class="textflow"><a href="{u('system/theme/template/select?dir='.$f['path'])}">{$f['name']}</a></div>
					<div class="description">{$f['note']}</div>
				</td>
				<td>
					<div class="hidden right manage">
						<a class="dialog-open" href="{u('system/theme/template_renamefolder?dir='.$f['path'])}" data-width="600px" data-height="200px" title="{t('重命名')} & {t('注释')}">
							<i class="icon icon-config"></i>
						</a>
						<s></s>
						<a class="delete" href="{u('system/theme/template_deletefolder?dir='.$f['path'])}" title="{t('删除')}">
							<i class="icon icon-delete"></i>
						</a>
					</div>
				</td>
				<td>{format::date($f['time'])}</td>
			</tr>
		{/loop}
		{loop $files $f}
			<tr class="template {if $f['file'] == $_GET['file']}selected{/if}" data-file="{$f['file']}">
				<td class="center"><b class="icon icon-file f32"></b></td>
				<td>
					<div class="textflow">{$f['name']}</div>
					<div class="description">{$f['note']}</div>
				</td>
				<td>
					<div class="hidden right manage">
					<a class="dialog-open" href="{u('system/theme/template_edit?file='.$f['path'])}" data-width="1000px" data-height="500px" title="{t('编辑')}">
						<i class="icon icon-edit"></i>
					</a>
					<s></s>
					<a class="dialog-open" href="{u('system/theme/template_copyfile?file='.$f['path'])}" data-width="600px" data-height="200px" title="{t('复制')}">
						<i class="icon icon-copy"></i>
					</a>
					<s></s>
					<a class="dialog-open" href="{u('system/theme/template_renamefile?file='.$f['path'])}" data-width="600px" data-height="200px" title="{t('重命名')} & {t('注释')}">
						<i class="icon icon-config"></i>
					</a>

					<s></s>
					<a class="delete" href="{u('system/theme/template_deletefile?file='.$f['path'])}" title="{t('删除')}">
						<i class="icon icon-delete"></i>
					</a>
					</div>
				</td>
				<td>{format::date($f['time'])}</td>
			</tr>
		{/loop}
		</tbody>
		</table>

	</div><!-- main-body -->
</div><!-- main -->

<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){

			var $selected = $('table.list').find('tr.selected');

			if ( $selected.length == 0 ){
				$.error('{t('请选择模版')}');
				return false;
			}

			$dialog.ok($selected.attr('data-file'));
			return true;

	};

	$dialog.title('{t('选择模版')}');

	// 选择模版
	$(function(){
		$(document).on('click','tr.template',function(e){
			e.preventDefault();

			//当点击为按钮时，禁止选择
			if( $(e.target).parent().attr('tagName') == 'A' ) return false;

			if ( $(this).hasClass('selected') ) {
				$(this).removeClass("selected");
			}else{
				$(this).addClass('selected').siblings("tr.template").removeClass('selected'); //单选
			}
		});
	});

	// 文件夹和模板删除
	$(function(){
		$(document).on('click','a.delete',function(e){
			e.preventDefault();

			var $item  = $(this).parents('tr');
			var action = $(this).attr('href');

			$.confirm("{t('您确定要删除该文件嘛?')}",function(){
				//删除操作
				$.get(action,function(msg){
					msg.state ? $item.removeClass('selected').hide().remove() : $.error(msg.content);
				},'json');
			},$.noop);

			return false;				
		});
	});

</script>

{template 'dialog.footer.php'}