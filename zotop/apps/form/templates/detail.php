{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{U('form/index/list/'.$formid)}">{$form.name}</a></li>
		<li>{t('详细信息')}</li>
    </ul>
</div>

<div class="blank"></div>

<table class="table list">
	{loop $show $key $val}
		<tr>
			<td class="vat" width="120">{$fields[$key]['label']} :</td>
			<td>{$val}</td>
		</tr>
	{/loop}
</table>

{template 'footer.php'}