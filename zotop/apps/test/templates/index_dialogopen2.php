{template 'dialog.header.php'}
<div id="body" style="width:550px;height:400px;" class="scrollable">
	<div id="body-header">
		<div class="title">{$title}</div>
	</div>
	<div id="body-main">
		<div>
			<div><a href="{u('test/index/dialogopen')}" class="dialog-open">dialog</a></div>
			<div><a href="{u('test/index/dialogload')}" class="dialog-load">ajax</a></div>
			<div><a href="http://www.qq.com/" class="dialog-load">ajax 163</a></div>
			<div><a href="{u('test/index/confirm')}" class="dialog-confirm">confirm</a></div>
			<div><a href="{u('test/index/confirm')}" class="dialog-prompt">prompt</a></div>
		</div>
		<div style="height:600px;">

		</div>
	</div><!-- body -->
	<div id="body-footer"></div>
</div>

<script>
	var $dialog = window.frameElement.dialog;

	$dialog.title('系统登录').button(
		{value:'确定',focus:true,callback:function(){
			parent.location.reload();
			return false;
		}},
		{value:'取消'}
	);

</script>
{template 'dialog.footer.php'}