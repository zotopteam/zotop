{template 'dialog.header.php'}
<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div>
	<div class="main-body scrollable">
		<div>
			<div><a href="{u('test/index/dialogopen2')}" class="dialog-open">dialog2</a></div>
			<div><a href="{u('test/index/dialogload')}" class="dialog-load">ajax</a></div>
			<div><a href="http://www.qq.com/" class="dialog-load">ajax 163</a></div>
			<div><a href="{u('test/index/confirm')}" class="dialog-confirm">confirm</a></div>
			<div><a href="{u('test/index/confirm')}" class="dialog-prompt">prompt</a></div>
		</div>
		<div style="height:600px;">

		</div>
	</div><!-- body -->
	<div class="main-footer"></div>
</div>

<script>
	$dialog.title('系统登录').button(
		{value:'确定',focus:true,callback:function(){
			parent.location.reload();
			return false;
		}},
		{value:'取消'}
	);

</script>
{template 'dialog.footer.php'}