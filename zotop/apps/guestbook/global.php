<?php


	//global.start
	zotop::add('global.start','guestbook_global_start');

	function guestbook_global_start($start)
	{
		$start['guestbook'] = array(
			'text' => A('guestbook.name'),
			'href' => u('guestbook/admin'),
			'icon' => A('guestbook.url').'/app.png',
			'description' => A('guestbook.description'),
			'allow' => priv::allow('guestbook')
		);

		// 设置提示信息
		if ( $pending = m('guestbook.guestbook')->statuscount('pending') )
		{
			$start['guestbook']['msg']   = '<a href="'.u('guestbook/admin/index/pending').'">'.t('%s 条新留言',$pending).'</a>';
			$start['guestbook']['badge'] = $pending;
		}

		return $start;
	}

	//global.navbar
	zotop::add('global.navbar','guestbook_global_navbar');
	
	function guestbook_global_navbar($nav)
	{
		$nav['guestbook'] = array(
			'text'        => t('留言'),
			'href'        => u('guestbook/admin'),
			'icon'        => A('guestbook.url').'/app.png',
			'description' => A('guestbook.description'),
			'allow'       => priv::allow('guestbook'),
			'active'      => (ZOTOP_APP == 'guestbook')
		);

		return $nav;
	}

	//system_globalmsg
	zotop::add('global.msg','guestbook_global_msg');

	function guestbook_global_msg($msg)
	{
			// 设置提示信息
		if ( $pending = m('guestbook.guestbook')->statuscount('pending') )
		{
			$msg[] = array(
				'text' => t('您有 %s 条等待审核的新留言，请尽快处理……', $pending),
				'href' => u('guestbook/admin/index/pending'),
				'type' => 'pending',
			);
		}

		return $msg;
	}
?>