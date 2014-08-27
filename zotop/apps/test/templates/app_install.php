{template 'dialog.header.php'}
<div class="side side-main" style="width:70px;">
	<img src="{ZOTOP_URL_APPS}/{$dir}/app.png" style="position:absolute;top:25px;right:10px;width:48px;height:48px;"">
</div>
<div class="main side-main no-footer" style="left:70px;">
		<div class="main-header">
			<div class="title">{t('自定义安装页面')}<div>
		</div>
		<div class="main-body scrollable">
		{form::header()}
		<div style="position:relative;;">

			<table class="table list">
				<tr>
					<td class="w40">{t('名称')} :</td>
					<td>{$data['name']}</td>
				</tr>
				<tr>
					<td class="w40">{t('版本')} :</td>
					<td>{$data['version']}</td>
				</tr>
				<tr>
					<td class="w40">{t('作者')} :</td>
					<td>
					{$data['author']}
					{if $data['homepage']}<a target="_blank" href="{$m['homepage']}">{$data['homepage']}</a> {/if}
					</td>
				</tr>
				<tr>
					<td class="w40">{t('说明')} :</td>
					<td>{$data['description']}</td>
				</tr>
			</table>
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