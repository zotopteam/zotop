<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统检测
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_check extends admin_controller
{
	
	/**
	 * 覆盖权限检查，当前页面的功能不检查权限
	 * 
	 * @return true
	 */
	public function __checkPriv()
	{
		return true;
	}

	/**
	 * 系统信息检查，检查服务器信息、文件和目录权限
	 * 
	 * @return [type] [description]
	 */
	public function action_index()
    {
		// 检查文件和目录权限
    	$check_io = array(
			ZOTOP_PATH           => array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CMS       => array('name'=>t('主目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_DATA      => array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_APPS      => array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CONFIG    => array('name'=>t('配置目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_RUNTIME   => array('name'=>t('运行时目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_THEMES    => array('name'=>t('主题和模板目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CACHE     => array('name'=>t('缓存目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_UPLOADS   => array('name'=>t('文件上传目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_LIBRARIES => array('name'=>t('类库'), 'type'=>'folder', 'writable'=>false),
    	);

    	foreach ($check_io as $f => &$r)
    	{
    		if ( !file_exists($f))
    		{
    			$is_writable = ($r['type'] == 'file') ? (false !== @file_put_contents($f, '')) : folder::create($f, 0777);
    		}
    		else
    		{
    			$is_writable = ($r['type'] == 'file') ? is_writable($f) : folder::writeable($f);
    		}

			$r['is_writable'] = $is_writable;

			$r['position']    = '/'.str_replace(DS,'/', trim(str_replace(ZOTOP_PATH, '', $f), DS));
    	}

		$this->assign('title',t('系统信息'));
		$this->assign('description',t('服务器信息及文件和目录权限检测'));
		$this->assign('check_rewrite',rewrite::check());
		$this->assign('check_io',$check_io);
		$this->display();
    }

    /**
     * phpinfo
     *
     * @return void
     */
    public function action_phpinfo()
    {
	    ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		ob_clean();

		if( preg_match('/<body><div class="center">([\s\S]*?)<\/div><\/body>/',$phpinfo,$match) )
		{
		    $phpinfo =$match[1];
		}

		$phpinfo = str_replace('<hr />','',$phpinfo);
		$phpinfo = str_replace('<br>','',$phpinfo);
		$phpinfo = str_replace('&nbsp;&nbsp;','',$phpinfo);		
		$phpinfo = preg_replace('/<tr(.*?)>/','<tr>',$phpinfo);
		$phpinfo = preg_replace('/<td(.*?)>/','<td>',$phpinfo);
		$phpinfo = preg_replace('/<th(.*?)>/','<th>',$phpinfo);
		$phpinfo = preg_replace('/<table(.*?)>/','<table class="table">',$phpinfo);
	    $phpinfo = preg_replace('/<a href="http:\/\/www.php.net\/"><img(.*)alt="PHP Logo" \/><\/a><h1 class="p">(.*)<\/h1>/',"<h1>\\2</h1>",$phpinfo);
		$phpinfo = preg_replace('/<a href="http:\/\/www.zend.com\/"><img(.*)><\/a>/','',$phpinfo);

		$this->assign('title',t('PHPINFO'));
	    $this->assign('phpinfo',$phpinfo);
	    $this->display();
    }

    /**
     * 针对rewrite验证的请求返回
     *
     * @access public
     * @return void
     */
	public function action_rewritecallback()
	{
		exit('ok');
	}    
}
?>