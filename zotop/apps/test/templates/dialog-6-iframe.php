{template 'dialog.header.php'}


<div class="scrollable" style="padding:0 5px;overflow:auto;">
	<div>
		<label><input autofocus id="input" type="text" value="hello world" style="padding: 5px;font-size:18px" /></label>

		<button id="height">设置高度</button> <br>
		<button id="open">传值到新的对话框</button> <br>
		<button id="remove">返回值并关闭当前对话框</button><br>

		<button id="msg">消息</button><br>

		<button id="loading">loading</button><br>


		<a><i class="icon icon-close close">×</i></a>
	</div>
</div>
<script>
		var mydialog = $.dialog();

		$('#input').val(mydialog.data).focus();

		mydialog.title('测试例子');
		//mydialog.width(550);
		//mydialog.height($(document).height());
		//mydialog.reset();     // 重置对话框位置


		mydialog.callbacks['ok'] = function(){

			this.shake();
			this.close($('#input').val()); // 关闭（隐藏）对话框
			this.remove();				 // 主动销毁对话框
			return false;
		}

		$('#remove').on('click', function () {
			mydialog.close($('#input').val()); // 关闭（隐藏）对话框
			mydialog.remove();				 // 主动销毁对话框
			return false;
		});

		$('.close').on('click', function () {
			mydialog.close().remove(); // 关闭（隐藏）对话框
			return false;
		});

		$('#height').on('click', function () {
			mydialog.height(400).reset();
			return false;
		});

		$('#open').on('click', function () {
			$.dialog({
				url: '{u('test/index/dialog-6-iframe')}'
			}).shake();
			return false;
		});

		$('#msg').on('click', function () {
			$.msg({
				state : true,
				content : 'ddddddddddddddd',
				url : parent.location.href,
				time: 3
			}).shake();
			return false;
		});

		$.loading();
</script>
{template 'dialog.footer.php'}