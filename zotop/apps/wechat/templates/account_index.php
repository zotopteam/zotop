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
						<div><i class="icon icon-false red"></i> <b class="red">{t('尚未接入，请在微信公众平台-开发者中心 启用并设置服务器配置')}</b></div>
						<br/>
						<div>{t('URL(服务器地址)')}：<span class="green">{U('wechat/api/'.$r.id)}</span></div>
						<div>{t('Token(令牌)')}：<span class="green">{$r.token}</span></div>
						<div>{t('EncodingAESKey(消息加解密密钥)')}：<span class="green">{$r.encodingaeskey}</span></div>
					{/if}
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

{template 'footer.php'}