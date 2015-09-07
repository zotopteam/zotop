<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 系统日志
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_log extends admin_controller
{
    protected $log;

    /*
    * 初始化
    */
    public function __init()
    {
        parent::__init();

        $this->log = m('system.log');
    }

    /**
     * 日志
     *
     */
    public function action_index()
    {
        // 自动删除过期日志
		$expire = ZOTOP_TIME - intval(c('system.log_expire')) * 24 * 60 * 60;

		m('system.log')->where('createtime','<',$expire)->delete();

		// 获取全部数据
        $dataset = m('system.log')->orderby('createtime','desc')->getPage(0,3);

        $this->assign('title', t('系统操作日志'));
        $this->assign($dataset);
        $this->display();
    }


}
?>