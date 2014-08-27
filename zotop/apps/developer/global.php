<?php
//注册类库
//zotop::register(array());

//system_start
zotop::add('system.start','developer_system_start');
function developer_system_start($start)
{
	$start = arr::before($start,'system_info','developer',array(
		'text'	=>	A('developer.name'),
		'href'	=>	U('developer'),
		'icon'	=>	A('developer.url').'/app.png',
		'description'	=>	A('developer.description')
	));

	return $start;
}

?>