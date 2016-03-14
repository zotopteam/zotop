<?php
return array(
	'site_config' => array(
		'text'        => t('站点设置'),
		'href'        => U('site/config/index'),
		'icon'        => A('site.url') . '/app.png',
		'description' => t('站点名称、搜索优化、联系方式等设置'),
		'allow'       => priv::allow('site'),
	)
);
?>