<?php
defined('ZOTOP') or die('No direct access allowed.');

/**
 * 图片类，Thanks for Kohana
 *
 * @copyright  (c)2009 zotop team
 * @package    zotop.ui
 * @author     zotop team
 * @license    http://zotop.com/license.html
 */
class image_gd
{
    protected $image = array();
    protected $tmp_image;

    // A transparent PNG as a string
    protected static $blank_png;
    protected static $blank_png_width;
    protected static $blank_png_height;

    /**
     * @var 驱动检测
     */
    protected static $checked = false;


    public function __construct()
    {
        if (image_gd::$checked === false)
        {
            // Make sure that GD2 is available
            if (!function_exists('gd_info')) throw new zotop_exception(t('需要GD库V2'));

            // Get the GD information
            $info = gd_info();

            // Make sure that the GD2 is installed
            if (strpos($info['GD Version'], '2.') === false)
            {
                throw new zotop_exception(t('需要GD库V2'));
            }
        }
    }

    /**
     * 执行actions中的操作
     *
     * @param   array    actions
     * @return  boolean
     */
    public function execute($actions)
    {
        foreach ($actions as $func => $args)
        {
            if (!$this->$func($args))
            {
                return false;
            }
        }
        return true;
    }

    /**
     * 处理图片
     *
     * @param   array  $image 图片参数
     * @param   array  $actions 操作集合
     * @param   string $target    存储目标文件
     * @return  boolean
     */
    public function process($image, $actions, $target, $render = false)
    {
        //存储图像
        $this->image = $image;

        //从actions获取图片质量值，默认为95
        if (isset($actions['quality']))
        {
            $quality = $actions['quality'];
            unset($actions['quality']);
        }

        //根据图片mime类型获取处理函数
        switch ($image['mime'])
        {
            case 'image/jpeg':
                $create = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
                $save = function_exists('imagejpeg') ? 'imagejpeg' : '';
                $quality = ($quality === null) ? 100 : $quality;
                break;
            case 'image/gif':
                $create = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
                $save = function_exists('imagegif') ? 'imagegif' : '';
                unset($quality);
                break;
            case 'image/png':
                $create = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
                $save = function_exists('imagepng') ? 'imagepng' : '';
                $quality = 9;
                break;
        }

        if (empty($create) or empty($save))
        {
            throw new zotop_exception(t('不支持该类型图片: %s', $image['path']));
        }

        // 创建临时图像
        $this->tmp_image = $create($image['path']);

        // 执行操作
        if ($status = $this->execute($actions))
        {
            // 防止透明度丢失
            imagealphablending($this->tmp_image, true);
            imagesavealpha($this->tmp_image, true);

            if ($render === false)
            {
                //保存图片
                $status = isset($quality) ? $save($this->tmp_image, $target, $quality) : $save($this->tmp_image, $target);
            }
            else
            {
                $status = isset($quality) ? $save($this->tmp_image, null, $quality) : $save($this->tmp_image);
            }
        }

        // 销毁临时图片
        imagedestroy($this->tmp_image);

        return $status;
    }

    public function flip($direction)
    {
        $width = imagesx($this->tmp_image);
        $height = imagesy($this->tmp_image);

        $flipped = $this->imagecreatetransparent($width, $height);

        if (($direction === image::HORIZONTAL) or ($direction === image::H))
        {
            for ($x = 0; $x < $width; $x++)
            {
                $status = imagecopy($flipped, $this->tmp_image, $x, 0, $width - $x - 1, 0, 1, $height);
            }
        }
        elseif (($direction === image::VERTICAL) or ($direction === Image::V))
        {
            for ($y = 0; $y < $height; $y++)
            {
                $status = imagecopy($flipped, $this->tmp_image, 0, $y, 0, $height - $y - 1, $width, 1);
            }
        }
        else
        {
            return true;
        }

        if ($status === true)
        {
            imagedestroy($this->tmp_image);

            $this->tmp_image = $flipped;
        }

        return $status;
    }

    public function rotate($amount)
    {
        $img = $this->tmp_image;

        $transparent = imagecolorallocatealpha($img, 255, 255, 255, 127);

        $img = imagerotate($img, 360 - $amount, $transparent, -1);

        imagecolortransparent($img, $transparent);

        if ($status = imagecopymerge($this->tmp_image, $img, 0, 0, 0, 0, imagesx($this->tmp_image), imagesy($this->tmp_image), 100))
        {
            imagealphablending($img, true);

            imagesavealpha($img, true);

            imagedestroy($this->tmp_image);

            $this->tmp_image = $img;
        }

        return $status;
    }

    public function sharpen($amount)
    {
        if (!function_exists('imageconvolution'))
        {
            throw new zotop_exception(t('不支持该方法<b>%s<b>'), __function__ );
        }

        $amount = round(abs(-18 + ($amount * 0.08)), 2);

        $matrix = array(
            array(-1,-1,-1),
            array(-1,$amount,-1),
            array(-1,-1,-1),
        );

        return imageconvolution($this->tmp_image, $matrix, $amount - 8, 0);
    }


    /**
     * resize
     * 
     * @param mixed $properties
     * @return
     */
    public function resize($properties)
    {
        $width = imagesx($this->tmp_image);
        $height = imagesy($this->tmp_image);

        if (substr($properties['width'], -1) === '%')
        {
            $properties['width'] = round($width * (substr($properties['width'], 0, -1) / 100));
        }

        if (substr($properties['height'], -1) === '%')
        {
            $properties['height'] = round($height * (substr($properties['height'], 0, -1) / 100));
        }

        empty($properties['width']) and $properties['width'] = round($width * $properties['height'] / $height);
        empty($properties['height']) and $properties['height'] = round($height * $properties['width'] / $width);

        if ($properties['master'] === Image::AUTO)
        {
            $properties['master'] = (($width / $properties['width']) > ($height / $properties['height'])) ? Image::WIDTH : Image::HEIGHT;
        }

        if ($properties['master'] === Image::INVERSE)
        {
            $properties['master'] = (($width / $properties['width']) < ($height / $properties['height'])) ? Image::WIDTH : Image::HEIGHT;
        }

        if (empty($properties['height']) or $properties['master'] === Image::WIDTH)
        {
            $properties['height'] = round($height * $properties['width'] / $width);
        }

        if (empty($properties['width']) or $properties['master'] === Image::HEIGHT)
        {
            $properties['width'] = round($width * $properties['height'] / $height);
        }

        if ($properties['width'] > $width / 2 and $properties['height'] > $height / 2)
        {
            $pre_width = $width;
            $pre_height = $height;

            $max_reduction_width = round($properties['width'] * 1.1);
            $max_reduction_height = round($properties['height'] * 1.1);

            while ($pre_width / 2 > $max_reduction_width and $pre_height / 2 > $max_reduction_height)
            {
                $pre_width /= 2;
                $pre_height /= 2;
            }

            $img = $this->imagecreatetransparent($pre_width, $pre_height);

            if ($status = imagecopyresized($img, $this->tmp_image, 0, 0, 0, 0, $pre_width, $pre_height, $width, $height))
            {
                imagedestroy($this->tmp_image);
                $this->tmp_image = $img;
            }

            $width = $pre_width;
            $height = $pre_height;
        }

        $img = $this->imagecreatetransparent($properties['width'], $properties['height']);

        if ($status = imagecopyresampled($img, $this->tmp_image, 0, 0, 0, 0, $properties['width'], $properties['height'], $width, $height))
        {
            imagedestroy($this->tmp_image);
            $this->tmp_image = $img;
        }

        return $status;
    }

    /**
     * 裁剪
     * 
     * @param mixed $properties
     * @return
     */
    public function crop($properties)
    {
        $width = imagesx($this->tmp_image);
        $height = imagesy($this->tmp_image);

        $properties = image::sanitize_geometry($properties, $width, $height);

        $img = $this->imagecreatetransparent($properties['width'], $properties['height']);

        if ($status = imagecopyresampled($img, $this->tmp_image, 0, 0, $properties['left'], $properties['top'], $width, $height, $width, $height))
        {
            imagedestroy($this->tmp_image);
            $this->tmp_image = $img;
        }

        return $status;
    }

    /**
     * 水印
     * 
     * @param mixed $properties
     * @return
     */
    public function watermark($properties)
    {
        $watermark = new image($properties['watermark']['path']);

        $overlay = imagecreatefromstring($watermark->render());

        $width = imagesx($this->tmp_image);
        $height = imagesy($this->tmp_image);

        $top = $properties['top'];
        $left = $properties['left'];

        switch ($top)
        {
            case 'top':
                $y = 0 + $properties['offsety'];
                break;
            case 'bottom':
                $y = $height - $properties['watermark']['height'] - $properties['offsety'];
                break;
            case 'center':
                $y = round(($height - $properties['watermark']['height']) / 2);
                break;
        }

        switch ($left)
        {
            case 'left':
                $x = 0 + $properties['offsetx'];
                break;
            case 'right':
                $x = $width - $properties['watermark']['width'] - $properties['offsetx'];
                break;
            case 'center':
                $x = round(($width - $properties['watermark']['width']) / 2);
                break;
        }

        if ($properties['opacity'] < 100)
        {
            $properties['opacity'] = round(abs(($properties['opacity'] * 127 / 100) - 127));

            $color = imagecolorallocatealpha($overlay, 255, 255, 255, $properties['opacity']);

            imagelayereffect($overlay, IMG_EFFECT_OVERLAY);

            imagefilledrectangle($overlay, 0, 0, $width, $height, $color);
        }

        imagealphablending($this->tmp_image, true);

        if ($status = imagecopy($this->tmp_image, $overlay, $x, $y, 0, 0, $properties['watermark']['width'], $properties['watermark']['height']))
        {
            imagedestroy($overlay);
        }

        return $status;
    }

    /**
     * 创建一个指定宽度和高度的空图片
     *
     * @param   integer   image width
     * @param   integer   image height
     * @return  resource
     */
    protected function imagecreatetransparent($width, $height)
    {
        $image = imagecreatetruecolor($width, $height);

        imagealphablending($image, false);

        imagesavealpha($image, true);

        return $image;
    }
}
?>