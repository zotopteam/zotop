<?php defined('ZOTOP') or die('No direct script access.');
/**
 * 异常处理类
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */

class zotop_exception extends Exception
{
	/**
	 * @var  array  PHP error code => human readable name
	 */
	public static $php_errors = array(
		E_ERROR              => 'Fatal Error',
		E_USER_ERROR         => 'User Error',
		E_PARSE              => 'Parse Error',
		E_WARNING            => 'Warning',
		E_USER_WARNING       => 'User Warning',
		E_STRICT             => 'Strict',
		E_NOTICE             => 'Notice',
		E_RECOVERABLE_ERROR  => 'Recoverable Error',
	);

	/**
	 * 抛出一个新的异常实例
	 *
	 *     throw new zotop_exception('Something went terrible wrong', 404);
	 *
	 * @param string $message error message
	 * @param integer $code the exception code
	 * @return  void
	 */
	public function __construct($message, $code = 0)
	{
		// Pass the message and integer code to the parent
		parent::__construct($message, (int) $code);

		// Save the unmodified code
		$this->code = $code;
	}

	/**
	 * Get a single line of text representing the exception:
	 *
	 * Error [ Code ]: Message ~ File [ Line ]
	 *
	 * @param   object  Exception
	 * @return  string
	 */
	public static function text(Exception $e)
	{
		//return sprintf('%s', strip_tags($e->getMessage()));
		return sprintf('%s [ %s ]: %s  %s [ %d ]',	get_class($e), $e->getCode(), strip_tags($e->getMessage()), debug::path($e->getFile()), $e->getLine());
	}

	/**
	 * Magic object-to-string method.
	 *
	 *     echo $exception;
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return zotop_exception::text($this);
	}
}
?>