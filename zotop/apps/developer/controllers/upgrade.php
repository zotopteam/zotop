<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * zotop
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class developer_controller_upgrade extends admin_controller
{
	/**
     * 列表替换
     *
     * @return void
     */
	public function action_list()
	{
		$file = a('developer.path').DS.'templates'.DS.'schema_index.php';
		
		$str  = file::get($file);

		$str = str_ireplace('icon icon', 'fa fa', $str);
		$str = str_ireplace('false false', 'times-circle fa-2x text-muted', $str);
		$str = str_ireplace('true true', 'check-circle fa-2x text-success', $str);
		$str = str_ireplace('fa-add', 'fa-plus', $str);


		$str = str_ireplace('<table class="table list sortable">', '<table class="table table-nowrap table-hover list sortable">', $str);
		$str = str_ireplace('<table class="table zebra list sortable">', '<table class="table table-nowrap table-hover table-striped list sortable">', $str);
		

		$str = str_ireplace('textflow', 'text-overflow', $str);
		$str = str_ireplace('"center"', '"text-center"', $str);
		$str = str_ireplace(' center', ' text-center', $str);

		$str = str_ireplace('dialog-', 'js-', $str);
		$str = str_ireplace('<s></s>', '<s>|</s>', $str);
		$str = str_ireplace('btn-highlight', 'btn-primary', $str);


		debug::dump(file::put($file,$str));
	}

	/**
     * 表单替换
     *
     * @return void
     */
	public function action_form()
	{
		$file = a('developer.path').DS.'templates'.DS.'schema_post.php';
		
		$str  = file::get($file);

		$str = str_ireplace('<table class="field">', '<div class="container-fluid">', $str);
		$str = str_ireplace('</table>', '</div>', $str);

		$str = str_ireplace('<tbody>', '<div class="form-horizontal">', $str);
		$str = str_ireplace('</tbody>', '</div>', $str);

		$str = str_ireplace('<tr>', '<div class="form-group">', $str);
		$str = str_ireplace('</tr>', '</div>', $str);		

		$str = str_ireplace('<td class="label">', '<div class="col-sm-2 control-label">', $str);
		$str = str_ireplace('<td class="input">', '<div class="col-sm-8">', $str);
		$str = str_ireplace('</td>', '</div>', $str);

		$str = str_ireplace('icon icon', 'fa fa', $str);

		$str = str_ireplace('.disable(true);', ".button('loading');", $str);
		$str = str_ireplace('.disable(false);', ".button('reset');", $str);

		//$str = str_ireplace('<tr class="', '<div class="form-group ', $str);


		debug::dump(file::put($file,$str));
	}

	/**
     * 表单替换
     *
     * @return void
     */
	public function action_form2()
	{
		$file = a('developer.path').DS.'templates'.DS.'schema_post.php';
		
		$str  = file::get($file);

		$str = str_ireplace('<table class="field">', '<div class="container-fluid">', $str);
		$str = str_ireplace('</table>', '</div>', $str);

		$str = str_ireplace('<tbody>', '', $str);
		$str = str_ireplace('</tbody>', '', $str);

		$str = str_ireplace('<tr>', '<div class="form-group">', $str);
		$str = str_ireplace('</tr>', '</div>', $str);		

		$str = str_ireplace('<td class="label">', '', $str);
		$str = str_ireplace('<td class="input">', '', $str);
		$str = str_ireplace('</td>', '', $str);

		$str = str_ireplace('icon icon', 'fa fa', $str);

		$str = str_ireplace('.disable(true);', ".button('loading');", $str);
		$str = str_ireplace('.disable(false);', ".button('reset');", $str);

		

		//$str = str_ireplace('<tr class="', '<div class="form-group ', $str);


		debug::dump(file::put($file,$str));
	}	
}
?>