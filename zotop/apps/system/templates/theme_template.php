{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="position">
			<a href="{u('system/theme/index')}">{t('主题管理')}</a>
			<s class="arrow">></s>
			<a href="{u('system/theme/template?theme='.$theme)}">{t('模版管理')}</a>
			{loop $position $p}
			<s class="arrow">></s> <a href="{u('system/theme/template?theme='.$theme.'&dir='.$p[1])}">{$p[0]}</a>
			{/loop}
		</div>
		<div class="action">
			<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="{u('system/theme/template_newfolder?theme='.$theme.'&dir='.$dir)}">
			<i class="icon icon-folder"></i><b>{t('新建目录')}</b>
			</a>
			<a class="btn btn-icon-text dialog-open"  data-width="600px" data-height="200px" href="{u('system/theme/template_newfile?theme='.$theme.'&dir='.$dir)}">
				<i class="icon icon-file"></i><b>{t('新建模板')}</b>
			</a>
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		<table class="table zebra list" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td class="w40 center"></td>
				<td>{t('名称')}</td>
				<td class="w200"></td>
				<td class="w100 center">{t('大小')}</td>
				<td class="w140">{t('修改时间')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $folders $f}
			<tr>
				<td class="center"><b class="icon icon-folder f32"></b></td>
				<td>
					<div class="textflow">
						<a href="{u('system/theme/template?theme='.$theme.'&dir='.$f['path'])}"><b>{$f['name']}</b></a>
					</div>
					<div class="description">
						{$f['note']}
					</div>
				</td>
				<td>
					<div class="manage right hidden">
						<a class="dialog-open" href="{u('system/theme/template_renamefolder?theme='.$theme.'&dir='.$f['path'])}" data-width="600px" data-height="200px">
							<i class="icon icon-config"></i> {t('重命名')} & {t('注释')}
						</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/theme/template_deletefolder?theme='.$theme.'&dir='.$f['path'])}">
							<i class="icon icon-delete"></i> {t('删除')}
						</a>
					</div>
				</td>
				<td class="center"></td>
				<td>{format::date($f['time'])}</td>
			</tr>
		{/loop}
		{loop $files $f}
			<tr>
				<td class="center"><b class="icon icon-file f32"></b></td>
				<td>
					<div>{$f['name']}</div>
					<div class="description">
						{$f['note']}
					</div>

				</td>
				<td>
					<div class="manage right hidden">
						<a class="dialog-open" href="{u('system/theme/template_edit?theme='.$theme.'&file='.$f['path'])}" data-width="800px" data-height="500px">
							<i class="icon icon-edit"></i> {t('编辑')}
						</a>
						<s></s>
						<a class="dialog-open" href="{u('system/theme/template_renamefile?theme='.$theme.'&file='.$f['path'])}" data-width="600px" data-height="200px">
							<i class="icon icon-config"></i> {t('重命名')} & {t('注释')}
						</a>
						<s></s>
						<a class="dialog-confirm" href="{u('system/theme/template_deletefile?theme='.$theme.'&file='.$f['path'])}">
							<i class="icon icon-delete"></i> {t('删除')}
						</a>
					</div>
				</td>
				<td class="center">{format::size($f['size'])}</td>
				<td>{format::date($f['time'])}</td>
			</tr>
		{/loop}
		</tbody>
		</table>

	</div><!-- main-body -->
	<div class="main-footer">
	{t('网站主题和模版决定了网站的外观，你可以修改网站当前主题或者安装一个新的主题')}
	</div><!-- main-footer -->
</div><!-- main -->

{template 'footer.php'}