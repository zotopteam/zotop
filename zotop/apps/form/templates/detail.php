{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{U('form/index/list/'.$formid)}">{$form.name}</a></li>
    </ul>
</div>

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