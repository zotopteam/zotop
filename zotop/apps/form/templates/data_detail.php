{template 'dialog.header.php'}

<table class="table table-nowrap list">
	{loop $show $key $val}
		<tr>
			<td class="vat" width="100">{$fields[$key]['label']}</td>
			<td>{$val}</td>
		</tr>
	{/loop}
</table>

{template 'dialog.footer.php'}