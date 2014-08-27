{template 'dialog.header.php'}
{form::header()}

		{form::field(array('type'=>'hidden','name'=>'blockid','value'=>$block['id'],'required'=>'required'))}

		<table class="field">
			<tbody>
			{loop $block['fields'] $k $f}
			{if $f['show']}
			<tr>
				<td class="label">{form::label($f['label'],$f['name'],true)}</td>
				<td class="input">
					{if in_array($f['name'], array('title','url','image','description','createtime'))}
						{if $f['name'] == 'title'}
							{form::field(array_merge($f,array('value'=>$data[$f['name']],'style'=>$data['style'],'required'=>'required')))}
						{elseif $f['name'] == 'image'}
							{form::field(array_merge($f,array('value'=>$data[$f['name']],'dataid'=>'block-'.$block['id'],'required'=>'required')))}
						{else}
							{form::field(array_merge($f,array('value'=>$data[$f['name']],'required'=>'required')))}
						{/if}
					{else}
						{form::field(array_merge($f,array('name'=>'custom['.$f[name].']','value'=>$data['custom'][$f['name']])))}
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