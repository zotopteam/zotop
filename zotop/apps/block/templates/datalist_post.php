{template 'dialog.header.php'}
{form::header()}

		{form::field(array('type'=>'hidden','name'=>'blockid','value'=>$block['id'],'required'=>'required'))}

		<table class="field">
			<tbody>
			{loop m('block.block.fieldlist',$block.fields) $k $f}
			{if $f.show}
			<tr>
				<td class="label">{form::label($f.label, $k, $f.required=='required')}</td>
				<td class="input">
					{if $f.type == 'title'}
						{form::field(array_merge($f,array('value'=>$data[$k],'style'=>$data['style'])))}
					{elseif in_array($f.type,array('image','file','images','files','editor'))}
						{form::field(array_merge($f,array('value'=>$data[$k],'dataid'=>'block-'.$block['id'])))}
					{else}
						{form::field(array_merge($f,array('value'=>$data[$k])))}
					{/if}
				</td>
			</tr>
			{/if}
			{/loop}
			</tbody>
		</table>

{form::footer()}

<script type="text/javascript" src="{zotop::app('system.url')}/common/js/jquery.validate.min.js"></script>
<script type="text/javascript">

	// 对话框设置
	$dialog.callbacks['ok'] = function(){
		$('form.form').submit();
		return false;
	};

	$(function(){
		$('form.form').validate({submitHandler:function(form){
			var action = $(form).attr('action');
			var data = $(form).serialize();
			$.loading();
			$.post(action, data, function(msg){

				if( msg.state ){
					parent.location.reload();
					$dialog.close();
				}

				$.msg(msg);

			},'json');
		}});
	});

</script>
{template 'dialog.footer.php'}