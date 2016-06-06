<?php
defined('ZOTOP') OR die('No direct access allowed.');
/**
 * 手机或者邮箱验证码模型
 *
 * @package		system
 * @author		zotop team
 * @copyright	(c)2009-2011 zotop team
 * @license		http://zotop.com/license.html
 */
class system_model_verifycode extends model
{
	protected $pk = 'id';
	protected $table = 'verifycode';

    /**
     * 生成数字验证码
     * 
     * @param number $length 验证码长度，默认六位
     * @return string
     */
    public function make($length=6)
    {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));

        return $hash;
    }

	/**
	 * 写入验证码
	 *
	 * @param string $target 手机或者邮箱地址
	 * @param string $verifycode 验证码，如果未传入则自动生成六位数字
	 * @return mixed
	 */
	public function save($target, $verifycode='')
	{
		$verifycode = empty($verifycode) ? $this->make() : $verifycode;

		$data = array(
			'target'     => $target,
			'verifycode' => $verifycode,
			'userid'     => zotop::user('id'),
			'sendip'     => request::ip(),
			'sendtime'   => ZOTOP_TIME
		);

		return $this->insert($data);
	}

	/**
	 * 检查验证码是否有效，TODO 增加验证码有效期
	 * 
	 * @param string $target 手机或者邮箱地址
	 * @param string $verifycode 验证码
	 * @return [type]             [description]
	 */
	public function check($target, $verifycode)
	{
		return $this->where('target',$target)->where('verifycode',$verifycode)->exists();
	}
}
?>