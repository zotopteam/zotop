{template 'header.php'}

<div class="main">
	<div class="main-header">
		<div class="title">{$title}</div>
		<div class="action">
			<a href="{u('wechat/account/add')}" class="btn btn-icon-text btn-highlight">
				<i class="icon icon-add"></i><b>{t('添加公众号')}</b>
			</a>			
		</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">

		{form::header()}
		<table class="table list sortable" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
			<td class="drag">&nbsp;</td>
			<td class="w200">{t('公众号名称')}</td>
			<td class="w180">{t('微信号')}</td>
			<td class="w120">{t('类型')}</td>
			<td>{t('服务器配置')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $data $r}
			<tr>
				<td class="drag">&nbsp;<input type="hidden" name="id[]" value="{$r['id']}"></td>
				<td>
					<div class="title">{$r['name']}</div>
					<div class="manage">
						<a href="{u('wechat/account/edit/'.$r['id'])}">{t('编辑')}</a>															
						<s></s>
						<a href="{u('wechat/account/delete/'.$r['id'])}" class="dialog-confirm">{t('删除')}</a>
					</div>
				</td>
				<td>{$r['account']}</td>
				<td>{m('wechat.account.type',$r['type'])}</td>
				<td>
					{if $r.connect}
						<i class="icon icon-true true"></i> {t('成功接入')} 
					{else}
						<i class="icon icon-false red"></i> <b class="red">{t('尚未接入')}</b> 
					{/if}

					<a href="javascript:;" class="dialog-from" data-target="#guide-{$r.id}" data-title="{t('公众号 [ {1} ] 服务器接入指南 ',$r['name'])}"><i class="icon icon-help"></i> {t('接入指南')}</a>
					
					<div id="guide-{$r.id}" class="clearfix none" style="width:1000px;height:450px;overflow:auto;">
						<div class="content-header">
							<h3>{t('登录微信公众平台官网后，在公众平台后台管理页面 - 开发者中心页，点击“修改配置”按钮，填写服务器地址（URL）、Token和EncodingAESKey')}</h3>
						</div>

						<div class="content-body">
							<div class="blank"></div>
							<table class="table border">
								<tr>
									<td>{t('URL(服务器地址)')}</td>
									<td class="green">{U('wechat/api/'.$r.id)}</td>
								</tr>
								<tr>
									<td>{t('Token(令牌)')}</td>
									<td class="green">{$r.token}</td>
								</tr>
								<tr>
									<td>{t('EncodingAESKey(消息加解密密钥)')}</td>
									<td class="green">{$r.encodingaeskey}</td>
								</tr>														
							</table>
							<div class="blank"></div>
							<div class="image">						
								<img src="http://mp.weixin.qq.com/wiki/static/assets/ce21f9e7d08b0f553032261b23c43b77.png" alt="{t('服务器配置')}"/>	
							</div>
						</div>
					</div> <!-- guide-x -->

				</td>				
			</tr>
		{/loop}
		</tbody>
		</table>
		{form::footer()}

	</div><!-- main-body -->
	<div class="main-footer">
		<div class="tips">{t('拖动列表项可以调整顺序')}</div>
	</div><!-- main-footer -->
</div><!-- main -->

<script type="text/javascript">
//sortable
$(function(){
	$("table.sortable").sortable({
		items: "tbody > tr",
		handle: "td.drag",
		axis: "y",
		placeholder:"ui-sortable-placeholder",
		helper: function(e,tr){
			tr.children().each(function(){
				$(this).width($(this).width());
			});
			return tr;
		},
		update:function(){
			var action = $(this).parents('form').attr('action');
			var data = $(this).parents('form').serialize();
			$.post(action, data, function(msg){
				$.msg(msg);
			},'json');
		}
	});
});
</script>

<script>
	
	$(function(){

		$(document).on('click', 'a.dialog-from',function(event){
			event.preventDefault();

			var title  = $(this).data('title') || $(this).data('original-title') || $(this).text();
			var target = $(this).data('target');

			var $dialog = $.dialog({
				title:title,
				content: $(target)[0],
				ok:$.noop,
				cancel:$.noop,
				padding:0
			},true);

			event.stopPropagation();
		});

	})

</script>

{template 'footer.php'}