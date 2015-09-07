{template 'head.php'}

<div class="main no-header">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<div class="container-fluid">
		<div class="row">
			{loop $start $s}
			<div class="col-sm-4 col-md-3 p0">
				<a href="{$s['href']}" class="shortcut">
					<div class="shortcut-icon"><img src="{$s['icon']}"></div>
					<div class="shortcut-text">
						<h2>{$s['text']}</h2>
						<p>{$s['description']}</p>		
					</div>
				</a>
				{if isset($s['msg'])}
				<b class="shortcut-badge badge badge-xs badge-danger">{$s['msg']}</b>
				{/if}			
			</div>
			{/loop}
		</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="copyright footer-text">
			<div class="copyright-thanks">{t('感谢您使用逐涛网站管理系统')}</div>
			<div class="copyright-powered">{zotop::powered()}</div>
		</div>
	</div>
</div>

{template 'foot.php'}