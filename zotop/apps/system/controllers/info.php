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
class system_controller_info extends admin_controller
{
	
	/**
	 * 覆盖系统默认的权限检查（系统信息不检查权限，只要是系统用户都可以访问）
	 *
     * @return bool	 
	 */	
	public function __checkPriv()
	{
		return true;
	}


	/**
	 * 页面导航，内置 system.info.navbar ，可以对该导航进行扩展
	 *
     * @return array	 
	 */	
	public function navbar()
	{
		$navbar = array(
			'index'		=> array('text'=>t('服务器信息'),'href'=>u('system/info/index'),'icon'=>'fa-server'),
			'io'		=> array('text'=>t('文件和目录权限'),'href'=>u('system/info/io'),'icon'=>'fa-folder'),
			'about'		=> array('text'=>t('关于zotop'),'href'=>u('system/info/about'),'icon'=>'fa-info-circle'),
		);

		$navbar = zotop::filter('system.info.navbar',$navbar);

		return $navbar;
	}

	/**
	 * 服务器信息
	 *
	 */
	public function action_index()
    {
		$this->assign('title',t('系统信息'));
		$this->assign('navbar',$this->navbar());
		$this->assign('rewrite',rewrite::check());
		$this->display();
    }

	/**
	 * 文件和目录权限
	 *
	 */
	public function action_io()
    {
		// 检查目录权限
    	$list = array(
    		ZOTOP_PATH 			=> array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_DATA 	=> array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_APPS 	=> array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_RUNTIME 	=> array('name'=>t('运行时目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_THEMES 	=> array('name'=>t('主题和模板目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CACHE 	=> array('name'=>t('缓存目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_UPLOADS 	=> array('name'=>t('文件上传目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_LIBRARIES => array('name'=>t('类库'), 'type'=>'folder', 'writable'=>false),
    	);

    	$success = 1;

    	foreach ($list as $f => &$r)
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
    		$r['position'] = '/'.str_replace(DS,'/', trim(str_replace(ZOTOP_PATH, '', $f), DS));
    		if ( !$is_writable AND $r['writable'] ) $success = false;
    	}

		$this->assign('title',t('系统信息'));
		$this->assign('navbar',$this->navbar());
		$this->assign('list',$list);
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

	    //$phpinfo = str_replace('class="e"','style="color:#ff6600;white-space:nowrap;"',$phpinfo);
		//$phpinfo = str_replace('class="v"','',$phpinfo);
		$phpinfo = str_replace('<hr />','',$phpinfo);
		$phpinfo = str_replace('<br>','',$phpinfo);
		$phpinfo = str_replace('&nbsp;&nbsp;','',$phpinfo);
		$phpinfo = str_replace('<table','<table class="table list"',$phpinfo);
		$phpinfo = str_replace('<tr class="h">','<tr class="title">',$phpinfo);
	    $phpinfo = preg_replace('/<a href="http:\/\/www.php.net\/"><img(.*)alt="PHP Logo" \/><\/a><h1 class="p">(.*)<\/h1>/',"<h1>\\2</h1>",$phpinfo);
		$phpinfo = preg_replace('/<a href="http:\/\/www.zend.com\/"><img(.*)><\/a>/','',$phpinfo);

		$this->assign('title',t('系统信息'));
		$this->assign('navbar',$this->navbar());
	    $this->assign('phpinfo',$phpinfo);
	    $this->display();
    }

    /**
     * 关于
     *
     * @return void
     */
	public function action_about()
	{
		$this->assign('title',t('系统信息'));
		$this->assign('navbar',$this->navbar());
	    $this->display();
	}
}
?>