{template 'dialog.header.php'}
<div class="main scrollable">

    <table class="table table-nowrap list">
    	{loop $show $key $val}
    		<tr>
    			<td class="vat" width="100">{$fields[$key]['label']}</td>
    			<td>{$val}</td>
    		</tr>
    	{/loop}
    </table>

</div>
{template 'dialog.footer.php'}