<table class="field">
	<tr>
		<td class="label">{form::label(t('日期格式'),'format',false)}</td>
		<td class="input">
			<?php echo form::field(array(
				'type'=>'radio',
				'name'=>'format',
				'options'=>array(''=>t('常用正则')) + zotop::filter('field.datatime.format', array(
					'yyyy-MM-dd'		=> t('日期，如：%s', format::date(ZOTOP_TIME,'Y-m-d')),
					'yyyy-MM-dd H:m'	=> t('日期+时间 ，如：%s', format::date(ZOTOP_TIME,'Y-m-d H:i')),
				)),
				'column'=>1
			))?>
		</td>
			</tr>
</table>
<script type="text/javascript">

</script>