{template 'header.php'}
<div class="side">
	<div class="side-header">{t('单元测试')}</div>
	<div class="side-body scrollable">
		<ul class="sidenavlist">
			{loop $navlist $nav}
			<li><a href="{$nav[href]}"{if request::url(false)==$nav['href']} class="current"{/if}><b>{$nav[text]}</b></a></li>
			{/loop}
		<ul>		
	</div>
</div>
<div class="main side-main no-footer">
	<div class="main-header"><div class="title">{$title}</div></div>
	<div class="main-body scrollable">
		<table class="table">
			<tr>
				<td>image:</td>
				<td>
					{$file} 
					{if file::exists($file)}
					<span class="green">{t('存在')}</span>
					{else}
					<span class="red">{t('不存在')}</span>
					{/if}
					<div>
					<img src="{$url}"/>
					<img src="{$result}"/>
					</div>
				</td>
			</tr>
			<tr>
				<td>info</td>
				<td>
					{loop $info $key $val}
						{$key} = {$val}<br/>
					{/loop}
				</td>
			</tr>
		</table>

	</div><!-- main-body -->

</div><!-- main -->
{template 'footer.php'}