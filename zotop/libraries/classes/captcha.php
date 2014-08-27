<?php
defined('ZOTOP') or die('No direct access allowed.');
/**
 * 生成验证码
 *
 * @author chenzhouyu
 * 类用法
 * $captcha = new captcha();
 * $captcha->create();

 * //验证
 * zotop::session('captcha') = $_POST['captcha'];
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class captcha
{
    //验证码的宽度
    public $width = 90;

    //验证码的高
    public $height = 28;

    //设置字体的地址
    private $font;

    //设置字体色
    public $font_color;

    //设置随机生成因子
    public $charset = 'abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ23456789';

    //设置背景色
    public $background = '#ffffff';

    //生成验证码字符数
    public $length = 4;

    //字体大小
    public $font_size = 15;

    //验证码
    private $code;

    //图片内存
    private $img;

    //文字X轴开始的地方
    private $x_start;

    function __construct()
    {
        $this->font = ZOTOP_PATH_LIBRARIES . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'font' . DIRECTORY_SEPARATOR . 'elephant.ttf';
    }

    /**
     * 生成随机验证码。
     */
    protected function creat_code()
    {
        $code = '';
        $charset_len = strlen($this->charset) - 1;

        for ($i = 0; $i < $this->length; $i++)
        {
            $code .= $this->charset[rand(1, $charset_len)];
        }
        $this->code = $code;
    }

    /**
     * 获取验证码
     */
    public function code()
    {
        return strtolower($this->code);
    }

    /**
     * 生成图片
     */
    public function create()
    {
        $code = $this->creat_code();

        $this->img = imagecreatetruecolor($this->width, $this->height);

        if (!$this->font_color)
        {
            $this->font_color = imagecolorallocate($this->img, rand(0, 156), rand(0, 156), rand(0, 156));
        }
        else
        {
            $this->font_color = imagecolorallocate($this->img, hexdec(substr($this->font_color, 1, 2)), hexdec(substr($this->font_color, 3, 2)), hexdec(substr($this->font_color, 5, 2)));
        }

        //设置背景色
        $background = imagecolorallocate($this->img, hexdec(substr($this->background, 1, 2)), hexdec(substr($this->background, 3, 2)), hexdec(substr($this->background, 5, 2)));

        //画一个柜形，设置背景颜色。
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $background);
        imagecolortransparent($this->img, $background); //把图片中背景色设置为透明色

        //设置字体颜色等
        $this->creat_font();
        $this->creat_line();
        $this->output();

        //返回代码，可以存储在session中
        return $this->code();
    }

    /**
     * 生成文字
     */
    private function creat_font()
    {
        $x = $this->width / $this->length;
        for ($i = 0; $i < $this->length; $i++)
        {
            imagettftext($this->img, $this->font_size, rand(-30, 30), $x * $i + rand(0, 5), $this->height / 1.4, $this->font_color, $this->font, $this->code[$i]);

            if ($i == 0) $this->x_start = $x * $i + 5;
        }
    }

    /**
     * 画线
     */
    private function creat_line()
    {
        imagesetthickness($this->img, 3);

        $xpos = ($this->font_size * 2) + rand(-5, 5);
        $width = $this->width / 2.66 + rand(3, 10);
        $height = $this->font_size * 2.14;

        if (rand(0, 100) % 2 == 0)
        {
            $start = rand(0, 66);
            $ypos = $this->height / 2 - rand(10, 30);
            $xpos += rand(5, 15);
        }
        else
        {
            $start = rand(180, 246);
            $ypos = $this->height / 2 + rand(10, 30);
        }

        $end = $start + rand(75, 110);

        imagearc($this->img, $xpos, $ypos, $width, $height, $start, $end, $this->font_color);

        if (rand(1, 75) % 2 == 0)
        {
            $start = rand(45, 111);
            $ypos = $this->height / 2 - rand(10, 30);
            $xpos += rand(5, 15);
        }
        else
        {
            $start = rand(200, 250);
            $ypos = $this->height / 2 + rand(10, 30);
        }

        $end = $start + rand(75, 100);

        imagearc($this->img, $this->width * .75, $ypos, $width, $height, $start, $end, $this->font_color);
    }

    /**
     * 输出图片，并存储验证码
     */
    private function output()
    {
        header("content-type:image/png\r\n");

		// 输出图片
        imagepng($this->img);
        imagedestroy($this->img);

		// 存储数据
		zotop::session('captcha',$this->code());

    }

	// 检查验证码
	public static function check($captcha='')
	{
		$captcha = empty($captcha) ? $_REQUEST['captcha'] : $captcha;

		if ( zotop::session('captcha') == $captcha )
		{
			return true;
		}

		return false;
	}
}
