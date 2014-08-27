{template 'dialog.header.php'}
<div class="main scrollable">
	<div class="m20">
		<div class="d-msg confirm">
			<div class="d-msg-icon"></div>
			<div class="d-msg-content">
				<div class="f16 red">{t('自定义卸载页面')}</div>
			</div>
		</div>
		{form::header()}
		<div class="p20 pl0 pr0">
			<ul class="list suggestion" >
				<li>{t('应用卸载后，您将无法使用该应用的所有功能')}</li>
				<li>{t('应用卸载后，该应用的所有<span class="red">数据及设置都会被删除，而且无法被恢复</span>')}</li>
				<li>{t('如果您只想暂时关闭该应用，我们建议您使用应用<span class="red">禁用</span>功能')}</li>
				<li>{t('您可以从未安装应用中找到该应用，并再次安装该应用')}</li>
			</ul>
		</div>
		{form::footer()}
	</div>
</div>
<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	// 表单验证
	$(function(){
		$('form.form').validate({submitHandler:function(){
			var action = $(this).attr('action');
			var data = $('form').serialize();
			$.loading();
			$.post(action,data,function(msg){

				if( msg.state ){
					$dialog.close();
				}
				$.msg(msg);

			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}