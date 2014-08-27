{template 'header.php'}
<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>
	</div>
</div>
<div class="main side-main">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
		<div>
			<div><a href="{u('test/index/dialogopen')}" class="dialog-open" data-width="500" data-height="300">dialog</a></div>
			<div><a href="http://www.connect.renren.com/igadget/renren/index.html" class="dialog-open" data-width="320" data-height="400">dialog 跨域访问</a></div>
			<div><a href="{u('test/index/confirm')}" class="dialog-confirm" data-confirm="{t('您确认要删除嘛？')}">confirm</a></div>
			<div><a href="javascript:void(0)" class="dialog-success">success</a></div>
		</div>
		<div style="height:600px;">

		</div>
		<script style="text/javascript">
			$(function(){
				//$.loading();
				$.msg({
					state: 'loading',
					content: '操作执行中',
					time: 0
				})

				$('.dialog-success').click(function(){
					$.msg({
						state: 'success',
						content: '操作成功',
						time: 0
					})
				});
			});
		</script>
	</div>
	<div class="main-footer">{t('测试对话框')}</div>
</div>
{template 'footer.php'}