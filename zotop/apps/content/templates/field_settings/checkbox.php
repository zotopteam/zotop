<table class="field">
	<tr>
		<td class="label">{form::label(t('选项列表'),'settings[options]',true)}</td>
		<td class="input">
			<?php
				if ( empty( $data['settings']['options'] ) )
				{
					$data['settings']['options'] = "选项名称A|选项值A
选项名称b|选项值b";
				}
			?>
			{form::field(array('type'=>'textarea','name'=>'settings[options]','value'=>$data['settings']['options'],'required'=>'required','style'=>'height:150px;line-height:25px;'))}
			{form::tips('每行一个，选项名称与选项值之间用竖线“|”隔开，如：<b>选项名称1|选项值1</b>')}
		</td>
	</tr>
</table>