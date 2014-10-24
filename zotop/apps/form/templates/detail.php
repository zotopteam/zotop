{template 'header.php'}

<div class="blank"></div>

<table class="table list">
	{loop $fields $f}
		{if $f.show}
		<tr>
			<td class="w100 vt">{$f.label} :</td>
			<td>{m('form.field.show',$data[$f.name],$f)}</td>
		</tr>
		{/if}
	{/loop}
</table>

{template 'footer.php'}