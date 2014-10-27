{template 'header.php'}

<div class="position">
    <ul>
        <li><a href="{u()}">{t('首页')}</a></li>
		<li><a href="{U('form/index/list/'.$formid)}">{$form.name}</a></li>
    </ul>
</div>

<table class="table">
	<thead>
		<tr>
			{loop m('form.field.cache',$formid) $f}
				{if $f.list and $f.show}<td>{$f.label}</td>{/if}
			{/loop}
			{if $form.settings.detail}<td></td>{/if}
		</tr>
	</thead>
	<tbody>
	{formdata formid="$formid" size="10" page="true"}
		<tr>
		{loop m('form.field.cache',$formid) $f}
			{if $f.list and $f.show}<td>{$r[$f.name]}</td>{/if}
		{/loop}
		{if $form.settings.detail}<td><a href="{U('form/index/detail/'.$formid.'/'.$r.id)}">{t('详细内容')}</a></td>{/if}
		</tr>	
	{/formdata}
	</tbody>
</table>

<div class="pagination">{$pagination}</div>

{template 'footer.php'}