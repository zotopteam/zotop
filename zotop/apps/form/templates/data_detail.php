{template 'dialog.header.php'}

<table class="table zebra list">
	{loop $show $key $val}
		<tr>
			<td class="w200 vt">{$fields[$key]['label']}</td>
			<td>{$val}</td>
		</tr>
	{/loop}
</table>

{template 'dialog.footer.php'}