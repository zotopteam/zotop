<?php
defined('ZOTOP') OR die('No direct access allowed.');

/**
 * 图片类，Thanks for Kohana
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.ui
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class image
{
	// 缩放方向
	const NONE       = 'none';
	const WIDTH      = 'width';
	const HEIGHT     = 'height';
	const AUTO       = 'auto';
	const INVERSE    = 'inverse';
	
	// 翻转方向
	const H          = 'h';
	const V          = 'v';
	const HORIZONTAL = 'H';
	const VERTICAL   = 'V';

	/**
	 * @var 驱动
	 */
	public $driver;


	/**
	 * @var 操作
	 */
	public $actions = array();

	/**
	 * @var 图片文件地址
	 */
	public $file;

	/**
	 * 图片处理工厂模式
	 *
	 *     $image = image::factory('upload/test.jpg');
	 *
	 * @param   string   file file path
	 * @param   string   driver type: GD, ImageMagick, etc
	 * @return  Image
	 */
	public static function open($file, $driver = null)
	{
		return new image($file, $driver);
	}

	/**
	 * 获取图片信息
	 *
	 * @param string $file 图片的地址,可以使用相对绝对地址或者相对地址
	 * @return string
	 */
	public static function info($file)
	{
		$info = @getimagesize($file);

		// 如果是一个存在的图片
        if ( $info and is_array($info) )
		{
			return array(
				'width'	=> intval($info[0]),
				'height'=> intval($info[1]),
				'size'	=> @filesize($file),
				'mime'	=> $info['mime'],
			);
		}

		return array();
	}	

	/**
	 * 初始化
	 *
	 * @param   string   $image	图片地址
	 * @param   string   $driver 驱动
	 * @return  object
	 */
	public function __construct($file, $driver = null)
	{
		//图片文件
		if ( preg_match( "/^(".preg_quote( ZOTOP_URL, "/" )."|".preg_quote(ZOTOP_PATH, "/" ).")(.*)\$/", $file, $matches ) )
		{
			$file = $matches[2];
		}

		// 格式化地址
		$this->file = strpos($file, ZOTOP_PATH) === false ? ZOTOP_PATH.DS.$file : $file;
		$this->file = format::path($this->file);

		// 获取图片信息		
		$this->info  = image::info($this->file);

		if ( empty($this->info) )
		{
			throw new zotop_exception(t('无效的图片文件 %s',$this->file));
		}

		//获取图片信息
		$driver = empty($driver) ? 'image_gd' : "image_{$driver}";

		//加载驱动程序
		if ( !zotop::autoload($driver) )
		{
			throw new zotop_exception(t('未能找到图片驱动 %s',$driver));
		}

		// 实例化driver
		$this->driver = new $driver();
	}

	public function valid($type, $value, $default='')
	{
		switch($type)
		{
			case 'width':
				//转化类似于 120px
				if ( is_string($value) AND !ctype_digit($value) )
				{
					if ( !preg_match('/^[0-9]++%$/D', $value) )
					{
						throw new zotop_exception(t('无效的图片宽度: %s',$value));
					}
				}
				else
				{
					$value = (int) $value;
				}
				break;
			case 'height':
				//转化类似于 120px
				if ( is_string($value) AND !ctype_digit($value) )
				{
					if ( !preg_match('/^[0-9]++%$/D', $value) )
					{
						throw new zotop_exception(t('无效的图片高度: %s',$value));
					}
				}
				else
				{
					$value = (int) $value;
				}
				break;
			case 'master':
				if ( $value !== image::NONE AND $value !== image::AUTO AND $value !== image::WIDTH AND $value !== image::HEIGHT AND $value !== image::INVERSE )
				{
					throw new zotop_exception(t('无效的master %s',$value));
				}
				break;
			case 'x':
				if ( is_string($value) AND !ctype_digit($value) )
				{
					if ( !in_array($value, array('top', 'bottom', 'center')))
					{
						throw new zotop_exception(t('无效的top offset  %s，支持 top、bottom、center 或者 数字 ',$value));
					}
				}
				else
				{
					$value = (int) $value;
				}
				break;
			case 'y':
				if (is_string($value) AND ! ctype_digit($value))
				{
					if ( ! in_array($value, array('left', 'right', 'center')))
					{
						throw new zotop_exception(t('无效的left offset  %s，支持 left、right、center  或者 数字 ',$value));
					}
				}
				else
				{
					$value = (int) $value;
				}
				break;
		}

		return $value;
	}

	/**
	 * 格式化图片偏移为具体的数值
	 *
	 * @param   array  含有位置的属性
	 * @return  void
	 */
	public static function sanitize_geometry($geometry, $width, $height)
	{
		// Turn off error reporting
		$reporting = error_reporting(0);

		// Width and height cannot exceed current image size
		$geometry['width']  = min($geometry['width'], $width);
		$geometry['height'] = min($geometry['height'], $height);

		// Set standard coordinates if given, otherwise use pixel values
		if ($geometry['top'] === 'center')
		{
			$geometry['top'] = floor(($height / 2) - ($geometry['height'] / 2));
		}
		elseif ($geometry['top'] === 'top')
		{
			$geometry['top'] = 0;
		}
		elseif ($geometry['top'] === 'bottom')
		{
			$geometry['top'] = $height - $geometry['height'];
		}

		// Set standard coordinates if given, otherwise use pixel values
		if ($geometry['left'] === 'center')
		{
			$geometry['left'] = floor(($width / 2) - ($geometry['width'] / 2));
		}
		elseif ($geometry['left'] === 'left')
		{
			$geometry['left'] = 0;
		}
		elseif ($geometry['left'] === 'right')
		{
			$geometry['left'] = $width - $geometry['height'];
		}

		// Restore error reporting
		error_reporting($reporting);

		return $geometry;
	}

	/**
	 * 翻转图片
	 *
	 * image::H,image::HORIZONTAL 横向翻转
	 * image::V,image::VERTICAL 竖向翻转
	 *
	 * @param   integer  direction
	 * @return  object
	 */
	public function flip($direction)
	{
		if ( !in_array($direction,array(image::H, image::HORIZONTAL, image::V, image::VERTICAL)) )
		{
			throw new zotop_exception(t('参数错误，可选参数：image::H | image::V '));
		}

		$this->actions['flip'] = $direction;

		return $this;
	}

	/**
	 * 旋转图片到特定角度
	 *
	 * @param   integer  $degrees  角度值
	 * @return  object
	 */
	public function rotate($degrees)
	{
		$degrees = (int) $degrees;

		if ($degrees > 180)
		{
			do
			{
				$degrees -= 360;
			}
			while($degrees > 180);
		}

		if ($degrees < -180)
		{
			do
			{
				$degrees += 360;
			}
			while($degrees < -180);
		}

		$this->actions['rotate'] = $degrees;

		return $this;
	}

	/**
	 * 设置图片质量
	 *
	 * @param   integer  图片质量，1到100之间
	 * @return  object
	 */
	public function quality($amount)
	{
		$this->actions['quality'] = max(1, min(intval($amount), 100));

		return $this;
	}

	/**
	 * 锐化图片
	 *
	 * @param   integer  锐化值，一般大约为20最佳
	 * @return  object
	 */
	public function sharpen($amount)
	{
		$this->actions['sharpen'] = max(1, min(intval($amount), 100));

		return $this;
	}

	/**
	 * 图片缩放
	 *
	 * image::NONE 强制图片宽度高度等于参数值
	 * image::WIDTH 保持比例，强制图片宽度等于$width
	 * image::HEIGHT 保持比例，强制图片高度等于$heigh
	 * image::AUTO 保持比例，根据图片宽高自动判断,图片最大宽度高度不超过$width和$height
	 * image::INVERSE 保持比例，根据图片宽高自动判断,图片最小宽度高度不小于$width或者$height
	 *
	 *
	 * @param string $image 待缩放的图片
	 * @param int $width 宽
	 * @param int $height 高
	 * @param string $master image::NONE, image::AUTO, image::WIDTH, image::HEIGHT, image::INVERSE
	 * @return string
	 */
	public function resize($width, $height, $master=null)
	{
		// 参数
		$width 	= $this->valid('width',$width);
		$height = $this->valid('height',$height);
		$master = ( $master===null ) ? image::AUTO : $this->valid('master', $master);

		$this->actions['resize'] = array(
			'width'  => $width,
			'height' => $height,
			'master' => $master,
		);

		return $this;
	}

	/**
	 * 裁剪图片到特定的宽度高度
	 *
	 * @param   integer  $width
	 * @param   integer  $height
	 * @param   integer  $top Y方向偏移, 支持参数：数字(单位px)或者 top, center, bottom
	 * @param   integer  $left X方向偏移, 支持参数：数字(单位px)或者 left, center, right
	 * @return  object
	 */
	public function crop($width, $height, $top = 0, $left = 0)
	{
		// 验证参数
		$width 	= $this->valid('width',$width);
		$height = $this->valid('height',$height);
		$top 	= $this->valid('top',$top);
		$left 	= $this->valid('left',$left);

		$this->actions['crop'] = array
		(
			'width'  => $width,
			'height' => $height,
			'top'    => $top,
			'left'   => $left,
		);

		return $this;
	}

	/**
	 * 生成特定大小的缩略图，相当于先resize，后crop
	 *
	 * @param   integer  $width
	 * @param   integer  $height
	 * @param   integer  $top Y方向偏移, 支持参数：数字(单位px)或者 top, center, bottom
	 * @param   integer  $left X方向偏移, 支持参数：数字(单位px)或者 left, center, right
	 * @return  object
	 */
	public function thumb($width, $height, $top='center', $left='center')
	{
		return $this->resize($width, $height, image::INVERSE)->crop($width, $height, $top, $left);
	}

	/**
	 * 图片加水印
	 *
	 * @param   integer  $width
	 * @param   integer  $height
	 * @param   integer  $top X偏移, 支持参数：数字(单位px)或者 top, center, bottom
	 * @param   integer  $left Y偏移, 支持参数：数字(单位px)或者 left, center, right
	 * @return  object
	 */
	public function watermark($file, $position = 'bottom right', $opacity = 100, $offsetx = 5, $offsety = 5)
	{
		//获取水印图片的信息
		$info = image::info($file);

		if ( empty($info)  )
		{
			throw new zotop_exception(t('无效的水印图片 %s', $file));
		}

		list($top,$left) = explode(' ',$position);

		//验证XY偏移
		$top 	= $this->valid('top',$top);
		$left 	= $this->valid('left',$left);

		//水印透明度和质量必须在1-100之间
		$opacity = min(max(intval($opacity), 1), 100);

		//水印属性设置
		$this->actions['watermark'] = array
		(
			'file'		=> $file,
			'width'		=> $info['width'],
			'height'	=> $info['height'],
			'top' 		=> $top,
			'left'    	=> $left,
			'offsetx' 	=> $offsetx,
			'offsety'   => $offsety,
			'opacity'   => $opacity
		);


		return $this;
	}

	/**
	 * 保存图像
	 *
	 * @param   string   $target 当值为空的时候，覆盖原图像
	 * @param   integer  $chmod 新图片的权限
	 * @param   boolean  $keep_actions 是否清空actions
	 * @return  object
	 */
	public function save($target=null, $chmod = 0644, $keep_actions = false)
	{
		if ( empty($target) )
		{
			$target = $this->file;
		}
		else
		{
			//替换目标文件中的 $dir(当前图片路径)，$name(当前图片名称，不含扩展名)， $ext(当前图片的扩展名)
			if ( strpos($target,'%dir') !==false OR strpos($target,'%name') !==false OR strpos($target,'%ext') !==false )
			{
				$pathinfo	= pathinfo($this->file);
				$target		= str_replace('%dir',$pathinfo['dirname'],$target);
				$target		= str_replace('%name',basename($pathinfo['basename'], '.'.$pathinfo['extension']),$target);
				$target		= str_replace('%ext',$pathinfo['extension'],$target);
			}

			$target = strpos($target, ZOTOP_PATH) === false ? ZOTOP_PATH.DS.$target : $target;
			$target = format::path($target);
		}

		$dir  = pathinfo($target, PATHINFO_DIRNAME);

		if ( !folder::create($dir) OR ! is_writable($dir) )
		{
			throw new zotop_exception(t('目录 %s不存在或者不可写',$dir));
		}

		//保存图像
		if ( $save = $this->driver->process($this->file, $this->info, $this->actions, $target) )
		{
			if ( $chmod !== FALSE)
			{
				@chmod($target, $chmod); //设置权限，所有者可读写
			}
		}

		if ( $keep_actions === FALSE )
		{
			$this->actions = array();
		}

		return $save;
	}

	/**
	 * 渲染图片并获得数据
	 *
	 * @param   boolean  是否清空actions
	 * @return	object
	 */
	public function render($keep_actions = FALSE)
	{
		ob_start();

		$status = $this->driver->process($this->file, $this->info, $this->actions, null, TRUE);

		if ($keep_actions === FALSE)
		{
			$this->actions = array();
		}
		return ob_get_clean();
	}

	/**
	 * 将图片输出到浏览器
	 *
	 * @param   boolean  是否清空actions
	 * @return	object
	 */
	public function output($keep_actions = FALSE)
	{
		$mime = $this->info['mime'];

		header('Content-Type: '.$mime);

		echo $this->render($keep_actions);
	}
}
?>