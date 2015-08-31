{template 'header.php'}

<div class="side">
	{template 'system/system_side.php'}
</div>

{form::header()}
<div class="main side-main">
	<div class="main-header">
		<div class="title">{$title}</div>
	</div><!-- main-header -->
	<div class="main-body scrollable">
		<table class="table list">
		<thead>
			<tr>
				<td class="select"><input type="checkbox" class="checkbox select-all"></td>
				<td class="w240">{t('名称')}</td>
				<td>{t('说明')}</td>
			</tr>
		</thead>
		<tbody>
		{loop $caches $r}
			<tr {if $r['checked']}class="selected"{/if}>
				<td class="select"><input type="checkbox" class="checkbox" name="id[]" value="{$r['id']}" {if $r['checked']}checked="checked"{/if}/></td>
				<td>{$r['name']}</td>
				<td>{$r['description']}</td>
			</tr>
		{/loop}
		</tbody>
		</table>		
	</div><!-- main-body -->
	<div class="main-footer">		
		<input type="checkbox" class="checkbox select-all middle">
		
		{form::field(array('type'=>'submit','value'=>t('清理')))}
		
	</div><!-- main-footer -->
</div><!-- main -->
{form::footer()}
<script type="text/javascript">
	$(function(){
		var tablelist = $('table.list').data('tablelist');

		//底部全选
		$('input.select-all').click(function(e){
			tablelist.selectAll(this.checked);
		});	
		
		// 操作
		var $form = $('form.form');

		$form.submit(function(event){
			event.preventDefault();

			$form.find('.submit').disable(true);
			$.loading();
			$.post($form.attr('action'), $form.serialize(), function(msg){
				$.msg(msg);
				$form.find('.submit').disable(false);
			},'json');				
		});
	});	
</script>
{template 'footer.php'}