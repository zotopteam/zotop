{template 'header.php'}

<div class="main no-header">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<div class="container-fluid">
			<div class="row">
				{loop system_hook::start() $s}
				<div class="col-sm-4 col-md-3">
					<a href="{$s['href']}" class="shortcut shortcut-list">
						<div class="shortcut-icon">
							<img src="{$s['icon']}">
							{if isset($s['badge'])}
							<b class="shortcut-badge badge badge-xs badge-danger">{$s['badge']}</b>
							{/if}		
						</div>
						<div class="shortcut-text">
							<h2>{$s['text']}</h2>
							<p>{$s['description']}</p>		
						</div>
					</a>	
				</div>
				{/loop}
			</div>
			<div class="row">
			</div>
		</div>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="footer-text">
			{t('感谢您使用逐涛网站管理系统')}
		</div>
		<div class="footer-text pull-right">{zotop::powered()}</div>
	</div>
</div>

{template 'footer.php'}