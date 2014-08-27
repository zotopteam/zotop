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
	 */
	public function action_onekeyclear()
    {
		if ( $post = $this->post() )
		{
			@set_time_limit(0);

			foreach( array('block', 'caches', 'temp') as $folder )
			{
				folder::clear(ZOTOP_PATH_RUNTIME.DS.$folder);
			}

			zotop::cache(null);

			zotop::run('system.onekeyclear');

			$this->success(t('清理成功'));
		}
	}

	/**
	 * 清理缓存，此功能暂时无用，使用一键清理和系统重启代替
	 *
	 */
	public function action_clear()
    {
		if ( $post = $this->post() )
		{
			if( !is_array($post['id']) )  return $this->error(t('请选择要操作的项'));

			@set_time_limit(0);

			foreach( $post['id'] as $id )
			{
				if ( in_array($id, array('caches','temp','templates','tables','sessions')) )
				{
					folder::clear(ZOTOP_PATH_RUNTIME.DS.$id);
				}

				if ( $id == "caches" )
				{
					zotop::cache(null);
				}

				if ( $id == 'core' )
				{
					zotop::reboot();
				}

			}

			zotop::run('system.clear',$post['id']);

			$this->success(t('清理成功'),u('system/system/clear'));
		}

		$caches = array(
			array('id'=>'caches','name'=> t('数据缓存'),'description'=>t('数据及内容缓存'),'checked'=>true),
			array('id'=>'temp','name'=> t('临时文件'),'description'=>t('网站运行中产生的临时文件'),'checked'=>false),
			array('id'=>'templates','name'=> t('模板缓存'),'description'=>t('网站模版缓存'),'checked'=>false),
			array('id'=>'tables','name'=> t('数据表缓存'),'description'=>t('数据库数据表信息缓存数据'),'checked'=>false),
			array('id'=>'core','name'=> t('核心缓存'),'description'=>t('系统核心缓存'),'checked'=>false),
		);

		$this->assign('title',t('清理缓存'));
		$this->assign('caches',$caches);
		$this->display();
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
	 * 服务器信息
	 *
	 */
	public function action_server()
    {
		$this->assign('title',t('服务器信息'));
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
    		ZOTOP_PATH => array('name'=>t('根目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CMS => array('name'=>t('主目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_DATA => array('name'=>t('数据目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_APPS => array('name'=>t('应用目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_RUNTIME => array('name'=>t('运行时目录'), 'type'=>'folder', 'writable'=>true),
    		ZOTOP_PATH_THEMES => array('name'=>t('主题和模板目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_CACHE => array('name'=>t('缓存目录'), 'type'=>'folder', 'writable'=>true),
			ZOTOP_PATH_UPLOADS => array('name'=>t('文件上传目录'), 'type'=>'folder', 'writable'=>true),
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
    		$r['position'] = '/'.str_replace('\\','/',trim(str_replace(ZOTOP_PATH, '', $f),'\\'));
    		if ( !$is_writable AND $r['writable'] ) $success = false;
    	}

		$this->assign('title',t('文件和目录权限'));
		$this->assign('list',$list);
		$this->display();
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
		$license = file::get(A('system.path').DS.'license.txt');
		$license = format::textarea($license);

		$this->assign('title',t('关于zotop'));
		$this->assign('license',$license);
	    $this->display();
	}
}
?>