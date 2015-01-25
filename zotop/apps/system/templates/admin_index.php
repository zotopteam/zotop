{template 'header.php'}

<div class="main no-header">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<ul class="navlist">
			{loop $start $s}
			<li>
				<a href="{$s['href']}" class="nav clearfix">
					<img src="{$s['icon']}">
					<h2>{$s['text']}</h2>
					<p>{$s['description']}</p>
				</a>

				{if isset($s['msg'])}
				<b class="msg">{$s['msg']}</b>
				{/if}
			</li>
			{/loop}
		</ul>
	</div><!-- main-body -->
	<div class="main-footer">
		<div class="fr">{zotop::powered()}</div>
		{t('感谢您使用逐涛网站管理系统')}
	</div>
</div>

<style type="text/css">
div.main{left:0;right:0;}
div.main-footer{padding:5px 8px;}
</style>

{template 'footer.php'}