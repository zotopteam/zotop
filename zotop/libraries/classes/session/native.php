<?php defined('ZOTOP') or die('No direct script access.');
/**
 * zotop session native
 *
 * @package		zotop
 * @author		zotop team
 * @copyright	(c)2009-2012 zotop team
 * @license		http://zotop.com/license
 */
class session_native extends session
{
	public function __construct($config)
	{
		if ( empty($config['path']) ) $config['path'] = ZOTOP_PATH_RUNTIME.DS.'session';

		if ( !folder::create($config['path']) )
		{
			throw new zotop_exception(t('The session path %s is not available', $config['path']));
		}

		ini_set('session.save_handler', 'files');

    	session_save_path($config['path']);

		parent::__construct($config);
	}

	public function register()
	{
		return true;
	}
}
?>