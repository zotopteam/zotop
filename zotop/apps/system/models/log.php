<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 日志模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_log extends model
{
	protected $pk = 'id';
	protected $table = 'log';

	/**
	 * 写入日志
	 *
	 * @param string $state 操作状态 error,success
	 * @param string $data 操作说明
	 * @return string
	 */
	public function write($state, $data)
	{
		$data = array(
			'state' => $state ? 'success' : 'error',
			'app' => ZOTOP_APP,
			'controller' => ZOTOP_CONTROLLER,
			'action' => ZOTOP_ACTION,
			'data' => $data,
			'userid' => zotop::user('id'),
			'username' => zotop::user('username') ? zotop::user('username') : $_POST['username'],
			'url' => request::url(false),
			'createip' => request::ip(),
			'createtime' => ZOTOP_TIME
		);

		return $this->insert($data);
	}
}
?>