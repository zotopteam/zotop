{template 'header.php'}

{template 'system/system_side.php'}

<div class="main side-main">
	<div class="main-header">
		<a class="goback" href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i> <span>{t('上一级')}</span></a>
		<div class="title hidden">{$title}</div>
		<ul class="breadcrumb hidden-xs">
			<li><a href="{u('system/theme/index')}"><i class="fa fa-magic"></i> {t('主题管理')}</a></li>
			<li><a href="{u('system/theme/template?theme='.$theme)}"><i class="fa fa-table"></i> {t('模版管理')}</a></li>
			{loop $position $p}
			<li><a href="{u('system/theme/template?theme='.$theme.'&dir='.$p[1])}"><i class="fa fa-folder"></i> {$p[0]}</a></li>
			{/loop}
		</ul>
		<div class="action">
			<div class="btn-group" role="group">
				<a class="btn btn-default js-open"  data-width="600" data-height="300" href="{u('system/theme/template_newfolder?theme='.$theme.'&dir='.$dir)}">
					<i class="fa fa-folder fa-fw"></i> <b>{t('新建目录')}</b>
				</a>
				<a class="btn btn-default js-open"  data-width="600" data-height="300" href="{u('system/theme/template_newfile?theme='.$theme.'&dir='.$dir)}">
					<i class="fa fa-file fa-fw"></i> <b>{t('新建模板')}</b>
				</a>
			</div>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table table-hover table-nowrap" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="text-center" width="1%"></td>
				<td>{t('名称')}</td>
				<td class="w200"></td>
				<td class="hidden-xs">{t('大小')}</td>
				<td class="hidden-xs">{t('修改时间')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $folders $f}
			<tr>
				<td class="text-center"><b class="fa fa-folder fa-2x text-primary"></b></td>
				<td >
					<div class="title text-overflow">
						<a href="{u('system/theme/template?theme='.$theme.'&dir='.$f['path'])}"><b>{$f['name']}</b></a>
					</div>
					<p class="description">
						{$f['note']}
					</p>
				</td>
				<td class="manage text-right">
					<div class="hover-show">					
						<a class="js-open" href="{u('system/theme/template_renamefolder?theme='.$theme.'&dir='.$f['path'])}" data-width="600" data-height="300">
							<i class="fa fa-pencil-square fa-fw"></i> {t('重命名')} & {t('注释')}
						</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/theme/template_deletefolder?theme='.$theme.'&dir='.$f['path'])}">
							<i class="fa fa-trash fa-fw"></i> {t('删除')}
						</a>
					</div>
				</td>
				<td class="hidden-xs"></td>
				<td class="hidden-xs">{format::date($f['time'])}</td>
			</tr>
		{/loop}
		{loop $files $f}
			<tr>
				<td class="text-center"><b class="fa fa-file fa-2x text-primary"></b></td>
				<td >
					<div class="title text-overflow">{$f['name']}</div>
					<p class="description">
						{$f['note']}
					</p>

				</td>
				<td class="manage text-right">
					<div class="hover-show">
						<a class="js-open" href="{u('system/theme/template_edit?theme='.$theme.'&file='.$f['path'])}" data-width="1000px" data-height="500px">
							<i class="fa fa-edit fa-fw"></i> {t('编辑')}
						</a>
						<s>|</s>
						<a class="js-open" href="{u('system/theme/template_renamefile?theme='.$theme.'&file='.$f['path'])}" data-width="600" data-height="300">
							<i class="fa fa-pencil-square fa-fw"></i> {t('重命名')} & {t('注释')}
						</a>
						<s>|</s>
						<a class="js-confirm" href="{u('system/theme/template_deletefile?theme='.$theme.'&file='.$f['path'])}">
							<i class="fa fa-trash fa-fw"></i> {t('删除')}
						</a>
					</div>
				</td>
				<td class="hidden-xs">{format::size($f['size'])}</td>
				<td class="hidden-xs">{format::date($f['time'])}</td>
			</tr>
		{/loop}
		</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">{t('网站主题和模版决定了网站的外观，你可以修改网站当前主题或者安装一个新的主题')}</div>
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}