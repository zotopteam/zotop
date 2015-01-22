<?php
defined('ZOTOP') OR die('No direct access allowed.');
defined('ZOTOP_UPGRADE') OR die('No direct access allowed.');

$listorder = 1421845178;

for ($i=0; $i < 1000; $i++)
{ 
	$this->db->insert('content',array (
	  'parentid' => '0',
	  'categoryid' => '1',
	  'modelid' => 'url',
	  'title' => '测试标题'.$i,
	  'url' => 'http://www.163.com/',
	  'createtime' => '1421161200',
	  'updatetime' => '1421845252',
	  'weight' => '0',
	  'listorder' => $listorder,
	  'stick' => '0',
	  'userid' => '1',
	  'status' => 'publish',
	));

	$listorder = $listorder + 1;
}
?>