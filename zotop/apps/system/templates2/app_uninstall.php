{template 'dialog.header.php'}

<div class="main">
	<div class="main-header">
		<div class="logo"><img src="{ZOTOP_URL_APPS}/{$data['dir']}/app.png"/></div>
		<div class="title">{t('您确定要卸载应用 “%s” ?',$data['name'])}</div>
		<div class="description">{t('卸载后，您将无法使用继续使用该应用，该应用的数据会被全部删除，并且无法被恢复')}</div>
	</div>
	<div class="main-body scrollable">
		{form::header()}
			<ul class="list" >
				<li>{t('您可以使用 <b class="red">禁用</b> 功能暂时关闭该应用')}</li>
				<li>{t('警告：<b class="red">卸载程序可能会删除该应用全部数据</b>，强烈建议您在卸载之前备份该应用的全部数据')}</li>
				<li>{t('卸载后，您可以从 未安装应用 中找到该应用，并重新安装该应用')}</li>
				<li>{t('如果您想完全删除该应用，请删除应用目录下的 “%s” 文件夹',$data['dir'])}</li>
			</ul>
		{form::footer()}
	</div>
</div>
<style type="text/css">
.main-header{height:70px;max-height:150px;background: #e5f3fb;padding:40px 20px;line-height:30px;}
.main-body{top:150px;bottom:0;padding:40px;}

.main-header div.logo{float:left;width:100px;height:100%;text-align:center;}
.main-header div.logo img{width:64px;height:64px;}
.main-header div.title{font-size:28px;padding:0px;margin-top:-5px;}
</style>
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
				$.msg(msg);
				if( msg.state ){
					$dialog.close();
				}

			},'json');
		}});
	});
</script>
{template 'dialog.footer.php'}