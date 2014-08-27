<?php
//注册类库
//zotop::register(array());

//system_navlist
zotop::add('system.start','database_system_start');
function database_system_start($start)
{
	$start['database']= array(
		'text'=>a('database.name'),
		'href'=>u('database'),
		'icon'=>a('database.url').'/app.png',
		'description'=>a('database.description')
	);

	return $start;
}

?>