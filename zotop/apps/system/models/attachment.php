<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * attachment
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_attachment extends model
{
	protected $pk = 'id';
	protected $table = 'attachment';

	public $files = array();
	public $allowexts;
	public $savepath;
	public $maxsize = 20; //单位mb

	/**
	 * 初始化函数
	 */
	public function __construct()
	{
		parent::__construct();

		// 上传文件格式
		foreach( $this->types() as $type=>$name )
		{
			$this->allowexts = $this->allowexts.','.c('system.attachment_'.$type.'_exts');
		}

		$this->allowexts = substr($this->allowexts,1);

		// 上传路径
		$this->savepath = ZOTOP_PATH_UPLOADS.DS.C('system.upload_dir');
	}

    /**
     * 附件类型
	 *
	 * @param string $type 类型
	 * @return string|array
     */
	public function types($type='')
	{
		$types = zotop::filter('attachment.types',array(
			'image'	=> t('图像'),
			'file'	=> t('文件'),
			'video'	=> t('视频'),
			'audio'	=> t('音频')
		));

		return $type ? $types[$type] : $types;
	}

	/**
	 * 获取文件类型
	 *
	 * @param string $file 文件或者文件扩展名
	 * @return string
	 */
	public function type($ext = '')
	{
		$ext = strpos($ext, '.') === false ? strtolower($ext) : strtolower(trim(substr(strrchr($ext, '.'), 1, 10)));

		foreach ( $this->types() as $type=>$name )
		{
			if ( in_array( $ext, $this->exts($type, true) ) ) return $type;
		}

		return '';
	}

	/**
	 * 获取特定类型设置的文件格式
	 *
	 * @param string $type 文件类型
	 * @param string $a 是否返回数组
	 * @return string
	 */
	public function exts($type, $a = true)
	{
		$exts = c('system.attachment_'.$type.'_exts');

		return $a ? explode(',', $exts) : $exts;
	}

	/**
	 * 上传并返回文件的详细信息
	 *
	 * @param array $params 传入的参数
	 *
	 * 传入参数可能包含以下字段
	 *
	 * 'app', 'dataid', 'status', 'maxfile':
	 * 'image_resize','image_width', 'image_height','image_quality',
	 * 'watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity','watermark_quality'
	 *
	 * @return array 返回的数组数据
	 */
	public function upload($params = array())
	{
		$params = is_array($params) ? $params : array();

		// 获取图片缩放参数，如果没有传入任何图片参数，则使用系统默认的参数
		foreach( array('image_resize','image_width','image_height','image_quality','watermark','watermark_width','watermark_height','watermark_image','watermark_position','watermark_opacity','watermark_quality') as $param )
		{
			$params[$param] = isset($params[$param]) ? $params[$param] : c('system.'.$param);
		}

		// 初始化上传对象
		$upload = new plupload();
		$upload->allowexts	= $this->allowexts;
		$upload->maxsize	= $this->maxsize;
		$upload->savepath	= $this->savepath;

		// 上传文件
		if ( $filepath = $upload->save() )
		{
			// HOOK
			zotop::run('system.attachment.upload', $filepath, $params);

			// 导入参数
			extract($params,EXTR_OVERWRITE);

			// 获取文件详细信息
			$data['name']	= $_REQUEST["filename"] ? $_REQUEST["filename"] : basename($filepath);
			$data['ext']	= file::ext($data['name']);
			$data['size']	= file::size($filepath);
			$data['type']	= $this->type($data['ext']); //文件类型

			if ( $data['type'] == 'image' )
			{
				try{
					
					$image	= new image($filepath);

					// 写入图片信息
					$data 	= array_merge($data, $image->info);

					// 压缩
					if ( $image_resize and ( $data['width'] > $image_width or $data['height'] > $image_height ) )
					{
						if ( $image->quality($image_quality)->resize($image_width, $image_height)->save() )
						{
							$data = array_merge($data, image::info($filepath));
						}
					}

					//水印
					if ( $watermark and $watermark_image and ( $data['width'] > $watermark_width or $data['height'] > $watermark_height ) )
					{
						$watermark_image = ZOTOP_PATH_CMS.DS.'watermark'.DS.$watermark_image;

						if ( is_file($watermark_image) and $image->quality($watermark_quality)->watermark($watermark_image, $watermark_position, $watermark_opacity)->save() )
						{
							$data = array_merge($data, image::info($filepath));
						}
					}

				}
				catch(Exception $e)
				{
					//忽略错误
				}
			}

			$data['id']			= md5($filepath);
			$data['guid']		= md5_file($filepath);
			$data['folderid']	= $folderid;
			$data['app']		= $app ? $app : ZOTOP_APP;
			$data['field']		= $field;
			$data['status'] 	= isset($status) ? $status : ( $dataid ? 1 : 0 );
			$data['dataid'] 	= $dataid ? $dataid : zotop::session('[id]');
			$data['path']		= format::path(substr($filepath, strlen(ZOTOP_PATH_UPLOADS)));
			$data['url']		= format::url(ZOTOP_URL_UPLOADS.'/'.$data['path']);

			if ( $this->add($data) )
			{
				return $data;
			}

			return $this->error(t('保存失败'));
		}

		return $this->error($upload->error);
	}

    /**
     * 添加
     *
     */
	public function add($data)
	{
		$data['description']= $data['description'] ? $data['description'] : file::name($data['name'],true);
		$data['userid']		= zotop::user('id');
		$data['uploadip']	= request::ip();
		$data['uploadtime'] = ZOTOP_TIME;

		return $this->insert($data);
	}

	/*
	 * 删除文件(如果是图片则删除附带的图片)
	 *
	 */
	public function remove($file)
	{
		// hook
		zotop::run('system.attachment.remove',$file);

		$file = format::path($file);

		if ( @unlink($file) )
		{
			$files = glob(dirname($file). DS .'*'.basename($file));

			if ( $files )
			{
				foreach( (array)$files as $file )
				{
					@unlink($file);
				}
			}

			return true;
		}

		return false;
	}

	/**
	* 根据编号删除文件
	*
	*/
	public function delete($id)
	{
		if ( is_array( $id ) )
		{
			return array_map( array($this,'delete'), $id );
		}

		if ( $id and $file = $this->getbyid($id) )
		{
			// 删除文件
			$this->remove(ZOTOP_PATH_UPLOADS.DS.$file['path']);

			// 删除记录
			return parent::delete($id);
		}

		return false;
	}


	/**
	 * 根据数据编号和类型获取相关附件数据
	 * 
	 * @param  [type]  $dataid [description]
	 * @param  [type]  $type   [description]
	 * @param  integer $limit  [description]
	 * @return [type]          [description]
	 */
	public function getRelated($dataid=null, $type=null, $limit=100)
	{

		// 如果没有传入dataid，则dataid为用户sessionid
		$dataid = empty($dataid) ? zotop::session('[id]') : $dataid;

		if ( $dataid ) 
		{
			$this->where('dataid', '=', $dataid);
		}

		if ( $type )
		{
			$this->where('type', '=', $type);
		} 	

		return $this->field('*')->orderby('uploadtime')->limit($limit)->select();
	}

	/**
	* 将临时id设为相关的数据id
	* 一般上传未保存的数据dataid为用户的sessionid，保存数据时候调用此函数，将临时dataid变更为数据dataid
	*/
	public function setRelated($dataid, $tempid=null)
	{
		if ( !$dataid ) return false;
		if ( !$tempid ) $tempid = zotop::session('[id]');

		return $this->data('app',ZOTOP_APP)->data('dataid',$dataid)->data('status',1)->where('dataid','=',$tempid)->update();
	}


	/*
	*  删除关联附件
	*/
	public function delRelated($dataid)
	{
		if ( is_array( $dataid ) )
		{
			return array_map( array($this,'delRelated'), $dataid );
		}

		$data = $this->where('dataid','=', $dataid )->select();

		//删除关联的附件
		if( is_array($data) )
		{
			foreach ( $data as $file )
			{
				$this->remove(ZOTOP_PATH_UPLOADS.DS.$file['path']);
			}
		}

		unset($data);

		return parent::delete(array('dataid','=',$dataid));
	}
}
?>