<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 系统信息控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_system extends admin_controller
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
	 * 系统管理
	 *
	 */
	public function action_index()
    {
		$system = @include(A('system.path').DS.'data'.DS.'system.php');
		$system = zotop::filter('system.system',$system);

		if ( is_array($system) )
		{
			foreach( $system as $id => $nav )
			{
				if ( $nav['allow'] === false ) unset($system[$id]);
			}
		}

		$this->assign('title',t('系统'));
		$this->assign('system', $system);
		$this->display();
	}

	/**
	 * 一键清理清理缓存数据
	 * 
	 * @return mixed
	 */
	public function action_refresh()
    {
		if ( $post = $this->post() )
		{
			@set_time_limit(0);

			foreach( array('caches', 'temp','templates') as $folder )
			{
				folder::clear(ZOTOP_PATH_RUNTIME.DS.$folder);
			}

			zotop::cache(null);

			zotop::run('system.refresh');

			$this->success(t('刷新成功'));
		}
	}

	/**
	 * 系统重启，重启将清理系统缓存、运行时等数据
	 *
	 */
	public function action_reboot()
    {
		if ( $this->post() )
		{

			// 清理运行时
			foreach( array('block','caches','temp','templates','sessions') as $folder )
			{
				folder::clear(ZOTOP_PATH_RUNTIME.DS.$folder);
			}

			// 清理缓存
			zotop::cache(null);

			// 清理系统运行文件
			zotop::reboot();

			$this->success(t('重启成功'));
		}
    }

    /**
     * 针对rewrite验证的请求返回
     *
     * @access public
     * @return void
     */
	public function action_rewriteCallback()
	{
		exit('ok');
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

		$this->assign('title',t('系统信息'));
	    $this->assign('phpinfo',$phpinfo);
	    $this->display();
    }


	/**
	 * 系统信息检查，检查服务器信息、文件和目录权限
	 * 
	 * @return [type] [description]
	 */
	public function action_check()
    {
		// 检查文件和目录权限
    	$check_io = array(
			ZOTOP_PATH           => array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CMS       => array('name'=>t('主目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_DATA      => array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_APPS      => array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
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
    			if ( $r['type'] == 'file' )
    			{
    				$is_writable = (false !== @file_put_contents($f, ''));
    			}
    			else
    			{
    				$is_writable = folder::create($f, 0777);
    			}
    		}
    		else
    		{
    			$is_writable = is_writable($f) && (($r['type'] == 'file') ^ is_dir($f));
    		}

			$r['is_writable'] = $is_writable;
			$r['position']    = '/'.str_replace('\\','/',trim(str_replace(ZOTOP_PATH, '', $f),'\\'));
    	}

		$this->assign('title',t('服务器信息'));
		$this->assign('description',t('服务器信息及文件和目录权限检测'));
		$this->assign('check_rewrite',rewrite::check());
		$this->assign('check_io',$check_io);
		$this->display();
    }    

    /**
     * 关于
     *
     * @return void
     */
	public function action_about()
	{
		$license = file::get(A('system.path').DS.'license.txt');
		$license = format::textarea($license);

		$this->assign('title',t('关于zotop'));
		$this->assign('license',$license);
		$this->display();
	}
}
?>