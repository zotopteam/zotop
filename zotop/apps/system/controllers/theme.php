<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 主题控制器
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_controller_theme extends admin_controller
{
	private $theme	= null;
	private $root	= null;
	private $path	= null;
	private $dir	= null;
	private $file	= null;


	public function __init()
	{
		parent::__init();

		// 获取当前主题
		$this->theme = $_GET['theme'] ? $_GET['theme'] : c('site.theme');

		// 获取模版根目录
		$this->root = ZOTOP_PATH_THEMES.DS.$this->theme.DS.'templates';

		// 获取当前文件名称
		$this->file = trim(rawurldecode($_GET['file']),'/');

		// 获取当前目录
		$this->dir = empty($this->file) ? trim(rawurldecode($_GET['dir']),'/') : trim(dirname($this->file),'./');

		// 获取当前路径
		$this->path = $this->root.DS.$this->dir;

	}

	/**
	 * 管理主题
	 *
	 */
	public function action_index()
    {
		// 获取全部主题
		$themes = array();
		$folders = folder::folders(ZOTOP_PATH_THEMES);

		foreach( (array) $folders as $folder )
		{
			$theme = ZOTOP_PATH_THEMES.DS.$folder.DS.'theme.php';

			if ( file::exists($theme) )
			{
				$theme = @include($theme);

				if ( is_array($theme) )
				{
					$themes[$folder] = $theme;
					$themes[$folder]['id'] = $folder;
					foreach ( array( 'png', 'gif', 'jpg', 'jpeg' ) as $ext )
					{
						if ( file::exists(ZOTOP_PATH_THEMES.DS.$folder.DS.'theme.'.$ext) )
						{
							$themes[$folder]['image'] = format::url(ZOTOP_URL_THEMES.'/'.$folder.'/theme.'.$ext);
							break;
						}
					}
				}
			}
		}

		// 获取已经在使用的主题包
		$current = array(c('site.theme'));

		$this->assign('title',t('主题管理'));
		$this->assign('themes',$themes);
		$this->assign('current',$current);
		$this->display();
    }

	/**
	 * 上传新主题
	 *
	 */
	/**
	 * upload process
	 *
	 */
	public function action_uploadprocess()
	{
		// 强制声明为AJAX状态
		$_REQUEST['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

		// 将文件上传到应用目录下
		$filepath = ZOTOP_PATH_THEMES.DS.$_POST['filename'];

		// 文件上传
		$upload = new plupload();
		$upload->allowexts = 'zip';
		$upload->maxsize = 0;

		if ( $file = $upload->save($filepath) )
		{
			// 解压文件
			if ( $dir = file::unzip($file, null, false) )
			{
				file::delete($file);

				if ( file::exists($dir.DS.'theme.php') )
				{
					return $this->success(t('上传成功'),u('system/theme'));
				}

				folder::delete($dir,true);
				file::delete($file);
				return $this->error(t('错误的主装包文件'));
			}

			file::delete($file);
			return $this->error(t('解压缩主装包文件失败'));
		}

		$this->error($upload->error);
	}

	/**
	 * 删除主题
	 *
	 */
	public function action_delete($id)
    {
		if ( $this->post() )
		{
			// 禁止删除已经在使用的主题包
			if ( in_array($id, array(c('site.theme'))) ) $this->error(t('无法删除正在使用中的主题包'));

			if ( folder::delete(ZOTOP_PATH_THEMES.DS.$id,true) )
			{
				return $this->success(t('删除成功'),request::referer());
			}

			return $this->error(t('删除失败'));
		}

		return $this->error(t('禁止访问'));
	}


	/**
	 * 管理模版
	 *
	 */
	public function action_template($type='')
    {
		// 获取当前路径下的全部文件夹
		$_folders = folder::folders($this->path);
		$_folders = is_array($_folders) ? $_folders : array();

		//获取当前路径下的全部文件
		$_files = folder::files($this->path);
		$_files = is_array($_files) ? $_files : array();

		// 获取全部注释
		$notes = @include($this->path.DS.'_notes.php');

		$folders = $files = array();

		// 获取文件夹详细信息
		foreach ($_folders as $f)
		{
			$folders[] = array(
				'name'	=>	$f,
				'path'	=>	rawurlencode($this->dir.'/'.$f),
				'time'	=>	file::time($this->path.DS.$f),
				'note'	=>	$notes[$f],
			);
		}

		// 获取文件详细信息
		foreach ($_files as $f)
		{
			if ( $f == '_notes.php' ) continue;

			$files[] = array(
				'name'	=>	$f,
				'file'	=>	trim($this->dir.'/'.$f,'/'),
				'path'	=>	rawurlencode($this->dir.'/'.$f),
				'size'	=>	file::size($this->path.DS.$f),
				'time'	=>	file::time($this->path.DS.$f),
				'ext'	=>	file::ext($f),
				'note'	=>	$notes[$f]
			);
		}

		// 解析path
		$position = folder::dirmap($this->dir);

		// 选择还是管理模版
		$template = ( $type == 'select' ) ? 'system/theme_template_select.php' : 'system/theme_template.php';

		$this->assign('title',t('模板管理'));
		$this->assign('theme',$this->theme);
		$this->assign('position',$position);
		$this->assign('folders',$folders);
		$this->assign('files',$files);
		$this->assign('dir',rawurlencode($this->dir));
		$this->display($template);
    }

	/**
	 * 新建目录
	 *
	 */
	public function action_template_newfolder()
    {
		if ( $post = $this->post() )
		{
			$notes_path = $this->path.DS.'_notes.php';
			$notes = @include($notes_path);

			if ( empty($post['name']) ) return $this->error(t('名称不能为空'));
			if ( !preg_match( "/^\w+$/", $post['name'] ) ) return $this->error(t('名称只能由数字、英文字母和下划线组成'));

			if ( folder::create($this->path.DS.$post['name']) )
			{
				//更改note
				$notes[$post['name']] = $post['note'];

				file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

				return $this->success(t('操作成功'));
			}

			return $this->error(t('文件夹已经存在'));
		}
		$data = array();
		$this->assign('title',t('新建文件夹'));
		$this->assign('data',$data);
		$this->display('system/theme_template_folder.php');
	}

	/**
	 * 重命名文件夹
	 *
	 */
	public function action_template_renamefolder()
    {
		// 获取notes
		$notes_path = dirname($this->path).DS.'_notes.php';
		$notes = @include($notes_path);

		if ( $post = $this->post() )
		{
			if ( empty($post['name']) ) return $this->error(t('名称不能为空'));
			if ( !preg_match( "/^\w+$/", $post['name'] ) ) return $this->error(t('名称只能由数字、英文字母和下划线组成'));

			if ( folder::rename($this->path, $post['name']) )
			{
				//更改note
				$notes[$post['name']] = $post['note'];

				file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

				return $this->success(t('操作成功'));
			}

			return $this->error(t('文件夹已经存在'));
		}

		$data = array();
		$data['name'] = basename($this->dir);
		$data['note'] = $notes[$data['name']];


		$this->assign('title',t('重命名文件夹'));
		$this->assign('data',$data);
		$this->display('system/theme_template_folder.php');
	}

	/**
	 * 删除文件夹
	 *
	 */
	public function action_template_deletefolder()
    {
		//文件路径
		$path = $this->path;

		//检查文件夹是否为空,只能删除空文件夹(不包含_notes.php)
		$items = glob($path.DS.'*');

		if( is_array($items) AND count($items)>1 )
		{
			return $this->error(t('删除失败，请先清空文件夹'));
		}

		if( folder::delete($path,true,true) or !folder::exists($path) )
		{
			$notes_path = dirname($path).DS.'_notes.php';

			$notes = @include($notes_path);

			unset($notes[basename($path)]);

			file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error(t('操作失败'));
	}

	/**
	 * 重命名文件
	 *
	 */
	public function action_template_renamefile()
    {
		// 获取notes
		$notes_path = $this->path.DS.'_notes.php';
		$notes = @include($notes_path);

		if ( $post = $this->post() )
		{
			if ( empty($post['name']) ) return $this->error(t('名称不能为空'));
			if ( !preg_match( "/^\w+$/", $post['name'] ) ) return $this->error(t('名称只能由数字、英文字母和下划线组成'));


			$name = $post['name'].'.'.$post['ext'];
			$file = $this->root.DS.$this->file;

			if ( file::rename($file, $name) )
			{
				//更改note
				$notes[$name] = $post['note'];

				file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

				return $this->success(t('操作成功'));
			}

			return $this->error(t('文件已经存在'));

		}

		$data = array();
		$data['name'] = file::name($this->file,true);
		$data['note'] = $notes[basename($this->file)];
		$data['ext'] = file::ext($this->file);

		$this->assign('title',t('重命名文件'));
		$this->assign('data',$data);
		$this->display('system/theme_template_file.php');
	}

	/**
	 * 复制文件
	 *
	 */
	public function action_template_copyfile()
    {
		// 获取notes
		$notes_path = $this->path.DS.'_notes.php';
		$notes = @include($notes_path);

		if ( $post = $this->post() )
		{
			if ( empty($post['name']) ) return $this->error(t('名称不能为空'));
			if ( !preg_match( "/^\w+$/", $post['name'] ) ) return $this->error(t('名称只能由数字、英文字母和下划线组成'));

			$file = $this->root.DS.$this->file;
			$name = $post['name'].'.'.$post['ext'];


			if ( file::copy($file, dirname($file).DS.$name, false) )
			{
				//更改note
				$notes[$name] = $post['note'];

				file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

				return $this->success(t('操作成功'));
			}

			return $this->error(t('文件已经存在'));

		}

		$data = array();
		$data['name'] = file::name($this->file,true).'_copy';
		$data['note'] = $notes[basename($this->file)];
		$data['ext'] = file::ext($this->file);

		$this->assign('title',t('复制文件'));
		$this->assign('data',$data);
		$this->display('system/theme_template_file.php');
	}

	/**
	 * 新建文件
	 *
	 */
	public function action_template_newfile()
    {
		if ( $post = $this->post() )
		{
			$notes_path = $this->path.DS.'_notes.php';
			$notes = @include($notes_path);

			if ( empty($post['name']) ) return $this->error(t('名称不能为空'));
			if ( !preg_match( "/^\w+$/", $post['name'] ) ) return $this->error(t('名称只能由数字、英文字母和下划线组成'));

			$name = $post['name'].'.'.$post['ext'];
			$content = "{template 'header.php'}\r\n\r\n{template 'footer.php'}";

			// 重命名文件夹
			if ( file::put($this->path.DS.$name,$content,false) )
			{
				//更改note
				$notes[$name] = $post['note'];

				file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

				return $this->success(t('操作成功'));
			}

			return $this->error(t('文件已经存在'));
		}

		$data = array();
		$data['ext'] = 'php';
		$this->assign('title',t('新建文件'));
		$this->assign('data',$data);
		$this->display('system/theme_template_file.php');
	}

	/**
	 * 编辑模板
	 *
	 */
	public function action_template_edit()
    {
		$file = $this->root.DS.$this->file;

		if ( $post = $this->post() )
		{
			if ( file::put($file,$post['content'],true) )
			{
				return $this->success(t('保存成功'));
			}

			return $this->error(t('文件已经存在'));
		}

		$content = file::get($file);

		$this->assign('title',t('编辑'));
		$this->assign('file',$this->file);
		$this->assign('content',$content);
		$this->display();
	}

	/**
	 * 删除模版
	 *
	 */
	public function action_template_deletefile()
    {
		//文件路径
		$file = $this->root.DS.$this->file;

		if( file::delete($file) or !file::exists($file) )
		{
			$notes_path = dirname($file).DS.'_notes.php';

			$notes = @include($notes_path);

			unset($notes[basename($file)]);

			file::put($notes_path, "<?php\nreturn ".var_export($notes, true).";\n?>");

			return $this->success(t('操作成功'),request::referer());
		}

		return $this->error(t('操作失败'));
	}
}
?>